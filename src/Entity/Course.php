<?php

namespace Drupal\course\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\RevisionLogEntityTrait;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\user\UserInterface;


/**
 * Defines the Course entity.
 *
 * @ingroup course
 *
 * @ContentEntityType(
 *   id = "course",
 *   label = @Translation("Course entity"),
 *   base_table = "courses",
 *   revision_table = "course_revision",
 *   show_revision_ui = TRUE,
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "rid",
 *     "label" = "title",
 *   },
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_uid",
 *     "revision_created" = "revision_timestamp",
 *     "revision_log_message" = "revision_log"
 *   },
 *   admin_permission = "administer courses",
 *   handlers = {
 *     "storage_schema" = "Drupal\course\CourseStorageSchema",
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *    "form" = {
 *       "default" = "\Drupal\Core\Entity\ContentEntityForm",
 *       "delete" = "\Drupal\Core\Entity\EntityDeleteForm"
 *     },
 *   },
 *   links = {
 *     "canonical" = "/course/{course}",
 *     "delete-form" = "/course/{course}/delete",
 *     "edit-form" = "/course/{course}/edit",
 *     "version-history" = "/course/{course}/revisions",
 *     "revision" = "/course/{course}/revisions/{course_revision}/view",
 *     "add-form" = "/course/add",
 *   }
 * )
 */

class Course extends ContentEntityBase {

  use RevisionLogEntityTrait;
  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }


  /**
   * {@inheritdoc}
   */
  public function getTitle() {
    return $this->get('title')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTitle($title) {
    $this->set('title', $title);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('uid')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->getEntityKey('uid');
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('uid', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('uid', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);
    // If no revision author has been set explicitly, make the course owner the
    // revision author.
    if (!$this->getRevisionUser()) {
      $this->setRevisionUserId($this->getOwnerId());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function preSaveRevision(EntityStorageInterface $storage, \stdClass $record) {
    parent::preSaveRevision($storage, $record);

    if (!$this->isNewRevision() && isset($this->original) && (!isset($record->revision_log) || $record->revision_log === '')) {
      // If we are updating an existing course without adding a new revision, we
      // need to make sure $entity->revision_log is reset whenever it is empty.
      // Therefore, this code allows us to avoid clobbering an existing log
      // entry with an empty one.
      $record->revision_log = $this->original->revision_log->value;
    }
  }

  /**
   * {@inheritdoc}
   *entidad
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

    $fields['rid'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Course revision id'))
      ->setDescription(t('Course revision id to identify revision course.'))
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
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    /*
     * Meta fields.
     */
    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setDescription(t('The time that the course was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the course last edited.'));

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The username of the content author.'))
      ->setSetting('target_type', 'user')
      ->setDefaultValueCallback('Drupal\course\Entity\Course::getCurrentUserId')
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 5,
      ])
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }


  /**
   * Default value callback for 'uid' base field definition.
   *
   * @see ::baseFieldDefinitions()
   *
   * @return array
   *   An array of default values.
   */
  public static function getCurrentUserId() {
    return [\Drupal::currentUser()->id()];
  }
}