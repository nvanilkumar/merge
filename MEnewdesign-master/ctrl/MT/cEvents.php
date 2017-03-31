<?php
include_once("cGlobali.php");
class cEvents
{
	public $Id = 0;
	public $UserID = 0;
	public $StartDt = 0;
	public $EndDt = 0;
	public $Title = "";
	public $IntendedFor = "";
	public $HighLights = "";
	public $Description = "";
	public $CountryId = 0;
	public $StateId = 0;
	public $CityId = 0;
       public $LocId = 0;
	public $Venue = "";
	public $ContactDetails = "";
	public $OEmails = "";
	public $Logo = "";
	public $URL = "";
	public $WebUrl = "";
	public $Free = 0;
	public $AttachName1 = "";
	public $AttachName2 = "";
	public $AttachName3 = "";
	public $RegnFee = 0;
	public $FacebookLink = "";
	public $PassReqd = 0;
	public $ThemeId = 0;
	public $ticketMsg = 0;
      	public $RegnDt = 0;
	public $CategoryId = 0;
	public $SubCategoryId = 0;
	public $PinCode = "";
	public $Banner = "";
	public $Private = 0;
        public $IsWebinar = 0;
	public $EmailTxt = "";
	public $NoReg = 0;
        public $NoDates = 0;
        public $TnC = "";
        public $use_original_image = 0;
        public $web_hook_url = 0;
        public $tags = 0;
//----------------------------------------------------------------------------------------------------

	public function __construct($lId, $sUserID, $sStartDt, $sEndDt, $sTitle, 
	$sIntendedFor, $sHighLights, $sDescription, $sCountryId,  $sStateId,$sCityId,$sLocId,$sVenue, $sContactDetails, $sOEmails, $sLogo,
	 $sURL, $sWebUrl, $sFree, $sAttachName1, $sAttachName2, $sAttachName3, $sRegnFee, $sFacebookLink, $sPassReqd, $sThemeId, $sticketMsg, $sCategoryId, $sSubCategoryId, $sPinCode , $sBanner, $sPrivate, $sIsWebinar, $sEmailTxt, $sNoReg, $sNoDates, $sTnC, $use_original_image,$web_hook_url=NULL,$tags )
	{
		$this->Id = $lId;
		$this->UserID = $sUserID;
		$this->StartDt = $sStartDt;
		$this->EndDt = $sEndDt;
		$this->Title = $sTitle;
		$this->Title = str_replace("\\","",$this->Title);
		$this->IntendedFor = $sIntendedFor;
		$this->HighLights = $sHighLights;
		$this->Description = $sDescription;
		$this->CountryId = $sCountryId;
		$this->StateId = $sStateId;
		$this->CityId = $sCityId;
              $this->LocId = $sLocId;
		$this->Venue = $sVenue;
		$this->ContactDetails = $sContactDetails;
		$this->OEmails = $sOEmails;
		$this->Logo = $sLogo;
		$this->URL = $sURL;
		$this->WebUrl = $sWebUrl;
		$this->Free = $sFree;
		$this->AttachName1 = $sAttachName1;
		$this->AttachName2 = $sAttachName2;
		$this->AttachName3 = $sAttachName3;
		$this->RegnFee = $sRegnFee;
		$this->FacebookLink = $sFacebookLink;
		$this->PassReqd = $sPassReqd;
		$this->ThemeId = $sThemeId;
		$this->ticketMsg = $sticketMsg;
		$this->RegnDt = date('Y-m-d h:i:s');
		$this->CategoryId = $sCategoryId;
		$this->SubCategoryId = $sSubCategoryId;
		$this->PinCode = $sPinCode;
		$this->Banner = $sBanner;
		$this->Private = $sPrivate;
                $this->IsWebinar = $sIsWebinar;
		$this->EmailTxt = $sEmailTxt;
		$this->NoReg = $sNoReg;
                $this->NoDates = $sNoDates;
		$this->TnC = $sTnC;
                $this->use_original_image = $use_original_image;
                $this->web_hook_url = $web_hook_url;
                $this->tags = $tags;
	}
	
//----------------------------------------------------------------------------------------------------
	
