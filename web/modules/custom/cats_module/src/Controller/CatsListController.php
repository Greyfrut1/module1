<?php

namespace Drupal\cats_module\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 *
 */
class CatsListController extends ControllerBase {

  /**
   *
   */
  public function buildListForUsers() {
    // Ваш код для відображення списку котів для звичайних користувачів.
    // Можливо, ви використовуєте другий ListBuilder, який ви створили раніше.
    // Наприклад:
    $list_builder = \Drupal::entityTypeManager()->getListBuilder('cats_module');
    $build = $list_builder->render();

    return $build;
  }

}
