<?php

namespace Drupal\course;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Class CourseTypeListBuilder.
 */
class CourseListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Linked Entity Label');
    $header['title'] = $this->t('Course title');

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\course\Entity\Course $entity */
    $row['id'] = $entity->toLink($entity->label());
    $row['title'] = $entity->getTitle();

    return $row + parent::buildRow($entity);
  }
}