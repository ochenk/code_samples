<?php
/**
 * @file
 * Contains Drupal\sfal_nat_map\Controller\apiController.
 */

namespace Drupal\sfal_nat_map\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Cache\CacheBackendInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class apiController
 *
 * @package Drupal\sfal_nat_map\Controller
 */
class apiController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * @var $db \Drupal\Core\Database\Connection
   */
  protected $db;

  /**
   * @var $cache \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cache;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('cache.default')
    );
  }

  /**
   * apiController constructor.
   *
   * @param Connection $db
   * @param CacheBackendInterface $cache
   */
  public function __construct(Connection $db, CacheBackendInterface $cache) {
    $this->db = $db;
    $this->cache = $cache;
  }

  /**
   * Display the markup.
   *
   * @return Response
   */
  public function index($year, $category, $subcategory) {

    $cacheName = "mapQuery-" . $year . "-" . $category . "-" . $subcategory;
    $cache = $this->cache->get($cacheName);
    if ($cache) {
      $response = new Response();
      $response->setContent($cache->data);
      $response->headers->set('Content-Type', 'application/json');
      return $response;

    }
    else {

      $result = $this->fetchMapApiData($year, $category, $subcategory);
      $output = [];
      foreach ($result as $item) {
        $view = [
          'title' => $item->lawtitle,
          'state' => $item->state,
          'state_code' => $item->stateCode,
          'provision' => $item->provision,
          'description' => $item->description,
          'category' => $item->category,
        ];
        $output[$item->state]['title'] = $item->state;
        $output[$item->state]['field_state_code'] = $item->stateCode;
        $output[$item->state]['view'][] = $view;
      }

      $encodedOutput = json_encode($output);

      $this->cache->set($cacheName, $encodedOutput);

      $response = new Response();
      $response->setContent($encodedOutput);
      $response->headers->set('Content-Type', 'application/json');
      return $response;

    }
  }

  /**
   * Query api data.
   *
   * @param null $year
   * @param null $category
   * @param null $subcategory
   *
   * @return mixed
   */
  private function fetchMapApiData($year = NULL, $category = NULL, $subcategory = NULL) {
    $db = $this->db;
    $query = $db->select('node_field_data', 's');
    $query->addField('s', 'nid', 'nid');
    $query->addField('s', 'title', 'state');
    $query->addField('c', 'field_state_code_value', 'stateCode');
    $query->addField('l', 'title', 'lawtitle');
    $query->addField('p', 'title', 'provision');
    $query->addField('pct', 'name', 'category');
    $query->addField('pd', 'field_provision_description_value', 'description');
    $query->addField('pc', 'field_provision_category_target_id', 'pcid');
    $query->addField('psc', 'field_provision_subcategory_target_id', 'pscid');

    $query->join('node__field_state_code', 'c', 'c.entity_id = s.nid');
    $query->join('node__field_law_state', 'ls', 'ls.field_law_state_target_id = s.nid');
    $query->join('node_field_data', 'l', 'l.nid = ls.entity_id');
    $query->join('node__field_law_year', 'ly', 'ly.entity_id = l.nid');
    $query->join('node__field_law_provision', 'lp', 'lp.entity_id = l.nid');
    $query->join('node_field_data', 'p', 'p.nid = lp.field_law_provision_target_id');
    $query->join('node__field_provision_category', 'pc', 'pc.entity_id = p.nid');
    $query->join('node__field_provision_subcategory', 'psc', 'psc.entity_id = p.nid');
    $query->join('taxonomy_term_field_data', 'pct', 'pct.tid = pc.field_provision_category_target_id');
    $query->join('node__field_provision_description', 'pd', 'pd.entity_id = p.nid');

    $query->condition('s.type', 'states');
    $query->condition('s.status', '1');

    if (is_numeric($year)) {
      $query->condition('ly.field_law_year_value', $year);
    }
    if (is_numeric($category)) {
      $query->condition('pc.field_provision_category_target_id', $category);
    }
    if (is_numeric($subcategory)) {
      $query->condition('psc.field_provision_subcategory_target_id', $subcategory);
    }

    $query->orderBy('s.title', 'ASC');
    $query->orderBy('p.title', 'ASC');

    $result = $query->execute();
    return $result->fetchAll();
  }

}