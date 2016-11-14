@not-on-ci
Feature: As a RND17.com user, I want to click on footer links of RND17 Site

  In order to naviage to footer pages
  As a user
  I want to verify and click on footer links

  Scenario Outline: Verify RND17 footer menu links
    Given I am on RND17 "http://review:hav3al00k@rednoseday.com" on "<Device>"
    Then I should see the following elements:
      | locators                        |
      | RND17FooterLink_ContactUs       |
      | RND17FooterLink_Privacy         |
      | RND17FooterLink_Legal           |
      | RND17FooterLink_Accessibility   |
      | RND17FooterLink_MediaCentre     |
    When I click on "RND17FooterLink_ContactUs" element
    Then I should see "CR_ContactUs" element
    And I move backward one page
    And I wait for "3000" milliseconds to pageload
    When I click on "RND17FooterLink_Privacy" element
    Then I should see "CR_Privacy" element
    And I move backward one page
    And I wait for "3000" milliseconds to pageload
    When I click on "RND17FooterLink_Legal" element
    Then I should see "CR_Legal" element
    And I move backward one page
    And I wait for "3000" milliseconds to pageload
    When I click on "RND17FooterLink_Accessibility" element
    Then I should see "CR_Accessibility" element
    And I move backward one page
    And I wait for "3000" milliseconds to pageload
    When I click on "RND17FooterLink_MediaCentre" element
    Then I should see "CR_MediaCentre" element
    And I move backward one page

    Examples:
      | Device  |
      | Desktop |

