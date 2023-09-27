<?php

namespace Drupal\cats_module\Controller;

use Drupal\cats_module\Form\CatsEntityListForm;
use Drupal\Core\Controller\ControllerBase;

/**
 *
 */
class CatsListFormController extends ControllerBase {

  /**
   *
   */
  public function buildListFormForUsers() {
    // Ваш код для відображення списку котів для звичайних користувачів.
    // Можливо, ви використовуєте другий ListBuilder, який ви створили раніше.
    // Наприклад:
    $form = $this->formBuilder()->getForm(CatsEntityListForm::class);
    $build = [
      // Working with our theme.
      '#theme' => 'cats_module_list_form',
      '#form' => $form,
    ];
    // Додайте цей рядок для вказівки, який елемент форми потрібно рендерити.
    $build['#render element'] = 'form';

    return $build;
  }

}
