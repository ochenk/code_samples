<?php
/**
 * @file
 * Contains Drupal\scca_pricing\Form\UploadPricings.
 */

namespace Drupal\scca_pricing\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\Core\Entity\EntityTypeManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Session\AccountProxy;
use Drupal\file\FileUsage\DatabaseFileUsageBackend;
use Drupal\Core\File\FileSystem;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\File\FileSystemInterface;

define('CATEGORIES', [
  "outpatient" => "Outpatient",
  "outpatient-pharmacy" => "Outpatient - Pharmacy",
  "outpatient-supplies" => "Outpatient - Supplies and Services",
  "inpatient-room" => "Inpatient - Room and Board",
  "inpatient-charges" => "Inpatient - Tiered Charges",
  "inpatient-other" => "Inpatient - Other Charges",
]);

class UploadPricings extends ConfigFormBase {

  /**
   * @var $entityTypeManager \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * @var \Drupal\Core\Entity\Query\QueryFactory|\Drupal\Core\Entity\Query\QueryInterface
   */
  protected $entityQuery;

  /**
   * @var \Drupal\Core\Session\AccountProxy
   */
  protected $currentUser;

  /**
   * @var \Drupal\file\FileUsage\DatabaseFileUsageBackend
   */
  protected $fileUsage;

  /**
   * @var \Drupal\Core\File\FileSystem
   */
  protected $fileSystem;

  /**
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *
   * @return \Drupal\Core\Form\ConfigFormBase|\Drupal\scca_pricing\Form\UploadPricings
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('entity.query'),
      $container->get('current_user'),
      $container->get('file.usage'),
      $container->get('file_system'),
      $container->get('messenger')
    );
  }

  /**
   * UploadPricings constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManager $entityTypeManager
   * @param \Drupal\Core\Entity\Query\QueryFactory $entityQuery
   * @param \Drupal\Core\Session\AccountProxy $current_user
   * @param \Drupal\file\FileUsage\DatabaseFileUsageBackend $fileUsage
   * @param \Drupal\Core\File\FileSystem $fileSystem
   */
  public function __construct(EntityTypeManager $entityTypeManager,
                              QueryFactory $entityQuery,
                              AccountProxy $current_user,
                              DatabaseFileUsageBackend $fileUsage,
                              FileSystem $fileSystem,
                              MessengerInterface $messenger) {
    $this->entityTypeManager = $entityTypeManager;
    $this->entityQuery = $entityQuery;
    $this->currentUser = $current_user;
    $this->fileSystem = $fileSystem;
    $this->messenger = $messenger;
  }


  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'scca_pricing.adminsettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'scca_pricings_upload_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $validators = [
      'file_validate_extensions' => ['csv'],
    ];

    $form['category'] = [
      '#type' => 'select',
      '#options' => CATEGORIES,
      '#title' => $this->t('Category'),
    ];
    $form['pricings_upload'] = [
      '#type' => 'managed_file',
      '#name' => 'pricings_upload',
      '#title' => t('File'),
      '#size' => 20,
      '#description' => t('CSV format only.<br>Format should be [cpt code, description, price]'),
      '#upload_validators' => $validators,
      '#upload_location' => 'public://',
      '#required' => TRUE,
    ];
    $form['file_only'] = [
      '#type' => 'checkbox',
      '#name' => 'file_only',
      '#title' => $this->t('Only upload file'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $orig_category = CATEGORIES[$form_state->getValue('category')];
    $category = $form_state->getValue('category');

    $file = File::load($form_state->getValue('pricings_upload')[0]);
    $file->setPermanent();
    $file->save();

    if (!$form_state->getValue('file_only')) {

      // Delete all current pricing items of matching category.
      $nids = $this->entityQuery->get('pricing_item')
        ->condition('pricing_category', $form_state->getValue('category'))
        ->execute();
      $deletebatch = array(
        'title' => t('Updating pricing items...'),
        'operations' => [],
        'init_message'     => t('Commencing'),
        'progress_message' => t('Deleted @current out of @total batches.'),
        'error_message'    => t('An error occurred during deleting pricing items'),
      );
      $deleteBatches = array_chunk($nids,20);
      foreach ($deleteBatches as $deleteBatch) {
        $deletebatch['operations'][] = ['\Drupal\scca_pricing\PricingController::deletePricing',[$deleteBatch]];
      }
      drupal_set_message("Deleted Pricing Items");
      batch_set($deletebatch);

      // Load local pricing category crosswalk.
      $crosswalk = [];
      if (file_exists(drupal_get_path('module', 'scca_pricing') . '/data/' . strtolower($category) . '_codes.csv')) {
        $crosswalk = array_map(
          'str_getcsv',
          file(drupal_get_path('module', 'scca_pricing') . '/data/' . strtolower($category) . '_codes.csv')
        );
      }

      // Create Pricing Item nodes.

      $batch = array(
        'title' => t('Updating Pricing Items...'),
        'operations' => [],
        'init_message'     => t('Commencing'),
        'progress_message' => t('Inserted @current out of @total batches.'),
        'error_message'    => t('An error occurred during inserting pricing items'),
      );

      $pricings = array_map('str_getcsv', file($file->get('uri')->value));

      $pricingBatches = array_chunk($pricings,50);

      foreach ($pricingBatches as $pricingBatch) {
        $batch['operations'][] = ['\Drupal\scca_pricing\PricingController::insertPricing',[
          $pricingBatch,
          $category,
          $form_state->getValue('category'),
          $crosswalk
        ]];
      }
      drupal_set_message("Imported Pricing Items");
      batch_set($batch);
    }

    $directory = 'public://';
    $new_filename = strtolower($category) . ".csv";
    file_move($file, $directory . $new_filename, FileSystemInterface::EXISTS_REPLACE);
    $file_usage = \Drupal::service('file.usage');
    $file_usage->add($file, 'file', 'file', $file->id());

    $this->messenger->addMessage("Upload of " . $orig_category . " pricing items completed.");
  }


  /**
   * Returns subcategory name based on CPT code within range.
   *
   * @param \Drupal\scca_pricing\Form\int $cpt
   * @param array $crosswalk
   *
   * @return string
   */
  private function getSubCat(int $cpt, array $crosswalk) {
    foreach ($crosswalk as $row) {
      if ($cpt >= $row[0] && $cpt <= $row[1]) {
        return $row[2];
      }
    }
    return "Miscellaneous";
  }

}
