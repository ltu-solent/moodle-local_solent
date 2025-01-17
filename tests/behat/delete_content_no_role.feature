@local @local_solent @sol @javascript
Feature: Delete course content if the current user has no role
  In order to ensure users are properly enrolled
  As a user enrolled with no role
  I should see a warning

  Background:
    Given the following "categories" exist:
      | name   | category | idnumber |
      | Parent | 0        | P        |
    And the following "courses" exist:
      | fullname       | shortname | category | format   |
      | Course role    | CR        | P        | onetopic |
    And the following "activities" exist:
      | activity | name               | intro                        | course | idnumber   | section |
      | label    | Test label1 name   | Test label1 name             | CR     | label1     | 1       |
    And the following "users" exist:
      | username | firstname | lastname | email                |
      | student1 | Student   | 1        | student1@example.com |
      | manager1 | Manager   | 1        | manager1@example.com |
      | teacher1 | Teacher   | 1        | teacher1@example.com |
    And the following "course enrolments" exist:
      | user     | course    | role           |
      | student1 | CR        | student        |
      | teacher1 | CR        | editingteacher |
    And the following "role assigns" exist:
      | user     | role    | contextlevel | reference |
      | manager1 | manager | System       |           |
    And the following config values are set as admin:
      | enrol_guest | Yes |
    And I am on the "Course role" "enrolment methods" page logged in as teacher1
    And I click on "Enable" "link" in the "Guest access" "table_row"
    And I log out
    And the following config values are set as admin:
      | config                    | value  | plugin       |
      | enablenoroledeletecontent | 1      | local_solent |

  Scenario: Users with roles should see content
    Given I log in as "student1"
    And I am on "Course role" course homepage
    And I wait "5" seconds
    When I click on "Topic 1" "link" in the "#page-content ul.nav.nav-tabs" "css_element"
    Then I should see "Test label1 name" in the ".course-content" "css_element"
    And I should not see "You are not correctly enrolled on this page." in the "#user-notifications" "css_element"

  Scenario: Users with no roles do not see content
    Given I log in as "teacher1"
    And I am on "Course role" course homepage
    And I navigate to course participants
    And I click on "Student 1's role assignments" "link"
    And I click on "Student" "autocomplete_selection"
    When I click on "Save changes" "link"
    Then I should see "No roles" in the "Student 1" "table_row"
    When I log in as "student1"
    And I am on "Course role" course homepage
    And I wait "5" seconds
    And I click on "Topic 1" "link" in the "#page-content ul.nav.nav-tabs" "css_element"
    Then I should not see "Test label1 name" in the ".course-content" "css_element"
    And I should see "You are not correctly enrolled on this page" in the "#user-notifications" "css_element"

  Scenario: System roles should always see the content
    Given I log in as "manager1"
    And I am on "Course role" course homepage
    And I wait "5" seconds
    When I click on "Topic 1" "link" in the "#page-content ul.nav.nav-tabs" "css_element"
    Then I should see "Test label1 name" in the ".course-content" "css_element"
    And I should not see "You are not correctly enrolled on this page." in the "#user-notifications" "css_element"

  Scenario: Guests should always see the content
    Given I am on "Course role" course homepage
    And I press "Access as a guest"
    And I wait "5" seconds
    When I click on "Topic 1" "link" in the "#page-content ul.nav.nav-tabs" "css_element"
    Then I should see "Test label1 name" in the ".course-content" "css_element"
    And I should not see "You are not correctly enrolled on this page." in the "#user-notifications" "css_element"

  Scenario: Feature is disabled, all users should see content
    Given the following config values are set as admin:
      | config                    | value  | plugin       |
      | enablenoroledeletecontent | 0      | local_solent |
    When I log in as "student1"
    And I am on "Course role" course homepage
    And I wait "5" seconds
    And I click on "Topic 1" "link" in the "#page-content ul.nav.nav-tabs" "css_element"
    Then I should see "Test label1 name" in the ".course-content" "css_element"
    And I should not see "You are not correctly enrolled on this page" in the "#user-notifications" "css_element"
    And I log in as "teacher1"
    And I am on "Course role" course homepage
    And I navigate to course participants
    And I click on "Student 1's role assignments" "link"
    And I click on "Student" "autocomplete_selection"
    When I click on "Save changes" "link"
    Then I should see "No roles" in the "Student 1" "table_row"
    When I log in as "student1"
    And I am on "Course role" course homepage
    And I wait "5" seconds
    And I click on "Topic 1" "link" in the "#page-content ul.nav.nav-tabs" "css_element"
    Then I should see "Test label1 name" in the ".course-content" "css_element"
    And I should not see "You are not correctly enrolled on this page" in the "#user-notifications" "css_element"
    When I log in as "manager1"
    And I am on "Course role" course homepage
    And I wait "5" seconds
    And I click on "Topic 1" "link" in the "#page-content ul.nav.nav-tabs" "css_element"
    Then I should see "Test label1 name" in the ".course-content" "css_element"
    And I should not see "You are not correctly enrolled on this page." in the "#user-notifications" "css_element"
    When I log out
    And I am on "Course role" course homepage
    And I press "Access as a guest"
    And I wait "5" seconds
    And I click on "Topic 1" "link" in the "#page-content ul.nav.nav-tabs" "css_element"
    Then I should see "Test label1 name" in the ".course-content" "css_element"
    And I should not see "You are not correctly enrolled on this page." in the "#user-notifications" "css_element"
