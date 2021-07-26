<?php

namespace Drupal\deam_site_alert\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Render\Renderer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Database\Connection;
use Drupal\block_content\Entity\BlockContent;

/**
 * Manages the site-wise alert box.
 *
 * @package Drupal\deam_site_alert\Controller
 */
class DEAMBlocksAlertController extends ControllerBase {

  /**
   * Injected render service.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * The cache backend.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cacheBackend;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The database connection from which to read route information.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * DEAMBlocksAlertController constructor.
   *
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache.
   * @param \Drupal\Core\Render\Renderer $renderer
   *   The renderer service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Entity Type manager.
   * @param \Drupal\Core\Database\Connection $connection
   *   Database connection class.
   */
  public function __construct(CacheBackendInterface $cache_backend, Renderer $renderer, EntityTypeManagerInterface $entity_type_manager, Connection $connection) {
    $this->entityTypeManager = $entity_type_manager;
    $this->renderer = $renderer;
    $this->cacheBackend = $cache_backend;
    $this->connection = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('cache.default'),
      $container->get('renderer'),
      $container->get('entity_type.manager'),
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getBlockAction() {
    $content = '';
    // If cached.
    $alert_cache = $this->cacheBackend->get('deam_blocks_alert');

    if (!empty($alert_cache)) {
      $blockInstance = $alert_cache->data;
    }
    else {
      $query = $this->connection->select('block_content', 'b');
      $query->fields('b', ['id']);
      $query->condition('type', 'alert');
      $query->range(0, 1);
      $block_uuid = $query->execute()->fetchCol();
      $blockInstance = NULL;
      if (isset($block_uuid[0])) {
        $blockInstance = BlockContent::load($block_uuid[0]);
        $this->cacheBackend
          ->set('deam_blocks_alert', $blockInstance);
      }

    }

    if (!empty($blockInstance)) {
      $enabled = [];
      if ($blockInstance->hasField('field_enabled')) {
        $first = $blockInstance->get('field_enabled')->first()->getValue();
        if (isset($first['value'])) {
          $enabled = $first['value'];
        }
      }
      if (!empty($enabled)) {
        $view_builder = $this->entityTypeManager->getViewBuilder($blockInstance->getEntityTypeId());
        $bodyRenderable = NULL;
        if ($blockInstance->hasField('body')) {
          $body = $blockInstance->get('body');

          $bodyRenderable = $view_builder->viewField($body, [
            'label' => 'hidden',
            'type' => 'text_trimmed',
            'settings' => ['trim_length' => 600],
          ]);

        }

        $fieldLinkRenderable = NULL;
        if ($blockInstance->hasField('field_destination_link')) {
          $fieldLinkRenderable = $blockInstance->get('field_destination_link');

          $fieldLinkRenderable = $view_builder->viewField($fieldLinkRenderable, [
            'label' => 'hidden',
            'type' => 'deam_link_static',
            'settings' => [
              'static_text' => $this->t('More Details'),
            ],
          ]);

        }

        $build = [
          '#theme' => 'deam_site_alert',
          '#content' => $bodyRenderable,
          '#link' => $fieldLinkRenderable,
        ];
        $content = render($build);
      }
    }
    $md5_hash = md5($content);
    $this->cacheBackend
      ->set('deam_blocks_alert_md5_hash', $md5_hash);
    return new JsonResponse([
      'block' => $content,
    ], 200, ['Cache-Control' => 'public, max-age=0']);
  }

  /**
   * Returns a block status to check if it was updated.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Json response with status.
   */
  public function getStatus() {
    $md5_block_hash = $this->cacheBackend->get('deam_blocks_alert_md5_hash');
    $response = 0;
    if (!empty($md5_block_hash)) {
      $response = $md5_block_hash->data;
    }
    return new JsonResponse([
      'status' => $response,
    ], 200, ['Cache-Control' => 'public, max-age=0']);
  }

}
