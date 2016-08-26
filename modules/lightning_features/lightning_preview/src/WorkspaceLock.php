<?php

namespace Drupal\lightning_preview;

use Drupal\Core\Config\Entity\ConfigEntityTypeInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\multiversion\Entity\WorkspaceInterface;
use Drupal\multiversion\Workspace\WorkspaceManagerInterface;

/**
 * A service for dealing with workspace-related entity locking.
 */
class WorkspaceLock {

  /**
   * The workspace manager.
   *
   * @var WorkspaceManagerInterface
   */
  protected $workspaceManager;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * WorkspaceLock constructor.
   *
   * @param WorkspaceManagerInterface $workspace_manager
   *   The workspace manager.
   * @param EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(WorkspaceManagerInterface $workspace_manager, EntityTypeManagerInterface $entity_type_manager) {
    $this->workspaceManager = $workspace_manager;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * Determines if a workspace is locked.
   *
   * @param WorkspaceInterface $workspace
   *   (optional) The workspace to check. Defaults to the active workspace.
   *
   * @return bool
   *   Whether or not the workspace is locked.
   */
  public function isWorkspaceLocked(WorkspaceInterface $workspace = NULL) {
    if (empty($workspace)) {
      $workspace = $this->workspaceManager->getActiveWorkspace();
    }

    if ($workspace->getMachineName() == 'live') {
      return FALSE;
    }

    if ($workspace->hasField('moderation_state')) {
      return in_array(
        $workspace->moderation_state->target_id,
        $workspace->type->entity->getThirdPartySetting('workbench_moderation', 'locked_states', [])
      );
    }
    else {
      return FALSE;
    }
  }

  /**
   * Determines if an entity type is locked.
   *
   * @param string $entity_type
   *   The entity type ID.
   *
   * @return bool
   *   Whether the entity type is locked.
   */
  public function isEntityTypeLocked($entity_type) {
    if ($entity_type == 'workspace') {
      return FALSE;
    }

    $definition = $this->entityTypeManager->getDefinition($entity_type);

    return $definition instanceof ConfigEntityTypeInterface
      ? $this->workspaceManager->getActiveWorkspace()->getMachineName() != 'live'
      : $this->isWorkspaceLocked();
  }

  /**
   * Determines if an entity is locked.
   *
   * @param EntityInterface $entity
   *   The entity to check.
   *
   * @return bool
   *   Whether the entity is locked.
   */
  public function isEntityLocked(EntityInterface $entity) {
    $entity_type = $entity->getEntityTypeId();

    return $this->isEntityTypeLocked($entity_type);
  }

}
