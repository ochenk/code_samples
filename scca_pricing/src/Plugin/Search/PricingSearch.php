<?php

namespace Drupal\scca_pricing\Plugin\Search;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Database\Connection;
use Drupal\Core\DependencyInjection\DeprecatedServicePropertyTrait;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessibleInterface;
use Drupal\search\Plugin\SearchPluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Executes a keyword search for pricing items against the {procing_item} database table.
 *
 * @SearchPlugin(
 *   id = "pricing_search",
 *   title = @Translation("Pricing Items")
 * )
 */
class PricingSearch extends SearchPluginBase implements AccessibleInterface {
  use DeprecatedServicePropertyTrait;

  /**
   * {@inheritdoc}
   */
  protected $deprecatedProperties = ['entityManager' => 'entity.manager'];

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('database'),
      $container->get('entity_type.manager'),
      $container->get('module_handler'),
      $container->get('current_user'),
      $configuration,
      $plugin_id,
      $plugin_definition
    );
  }

  /**
   * Creates a PricingSearch object.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(Connection $database, EntityTypeManagerInterface $entity_type_manager, ModuleHandlerInterface $module_handler, AccountInterface $current_user, array $configuration, $plugin_id, $plugin_definition) {
    $this->database = $database;
    $this->entityTypeManager = $entity_type_manager;
    $this->moduleHandler = $module_handler;
    $this->currentUser = $current_user;
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public function access($operation = 'view', AccountInterface $account = NULL, $return_as_object = FALSE) {
    $result = AccessResult::allowedIf(!empty($account) && $account->hasPermission('view pricing_item entity'))->cachePerPermissions();
    return $return_as_object ? $result : $result->isAllowed();
  }

  /**
   * {@inheritdoc}
   */
  public function execute() {
    $results = [];
    if (!$this->isSearchExecutable()) {
      return $results;
    }

    // Process the keywords.
    $keys = $this->keywords;
    // Escape for LIKE matching.
    $keys = $this->database->escapeLike($keys);
    // Replace wildcards with MySQL/PostgreSQL wildcards.
    $keys = preg_replace('!\*+!', '%', $keys);

    // Run the query to find matching pricing items.
    $query = $this->database
      ->select('pricing_item', 'pi')
      ->extend('Drupal\Core\Database\Query\PagerSelectExtender');
    $query->fields('pi', ['id']);
    $query->condition($query->orConditionGroup()
      ->condition('pricing_cpt_code', '%' . $keys . '%', 'LIKE')
      ->condition('pricing_description', '%' . $keys . '%', 'LIKE')
    );

    $ids = $query
      ->execute()
      ->fetchCol();
    $items = $this->entityTypeManager->getStorage('pricing_item')->loadMultiple($ids);

    foreach ($items as $item) {
      $result = [
        'cpt_code' => $item->pricing_cpt_code,
        'description' => $item->pricing_description,
      ];
      $results[] = $result;
    }

    return $results;
  }

  /**
   * {@inheritdoc}
   */
  public function getHelp() {
    $help = [
      'list' => [
        '#theme' => 'item_list',
        '#items' => [
          $this->t('Pricing Items search looks for matches with the CPT code or description.'),
        ],
      ],
    ];

    return $help;
  }

}
