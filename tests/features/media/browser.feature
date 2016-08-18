@lightning @media @api @javascript
Feature: Media browser

  Scenario: Uploading an image from within the media browser
    Given I am logged in as a user with the media_manager role
    When I visit "/entity-browser/iframe/media_browser"
    And I click "Upload"
    And I attach the file "puppy.jpg" to "File"
    And I wait for AJAX to finish
    And I enter "Foobazzz" for "Media name"
    And I press "Place"
    And I visit "/admin/content/media"
    Then I should see "Foobazzz"
    And I queue the latest media entity for deletion

  Scenario: Uploading a document from within the media browser
    Given I am logged in as a user with the media_manager role
    When I visit "/entity-browser/iframe/media_browser"
    And I click "Upload"
    And I attach the file "internet.pdf" to "File"
    And I wait for AJAX to finish
    And I enter "A rant about the Internet" for "Media name"
    And I press "Place"
    And I visit "/admin/content/media"
    Then I should see "A rant about the Internet"
    And I queue the latest media entity for deletion

  Scenario: Creating a YouTube video from within the media browser
    Given I am logged in as a user with the media_manager role
    When I visit "/entity-browser/iframe/media_browser"
    And I click "Create Embed"
    And I enter "https://www.youtube.com/watch?v=zQ1_IbFFbzA" for embed_code
    And I wait 1 second
    And I wait for AJAX to finish
    And I enter "The Pill Scene" for "Media name"
    And I press "Place"
    And I visit "/admin/content/media"
    Then I should see "The Pill Scene"
    And I queue the latest media entity for deletion

  Scenario: Creating a Vimeo video from within the media browser
    Given I am logged in as a user with the media_manager role
    When I visit "/entity-browser/iframe/media_browser"
    And I click "Create Embed"
    And I enter "https://vimeo.com/14782834" for embed_code
    And I wait 1 second
    And I wait for AJAX to finish
    And I enter "Cache Rules Everything Around Me" for "Media name"
    And I press "Place"
    And I visit "/admin/content/media"
    Then I should see "Cache Rules Everything Around Me"
    And I queue the latest media entity for deletion

  Scenario: Creating a tweet from within the media browser
    Given I am logged in as a user with the media_manager role
    When I visit "/entity-browser/iframe/media_browser"
    And I click "Create Embed"
    And I enter "https://twitter.com/AprilTrubody/status/707226928730742784" for embed_code
    And I wait 1 second
    And I wait for AJAX to finish
    And I enter "chx speaks" for "Media name"
    And I press "Place"
    And I visit "/admin/content/media"
    Then I should see "chx speaks"
    And I queue the latest media entity for deletion

  Scenario: Creating an Instagram post from within the media browser
    Given I am logged in as a user with the media_manager role
    When I visit "/entity-browser/iframe/media_browser"
    And I click "Create Embed"
    And I enter "https://www.instagram.com/p/jAH6MNINJG" for embed_code
    And I wait 1 second
    And I wait for AJAX to finish
    And I enter "Drupal Does LSD" for "Media name"
    And I press "Place"
    And I visit "/admin/content/media"
    Then I should see "Drupal Does LSD"
    And I queue the latest media entity for deletion

  Scenario: Uploading an image through the image browser
    Given an Image field called hero_image on the page node type
    And I am logged in as a user with the page_creator role
    When I visit "/node/add/page"
    And I press "Select Image(s)"
    And I wait for AJAX to finish
    And I enter the image_browser entity browser
    And I wait 10 seconds
    And I click "Upload"
    And I attach the file "puppy.jpg" to "File"
    And I wait for AJAX to finish
    And I enter "Lookit the cutie pie!" for "Media name"
    And I complete the entity browser
    And I wait 10 seconds
    And I wait for AJAX to finish
    Then I should not see a "table[drupal-data-selector='edit-image-current'] td.empty" element
    And I queue the latest media entity for deletion

  Scenario: Opening the media browser on a pre-existing node
    Given I am logged in as a user with the "page_creator,page_reviewer,media_creator" roles
    And media entities:
      | bundle | name            | embed_code                                                  | status | field_media_in_library |
      | tweet  | Here be dragons | https://twitter.com/50NerdsofGrey/status/757319527151636480 | 1      | 1                      |
    When I visit "/node/add/page"
    And I enter "Blorgzville" for "Title"
    And I open the media browser
    And I select item 1 in the media browser
    And I complete the media browser selection
    And I wait 5 seconds
    And I press "Save"
    And I click "Edit draft"
    And I wait 10 seconds
    And I open the media browser
    And I wait for AJAX to finish
    Then I should see a "form.entity-browser-form" element
    And I queue the latest node entity for deletion
