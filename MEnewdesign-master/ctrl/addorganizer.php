<?php

session_start();
include_once("MT/cGlobali.php");
include_once("includes/common_functions.php");
$Global = new cGlobali();

//when converting normal user to organizer
if (isset($_POST['org'])) {
    $email = $_POST['emailId'];
    $selUserDetails = "SELECT id FROM user where email='" . $email . "'";
    $resUserDetails = $Global->SelectQuery($selUserDetails);
    if (count($resUserDetails) > 0) {
        $addOrganizer = "insert into organizer(userid) values('" . $resUserDetails[0]['id'] . "')";
        if ($Global->ExecuteQuery($addOrganizer)) {
            $_SESSION['addStatus'] = true;
        }
        header('Location:addorganizer.php');
        exit;
    }
}
include 'templates/addorganizer_tpl.php';
?>