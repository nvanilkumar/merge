
<?php
@session_start();    
include 'loginchk.php';
$uid =    $_SESSION['uid'];
include_once("MT/cGlobali.php");
include_once("includes/common_functions.php");
$Global = new cGlobali();
$functions=new functions();

//Get events which has delete requests
$deleteRequests=$Global->SelectQuery("SELECT id,title FROM event WHERE deleterequest=1 and deleted=0");

//Get events which are deleted
$deletedEvents=$Global->SelectQuery("SELECT id,title FROM event WHERE deleted=1");

include 'templates/delete_requests.tpl.php';
?>