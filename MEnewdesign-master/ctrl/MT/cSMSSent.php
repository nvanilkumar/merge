<?php
include_once("cGlobal.php");

class cSMSSent
{
	private $TableNm = "SMSSent";

	public $Id = 0;
	public $UserId = '';
	public $SMSMsgId = '';
	public $SentDt = '';

	//----------------------------------------------------------------------------------------------------

	public function __construct($lId, $sUserId, $sSMSMsgId, $sSentDt)
	{
		$this->Id 		=  $lId;
		$this->UserId 	=  $sUserId;
		$this->SMSMsgId	=  $sSMSMsgId;
		$this->SentDt	=  $sSentDt;
	}

	//----------------------------------------------------------------------------------------------------

	public function Save()
	{
		$Success = FALSE;

		try
		{
			$Global=new cGlobal();
			$Conn = new mysqli($Global->DBServerNameOnly, $Global->DBUserName,$Global->DBPassword,$Global->DBIniCatalog,$Global->portNumber);
			//$Conn->connect();


			if ($Conn->errno > 0)
			{
				throw new Exception("Could not connect to DB. Error: " . $Conn->error);
			}

			if (!$Conn)
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

			$myQuery = " UserId = ?, SMSMsgId = ?, SentDt = ? ";
				
			if ($EditedRecord)
			{
				$myQuery = "UPDATE " . $this->TableNm . " SET " . $myQuery . " WHERE Id = ?";
			}
			else	//New Record: Insert
			{
			echo	$myQuery = "INSERT INTO " . $this->TableNm . " SET " . $myQuery;
			}

			$stmt = $Conn->stmt_init();    // Create a statement object

			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Conn->error);


			if ($EditedRecord)
			{
				$Success = $stmt->bind_param("iisi", $this->UserId, $this->SMSMsgId, $this->SentDt, $this->Id);    // Bind the parameters
			}
			else
			{
				$Success = $stmt->bind_param("iis", $this->UserId, $this->SMSMsgId, $this->SentDt);
		  // Bind the parameters
			}

			if (! $Success) throw new Exception("Parameters couldn't be bound. Error: " . $Conn->error);

			$Success = $stmt->execute();		//Execute Statement
			if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Conn->error);

			$AffectedRows = $stmt->affected_rows;
			if ($AffectedRows > 0)
			{
				if (! $EditedRecord)	$this->Id = $Conn->insert_id;
			}
			else
			{
				$this->Id = 0;
			}

			$Conn->close();
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
			$Global=new cGlobal();
			$Conn = new mysqli($Global->DBServerNameOnly, $Global->DBUserName,$Global->DBPassword,$Global->DBIniCatalog,$Global->portNumber);
			//$Conn->connect();


			if ($Conn->errno > 0)
			{
				throw new Exception("Could not connect to DB. Error: " . $Conn->error);
			}

			if (!$Conn)
			{
				throw new Exception("Could not connect to DB");
			}

			//echo $this->Id;

			$myQuery="SELECT Id, UserId, SMSMsgId, SentDt FROM ".$this->TableNm." WHERE Id = ?";
			$stmt = $Conn->stmt_init();    // Create a statement object

			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Conn->error);

			$Success = $stmt->bind_param("i", $this->Id);    // Bind the parameters
			if (! $Success) throw new Exception("Parameters couldn't be bound. Error: " . $Conn->error);

			$Success = $stmt->execute();		//Execute Statement
			if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Conn->error);

			$Success = $stmt->bind_result($this->Id, $this->UserId, $this->SMSMsgId, $this->SentDt);	// Bind the result parameters

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
			$Global=new cGlobal();
			$Conn = new mysqli($Global->DBServerNameOnly, $Global->DBUserName,$Global->DBPassword,$Global->DBIniCatalog,$Global->portNumber);
			//$Conn->connect();


			if ($Conn->errno > 0)
			{
				throw new Exception("Could not connect to DB. Error: " . $Conn->error);
			}

			if (!$Conn)
			{
				throw new Exception("Could not connect to DB");
			}

			$myQuery="DELETE FROM " . $this->TableNm . " WHERE Id = ?";

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

			$Conn->close();
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
*/
?>