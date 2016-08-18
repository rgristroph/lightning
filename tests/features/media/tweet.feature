@lightning @media @api
Feature: Twitter media assets
  A media asset representing a tweet.

  @javascript
  Scenario: Creating a tweet
    Given I am logged in as a user with the media_creator role
    When I visit "/media/add/tweet"
    And I enter "https://twitter.com/chx/status/493538461761028096" for "Tweet"
    And I wait for AJAX to finish
    And I enter "Foobaz" for "Media name"
    And I press "Save and publish"
    Then I should be visiting a media entity
    And I should see "Foobaz"
    And I queue the latest media entity for deletion

  Scenario: Viewing a tweet as an anonymous user
    Given I am an anonymous user
    And media entities:
      | bundle | name            | embed_code                                             | status | uid |
      | tweet  | webchick speaks | https://twitter.com/webchick/status/672110599497617408 | 1      | 1   |
    When I visit a media entity of type tweet
    Then I should get a 200 HTTP response