	public function Save()
	{
		$Success = FALSE;

		try
		{
		
			$this->Title = str_replace("\\","",$this->Title);
		
			$Globali=new cGlobali();
			

			if ($Globali->dbconn->errno > 0)
			{
				throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
			}

			if (!$Globali->dbconn)
			{
				throw new Exception("Could not connect to DB");
			}

			if ($this->Id > 0)		//Edited Record: Update
			{
				$EditedRecord = TRUE;
			}
			else
			{
				$EditedRecord = FALSE;
			}
			
			if ($EditedRecord)
			{
				$myQuery="UPDATE events SET   StartDt = ?, EndDt = ?, Title = ?,  
	IntendedFor = ?, HighLights = ?, Description = ?, CountryId = ?,  StateId = ?, CityId = ?, LocId=?, Venue = ?, ContactDetails = ?, OEmails = ?, Logo = ?,
	 URL = ?, WebUrl = ?, Free = ?, AttachName1 = ?, AttachName2 = ?, AttachName3 = ?,
	  RegnFee = ?, FacebookLink = ?, PassReqd = ?, ThemeId = ? , ticketMsg = ?, CategoryId = ?, SubCategoryId = ?, PinCode=?, Banner=?, Private=?, IsWebinar=?, EmailTxt=?, NoReg=?, NoDates=?, TnC=?, use_original_image=?,web_hook_url=? , tags = ? WHERE Id = ?";
			}
			else	//New Record: Insert
			{
				$myQuery="INSERT INTO events SET  UserID = ?, StartDt = ?, EndDt = ?, Title = ?, 
	IntendedFor = ?, HighLights = ?, Description = ?, CountryId = ?,  StateId = ?, CityId = ?, LocId=?, Venue = ?, ContactDetails = ?, OEmails = ?, Logo = ?,
	 URL = ?, WebUrl = ?, Free = ?, AttachName1 = ?, AttachName2 = ?, AttachName3 = ?, RegnFee = ?, FacebookLink = ?, 
	  PassReqd = ?, ThemeId = ?, ticketMsg = ?, RegnDt = ?, CategoryId = ?, SubCategoryId = ?, PinCode = ?, Banner = ?, Private = ?, IsWebinar=?, EmailTxt=?, NoReg=?, NoDates=?, TnC=?, use_original_image=?, web_hook_url=?,tags = ? ";
			}
			
			$stmt = $Globali->dbconn->stmt_init();    // Create a statement object
				
			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);

			
			if ($EditedRecord)
			{
				$Success = $stmt->bind_param("ssssssiiiissssssisssdsiisssssssssssdssi",  $this->StartDt, $this->EndDt, $this->Title, $this->IntendedFor, $this->HighLights, $this->Description, 
				$this->CountryId, $this->StateId, $this->CityId,$this->LocId, $this->Venue, $this->ContactDetails,$this->OEmails, 
				$this->Logo, $this->URL, $this->WebUrl, $this->Free, $this->AttachName1, 
				$this->AttachName2, $this->AttachName3, $this->RegnFee, $this->FacebookLink,$this->PassReqd,
				$this->ThemeId, $this->ticketMsg, $this->CategoryId, $this->SubCategoryId, $this->PinCode, $this->Banner, $this->Private, $this->IsWebinar, $this->EmailTxt, $this->NoReg, $this->NoDates, $this->TnC, $this->use_original_image,$this->web_hook_url,$this->tags, $this->Id);    // Bind the parameters
			}
			else
			{
				$Success = $stmt->bind_param("issssssiiiissssssisssdsiissssssssssssdss",$this->UserID, $this->StartDt, $this->EndDt, $this->Title, 
	$this->IntendedFor, $this->HighLights, $this->Description, $this->CountryId,$this->StateId, $this->CityId,$this->LocId, $this->Venue, $this->ContactDetails,$this->OEmails, $this->Logo,
	 $this->URL, $this->WebUrl, $this->Free, $this->AttachName1, $this->AttachName2, $this->AttachName3, $this->RegnFee, $this->FacebookLink,$this->PassReqd,$this->ThemeId,$this->ticketMsg, $this->RegnDt, $this->CategoryId, $this->SubCategoryId, $this->PinCode, $this->Banner, $this->Private, $this->IsWebinar, $this->EmailTxt, $this->NoReg, $this->NoDates, $this->TnC, $this->use_original_image,$this->web_hook_url,$this->tags);  
		  // Bind the parameters
			}
			
			if (! $Success) throw new Exception("Parameters couldn't be bound. Error: " . $Globali->dbconn->error);

			
			$Success = $stmt->execute();		//Execute Statement
			if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Globali->dbconn->error);
				
