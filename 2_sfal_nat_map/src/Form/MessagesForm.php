<?php
/**
 * @file
 * Contains Drupal\sfal_nat_map\Form\MessagesForm.
 */

namespace Drupal\sfal_nat_map\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class MessagesForm
 *
 * Manages admin for to set default year.
 *
 * @package Drupal\sfal_nat_map\Form
 */
class MessagesForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'sfal_nat_map.adminsettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'sfal_nat_map_settings_form';
  }

  /**
   * Build form.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('sfal_nat_map.adminsettings');

    $form['sfal_nat_map_default_year'] = [
      '#type' => 'number',
      '#title' => $this->t('Default Map Year'),
      '#description' => $this->t('Default year for map when none is provided'),
      '#default_value' => $config->get('sfal_nat_map_default_year'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * Saves default year setting.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('sfal_nat_map.adminsettings')
      ->set('sfal_nat_map_default_year', $form_state->getValue('sfal_nat_map_default_year'))
      ->save();
  }

}  