<?php

namespace Drupal\cats_module\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Provides a controller for cat entity deletion and modal display.
 */
class CatsEntityDeleteController extends ControllerBase {

  /**
   * Displays the delete confirmation modal.
   *
   * @param int $cats_module_id
   *   The cat entity ID.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   A JSON response for displaying the modal.
   */
  public function delete($cats_module_id) {
    // Отримайте сутність за її ідентифікатором.
    $cats_entity = $this->entityTypeManager()->getStorage('cats_module')->load($cats_module_id);

    if (!$cats_entity) {
      throw new NotFoundHttpException();
    }
    $response = new AjaxResponse();
    $form = \Drupal::formBuilder()->getForm('\Drupal\cats_module\Form\ConfirmDeleteForm', $cats_entity);
    $form['#attached']['library'][] = 'cats_module/cats_module_js';

    $response->addCommand(new OpenModalDialogCommand(
      t('Delete Cat Entity'),
      $form,
      ['width' => 'auto']
    ));

    return $response;
  }

}