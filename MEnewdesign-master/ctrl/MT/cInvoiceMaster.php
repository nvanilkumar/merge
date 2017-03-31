<?php
include_once("cGlobali.php");
class cInvoiceMaster
{
	public $Id = 0;
	public $UserId = 0;
	public $Company = "";
	public $Address = "";
	public $TnC = "";
	public $LeftAligned = 0;
	public $PaymentByChq = 0;
	public $PayatCounter = 0;
	public $PaymentByCard = 0;
	public $COD = 0;
	public $Paypal = 0;
	public $Mobikwik = 1;
	public $Paytm = 1;
    public $EventId = 0;
       
	

//----------------------------------------------------------------------------------------------------

	public function __construct($lId, $sUserId, $sCompany, $sAddress, $sTnC, $sLeftAligned, $sPaymentByChq, $sPayatCounter, $sPaymentByCard, $sCOD, $sPaypal,$sMobikwik, $sPaytm, $sEventId)
	{
		$this->Id = $lId;
		$this->UserId = $sUserId;
		$this->Company = $sCompany;
		$this->Address = $sAddress;
		$this->TnC = $sTnC;
		$this->LeftAligned = $sLeftAligned;
		$this->PaymentByChq = $sPaymentByChq;
		$this->PayatCounter = $sPayatCounter;
		$this->PaymentByCard = $sPaymentByCard;
		$this->COD = $sCOD;
		$this->Paypal = $sPaypal;
		$this->Mobikwik = $sMobikwik;
		$this->Paytm = $sPaytm;
		$this->EventId = $sEventId;
		
	}
	
//----------------------------------------------------------------------------------------------------
	
	public function Save()
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
				$myQuery="UPDATE InvoiceMaster SET UserId = ?, Company = ?, Address = ?, TnC = ? , LeftAligned = ?, PaymentByChq = ?,PayatCounter = ?,PaymentByCard = ?,COD = ?,Paypal = ?, Mobikwik=?, Paytm=?,  EventId = ? WHERE Id = ?";
			}
			else	//New Record: Insert
			{
				$myQuery="INSERT INTO InvoiceMaster SET UserId = ?, Company = ?, Address = ?, TnC = ? , LeftAligned = ?, PaymentByChq = ?,PayatCounter = ?,PaymentByCard = ?,COD = ?,Paypal = ?, Mobikwik=?, Paytm=?, EventId = ?";
			}
			$stmt = $Globali->dbconn->stmt_init();    // Create a statement object
				
			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);

			
			if ($EditedRecord)
			{
				$Success = $stmt->bind_param("isssiiiiiiiiii", $this->UserId, $this->Company,$this->Address ,$this->TnC,$this->LeftAligned, $this->PaymentByChq, $this->PayatCounter, $this->PaymentByCard, $this->COD, $this->Paypal,$this->Mobikwik, $this->Paytm, $this->EventId,  $this->Id);    // Bind the parameters
			}
			else
			{
				$Success = $stmt->bind_param("isssiiiiiiiii", $this->UserId, $this->Company, $this->Address, $this->TnC, $this->LeftAligned, $this->PaymentByChq, $this->PayatCounter, $this->PaymentByCard, $this->COD, $this->Paypal,$this->Mobikwik, $this->Paytm, $this->EventId);    // Bind the parameters
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

			$myQuery="SELECT Id, UserId, Company, Address, TnC, LeftAligned,PaymentByChq,PayatCounter,PaymentByCard,COD,Paypal,Mobikwik,Paytm,EventId FROM InvoiceMaster WHERE Id = ?";
			$stmt = $Globali->dbconn->stmt_init();    // Create a statement object
				
			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);
				
			$Success = $stmt->bind_param("i", $this->Id);    // Bind the parameters
			if (! $Success) throw new  Exception("Parameters couldn't be bound. Error: " . $Globali->dbconn->error);
				
			$Success = $stmt->execute();		//Execute Statement
			if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Globali->dbconn->error);

			$Success = $stmt->bind_result($this->Id, $this->UserId, $this->Company, $this->Address, $this->TnC, $this->LeftAligned, $this->PaymentByChq, $this->PayatCounter, $this->PaymentByCard, $this->COD, $this->Paypal,$this->Mobikwik, $this->Paytm, $this->EventId);	// Bind the result parameters
			
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
			
			
			if ($Globali->dbconn->errno > 0)
			{
				throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
			}

			if (!$Globali->dbconn)
			{
				throw new Exception("Could not connect to DB");
			}

			$myQuery="DELETE FROM InvoiceMaster WHERE Id = ?";
			
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




?>