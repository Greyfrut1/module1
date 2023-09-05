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
    $output = [
      'cats_list' => [
        '#markup' => $this->t('Hello! You can add here a photo of your cat.'), // Текст, який ви виводили раніше.
      ],
      'cats_form' => [
        '#type' => 'container',
        'form' => $this->formBuilder()->getForm('Drupal\cats_module\Form\CatsForm'),
      ],
    ];
    return $output;
  }
}
