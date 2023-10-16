<?php

namespace Drupal\cats_module\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Provides a controller for cat entity deletion and modal display.
 */
class CatsEntityDeleteController extends ControllerBase {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The form builder service.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * Constructs a new CatsEntityDeleteController instance.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   *   The form builder service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, FormBuilderInterface $form_builder) {
    $this->entityTypeManager = $entity_type_manager;
    $this->formBuilder = $form_builder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('form_builder')
    );
  }

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
    $cats_entity = $this->entityTypeManager->getStorage('cats_module')->load($cats_module_id);
    if (!$cats_entity) {
      throw new NotFoundHttpException();
    }
    $response = new AjaxResponse();
    $form = $this->formBuilder->getForm('Drupal\cats_module\Form\ConfirmDeleteForm', $cats_entity);

    $response->addCommand(new OpenModalDialogCommand(
      $this->t('Delete Cat Entity'),
      $form,
      ['width' => 'auto']
    ));

    return $response;
  }

}
