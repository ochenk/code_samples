<?php

namespace Drupal\scca_pricing\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Routing\CurrentRouteMatch;


/**
 * Provides a 'SCCAPricingItemsSearch' block.
 *
 * @Block(
 *  id = "scca_pricing_items_search",
 *  admin_label = @Translation("SCCA Pricing Items Search Block"),
 * )
 */
class SCCAPricingItemsSearch extends BlockBase implements ContainerFactoryPluginInterface {

  protected $entityTypeManager;
  protected $routeMatch;


  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('current_route_match')
    );
  }

  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManager $entityTypeManager, CurrentRouteMatch $routeMatch) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entityTypeManager;
    $this->routeMatch = $routeMatch;

  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $vars = [];

    return [
      '#theme' => 'scca_pricing_search',
      '#vars' => $vars,
      '#cache' => ['contexts' => ['url.path']],
    ];
  }



}


