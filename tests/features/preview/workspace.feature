@lightning @preview @api
Feature: Workspaces

  Scenario: Locking a workspace by publishing it
    Given I am logged in as a user with the content_manager,workspace_reviewer roles
    When I visit "/admin/structure/workspace/2/edit"
    And I press "Save and Publish"
    And I visit "/admin/structure/workspace/2/activate"
    And I press "Activate"
    And I go to "/node/add"
    Then the response status code should be 403
    And I visit "/admin/structure/workspace/2/edit"
    And I press "Save and Create New Draft"

  Scenario: Configuration entities are unconditionally locked in every workspace except the Live workspace
    Given I am logged in as a user with the administrator role
    When I visit "/admin/structure/workspace/2/activate"
    And I press "Activate"
    And I visit "/admin/structure/block"
    And I press "Save blocks"
    Then the response status code should be 500
    And I should see "Configuration can only be modified in the Live workspace"

  Scenario: Configuration entity form routes cannot be accessed in any workspace except the Live workspace
    Given I am logged in as a user with the administrator role
    When I visit "/admin/structure/workspace/2/activate"
    And I press "Activate"
    And I visit "/admin/config/content/formats"
    Then I should not see an "Add text format" link

  Scenario: Configuration entity forms protected by standard permissions cannot be accessed in any workspace except the Live workspace
    Given I am logged in as a user with the administrator role
    When I visit "/admin/structure/workspace/2/activate"
    And I press "Activate"
    And I visit "/admin/structure/workbench-moderation/states/draft"
    Then I should see "Configuration can only be modified in the Live workspace."
    And I should not see the button "Save"
