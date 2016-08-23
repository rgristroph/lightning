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
