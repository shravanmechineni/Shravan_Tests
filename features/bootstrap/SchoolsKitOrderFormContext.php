<?php

use PageObjects\SchoolsKitOrderFormPageObjects;
use Utils\TestData;
use Behat\Gherkin\Node\PyStringNode;
use Utils\EpDbConnectionAndDataExtraction;
use Utils\EpMessageQueueAccess;

class SchoolsKitOrderFormContext extends SchoolsKitOrderFormPageObjects {
	
	/**
	 * @Given I see SVG title for :arg1 page
	 */
	public function iSeeSvgTitleForPage($arg1)
	{
		
	}
	
	/**
	 * @Given I see :arg1 copy as
	 */
	public function iSeeCopyAs($locator, PyStringNode $copy)
	{
		PHPUnit_Framework_TestCase::assertEquals($copy->getRaw() , $this->getTextByXpath($locator), 'Failed:Actual and expected copy is not equal');
	}
	
	/**
	 * @Given I select :arg1 from the fundraising Pack drop down list
	 */
	public function iSelectFromTheFundraisingPackDropDownList($fundraisingPack)
	{
		TestData::$formData['SchoolsfundraisingPack'] = $this->selectFundraisingPackFromTheDropDownlist($fundraisingPack);
		echo "pack: " . TestData::$formData['SchoolsfundraisingPack'];
		
	}
	
	/**
	 * @Given I select :arg1 from the title drop down list
	 */
	public function iSelectFromTheTitleDropDownList($title)
	{
		TestData::$formData['SchoolsfundraisingPack_title'] = $this->selectTitleFromTheDropDownlist($title);
		echo "title: " . TestData::$formData['SchoolsfundraisingPack_title'];
	}
	
	/**
	 * @Given I select :arg1 from the job title drop down list
	 */
	public function iSelectFromTheJobTitleDropDownList($locator)
	{
		TestData::$formData['SchoolsfundraisingPack_jobTitle'] = $this->selectJobTitleFromTheDropDownlist($locator);
		echo "job tile : " . TestData::$formData['SchoolsfundraisingPack_jobTitle'];
	}
	
	/**
	 * @Given I select :arg1  from the establishment type drop down list
	 */
	public function iSelectFromTheEstablishmentTypeDropDownList($establishmentType)
	{
		 TestData::$formData['SchoolsfundraisingPack_establishmentType'] = $this->selectEstablishmentTypeFromTheDropDownlist($establishmentType);
		 echo "est type: " . TestData::$formData['SchoolsfundraisingPack_establishmentType'];
	}
	
	/**
	 * @Given I enter schools postcode in the :arg1 look up field
	 */
	public function iEnterSchoolsPostcodeInTheLookUpField($field)
	{
		$value = $this->fillFieldByXpath($field, $this->getRandomSchoolsPostCode());
	//	$value = $this->fillFieldByXpath($field, "manc");
		TestData::$formData[$field] = $value;
		$this->getSession()->wait(10000);
		/* $element = $this->getSession()->getPage()->find('xpath', $this->getValueFromOR('schoolsKitOrderForm_scoolsPostcode'));
		$element->setValue($this->getRandomSchoolsPostCode());
		$this->getSession()->wait(2000);
		$this->getSession()->getDriver()->keyPress($this->getValueFromOR('schoolsKitOrderForm_scoolsPostcode'), 32);
		$this->getSession()->wait(2000);
		$this->getSession()->getDriver()->keyPress($this->getValueFromOR('schoolsKitOrderForm_scoolsPostcode'), 08);
		$this->getSession()->wait(5000); */
	}
	
	/**
	 * @Given I select one of the schools details from the ajax drop down list
	 */
	public function iSelectOneOfTheSchoolsDetailsFromTheAjaxDropDownList()
	{
		$this->clickByXpath('autoAddressDropdownlist');
		$this->getSession()->wait(3000);
	}
	
	/**
	 * @Given I opt in :arg1 for :arg2 marketing preferences
	 */
	public function iOptInForMarketingPreferences($tick, $field)
	{
		Testdata::$formData[$field] = $tick;
		if($tick == 'Yes'){
			$this->clickByXpath($field);
			$this->getSession()->wait(3000);
		}
	}
	
