<?php
include_once("cGlobali.php");
class cEventCustomFieldsMultipleValue
{
	public $Id = 0;
	public $EventCustomFieldId = 0;
	public $EventCustomFieldMultipleValues = "";
	

//----------------------------------------------------------------------------------------------------

	public function __construct($lId, $lEventCustomFieldId, $sEventCustomFieldMultipleValues )
	{
		$this->Id = $lId;
		$this->EventCustomFieldId = $lEventCustomFieldId;
		$this->EventCustomFieldMultipleValues = $sEventCustomFieldMultipleValues;
		
	}
	
//----------------------------------------------------------------------------------------------------
	
	public function Save()
	{
		$Success = FALSE;

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
				$myQuery="UPDATE eventcustomfieldsmultiplevalue SET EventCustomFieldId = ?, EventCustomFieldMultipleValues = ? WHERE Id = ?";
			}
			else	//New Record: Insert
			{
				$myQuery="INSERT INTO eventcustomfieldsmultiplevalue  SET EventCustomFieldId = ? ,EventCustomFieldMultipleValues = ?";
			}
			
			$stmt = $Conn->stmt_init();    // Create a statement object
				
			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Conn->error);

			
			if ($EditedRecord)
			{
				$Success = $stmt->bind_param("isi", $this->EventCustomFieldId, $this->EventCustomFieldMultipleValues, $this->Id);    // Bind the parameters
			}
			else
			{
				$Success = $stmt->bind_param("is", $this->EventCustomFieldId, $this->EventCustomFieldMultipleValues);    // Bind the parameters
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

	public function SaveTrans($Conn)
	{
		$Success = FALSE;

		try
		{
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
				$myQuery="UPDATE eventcustomfieldsmultiplevalue SET EventCustomFieldId = ?, EventCustomFieldMultipleValues = ? WHERE Id = ?";
			}
			else	//New Record: Insert
			{
				$myQuery="INSERT INTO eventcustomfieldsmultiplevalue  SET EventCustomFieldId = ? ,EventCustomFieldMultipleValues = ?";
			}
			
			$stmt = $Conn->stmt_init();    // Create a statement object
				
			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Conn->error);

			
			if ($EditedRecord)
			{
				$Success = $stmt->bind_param("isi", $this->EventCustomFieldId, $this->EventCustomFieldMultipleValues, $this->Id);    // Bind the parameters
			}
			else
			{
				$Success = $stmt->bind_param("is", $this->EventCustomFieldId, $this->EventCustomFieldMultipleValues);    // Bind the parameters
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

			$myQuery="SELECT Id, EventCustomFieldId, EventCustomFieldMultipleValues  FROM eventcustomfieldsmultiplevalue WHERE Id = ?";
			$stmt = $Conn->stmt_init();    // Create a statement object
				
			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Conn->error);
				
			$Success = $stmt->bind_param("i", $this->Id);    // Bind the parameters
			if (! $Success) throw new  Exception("Parameters couldn't be bound. Error: " . $Conn->error);
				
			$Success = $stmt->execute();		//Execute Statement
			if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Conn->error);

			$Success = $stmt->bind_result($this->Id, $this->EventCustomFieldId, $this->EventCustomFieldMultipleValues);	// Bind the result parameters
			
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

			$myQuery="DELETE FROM eventcustomfieldsmultiplevalue WHERE Id = ?";
			
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
	}		//Save

//---------------------------------------------------------------------------------------------------------------

	//Method written by Manoj: 28Aug2009
	public function DeleteAll($lEventCustomFieldId, $Conn)			//Delete all records for this EventCustomFieldId
	{
		$Success = FALSE;

		try
		{
			$myQuery = "DELETE FROM eventcustomfieldsmultiplevalue WHERE EventCustomFieldId = ?";
			
			$stmt = $Conn->stmt_init();    // Create a statement object
				
			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Conn->error);

			
			$Success = $stmt->bind_param("i", $lEventCustomFieldId);    // Bind the parameters
			if (! $Success) throw new Exception("Parameters couldn't be bound. Error: " . $Conn->error);

			
			$Success = $stmt->execute();		//Execute Statement
			if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Conn->error);
				
			$AffectedRows = $stmt->affected_rows;
			
			if ($Success)
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
	}		//Save

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