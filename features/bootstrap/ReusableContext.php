<?php

use Behat\Behat\Context\SnippetAcceptingContext;
use Utils\TestData;
use Utils\WebConnector;
use Behat\Mink\Driver\Selenium2Driver;
use Behat\Gherkin\Node\TableNode;


class ReusableContext extends WebConnector  implements SnippetAcceptingContext {
	
	public function printData() 
	{
		// echo $this->testData['email'];
		foreach (TestData::$formData as $field => $value ) {
			echo $field . ' => ' . $value . "\n";
		}
	}
	
	/**
	 * @Given I go to SR16 :arg1
	 */
	public function iGoToSr($uri)
	{
		$this->navigate($uri);
		$this->getSession()->wait(5000);
		TestData::$url = $this->getMinkParameter('base_url');
		echo TestData::$url;
	}
	

	/**
	 * @Given I am on SR16 :arg1 on :arg2
	 */
	public function iAmOnSrOn($uri, $device)
	{
		TestData::$device = $device;
		$driver = $this->getSession()->getDriver();
		if ($driver instanceof Selenium2Driver) {
			if($device == "Desktop"){
				$this->getSession()->resizeWindow(1620, 870, 'current');
			}elseif($device == "Mobile"){
				$this->getSession()->resizeWindow(375, 627, 'current');
			}
		}
		$this->navigate($uri);
		$this->getSession()->wait(10000);
		TestData::$url = $this->getMinkParameter('base_url');
		echo TestData::$url;
	}
	
	/**
	 * @Given I am on RND17 :arg1 on :arg2
	 * @Given I navigate to RND17 :arg1 on :arg2
	 */
	public function iAmOnRndOn($uri, $device)
	{			
		TestData::$device = $device;
		$driver = $this->getSession()->getDriver();
		if ($driver instanceof Selenium2Driver) {
			if($device == "Desktop"){
				$this->getSession()->resizeWindow(1620, 870, 'current');
			}elseif($device == "Mobile"){
				$this->getSession()->resizeWindow(375, 627, 'current');
			}
		}
		$this->navigate($uri);		
		$this->getSession()->wait(10000);
		TestData::$url = $this->getMinkParameter('base_url');
		echo TestData::$url;
	}
	
	/**
	 * @Given I am logged in with :arg1 as :arg2 and :arg3 as :arg4
	 * 
	 */
	public function iAmLoggedInAs($usernameLocator, $username, $passwordLocator, $password)
	{

		$this->navigate('/user');
		$this->getSession()->wait(5000);
		if($this->isLoggedIn($usernameLocator)){
			$this->doDefaultLoginIntoWebSite($usernameLocator, $username, $passwordLocator, $password);
		}				
	}

    /**
     * @Given I maximise the browser window
     * @throws \Behat\Mink\Exception\DriverException
     */
    public function iMaximiseTheBrowserWindow()
    {
        $this->getSession()->getDriver()->maximizeWindow();
        $this->getSession()->wait(5000);
    }

    /**
     * @Given I should see :arg1 page header image
     */
    public function iShouldSeeHeaderImage($locator)
    {
        PHPUnit_Framework_TestCase::assertTrue($this->isElemetPresentByXpath($locator),'can not see ' . $locator . ' header image on the page');
    }
	
	/**
	 * @Given I fill in the :arg1 field
	 * @And I enter :arg1
	 */
	public function iFillInTheField($field)
	{
		if(strpos($field, 'email')){
			$value = $this->fillFieldByXpath($field, $this->testData->randomEmailAddress());
		}else if(strpos($field, 'firstname')){
			$value = $this->fillFieldByXpath($field, $this->testData->randomFirstName());
		}else if(strpos($field, 'lastname')){
			$value = $this->fillFieldByXpath($field, $this->testData->randomLastName());
		}else if(strpos($field, 'postcode')){
			$value = $this->fillFieldByXpath($field, $this->testData->randomPostCode());
		}else if(strpos($field, 'int')){
			$value = $this->fillFieldByXpath($field, $this->testData->randomInteger(1, 5000));
		}else if(strpos($field, 'mobile')){
			$value = $this->fillFieldByXpath($field, $this->testData->generateValidUKMobileNumber());
		}else if(strpos($field, 'schoolsname')){
			$value = $this->fillFieldByXpath($field, "Auto Test Schools name");
		}else if(strpos($field, 'addressline1')){
			$value = $this->fillFieldByXpath($field, "Auto address line 1");
		}else if(strpos($field, 'town')){
			$value = $this->fillFieldByXpath($field, "Auto town");
		}
		
		TestData::$formData[$field] = $value;
		echo TestData::$formData[$field];
	}
		
