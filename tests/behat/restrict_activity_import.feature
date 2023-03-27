@local @local_solent @sol @javascript @theme_solent
Feature: Restrict specific activities being imported
  In order to only copy non-templated content between courses
  As a teacher
  Specified activities will be automatically be disabled and deselected for import

  Background:
    Given the following "courses" exist:
      | fullname     | shortname | catgory |
      | Old Course 1 | OC1       | 0       |
      | New Course 1 | NC1       | 0       |
    And the following "users" exist:
      | username | firstname | lastname | email                |
      | teacher1 | Teacher   | 1        | teacher1@example.com |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | OC1    | editingteacher |
      | teacher1 | NC1    | editingteacher |
    And I log in as "admin"
    And I navigate to "Plugins > Local plugins > Solent modifications" in site administration
    And I click on "Backup" "link"
    And I set the field "Restrict backup activities" to multiline:
    """
activity=data|title=Test database name
activity=forum|title=Test forum name
activity=label|title=Test label1 name
activity=label|title=Test . name
activity=label|title=Collaborative learningCollaborative learning refer...
    """
    And I press "Save changes"
    And the following config values are set as admin:
      | config | value        | plugin |
      | theme  | solent |        |

  @javascript
  Scenario: Import course's contents to another course
    Given I log in as "teacher1"
    And the following "activities" exist:
      | activity | name               | intro                        | course | idnumber   | section |
      | data     | Test database name | Test database description    | OC1    | database1  | 2       |
      | forum    | Test forum name    | Test forum name description  | OC1    | forum1     | 1       |
      | label    | Test label1 name   | Test label1 name             | OC1    | label1     | 1       |
      | label    | Test label2 name   | Test label2 name             | OC1    | label2     | 1       |
      | label    | Collaborative learningCollaborative learning refer... | <h4><div class="outline-box outline-box-teal fa-style">Collaborative learning</div></h4><span>Collaborative learning refers to learning which will take place&nbsp;</span><span data-contrast="auto">onsite, with interactive, problem-based and&nbsp;active&nbsp;learning</span>&nbsp;activities. This will include plenty of opportunity for g<span data-contrast="auto">roup&nbsp;work, p</span><span data-contrast="auto">eer exchange and peer-learning.</span><br> | OC1 | label3 | 1 |
      | label    | Test A name        | Test A name                  | OC1    | label4     | 1       |
      | label    | Test . name        | Test . name                  | OC1    | label5     | 1       |
    And I am on "Old Course 1" course homepage with editing mode on
    And I add the "Comments" block
    And I add the "Recent blog entries" block
    And I turn editing mode off
    And I am on the "New Course 1" "import" page
    And I click on "OC1" "radio"
    When I press "Continue"
    Then I should see "Import settings"
    And I should not see "Jump to final step"
    When I press "Next"
    And I should see "Include:"
    And the "Test label1 name" "checkbox" should be disabled
    And the "Collaborative learningCollaborative learning refer..." "checkbox" should be disabled
    And the "Test database name" "checkbox" should be disabled
    And the "Test forum name" "checkbox" should be disabled
    And the "Test . name" "checkbox" should be disabled
    And the "Test label2 name" "checkbox" should be enabled
    And the "Test A name" "checkbox" should be enabled
