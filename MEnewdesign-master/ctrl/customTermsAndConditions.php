<?php

@session_start();

include 'loginchk.php';

$uid =    $_SESSION['uid'];
include_once("MT/cGlobali.php");
$Global = new cGlobali();
include_once("includes/common_functions.php");
    
include_once('includes/functions.php');
	

$functions=new functions();

$eventid = NULL;
$eventdata = array();
$edidSEOdata = array();
if (isset($_REQUEST['eventid']) && $_REQUEST['eventid'] > 0) {


    $eventid = $_REQUEST['eventid'];
    $eventsql = "select `startdatetime` AS StartDt, `enddatetime` AS EndDt, `title` AS Title, `url` AS URL "
            . "from `event` where  deleted = 0 and `id`='" . $eventid . "'";

    $eventdata = $Global->SelectQuery($eventsql);

    if (count($eventdata) < 1) {
        header("Location: customTermsAndConditions.php");
    }
}


if (isset($_GET['edit']) && empty($_POST['addCustomTermsAndConditions'])) {

    $sqlEditTC = "SELECT `eventid` AS EventId, `organizertnc` AS Org_Description, "
            . "`meraeventstnc` AS ME_Description, `tnctype` AS showTC"
            . " FROM `eventdetail` where `eventid`='" . $_GET['edit'] . "'";
    $editTCdata = $Global->SelectQuery($sqlEditTC);
     
}

if (isset($_POST['addCustomTermsAndConditions'])) {
    $editeventid = $_POST['editid'];
    //$eventid1 = $_POST['editid'];
    $termsandconditions = $_POST['termsandconditions'];

    //print_r($_POST);

    $datatime = date('Y-m-d H:i:s', time());

    $eventid = $_POST['addTypeValue'];
    $status = $_POST['showTC'];

  $query="update eventdetail set meraeventstnc='".$termsandconditions."',tnctype='".$status."' where eventid=".$editeventid;
 
    if ($Global->ExecuteQuery($query)) {
        header("Location: customTermsAndConditions.php");
    }
}

// pagination script starts here
$page = 1; //Default page
$limit = 20; //Records per page
$start = 0; //starts displaying records from 0
if (isset($_GET['page']) && $_GET['page'] != '') {
    $page = $_GET['page'];
}
$start = ($page - 1) * $limit;



//Get total records (you can also use MySQL COUNT function to count records)
$query = $Global->SelectQuery("select eventid from eventdetail WHERE (organizertnc != '' OR meraeventstnc != '') ");
$rows = count($query);

$sqlTC = "SELECT `eventid` AS EventId, `organizertnc` AS Org_Description, "
        . "`meraeventstnc` AS ME_Description, `tnctype` AS showTC "
        . "FROM `eventdetail` "
        . "WHERE (organizertnc != '' OR meraeventstnc != '') "
        . "ORDER BY eventid DESC "
        . "LIMIT " . $start . ", " . $limit . " ";
$dataTC = $Global->SelectQuery($sqlTC);

//print_r($seotypesdata);

include 'templates/customTermsAndConditions.tpl.php';
?>