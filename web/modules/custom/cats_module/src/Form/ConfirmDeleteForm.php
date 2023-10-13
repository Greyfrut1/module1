<?php

namespace Drupal\cats_module\Form;

use Drupal\cats_module\Entity\CatsEntity;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CloseModalDialogCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a confirmation form for deleting a cat entity.
 */
class ConfirmDeleteForm extends FormBase {

  protected $catsEntity;

  /**
   * {@inheritdoc}
   */
  public function __construct(CatsEntity $cats_entity) {
    $this->catsEntity = $cats_entity;
  }

  /**
   * {@inheritdoc}
   */

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
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $route_match = \Drupal::routeMatch();
    $cats_module_id = $route_match->getParameter('cats_module_id');
    $cats_entity = \Drupal::entityTypeManager()->getStorage('cats_module')->load($cats_module_id);

    return new static($cats_entity);
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
