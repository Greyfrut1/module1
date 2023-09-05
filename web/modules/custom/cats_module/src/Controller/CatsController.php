<?php

namespace Drupal\cats_module\Controller;

use Drupal\Core\Controller\ControllerBase;

class CatsController extends ControllerBase {
  /**
   * Returns the Cats page content.
   *
   * @return array
   */
  public function content() {
    return [
      '#markup' => $this->t('Hello! You can add here a photo of your cat.'),
    ];
  }
}
