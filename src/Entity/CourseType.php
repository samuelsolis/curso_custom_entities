<?php

namespace Drupal\course\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * A configuration entity used to manage bundles for the Most Simple entity.
 *
 * @ConfigEntityType(
 *   id = "course_type",
 *   label = @Translation("Course Type"),
 *   bundle_of = "course",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *   },
 *   config_prefix = "course_type",
 *   config_export = {
 *     "id",
 *     "label",
 *     "contentType",
 *   },
 *   handlers = {
 *    "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\course\CourseTypeListBuilder",
 *     "form" = {
 *       "default" = "Drupal\course\Form\CourseTypeForm",
 *       "add" = "Drupal\course\Form\CourseTypeForm",
 *       "edit" = "Drupal\course\Form\CourseTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityForm",
 *     },
 *   },
 *   admin_permission = "administer course types",
 *   links = {
 *     "canonical" = "/admin/structure/course_type/{course_type}",
 *     "add-form" = "/admin/structure/course_type/add",
 *     "edit-form" = "/admin/structure/course_type/{course_type}/edit",
 *     "delete-form" = "/admin/structure/course_type/{course_type}/delete",
 *     "collection" = "/admin/structure/course_type",
 *   }
 * )
 */
class CourseType extends ConfigEntityBundleBase {

  /**
   * The machine name of the practical type.
   *
   * @var string
   */
  protected $id;

  /**
   * The human-readable name of the practical type.
   *
   * @var string
   */
  protected $label;

  /**
   * Bodeboca content type related with this bundle.
   *
   * @var string
   */
  protected $contentType;

  /**
   * {@inheritdoc}
   */
  public function getContentType() {
    return $this->contentType;
  }

  /**
   * {@inheritdoc}
   */
  public function setContentType($contentType) {
    $this->contentType = $contentType;
    return $this;
  }
}
