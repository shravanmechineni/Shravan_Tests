<?php

use Behat\Behat\Context\SnippetAcceptingContext;
use Utils\EpDbConnectionAndDataExtraction;
use Utils\TestData;
use Utils\EpMessageQueueAccess;
use Behat\Gherkin\Node\TableNode;
use PageObjects\ESUPageObjects;

class ESUContext extends ESUPageObjects implements SnippetAcceptingContext{
	
	/**
	 * @Then I can see a message row for SR16 ESU in the message queue
	 */
	public function iCanSeeAMessageRowForSrEsuInTheMessageQueue()
	{
		$baseurl = TestData::$url;
		if (strpos($baseurl, 'qa') !== false) {
			$this->navigatetoMessageQueueWebInterface('MesageQueueUrl_QA', 'esu');
		}else if (strpos($baseurl, 'test') !== false) {
			$this->navigatetoMessageQueueWebInterface('MesageQueueUrl_Test', 'esu');
		}else if (strpos($baseurl, 'staging') !== false) {
			$this->navigatetoMessageQueueWebInterface('MesageQueueUrl_Staging', 'esu');
		}else{
			$this->navigatetoMessageQueueWebInterface('MesageQueueUrl_Prod', 'esu');
		}
		$this->CheckMessageQueueESU();
	}

	/**
	 * @Then I can see messages to EP mandatory fields for ESU Queue
	 */
	public function iCanSeeMessagesToEpMandatoryFieldsForEsuQueue()
	{
		if ((!EpMessageQueueAccess::$jsonMQString['timestamp'] == null) && (strcmp(EpMessageQueueAccess::$jsonMQString['email'], TestData::$formData['esu_widget_email']) == 0) && strcmp(EpMessageQueueAccess::$jsonMQString['campaign'], 'SR16') == 0){
			PHPUnit_Framework_TestCase::assertNotNull(EpMessageQueueAccess::$jsonMQString['timestamp'],'Failed: Timestamp is null, plz check maually in the MQ');
			PHPUnit_Framework_TestCase::assertTrue((strpos(EpMessageQueueAccess::$jsonMQString['transSourceUrl'], '/homepage') !== false),'Failed: Correct transsource URL was not displayed in EP DB, plz check maually in the MQ');
			PHPUnit_Framework_TestCase::assertEquals('subscribe', EpMessageQueueAccess::$jsonMQString['transType'], 'Failed:Actual and expected trasType are not equal in EP Queue, plz check maually in the MQ');
			if(strcmp(TestData::$device, 'Desktop') == 0){
				PHPUnit_Framework_TestCase::assertEquals($this->testData->DesktopESUHWTransSource, EpMessageQueueAccess::$jsonMQString['transSource'],'Failed:Actual and expected desktop transSource are not equal in EP Queue, plz check maually in the MQ');
			}else if(strcmp(TestData::$device, 'Mobile') == 0){
				PHPUnit_Framework_TestCase::assertEquals($this->testData->MobileESUHWTransSource, EpMessageQueueAccess::$jsonMQString['transSource'],'Failed:Actual and expected mobile transSource are not equal in EP Queue, plz check maually in the MQ');
			}
			PHPUnit_Framework_TestCase::assertEquals('SR16', EpMessageQueueAccess::$jsonMQString['campaign'], 'Failed:Actual and expected Campaign are not equal in EP Queue, plz check maually in the MQ');
		}else{
			echo 'Messages are consumed before looking into MessageQueue' ;
		}		 
		
	}
	
