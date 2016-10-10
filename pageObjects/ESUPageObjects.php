<?php
namespace PageObjects;

use Utils\EpMessageQueueAccess;

abstract class ESUPageObjects extends EpMessageQueueAccess
{
	
	public function selectYearPhaseFromAgeGroupDropDownList($yearPhase)
	{
		if(strcmp('Nurseries', $yearPhase) == 0){
			//echo "xpath::   " . sprintf($this->getValueFromOR('ageGroup_list'),'EY');
			$this->getSession()->getPage()->find('xpath', sprintf($this->getValueFromOR('ageGroup_list'),'EY'))->click();
		}else if(strcmp('Primary', $yearPhase) == 0){
			$this->getSession()->getPage()->find('xpath', sprintf($this->getValueFromOR('ageGroup_list'),'PY'))->click();
		}else if(strcmp('Secondary', $yearPhase) == 0){
			$this->getSession()->getPage()->find('xpath', sprintf($this->getValueFromOR('ageGroup_list'),'SY'))->click();
		}else if(strcmp('Sixth Form/FEcollege', $yearPhase) == 0){
			$this->getSession()->getPage()->find('xpath', sprintf($this->getValueFromOR('ageGroup_list'),'FE'))->click();
		}
	}
	
	public function selectYearPhaseFromSchoolsESUAgeGroupDropDownList($yearPhase)
	{
		if(strcmp('Early Years or Nursery', $yearPhase) == 0){
			//echo "xpath::   " . sprintf($this->getValueFromOR('ageGroup_list'),'EY');
			$this->getSession()->getPage()->find('xpath', sprintf($this->getValueFromOR('schools_ageGroup_list'),'2'))->click();
		}else if(strcmp('Primary', $yearPhase) == 0){
			$this->getSession()->getPage()->find('xpath', sprintf($this->getValueFromOR('schools_ageGroup_list'),'3'))->click();
		}else if(strcmp('Secondary', $yearPhase) == 0){
			$this->getSession()->getPage()->find('xpath', sprintf($this->getValueFromOR('schools_ageGroup_list'),'4'))->click();
		}else if(strcmp('Further Education or Sixth-Form College', $yearPhase) == 0){
			$this->getSession()->getPage()->find('xpath', sprintf($this->getValueFromOR('schools_ageGroup_list'),'5'))->click();
		}else if(strcmp('Higher Education', $yearPhase) == 0){
			$this->getSession()->getPage()->find('xpath', sprintf($this->getValueFromOR('schools_ageGroup_list'),'6'))->click();
		}else if(strcmp('Other', $yearPhase) == 0){
			$this->getSession()->getPage()->find('xpath', sprintf($this->getValueFromOR('schools_ageGroup_list'),'7'))->click();
		}
	}

	public function stringFormationElements($xpathfromOR, $formationString)
    {
        return $this->getSession()->getPage()->find('xpath', sprintf($this->getValueFromOR($xpathfromOR),$formationString));
    }
	
	
	
}