			$AffectedRows = $stmt->affected_rows;
			if ($AffectedRows > 0)
			{
				if (! $EditedRecord)	$this->Id = $Globali->dbconn->insert_id;
			}
			else
			{
				$this->Id = 0;
			}
			
			$Globali->dbconn->close();
			$stmt->close();
			
			return $this->Id;
		}
		catch (Exception $Ex)
		{
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
			
			if ($Globali->dbconn->errno > 0)
			{
				throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
			}

			if (!$Globali->dbconn)
			{
				throw new Exception("Could not connect to DB");
			}
			
		//echo $this->Id;
		
			$myQuery="SELECT Id, UserID, StartDt, EndDt, Title, 
	IntendedFor, HighLights, Description , CountryId,  StateId , CityId,LocId, Venue, ContactDetails, OEmails, Logo,
	 URL, WebUrl, Free, AttachName1, AttachName2, AttachName3, RegnFee, FacebookLink, PassReqd, ThemeId, ticketMsg, CategoryId, SubCategoryId, PinCode, Banner, Private, IsWebinar,EmailTxt,NoReg,NoDates,TnC,use_original_image,web_hook_url, tags FROM events WHERE Id = ?";
			$stmt = $Globali->dbconn->stmt_init();    // Create a statement object
				
			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);
				
			$Success = $stmt->bind_param("i", $this->Id);    // Bind the parameters
			if (! $Success) throw new Exception("Parameters couldn't be bound. Error: " . $Globali->dbconn->error);
				
			$Success = $stmt->execute();		//Execute Statement
			if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Globali->dbconn->error);

			$Success = $stmt->bind_result($this->Id, $this->UserID, $this->StartDt, $this->EndDt, $this->Title,
			$this->IntendedFor, $this->HighLights, $this->Description,$this->CountryId,
			$this->StateId, $this->CityId, $this->LocId, $this->Venue, $this->ContactDetails, $this->OEmails, $this->Logo,$this->URL,
			$this->WebUrl,$this->Free,$this->AttachName1,$this->AttachName2,$this->AttachName3,$this->RegnFee,$this->FacebookLink,$this->PassReqd,$this->ThemeId ,$this->ticketMsg, $this->CategoryId, $this->SubCategoryId, $this->PinCode, $this->Banner, $this->Private, $this->IsWebinar, $this->EmailTxt, $this->NoReg, $this->NoDates, $this->TnC, $this->use_original_image,$this->web_hook_url, $this->tags);	// Bind the result parameters
			
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
//---------------------------------------------------------------------------------------------------------------

	public function LoadAllByIndustries()
	{
		$Success = FALSE;

		try
		{
			$Globali=new cGlobali();
		/*$Globali->dbconn = new mysqli('localhost', 'meraeven_admin','','meraeven_dmeraevent');
			//$Globali->dbconn->connect();

			if ($Globali->dbconn->errno > 0)
			{
				throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
			}

			if (!$Globali->dbconn)
			{
				throw new Exception("Could not connect to DB");
			}
*/
			//Kashif's code: 07Aug2009
			

			$myQuery = "SELECT DISTINCT i.id, i.industries, (SELECT COUNT( e.Id ) FROM events e WHERE e.industyid = i.id and Published = 1) AS EventCount FROM industries AS i ORDER BY i.Industries ASC";
			$Result = $Globali->query($myQuery); 
		   
			//$Globali->dbconn->close();
		   return $Result;
//Kashif's Code<<<<< 


			//Manoj's code: 07Aug2009>>>
			/*		$myQuery = "SELECT DISTINCT i.id, i.industries, (SELECT COUNT( e.Id ) FROM events e WHERE e.industyid = i.id ) AS EventCount FROM industries i ";
					$Result = $Globali->dbconn->query($myQuery, MYSQLI_USE_RESULT); 
					echo  "Number of Rows: " . $Result->num_rows;*/
			//Manoj's Code: 07Aug2009<<<

/*	       	$myQuery = "SELECT DISTINCT i.id, i.industries, (SELECT COUNT( e.Id ) FROM events e WHERE e.industyid = i.id ) AS EventCount FROM industries i ";
		 	$Success = mysqli_query($myQuery);
	 		
	 		$stmt = $Globali->dbconn->stmt_init();    // Create a statement object
				
			$Success = $stmt->prepare($myQuery);  	  // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);
				
		//$Success = $stmt->bind_param("i", $this->Id);    // Bind the parameters
		//	if (! $Success) throw new Exception("Parameters couldn't be bound. Error: " . $Globali->dbconn->error);
					

		$Success = $stmt->execute();		//Execute Statement
		if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Globali->dbconn->error);*/
			
				
				
			/*	
			
			if ($stmt->fetch())		//Fetch values actually into bound fields of the result
			{
				$Success = TRUE;
			}
			else
			{
				$Success = FALSE;
			}
			
			$Globali->dbconn->close();
			//$stmt->close();
			
			return $Result;
			*/
		}
		catch (Exception $Ex)
		{
			throw $Ex;
		}
	}