	/**
	 * @When I can see a Message row for SR16 schools fundraising kit order webform in the message queue
	 */
	public function iCanSeeAMessageRowForSrSchoolsFundraisingKitOrderWebformInTheMessageQueue()
	{
		$baseurl = TestData::$url;
		if (strpos($baseurl, 'qa') !== false) {
			$this->navigatetoMessageQueueWebInterface('MesageQueueUrl_QA', 'schools_preorder_pack');
		}else if (strpos($baseurl, 'test') !== false) {
			$this->navigatetoMessageQueueWebInterface('MesageQueueUrl_Test', 'schools_preorder_pack');
		}else if (strpos($baseurl, 'staging') !== false) {
			$this->navigatetoMessageQueueWebInterface('MesageQueueUrl_Staging', 'schools_preorder_pack');
		}else{
			$this->navigatetoMessageQueueWebInterface('MesageQueueUrl_Prod', 'schools_preorder_pack');
		}
		$this->CheckMessageQueue();
		
	}
	
	/**
	 * @Then I can see messages to EP mandatory fields for Schools pack Queue
	 */
	public function iCanSeeMessagesToEpMandatoryFieldsForSchoolsPackQueue()
	{
		if ((!EpMessageQueueAccess::$jsonMQString['timestamp'] == null) && (strcmp(EpMessageQueueAccess::$jsonMQString['emailAddress'], TestData::$formData['schoolsKitOrderForm_email']) == 0) && strcmp(EpMessageQueueAccess::$jsonMQString['campaign'], 'RND17') == 0){
			PHPUnit_Framework_TestCase::assertNotNull(EpMessageQueueAccess::$jsonMQString['timestamp'],'Failed: Timestamp is null, plz check maually in the MQ');
			PHPUnit_Framework_TestCase::assertTrue((strpos(EpMessageQueueAccess::$jsonMQString['transSourceURL'], '/order-schools-pack') !== false),'Failed: Correct transsource URL was not displayed in EP DB, plz check maually in the MQ');
			PHPUnit_Framework_TestCase::assertEquals('KitOrder', EpMessageQueueAccess::$jsonMQString['transType'], 'Failed:Actual and expected trasType is not equal in EP Queue, plz check maually in the MQ');
			if(strcmp(TestData::$device, 'Desktop') == 0){
				PHPUnit_Framework_TestCase::assertEquals($this->testData->RND17DesktopSchoolsKitTransSource, EpMessageQueueAccess::$jsonMQString['transSource'],'Failed:Actual and expected desktop transSource is not equal in EP Queue, plz check maually in the MQ');
			}else if(strcmp(TestData::$device, 'Mobile') == 0){
				PHPUnit_Framework_TestCase::assertEquals($this->testData->RND17MobileSchoolsKitTransSource, EpMessageQueueAccess::$jsonMQString['transSource'],'Failed:Actual and expected mobile transSource is not equal in EP Queue, plz check maually in the MQ');
			}
			PHPUnit_Framework_TestCase::assertEquals('RND17', EpMessageQueueAccess::$jsonMQString['campaign'], 'Failed:Actual and expected Campaign is not equal in EP Queue, plz check maually in the MQ');
		}else{
			echo 'Messages are consumed before looking into MessageQueue' ;
		}
		
	}
	
