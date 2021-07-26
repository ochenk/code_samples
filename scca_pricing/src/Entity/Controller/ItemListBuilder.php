<?php

/**
 * @file
 * Contains \Drupal\scca_pricing\Entity\Controller\ItemListBuilder.
 */

namespace Drupal\scca_pricing\Entity\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Routing\UrlGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a list controller for dictionary_term entity.
 *
 * @ingroup pricing
 */
class ItemListBuilder extends EntityListBuilder {

  /**
   * The url generator.
   *
   * @var \Drupal\Core\Routing\UrlGeneratorInterface
   */
  protected $urlGenerator;

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('entity.manager')->getStorage($entity_type->id()),
      $container->get('url_generator')
    );
  }

  /**
   * Constructs a new DictionaryTermListBuilder object.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   * The entity type term.
   * @param \Drupal\Core\Entity\EntityStorageInterface $storage
   * The entity storage class.
   * @param \Drupal\Core\Routing\UrlGeneratorInterface $url_generator
   * The url generator.
   */
  public function __construct(EntityTypeInterface $entity_type, EntityStorageInterface $storage, UrlGeneratorInterface $url_generator) {
    parent::__construct($entity_type, $storage);
    $this->urlGenerator = $url_generator;
  }

  /**
   * {@inheritdoc}
   */
  public function render() {
    $build['description'] = array(
      '#markup' => $this->t('These are fieldable entities. You can manage the fields on the <a href="@adminlink">Pricing Item admin page</a>.', array(
        '@adminlink' => $this->urlGenerator->generateFromRoute('entity.pricing_item.item_settings'),
      )),
    );
    $build['table'] = parent::render();
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('ID');
    $header['cpt_code'] = $this->t('CPT Code');
    $header['category'] = $this->t('Category');
    $header['subcategory'] = $this->t('Subcategory');
    $header['description'] = $this->t('Description');
    $header['price'] = $this->t('Price');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\scca_pricing\Entity\PricingItem */
    $row['id'] = $entity->id();
    $row['pricing_cpt_code'] = $entity->pricing_cpt_code->value;
    $row['pricing_category'] = $entity->pricing_category->value;
    $row['pricing_subcategory'] = $entity->pricing_subcategory->value;
    $row['pricing_description'] = $entity->pricing_description->value;
    $row['pricing_price'] = $entity->pricing_price->value;
    return $row + parent::buildRow($entity);
  }

}
