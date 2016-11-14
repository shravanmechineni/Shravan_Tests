@not-on-ci
Feature: As a RND17.com schools teacher, I want to sign up for schools emails via Strip RND-38
  
  IN order to get schools updates
  As a teacher
  I want to sign up for emails quickly and easily

  @RND17General
  Scenario Outline: verify Email Sign up via schools esu strip for General user
    Given I am on RND17 "/schools/find-out-more" on "<Device>"
    And I should see "esu_strip_email" element
    And I see "esu_strip_emai_copy" copy as
      """
      Get exclusive news direct to your inbox.
      """
    And I fill in the "esu_strip_email" field
    And I scroll down the page
    When I click on "esu_strip_email_go" button
    Then I should see "Thanks! Your first email will be with you shortly." text matching in the "esu_strip_thankyoumessage" element
    And I should see "schools_esu_strip_ageGroup_dropDownfield" element
    And I can see a message row for RND17 ESU Strip in the message queue
    And I can see messages to EP mandatory fields for RND17 Schools ESU Strip
    And I can see all the associated data for RND17 Schools ESU strip in the EP database with valid field values

    Examples: 
      | Device  |
      | Desktop |
   #   | Mobile  |
  @RND17Schools
  Scenario Outline: verify Email Sign up via schools esu strip for schools user
    Given I am on RND17 "/schools/find-out-more" on "<Device>"
    And I should see "esu_strip_email" element
    And I fill in the "esu_strip_email" field
    And I scroll down the page
    When I click on "esu_strip_email_go" button
    Then I should see "Thanks! Your first email will be with you shortly." text matching in the "esu_strip_thankyoumessage" element
    And I should see "schools_esu_strip_ageGroup_dropDownfield" element
    When I click on "schools_esu_strip_ageGroup_dropDownfield" element
    Then I see the following list of schools age group options:
      | Age Group                               |
      | Early Years or Nursery                  |
      | Primary                                 |
      | Secondary                               |
      | Further Education or Sixth-Form College |
      | Higher Education                        |
      | Other                                   |
    And I select "<YearPhase>" from the schools age group drop down list
    And I click on "esu_strip_email_submit" button
    And I should see "Great! Now we know what's right for you." text matching in the "esu_strip_second_thankyoumessage" element
    And I should see "esu_strip_second_thankyoumessage" element
    And I can see a message row for RND17 ESU Strip in the message queue
    And I can see messages to EP mandatory fields for RND17 Schools ESU Strip
    And I can see all the associated data for RND17 Schools ESU strip in the EP database with valid field values

    Examples: 
      | Device  | YearPhase                               |
      | Desktop | Early Years or Nursery                  |
#      | Mobile  | Primary                                 |
#      | Dekstop | Secondary                               |
#      | Mobile  | Further Education or Sixth-Form College |
#      | Dekstop | Higher Education                        |
#      | Mobile  | Other                                   |

  @ignore
  Scenario: verify error messages for schools esu strip
    Given I am on RND17 "/schools/find-out-more" on "<Device>"
    And I leave "esu_strip_email" field blank
    And I scroll down the page
    When I click on "esu_strip_email_go" button
    Then I should see "Your email address field is required." text contains in the "esu_strip_email_ErrorMessage" element
    And I fill invalid email address in the "esu_strip_email" field
    When I click on "esu_strip_email_go" button
    Then I should see email is invaid error message text in the "esu_strip_email_ErrorMessage" element
    And I fill in the "esu_strip_email" field
    And I click on "esu_strip_email_go" button
    And I leave "schools_esu_strip_ageGroup_dropDownfield" field blank
    When I click on "esu_strip_email_go" button
    Then I should see "Please select an age group" text contains in the "esu_strip_email_ErrorMessage" element

  @RND17ESUValidation
  Scenario: verify error messages for schools esu strip
    Given I am on RND17 "/schools/find-out-more" on "<Device>"
    And I leave "esu_strip_email" field blank
    And I scroll down the page
    When I click on "esu_strip_email_go" button
    Then I should see "Please enter a valid email address" text contains in the "esu_strip_email_ErrorMessage" element
    And I fill invalid email address in the "esu_strip_email" field
    When I click on "esu_strip_email_go" button
    Then I should see "Please enter a valid email address" text contains in the "esu_strip_email_ErrorMessage" element
    And I fill in the "esu_strip_email" field
    And I click on "esu_strip_email_go" button
    And I leave "schools_esu_strip_ageGroup_dropDownfield" field blank
    When I click on "esu_strip_email_go" button
    Then I should see "Please select an age group." text contains in the "esu_strip_email_ErrorMessage" element

