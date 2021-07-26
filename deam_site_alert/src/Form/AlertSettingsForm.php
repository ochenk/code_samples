<?php

namespace Drupal\deam_site_alert\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\State\StateInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure example settings for this site.
 */
class AlertSettingsForm extends ConfigFormBase {

  /**
   * Config settings namespace.
   *
   * @var string Config settings
   */
  const SETTINGS = 'deam_blocks.alert.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'deam_block_alert_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * Stores the state storage service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * A cache backend interface instance.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cacheRender;

  /**
   * {@inheritdoc}
   */
  public function __construct(StateInterface $state, CacheBackendInterface $cacheRender) {
    $this->state = $state;
    $this->cacheRender = $cacheRender;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('state'),
      $container->get('cache.render')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['deam_alert_group'] = [
      '#type' => 'fieldset',
      '#title' => t('Site-wide Alert'),
    ];

    $form['deam_alert_group']['deam_alert_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#default_value' => $this->state->get('deam_alert_title'),
      '#description' => 'Leave blank to hide the message.',
    ];

    $form['deam_alert_group']['deam_alert_message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      '#default_value' => $this->state->get('deam_alert_message'),
    ];

    $form['deam_alert_group']['deam_alert_link'] = [
      '#type' => 'fieldset',
      '#title' => t('URL'),
    ];
    $form['deam_alert_group']['deam_alert_link']['deam_alert_link_title'] = [
      '#type' => 'textfield',
      '#title' => t('Link Title'),
      '#default_value' => $this->state->get('deam_alert_link_title') ?: "Learn More",
      '#description' => t('Label for URL.
          If left blank, the link label will be "Learn More".'),
      '#maxlength' => 512,
    ];

    $form['deam_alert_group']['deam_alert_link']['deam_alert_link_url'] = [
      '#type' => 'textfield',
      '#title' => t('URL'),
      '#default_value' => $this->state->get('deam_alert_link_url'),
      '#description' => t('Can be an internal or external link.
          Internal links must start with "/".
          If you don\'t want a link added to the alert, leave this blank.'),
      '#maxlength' => 2048,
    ];

    $form['deam_alert_group']['deam_alert_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Alert Type'),
      '#default_value' => $this->state->get('deam_alert_type'),
      '#options' => [
        'notice' => $this->t('Notice (blue)'),
        'emergency' => $this->t('Emergency (red)'),
      ],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->state->set('deam_alert_title', $form_state->getValue('deam_alert_title'));
    $this->state->set('deam_alert_message', $form_state->getValue('deam_alert_message'));
    $this->state->set('deam_alert_link_title', $form_state->getValue('deam_alert_link_title'));
    $this->state->set('deam_alert_link_url', $form_state->getValue('deam_alert_link_url'));
    $this->state->set('deam_alert_type', $form_state->getValue('deam_alert_type'));

    parent::submitForm($form, $form_state);
    $this->cacheRender->invalidateAll();
  }

}
