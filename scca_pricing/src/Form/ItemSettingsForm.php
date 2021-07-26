<?php
/**
 * @file
 * Contains \Drupal\scca_pricing\Form\ItemSettingsForm.
 */

namespace Drupal\scca_pricing\Form;

use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\File\FileSystem;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Session\AccountProxy;
use Drupal\file\FileUsage\DatabaseFileUsageBackend;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class PricingSettingsForm.
 *
 * @package Drupal\scca_pricing\Form
 *
 * @ingroup pricing
 */
class ItemSettingsForm extends FormBase {


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
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'pricing_item_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {


    $nids = $this->entityQuery->get('pricing_item')
      ->execute();
    $deletebatch = array(
      'title' => t('Deleting pricing items...'),
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


  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['pricing_item_settings']['#markup'] = 'Settings form for Pricing Items. Manage field settings here.';

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Delete all pricing items'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

}
