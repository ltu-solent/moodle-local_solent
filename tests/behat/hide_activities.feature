@local @local_solent @javascript
Feature: Testing hide_activities in local_solent
  In order to limit the use of some activities
  As an administrator
  I need to prevent adding hidden activities

  Background:
    Given the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    And the following "users" exist:
      | username | firstname | lastname |
      | user1 | User | One |
    And the following "course enrolments" exist:
      | user | course | role |
      | user1 | C1 | editingteacher |

  Scenario: No activities are hidden
    Given the following config values are set as admin:
      | hiddenactivities |  | local_solent |
    And I am logged in as user1
    And I am on "Course 1" course homepage with editing mode on
    Given I click on "Add an activity or resource" "button" in the "Topic 1" "section"
    Then I should see "Add an activity or resource" in the ".modal-title" "css_element"
    And I should see "Page" in the ".modal-body" "css_element"
    And I should see "Lesson" in the ".modal-body" "css_element"
    And I should see "Feedback" in the ".modal-body" "css_element"

  Scenario: Lesson and Feedback activities are hidden
    Given the following config values are set as admin:
      | hiddenactivities | lesson,feedback | local_solent |
    And I am logged in as user1
    And I am on "Course 1" course homepage with editing mode on
    Given I click on "Add an activity or resource" "button" in the "Topic 1" "section"
    Then I should see "Add an activity or resource" in the ".modal-title" "css_element"
    And I should see "Page" in the ".modal-body" "css_element"
    And I should not see "Lesson" in the ".modal-body" "css_element"
    And I should not see "Feedback" in the ".modal-body" "css_element"