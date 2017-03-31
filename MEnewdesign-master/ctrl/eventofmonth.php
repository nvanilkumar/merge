<?php
include_once("includes/application_top.php");
include('includes/functions.php');

$task = $_GET['task'];
$nid = $_GET['nid'];


///// Check if the nid in this table exists or has been deleted, if its deleted from the NODE table, if so delete it from the this table as well.
/**************************commented on 17082009 need to remove afterwords**************************
$sql_check = "SELECT nid FROM node WHERE nid=".$nid;
$sql_check = mysql_query($sql_check) or die("Error in check :".mysql_error());
$num = mysql_num_rows($sql_check);

if($num == 0){
	$task = "delete";
}
/////////////////////////////////////////////////////////////////////////////////


if($task == "delete"){
	$sql = "DELETE FROM events_of_month WHERE nid=".$nid;
}else{
	$sql = "INSERT INTO events_of_month SET nid=".$nid;
}

mysql_query($sql) or die("Error in query ".mysql_error());
***************************************************/
header("location:events_of_month.php");
?>
