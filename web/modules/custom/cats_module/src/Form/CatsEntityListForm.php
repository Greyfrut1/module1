<?php

namespace Drupal\cats_module\Form;

use Drupal\cats_module\CatsEntityListBuilder;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form for managing the list of Cats entities.
 */
class CatsEntityListForm extends FormBase {

  protected $catsEntityListBuilder;
  protected $entityTypeManager;

  /**
   * Constructs a new CatsEntityListBuilder object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager service.
   */
  public function __construct(CatsEntityListBuilder $catsEntityListBuilder, EntityTypeManagerInterface $entityTypeManager) {
    $this->catsEntityListBuilder = $catsEntityListBuilder;
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')->getListBuilder('cats_module'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cats_module_list_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $entities = $this->catsEntityListBuilder->load();
    $rows = [];
    $row_id = 0;
    foreach ($entities as $entity) {
      $entity = $this->entityTypeManager->getStorage('cats_module')->load($entity->id());
      $cat_name = $entity->get('cat_name')->value;
      $email = $entity->get('email')->value;
      $created_timestamp = $entity->get('created')->value;
      $created_date = DrupalDateTime::createFromTimestamp($created_timestamp);
      $created_date_formatted = $created_date->format('d-m-Y H:i:s');
      $image_id = $entity->get('image')->target_id;
      $url = Url::fromRoute('entity.cats_module.edit_form', ['cats_module_id' => $entity->id()]);
      $image_url = '';
      if ($image_id) {
        $file = $this->entityTypeManager->getStorage('file')->load($image_id);
        if ($file) {
          $image_url = $file->createFileUrl();
        }
      }
      $cat_image = [
        '#theme' => 'image_style',
        '#style_name' => 'thumbnail',
        '#uri' => $file->getFileUri(),
      ];
      $form['test' . $entity->id()] = [
        '#type' => 'checkbox',
        '#return_value' => $entity->id(),
      ];
      $rows[] = [
        'id' => 'test' . $entity->id(),
        'cat_name' => $cat_name,
        'email' => $email,
        'cat_image' => $cat_image,
        'created' => $created_date_formatted,
        'edit' => $url,
      ];
      $row_id++;
    }
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Видалити обрані'),
      '#attributes' => ['class' => ['entity-delete-button']],
    ];
    $form['#rows'] = $rows;
    $form['#theme'] = 'cats_module_list_form';
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $entities = $this->catsEntityListBuilder->load();
    $test_values = [];
    foreach ($entities as $entity) {
      $test_values[] = $form_state->getValue('test' . $entity->id());
    };
    foreach ($test_values as $value) {
      if ($value) {
        $entity = $this->entityTypeManager->getStorage('cats_module')->load($value);
        $entity->delete();
      }
    }

  }

  /**
   * {@inheritdoc}
   */
  public function elementValidateFunction($element, FormStateInterface $form_state) {
    return $element;
  }

}
