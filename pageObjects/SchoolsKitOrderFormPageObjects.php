<?php
namespace PageObjects;

use Utils\EpMessageQueueAccess;

abstract class SchoolsKitOrderFormPageObjects extends EpMessageQueueAccess
{
	public $jobTitles = array( "administrative_staff","head_teacher","assistant_head","charity_coordinator","head_of_department","pe_coordinator","pshe_coordinator","manager","teacher",
	"childminder","support_staff","early_years_practitioner","head_of_year","deputy_head","student","pe_department","youth_group_leader","business_management","other");
	
	public $schoolPostCodes = array( "TW3 1NX", "TW3 1LD", "HA1 3TA", "HA1 4EE", "M8 4JY", "M8 5BR", "NG2 4HT", "E1 1JX" );
	

	public function ClickStringFormationElementXpaths($locator, $string){
		$this->getSession()->getPage()->find('xpath', sprintf($this->getValueFromOR($locator), $string))->click();
		return $string;		
	}
	
	public function selectFundraisingPackFromTheDropDownlist($fundraisingPack){
		if($fundraisingPack == 'Primary'){
			$pack = $this->ClickStringFormationElementXpaths('schoolsKitOrderForm_fundraisingkit_list', 'primary_school_fundraising_pack');
		}else if($fundraisingPack == 'Secondary'){
			$pack = $this->ClickStringFormationElementXpaths('schoolsKitOrderForm_fundraisingkit_list', 'secondary_school_fundraising_pack');
		}else {
			$pack = $this->ClickStringFormationElementXpaths('schoolsKitOrderForm_fundraisingkit_list', 'nursery_activity_pack');
		}
		return $pack;
	}
	
	public function selectTitleFromTheDropDownlist($title){
		$Title = $this->ClickStringFormationElementXpaths('schoolsKitOrderForm_title_dropdown_list', strtolower($title));	
		return $Title;
	}
	
	public function getRandomJobTitle()
	{
		$rand_keys = array_rand($this->jobTitles, 1);
		return $this->jobTitles[$rand_keys];
	}
	
	public function selectJobTitleFromTheDropDownlist($locator){
		$jobTitle = $this->getRandomJobTitle();
		$this->ClickStringFormationElementXpaths($locator, $jobTitle);		
		return $jobTitle;
	}
	
	public function selectEstablishmentTypeFromTheDropDownlist($establishmentType){
		if($establishmentType == 'Primary'){
			$estType = $this->ClickStringFormationElementXpaths('establishmentType_dropdown_list', 'primary_school');
		}else if($establishmentType == 'Secondary'){
			$estType = $this->ClickStringFormationElementXpaths('establishmentType_dropdown_list', 'secondary_school');
		}else if($establishmentType == 'Nursery'){
			$estType = $this->ClickStringFormationElementXpaths('establishmentType_dropdown_list', 'nursery');
		}else{
			$estType = $this->ClickStringFormationElementXpaths('establishmentType_dropdown_list', 'sixth_form_fe_college');
		}
		return $estType;
	}
	
	public function getRandomSchoolsPostCode(){
		$rand_keys = array_rand($this->schoolPostCodes, 1);
		return $this->schoolPostCodes[$rand_keys];
	}
	
	public function getErrorMessagesText($locator,$fieldposition){
		return $this->getSession()->getPage()->find('xpath', sprintf($this->getValueFromOR($locator), $fieldposition))->getText();
	}
	
}