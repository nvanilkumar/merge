<?php
include("commondbdetails.php");
/* * ********************************************************************************************** 
 * 	Page Details : Cron Job Transactions happen yesterday and Send Mail
 * Cron file to update the event ticket sold out status related to ticket avilable qty
 * 	Created / Last Updation Details : 
 * 	1.	Created on 31st Dec 2015 : Created by MAK
 * ********************************************************************************************** */
include("../ctrl/MT/cGlobali.php");
$cGlobali = new cGlobali();
include_once '../ctrl/includes/common_functions.php';
$commonFunctions = new functions();
$_GET = $commonFunctions->stripData($_GET, 1);
$_POST = $commonFunctions->stripData($_POST, 1);
$_REQUEST = $commonFunctions->stripData($_REQUEST, 1);



if ($_GET['runNow'] == 1) {
	$ownerid = $_GET['ownerid'];
 
    //Brings the all the events which have added new ticktes or increased ticket qty values by organizer
   $ticketAvilableEventsList = "select id,status from event where ownerid = ".$ownerid." and status = 1";
    $resTicketAvilableEventsList = $cGlobali->justSelectQuery($ticketAvilableEventsList);
    //count > 0 then need to do below things
//    echo "<br/> @@@@##".
     $ticketAvilableEventCount = mysqli_num_rows($resTicketAvilableEventsList);
    if ($ticketAvilableEventCount > 0) {
        while ($row = $resTicketAvilableEventsList->fetch_assoc()) {
            $solrUpdateData = array();
            $solrUpdateData['eventId'] = $row['id'];
            $output = $commonFunctions->changeEventStatus($solrUpdateData);
            $jsonOutput = json_decode($output, true);
            if ($jsonOutput['response']['updatedTicketSoldout'] == 'Success') {
                echo 'Successfully Updated Status for eventid ' . $row['id'];
            } else {
                echo 'Something went wrong in updating solr, please try again for ' . $row['id'];
            }
        }
    }



}
?>
