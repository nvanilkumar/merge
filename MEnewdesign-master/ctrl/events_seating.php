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
	
	$Global = new cGlobali();
  if($_REQUEST['Type']!="")
{	
   $type=" AND Type like'%".$_REQUEST['Type']."%'";
}else{
$type="";

}
	if($_REQUEST['EventId'] != "")
	{
//$EventsQuery = "SELECT * FROM VenueSeats WHERE 1 AND EventId=".$_REQUEST['EventId']." $type ORDER BY Id ASC";
            $EventsQuery = "SELECT `Id`, `EventId`, `GridPosition`, `Seatno`, `Price`,`Type`, `Status` FROM venueseat WHERE 1 and deleted=0 AND EventId=".$_REQUEST['EventId']." $type ORDER BY Id ASC";
		$EventsOfMonth = $Global->SelectQuery($EventsQuery);
$Events1 = "SELECT count(Id) as ct  FROM `venueseat` WHERE `GridPosition` LIKE '%A%' and EventId=".$_REQUEST['EventId']." and deleted=0 GROUP BY  Price ";
	$EventsCtn = $Global->SelectQuery($Events1);

	}
	 
	include 'templates/events_seating.tpl.php';	
?>

