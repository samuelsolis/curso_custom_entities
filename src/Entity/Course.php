<?php

namespace Drupal\course\Entity;

use Drupal\Core\Entity\ContentEntityBase;


/**
 * Defines the Course entity.
 *
 * @ingroup course
 *
 * @ContentEntityType(
 *   id = "course",
 *   label = @Translation("Course entity"),
 *   base_table = "courses",
 *   entity_keys = {},
 *   admin_permission = "administer courses",
 *   handlers = {},
 *   },
 *   links = {},
 * )
 */
class Course extends ContentEntityBase {}