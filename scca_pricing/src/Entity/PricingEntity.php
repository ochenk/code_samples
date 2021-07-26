<?php
/**
 * @file
 * Contains \Drupal\scca_pricing\Entity\PricingEntity.
 */

namespace Drupal\scca_pricing\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;
use Drupal\Core\Entity\EntityChangedTrait;

/**
 * Defines the PricingEntity entity.
 *
 * @ingroup pricing
 *
 *
 * @ContentEntityType(
 *   id = "pricing_item",
 *   label = @Translation("Pricing Item entity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\scca_pricing\Entity\Controller\ItemListBuilder",
 *     "form" = {
 *       "add" = "Drupal\scca_pricing\Form\ItemForm",
 *       "edit" = "Drupal\scca_pricing\Form\ItemForm",
 *       "delete" = "Drupal\scca_pricing\Form\ItemDeleteForm",
 *     },
 *     "views_data" = "Drupal\views\EntityViewsData",
 *   },
 *   list_cache_contexts = { "user" },
 *   base_table = "pricing_item",
 *   admin_permission = "administer pricing_item entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "user_id" = "user_id",
 *     "created" = "created",
 *     "changed" = "changed",
 *     "cpt_code" = "cpt_code",
 *     "category" = "category",
 *     "subcategory" = "subcategory",
 *   },
 *   links = {
 *     "canonical" = "/pricing_item/{pricing_item}",
 *     "edit-form" = "/pricing_item/{pricing_item}/edit",
 *     "delete-form" = "/pricing_item/{pricing_item}/delete",
 *     "collection" = "/pricing_item/list"
 *   },
 *   field_ui_base_route = "entity.pricing_item.item_settings",
 * )
 */
class PricingEntity extends ContentEntityBase {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   *
   * When a new entity instance is added, set the user_id entity reference to
   * the current user as the creator of the instance.
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    // Default author to current user.
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Pricing Item entity.'))
      ->setReadOnly(TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the Pricing Item entity.'))
      ->setReadOnly(TRUE);

    $fields['pricing_cpt_code'] = BaseFieldDefinition::create('string')
      ->setLabel(t('CPT Code'))
      ->setSettings([
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'none',
        'type' => 'string',
        'weight' => -6,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -6,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['pricing_description'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Description'))
      ->setSettings([
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'none',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['pricing_category'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Category'))
      ->setSettings([
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'none',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['pricing_subcategory'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Subcategory'))
      ->setSettings([
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'none',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['pricing_price'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Price'))
      ->setSettings([
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'none',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);


    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
