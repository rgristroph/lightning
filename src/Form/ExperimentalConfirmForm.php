<?php

namespace Drupal\lightning\Form;

use Drupal\system\Form\ModulesListConfirmForm;

/**
 * Defines a form to confirm enabling Lightning experimental modules.
 */
class ExperimentalConfirmForm extends ModulesListConfirmForm {

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
    return 'lightning_experimental_confirm_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function buildMessageList() {
    drupal_set_message($this->t('<a href=":url">Experimental modules</a> are provided for testing purposes only. Use at your own risk.', [':url' => 'https://www.drupal.org/core/experimental']), 'warning');
    $items = parent::buildMessageList();
    // Add the list of experimental modules after any other messages.
    $items[] = $this->t('The following modules are experimental: @modules', ['@modules' => implode(', ', array_values($this->modules['experimental']))]);
    return $items;
  }

}
