<?php
error_reporting(-1);
session_start();
include 'loginchk.php';
include_once("MT/cGlobali.php");
include_once("includes/common_functions.php");
$Global = new cGlobali();
$eventId=$_REQUEST['eventid'];
if (isset($_POST['promotionalText'])) {
//    echo 'coming here';
//    echo 'eventid na';echo $eventId;
    $promotionalText = $_POST['promotionalText'];//print_r($promotionalText);exit;
    $insertId = $Global->ExecuteQueryId("UPDATE eventdetail SET promotionaltext='".$promotionalText."'"."WHERE eventid=".$eventId);
    header('Location: addpromotionaltext.php?editID=' . $EventId);
}
if (isset($_REQUEST['eventid']) && $_REQUEST['eventid'] > 0) {
    $eventid = $_REQUEST['eventid'];
    $promotionsRes = $Global->SelectQuery("SELECT promotionaltext FROM eventdetail WHERE eventid=".$_REQUEST['eventid']);

}
include 'templates/addpromotionaltext_tpl.php';