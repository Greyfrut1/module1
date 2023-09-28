<?php

namespace Drupal\cats_module;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *
 */
class CatsEntityListBuilder extends EntityListBuilder {

  protected $formBuilder;

  /**
   *
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return parent::createInstance($container, $entity_type);
  }

  /**
   *
   */
  public function render() {
    $entities = $this->load();

    // Створюємо масив для рядків таблиці.
    $rows = [];
    foreach ($entities as $entity) {
      if ($entity) {
        $rows[] = $this->buildRow($entity);
      }
    }
    $build['table'] = [
      '#theme' => 'cats_entity_list_builder',
      '#header' => $this->buildHeader(),
      '#rows' => $rows,
      '#attributes' => [],
    ];

    $total = $this->getStorage()
      ->getQuery()
      ->accessCheck(FALSE)
      ->count()
      ->execute();

    $current_user = \Drupal::currentUser();

    $user_roles = $current_user->getRoles();

    $second_role = '';
    if (!empty($user_roles) && count($user_roles) >= 2) {
      $second_role = array_values($user_roles)[1];
    }

    $build['#attached']['library'][] = 'cats_module/cats_module_js';
    $build['table']['#attributes']['user'] = $second_role;
    return $build;
  }

  /**
   *
   */
  public function buildHeader() {
    $header['select_all'] = [
      'data' => [
        '#type' => 'checkbox',
        '#title' => $this->t('Select All'),
        '#attributes' => ['class' => ['entity-select-all']],
      ],
    ];
    $header['cat_name'] = $this->t('Cat name');
    $header['email'] = $this->t('Email');
    $header['image'] = $this->t('Image');
    $header['created'] = $this->t('Created');
    return $header + parent::buildHeader();
  }

  /**
   *
   */
  public function buildRow(EntityInterface $entity) {
    $image_id = $entity->get('image')->target_id;
    $cat_name = $entity->get('cat_name')->value;

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

    $created_timestamp = $entity->get('created')->value;
    $created_date = DrupalDateTime::createFromTimestamp($created_timestamp);
    $created_date_formatted = $created_date->format('d-m-Y H:i:s');

    $current_user = \Drupal::currentUser();
    $user_roles = $current_user->getRoles();
    $second_role = '';
    if (!empty($user_roles) && count($user_roles) >= 2) {
      $second_role = array_values($user_roles)[1];
    }

    $row['select'] = [
      'data' => [
        '#type' => 'checkbox',
        '#attributes' => ['class' => ['entity-select']],
        '#default_value' => 0,
        '#name' => 'entity_select[' . $entity->id() . ']',
      ],
      '#ajax' => [
        'callback' => '::deleteSelectedCallback',
        'wrapper' => 'cats-form-wrapper',
        'method' => 'replace',
        'event' => 'change',
      ],
    ];
    $row['user'] = $second_role;
    $row['cat_name'] = $entity->get('cat_name')->value;
    $row['email'] = $entity->get('email')->value;
    $row['image'] = [
      'data' => [
        '#markup' => '<a href="' . $image_url . '" target="_blank"><img src="' . $image_url . '" alt="' . $cat_name . '"></a>',
      ],
    ];
    $row['cat_image'] = $cat_image;
    $row['created'] = $created_date_formatted;
    $row['id'] = $entity->id();
    $row['operations']['edit'] = Url::fromRoute('entity.cats_module.edit_form', ['cats_module_id' => $entity->id()]);
    $row['operations']['delete'] = Url::fromRoute('entity.cats_module.delete_form', ['cats_module_id' => $entity->id()]);

    $row['operations']['data'] = [
      '#type' => 'operations',
      '#links' => [
        'edit' => [
          'title' => $this->t('Edit'),
          'url' => Url::fromRoute('entity.cats_module.edit_form', ['cats_module_id' => $entity->id()]),
        ],
        'delete' => [
          'title' => $this->t('Delete'),
          'url' => Url::fromRoute('entity.cats_module.delete_form', ['cats_module_id' => $entity->id()]),
        ],
      ],
    ];

    return $row + parent::buildRow($entity);
  }

  /**
   *
   */
  public function load() {
    $query = $this->getStorage()->getQuery();
    $query->sort('created', 'DESC');
    $query->accessCheck(FALSE);
    $entity_ids = $query->execute();
    return $this->storage->loadMultiple($entity_ids);
  }

}
