@javascript @rnd17_sanity
Feature: As a RND17.com QA, I want to do smoke test around critical functionalty

  In order to find critical functionaity issues
  As a QA
  I want to do smoke test

  Scenario: Smoke test on rnd17
    Given I am on the homepage
    And I should see the link "Fundraise"
    And I should see the link "Schools"
    And I should see the link "The difference you make"
    And I should see the link "Donate"
    And I should see "esu_strip_email" element

