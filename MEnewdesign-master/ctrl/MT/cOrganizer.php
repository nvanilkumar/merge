<?php
include_once("cGlobali.php");
include_once("cUser.php");

class cOrganizer extends cUser
{
	public $UserId = 0;
	public $CDesc = "";
	public $CAddress = "";
	public $CCountyId = 0;
	public $CStateId = 0;
	public $CCidd = 0;
	public $Cpin = 0 ;
	public $CPhone = 0;
	public $CFax = 0;
	public $CEMail = "" ;
	public $CURL = "";
	public $CLogo = "";
	public $Facebook = "" ;
	public $Twitter = "";
	public $LinkedIn = "";

	//----------------------------------------------------------------------------------------------------

	public function __construct($lId, $sUserName, $sPassword, $sEmail, $sCompany, $sSalutation,
	$sFirstName, $sMiddleName, $sLastName,
	$sUAddress, $sCountryId, $sStateId, $sCityId,  $sPIN, $sPhone, $sMobile, $sNewsletterSub, $sDesignationId,
	$sCDesc, $sAddress, $sCountyId, $sCStateId, $sCCidd, $sCpin , $sCPhone,
	$sCFax, $sCEMail, $sCURL, $sCLogo, $sFacebook, $sTwitter, $sLinkedIn)
	{
		parent::__construct($lId, $sUserName, $sPassword, $sEmail, $sCompany, $sSalutation,
		$sFirstName, $sMiddleName, $sLastName,
		$sUAddress, $sCountryId, $sStateId, $sCityId, $sPIN, $sPhone, $sMobile, $sNewsletterSub, $sDesignationId);

		$this->UserId = $lId;
		$this->CDesc = $sCDesc;
		$this->CAddress = $sAddress;
		$this->CCountyId = $sCountyId;
		$this->CStateId = $sCStateId;
		$this->CCidd = $sCCidd;
		$this->Cpin = $sCpin;
		$this->CPhone = $sCPhone;
		$this->CFax = $sCFax;
		$this->CEMail = $sCEMail;
		$this->CURL = $sCURL;
		$this->CLogo = $sCLogo;
		$this->Facebook = $sFacebook;
		$this->Twitter = $sTwitter;
		$this->LinkedIn = $sLinkedIn;
			
		//echo $this->FirstName;
	}

	//----------------------------------------------------------------------------------------------------

	private function SaveRecord($Conn)		//Returns Boolean
	{
		//$this->UserId;
		
		
		
		if ($this->UserId > 0)		//Edited Record: Update
		{
			$EditedRecord = TRUE;
		}
		else
		{
			$EditedRecord = FALSE;
		}
		


		if ($EditedRecord)
		{
		  $myQuery="UPDATE  organizer SET UserId = ?, CDesc = ?, CAddress = ?, CCountyId = ?, CStateId = ?, CCidd = ?,
				 Cpin = ?, CPhone = ?, CFax = ?, CEMail = ?, CURL = ?, CLogo = ?, Facebook = ?, Twitter = ?, LinkedIn = ? WHERE UserId = ?";
		}
		else	//New Record: Insert
		{
			
		 $myQuery="INSERT INTO organizer SET UserId = ?, CDesc = ?, CAddress = ?, CCountyId = ?, CStateId = ?, CCidd = ?,
				 Cpin = ?, CPhone = ?, CFax = ?, CEMail = ?, CURL = ?, CLogo = ?, Facebook = ?, Twitter = ?, LinkedIn = ?";
		}
		
		
			
		$stmt = $Conn->stmt_init();    // Create a statement object

		$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
		if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Conn->error);

			
		if ($EditedRecord)
		{
			$Success = $stmt->bind_param("issiiisssssssssi", $this->Id, $this->CDesc, $this->CAddress, $this->CCountyId, $this->CStateId, $this->CCidd,
			$this->Cpin , $this->CPhone, $this->CFax, $this->CEMail, $this->CURL, $this->CLogo, $this->Facebook, $this->Twitter, $this->LinkedIn, $this->UserId);
			// Bind the parameters
		}
		else
		{
			$Success = $stmt->bind_param("issiiisssssssss", $this->Id, $this->CDesc, $this->CAddress, $this->CCountyId, $this->CStateId, $this->CCidd,
			$this->Cpin , $this->CPhone, $this->CFax, $this->CEMail, $this->CURL, $this->CLogo, $this->Facebook, $this->Twitter, $this->LinkedIn);
			// Bind the parameters
		}
			
		if (! $Success) throw new Exception("Parameters couldn't be bound. Error: " . $Conn->error);

			
		$Success = $stmt->execute();		//Execute Statement
		if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Conn->error);

		$AffectedRows = $stmt->affected_rows;
		if ($AffectedRows > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	//----------------------------------------------------------------------------------------------------------
	private function LoadRecord($Conn)
	{
			  $myQuery="SELECT  UserId, CDesc, CAddress, CCountyId, CStateId, CCidd,
				 Cpin , CPhone, CFax, CEMail, CURL, CLogo, Facebook, Twitter, LinkedIn  FROM organizer WHERE UserId = ?";
			$stmt = $Conn->stmt_init();    // Create a statement object

			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Conn->error);

			 $Success = $stmt->bind_param("i", $this->UserId);    // Bind the parameters
			if (! $Success) throw new Exception("Parameters couldn't be bound. Error: " . $Conn->error);

			$Success = $stmt->execute();		//Execute Statement
			if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Conn->error);

			$Success = $stmt->bind_result($this->UserId, $this->CDesc, $this->CAddress, $this->CCountyId,
			$this->CStateId, $this->CCidd, $this->Cpin, $this->CPhone, $this->CFax, $this->CEMail, $this->CURL,
			$this->CLogo, $this->Facebook, $this->Twitter, $this->LinkedIn);	// Bind the result parameters

			if ($stmt->fetch())		//Fetch values actually into bound fields of the result
			{
				$Success = TRUE;
				
			}
			else
			{
				$Success = FALSE;
				
			}

			$Conn->close();
			$stmt->close();

			return $Success;
			
	}
