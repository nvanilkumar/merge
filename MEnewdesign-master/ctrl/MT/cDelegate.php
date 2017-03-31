<?php
include_once("cGlobali.php");
//jagadish:this files are removed.

/*
include_once("cCityPref.php");
include_once("cIndustryPref.php");
include_once("cEventPref.php");
include_once("cFunctionPref.php");
include_once("cEventNamePref.php");
include_once("cAssoMember.php");
include_once("cPartnerOffers.php");
*/

include_once("cUser.php");

class cDelegate extends cUser
{
	public $UserId = 0;
	public $Speaker = 0;
	public $DisplayName = "";
	public $UserTypeId = 0;

	private $EditedRecord = FALSE;

	//----------------------------------------------------------------------------------------------------

	public function __construct($lId, $sUserName,$sPassword, $sEmail, $sCompany, $sSalutation,
	$sFirstName, $sMiddleName, $sLastName,
	$sUAddress, $sCountryId, $sStateId, $sCityId,  $sPIN, $sPhone, $sMobile, $sNewsletterSub, $sDesignationId, $sSpeaker ,
	$sDisplayName, $sUserTypeId)
	{
		parent::__construct($lId, $sUserName, $sPassword, $sEmail, $sCompany, $sSalutation,
		$sFirstName, $sMiddleName, $sLastName,
		$sUAddress, $sCountryId, $sStateId, $sCityId,  $sPIN, $sPhone, $sMobile, $sNewsletterSub, $sDesignationId);

		$this->UserId = $lId;
		$this->Speaker = $sSpeaker;
		$this->DisplayName = $sDisplayName;
		$this->UserTypeId = $sUserTypeId;

			
		//echo $this->FirstName;
	}

	//----------------------------------------------------------------------------------------------------

	private function SaveRecord($Conn)		//Returns Boolean
	{
		if ($this->EditedRecord)
		{
			$myQuery="UPDATE  delegate SET UserId = ?, Speaker = ?, DisplayName = ?, UserTypeId = ?  WHERE UserId = ?";
		}
		else	//New Record: Insert
		{
			$myQuery="INSERT INTO delegate SET UserId = ?, Speaker = ?, DisplayName = ?, UserTypeId = ? ";
		}
			
		$stmt = $Conn->stmt_init();    // Create a statement object

		$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
		if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Conn->error);

			
		if ($this->EditedRecord)
		{
			$Success = $stmt->bind_param("iisii", $this->UserId, $this->Speaker, $this->DisplayName, $this->UserTypeId, $this->UserId);
			// Bind the parameters
		}
		else
		{
			$Success = $stmt->bind_param("iisi", $this->UserId, $this->Speaker,  $this->DisplayName, $this->UserTypeId);
			// Bind the parameters
		}
			
		if (! $Success) throw new Exception("Parameters couldn't be bound. Error: " . $Conn->error);

			
		$Success = $stmt->execute();		//Execute Statement
		if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Conn->error);

		$AffectedRows = $stmt->affected_rows;

		if ($Success)
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
		$myQuery="SELECT  UserId, Speaker, DisplayName, UserTypeId   FROM delegate WHERE UserId = ?";
		$stmt = $Conn->stmt_init();    // Create a statement object

		$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
		if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Conn->error);

		$Success = $stmt->bind_param("i", $this->UserId);    // Bind the parameters
		if (! $Success) throw new Exception("Parameters couldn't be bound. Error: " . $Conn->error);

		$Success = $stmt->execute();		//Execute Statement
		if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Conn->error);

		$Success = $stmt->bind_result($this->UserId, $this->Speaker, $this->DisplayName, $this->UserTypeId);	// Bind the result parameters

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
		$myQuery="DELETE FROM delegate WHERE UserId = ?";

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

