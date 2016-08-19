<?php

namespace Acquia\LightningExtension;

use Drupal\DrupalExtension\Context\DrupalContext;
use Drupal\user\Entity\User;

/**
 * Contains helper methods for temporarily escalating privileges.
 */
trait PrivilegeTrait {

  /**
   * Whether or not the user was anonymous before privilege escalation.
   *
   * @var bool
   */
  protected $wasAnonymous;

  /**
   * Additional roles acquired by the user.
   *
   * @var string[]
   */
  protected $rolesAcquired = [];

  /**
   * Switches to a user account with a set of roles.
   *
   * @param string[] $roles
   *    The roles to acquire.
   *
   * @throws \Exception
   *   If DrupalContext is not available.
   */
  protected function acquireRoles(array $roles) {
    /** @var DrupalContext $context */
    $context = $this->getContext(DrupalContext::class);

    if (empty($context)) {
      throw new \Exception('Cannot acquire roles without DrupalContext.');
    }

    if ($context->user) {
      $this->rolesAcquired = array_diff(
        $roles,
        User::load($context->user->uid)->getRoles()
      );

      foreach ($this->rolesAcquired as $role) {
        $context->getDriver()->userAddRole($context->user, $role);
      }
    }
    else {
      $this->wasAnonymous = TRUE;

      $roles = implode(',', $roles);
      $context->assertAuthenticatedByRole($roles);
    }
  }

  /**
   * Returns to the previous, unprivileged user context.
   *
   * @throws \Exception
   *   If DrupalContext is not available.
   */
  protected function releasePrivileges() {
    /** @var DrupalContext $context */
    $context = $this->getContext(DrupalContext::class);

    if (empty($context)) {
      throw new \Exception('Cannot acquire roles without DrupalContext.');
    }

    if ($this->wasAnonymous) {
      $context->assertAnonymousUser();

      $this->wasAnonymous = NULL;
    }
    else {
      /** @var \Drupal\user\UserInterface $account */
      $account = User::load($context->user->uid);
      array_walk($this->rolesAcquired, [$account, 'removeRole']);
      $account->save();

      $this->rolesAcquired = [];
    }
  }

}