	/**
	 * @Then I can see all the associated data for SR16 ESU in EP database with valid field values
	 */
	public function iCanSeeAllTheAssociatedDataForSrEsuInEpDatabaseWithValidFieldValues()
	{
		$db = new EpDbConnectionAndDataExtraction();
		//echo TestData::$formData['esu_widget_email'];
		$db->dbEmailSubscribes(TestData::$formData['esu_widget_email'], $this->testData->dbServerTest);
		if ((!EpMessageQueueAccess::$jsonMQString['timestamp'] == null) && (strcmp(EpMessageQueueAccess::$jsonMQString['email'], TestData::$formData['esu_widget_email']) == 0) && strcmp(EpMessageQueueAccess::$jsonMQString['campaign'], 'SR16') == 0){
		PHPUnit_Framework_TestCase::assertEquals(EpMessageQueueAccess::$jsonMQString['timestamp'], $db->dbData['TimeStamp'], "Failed:Actual and expected Timestamp are not equal in EP DB, Plz check manually ");
		}else{
			echo "Messages are consumed before looking into MessageQueue-DatabaseTimestamp:" . $db->dbData['TimeStamp'];
		}
		PHPUnit_Framework_TestCase::assertNotNull($db->dbData['TimeStamp'], "Failed: Timestamp is null: Plz check manually");
		PHPUnit_Framework_TestCase::assertEquals(TestData::$formData['esu_widget_email'], $db->dbData['EmailAddress'], 'Failed:Actual and expected Email are not equal in EP DB, plz check maually in the DB');
		PHPUnit_Framework_TestCase::assertTrue((strpos($db->dbData['TransSourceURL'], '/homepage') !== false),'Failed: Correct transsource URL was not displayed in EP DB, plz check maually in the MQ');
		PHPUnit_Framework_TestCase::assertEquals('subscribe', $db->dbData['TransType'], 'Failed:Actual and expected trasType are not equal in EP DB, plz check maually in the DB');
		PHPUnit_Framework_TestCase::assertEquals('SR16', $db->dbData['Campaign'], 'Failed:Actual and expected Campaign are not equal in EP DB, plz check maually in the DB');
		if(strcmp(TestData::$device, 'Desktop') == 0){
			PHPUnit_Framework_TestCase::assertEquals($this->testData->DesktopESUHWTransSource, $db->dbData['TransSource'],'Failed:Actual and expected desktop transSource are not equal in EP Queue, plz check maually in the MQ');
		}else if(strcmp(TestData::$device, 'Mobile') == 0){
			PHPUnit_Framework_TestCase::assertEquals($this->testData->MobileESUHWTransSource, $db->dbData['TransSource'],'Failed:Actual and expected mobile transSource are not equal in EP Queue, plz check maually in the MQ');
		}
		if(empty(TestData::$formData['SchoolsAgeGroup'])){
			PHPUnit_Framework_TestCase::assertEquals('general' , $db->dbData['GeneralList'] ,"Failed: Expected General List value is not found in db: Plz check manually");
		}
		if(!empty(TestData::$formData['SchoolsAgeGroup'])){
			PHPUnit_Framework_TestCase::assertEquals('teacher' , $db->dbData['TeacherList'] ,"Failed: Expected Teacher List value is not found in db: Plz check manually");
			if(TestData::$formData['SchoolsAgeGroup'] == 'Nurseries'){
				PHPUnit_Framework_TestCase::assertEquals('EY' , $db->dbData['SchoolPhase'] ,"Failed: Expected SchoolPhase value is not found in db: Plz check manually");
			}else if(TestData::$formData['SchoolsAgeGroup'] == 'Primary'){
				PHPUnit_Framework_TestCase::assertEquals('PY' , $db->dbData['SchoolPhase'] ,"Failed: Expected SchoolPhase value is not found in db: Plz check manually");
			}else if(TestData::$formData['SchoolsAgeGroup'] == 'Secondary'){
				PHPUnit_Framework_TestCase::assertEquals('SY' , $db->dbData['SchoolPhase'] ,"Failed: Expected SchoolPhase value is not found in db: Plz check manually");
			}else if(TestData::$formData['SchoolsAgeGroup'] == 'Sixth Form/FEcollege'){
				PHPUnit_Framework_TestCase::assertEquals('FE' , $db->dbData['SchoolPhase'] ,"Failed: Expected SchoolPhase value is not found in db: Plz check manually");
			}

		}
	}
	
