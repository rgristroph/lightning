<?php

namespace Acquia\LightningExtension;

use Behat\Mink\Element\NodeElement;

/**
 * Contains helper methods for interacting with drop buttons.
 */
trait DropButtonTrait {

  /**
   * Executes a drop button action.
   *
   * @param \Behat\Mink\Element\NodeElement $container
   *   The element containing the drop button.
   * @param string $action
   *   The action to execute.
   */
  protected function doDropButtonAction(NodeElement $container, $action) {
    $action = $this->assertDropButtonAction($container, $action);

    $drop_button = $this->assertDropButton($container);

    // If we're interacting with a browser, we may need to expand the drop
    // button before we can click the action.
    $this->assertSession()
      ->elementExists('css', '.dropbutton-toggle', $drop_button)
      ->click();

    $action->click();
  }

  /**
   * Asserts the presence of a drop button action.
   *
   * @param \Behat\Mink\Element\NodeElement $container
   *    The element containing the drop button.
   * @param string $action
   *   The action to assert.
   *
   * @return \Behat\Mink\Element\NodeElement
   *   The action element.
   */
  protected function assertDropButtonAction(NodeElement $container, $action) {
    $drop_button = $this->assertDropButton($container);

    return $this->assertSession()
      ->elementExists('named', ['link_or_button', $action], $drop_button);
  }

  /**
   * Asserts the presence of a drop button.
   *
   * @param \Behat\Mink\Element\NodeElement $container
   *   The element containing the drop button.
   *
   * @return \Behat\Mink\Element\NodeElement
   *   The drop button element.
   */
  protected function assertDropButton(NodeElement $container) {
    return $this->assertSession()
      ->elementExists('css', '.dropbutton', $container);
  }

}
