<?php 
session_start();



include 'loginchk.php';



include_once("MT/cEvents.php");



include_once("MT/cGlobali.php");
$Golobal=new cGlobali();

if($_GET['DelId'])
{
//	$Reminder_Del= "delete from reminder where Id=".$_GET['DelId'];
//	$Golobal->ExecuteQuery($Reminder_Del);
	
		
}	

if($_REQUEST[EventId]!="")
{
$con=" AND EventId='".$_REQUEST[EventId]."'";
}

$Selectreminder="SELECT * FROM reminder WHERE 1 AND StartDate > now() $con"; // using 6/8
$reminderArr=$Golobal->SelectQuery($Selectreminder);
		
$EventQuery = "SELECT Id, Title from events  where StartDt > now() order by Title "; 
		$EventQueryRES = $Golobal->SelectQuery($EventQuery);
	
	
	


include "templates/eventreminder_tpl.php";

?>