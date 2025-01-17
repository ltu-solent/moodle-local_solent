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
    And the following "activities" exist:
      | activity | name         | course | idnumber  | section |
      | page     | TestPage     | C1     | page1     | 0       |
      | assign   | TestAssign   | C1     | assign1   | 0       |
      | lesson   | TestLesson   | C1     | lesson1   | 0       |
      | feedback | TestFeedback | C1     | feedback1 | 0       |

  Scenario: No activities are hidden in the activity chooser
    Given the following config values are set as admin:
      | hiddenactivities |  | local_solent |
    And I am logged in as user1
    And I am on "Course 1" course homepage with editing mode on
    And I should see "TestPage" in the "General" "section"
    And I should see "TestAssign" in the "General" "section"
    And I should see "TestLesson" in the "General" "section"
    And I should see "TestFeedback" in the "General" "section"
    When I click on "Add an activity or resource" "button" in the "General" "section"
    Then I should see "Add an activity or resource" in the ".modal-title" "css_element"
    And I should see "Page" in the ".modal-body" "css_element"
    And I should see "Lesson" in the ".modal-body" "css_element"
    And I should see "Feedback" in the ".modal-body" "css_element"

  Scenario: Lesson and Feedback activities are hidden in the activity chooser
    Given the following config values are set as admin:
      | hiddenactivities | lesson,feedback | local_solent |
    And I am logged in as user1
    And I am on "Course 1" course homepage with editing mode on
    And I should see "TestPage" in the "General" "section"
    And I should see "TestAssign" in the "General" "section"
    And I should see "TestLesson" in the "General" "section"
    And I should see "TestFeedback" in the "General" "section"
    When I click on "Add an activity or resource" "button" in the "General" "section"
    Then I should see "Add an activity or resource" in the ".modal-title" "css_element"
    And I should see "Page" in the ".modal-body" "css_element"
    And I should not see "Lesson" in the ".modal-body" "css_element"
    And I should not see "Feedback" in the ".modal-body" "css_element"
