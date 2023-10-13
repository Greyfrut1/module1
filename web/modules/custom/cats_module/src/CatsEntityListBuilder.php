<?php

namespace Drupal\cats_module;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *
 */
class CatsEntityListBuilder extends EntityListBuilder {

  protected $formBuilder;
  protected $currentUser;
  protected $entityTypeManager;
  protected $requestStack;

  /**
   * Constructs a new CatsEntityListBuilder object.
   *
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   The current user.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager service.
   * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
   *   The request stack service.
   */
  public function __construct(
    EntityTypeInterface $entity_type,
    AccountProxyInterface $current_user,
    EntityTypeManagerInterface $entityTypeManager,
    RequestStack $requestStack
  ) {
    parent::__construct($entity_type, $entityTypeManager->getStorage('cats_module'));
    $this->currentUser = $current_user;
    $this->entityTypeManager = $entityTypeManager;
    $this->requestStack = $requestStack;
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('current_user'),
      $container->get('entity_type.manager'),
      $container->get('request_stack')
    );
  }

  /**
   *
   */
  public function render() {
    $entities = $this->load();

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

    $current_user = $this->currentUser;

    $user_roles = $current_user->getRoles();

    $second_role = '';
    if (!empty($user_roles) && count($user_roles) >= 2) {
      $second_role = array_values($user_roles)[1];
    }

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

    $created_timestamp = $entity->get('created')->value;
    $created_date = DrupalDateTime::createFromTimestamp($created_timestamp);
    $created_date_formatted = $created_date->format('d-m-Y H:i:s');

    $current_user = $this->currentUser;
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
    $entity_type_id = 'cats_module';
    $query = $this->entityTypeManager->getStorage($entity_type_id)->getQuery();
    $query->sort('created', 'DESC');
    $query->accessCheck(FALSE);
    $entity_ids = $query->execute();
    return $this->storage->loadMultiple($entity_ids);
  }

}