	/**
	 * @Then I see the following list of age group options:
	 */
	public function iSeeTheFollowingListOfAgeGroupOptions(TableNode $ageGroupOptions)
	{
		foreach ($ageGroupOptions as $ageGroup) {
			if($ageGroup == 'Nurseries'){
				PHPUnit_Framework_TestCase::assertEquals($ageGroup , $this->stringFormationElements('ageGroup_list', 'EY')->getText(),"Failed: Nurseris drop down value not present: Plz check manually");
			}else if($ageGroup == 'Primary'){
				PHPUnit_Framework_TestCase::assertEquals($ageGroup , $this->stringFormationElements('ageGroup_list', 'PY')->getText(),"Failed: Primary drop down value not present: Plz check manually");
			}else if($ageGroup == 'Secondary'){
				PHPUnit_Framework_TestCase::assertEquals($ageGroup , $this->stringFormationElements('ageGroup_list', 'SY')->getText(),"Failed: Secondary drop down value not present: Plz check manually");
			}else if($ageGroup == 'Sixth Form/FEcollege'){
				PHPUnit_Framework_TestCase::assertEquals($ageGroup , $this->stringFormationElements('ageGroup_list', 'FE')->getText(),"Failed: Sixth form/FE college drop down value not present: Plz check manually");
			}
		}
	}
	
	/**
	 * @When I select :arg1 from the age group drop down list
	 */
	public function iSelectFromTheAgeGroupDropdownList($yearPhase)
	{
		TestData::$formData['SchoolsAgeGroup'] = $yearPhase;
		$this->selectYearPhaseFromAgeGroupDropDownList($yearPhase);
	}
	
	/**
	 * @Then I fill invalid email address in the :arg1 field
	 */
	public function iFillInvalidEmailAddressInTheField($field)
	{
		$value = $this->fillFieldByXpath($field, 's.mechineni@');
		TestData::$formData[$field] = $value;
	}
	
	// Below steps for ESU strip feature
	
	/**
	 * @Then I can see a message row for RND17 ESU Strip in the message queue
	 */
	public function iCanSeeAMessageRowForRndEsuStripInTheMessageQueue()
	{
		$baseurl = TestData::$url;
		if (strpos($baseurl, 'qa') !== false) {
			$this->navigatetoMessageQueueWebInterface('MesageQueueUrl_QA', 'esu');
		}else if (strpos($baseurl, 'test') !== false) {
			$this->navigatetoMessageQueueWebInterface('MesageQueueUrl_Test', 'esu');
		}else if (strpos($baseurl, 'staging') !== false) {
			$this->navigatetoMessageQueueWebInterface('MesageQueueUrl_Staging', 'esu');
		}else{
			$this->navigatetoMessageQueueWebInterface('MesageQueueUrl_Prod', 'esu');
		}
		$this->CheckMessageQueueESU();
	
	}
	
	/**
	 * @Then I can see messages to EP mandatory fields for RND17 Schools ESU Strip
	 */
	public function iCanSeeMessagesToEpMandatoryFieldsForRndEsuStrip()
	{
		if ((!EpMessageQueueAccess::$jsonMQString['timestamp'] == null) && (strcmp(EpMessageQueueAccess::$jsonMQString['email'], TestData::$formData['esu_strip_email']) == 0) && strcmp(EpMessageQueueAccess::$jsonMQString['campaign'], 'RND17') == 0){
			PHPUnit_Framework_TestCase::assertNotNull(EpMessageQueueAccess::$jsonMQString['timestamp'],'Failed: Timestamp is null, plz check maually in the MQ');
			PHPUnit_Framework_TestCase::assertTrue((strpos(EpMessageQueueAccess::$jsonMQString['transSourceURL'], '/node/1') !== false),'Failed: Correct transsource URL was not displayed in EP DB, plz check maually in the MQ');
			PHPUnit_Framework_TestCase::assertEquals('esu', EpMessageQueueAccess::$jsonMQString['transType'], 'Failed:Actual and expected trasType are not equal in EP Queue, plz check maually in the MQ');
			if(strcmp(TestData::$device, 'Desktop') == 0){
				PHPUnit_Framework_TestCase::assertEquals($this->testData->RND17DesktopSchoolsESUStripTransSource, EpMessageQueueAccess::$jsonMQString['transSource'],'Failed:Actual and expected desktop transSource is not equal in EP Queue, plz check maually in the MQ');
			}else if(strcmp(TestData::$device, 'Mobile') == 0){
				PHPUnit_Framework_TestCase::assertEquals($this->testData->RND17MobileSchoolsESUStripTransSource, EpMessageQueueAccess::$jsonMQString['transSource'],'Failed:Actual and expected mobile transSource is not equal in EP Queue, plz check maually in the MQ');
			}
			PHPUnit_Framework_TestCase::assertEquals('RND17', EpMessageQueueAccess::$jsonMQString['campaign'], 'Failed:Actual and expected Campaign is not equal in EP Queue, plz check maually in the MQ');
		}else{
			echo 'Messages are consumed before looking into MessageQueue' ;
		}
	}
	