//----------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------

	public function LoadAllByCities()
	{
		$Success = FALSE;

		try
		{    		$Globali=new cGlobali();
			/*$Globali=new cGlobali();
			$Globali->dbconn = new mysqli('localhost', 'meraeven_admin','','meraeven_dmeraevent');
			//$Globali->dbconn->connect();

			if ($Globali->dbconn->errno > 0)
			{
				throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
			}

			if (!$Globali->dbconn)
			{
				throw new Exception("Could not connect to DB");
			}*/

 //Kashif's code: 07Aug2009

	
			$myQuery = "SELECT DISTINCT c.Id, c.City, (SELECT COUNT( e.Id ) FROM events e WHERE e.CityId = c.Id and Published = 1) AS EventCount FROM Cities AS c  ORDER BY c.City ASC";
			$Result = $Globali->query($myQuery); 
		   
		   		//$Globali->dbconn->close();
		   return $Result;
//Kashif's Code<<<<< 

		}
		catch (Exception $Ex)
		{
			throw $Ex;
		}
	}

//----------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------

	public function LoadAllByEventTypes()
	{
		$Success = FALSE;

		try
		{   		$Globali=new cGlobali();
			/*$Globali=new cGlobali();
			$Globali->dbconn = new mysqli('localhost', 'meraeven_admin','','meraeven_dmeraevent');
			//$Globali->dbconn->connect();

			if ($Globali->dbconn->errno > 0)
			{
				throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
			}

			if (!$Globali->dbconn)
			{
				throw new Exception("Could not connect to DB");
			}
*/
			//		
			$myQuery = "SELECT DISTINCT et.Id, et.EventType, (SELECT COUNT( e.Id ) FROM events e WHERE e.EventTypeId = et.Id and Published = 1) AS EventCount FROM eventtypes AS et ORDER BY et.EventType ASC";
			$Result = $Globali->query($myQuery); 
			
			//$Globali->dbconn->close();
			return $Result;
			//Kashif's Code<<<<< 

		}
		catch (Exception $Ex)
		{
			throw $Ex;
		}
	}

