@local @local_solent @sol @javascript
Feature: Only the first n section names can be changes
  In order to preserve the SOL template structure
  As a teacher
  I have restrictions on which sections I can fully manage
  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email            |
      | teacher1 | Teacher   | 1        | teacher1@example.com |
    And the following "categories" exist:
      | name | category | idnumber |
      | Modules | 0 | modules_CAT |
      | Courses | 0 | courses_CAT |
    And the following "courses" exist:
      | fullname | shortname | format   | coursedisplay | numsections | category    |
      | Course 1 | C1        | onetopic | 0             | 5           | modules_CAT |
      | Course 2 | C2        | onetopic | 0             | 5           | courses_CAT |
    And the following "activities" exist:
      | activity   | name                   | intro                         | course | idnumber    | section |
      | assign     | Test assignment name   | Test assignment description   | C1     | assign1     | 0       |
      | book       | Test book name         | Test book description         | C1     | book1       | 1       |
      | chat       | Test chat name         | Test chat description         | C1     | chat1       | 4       |
      | choice     | Test choice name       | Test choice description       | C1     | choice1     | 5       |
      | assign     | Test assignment name   | Test assignment description   | C2     | assign1     | 0       |
      | book       | Test book name         | Test book description         | C2     | book1       | 1       |
      | chat       | Test chat name         | Test chat description         | C2     | chat1       | 4       |
      | choice     | Test choice name       | Test choice description       | C2     | choice1     | 5       |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | teacher1 | C2     | editingteacher |

  Scenario: Cannot change the name of a locked section for a course in modules_ category
    Given the following config values are set as admin:
      | locksections        | 2        | format_onetopic |
      | locksectioncategory | modules_ | format_onetopic |
    And I log in as "teacher1"
    And I am on "C1" course homepage with editing mode on
    And I click on "Topic 2" "link" in the "#page-content ul.nav.nav-tabs" "css_element"
    When I edit the section "2"
    Then the "Section name" "field" should be disabled
    When I am on "C1" course homepage
    And I click on "Topic 4" "link" in the "#page-content ul.nav.nav-tabs" "css_element"
    When I edit the section "4"
    And I set the field "Section name" to "My section 4"
    And I press "Save changes"
    Then I should see "My section 4" in the "#page-content ul.nav.nav-tabs" "css_element"

  Scenario: Can change the name of a section for a course not in modules_ category
    Given the following config values are set as admin:
      | locksections        | 2        | format_onetopic |
      | locksectioncategory | modules_ | format_onetopic |
    And I log in as "teacher1"
    And I am on "C2" course homepage with editing mode on
    And I click on "Topic 2" "link" in the "#page-content ul.nav.nav-tabs" "css_element"
    When I edit the section "2" and I fill the form with:
      | Section name | My section 2 |
    Then I should see "My section 2" in the "#page-content ul.nav.nav-tabs" "css_element"
