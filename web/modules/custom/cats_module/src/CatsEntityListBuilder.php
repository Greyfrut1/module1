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
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return parent::createInstance($container, $entity_type);
  }

  public function render() {
    $build['table'] = parent::render();

    $total = $this->getStorage()
      ->getQuery()
      ->accessCheck(False)
      ->count()
      ->execute();

    $build['summary']['#markup'] = $this->t('Total custom entities: @total', ['@total' => $total]);
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

    $created_timestamp = $entity->get('created')->value;
    $created_date = DrupalDateTime::createFromTimestamp($created_timestamp);
    $created_date_formatted = $created_date->format('Y-m-d H:i:s');
  
    $row['cat_name'] = $entity->get('cat_name')->value;
    $row['email'] = $entity->get('email')->value;
    $row['image'] = [
      'data' => [
        '#markup' => '<img src="' . $image_url . '" alt="' . $cat_name . '">',
      ],
    ];
    $row['created'] = $created_date_formatted;
  
    return $row + parent::buildRow($entity);
  }
}