//---------------------------------------------------------------------------------------------------------
private function DeleteRecord($Conn)
{
	$myQuery="DELETE FROM organizer WHERE UserId = ?";

			$stmt = $Conn->stmt_init();    // Create a statement object

			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Conn->error);


			$Success = $stmt->bind_param("i", $this->UserId);    // Bind the parameters
			if (! $Success) throw new Exception("Parameters couldn't be bound. Error: " . $Conn->error);


			$Success = $stmt->execute();		//Execute Statement
			if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Conn->error);

			$AffectedRows = $stmt->affected_rows;
			if ($AffectedRows > 0)
			{
				$Success = TRUE;
			}
			else
			{
				$Success = FALSE;
			}

			$Conn->close();
			$stmt->close();

			return $Success;
}
//----------------------------------------------------------------------------------------------------------
	
	public function Save()
	{	
		$Success = FALSE;

		//Connect to DB>>>
		try
		{
			$Globali=new cGlobali();
			//$Conn = new mysqli($Global->DBServerNameOnly, $Global->DBUserName,$Global->DBPassword,$Global->DBIniCatalog,$Global->portNumber);
			//$Conn->connect();

			if ($Globali->dbconn->errno > 0)
			{
				throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
			}

			if (!$Globali->dbconn)
			{
				throw new Exception("Could not connect to DB");
			}
		}
		catch (Exception $E1)
		{
			throw $E1;
		}
		//<<<Connect to DB


		$Globali->dbconn->autocommit(FALSE);

		//>>Save Base Class>>
		try
		{
			//echo "Calling Parent's Save Method <br>";
			
			$ParentId = parent::Save($Globali->dbconn);
			
		}
		catch (Exception $E2)
		{
			throw $E2;
		}
		//<<Save Base Class<<

		
		//>>Save SubClass>>
		try
		{
			$Success = $this->SaveRecord($Globali->dbconn);
			//echo "Called subclass SaveRecord(): Success = " . $Success . "<br>";

				

			if(!$Success)
			{
				throw new Exception("Error while saveing record");
			}
		}
		catch (Exception $E3)
		{
			throw $E3;
		}
		//<<Save SubClass<<


		//>>Commit/Rollback>>
		if ($ParentId > 0 && $Success)
		{
			$Globali->dbconn->commit;
		}
		else
		{
			$Globali->dbconn->rollback;
		}
		//<<Commit/Rollback<<

		if ($Globali->dbconn)
		{
			$Globali->dbconn->autocommit(TRUE);
			$Globali->dbconn->close();
		}


//		$this->Id = $ParentId;
		return $this->Id;
	}		//Save

	//---------------------------------------------------------------------------------------------------------------

	public function Load()
	{
		$Success = FALSE;

		try
		{
			$Globali=new cGlobali();
			//$Conn = new mysqli($Global->DBServerNameOnly, $Global->DBUserName,$Global->DBPassword,$Global->DBIniCatalog,$Global->portNumber);
			//$Conn->connect();

			if ($Globali->dbconn->errno > 0)
			{
				throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
			}

			if (!$Globali->dbconn)
			{
				throw new Exception("Could not connect to DB");
			}

			
		}
		catch (Exception $Ex)
		{
			throw $Ex;
		}
		
		

		//>>Load Base Class>>
		try
		{
			$PSuccess = parent::Load($Globali->dbconn);
			//$this->Id = $ParentId;
		}
		catch (Exception $E2)
		{
			throw $E2;
		}
		//<<Load Base Class<<


		//>>Load SubClass>>
		try
		{
			$CSuccess = $this->LoadRecord($Globali->dbconn);

			if(!$CSuccess)
			{
				throw new Exception("Error:Record does not exist");
			}
		}
		catch (Exception $E3)
		{
			throw $E3;
		}
		//<<Load SubClass<<
	

	return $CSuccess;
	}

	//----------------------------------------------------------------------------------------------------
	
	
	public function Delete()
	{
		$Success = FALSE;

		try
		{
			$Globali=new cGlobali();
			//$Conn = new mysqli($Global->DBServerNameOnly, $Global->DBUserName,$Global->DBPassword,$Global->DBIniCatalog,$Global->portNumber);
			//$Conn->connect();

			if ($Globali->dbconn->errno > 0)
			{
				throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
			}

			if (!$Globali->dbconn)
			{
				throw new Exception("Could not connect to DB");
			}

			
		}
		catch (Exception $Ex)
		{
			throw $Ex;
		}
		
		//>>Delete Base Class>>
		try
		{
			$PSuccess = parent::Delete($Globali->dbconn);
			//$this->Id = $ParentId;
		}
		catch (Exception $E2)
		{
			throw $E2;
		}
		//<<Load Base Class<<


		//>>Delete SubClass>>
		try
		{
			$CSuccess = $this->DeleteRecord($Globali->dbconn);

			if(!$CSuccess)
			{
				throw new Exception("Error:Record does not exist");
			}
		}
		catch (Exception $E3)
		{
			throw $E3;
		}
		//<<Load SubClass<<
	

	return $CSuccess;
	}		//Delete

	//---------------------------------------------------------------------------------------------------------------

}		//Class


/* >>>Useful notes>>>
 * >>Bind_Param Types (First Argument)
 • i: All INTEGER types
 • d: The DOUBLE and FLOAT types
 • b: The BLOB types
 • s: All other types (including strings)
 * <<Bind_Param Types<<
 *
 *
 */

?>