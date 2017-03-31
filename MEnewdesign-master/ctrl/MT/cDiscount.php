<?php
include_once("cGlobali.php");
class cDiscount
{
	public $Id = 0;
	public $Name = "";
	public $EventId = 0;
	public $DiscountType = 0;
	public $DiscountAmt = 0;
	public $ActiveFrom = 0;
	public $ActiveTo = 0;
	public $UsageLimit = 0;
	public $PromotionCode = 0; 
	public $Status = 0;
	public $DiscountLevel = 0;
	public $sDiscountMode = 'normal';
	public $sDiscTktFrom = 0;
	public $sDiscTktTo = 0;
	

//----------------------------------------------------------------------------------------------------

	public function __construct($lId, $sName, $sEventId, $sDiscountType, $sDiscountAmt, $sActiveFrom, $sActiveTo, $sUsageLimit, $sPromotionCode, $sStatus, $sDiscountLevel, $sDiscountMode='normal',$sDiscTktFrom=0,$sDiscTktTo=0)
	{
		$this->Id = $lId;		
		$this->Name = $sName; 
		$this->EventId = $sEventId;
		$this->DiscountType = $sDiscountType;
		$this->DiscountAmt = $sDiscountAmt;
		$this->ActiveFrom = $sActiveFrom;
		$this->ActiveTo = $sActiveTo;
		$this->UsageLimit = $sUsageLimit;
		$this->PromotionCode = $sPromotionCode;
		$this->Status = $sStatus;
		$this->DiscountLevel = $sDiscountLevel;
		$this->sDiscountMode = $sDiscountMode;
		$this->sDiscTktFrom = $sDiscTktFrom;
		$this->sDiscTktTo = $sDiscTktTo;
	}
	
//----------------------------------------------------------------------------------------------------
	
	public function Save()
	{
		$Success = FALSE;

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
				$myQuery="UPDATE discounts SET Name = ?, EventId = ?, DiscountType = ?, DiscountAmt = ?, ActiveFrom = ?, ActiveTo =?, UsageLimit = ?, PromotionCode = ?, Status = ?, DiscountLevel = ?, `tickets_from`=?, `tickets_to`=?  WHERE Id = ?";

			}
			else	//New Record: Insert
			{
				$myQuery="INSERT INTO discounts SET Name = ?, EventId = ?, DiscountType=?, DiscountAmt = ?, ActiveFrom = ?, ActiveTo =?, UsageLimit = ?, PromotionCode = ?, Status = ?, DiscountLevel = ?, `discountmode`=?, `tickets_from`=?, `tickets_to`=?";
			}
			
			$stmt = $Globali->dbconn->stmt_init();    // Create a statement object
				
			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);

			
			if ($EditedRecord)
			{
				$Success = $stmt->bind_param("ssssssssssssi", $this->Name, $this->EventId, $this->DiscountType, $this->DiscountAmt, $this->ActiveFrom, $this->ActiveTo, $this->UsageLimit, $this->PromotionCode, $this->Status, $this->DiscountLevel, $this->sDiscTktFrom, $this->sDiscTktTo, $this->Id);    // Bind the parameters
				
			}
			else
			{
				$Success = $stmt->bind_param("sssssssssssss", $this->Name, $this->EventId, $this->DiscountType, $this->DiscountAmt, $this->ActiveFrom, $this->ActiveTo, $this->UsageLimit, $this->PromotionCode, $this->Status, $this->DiscountLevel,$this->sDiscountMode,$this->sDiscTktFrom, $this->sDiscTktTo);    // Bind the parameters
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

			$myQuery="SELECT Id, Name, EventId, DiscountType, DiscountAmt, ActiveFrom, ActiveTo, UsageLimit, PromotionCode, Status, DiscountLevel,discountmode,tickets_from,tickets_to FROM discounts WHERE Id = ?";
			$stmt = $Globali->dbconn->stmt_init();    // Create a statement object
				
			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);
				
			$Success = $stmt->bind_param("i", $this->Id);    // Bind the parameters
			if (! $Success) throw new Exception("Parameters couldn't be bound. Error: " . $Globali->dbconn->error);
				
			$Success = $stmt->execute();		//Execute Statement
			if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Globali->dbconn->error);

			$Success = $stmt->bind_result($this->Id, $this->Name, $this->EventId, $this->DiscountType, $this->DiscountAmt, $this->ActiveFrom, $this->ActiveTo, $this->UsageLimit, $this->PromotionCode, $this->Status, $this->DiscountLevel, $this->discountmode,$this->tickets_from,$this->tickets_to);	// Bind the result parameters
			
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

			$myQuery="DELETE FROM discounts WHERE Id = ?";
			
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
	}		//Save

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