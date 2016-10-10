<?php
namespace Utils; 

class TestData
{
	public static $device;
	public static $url;
	public static $formData = [];
	
	public $firstNames = array( "Zenon", "John", "Andy", "Michael", "Bridget", "Adam", "Admin", "Aleks", "Beth", "Caroline", "Dave", "Gabriela", "Holly" );
	public $lastNames = array( "Hannick", "Kerry", "Phips", "Jackson", "Road", "Gilchrist", "User", "Pollock", "Matt", "Foster", "Ridge", "Michelle", "Trinity" );
	public $postcodes = array( "EC2Y 9AE", "EC2Y 9AA", "WD17 2LQ", "WD24 4RS", "BS16 1EJ", "N7 6DR", "N1 2SN", "WC1X 8EB", "HP5 1AB", "HP5 1AH",
			                   "HP5 1AD", "HP5 1AE", "HP5 1AG", "M50 3AH", "G34 9DL", "CB1 1PS", "RG1 2AG", "IV2 7GD");
	public $schoolPostCodes= array( "TW3 1NX", "TW3 1LD", "SE1 6PD", "HA1 3TA", "HA1 4EE", "M8 4JY", "M8 5BR", "NG2 4HT", "E1 1JX" );
	
	
	public $DesktopESUHWTransSource = "SR16_ESU_HeaderWidget";
	public $MobileESUHWTransSource = "SR16_Mob_ESU_HeaderWidget";
	public $RND17DesktopSchoolsESUStripTransSource = "RND17_[Device]_ESU_[PageElementSource]"; //need to change after fix
	public $RND17MobileSchoolsESUStripTransSource = "RND17_[Device]_ESU_[PageElementSource]"; //need to change after fix
	public $RND17DesktopSchoolsKitTransSource = "RND17_KITORDER";
	public $RND17MobileSchoolsKitTransSource = "RND17_KITORDER";
	
	public $dbServerDev = "D-MQSQL01.vxh.comicrelief.org.uk";
	public $dbServerTest = "T-MQSQL01.vxh.comicrelief.org.uk";
	public $dbServerProd = "MQSQL01.vxh.comicrelief.org.uk";
	
	
	public function randomInteger($min, $max)
	{
		return mt_rand($min, $max);
	}
	
	
	public function randomFirstName()
	{
		$rand_keys = array_rand($this->firstNames, 1);
		return $this->firstNames[$rand_keys];
	}
	
	public function randomLastName()
	{
		$rand_keys = array_rand($this->lastNames, 1);
		return $this->lastNames[$rand_keys];
	}
	
	public function randomEmailAddress() 
	{
		//return "qatester_".rand(1, 1000)."@comicrelief.com" ;
		$email = sprintf("daspemail+%06x@comicrelieftest.com", rand(1,1000));
		return $email;
	}
	
	public function randomPostCode()
	{
		$rand_keys = array_rand($this->postcodes, 1);
		return $this->postcodes[$rand_keys];
	}
	
	public function randomSchoolPostCode()
	{
		$rand_keys = array_rand($this->schoolPostCodes, 1);
		return $this->schoolPostCodes[$rand_keys];
	}
		
	public function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	
	public function generateValidUKMobileNumber()
	{
		$phonenumber = '0' . (string)$this->randomInteger(70, 79) . (string)$this->randomInteger(1000, 5000) . (string)$this->randomInteger(6000, 9000);
		return $phonenumber;
	}
}
