<?php

namespace Drupal\scca_ash\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Provides a 'SpeakersBlock' block.
 *
 * @Block(
 *  id = "ash2018_speakers_block",
 *  admin_label = @Translation("ASH2018 Speakers List"),
 * )
 */
class SpeakersBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var $entityTypeManager \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * @var $db \Drupal\Core\Database\Connection
   */
  protected $db;

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   *
   * @return static
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('database')
    );
  }

  /**
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\Core\Entity\EntityTypeManager $entityTypeManager
   * @param \Drupal\Core\Database\Connection $db
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManager $entityTypeManager, Connection $db) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entityTypeManager;
    $this->db = $db;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return $this->speakerList();
  }

  /**
   * @return array
   *      renderable array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  private function speakerList() {
    $node_storage = $this->fetchSpeakers();
    $speakers = [];
    foreach ($node_storage as $speakerEntity) {
      $physician = $speakerEntity->field_conf_speaker_physician->entity;
      $speaker = [
        'nid' => $physician->id(),
        'name' => trim($physician->field_first_name->value) . " "
          . trim($physician->field_middle_name->value) . " "
          . trim($physician->field_last_name->value) . ", "
          . trim($physician->field_degrees->value),
        'title' => $physician->field_resume_title->value,
        'summary' => $physician->field_clinicalexpertise->value,
        'image' => !$physician->field_image->isEmpty() ? file_create_url($physician->field_image->entity->uri->value) : NULL,
        'url' => $physician->url(),
        'doximity' => $physician->field_physician_doximity_link->value,
        'id' => $physician->id(),
      ];
      foreach ($speakerEntity->field_conf_speaker_abstract as $abid) {
        $abs = $abid->entity;
        $speaker['abstracts'][] = [
          'title' => $abs->title->value,
          'subtitle' => $abs->field_conf_abs_subtitle->value,
          'type' => $abs->field_conf_abs_type->value,
          'location' => $abs->field_conf_abs_location->value,
          'summary' => $abs->field_conf_abs_summary->value,
          'video' => $abs->field_conf_abs_video->value,
          'poster' => !$abs->field_conf_abs_poster->isEmpty() ? file_create_url($abs->field_conf_abs_poster->entity->uri->value) : NULL,
          'id' => $abs->id(),
        ];
      }
      $speakers[] = $speaker;
    }
    return [
      '#theme' => 'scca_ash_speakers_block',
      '#speakers' => $speakers,
      //'#cache' => ['max-age' => 0],
    ];
  }

  /**
   * Queries for Speaker data and loads nodes.
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   *      array of speaker nodes.
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  private function fetchSpeakers() {
    $query = $this->db->select('node_field_data', 'nfd');
    $query->addField('nfd', 'nid');
    $query->join('node__field_conf_speaker_physician', 'p', 'p.entity_id=nfd.nid');
    $query->leftJoin('node__field_conf_speaker_featured', 'f', 'f.entity_id=nfd.nid');
    $query->join('node__field_last_name', 'ln', 'ln.entity_id=p.field_conf_speaker_physician_target_id');
    $query->condition('nfd.type', 'conference_speaker');
    $query->condition('nfd.status', 1);
    $query->orderBy('f.field_conf_speaker_featured_value', 'DESC');
    $query->orderBy('ln.field_last_name_value', 'ASC');
    $nids = $query->execute()->fetchCol();
    return $this->entityTypeManager->getStorage('node')->loadMultiple($nids);
  }


}