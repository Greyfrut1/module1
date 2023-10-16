<?php

namespace Drupal\cats_module\Form;

use Drupal\cats_module\Entity\CatsEntity;
use Drupal\Component\DependencyInjection\ContainerInterface;
use Drupal\Component\Utility\EmailValidatorInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;

/**
 * Form for adding a new cat entity.
 */
class CatsForm extends FormBase {

  protected $emailValidator;
  protected $messenger;

  /**
   * Constructs a CatsEntityEditForm object.
   */
  public function __construct(EmailValidatorInterface $emailValidator, MessengerInterface $messenger) {
    $this->emailValidator = $emailValidator;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface|\Symfony\Component\DependencyInjection\ContainerInterface $container) {
    return new static(
      $container->get('email.validator'),
      $container->get('messenger')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cats_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#prefix'] = '<div id="cats-form-wrapper">';
    $form['#suffix'] = '</div>';
    $form['cat_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your cat’s name:'),
      '#required' => TRUE,
      '#description' => $this->t('Min length: 2 characters. Max length: 32 characters'),
    ];
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Your email:'),
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
      '#description' => $this->t('Choose an image file to upload (jpeg, jpg, png formats only).'),
      '#upload_location' => 'public://',
      '#upload_validators' => [
        'file_validate_extensions' => ['jpg jpeg png'],
        'file_validate_size' => [2100000],
      ],
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add cat'),
      '#ajax' => [
        'callback' => '::submitFormAjax',
        'wrapper' => 'cats-form-wrapper',
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitFormAjax(array &$form, FormStateInterface $form_state) {
    if ($form_state->hasAnyErrors()) {
      return $form;
    }
    $cat_name = $form_state->getValue('cat_name');
    $this->messenger->addMessage($this->t('The cat @name has been added.', ['@name' => $cat_name]));
    $form_state->setValue('cat_name', '');
    $name = $form_state->getValue('cat_name');

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $cat_name = $form_state->getValue('cat_name');
    $email = $form_state->getValue('email');
    $image_id = $form_state->getValue('image')[0];
    $entity = CatsEntity::create();
    $entity->set('cat_name', $cat_name);
    $entity->set('email', $email);
    $entity->set('image', $image_id);
    $current_time = new DrupalDateTime('now');
    $entity->set('created', $current_time->getTimestamp());
    $entity->save();
    $form_state->setValue('cat_name', '');

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