	/**
	 * @Given I click on :arg1 element
	 * @When I click on :arg1 button
	 * @When I click on :arg1 field
	 */
	public function iClickOnElementButton($locator)
	{
		$this->clickByXpath($locator);
		$this->getSession()->wait(5000);
	}

	/**
	 * @Then I wait for :arg1 milliseconds to pageload
	 */
	public function iWaitForMillisecondsToPageload($time)
	{
		$this->getSession()->wait($time);
	}
	
	/**
	 * @Then I should see :arg1 element
	 */
	public function iShouldSeeElement($locator)
	{
        echo 'URL:'. TestData::$url;
		PHPUnit_Framework_TestCase::assertTrue($this->isElemetPresentByXpath($locator),'can not see ' . $locator . ' element on the page');
	}
	
	/**
	 * @Then I should not see :arg1 element
	 */
	public function iShouldNotSeeElement($locator)
	{
		PHPUnit_Framework_TestCase::assertFalse($this->isElemetPresentByXpath($locator),'can see ' . $locator . ' element on the page');
	}
	
	/**
	 * @Then I should see :arg1 text matching in the :arg2 element
	 * @Then I should see :arg1 text in the :arg2 element
	 */
	public function iShouldSeeTextInTheElement($text, $locator)
	{
		PHPUnit_Framework_TestCase::assertEquals($text, $this->getTextByXpath($locator),'can not see '. $text . ' in the ' . $locator . ' element');
	}
	
	/**
	 * @Then I should see :arg1 text contains in the :arg2 element
	 */
	public function iShouldSeeTextContainsInTheElement($text, $locator)
	{
		PHPUnit_Framework_TestCase::assertTrue(strpos($this->getTextByXpath($locator),$text)!== false ,'can not see '. $text . ' in the ' . $locator . ' element');
	}
	
	/**
	 * @Then I should not see :arg1 text matching in the :arg2 element
	 * @Then I should not see :arg1 text in the :arg2 element
	 */
	public function iShouldNotSeeTextInTheElement($text, $locator)
	{
		PHPUnit_Framework_TestCase::assertNotEquals($text, $this->getTextByXpath($locator) ,'can see '. $text . ' in the ' . $locator . ' element');
	}
	
	/**
	 * clicks link with link id, title, text or image alt
	 * @When I click on :arg1 link
	 */
	public function iClickOnLink($locator)
	{
		//$mainWindow = $this->getCurrentWindowName();
		$this->getSession()->getPage()->clickLink($this->getValueFromOR($locator));
		$this->getSession()->wait(3000);
	}

	/**
	 * clicks link with link id, title, text or image alt
	 * @Then I see :arg1 pdf opened in a new tab
	 */
	public function iSeePdfOpenedInANewTab($page)
	{
		$mainWindow = $this->switchToNewTabAndVerifyPageURL($page);
		$this->getSession()->switchToWindow($mainWindow);
	}
	
	/**
	 * @Then I should be on :arg1 page
	 */
	public function iShouldBeOnPage($page)
	{
		$this->assertSession()->addressEquals($this->locatePath($page));
	}
	
	
	/**
	 * Checks, that current page PATH is not equal to specified
	 * @Then I should not be on :arg1 page
	 */
	public function iShouldNotBeOnPage($page)
	{
		$this->assertSession()->addressNotEquals($this->locatePath($page));
	}
	
	/**
	 * 
	 * @Given I leave :arg1 field blank
	 */
	public function iLeaveFieldBlank($field)
	{
		 $this->fillFieldByXpath($field, '');
	}
	
	/**
	 * @When I scroll down the page
	 */
	public function scrollIntoViews(){
		try {
			$this->getSession()->executeScript("scroll(0, 300)");
			$this->getSession()->wait('2000');
		}
		catch(\Exception $e) {
			throw new \Exception("ScrollIntoView failed");
		}
	}
	
	/**
	 * @Then I should see the following elements:
	 */
	public function iShouldSeefollowingElements(TableNode $locators)
	{
		foreach ($locators as $row) {
			PHPUnit_Framework_TestCase::assertTrue($this->isElemetPresentByXpath($row['locators']),'can not see ' . $row['locators'] . ' element on the page');
		}
	}
	
}