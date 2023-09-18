<?php

namespace Drupal\cats_module\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\file\Entity\File;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\user\UserInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Render\Element\EntityAutocomplete;
use Drupal\Core\Entity\ContentEntityInterface;
/**
 * Defines the cats_module entity.
 *
 * @ingroup cats_module
 *
 * @ContentEntityType(
 *   id = "cats_module",
 *   label = @Translation("cats_module"),
 *   base_table = "cats_module",
 *   entity_keys = {
 *     "id" = "id",
 *     "created" = "created",
 *   },
 *   admin_permission = "administer my awesome entities",
 *   links = {
 *      "collection" = "/admin/structure/cats_module/entities",
 *      "add-form" = "/admin/cats_module/add",
 *    },
 * )
 */

class CatsEntity extends ContentEntityBase implements ContentEntityInterface {

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    // Standard field, used as unique if primary index.
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('Id of cat entity'))
      ->setReadOnly(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields["cat_name"] = BaseFieldDefinition::create("string")
      ->setLabel(t("Your cat’s name:"))
      ->setDescription(t("Min length: 2 characters. Max length: 32 characters"))
      ->setSettings(["max_length" => 255, "text_processing" => 0])
      ->setDefaultValue("")
      ->setDisplayOptions("view", ["label" => "above", "type" => "string", "wegith" => -3])
      ->setDisplayOptions("form", ["type" => "string_textfield", "wegith" => -3]);

    $fields["email"] = BaseFieldDefinition::create("email")
      ->setLabel(t("Your email:"))
      ->setDescription(t("Email can only contain Latin letters, underscore, or hyphen."))
      ->setDefaultValue("")
      ->setDisplayOptions("form", ["type" => "email_default", "wegith" => -3]);
      
    $fields['image'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Image'))
      ->setDescription(t('An image associated with the custom entity.'))
      ->setRequired(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'image_image',
        'weight' => 0,
      ]);

    return $fields;
  }
}