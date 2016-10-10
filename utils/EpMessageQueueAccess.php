<?php
namespace Utils;

class EpMessageQueueAccess extends WebConnector
{
	public static $jsonMQString;
	
	public function navigatetoMessageQueueWebInterface($mqURL,$mqName)
	{		
		$this->getSession()->visit($this->getValueFromConfig($mqURL) . $mqName);
		$this->getSession()->wait(10000);
	}
	
	public function CheckMessageQueueESU()
	{
		if(($this->getSession()->getPage()->has('xpath', $this->getValueFromOR('mqrow2'))) && ($this->getSession()->getPage()->has('xpath', $this->getValueFromOR('mqrow1')))){
			$json = $this->getTextByXpath('mqrow2');
			
			EpMessageQueueAccess::$jsonMQString = json_decode($json, true);
			//var_dump(json_decode($json, true));
			
			//echo EpMessageQueueAccess::$jsonMQString['email'];
			
			$this->getSession()->wait(55000);
			$this->getSession()->reload();		
			if($this->getSession()->getPage()->has('xpath', $this->getValueFromOR('mqrow1')))
			{
				$this->getSession()->wait(2000);		 
			}
		}else{
			$this->getSession()->wait(6000);
		}
		if($this->getSession()->getPage()->has('xpath', $this->getValueFromOR('mqrow1'))){
			$json = $this->getTextByXpath('mqrow1');
			
			EpMessageQueueAccess::$jsonMQString = json_decode($json, true);
			//var_dump(json_decode($json, true));
			
			//echo EpMessageQueueAccess::$jsonMQString['email'];
			
			$this->getSession()->wait(55000);
			$this->getSession()->reload();
			if($this->getSession()->getPage()->has('xpath', $this->getValueFromOR('mqrow1')))
			{
				$this->getSession()->wait(2000);
			}
		}else{
			$this->getSession()->wait(6000);
		}
	}
	
	public function checkMessageQueue(){
		if($this->getSession()->getPage()->has('xpath', $this->getValueFromOR('mqrow1'))){
			$json = $this->getTextByXpath('mqrow1');
				
			EpMessageQueueAccess::$jsonMQString = json_decode($json, true);
				
			$this->getSession()->wait(55000);
			$this->getSession()->reload();
			if($this->getSession()->getPage()->has('xpath', $this->getValueFromOR('mqrow1')))
			{
				$this->getSession()->wait(2000);
			}
		}else{
			$this->getSession()->wait(6000);
		}
	}
	
	
	
	
}