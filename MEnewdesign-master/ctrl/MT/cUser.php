<?php
include_once("cGlobali.php");

class cUser
{
	public $Id= 0;
	public $UserName="";
	public $Password="";
	public $Email = "";
	public $Company = "";
	public $Salutation = "";
	public $FirstName = "";
	public $MiddleName = "";
	public $LastName = "";
	public $Address = "";
	public $CountryId = 0;
	public $StateId = 0;
	public $CityId = 0;
	public $PIN = 0;
	public $Phone = 0;
	public $Mobile = 0;
	public $NewsletterSub = 0;
	public $DesignationId = 0;
	public $Active = 1;
	public $RegnDt = "";
        public $adminId=1;
        public $superAdminId=2;
//----------------------------------------------------------------------------------------------------

	public function __construct($lId, $sUserName,$sPassword, $sEmail, $sCompany, $sSalutation, $sFirstName, $sMiddleName, $sLastName, 
	$sAddress, $sCountryId, $sStateId, $sCityId,  $sPIN, $sPhone, $sMobile, $sNewsletterSub, $sDesignationId)
	{
		$this->Id = $lId;
		
		$this->UserName = $sUserName;
		$this->Password = $sPassword;
		$this->Email = $sEmail;
		$this->Company = $sCompany;
		$this->Salutation = $sSalutation;
		$this->FirstName = $sFirstName;
		$this->MiddleName = $sMiddleName;
		$this->LastName = $sLastName;
		$this->Address = $sAddress;
		$this->CountryId = $sCountryId;
		$this->StateId = $sStateId;
		$this->CityId = $sCityId;
		$this->PIN = $sPIN;
		$this->Phone = $sPhone;
		$this->Mobile = $sMobile;
		$this->NewsletterSub = $sNewsletterSub;
		$this->DesignationId = $sDesignationId;
		$this->Active = 1;
		$this->RegnDt = date('Y-m-d h:i:s');
	}
	
//----------------------------------------------------------------------------------------------------
	
	public function Save($Conn)
	{
		$Success = FALSE;

		try
		{
			//$Globali=new cGlobali();
			
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
				 $myQuery="UPDATE user SET UserName = ?, Password = ?, Email = ?, Company = ?, Salutation = ?, FirstName = ?, MiddleName = ?,
				 LastName = ?, Address = ?, CountryId = ?, StateId = ?, CityId = ?, PIN = ?, Phone = ?, Mobile = ?, NewsletterSub = ?, DesignationId = ?, Active = ? WHERE Id = ? and Id not in(?,?)";
			}
			else	//New Record: Insert
			{
	
			 $myQuery="INSERT INTO user SET  UserName = ?, Password = ?, Email = ?, Company = ?, Salutation = ?, FirstName = ?, MiddleName = ?,
				 LastName = ?, Address = ?, CountryId = ?, StateId = ?, CityId = ?, PIN = ?, Phone = ?, Mobile = ?, NewsletterSub = ?, DesignationId = ?, Active = ?, RegnDt = ?";
			}
			
			$stmt = $Conn->stmt_init();    // Create a statement object
			
			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (!$Success) throw new Exception("Statement couldn't be prepared. Error: " . $Conn->error);

			
			if ($EditedRecord)
			{	
                           
                 $Success = $stmt->bind_param("sssssssssiiisssiiiiii", $this->UserName,$this->Password, $this->Email, $this->Company, $this->Salutation, $this->FirstName, $this->MiddleName, $this->LastName, $this->Address, $this->CountryId, $this->StateId, $this->CityId, $this->PIN, $this->Phone, $this->Mobile, $this->NewsletterSub, $this->DesignationId, $this->Active, $this->Id,  $this->adminId,  $this->superAdminId);
                                
		  // Bind the parameters
			}
			else
			{
				$Success = $stmt->bind_param("sssssssssiiisssiiis", $this->UserName, $this->Password, $this->Email, $this->Company, $this->Salutation, $this->FirstName, $this->MiddleName, $this->LastName, $this->Address, $this->CountryId, $this->StateId, $this->CityId, $this->PIN, $this->Phone, $this->Mobile, $this->NewsletterSub, $this->DesignationId, $this->Active, $this->RegnDt);  
		  // Bind the parameters
			}
			
			if (! $Success) throw new Exception("Parameters couldn't be bound. Error: " . $Conn->error);


			$Success = $stmt->execute();		//Execute Statement
			if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Conn->error);
				
			$AffectedRows = $stmt->affected_rows;

			if ($Success)
			{
				if (! $EditedRecord)
				{
					$this->Id = $Conn->insert_id;
				}			
			}
			else
			{
				$this->Id = 0;
			}
			
			$stmt->close();
			
			return $this->Id;
		}
		catch (Exception $Ex)
		{
			throw $Ex;
		}
	}		//Save

//---------------------------------------------------------------------------------------------------------------

	public function Load($Conn)
	{
		$Success = FALSE;

		try
		{
			
			$myQuery="SELECT Id, UserName,Password, Email, Company, Salutation, FirstName, MiddleName,
				 LastName, Address, CountryId, StateId, CityId , PIN , Phone , Mobile , NewsletterSub , DesignationId, Active FROM user WHERE Id = ?";
			$stmt = $Conn->stmt_init();    // Create a statement object
				
			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Conn->error);
				
			$Success = $stmt->bind_param("i", $this->Id);    // Bind the parameters
			if (! $Success) throw new Exception("Parameters couldn't be bound. Error: " . $Conn->error);
				
			$Success = $stmt->execute();		//Execute Statement
			if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Conn->error);

			$Success = $stmt->bind_result($this->Id, $this->UserName,$this->Password, $this->Email, $this->Company, $this->Salutation,
			$this->FirstName, $this->MiddleName, $this->LastName, $this->Address, $this->CountryId, $this->StateId, $this->CityId,
			$this->PIN, $this->Phone, $this->Mobile, $this->NewsletterSub, $this->DesignationId, $this->Active);	// Bind the result parameters
			
			if ($stmt->fetch())		//Fetch values actually into bound fields of the result
			{
				$Success = TRUE;
			}
			else
			{
				$Success = FALSE;
			}
			
			$stmt->close();
			
			return $Success;
		}
		catch (Exception $Ex)
		{
			throw $Ex;
		}
	}

//----------------------------------------------------------------------------------------------------

	public function Delete($Conn)
	{
		$Success = FALSE;

		try
		{
		

			$myQuery="DELETE FROM user WHERE Id = ?";
			
			$stmt = $Conn->stmt_init();    // Create a statement object
				
			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Conn->error);

			
			$Success = $stmt->bind_param("i", $this->Id);    // Bind the parameters
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
			
			$stmt->close();
			
			return $Success;
		}
		catch (Exception $Ex)
		{
			throw $Ex;
		}
	}		//Delete

//---------------------------------------------------------------------------------------------------------------
	
}		

?>