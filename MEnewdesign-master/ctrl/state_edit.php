<?php

session_start();
include 'loginchk.php';

include_once("MT/cGlobali.php");
include_once("MT/cStates.php");

$Global = new cGlobali();


$check_duplicate_status = FALSE;

//To check the duplicate state value is updating or not
if (isset($_POST['Submit'])) {
    $state_name = $_POST['state'];
    $Id = $_POST['state_id'];
    $country_id = $_POST['country'];
    $stateStatus = $_POST['state_status'];
    $check_duplicate_status = check_duplicate_state($Global, $state_name, $Id, $country_id);
    $error_message = ($check_duplicate_status == TRUE) ? "You are trying to inserting the duplicate value" : "";
}
if ($_POST['Submit'] == "Update" && !$check_duplicate_status) {
    try {
        $States = new cStates($Id, $state_name, $country_id, $stateStatus);
        
        $Id = $States->Save();
        if (isset($_GET["type"])) {
            header("location: auto_suggest_review.php?&update_record=" . $_GET["type"]);
            exit;
        }

        if ($Id > 0) {
            header("location:editstate.php");
        }
    } catch (Exception $Ex) {
        echo $Ex->getMessage();
    }
}// END IF update
$Id = $_GET['id'];
////////////////////////////////Query For State Details////////////////////////////////
$StatesQuery = "SELECT * FROM state WHERE id = '" . $Id . "'";
$StatesList = $Global->SelectQuery($StatesQuery);
////////////////////////////////
////////////////////////////////Query For Country List////////////////////////////////
$CountryQuery = "SELECT * FROM country WHERE deleted = 0 AND status =1 ORDER BY name ASC";
$CountryList = $Global->SelectQuery($CountryQuery);

////////////////////////////////


function check_duplicate_state($global, $compare_string, $ignore_id, $country_id) {
    $query = "SELECT id FROM state WHERE  `name`= '" . $compare_string . "' AND id!=" . $ignore_id . " AND `status`=1 AND countryid=".$country_id." AND deleted = 0";
    $record_list = $global->SelectQuery($query);
    if (count($record_list) > 0) {
        return TRUE;
    }
    return FALSE;
}

include 'templates/state_edit.tpl.php';
?>