<?php
/**
 * @file
 * Contains \Drupal\sfal_nat_map\Plugin\Block\sfalNatMapBlock.
 */

namespace Drupal\sfal_nat_map\Plugin\Block;

use Drupal\Core\Block\BlockBase;


/**
 * Provides a 'SFAL National Map' block.
 *
 * @Block(
 *   id = "sfalNatMap",
 *   admin_label = @Translation("SFAL National Map"),
 *   category = @Translation("SFAL")
 * )
 */
class sfalNatMapBlock extends BlockBase {

  /**
   * Builds block.
   *
   * This is light because all display logic is in module's js and theme's template files.
   *
   * @return array
   */
  public function build() {
    return [
      '#theme' => 'homeNatMap',
      '#attached' => [
        'library' => [
          'sfal_nat_map/D3',
          'sfal_nat_map/topojson',
          'sfal_nat_map/sfalNatMap',
        ],
      ],
    ];
  }
}