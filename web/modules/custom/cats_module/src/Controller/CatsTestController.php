<?php

namespace Drupal\cats_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 */
class CatsTestController extends ControllerBase {

  /**
   * Displays the delete confirmation modal.
   *
   * @param int $entity_id
   *   The cat entity ID.
   *
   *
   *   A JSON response for displaying the modal.
   */
  public function delete(Request $request) {
    $entityId = $request->request->get('entity_id');
    $message = $this->t('Entity with ID @entity_id has been selected.', ['@entity_id' => $entityId]);

    return new JsonResponse(['message' => $message]);
  }

}
