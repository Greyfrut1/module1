<?php

namespace Drupal\cats_module;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\Core\Datetime\DrupalDateTime;

class CatsEntityListBuilder extends EntityListBuilder {

  // public function render() {
  //   $build = parent::render();

  //   // Встановлюємо кастомний шаблон для списку.
  //   $build['table']['#theme'] = 'cats_module_list';

  //   // Решта вашого коду.

  //   return $build;
  // }

  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return parent::createInstance($container, $entity_type);
  }

  public function render() {
    $entities = $this->load();

    // Створюємо масив для рядків таблиці.
    $rows = [];
    foreach ($entities as $entity) {
      if($entity){
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
      ->accessCheck(False)
      ->count()
      ->execute();

    $build['summary']['#markup'] = $this->t('Total custom entities: @total', ['@total' => $total]);
    $build['#attached']['library'][] = 'cats_module/cats_module_js';

    return $build;
  }

  public function buildHeader() {
    $header['cat_name'] = $this->t('Cat name');
    $header['email'] = $this->t('Email');
    $header['image'] = $this->t('Image');
    $header['created'] = $this->t('Created');
    return $header + parent::buildHeader();
  }

  public function buildRow(EntityInterface $entity) {
    $image_id = $entity->get('image')->target_id;
    $cat_name = $entity->get('cat_name')->value;

    $image_url = '';
    if ($image_id) {
      $file = File::load($image_id);
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

    $row['cat_name'] = $entity->get('cat_name')->value;
    $row['email'] = $entity->get('email')->value;
    $row['image'] = [
      'data' => [
        '#markup' => '<a href="'. $image_url .'" target="_blank"><img src="' . $image_url . '" alt="' . $cat_name . '"></a>',
      ],
    ];
    $row['cat_image'] = $cat_image;
    $row['created'] = $created_date_formatted;
    $row['id'] = $entity->id();
    $row['operations']['edit'] = Url::fromRoute('entity.cats_module.edit_form', ['cats_module_id' => $entity->id()]);
    $row['operations']['delete'] = Url::fromRoute('entity.cats_module.delete_form', ['cats_module_id' => $entity->id()]);

//    $row['operations']['data'] = [
//      '#type' => 'operations',
//      '#links' => [
//        'edit' => [
//          'title' => $this->t('Edit'),
//          'url' => Url::fromRoute('entity.cats_module.edit_form', ['cats_module_id' => $entity->id()]),
//        ],
//        'delete' => [
//          'title' => $this->t('Delete'),
//          'url' => Url::fromRoute('entity.cats_module.delete_form', ['cats_module_id' => $entity->id()]),
//        ],
//      ],
//    ];

    return $row + parent::buildRow($entity);
  }
  public function load() {
    $query = $this->getStorage()->getQuery();
    $query->sort('created', 'DESC'); // Сортування за полем "created" у зворотньому порядку (новіші зверху).
    $query->accessCheck(FALSE);
    $entity_ids = $query->execute();


    return $this->storage->loadMultiple($entity_ids);
  }
}
