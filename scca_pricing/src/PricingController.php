<?php

namespace Drupal\scca_pricing;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\scca_pricing\Entity\PricingEntity;
use Drupal\search_api\Entity\Index;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PricingController.
 */
class PricingController extends ControllerBase {

  protected $entityTypeManager;

  protected $routeMatch;

  protected $request;

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('current_route_match'),
      $container->get('request_stack'),
    );
  }

  public function __construct(EntityTypeManager $entityTypeManager, CurrentRouteMatch $routeMatch, RequestStack $request) {
    $this->entityTypeManager = $entityTypeManager;
    $this->routeMatch = $routeMatch;
    $this->request = $request;
  }


  public function deletePricing($ids) {
    $storage_handler = \Drupal::entityTypeManager()->getStorage('pricing_item');
    $entities = $storage_handler->loadMultiple($ids);
    $storage_handler->delete($entities);
  }

  public function insertPricing($pricingBatch, $category, $formCat, $crosswalk) {
    foreach ($pricingBatch as $pricing) {

      $cat = "Miscellaneous";
      foreach ($crosswalk as $row) {
        if ($pricing[0] >= $row[0] && $pricing[0] <= $row[1]) {
          $cat = $row[2];
        }
      }

      $subcat = is_numeric($pricing[0]) ? trim($cat) : "Miscellaneous";
      $price = trim(str_replace(['$', ',', '*'], "", $pricing[2]));
      $price = number_format(floatval($price), 2);
      $price = "$" . strval($price);
      $node = PricingEntity::create([
        'type' => 'pricing_item',
        'pricing_cpt_code' => $pricing[0],
        'pricing_category' => $formCat,
        'pricing_description' => trim($pricing[1]),
        'pricing_price' => $price,
        'pricing_subcategory' => $subcat,
      ]);
      $node->save();
    }
  }


  public function searchResults() {

    $getVars = $this->request->getCurrentRequest()->query->all();
    $vars = [];

    // SOLR search
    $index = Index::load('pricing_index');
    $query = $index->query();

    $query->keys($getVars['k']);

    $results = $query->execute();

    $items = $results->getResultItems();

    $vars['results'] = NULL;

    if (count($items) > 0) {
      foreach ($items as $item) {
        $result = $item->getOriginalObject()->getEntity();
        $vars['results'][] = [
          $result->pricing_cpt_code->value,
          $result->pricing_description->value,
          $result->pricing_price->value,
        ];
        unset($result);
      }
    }

    $build = [
      '#theme' => 'scca_pricing_search_results',
      '#vars' => $vars,
      '#cache' => ['contexts' => ['url.path']],
      '#attached' => [
        'library' => [
          'scca_pricing/scca_pricing.csv',
        ],
      ],
    ];


    $response = new Response();
    $output = \Drupal::service('renderer')->renderRoot($build);
    $response->setContent($output);
    return $response;

  }


}
