<?php
namespace Utils;

class EpDbConnectionAndDataExtraction {
	
	public $dbData = [];
	public function dbDonations() {
		$serverName = "T-MQSQL01.vxh.comicrelief.org.uk";
		$uid = "Eptest";
		$pwd = "Hy1567!yh";
		$databaseName = "EpWebServicesDb";
		
		$connectionInfo = array (
				"UID" => $uid,
				"PWD" => $pwd,
				"Database" => $databaseName 
		);
		
		/* Connect using SQL Server Authentication. */
		$conn = sqlsrv_connect ( $serverName, $connectionInfo );
		if ($conn) {
			echo "Connected to EP Database\n";
		} else {
			echo "Error";
			die ( print_r ( sqlsrv_errors (), true ) );
		}
		
		$tsql = "SELECT * FROM EpWebServicesDb.dbo.OTNDonations where SystemTransactionIdentifier='06925b6014f9'";
		
		/* Execute the query. */
		
		$stmt = sqlsrv_query ( $conn, $tsql );
		
		if ($stmt) {
			echo "Statement executed\n";
		} else {
			echo "Error in statement execution.\n";
			die ( print_r ( sqlsrv_errors (), true ) );
		}
		
		/* Iterate through the result set printing a row of data upon each iteration. */
		
		while ( $row = sqlsrv_fetch_array ( $stmt, SQLSRV_FETCH_NUMERIC ) ) {
			/*
			 * echo "Col1: " . $row[0] . "\n";
			 * echo "Col2: " . $row[1] . "\n";
			 * echo "Col3: " . $row[2] . "\n";
			 * echo "-----------------<br>\n";
			 */
			$SystemIdentifier = $row [0];
			$SystemTransactionIdentifier = $row [1];
			$FirstName = $row [4];
			echo $SystemIdentifier . "\n";
			echo $SystemTransactionIdentifier . "\n";
			print ("$FirstName\n") ;
		}
	}
	
	public function dbEmailSubscribes($email, $serverName) {
	//	$serverName = "T-MQSQL01.vxh.comicrelief.org.uk";
		$uid = "Eptest";
		$pwd = "Hy1567!yh";
		$databaseName = "EpWebServicesDb";
		
		$connectionInfo = array (
				"UID" => $uid,
				"PWD" => $pwd,
				"Database" => $databaseName 
		);
		
		/* Connect using SQL Server Authentication. */
		$conn = sqlsrv_connect ( $serverName, $connectionInfo );
		if ($conn) {
			echo "Connected to EP database\n";
		} else {
			echo "Error";
			die ( print_r ( sqlsrv_errors (), true ) );
		}
		
		$tsql = "SELECT Top 1 * FROM EpWebServicesDb.dbo.EmailSubscribes where EmailAddress='$email' order by Id desc";
		
		/* Execute the query. */
		
		$stmt = sqlsrv_query ( $conn, $tsql );
		
		if ($stmt) {
			echo "Statement executed\n";
		} else {
			echo "Error in statement execution.\n";
			die ( print_r ( sqlsrv_errors (), true ) );
		}
		
		/* Iterate through the result set printing a row of data upon each iteration. */
		
		while ( $row = sqlsrv_fetch_array ( $stmt, SQLSRV_FETCH_NUMERIC ) ) {
			/* if(isEmpty($row [0])){
			throw new \Exception("Row is empty - no data for the statement");
			} */
			$this->dbData['Id'] = $row [0];
			$this->dbData['EmailAddress'] = $row [1];
			$this->dbData['TeacherFlag'] = $row [3];
			$this->dbData['Timestamp'] = $row [5];
			$this->dbData['TransType'] = $row [6];
			$this->dbData['TransSource'] = $row [7];
			$this->dbData['TransSourceURL'] = $row [8];
			$this->dbData['Campaign'] = $row [9];
			$this->dbData['SchoolPhase'] = $row [15];
			$this->dbData['GeneralList'] = $row [16];
			$this->dbData['TeacherList'] = $row [17];
			echo "Id: ". $this->dbData['Id'] . "\n" ;
			echo "Email Address: ". $this->dbData['EmailAddress'] . "\n";
			echo "TimeStamp: " . $this->dbData['Timestamp'] . "\n";
			echo "TransType: " . $this->dbData['TransType'] . "\n";
			echo "TransSource: " . $this->dbData['TransSource'] . "\n";
			echo "TransSourceURL: " . $this->dbData['TransSourceURL'] . "\n";
			echo "Campaign: " . $this->dbData['Campaign'] . "\n";
		}
	}
	
	public function dbVerifySchoolsPreorderPack($email, $serverName) {
		$uid = "Eptest";
		$pwd = "Hy1567!yh";
		$databaseName = "EpWebServicesDb";
	
		$connectionInfo = array (
				"UID" => $uid,
				"PWD" => $pwd,
				"Database" => $databaseName
		);
	
		/* Connect using SQL Server Authentication. */
		$conn = sqlsrv_connect ( $serverName, $connectionInfo );
		if ($conn) {
			echo "Connected to EP database\n";
		} else {
			echo "Error";
			die ( print_r ( sqlsrv_errors (), true ) );
		}
	
		$tsql = "SELECT Top 1 * FROM EpWebServicesDb.dbo.KitOrders where email='$email' order by Id desc";
	
		/* Execute the query. */
	
		$stmt = sqlsrv_query ( $conn, $tsql );
	
		if ($stmt) {
			echo "Statement executed\n";
		} else {
			echo "Error in statement execution.\n";
			die ( print_r ( sqlsrv_errors (), true ) );
		}
	
		/* Iterate through the result set printing a row of data upon each iteration. */
	
		while ( $row = sqlsrv_fetch_array ( $stmt, SQLSRV_FETCH_NUMERIC ) ) {
			$this->dbData['Id'] = $row [0];
			$this->dbData['KitType'] = $row [1];
			$this->dbData['title'] = $row [2];
			$this->dbData['firstName'] = $row [3];
			$this->dbData['lastName'] = $row [4];
			$this->dbData['jobTitle'] = $row [5];
			$this->dbData['email'] = $row [6];
			$this->dbData['establishmentType'] = $row [7];
			$this->dbData['telephone'] = $row [8];
			$this->dbData['postalUpdates'] = $row [9];
			$this->dbData['tcs'] = $row [10];
			$this->dbData['emailUpdates'] = $row [11];
			$this->dbData['Timestamp'] = $row [12];
			$this->dbData['TransType'] = $row [14];
			$this->dbData['TransSource'] = $row [15];
			$this->dbData['TransSourceURL'] = $row [16];
			$this->dbData['Campaign'] = $row [17];
			$this->dbData['SchoolName'] = $row [23];
			$this->dbData['SchoolAddress1'] = $row [24];
			$this->dbData['SchoolTown'] = $row [27];
			$this->dbData['SchoolPostcode'] = $row [29];
			
		}
	}
}