	/**
	 * @When I can see all the associated data for SR16 schools fundraising kit order webform journey in EP database with valid field values
	 */
	public function iCanSeeAllTheAssociatedDataForSrSchoolsFundraisingKitOrderWebformJourneyInEpDatabaseWithValidFieldValues()
	{
		 $db = new EpDbConnectionAndDataExtraction();
		 $db->dbVerifySchoolsPreorderPack(TestData::$formData['schoolsKitOrderForm_email'], $this->testData->dbServerTest);
		 if ((!EpMessageQueueAccess::$jsonMQString['timestamp'] == null) && (strcmp(EpMessageQueueAccess::$jsonMQString['emailAddress'], TestData::$formData['schoolsKitOrderForm_email']) == 0) && strcmp(EpMessageQueueAccess::$jsonMQString['campaign'], 'RND17') == 0){
		 	PHPUnit_Framework_TestCase::assertEquals(EpMessageQueueAccess::$jsonMQString['timestamp'], $db->dbData['Timestamp'], "Failed:Actual and expected Timestamp is not equal in EP DB, Plz check manually ");
		 }else{
		 	echo "Messages are consumed before looking into MessageQueue-DatabaseTimestamp:" . $db->dbData['Timestamp'];
		 }
		 PHPUnit_Framework_TestCase::assertNotNull($db->dbData['Timestamp'], "Failed: Timestamp is null: Plz check manually");
		 PHPUnit_Framework_TestCase::assertEquals(TestData::$formData['schoolsKitOrderForm_email'], $db->dbData['email'], 'Failed:Actual and expected Email is not equal in EP DB, plz check maually in the DB');
		 PHPUnit_Framework_TestCase::assertTrue((strpos($db->dbData['TransSourceURL'], '/order-schools-pack') !== false),'Failed: Correct transsource URL was not displayed in EP DB, plz check maually in the MQ');
		 PHPUnit_Framework_TestCase::assertEquals('KitOrder', $db->dbData['TransType'], 'Failed:Actual and expected trasType are not equal in EP DB, plz check maually in the DB');
		 PHPUnit_Framework_TestCase::assertEquals('RND17', $db->dbData['Campaign'], 'Failed:Actual and expected Campaign are not equal in EP DB, plz check maually in the DB');
		 if(strcmp(TestData::$device, 'Desktop') == 0){
		 	PHPUnit_Framework_TestCase::assertEquals($this->testData->RND17DesktopSchoolsKitTransSource, $db->dbData['TransSource'],'Failed:Actual and expected desktop transSource is not equal in EP Queue, plz check maually in the MQ');
		 }else if(strcmp(TestData::$device, 'Mobile') == 0){
		 	PHPUnit_Framework_TestCase::assertEquals($this->testData->RND17MobileSchoolsKitTransSource, $db->dbData['TransSource'],'Failed:Actual and expected mobile transSource is not equal in EP Queue, plz check maually in the MQ');
		 }
		 PHPUnit_Framework_TestCase::assertEquals(TestData::$formData['SchoolsfundraisingPack'], $db->dbData['KitType'], 'Failed:Actual and expected Kit Type is not equal in EP DB, plz check maually in the DB');
		 PHPUnit_Framework_TestCase::assertEquals(TestData::$formData['SchoolsfundraisingPack_title'], $db->dbData['title'], 'Failed:Actual and expected title is not equal in EP DB, plz check maually in the DB');
		 PHPUnit_Framework_TestCase::assertEquals(TestData::$formData['SchoolsfundraisingPack_jobTitle'], $db->dbData['jobTitle'], 'Failed:Actual and expected job title is not equal in EP DB, plz check maually in the DB');
		 PHPUnit_Framework_TestCase::assertEquals(TestData::$formData['SchoolsfundraisingPack_establishmentType'], $db->dbData['establishmentType'], 'Failed:Actual and expected establishment type is not equal in EP DB, plz check maually in the DB');
		 PHPUnit_Framework_TestCase::assertEquals(TestData::$formData['schoolsKitOrderForm_schoolsname'], $db->dbData['SchoolName'], 'Failed:Actual and expected School name is not equal in EP DB, plz check maually in the DB');
		 PHPUnit_Framework_TestCase::assertEquals(TestData::$formData['schoolsKitOrderForm_schools_addressline1'], $db->dbData['SchoolAddress1'], 'Failed:Actual and expected School address is not equal in EP DB, plz check maually in the DB');
		 PHPUnit_Framework_TestCase::assertEquals(TestData::$formData['schoolsKitOrderForm_schools_town'], $db->dbData['SchoolTown'], 'Failed:Actual and expected School town is not equal in EP DB, plz check maually in the DB');
		 PHPUnit_Framework_TestCase::assertEquals(TestData::$formData['schoolsKitOrderForm_schools_postcode'], $db->dbData['SchoolPostcode'], 'Failed:Actual and expected School post code is not equal in EP DB, plz check maually in the DB');
		 if(TestData ::$formData['schoolsKitOrder_emails_Checkbox'] == 'Yes'){
		 	PHPUnit_Framework_TestCase::assertEquals('teacher', $db->dbData['emailUpdates'], 'Failed:Actual and expected email update status is not equal in EP DB, plz check maually in the DB');
		 }else{
		 	PHPUnit_Framework_TestCase::assertEquals( 'none' , $db->dbData['emailUpdates'], 'Failed:Actual and expected email update status is not equal in EP DB, plz check maually in the DB');
		 }
		 if(TestData ::$formData['schoolsKitOrder_posts_CheckBox'] == 'Yes'){
		 	PHPUnit_Framework_TestCase::assertEquals('yes', $db->dbData['postalUpdates'], 'Failed:Actual and expected postal update status is not equal in EP DB, plz check maually in the DB');
		 }else{
		 	PHPUnit_Framework_TestCase::assertEquals( 'none' , $db->dbData['postalUpdates'], 'Failed:Actual and expected postal update status is not equal in EP DB, plz check maually in the DB');
		 }
		 
	}
	
