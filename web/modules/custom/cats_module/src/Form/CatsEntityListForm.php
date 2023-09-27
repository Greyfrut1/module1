<?php

namespace Drupal\cats_module\Form;

use Drupal\cats_module\CatsEntityListBuilder;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *
 */
class CatsEntityListForm extends FormBase {

  protected $catsEntityListBuilder;

  /**
   *
   */
  public function __construct(CatsEntityListBuilder $catsEntityListBuilder) {
    $this->catsEntityListBuilder = $catsEntityListBuilder;
  }

  /**
   *
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')->getListBuilder('cats_module')
    );
  }

  /**
   *
   */
  public function getFormId() {
    return 'cats_module_list_admin_form';
  }

  /**
   *
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $entities = $this->catsEntityListBuilder->load();

    $form['test'] = [];
    $rows = [];
    $row_id = 0;
    // Додаємо кожну сутність як опцію разом з її назвою.
    foreach ($entities as $entity) {
      $entity = \Drupal::entityTypeManager()->getStorage('cats_module')->load($entity->id());
      $cat_name = $entity->get('cat_name')->value;
      $email = $entity->get('email')->value;
      $created_timestamp = $entity->get('created')->value;
      $created_date = DrupalDateTime::createFromTimestamp($created_timestamp);
      $created_date_formatted = $created_date->format('d-m-Y H:i:s');
      $image_id = $entity->get('image')->target_id;
      $image_url = '';
      if ($image_id) {
        $file = \Drupal::entityTypeManager()->getStorage('file')->load($image_id);
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
   *
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Отримуємо ідентифікатори обраних сутностей з форми.
    $entities = $this->catsEntityListBuilder->load();
    $test_values = [];
    foreach ($entities as $entity) {
      $test_values[] = $form_state->getValue('test' . $entity->id());
    };
    foreach ($test_values as $value) {
      if ($value) {
        $entity = \Drupal::entityTypeManager()->getStorage('cats_module')->load($value);
        $entity->delete();
      }
    }

  }

  /**
   *
   */
  public function elementValidateFunction($element, FormStateInterface $form_state) {
    return $element;
  }

}
