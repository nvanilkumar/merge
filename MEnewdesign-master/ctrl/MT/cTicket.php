<?php
include_once("cGlobali.php");
class cTicket
{
	public $Id = 0;
	public $Name = "";
	public $Description = "";
	public $EventId = 0;
	public $Price = 0;
	public $MaxQtyOnSale = 0;
	public $OrderQtyMin = 0;
	public $OrderQtyMax = 0;
	public $SalesStartOn = 0; 
	public $SalesEndOn = 0;
	public $Status = 0;
	public $ServiceTax = 0;
    public $ServiceTaxValue = 0;
	public $EntertainmentTax = 0;
    public $EntertainmentTaxValue = 0;
	public $ticketLevel = 0;
	public $DispOrder = 0;
	public $dispno  = 0;
        public $donation=0;
        public $currencyId=1;

//----------------------------------------------------------------------------------------------------

	public function __construct($lId, $sName, $sDescription, $sEventId, $sPrice , $sMaxQtyOnSale, $sOrderQtyMin, $sOrderQtyMax, $sSalesStartOn, $sSalesEndOn, $sStatus, $sServiceTax, $sServiceTaxValue,$sEntertainmentTax, $sEntertainmentTaxValue, $sticketLevel, $sDispOrder, $sdispno, $sdonation,$sCurrencyId)
	{
		$this->Id = $lId;		
		$this->Name = $sName; 
		$this->Description = $sDescription;
		$this->EventId = $sEventId;
		$this->Price = $sPrice;
		$this->MaxQtyOnSale = $sMaxQtyOnSale;
		$this->OrderQtyMin = $sOrderQtyMin;
		$this->OrderQtyMax = $sOrderQtyMax;
		$this->SalesStartOn = $sSalesStartOn;
		$this->SalesEndOn = $sSalesEndOn;
		$this->Status = $sStatus;
		$this->ServiceTax = $sServiceTax;
        $this->ServiceTaxValue = $sServiceTaxValue;
		$this->EntertainmentTax = $sEntertainmentTax;
        $this->EntertainmentTaxValue = $sEntertainmentTaxValue;
		$this->ticketLevel = $sticketLevel;
		$this->DispOrder = $sDispOrder;
		$this->dispno = $sdispno;
                $this->donation=$sdonation;
                $this->currencyId=$sCurrencyId;
	}
	
//----------------------------------------------------------------------------------------------------
	
	public function Save()
	{
		$Success = FALSE;

		try
		{
			$Globali=new cGlobali();
			//$Globali->dbconn = new mysqli($Global->DBServerNameOnly, $Global->DBUserName,$Global->DBPassword,$Global->DBIniCatalog,$Global->portNumber);
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
			ELSE
			{
				$EditedRecord = FALSE;
			}
			
			if ($EditedRecord)
			{
				$myQuery="UPDATE tickets SET Name = ?, Description = ?, Price = ?, MaxQtyOnSale = ?, OrderQtyMin =?, OrderQtyMax = ?, SalesStartOn = ?, SalesEndOn = ?, Status = ?, ServiceTax = ?, ServiceTaxValue = ?, EntertainmentTax=?, EntertainmentTaxValue=?, ticketLevel = ?, DispOrder = ?, dispno = ?, donation =?, currencyId= ? WHERE Id = ?";

			}
			else	//New Record: Insert
			{
				$myQuery="INSERT INTO tickets (Name,Description,EventId,Price,MaxQtyOnSale,OrderQtyMin,OrderQtyMax,SalesStartOn,SalesEndOn,Status,ServiceTax,ServiceTaxValue, EntertainmentTax,EntertainmentTaxValue,ticketLevel,DispOrder,dispno,donation,currencyId)  values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			}
			
			$stmt = $Globali->dbconn->stmt_init();    // Create a statement object
				
			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);

			
			if ($EditedRecord)
			{
				$Success = $stmt->bind_param("ssssssssssssssssiii", $this->Name, $this->Description, $this->Price, $this->MaxQtyOnSale, $this->OrderQtyMin, $this->OrderQtyMax, $this->SalesStartOn, $this->SalesEndOn, $this->Status, $this->ServiceTax, $this->ServiceTaxValue,$this->EntertainmentTax, $this->EntertainmentTaxValue, $this->ticketLevel, $this->DispOrder, $this->dispno, $this->donation,  $this->currencyId,$this->Id);    // Bind the parameters
				
			}
			else
			{
				$Success = $stmt->bind_param("sssssssssssssssssii", $this->Name, $this->Description, $this->EventId, $this->Price, $this->MaxQtyOnSale, $this->OrderQtyMin, $this->OrderQtyMax, $this->SalesStartOn, $this->SalesEndOn, $this->Status, $this->ServiceTax, $this->ServiceTaxValue ,$this->EntertainmentTax, $this->EntertainmentTaxValue,$this->ticketLevel, $this->DispOrder, $this->dispno,  $this->donation,  $this->currencyId);    // Bind the parameters
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
			//$Globali->dbconn = new mysqli($Global->DBServerNameOnly, $Global->DBUserName,$Global->DBPassword,$Global->DBIniCatalog,$Global->portNumber);
			//$Globali->dbconn->connect();

			if ($Globali->dbconn->errno > 0)
			{
				throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
			}

			if (!$Globali->dbconn)
			{
				throw new Exception("Could not connect to DB");
			}

			$myQuery="SELECT Id, Name, Description, EventId, Price, MaxQtyOnSale, OrderQtyMin, OrderQtyMax, SalesStartOn, SalesEndOn, Status, ServiceTax, ServiceTaxValue,EntertainmentTax, EntertainmentTaxValue,ticketLevel, DispOrder, dispno, donation, currencyId FROM tickets WHERE Id = ?";
			
			$stmt = $Globali->dbconn->stmt_init();    // Create a statement objectValue
				
			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);
				
			$Success = $stmt->bind_param("i", $this->Id);    // Bind the parameters
			if (! $Success) throw new Exception("Parameters couldn't be bound. Error: " . $Globali->dbconn->error);
				
			$Success = $stmt->execute();		//Execute Statement
			if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Globali->dbconn->error);

			$Success = $stmt->bind_result($this->Id, $this->Name, $this->Description, $this->EventId, $this->Price, $this->MaxQtyOnSale, $this->OrderQtyMin, $this->OrderQtyMax, $this->SalesStartOn, $this->SalesEndOn, $this->Status, $this->ServiceTax, $this->ServiceTaxValue,$this->EntertainmentTax, $this->EntertainmentTaxValue, $this->ticketLevel, $this->DispOrder, $this->dispno,  $this->donation,  $this->currencyId);	// Bind the result parameters
			
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
			//$Globali->dbconn = new mysqli($Global->DBServerNameOnly, $Global->DBUserName,$Global->DBPassword,$Global->DBIniCatalog,$Global->portNumber);
			//$Globali->dbconn->connect();

			if ($Globali->dbconn->errno > 0)
			{
				throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
			}

			if (!$Globali->dbconn)
			{
				throw new Exception("Could not connect to DB");
			}

			$myQuery="DELETE FROM tickets WHERE Id = ?";
			
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
 i: All INTEGER types
 d: The DOUBLE and FLOAT types
 b: The BLOB types
 s: All other types (including strings)
 * <<Bind_Param Types<<
 *
 *
 */

?>