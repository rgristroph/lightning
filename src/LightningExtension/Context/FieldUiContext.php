<?php

namespace Acquia\LightningExtension\Context;

use Acquia\LightningExtension\FieldUiTrait;
use Acquia\LightningExtension\PrivilegeTrait;
use Behat\Mink\Driver\Selenium2Driver;
use Drupal\DrupalExtension\Context\DrupalSubContextBase;
use Drupal\DrupalExtension\Context\MinkContext;
use Zumba\Mink\Driver\PhantomJSDriver;

/**
 * Contains steps for interacting with the Field UI.
 */
class FieldUiContext extends DrupalSubContextBase {

  use FieldUiTrait;
  use PrivilegeTrait;

  /**
   * Entity IDs of fields created during the scenario.
   *
   * @see ::createField
   *
   * @var string[]
   */
  protected $fields = [];

  /**
   * The Mink context.
   *
   * @var MinkContext
   */
  protected $minkContext;

  /**
   * Pre-scenario hook.
   *
   * @BeforeScenario
   */
  public function gatherContexts() {
    $this->minkContext = $this->getContext(MinkContext::class);
  }

  /**
   * Post-scenario hook.
   *
   * @AfterScenario
   */
  public function cleanFields() {
    if ($this->fields) {
      $this->acquireRoles(['administrator']);

      while ($this->fields) {
        list ($entity_type, $bundle, $field) = array_pop($this->fields);

        $this->fieldUi($entity_type, $bundle, 'Manage fields');

        $path = sprintf(
          '%s/%s.%s.%s/delete',
          parse_url($this->getSession()->getCurrentUrl(), PHP_URL_PATH),
          $entity_type,
          $bundle,
          $field
        );
        $this->visitPath($path);
        $this->minkContext->pressButton('Delete');
      }

      $this->releasePrivileges();
    }
  }

  /**
   * Customizes a view mode.
   *
   * @param string $entity_type
   *   The entity type ID.
   * @param string $view_mode
   *   The view mode ID.
   * @param string $bundle
   *   (optional) The bundle to customize.
   *
   * @Given I have customized the :view_mode view mode of the :bundle :entity_type type
   * @Given I have customized the :view_mode view mode of the :entity_type entity type
   *
   * @When I customize the :view_mode view mode of the :bundle :entity_type type
   * @When I customize the :view_mode view mode of the :entity_type entity type
   */
  public function customize($entity_type, $view_mode, $bundle = NULL) {
    $this->fieldUi($entity_type, $bundle, 'Manage display');
    $this->minkContext->checkOption($view_mode);
    $this->minkContext->pressButton('Save');
  }

  /**
   * Uncustomizes a view mode.
   *
   * @param string $entity_type
   *   The entity type ID.
   * @param string $view_mode
   *   The view mode ID.
   * @param string $bundle
   *   (optional) The bundle to uncustomize.
   *
   * @Given I have uncustomized the :view_mode view mode of the :bundle :entity_type type
   * @Given I have uncustomized the :view_mode view mode of the :entity_type entity type
   *
   * @When I uncustomize the :view_mode view mode of the :bundle :entity_type type
   * @When I uncustomize the :view_mode view of the :entity_type entity type
   */
  public function uncustomize($entity_type, $view_mode, $bundle = NULL) {
    $this->fieldUi($entity_type, $bundle, 'Manage display');
    $this->minkContext->uncheckOption($view_mode);
    $this->minkContext->pressButton('Save');
  }

  /**
   * Creates a configurable field via the UI.
   *
   * @param string $entity_type
   *   The target entity type ID.
   * @param string $bundle
   *   The target bundle.
   * @param string $field_type
   *   The type of field to create.
   * @param string $machine_name
   *   The machine name of the field, without the field_ prefix. Will also be
   *   used as the label of the field.
   *
   * @Given a(n) :field_type field called :machine_name on the :bundle :entity_type type
   *
   * @When I create a(n) :field_type field called :machine_name on the :bundle :entity_type type
   */
  public function createField($entity_type, $bundle, $field_type, $machine_name) {
    $this->acquireRoles(['administrator']);

    $this->fieldUi($entity_type, $bundle, 'Manage fields');
    $this->minkContext->clickLink('Add field');
    $this->minkContext->selectOption('new_storage_type', $field_type);
    $this->minkContext->fillField('label', $machine_name);

    // If we're using a JavaScript driver, the field name will be filled in
    // automatically after a short delay. Otherwise, we'll have to set it
    // directly ourselves.
    $driver = $this->getSession()->getDriver();
    if ($driver instanceof Selenium2Driver || $driver instanceof PhantomJSDriver) {
      // Wait for the JavaScript to generate the field name.
      sleep(1);
    }
    else {
      $this->minkContext->fillField('field_name', $machine_name);
    }

    $this->minkContext->pressButton('Save and continue');
    $this->minkContext->pressButton('Save field settings');
    $this->minkContext->pressButton('Save settings');

    $this->fields[] = [$entity_type, $bundle, 'field_' . $machine_name];

    $this->releasePrivileges();
  }

}
