<?php

session_start();


include 'loginchk.php';

$uid = $_SESSION['uid'];

include_once("MT/cGlobali.php");
include_once("includes/common_functions.php");

$Global = new cGlobali();

$eventid = NULL;
if (isset($_GET['delete'])) {
    $Id = $_GET['delete'];
    $Global->ExecuteQuery("DELETE FROM promotions WHERE Id='" . $Id . "'");
}

$insertId = NULL;
if (isset($_POST['addPromotion'])) {
    $PId = isset($_GET['edit']) ? $_GET['edit'] : '';
    $EventId = $_POST['eventId'];
    $promotionMedium = $_POST['promotionMedium'];
    $promotionURL = $_POST['promotionURL'];
    $status = $_POST['status'];
    $comments = $_POST['comments'];
    $mts = date('Y-m-d H:i:s');
// echo  $t=;
    $insertId = $Global->ExecuteQueryId("INSERT INTO `promotions`(Id,EventId,promotionMedium,promotionURL,status,comments) VALUES('" . $PId . "','" . $EventId . "','" . $promotionMedium . "','" . $promotionURL . "','".$status."','".$comments."' ) ON DUPLICATE KEY UPDATE promotionMedium='" . $promotionMedium . "',promotionURL='" . $promotionURL . "',status='".$status."',comments='".$comments."',mts='" . $mts . "'");
    header('Location: addpromotions.php?eventid=' . $EventId);
}
$editPromo = array();
if (isset($_GET['edit'])) {
    $pId = $_GET['edit'];
    $editPromo = $Global->SelectQuery("SELECT p.promotionMedium,p.promotionURL,p.status,p.comments FROM promotions p WHERE p.Id='" . $pId . "'");
}
if (isset($_REQUEST['eventid']) && $_REQUEST['eventid'] > 0) {
    $eventid = $_REQUEST['eventid'];
    $promotionsRes = $Global->SelectQuery("SELECT e.Id as EventId,p.Id,`Title`,`URL`,p.promotionMedium,p.promotionURL,p.status,p.comments FROM events e LEFT OUTER JOIN promotions p ON e.Id=p.EventId WHERE e.Id='" . $eventid . "' ORDER BY p.Id DESC");
}

include 'templates/addpromotions_tpl.php';
?>
