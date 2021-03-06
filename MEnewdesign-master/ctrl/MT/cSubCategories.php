<?php
include_once("cGlobali.php");
class cSubCategories
{
	public $Id = 0;
	public $CatId = 0;
	public $SubCatName = "";
	public $SubCatValue = "";

//----------------------------------------------------------------------------------------------------

	public function __construct($lId, $sCid, $sCategory, $sCategoryVal,$scStatus=1)
	{
		$this->Id = $lId;
		$this->CatId = $sCid;
		$this->SubCatName = $sCategory;
                $this->SubCatStatus = $scStatus;
                $this->SubCatValue = $sCategoryVal;
		
	}
	
//----------------------------------------------------------------------------------------------------
	
	public function Save()
	{
		$Success = FALSE;

		try
		{
			$Global=new cGlobali();
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
			ELSE
			{
				$EditedRecord = FALSE;
			}
			
			if ($EditedRecord)
			{
				$myQuery="UPDATE subcategory SET `name` = ?, `value` = ?, `categoryid` = ?, `status` = ?, `modifiedby` = ? WHERE `id` = ? ";
			}
			else	//New Record: Insert
			{
				$myQuery="INSERT INTO subcategory (`name`, `value`, `categoryid`, `createdby`) VALUES (?,?,?,?)";
			}
			
			$stmt = $Conn->stmt_init();    // Create a statement object
				
			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Conn->error);

			
			if ($EditedRecord)
			{ 
				$Success = $stmt->bind_param("ssiiii", $this->SubCatName, $this->SubCatValue, $this->CatId, $this->SubCatStatus, $_SESSION['uid'], $this->Id);    // Bind the parameters
			}
			else
			{
				$Success = $stmt->bind_param("ssii", $this->SubCatName, $this->SubCatValue, $this->CatId, $_SESSION['uid']);    // Bind the parameters
			}
			
			if (! $Success) throw new Exception("Parameters couldn't be bound. Error: " . $Conn->error ."11");

			
			$Success = $stmt->execute();		//Execute Statement
			if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Conn->error);
			/*echo $stmt->mysqli_error;
                        echo $stmt->info;
                        print_r($stmt->get_warnings());
                        print_r(mysqli_info($Conn));
                        echo $stmt->affected_rows;
                        echo $this->Id = $Conn->insert_id;
                        exit;	*/
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
			$Global=new cGlobali();
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

			$myQuery="SELECT Id,CatId, SubCatName  FROM subcategory WHERE Id = ?";
			$stmt = $Conn->stmt_init();    // Create a statement object
				
			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Conn->error);
				
			$Success = $stmt->bind_param("i", $this->Id);    // Bind the parameters
			if (! $Success) throw new Exception("Parameters couldn't be bound. Error: " . $Conn->error);
				
			$Success = $stmt->execute();		//Execute Statement
			if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Conn->error);

			$Success = $stmt->bind_result($this->Id, $this->CatId, $this->SubCatName);	// Bind the result parameters
			
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
                $deleted = 1;
		try
		{
			$Global=new cGlobali();
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

			$myQuery="UPDATE subcategory SET `deleted` = ?, `modifiedby` = ?  WHERE Id = ?";
			
			$stmt = $Conn->stmt_init();    // Create a statement object
				
			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Conn->error);

			
			$Success = $stmt->bind_param("iii", $deleted, $_SESSION['uid'], $this->Id);    // Bind the parameters
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