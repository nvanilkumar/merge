<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Display Events of the Month
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 26th Aug 2009
 *	2.	Added the new filed IsFamous in db which is used to display the Famous Events on the front end.
 * 		The check box property checked shows the event is famous, visible on front end and vice versa.
******************************************************************************************************************************************/
	
	session_start();
	$uid =	$_SESSION['uid'];
	
	include 'loginchk.php';
	
	
	
		
include_once("MT/cGlobali.php");
include_once("MT/cEvents.php");	
		$Global = new cGlobali();
	
		$VenueSeatsId = $_REQUEST['Id'];
		$Seatno = $_REQUEST['Seatno'];
		$Price = $_REQUEST['Price'];
		$Type = $_REQUEST['Type'];
		$Status = $_REQUEST['Status'];
	
		$update_query1="UPDATE venueseat SET Seatno='".$Seatno."',Price='".$Price."',Type='".$Type."',Status='".$Status."' WHERE Id='".$VenueSeatsId."'";  
		$Global->ExecuteQuery($update_query1);
		   
	
	
?>

