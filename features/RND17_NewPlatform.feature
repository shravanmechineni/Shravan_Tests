@not-on-ci
Feature: As a RND17.com user, I want to see new Platform page

  In order to check the content on RND17 site
  As a user
  I want to visit RND17 site

  Scenario Outline: Verify RND17 New platform page rednoseday.com
    Given I am on RND17 "http://review:hav3al00k@rednoseday.com" on "<Device>"
    And I see SVG title for "RND17_HomePageSVG" page
    Then I should see the following elements:
      | locators                         |
      | RND17_HomePage_LaughRow          |
      | RND17_HomePage_KidsLoveRow       |
      | RND17_HomePage_HelpingOthersRow  |
      | RND17_HomePage_SurpriseRow       |
      | RND17_HomePage_PickYourNoseRow   |
      | RND17_HomePage_SchoolOnBoardRow  |
      | RND17_HomePage_ReadyToGoRow      |
    When I click on "RND17_SchoolCouncilGuideLink" link
    Then I see "https://www.rednoseday.com/sites/rednoseday.com/files/downloadables/RND17_Schools_Council_Guide_SY17_SS_FR_5.pdf?_ga=1.96047253.867829002.1447768287" pdf opened in a new tab
    When I click on "RND17_TeachersGuideLink" link
    Then I see "https://www.rednoseday.com/sites/rednoseday.com/files/downloadables/RND17_Schools_Teacher_Guide_SY17_SS_FR.pdf?_ga=1.192516899.867829002.1447768287" pdf opened in a new tab

    Examples:
      | Device  |
      | Desktop |

