<?php
include_once("cGlobali.php");
class cCountries
{
	public $Id = 0;
	public $Country = array();
	
//----------------------------------------------------------------------------------------------------

	public function __construct($lId, $sCountry)
	{
		$this->Id = $lId;
		$this->Country = $sCountry;
		
	}
	
//----------------------------------------------------------------------------------------------------
	
	public function Save()
	{
		$Success = FALSE;

		try{
			$Globali=new cGlobali();
			//$Globali->dbconn = new mysqli($Globali->DBServerNameOnly, $Globali->DBUserName,$Globali->DBPassword,$Globali->DBIniCatalog,$Globali->portNumber);
			//$Globali->dbconn->connect();
			
			if ($Globali->dbconn->errno > 0){
				throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
			}

			if (!$Globali->dbconn){
				throw new Exception("Could not connect to DB");
			}

			if ($this->Id > 0){		//Edited Record: Update			
				$EditedRecord = TRUE;
			}
			else{
				$EditedRecord = FALSE;
			}
			
			if ($EditedRecord){
				$myQuery="UPDATE country SET `name` = ?, `shortname` = ?, `code` = ?, `logofileid` = ?, `featured`= ?, `default` = ?, `defaultcurrencyid` = ?, `timezoneid` = ?, `status`=?, `order`=?, `modifiedby` = ? WHERE `id` = ?";
			}
			else{//New Record: Insert
                                $statusVal = 1;
				$myQuery="INSERT INTO country (`name`, `shortname`, `code`, `logofileid`, `featured`, `default`, `defaultcurrencyid`, `timezoneid`, `order`, `createdby` , `status`) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
			}
			
			$stmt = $Globali->dbconn->stmt_init();    // Create a statement object
			
			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);

			
			if ($EditedRecord){
				$Success = $stmt->bind_param("sssiiiiiiiii", $this->Country['country_name'], $this->Country['country_short_name'],$this->Country['country_code'],  $this->Country['flagid'], $this->Country['country_featured'], $this->Country['country_default'], $this->Country['currency_id'], $this->Country['timezone_id'], $this->Country['country_status'], $this->Country['country_order'], $_SESSION['uid'], $this->Id);    // Bind the parameters
			}
			else{
				$Success = $stmt->bind_param("sssiiiiiiii",  $this->Country['country_name'], $this->Country['country_short_name'], $this->Country['country_code'],  $this->Country['flagid'], $this->Country['country_featured'], $this->Country['country_default'], $this->Country['currency_id'], $this->Country['timezone_id'], $this->Country['country_order'], $_SESSION['uid'], $statusVal);  
		  // Bind the parameters
			}
			
			if (! $Success) 
                            throw new Exception("Parameters couldn't be bound. Error: " . $Globali->dbconn->error);
			
			$Success = $stmt->execute();		//Execute Statement
			if (! $Success) 
                            throw new Exception("Statement couldn't be Executed. Error: " . $Globali->dbconn->error);
				
			$AffectedRows = $stmt->affected_rows;
			if ($AffectedRows > 0){
				if (! $EditedRecord)	$this->Id = $Globali->dbconn->insert_id;
			}
			else{
				$this->Id = 0;
			}
			
			$Globali->dbconn->close();
			$stmt->close();
			
			return $this->Id;
		}
		catch (Exception $Ex) {
			throw $Ex;
		}
	}		//Save

//---------------------------------------------------------------------------------------------------------------

	public function Load()
	{
		$Success = FALSE;

		try
		{
			$Globali=new cGlobali();
			//$Globali->dbconn = new mysqli($Globali->DBServerNameOnly, $Globali->DBUserName,$Globali->DBPassword,$Globali->DBIniCatalog,$Globali->portNumber);
			//$Globali->dbconn->connect();

			if ($Globali->dbconn->errno > 0){
				throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
			}

			if (!$Globali->dbconn){
				throw new Exception("Could not connect to DB");
			}

			$myQuery="SELECT Id, Country FROM Countries WHERE Id = ?";
			$stmt = $Globali->dbconn->stmt_init();    // Create a statement object
				
			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);
				
			$Success = $stmt->bind_param("i", $this->Id);    // Bind the parameters
			if (! $Success) throw new Exception("Parameters couldn't be bound. Error: " . $Globali->dbconn->error);
				
			$Success = $stmt->execute();		//Execute Statement
			if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Globali->dbconn->error);

			$Success = $stmt->bind_result($this->Id, $this->Country);	// Bind the result parameters
			
			if ($stmt->fetch())		//Fetch values actually into bound fields of the result
			{
				$Success = TRUE;
			}
			else
			{
				$Success = FALSE;
			}
			
			$Globali->dbconn->close();
			$stmt->close();
			
			return $Success;
		}
		catch (Exception $Ex)
		{
			throw $Ex;
		}
	}

//----------------------------------------------------------------------------------------------------

	public function Delete()
	{
		$Success = FALSE;
                
                $deleted = 1;
                
		try
		{
			$Globali=new cGlobali();
			//$Globali->dbconn = new mysqli($Globali->DBServerNameOnly, $Globali->DBUserName,$Globali->DBPassword,$Globali->DBIniCatalog,$Globali->portNumber);
			//$Globali->dbconn->connect();

			if ($Globali->dbconn->errno > 0)
			{
				throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
			}

			if (!$Globali->dbconn)
			{
				throw new Exception("Could not connect to DB");
			}

			$myQuery="UPDATE country SET `deleted` = ?, `modifiedby` = ?  WHERE `id` = ?";
			
			$stmt = $Globali->dbconn->stmt_init();    // Create a statement object
				
			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);

			
			$Success = $stmt->bind_param("iii", $deleted, $_SESSION['uid'], $this->Id);    // Bind the parameters
			if (! $Success) throw new Exception("Parameters couldn't be bound. Error: " . $Globali->dbconn->error);

			
			$Success = $stmt->execute();		//Execute Statement
			if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Globali->dbconn->error);
				
			$AffectedRows = $stmt->affected_rows;
			if ($AffectedRows > 0){
				$Success = TRUE;
			}
			else{
				$Success = FALSE;
			}
			
			$Globali->dbconn->close();
			$stmt->close();
			
			return $Success;
		}
		catch (Exception $Ex)
		{
			throw $Ex;
		}
	}		//Delete

//---------------------------------------------------------------------------------------------------------------
	
}		//Class


/* >>>Useful notes>>>
 * >>Bind_Param Types (First Argument)
 � i: All INTEGER types
 � d: The DOUBLE and FLOAT types
 � b: The BLOB types
 � s: All other types (including strings)
 * <<Bind_Param Types<<
 *
 *
 */

?>