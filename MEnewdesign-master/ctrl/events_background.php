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
	
	if($_REQUEST['Save']=="Save")
	{
		
	
		$PEventId = $_REQUEST['PEventId'];
		$Background = $_REQUEST['Background'];
	
		    $update_query1="UPDATE events SET EventBackground='".$Background."' WHERE Id='".$PEventId."'";  
		$Global->ExecuteQuery($update_query1);
		   
	
	}
	

	if($_REQUEST['submit'] == 'Show Events' || $_REQUEST['StartDt1']!="")
	{
		$SDt = $_REQUEST['txtSDt']; 
		$SDtExplode = explode("/", $SDt);
		$SDtYMD = $SDtExplode[2].'-'.$SDtExplode[1].'-'.$SDtExplode[0].' 00:00:00';
		
		$EDt = $_REQUEST['txtEDt'];
		$EDtExplode = explode("/", $EDt);
		$EDtYMD = $EDtExplode[2].'-'.$EDtExplode[1].'-'.$EDtExplode[0].' 23:59:59';
		
		$EventsQuery = "SELECT Id, Title, StartDt, EndDt,EventBackground FROM events WHERE 1 AND (StartDt >= '".$SDtYMD."' AND EndDt <='".$EDtYMD."') and Title!='' and EventBackground!='' ORDER BY StartDt, Title ASC";
   		$EventsOfMonth = $Global->SelectQuery($EventsQuery);
	}
	else
	{
		$SDt = $_REQUEST['txtSDt'];
		$SDtExplode = explode("/", $SDt);
		$SDtYMD = $SDtExplode[2].'-'.$SDtExplode[1].'-'.$SDtExplode[0].' 00:00:00';
		
		$EDt = $_REQUEST['txtEDt'];
		$EDtExplode = explode("/", $EDt);
		$EDtYMD = $EDtExplode[2].'-'.$EDtExplode[1].'-'.$EDtExplode[0].' 23:59:59';
		
$EventsQuery = "SELECT id AS Id, title AS Title, startdatetime AS StartDt, enddatetime As EndDt, EventBackground "
                . "FROM event "
                . "WHERE 1 AND (startdatetime >= '".$SDtYMD."' AND enddatetime <='".$EDtYMD."') "
                . "and title!='' and EventBackground!='' "
                . "ORDER BY startdatetime, title ASC";
		$EventsOfMonth = $Global->SelectQuery($EventsQuery);
	}
	
	include 'templates/events_background.tpl.php';	
?>

