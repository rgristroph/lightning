<?php

namespace Drupal\lightning\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Defines a form to confirm enabling Lightning experimental modules.
 */
class ExperimentalConfirmForm extends ConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you wish to enable Lightning experimental modules?');
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'lightning_confirm_experimental';
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('system.modules_list');
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {}

}
