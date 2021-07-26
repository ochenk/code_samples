<?php
/**
 * @file
 * Contains Drupal\scca_pricing\Form\TermForm.
 */

namespace Drupal\scca_pricing\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the pricing_item entity edit forms.
 *
 * @ingroup pricing
 */
class ItemForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\dictionary\Entity\Term */
    $form = parent::buildForm($form, $form_state);
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    // Redirect to term list after save.
    $form_state->setRedirect('entity.pricing_item.collection');
    $entity = $this->getEntity();
    $entity->save();
  }

}
