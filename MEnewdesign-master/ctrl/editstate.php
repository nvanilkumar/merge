<?php

session_start();

include_once("MT/cGlobali.php");

$Global = new cGlobali();
include 'loginchk.php';

if ($_POST['Submit'] == "Delete") {
    $delStates = $_POST['state'];

    foreach ($delStates as $Id) {
        include_once("MT/cStates.php");

        try {
            $State = new cStates($Id);
            $Id = $State->Delete();
            if ($Id > 0) {
                //delete successful statement
            }
        } catch (Exception $Ex) {
            echo $Ex->getMessage();
        }
    }
}// END IF delete	
////////////////////////////////Query For States List////////////////////////////////
//Brings only active counties list
$countryQuery = "SELECT * FROM `country` WHERE `deleted` = 0 AND `status` = 1 ORDER BY `name` ASC";
$countryList = $Global->SelectQuery($countryQuery);
if (isset($_GET['country']) && $_GET['country'] != "" && $_GET['country'] != 0) {
    $countryId = $_GET['country'];    
}
else {
    $countryId = 14;//$countryList[0]['id'];
}
$countryName = getStateCountry($Global, $countryId );

//Brings only active states list
$stateQuery = "SELECT * FROM state WHERE deleted = 0 AND countryid=".$countryId." and status in (0,1) ORDER BY name ASC";
$StatesList = $Global->SelectQuery($stateQuery);
////////////////////////////////

function getStateCountry ($Global, $id) {
    $query = "SELECT name,id FROM `country` WHERE `deleted` = 0 AND `status` =1 AND `id` = ".$id." ORDER BY `name` ASC";
    $countryName = $Global->SelectQuery($query);
    if (count($countryName)>0) {
        return $countryName[0]['name'];
    }
}

include 'templates/editstate.tpl.php';
?>