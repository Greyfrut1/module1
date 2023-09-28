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
    $form = $this->formBuilder()->getForm(CatsEntityListForm::class);
    $build = [
      // Working with our theme.
      '#theme' => 'cats_module_list_form',
      '#form' => $form,
    ];
    $build['#render element'] = 'form';

    return $build;
  }

}
