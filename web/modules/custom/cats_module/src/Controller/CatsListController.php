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
    $list_builder = \Drupal::entityTypeManager()->getListBuilder('cats_module');
    $build = $list_builder->render();

    return $build;
  }

}
