<?php
include_once("cGlobali.php");
class cEventCustomFields
{
	public $Id = 0;
	public $EventId = 0;
	public $EventCustomFieldName = "";
	public $EventCustomFieldType = "";
	public $EventCustomFieldMandatory = 0;
	public $EventCustomFieldGuest = 1;
	public $EventCustomFieldDefaultValue = "";
	public $EventCustomFieldSeqNo = "";
	public $displayOnTicket = 0;
	

//----------------------------------------------------------------------------------------------------

	public function __construct($lId, $sEventId, $sEventCustomFieldName, $sEventCustomFieldType, $sEventCustomFieldMandatory, $sEventCustomFieldGuest, $sEventCustomFieldDefaultValue, $sEventCustomFieldSeqNo,$displayOnTicket)
	{
		$this->Id = $lId;
		$this->EventId = $sEventId;
		$this->EventCustomFieldName = $sEventCustomFieldName;
		$this->EventCustomFieldType = $sEventCustomFieldType;
		$this->EventCustomFieldMandatory = $sEventCustomFieldMandatory;
		$this->EventCustomFieldGuest = $sEventCustomFieldGuest;
		$this->EventCustomFieldDefaultValue = $sEventCustomFieldDefaultValue;
		$this->EventCustomFieldSeqNo = $sEventCustomFieldSeqNo;
		$this->displayOnTicket = $displayOnTicket;
	}
	
//----------------------------------------------------------------------------------------------------
	
	public function Save($ticket_id =NULL)
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
				$myQuery="UPDATE eventcustomfields SET EventId = ?, EventCustomFieldName=?, EventCustomFieldType=?, EventCustomFieldMandatory=?, EventCustomFieldGuest=?, EventCustomFieldDefaultValue=?, EventCustomFieldSeqNo=?, displayOnTicket=? WHERE Id = ?";
			}
			else	//New Record: Insert
			{
			 	$myQuery="INSERT INTO eventcustomfields (`EventId`,`EventCustomFieldName`,`EventCustomFieldType`,`EventCustomFieldMandatory`,`EventCustomFieldGuest`,`EventCustomFieldDefaultValue`,`EventCustomFieldSeqNo`,`displayOnTicket`";
                //Custom ticket level field is adding
                if(strlen($ticket_id)>0)
                {
                    $myQuery.=",`CustomfieldLevel`) values (?,?,?,?,?,?,?,?,?) ";
                }else{
                    $myQuery.=") values (?,?,?,?,?,?,?,?) ";
			}
			}
			
			$stmt = $Globali->dbconn->stmt_init();    // Create a statement object
				
			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);

			
			if ($EditedRecord)
			{
				$Success = $stmt->bind_param("issssssii", $this->EventId, $this->EventCustomFieldName, $this->EventCustomFieldType, $this->EventCustomFieldMandatory, $this->EventCustomFieldGuest, $this->EventCustomFieldDefaultValue, $this->EventCustomFieldSeqNo, $this->displayOnTicket, $this->Id);    // Bind the parameters
				
			}
			else
			{
                //ticket level custom field is adding
                if(strlen($ticket_id)>0)
                {$level='ticket_level';
                    $Success = $stmt->bind_param("isiiisiis", $this->EventId,$this->EventCustomFieldName, $this->EventCustomFieldType, $this->EventCustomFieldMandatory, $this->EventCustomFieldGuest, $this->EventCustomFieldDefaultValue, $this->EventCustomFieldSeqNo,$this->displayOnTicket,$level);    // Bind the parameters
                    
                }else{
				$Success = $stmt->bind_param("isiiisii", $this->EventId,$this->EventCustomFieldName, $this->EventCustomFieldType, $this->EventCustomFieldMandatory, $this->EventCustomFieldGuest, $this->EventCustomFieldDefaultValue, $this->EventCustomFieldSeqNo,$this->displayOnTicket);    // Bind the parameters
			}
			
			}
			
			if (! $Success) throw new Exception("Parameters couldn't be bound. Error: " . $Globali->dbconn->error);

			
			$Success = $stmt->execute();		//Execute Statement
			if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Globali->dbconn->error);
				
			$AffectedRows = $stmt->affected_rows;
			if ($AffectedRows > 0)
			{
				//echo $stmt->insert_id."<br>";
				if (! $EditedRecord)	$this->Id = $stmt->insert_id;
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

			$myQuery="SELECT Id, EventId, EventCustomFieldName, EventCustomFieldType, EventCustomFieldMandatory, EventCustomFieldGuest, EventCustomFieldDefaultValue, EventCustomFieldSeqNo,displayOnTicket FROM eventcustomfields WHERE Id = ?";
			
			$stmt = $Globali->dbconn->stmt_init();    // Create a statement object
				
			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);
				
			$Success = $stmt->bind_param("i", $this->Id);    // Bind the parameters
			if (! $Success) throw new Exception("Parameters couldn't be bound. Error: " . $Globali->dbconn->error);
				
			$Success = $stmt->execute();		//Execute Statement
			if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Globali->dbconn->error);

			$Success = $stmt->bind_result($this->Id, $this->EventId, $this->EventCustomFieldName, $this->EventCustomFieldType, $this->EventCustomFieldMandatory, $this->EventCustomFieldGuest, $this->EventCustomFieldDefaultValue, $this->EventCustomFieldSeqNo, $this->displayOnTicket);	// Bind the result parameters
			
			
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

			$myQuery="DELETE FROM eventcustomfields WHERE Id = ?";
			
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

    public function ticket_cutom_field_entry($EventCustomFieldsId, $ticket_id) {
        $Success = FALSE;
        try {
            $Globali = new cGlobali();
            if ($Globali->dbconn->errno > 0) {
                throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
            }
	
            if (!$Globali->dbconn) {
                throw new Exception("Could not connect to DB");
            }

            $myQuery = "INSERT INTO  event_ticket_customfields (`eve_tic_ticket_id` ,
                       `eve_tic_eventcustomfield_id`) values (?,?)";
            $stmt = $Globali->dbconn->stmt_init();    // Create a statement object

            $Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
            if (!$Success)
                throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);
            $Success = $stmt->bind_param("ii", $ticket_id, $EventCustomFieldsId);

            if (!$Success)
                throw new Exception("Parameters couldn't be bound. Error: " . $Globali->dbconn->error);
            $Success = $stmt->execute();  //Execute Statement
            if (!$Success)
                throw new Exception("Statement couldn't be Executed. Error: " . $Globali->dbconn->error);

            $Globali->dbconn->close();
            $stmt->close();
        } catch (Exception $Ex) {
            throw $Ex;
        }
    }

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