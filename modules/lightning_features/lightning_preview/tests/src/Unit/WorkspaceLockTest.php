<?php

namespace Drupal\Tests\lightning_preview\Unit;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\lightning_preview\WorkspaceLock;
use Drupal\multiversion\Entity\WorkspaceInterface;
use Drupal\multiversion\Entity\WorkspaceTypeInterface;
use Drupal\multiversion\Workspace\WorkspaceManagerInterface;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\lightning_preview\WorkspaceLockTest
 * @group lightning_preview
 */
class WorkspaceLockTest extends UnitTestCase {

  /**
   * The mocked workspace manager.
   *
   * @var WorkspaceManagerInterface|\Prophecy\Prophecy\ObjectProphecy
   */
  protected $workspaceManager;

  /**
   * The mocked entity type manager.
   *
   * @var EntityTypeManagerInterface|\Prophecy\Prophecy\ObjectProphecy
   */
  protected $entityTypeManager;

  /**
   * The mocked workspace.
   *
   * @var WorkspaceInterface|\Prophecy\Prophecy\ObjectProphecy
   */
  protected $workspace;

  /**
   * The mocked workspace type.
   *
   * @var WorkspaceTypeInterface|\Prophecy\Prophecy\ObjectProphecy
   */
  protected $workspaceType;

  /**
   * The WorkspaceLock instance under test.
   *
   * @var WorkspaceLock
   */
  protected $workspaceLock;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->workspaceManager = $this->prophesize(WorkspaceManagerInterface::class);
    $this->entityTypeManager = $this->prophesize(EntityTypeManagerInterface::class);
    $this->workspace = $this->prophesize(WorkspaceInterface::class);
    $this->workspaceType = $this->prophesize(WorkspaceTypeInterface::class);

    $this->workspaceLock = new WorkspaceLock(
      $this->workspaceManager->reveal(),
      $this->entityTypeManager->reveal()
    );
  }

  /**
   * Tests that the live workspace is never considered locked.
   *
   * @covers ::isWorkspaceLocked
   */
  public function testLiveWorkspaceIsNotLocked() {
    $this->workspace->getMachineName()->willReturn('live');

    $this->workspaceManager->getActiveWorkspace()->willReturn(
      $this->workspace->reveal()
    );

    $this->assertFalse($this->workspaceLock->isWorkspaceLocked());
    $this->assertFalse($this->workspaceLock->isWorkspaceLocked($this->workspace->reveal()));
  }

  /**
   * Tests the influence of moderation states on workspace locking.
   *
   * @covers ::isWorkspaceLocked
   */
  public function testModeratedWorkspaceLock() {
    $this->workspace->getMachineName()->willReturn('foo');
    // TODO: The rest o' this...
  }

}
