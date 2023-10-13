<?php

namespace Drupal\cats_module\Form;

use Drupal\cats_module\Entity\CatsEntity;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CloseModalDialogCommand;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a confirmation form for deleting a cat entity.
 */
class ConfirmDeleteForm extends FormBase {

  protected $catsEntity;
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *     The entity type manager service.
   */
  public function __construct(CatsEntity $cats_entity, EntityTypeManagerInterface $entityTypeManager) {
    $this->catsEntity = $cats_entity;
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $routeMatch = $container->get('current_route_match');
    $route_parameters = $routeMatch->getParameters();
    $cat_id = $route_parameters->get('cats_module_id');
    $cats_entity = $container->get('entity_type.manager')->getStorage('cats_module')->load($cat_id);
    $typeManager = $container->get('entity_type.manager');

    return new static($cats_entity, $typeManager);
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cats_module_confirm_delete_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['message'] = [
      '#markup' => $this->t('Are you sure you want to delete the cat entity: %name?', ['%name' => $this->catsEntity->label()]),
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['yes'] = [
      '#type' => 'submit',
      '#value' => $this->t('Yes'),

    ];

    $form['actions']['no'] = [
      '#type' => 'button',
      '#value' => $this->t('No'),
      '#ajax' => [
        'callback' => [$this, 'closeModal'],
      ],
      '#limit_validation_errors' => [],
    ];
    $form['#attached']['library'][] = 'cats_module/cats_module_js';

    return $form;
  }

  /**
   *
   */
  public function closeModal(array &$form, FormStateInterface $form_state) {
    // Create an AjaxResponse to close the modal.
    $response = new AjaxResponse();
    $response->addCommand(new CloseModalDialogCommand());
    return $response;
  }

  /**
   *
   */
  public function submitFormAjax(array &$form, FormStateInterface $form_state) {
    $this->catsEntity->delete();
    $response = new AjaxResponse();
    $response->addCommand(new CloseModalDialogCommand());
    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->catsEntity->delete();

    $form_state->setRedirect('entity.cats_module.collection');
  }

}
