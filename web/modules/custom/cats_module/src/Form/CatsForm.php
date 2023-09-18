<?php

namespace Drupal\cats_module\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;


class CatsForm extends FormBase {
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
      '#title' => $this->t('Email'),
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
        //'progress' => ['type' => 'throbber', 'message' => 'test'], // Опціональний прогрес-індикатор.
      ],
    ];
    $form['email_validate_message'] = [
      '#markup' => '<div class="email-validate-message"></div>',
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add cat'),
      '#ajax' => [
        'callback' => '::submitFormAjax',
        'wrapper' => 'cats-form-wrapper', // ID обгортки, яку ми оновлюємо AJAX.
      ],
    ];



    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitFormAjax(array &$form, FormStateInterface $form_state) {
    if ($form_state->hasAnyErrors()) {
      // Якщо валідація не пройшла, поверніть поточний стан форми.
      return $form;
    }

    // Отримайте значення з поля для імені кота.
    $cat_name = $form_state->getValue('cat_name');

    // Збережіть ім'я кота у вашому модулі або в базі даних, залежно від вашого варіанту реалізації.

    // Повідомте користувача про успішне додавання кота.
    \Drupal::messenger()->addMessage($this->t('The cat @name has been added.', ['@name' => $cat_name]));

    // Очистіть поле для імені кота.
    $form_state->setValue('cat_name', '');

    // Поверніть змінену форму (порожню), щоб оновити її через AJAX.
    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Отримайте значення з поля для імені кота.

  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $cat_name = $form_state->getValue('cat_name');

    // Перевірка, чи довжина імені кота менше 3 символів або більше 32 символів.
    if (strlen($cat_name) < 3 || strlen($cat_name) > 32) {
      $form_state->setErrorByName('cat_name', $this->t('Cat name must be between 3 and 32 characters.'));
    }
  }
  public function validateEmailAjax(array &$form, FormStateInterface $form_state) {
    $email = $form_state->getValue('email');

    // Перевірте, чи введена адреса електронної пошти є валідною.
    if (!\Drupal::service('email.validator')->isValid($email)) {
      $form['email']['#attributes']['class'][] = 'error';
      $form['email_validate_message']['#markup'] = '<div class="email-valudate-message">' . $this->t('Email is not valid.') . '</div>';
    } else{
      $form['email']['#attributes']['class'][] = '';
      $form['email_validate_message']['#markup'] = '';
    }

    // Поверніть всю оновлену форму, а не лише поле електронної пошти.
    return $form;
  }
}
