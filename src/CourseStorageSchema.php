<?php

namespace Drupal\course;

use Drupal\Core\Entity\ContentEntityTypeInterface;
use Drupal\Core\Entity\Sql\SqlContentEntityStorageSchema;

/**
 * Defines the provider schema handler.
 */
class CourseStorageSchema extends SqlContentEntityStorageSchema {

  /**
   * {@inheritdoc}
   */
  protected function getEntitySchema(ContentEntityTypeInterface $entity_type, $reset = FALSE) {
    $schema = parent::getEntitySchema($entity_type, $reset);

    /*
     * Element availables in $schema['courses'] :
     *  - indexes
     *  - primary key
     *  - fields
     *  - unique keys
     *  - foreign keys
     */

    $schema['courses']['indexes'] += [
      'course__title' => ['title'],

    ];
    
    return $schema;
  }
}