	/**
	 * @Then I can see all the associated data for RND17 Schools ESU strip in the EP database with valid field values
	 */
	public function iCanSeeAllTheAssociatedDataForRndEsuStripInTheEpDatabaseWithValidFieldValues()
	{
		$db = new EpDbConnectionAndDataExtraction();
		//echo TestData::$formData['esu_widget_email'];
		$db->dbEmailSubscribes(TestData::$formData['esu_strip_email'], $this->testData->dbServerTest);
		if ((!EpMessageQueueAccess::$jsonMQString['timestamp'] == null) && (strcmp(EpMessageQueueAccess::$jsonMQString['email'], TestData::$formData['esu_strip_email']) == 0) && strcmp(EpMessageQueueAccess::$jsonMQString['campaign'], 'RND17') == 0){
			PHPUnit_Framework_TestCase::assertEquals(EpMessageQueueAccess::$jsonMQString['timestamp'], $db->dbData['Timestamp'], "Failed:Actual and expected Timestamp is not equal in EP DB, Plz check manually ");
		}else{
			echo "Messages are consumed before looking into MessageQueue-DatabaseTimestamp:" . $db->dbData['Timestamp'];
		}
		PHPUnit_Framework_TestCase::assertNotNull($db->dbData['Timestamp'], "Failed: Timestamp is null: Plz check manually");
		PHPUnit_Framework_TestCase::assertEquals(TestData::$formData['esu_strip_email'], $db->dbData['EmailAddress'], 'Failed:Actual and expected Email is not equal in EP DB, plz check maually in the DB');
		PHPUnit_Framework_TestCase::assertTrue((strpos($db->dbData['TransSourceURL'], '/node/1') !== false),'Failed: Correct transsource URL was not displayed in EP DB, plz check maually in the MQ');
		PHPUnit_Framework_TestCase::assertEquals('esu', $db->dbData['TransType'], 'Failed:Actual and expected trasType is not equal in EP DB, plz check maually in the DB');
		PHPUnit_Framework_TestCase::assertEquals('RND17', $db->dbData['Campaign'], 'Failed:Actual and expected Campaign is not equal in EP DB, plz check maually in the DB');
		if(strcmp(TestData::$device, 'Desktop') == 0){
			PHPUnit_Framework_TestCase::assertEquals($this->testData->RND17DesktopSchoolsESUStripTransSource, $db->dbData['TransSource'],'Failed:Actual and expected desktop transSource is not equal in EP Queue, plz check maually in the MQ');
		}else if(strcmp(TestData::$device, 'Mobile') == 0){
			PHPUnit_Framework_TestCase::assertEquals($this->testData->RND17MobileSchoolsESUStripTransSource, $db->dbData['TransSource'],'Failed:Actual and expected mobile transSource is not equal in EP Queue, plz check maually in the MQ');
		}

		if(!empty(TestData::$formData['SchoolsAgeGroup'])){
			PHPUnit_Framework_TestCase::assertEquals('teacher' , $db->dbData['TeacherList'] ,"Failed: Expected Teacher List value is not found in db: Plz check manually");
			if(TestData::$formData['SchoolsAgeGroup'] == 'Early Years or Nursery'){
				PHPUnit_Framework_TestCase::assertEquals('EY' , $db->dbData['SchoolPhase'] ,"Failed: Expected SchoolPhase value for Early years not found in db: Plz check manually");
			}else if(TestData::$formData['SchoolsAgeGroup'] == 'Primary'){
				PHPUnit_Framework_TestCase::assertEquals('PY' , $db->dbData['SchoolPhase'] ,"Failed: Expected SchoolPhase value for Primary not found in db: Plz check manually");
			}else if(TestData::$formData['SchoolsAgeGroup'] == 'Secondary'){
				PHPUnit_Framework_TestCase::assertEquals('SY' , $db->dbData['SchoolPhase'] ,"Failed: Expected SchoolPhase value for Secondary  not found in db: Plz check manually");
			}else if(TestData::$formData['SchoolsAgeGroup'] == 'Further Education or Sixth-Form College'){
				PHPUnit_Framework_TestCase::assertEquals('FE' , $db->dbData['SchoolPhase'] ,"Failed: Expected SchoolPhase value for Further Education not found in db: Plz check manually");
			}else if(TestData::$formData['SchoolsAgeGroup'] == 'Higher Education'){
				PHPUnit_Framework_TestCase::assertEquals('HE' , $db->dbData['SchoolPhase'] ,"Failed: Expected SchoolPhase value for Higher Education not found in db: Plz check manually");
			}else if(TestData::$formData['SchoolsAgeGroup'] == 'Other'){
				PHPUnit_Framework_TestCase::assertEquals('other' , $db->dbData['SchoolPhase'] ,"Failed: Expected SchoolPhase value for Other not found in db: Plz check manually");
			}

		}

		if(empty(TestData::$formData['SchoolsAgeGroup'])){
			PHPUnit_Framework_TestCase::assertEquals('general' , $db->dbData['GeneralList'] ,"Failed: Expected General List value is not found in db: Plz check manually");
		}

	
	}
	
