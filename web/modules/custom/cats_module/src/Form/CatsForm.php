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
    $form['cat_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your cat’s name:'),
      '#required' => TRUE,
      '#description' => $this->t('Min length: 2 characters. Max length: 32 characters'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add cat'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Отримайте значення з поля для імені кота.
    $cat_name = $form_state->getValue('cat_name');

    // Збережіть ім'я кота у вашому модулі або в базі даних, залежно від вашого варіанту реалізації.
    // Наприклад, можна використовувати Drupal's Configuration API для збереження інформації.

    // Повідомте користувача про успішне додавання кота.
    \Drupal::messenger()->addMessage($this->t('The cat @name has been added.', ['@name' => $cat_name]));
  }
}
