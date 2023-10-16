<?php

namespace Drupal\cats_module\Form;

use Drupal\cats_module\Entity\CatsEntity;
use Drupal\Component\Utility\EmailValidatorInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Form for editing the CatsEntity.
 */
class CatsEntityEditForm extends FormBase {

  protected $entity;

  /**
   * The email validator service.
   *
   * @var \Drupal\Component\Utility\EmailValidatorInterface
   */
  protected $emailValidator;

  /**
   * Constructs a CatsEntityEditForm object.
   */
  public function __construct(CatsEntity $entity, EmailValidatorInterface $emailValidator) {
    $this->entity = $entity;
    $this->emailValidator = $emailValidator;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $emailValidator = $container->get('email.validator');
    $routeMatch = $container->get('current_route_match');
    $route_parameters = $routeMatch->getParameters();
    $cat_id = $route_parameters->get('cats_module_id');

    $entity = \Drupal::entityTypeManager()->getStorage('cats_module')->load($cat_id);

    if (!$entity) {
      throw new NotFoundHttpException();
    }

    return new static($entity, $emailValidator);
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cats_module_id';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $cats_entity = $this->entity;
    $form['#prefix'] = '<div id="cats-form-wrapper">';
    $form['#suffix'] = '</div>';
    $form['cat_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your cat’s name:'),
      '#default_value' => $cats_entity->cat_name->value,
      '#required' => TRUE,
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Your email:'),
      '#default_value' => $cats_entity->email->value,
      '#required' => TRUE,
      '#description' => 'Email can only contain Latin letters, underscore, or hyphen.',
      '#attributes' => [
        'class' => ['4'],
      ],
      '#ajax' => [
        'callback' => '::validateEmailAjax',
        'wrapper' => 'cats-form-wrapper',
        'method' => 'replace',
        'event' => 'change',
      ],
    ];
    $form['email_validate_message'] = [
      '#markup' => '<div class="email-validate-message"></div>',
    ];
    $form['image'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Image'),
      '#default_value' => [$this->entity->image->target_id],
      '#upload_location' => 'public://',
      '#required' => TRUE,
      '#description' => $this->t('An image associated with the custom entity.'),
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg jpeg'],
      ],
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),

    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $entity = $this->entity;
    $entity->set('cat_name', $form_state->getValue('cat_name'));
    $entity->set('email', $form_state->getValue('email'));
    $entity->set('image', $form_state->getValue('image'));

    $entity->save();

    $form_state->setRedirect('entity.cats_module.collection');
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $cat_name = $form_state->getValue('cat_name');

    if (strlen($cat_name) < 3 || strlen($cat_name) > 32) {
      $form_state->setErrorByName('cat_name', $this->t('Cat name must be between 3 and 32 characters.'));
    }
  }

  /**
   * Validates the email field using Ajax.
   */
  public function validateEmailAjax(array &$form, FormStateInterface $form_state) {
    $email = $form_state->getValue('email');

    if (!$this->emailValidator->isValid($email)) {
      $form['email']['#attributes']['class'][] = 'error';
      $form['email_validate_message']['#markup'] = '<div class="email-valudate-message">' . $this->t('Email is not valid.') . '</div>';
    }
    else {
      $form['email']['#attributes']['class'][] = '';
      $form['email_validate_message']['#markup'] = '';
    }

    return $form;
  }

}
