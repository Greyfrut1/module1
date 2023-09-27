<?php

namespace Drupal\cats_module\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 *
 */
class CatsController extends ControllerBase {

  /**
   * Returns the Cats page content.
   *
   * @return array
   */
  public function content() {
    $output = [
      'cats_list' => [
    // Текст, який ви виводили раніше.
        '#markup' => $this->t('Hello! You can add here a photo of your cat.'),
      ],
      'cats_form' => [
        '#type' => 'container',
        'form' => $this->formBuilder()->getForm('Drupal\cats_module\Form\CatsForm'),
      ],
    ];
    return $output;
  }

}