//----------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------

	public function LoadAllByCurrMonth()
	{
		$Success = FALSE;

		try
		{
			$Globali=new cGlobali();
			
				
			
			//SHOW ONLY Next 30 Days events
			$todaysSDate = date ("Y-m-d 00:00:00");
			
			$after30DaysEDate = date ("Y-m-d", mktime (0,0,0,date("m"),(date("d")+30),date("Y"))).' 23:59:59';
			
//			$myQuery = "SELECT Id, UserId, StartDt, EndDt, Title, IndustyId, EventTypeId from events where month(StartDt)=month(now()) and year(StartDt)=year(now()) and Published = 1 ORDER BY Title ASC";
			$myQuery = "SELECT Id, UserId, StartDt, EndDt, Title from events where StartDt >= '".$todaysSDate."' AND StartDt <= '".$after30DaysEDate."' AND Published = 1 ORDER BY StartDt ASC";//Query modified according to requirement Next 30 Days events
			$Result = $Globali->query($myQuery); 
			
			//	$Globali->dbconn->close();
			return $Result;
			//Kashif's Code<<<<< 

		}
		catch (Exception $Ex)
		{
			throw $Ex;
		}
	}

//----------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------

	public function LoadCurrentBySelect($choice, $qId)
	{
		$Globali=new cGlobali();
	
	/*	echo "DBServerName - " . $Globali->DBServerName . "<br>";
		echo "DBUserName - " . $Globali->DBUserName . "<br>";
		echo "DBPassword - " . $Globali->DBPassword . "<br>";
		echo "DBIniCatalog - " . $Globali->DBIniCatalog . "<br>";		
		echo "$qId - " . $qId . "<br>";			
		echo "$choice - " . $choice . "<br>";	*/		
				
		$Success = FALSE;

		try
		{
			if($choice==1)
				$myQuery = "SELECT * FROM events WHERE IndustyId=".$qId." and StartDt > now() and Published = 1 order by 3";
			if($choice==2)
				$myQuery = "SELECT * FROM events WHERE CityId=".$qId." and StartDt > now() and Published = 1 order by 3";
			if($choice==3)
				$myQuery = "SELECT * FROM events WHERE EventTypeId=".$qId." and StartDt > now() and Published = 1 order by 3";
			if($choice==4)
				$myQuery = "SELECT * FROM events WHERE Id =".$qId." and Published = 1 order by 3";
			 
	 	   $Result = $Globali->query($myQuery); 
			   		//$Globali->dbconn->close();
		   return $Result;
//Kashif's Code<<<<< 

		}
		catch (Exception $Ex)
		{
			throw $Ex;
		}
	}

//----------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------

	public function LoadPassedBySelect($choice, $qId)
	{
		$Globali=new cGlobali();
	
	/*	echo "DBServerName - " . $Globali->DBServerName . "<br>";
		echo "DBUserName - " . $Globali->DBUserName . "<br>";
		echo "DBPassword - " . $Globali->DBPassword . "<br>";
		echo "DBIniCatalog - " . $Globali->DBIniCatalog . "<br>";		
		echo "$qId - " . $qId . "<br>";			
		echo "$choice - " . $choice . "<br>";	*/		
				
		$Success = FALSE;

		try
		{	

			if($choice==1)
				$myQuery = "SELECT * FROM events WHERE IndustyId=".$qId." and StartDt < now() and Published = 1 order by 3";
			if($choice==2)
				$myQuery = "SELECT * FROM events WHERE CityId=".$qId." and StartDt < now() and Published = 1 order by 3";
			if($choice==3)
				$myQuery = "SELECT * FROM events WHERE EventTypeId=".$qId." and StartDt < now() and Published = 1 order by 3";
			if($choice==4)
				$myQuery = "SELECT * FROM events WHERE Id =".$qId." and StartDt < now() and Published = 1 order by 3";
			 
	 	   $Result = $Globali->query($myQuery); 
			   		//$Globali->dbconn->close();
		   return $Result;
		//Kashif's Code<<<<< 

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

			$myQuery="DELETE FROM events WHERE Id = ?";
			
			$stmt = $Globali->dbconn->stmt_init();    // Create a statement object
				
			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);

			
			$Success = $stmt->bind_param("i", $this->Id);    // Bind the parameters
			if (! $Success) throw new Exception("Parameters couldn't be bound. Error: " . $Globali->dbconn->error);

			
			$Success = $stmt->execute();		//Execute Statement
			if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Globali->dbconn->error);
				
			$AffectedRows = $stmt->affected_rows;
			if ($AffectedRows > 0)
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
	}		//Delete

//---------------------------------------------------------------------------------------------------------------
	
}		//Class



?>