	/**
	 * @Then I should see error messages for all the mandatory fields of schools kit form
	 */
	public function iShouldSeeErrorMessagesForAllTheMandatoryFieldsOfSchoolsKitForm(){
		PHPUnit_Framework_TestCase::assertEquals("Choose your fundraising kit field is required.", $this->getTextByXpath('schoolsKitOrderForm_fundraisingkit_errormessage'), 'Failed: can not see error message for fundraisingkit drop down field');
		PHPUnit_Framework_TestCase::assertEquals("Title field is required.", $this->getTextByXpath('schoolsKitOrderForm_title_errormessage'), 'Failed: can not see error message for title field');
		PHPUnit_Framework_TestCase::assertEquals("First name field is required.", $this->getErrorMessagesText('schoolsKitOrderForm_details_fields_errormessages', '1'), 'Failed: can not see error message for first name field');
		PHPUnit_Framework_TestCase::assertEquals("Last name field is required.", $this->getErrorMessagesText('schoolsKitOrderForm_details_fields_errormessages', '2'), 'Failed: can not see error message for last name field');
		PHPUnit_Framework_TestCase::assertEquals("Job title field is required.", $this->getErrorMessagesText('schoolsKitOrderForm_details_fields_errormessages', '3'), 'Failed: can not see error message for job title field');
		PHPUnit_Framework_TestCase::assertEquals("Establishment type field is required.", $this->getErrorMessagesText('schoolsKitOrderForm_details_fields_errormessages', '4'), 'Failed: can not see error message for establishment type drop down field');
		PHPUnit_Framework_TestCase::assertEquals("Email address field is required.", $this->getErrorMessagesText('schoolsKitOrderForm_details_fields_errormessages', '5'), 'Failed: can not see error message for email address field');
		PHPUnit_Framework_TestCase::assertEquals("Please fill in your establishment name", $this->getErrorMessagesText('schoolsKitOrderForm_schoolsaddress_fields_errormessages', '2'), 'Failed: can not see error message for establishment name field');
		PHPUnit_Framework_TestCase::assertEquals("Please fill in your address", $this->getErrorMessagesText('schoolsKitOrderForm_schoolsaddress_fields_errormessages', '4'), 'Failed: can not see error message for schools address field');
		PHPUnit_Framework_TestCase::assertEquals("Please fill in your town/city", $this->getErrorMessagesText('schoolsKitOrderForm_schoolsaddress_fields_errormessages', '8'), 'Failed: can not see error message for schools city field');
		PHPUnit_Framework_TestCase::assertEquals("Please fill in your post code", $this->getErrorMessagesText('schoolsKitOrderForm_schoolsaddress_fields_errormessages', '10'), 'Failed: can not see error message for schools post code field');
	}
}