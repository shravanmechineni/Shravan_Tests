@not-on-travis
Feature: As a RND17 schools fundraiser I want to register for receiving a schools fundraising kit RND-39
  
  As a school receiving the DM
  I would like to be able to register my interest in receiving a fundraising kit
  So that I do not have to wait until after the summer holidays when I might forget

  Background: 
    Given I am logged in with "username" as "qa-developer" and "password" as "qa-developer&2"

  @RND17SchoolsKitOrderFormEE
  Scenario Outline: verify end to end functionality of schools fundraiaing kit form
    #    Given I am on RND17 "/" on "<Device>"
    And I navigate to RND17 "/schools-pack" on "<Device>"
    And I see SVG title for "schoolsKitOrderForm_svg_title" page
    And I see "schoolsKitOrderForm_svg_copy" copy as
      """
      svg copy here
      """
    And I click on "schoolsKitOrderForm_fundraisingkit_dropdown" field
    And I select "<Fundraising pack>" from the fundraising Pack drop down list
    And I click on "schoolsKitOrderForm_title_dropdown" field
    And I select "<Title>" from the title drop down list
    And I fill in the "schoolsKitOrderForm_firstname" field
    And I fill in the "schoolsKitOrderForm_lastname" field
    And I click on "schoolsKitOrderForm_jobtitle_dropdown" field
    And I select "schoolsKitOrderForm_jobtitle_list" from the job title drop down list
    And I click on "establishmentType_dropdown" field
    And I select "<Establishment type>"  from the establishment type drop down list
    And I fill in the "schoolsKitOrderForm_email" field
    And I fill in the "schoolsKitOrderForm_mobile" field
    #    And I enter schools postcode in the "schoolsKitOrderForm_scoolsPostcode" look up field
    #    And I select one of the schools details from the ajax drop down list
    When I click on "schoolsKitOrderForm_manualaddressentry_link" element
    Then I should see the following elements:
      | locators                                 |
      | schoolsKitOrderForm_schoolsname          |
      | schoolsKitOrderForm_schools_addressline1 |
      | schoolsKitOrderForm_schools_town         |
      | schoolsKitOrderForm_schools_postcode     |
    And I fill in the "schoolsKitOrderForm_schoolsname" field
    And I fill in the "schoolsKitOrderForm_schools_addressline1" field
    And I fill in the "schoolsKitOrderForm_schools_town" field
    And I fill in the "schoolsKitOrderForm_schools_postcode" field
    And I opt in "<ESUcheckbox>" for "schoolsKitOrder_emails_Checkbox" marketing preferences
    And I click on "termsAndConditions_checkbox" element
    And I opt in "<Posatlcheckbox>" for "schoolsKitOrder_posts_CheckBox" marketing preferences
    When I click on "schoolsKitOrderForm_submit" button
    Then I should be on "/thank-you" page
    And I can see a Message row for SR16 schools fundraising kit order webform in the message queue
    And I can see messages to EP mandatory fields for Schools pack Queue
    And I can see all the associated data for SR16 schools fundraising kit order webform journey in EP database with valid field values

    Examples: 
      | Device  | Fundraising pack | Title | Establishment type    | ESUcheckbox | Posatlcheckbox |
      | Desktop | Primary          | Mr    | Primary               | Yes         | No             |
      | Desktop | Secondary        | Ms    | Secondary             | No          | Yes            |
      | Desktop | Nursery          | Mrs   | Nursery               | Yes         | Yes            |
      | Desktop | Secondary        | Mr    | Sixth form/FE College | No          | No             |

  @RND17SchoolsKitOrderFormValidation
  Scenario Outline: verify error message validation
    And I navigate to RND17 "/schools-pack" on "<Device>"
    When I click on "schoolsKitOrderForm_submit" button
    Then I should see error messages for all the mandatory fields of schools kit form

    Examples: 
      | Device  |
      | Desktop |