	/**
	 * @Then I see the following list of schools age group options:
	 */
	public function iSeeTheFollowingListOfSchoolsAgeGroupOptions(TableNode $ageGroupOptions)
	{
		foreach ($ageGroupOptions as $ageGroup) {
			if($ageGroup == 'Early Years or Nursery'){
				PHPUnit_Framework_TestCase::assertEquals($ageGroup , $this->stringFormationElements('schools_ageGroup_list', '2')->getText(),"Failed: Nurseris drop down value not present: Plz check manually");
			}else if($ageGroup == 'Primary'){
				PHPUnit_Framework_TestCase::assertEquals($ageGroup , $this->stringFormationElements('schools_ageGroup_list', '3')->getText(),"Failed: Primary drop down value not present: Plz check manually");
			}else if($ageGroup == 'Secondary'){
				PHPUnit_Framework_TestCase::assertEquals($ageGroup , $this->stringFormationElements('schools_ageGroup_list', '4')->getText(),"Failed: Secondary drop down value not present: Plz check manually");
			}else if($ageGroup == 'Further Education or Sixth-Form College'){
				PHPUnit_Framework_TestCase::assertEquals($ageGroup , $this->stringFormationElements('schools_ageGroup_list', '5')->getText(),"Failed: Sixth form/FE college drop down value not present: Plz check manually");
			}else if($ageGroup == 'Higher Education'){
				PHPUnit_Framework_TestCase::assertEquals($ageGroup , $this->stringFormationElements('schools_ageGroup_list', '6')->getText(),"Failed: Higher Education drop down value not present: Plz check manually");
			}else if($ageGroup == 'Other'){
				PHPUnit_Framework_TestCase::assertEquals($ageGroup , $this->stringFormationElements('schools_ageGroup_list', '7')->getText(),"Failed: Other drop down value not present: Plz check manually");
			}
		}
	}
	
	/**
	 * @Then I select :arg1 from the schools age group drop down list
	 */
	public function iSelectFromTheSchoolsAgeGroupDropDownList($yearPhase)
	{
		TestData::$formData['SchoolsAgeGroup'] = $yearPhase;
		$this->selectYearPhaseFromSchoolsESUAgeGroupDropDownList($yearPhase);
	}
	
	/**
	 * @Then I should see email is invaid error message text in the :arg1 element
	 */
	public function iShouldSeeEmailIsInvaidErrorMessageTextInTheElement($locator)
	{
		PHPUnit_Framework_TestCase::assertTrue(strpos($this->getTextByXpath($locator),'The email address ' . TestData::$formData['esu_strip_email'] . ' is not valid.' )!== false ,'can not see error message in the ' . $locator . ' element');
	}

}