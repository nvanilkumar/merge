<?php

include_once("cGlobali.php");

class cStates {

    public $Id = 0;
    public $State = "";
    public $CountryId = 0;
    public $stateStatus=1;

//----------------------------------------------------------------------------------------------------

    public function __construct($lId, $sState, $sCountryId, $status=1) {
        $this->Id = $lId;
        $this->State = $sState;
        $this->CountryId = $sCountryId;
	$this->stateStatus=$status;
    }

//----------------------------------------------------------------------------------------------------

    public function Save() {
        $Success = FALSE;

        try {
            $Globali = new cGlobali();

            if ($Globali->dbconn->errno > 0) {
                throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
            }

            if (!$Globali->dbconn) {
                throw new Exception("Could not connect to DB");
            }

            if ($this->Id > 0) {  //Edited Record: Update
                $EditedRecord = TRUE;
            } else {
                $EditedRecord = FALSE;
            }

            if ($EditedRecord) {
                $myQuery = "UPDATE `state` SET `name` = ?, `countryid` = ?, `status` = ?, `modifiedby`=? WHERE `id` = ?";
            } else { //New Record: Insert
                echo $myQuery = "INSERT INTO `state` (`name`, `countryid`, `status`, `createdby`) VALUES (?,?,?,?)";
            }

            $stmt = $Globali->dbconn->stmt_init();    // Create a statement object

            $Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
            if (!$Success)
                throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);


            if ($EditedRecord) {
                $Success = $stmt->bind_param("siiii", $this->State, $this->CountryId, $this->stateStatus, $_SESSION['uid'], $this->Id);    // Bind the parameters
            } else {
                $Success = $stmt->bind_param("siii", $this->State, $this->CountryId, $this->stateStatus,  $_SESSION['uid']);    // Bind the parameters
            }

            if (!$Success) {
                throw new Exception("Parameters couldn't be bound. Error: " . $Globali->dbconn->error);
            }

            $Success = $stmt->execute();  //Execute Statement
            if (!$Success) {
                throw new Exception("Statement couldn't be Executed. Error: " . $Globali->dbconn->error);
            }
           /* echo $stmt->mysqli_error;
            echo $stmt->info;
            print_r($stmt->get_warnings());
            print_r(mysqli_info($Globali->dbconn));
            echo $stmt->affected_rows;
            echo $this->Id = $Globali->dbconn->insert_id;
            exit;*/
            $AffectedRows = $stmt->affected_rows;
            if ($AffectedRows > 0) {
                if (!$EditedRecord)
                    $this->Id = $Globali->dbconn->insert_id;
            }
            else {
                $this->Id = 0;
            }

            $Globali->dbconn->close();
            $stmt->close();

            return $this->Id;
        } catch (Exception $Ex) {
            throw $Ex;
        }
    }

//Save
//---------------------------------------------------------------------------------------------------------------

    public function Load() {
        $Success = FALSE;

        try {
            $Globali = new cGlobali();


            if ($Globali->dbconn->errno > 0) {
                throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
            }

            if (!$Globali->dbconn) {
                throw new Exception("Could not connect to DB");
            }

            $myQuery = "SELECT Id, name, country FROM state WHERE id = ?";
            $stmt = $Globali->dbconn->stmt_init();    // Create a statement object

            $Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
            if (!$Success)
                throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);

            $Success = $stmt->bind_param("i", $this->Id);    // Bind the parameters
            if (!$Success)
                throw new Exception("Parameters couldn't be bound. Error: " . $Globali->dbconn->error);

            $Success = $stmt->execute();  //Execute Statement
            if (!$Success)
                throw new Exception("Statement couldn't be Executed. Error: " . $Globali->dbconn->error);

            $Success = $stmt->bind_result($this->Id, $this->State, $this->CountryId); // Bind the result parameters

            if ($stmt->fetch()) {  //Fetch values actually into bound fields of the result
                $Success = TRUE;
            } else {
                $Success = FALSE;
            }

            $Globali->dbconn->close();
            $stmt->close();

            return $Success;
        } catch (Exception $Ex) {
            throw $Ex;
        }
    }

//----------------------------------------------------------------------------------------------------

    public function Delete() {
        $Success = FALSE;
        $deleted = 1;
        try {
            $Globali = new cGlobali();

            if ($Globali->dbconn->errno > 0) {
                throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
            }

            if (!$Globali->dbconn) {
                throw new Exception("Could not connect to DB");
            }

            $myQuery = "UPDATE `state` SET `deleted` = ?, `modifiedby` = ?  WHERE `id` = ?";

            $stmt = $Globali->dbconn->stmt_init();    // Create a statement object

            $Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
            if (!$Success)
                throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);


            $Success = $stmt->bind_param("iii", $deleted, $_SESSION['uid'], $this->Id);    // Bind the parameters
            if (!$Success)
                throw new Exception("Parameters couldn't be bound. Error: " . $Globali->dbconn->error);


            $Success = $stmt->execute();  //Execute Statement
            if (!$Success)
                throw new Exception("Statement couldn't be Executed. Error: " . $Globali->dbconn->error);

            $AffectedRows = $stmt->affected_rows;
            if ($AffectedRows > 0) {
                $Success = TRUE;
            } else {
                $Success = FALSE;
            }

            $Globali->dbconn->close();
            $stmt->close();

            return $Success;
        } catch (Exception $Ex) {
            throw $Ex;
        }
    }

//Save
//---------------------------------------------------------------------------------------------------------------
}

//Class


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