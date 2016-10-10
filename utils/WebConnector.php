<?php
namespace Utils;

use Behat\MinkExtension\Context\RawMinkContext;

class WebConnector extends RawMinkContext{
	public $testData;
	
	public function __construct()
	{
		$this->testData = new TestData();
	}
	
	public function getValueFromOR($key)
	{
		//$key1= str_replace('"', '', $key);
		$OR_iniarray = parse_ini_file(__DIR__ . "/../configs/OR.ini");
		return  $OR_iniarray[$key];
	}
	
	public function getValueFromConfig($key)
	{
		//$key1= str_replace('"', '', $key);
		$Config_iniarray = parse_ini_file(__DIR__ . "/../configs/Config.ini");
		return  $Config_iniarray[$key];
	}
	
	
	/*
	 * To visit page, takes base_url parameter value as url given in behat.yml file
	 * 
	 */
	public function navigate($url)
	{
		$this->getSession()->visit($this->locatePath($url));
		$this->getSession()->wait(10000);
		return $url;
	}
	
	/**
	 * click by xpath
	 * @param xpath $locator
	 */
	public function clickByXpath($locator)
	{
		try {
			$this->getSession()->getPage()->find('xpath', $this->getValueFromOR($locator))->click();		
		} catch (Exception $e) {
			$e->getTrace();
		}
	}
	
	/**
	 * fill field by xpath
	 * @param xpath $locator
	 * @param $value  
	 */
	public function fillFieldByXpath($locator, $value)
	{
		try {
			$this->getSession()->getPage()->find('xpath', $this->getValueFromOR($locator))->setValue($value);
		} catch (Exception $e) {
			$e->getTrace();
		}
		return $value;
	}
	
	/**
	 * get text by xpath
	 * @param xpath $locator
	 */
	public function getTextByXpath($locator)
	{
		try {
			$text = $this->getSession()->getPage()->find('xpath', $this->getValueFromOR($locator))->getText();				
		} catch (Exception $e) {
			$e->getTrace();
		}
		return $text;		
	}
	
	/**
	 *  Checks whether element present located by it's XPath query.
	 * @param unknown $locator
	 */
	public function isElemetPresentByXpath($locator) 
	{
		if(count($this->getSession()->getPage()->find('xpath', $this->getValueFromOR($locator))) == 0){
			return false;
		}else{
			return true;
		}          
	}
	
	/**
	 *  checks whether text present located by it's XPath query.
	 * @param unknown $locator
	 */
	public function isTextPresentByXpath($locator)
	{
		try {
			$this->getSession()->getPage()->find('xpath', $this->getValueFromOR($locator))->getText();
			return true;
		} catch (\Exception $e) {
			$e->getTrace();
			return false;
		}
	
	}
	
	public function stringContainss($str1,$str2)
	{		
	    $status = false;

        if (strpos($str1, $str2) !== false) {
            $status = true;
        }

        return $status;
	}

	/**
	 *Get the current wondow name
     */
	public function getCurrentWindowName(){
		$windowName = $this->getSession()->getWindowName();
		return $windowName;

	}
	
	/**
	* @param Switch to new tab
	* @param Verify whether expected page has opened or not
	*/
	public function switchToNewTabAndVerifyPageURL($NewTabPageURL)
	{
		$windowNames = $this->getSession()->getWindowNames();
		if(count($windowNames) > 1) {
			$this->getSession()->switchToWindow(end($windowNames));
			$this->assertSession()->addressEquals($this->locatePath($NewTabPageURL));
		}
		return $windowNames[0];
	}
			
	/**
	 * Mouse Hovering onto Element (Menu)and click on ChildElement ( ex: submenu )
	 * @param xpath $menulocator
	 * @param xpath $submenulocator
	 */
	public function hoverAndClickOnSubMenu($menulocator,$submenulocator)
	{
		$menu = $this->getSession()->getPage()->find('xpath', $this->getValueFromOR($menulocator));
		$submenu = $this->getSession()->getPage()->find('xpath', $this->getValueFromOR($submenulocator));
		$menu->mouseOver();
		$submenu->focus();
		$submenu->click();
		
	}
	
	public function isLoggedIn($locator)
	{
		if(count($this->getSession()->getPage()->find('xpath', $this->getValueFromOR($locator))) == 0){
			return false;
		}else{
			return true;
		}			
	}
	
	public function doDefaultLoginIntoWebSite($usernameLocator, $username, $passwordLocator, $password)
	{
		$this->fillFieldByXpath($usernameLocator, $username);
		$this->fillFieldByXpath($passwordLocator, $password);
		$this->clickByXpath('login');
		$this->getSession()->wait(5000);
	}
		
}