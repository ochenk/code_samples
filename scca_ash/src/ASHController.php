<?php

namespace Drupal\scca_ash;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Datetime\DateFormatter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ASHController.
 */
class ASHController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * @var $entityTypeManager \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * @var $dateFormat \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormat;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('date.formatter')
    );
  }

  /**
   * ASHController constructor.
   *
   * @param EntityTypeManager $entityTypeManager
   * @param DateFormatter $dateFormat
   */
  public function __construct(EntityTypeManager $entityTypeManager, DateFormatter $dateFormat) {
    $this->entityTypeManager = $entityTypeManager;
    $this->dateFormat = $dateFormat;
  }

  /**
   * {@inheritdoc}
   */
  public function index() {
    $vars = [];
    $build = [
      'page' => [
        '#theme' => 'scca_ash_index',
        '#var' => $vars,
      ],
    ];
    return $build;

  }

  /**
   * @param null $post
   *      supports blog post nid or slug.
   *
   * @return array|null
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function blogPost($post = NULL) {
    if ($post) {
      if (is_numeric($post)) {
        $post_node = $this->entityTypeManager->getStorage('node')->load($post);
      }
      else {
        $nodes = $this->entityTypeManager
          ->getStorage('node')
          ->loadByProperties(['field_conference_blog_url' => '/ash/news/' . $post]);
        $post_node = reset($nodes);
      }
      return [
        '#theme' => 'scca_ash_blog_post',
        '#post' => $post_node,
        '#cache' => ['max-age' => 0],
      ];
    }
    else {
      return NULL;
    }
  }

  /**
   * Load and parse conference abstract.
   * Returns json. Used as a jQuery ajax call to dynamically load data for
   * modal.
   *
   * @param NULL $id
   *      abstract nid.
   *
   * @return null|JsonResponse
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function abstractData($id = NULL) {
    if ($id) {
      $abstract = $this->entityTypeManager->getStorage('node')->load($id);

      $response['id'] = $id;
      $response['title'] = $abstract->title->value;
      $response['subtitle'] = $abstract->field_conf_abs_subtitle->value;
      $response['full_abstract'] = $abstract->field_conf_abs_full_abstract->value;
      if ($abstract->field_conf_abs_image->entity) {
        $response['image'] = file_create_url($abstract->field_conf_abs_image->entity->uri->value);
      }
      $response['author'] = $abstract->field_conf_abs_author_name->value;
      $response['datetime'] = $this->dateFormat->format(
        strtotime($abstract->field_conf_abs_datetime->value), 'custom', 'l F j, Y'
      );
      $response['location'] = $abstract->field_conf_abs_location->value;
      $response['poster'] = $abstract->field_conf_abs_poster->value;
      $response['subtitle'] = $abstract->field_conf_abs_subtitle->value;
      $response['summary'] = $abstract->field_conf_abs_summary->value;
      $response['type'] = $abstract->field_conf_abs_type->value;
      if ($abstract->field_conf_abs_poster) {
        $response['video'] = file_create_url($abstract->field_conf_abs_poster->entity->uri->value);
      }
      return new JsonResponse(json_encode($response));

    }
    else {
      return NULL;
    }
  }

  /**
   * Theme call for who-is-scca page.
   *
   * @return array
   */
  public function whoIsSCCA() {
    return [
      '#theme' => 'scca_ash_who_is',
      //'#cache' => ['max-age' => 0],
    ];
  }

  /**
   * Theme call for careers page.
   *
   * @return array
   */
  public function careers() {
    return [
      '#theme' => 'scca_ash_who_is',
      //'#cache' => ['max-age' => 0],
    ];
  }

  /**
   * Theme call for press page.
   *
   * @return array
   */
  public function press() {
    return [
      '#theme' => 'scca_ash_who_is',
      //'#cache' => ['max-age' => 0],
    ];
  }
}