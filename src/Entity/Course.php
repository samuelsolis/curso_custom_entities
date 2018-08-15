<?php

namespace Drupal\course\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;


/**
 * Defines the Course entity.
 *
 * @ingroup course
 *
 * @ContentEntityType(
 *   id = "course",
 *   label = @Translation("Course entity"),
 *   base_table = "courses",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "title",
 *   },
 *   admin_permission = "administer courses",
 *   handlers = {},
 *   links = {},
 * )
 */
class Course extends ContentEntityBase {

  /**
   * {@inheritdoc}
   *
   * Define the field properties here.
   *
   * Field name, type and size determine the table structure.
   *
   * In addition, we can define how the field and its content can be manipulated
   * in the GUI. The behaviour of the widgets used can be determined here.
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Course id'))
      ->setDescription(t('Course id to identify the course.'))
      ->setSettings([
        'default_value' => NULL,
      ])
      ->setSetting('unsigned', TRUE)
      ->setReadOnly(TRUE);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setDescription(t('Course title.'))
      ->setSettings([
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ]);

    return $fields;
  }
}