/*	public function Save()
	{
		//echo "hello";
		$Success = FALSE;

		//Connect to DB>>>
		try
		{
			$Globali=new cGlobali();
			$Conn = new mysqli($Globali->DBServerNameOnly, $Globali->DBUserName,$Globali->DBPassword,$Globali->DBIniCatalog,$Globali->portNumber);
			//$Conn->connect();

			if ($Conn->errno > 0)
			{
				throw new Exception("Could not connect to DB. Error: " . $Conn->error);
			}

			if (!$Conn)
			{
				throw new Exception("Could not connect to DB");
			}
		}
		catch (Exception $E1)
		{
			throw $E1;
		}
		//<<<Connect to DB


		$Conn->autocommit(FALSE);

		//>>Save Base Class>>
		try
		{
			$ParentId = parent::Save($Conn);
			//$this->Id = $ParentId;
		}
		catch (Exception $E2)
		{
			throw $E2;
		}
		//<<Save Base Class<<


		//>>Save SubClass>>
		try
		{
			$Success = $this->SaveRecord($Conn);

			if(!$Success)
			{
				throw new Exception("Error:while saveing record");
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
			$Conn->commit;
		}
		else
		{
			$Conn->rollback;
		}
		//<<Commit/Rollback<<

		if ($Conn)
		{
			$Conn->autocommit(TRUE);
			$Conn->close();
		}


		//		$this->Id = $ParentId;
		return $this->Id;
	}		//Save

*/	//---------------------------------------------------------------------------------------------------------------

	//Method written by Manoj: 28Aug2009
	public function Save($arCityPrefIDs, $arIndustryPrefIDs, $arEventPrefIDs, $arFunctionPrefIDs, $arEventNamePrefNames, $arAssoMemberIDs, $arPartnerOffersIDs)
	{
		if ($this->UserId > 0)
		{
			$this->EditedRecord = TRUE;
		}
		else
		{
			$this->EditedRecord = FALSE;	
		}

		//echo "hello";
		$Success = FALSE;

		//Connect to DB>>>
		try
		{
			$Globali=new cGlobali();
			

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
			$ParentId = parent::Save($Globali->dbconn);

			if ($ParentId <= 0)
			{
				$Globali->dbconn->rollback;
				throw new Exception("Error occurred while trying to save User");
			}
			else
			{
				$this->UserId = $ParentId;
			}

			//$this->Id = $ParentId;
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

			if(!$Success)
			{
				$Globali->dbconn->rollback;
				throw new Exception("Error occurred while trying to save Delegate");
			}
		}
		catch (Exception $E3)
		{
			throw $E3;
		}
		//<<Save SubClass<<

		//>>>>>>>>>>>>>>>>>>>>>>Save Contained Classes>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		//>>Save CityPref>>
		try
		{
			//Delete existing records for this UserID >>>
			$CityPref = new cCityPref(0, 0, 0);
			$Success = $CityPref->DeleteAll($this->UserId, $Globali->dbconn);
				
			if (! $Success)
			{
				$Globali->dbconn->rollback;
				throw new Exception("Error occurred while trying to save CityPref - Delete Existing");
			}
			//<<<Delete existing records for this UserID

			//Insert New Records>>>
			for ($i = 0; $i < count($arCityPrefIDs); ++$i)
			{
				$CityPref = new cCityPref(0, $this->UserId, $arCityPrefIDs[$i]);
				$CityPrefId = $CityPref->SaveTrans($Globali->dbconn);

				if ($CityPrefId <= 0)
				{
					$Globali->dbconn->rollback;
					throw new Exception("Error occurred while trying to save CityPref");
				}
			}
			//<<<Insert New Records

		}
		catch (Exception $E3)
		{
			throw $E3;
		}
		//<<Save CityPref<<

		//>>Save IndustryPref>>
		try
		{
			//Delete existing records for this UserID >>>
			$IndustryPref = new cIndustryPref(0, 0, 0);
			$Success = $IndustryPref->DeleteAll($this->UserId, $Globali->dbconn);
			if (! $Success)
			{
				$Globali->dbconn->rollback;
				throw new Exception("Error occurred while trying to save IndustryPref - Delete Existing");
			}
			//<<<Delete existing records for this UserID

			//Insert New Records>>>
			for ($i = 0; $i < count($arIndustryPrefIDs); ++$i)
			{
				$IndustryPref = new cIndustryPref(0, $this->UserId, $arIndustryPrefIDs[$i]);
				$IndustryPrefId = $IndustryPref->SaveTrans($Globali->dbconn);

				if ($IndustryPrefId <= 0)
				{
					$Globali->dbconn->rollback;
					throw new Exception("Error occurred while trying to save IndustryPref");
				}
			}
			//<<<Insert New Records

		}
		catch (Exception $E3)
		{
			throw $E3;
		}
		//<<Save IndustryPref<<

		//>>Save EventPref>>
		try
		{
			//Delete existing records for this UserID >>>
			$EventPref = new cEventPref(0, 0, 0);
			$Success = $EventPref->DeleteAll($this->UserId, $Globali->dbconn);
			if (! $Success)
			{
				$Globali->dbconn->rollback;
				throw new Exception("Error occurred while trying to save EventPref - Delete Existing");
			}
			//<<<Delete existing records for this UserID

			//Insert New Records>>>
			for ($i = 0; $i < count($arEventPrefIDs); ++$i)
			{
				$EventPref = new cEventPref(0, $this->UserId, $arEventPrefIDs[$i]);
				$EventPrefId = $EventPref->SaveTrans($Globali->dbconn);

				if ($EventPrefId <= 0)
				{
					$Globali->dbconn->rollback;
					throw new Exception("Error occurred while trying to save EventPref");
				}
			}
			//<<<Insert New Records

		}
		catch (Exception $E3)
		{
			throw $E3;
		}
		//<<Save EventPref<<

		//>>Save FunctionPref>>
		try
		{
			//Delete existing records for this UserID >>>
			$FunctionPref = new cFunctionPref(0, 0, 0);
			$Success = $FunctionPref->DeleteAll($this->UserId, $Globali->dbconn);
			if (! $Success)
			{
				$Globali->dbconn->rollback;
				throw new Exception("Error occurred while trying to save EventPref - Delete Existing");
			}
			//<<<Delete existing records for this UserID

			//Insert New Records>>>
			for ($i = 0; $i < count($arFunctionPrefIDs); ++$i)
			{
				$FunctionPref = new cFunctionPref(0, $this->UserId, $arFunctionPrefIDs[$i]);
				$FunctionPrefId = $FunctionPref->SaveTrans($Globali->dbconn);

				if ($FunctionPrefId <= 0)
				{
					$Globali->dbconn->rollback;
					throw new Exception("Error occurred while trying to save EventPref");
				}
			}
			//<<<Insert New Records

		}
		catch (Exception $E3)
		{
			throw $E3;
		}
		//<<Save FunctionPref<<

		//>>Save EventNamePref>>
		try
		{
			//Delete existing records for this UserID >>>
			$EventNamePref = new cEventNamePref(0, 0, '');
			$Success = $EventNamePref->DeleteAll($this->UserId, $Globali->dbconn);
			if (! $Success)
			{
				$Globali->dbconn->rollback;
				throw new Exception("Error occurred while trying to save EventPref - Delete Existing");
			}
			//<<<Delete existing records for this UserID

			//Insert New Records>>>
			for ($i = 0; $i < count($arEventNamePrefNames); ++$i)
			{
				$EventNamePref = new cEventNamePref(0, $this->UserId, $arEventNamePrefNames[$i]);
				$EventNamePrefId = $EventNamePref->SaveTrans($Globali->dbconn);

				if ($EventNamePrefId <= 0)
				{
					$Globali->dbconn->rollback;
					throw new Exception("Error occurred while trying to save EventPref");
				}
			}
			//<<<Insert New Records

		}
		catch (Exception $E3)
		{
			throw $E3;
		}
		//<<Save EventNamePref<<

		//>>Save AssoMember>>
		try
		{
			//Delete existing records for this UserID >>>
			$AssoMember = new cAssomember(0, 0, 0);
			$Success = $AssoMember->DeleteAll($this->UserId, $Globali->dbconn);
			if (! $Success)
			{
				$Globali->dbconn->rollback;
				throw new Exception("Error occurred while trying to save EventPref - Delete Existing");
			}
			//<<<Delete existing records for this UserID

			//Insert New Records>>>
			for ($i = 0; $i < count($arAssoMemberIDs); ++$i)
			{
				$AssoMember = new cAssomember(0, $this->UserId, $arAssoMemberIDs[$i]);
				$AssoMemberId = $AssoMember->SaveTrans($Globali->dbconn);

				if ($AssoMemberId <= 0)
				{
					$Globali->dbconn->rollback;
					throw new Exception("Error occurred while trying to save EventPref");
				}
			}
			//<<<Insert New Records

		}
		catch (Exception $E3)
		{
			throw $E3;
		}
		//<<Save AssoMember<<

		//>>Save PartnerOffers>>
		try
		{
			//Delete existing records for this UserID >>>
			$PartnerOffers = new cPartnerOffers(0, 0, 0);
			$Success = $PartnerOffers->DeleteAll($this->UserId, $Globali->dbconn);
			if (! $Success)
			{
				$Globali->dbconn->rollback;
				throw new Exception("Error occurred while trying to save EventPref - Delete Existing");
			}
			//<<<Delete existing records for this UserID

			//Insert New Records>>>
			for ($i = 0; $i < count($arPartnerOffersIDs); ++$i)
			{
				$PartnerOffers = new cPartnerOffers(0, $this->UserId, $arPartnerOffersIDs[$i]);
				$PartnerOffersId = $PartnerOffers->SaveTrans($Globali->dbconn);

				if ($PartnerOffersId <= 0)
				{
					$Globali->dbconn->rollback;
					throw new Exception("Error occurred while trying to save EventPref");
				}
			}
			//<<<Insert New Records

		}
		catch (Exception $E3)
		{
			throw $E3;
		}
		//<<Save PartnerOffers<<
			
		//<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<Save Contained Classes<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<


		//>>Commit/Rollback>>
		$Globali->dbconn->commit;

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