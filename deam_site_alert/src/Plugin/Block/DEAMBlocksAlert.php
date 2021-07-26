<?php

namespace Drupal\deam_site_alert\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\State\StateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'DEAMBlocksAlert' block.
 *
 * @Block(
 *  id = "deam_site_alert",
 *  admin_label = @Translation("DEAM Site Alert"),
 *  category = @Translation("DEAM Blocks")
 * )
 */
class DEAMBlocksAlert extends BlockBase implements ContainerFactoryPluginInterface {


  /**
   * Stores the state storage service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, StateInterface $state) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->state = $state;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('state')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    if (strlen($this->state->get('deam_alert_title'))) {
      $alert = [
        'title' => $this->state->get('deam_alert_title'),
        'message' => $this->state->get('deam_alert_message'),
        'type' => $this->state->get('deam_alert_type'),
      ];
      if (strlen($this->state->get('deam_alert_link_url'))) {
        $alert['link'] = '<a href="' . $this->state->get('deam_alert_link_url') . '">' . $this->state->get('deam_alert_link_title') . '</a>';

      }
      return [
        '#theme' => 'deam-site-alert',
        '#alert' => $alert,
      ];
    }
    else {
      return [];

    }
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'access content');
  }

}
