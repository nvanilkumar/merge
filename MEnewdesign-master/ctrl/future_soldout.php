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
        include_once("includes/common_functions.php");
        $common=new functions();
	

	if($_REQUEST['submit'] == 'Show Events')
	{
		$SDt = $_REQUEST['txtSDt'];
		$SDtExplode = explode("/", $SDt);
		$SDtYMD = $SDtExplode[2].'-'.$SDtExplode[1].'-'.$SDtExplode[0].' 00:00:00';
                $SDtYMD =$common->convertTime($SDtYMD, DEFAULT_TIMEZONE);
		
		$EDt = $_REQUEST['txtEDt'];
		$EDtExplode = explode("/", $EDt);
		$EDtYMD = $EDtExplode[2].'-'.$EDtExplode[1].'-'.$EDtExplode[0].' 23:59:59';
                $EDtYMD =$common->convertTime($EDtYMD, DEFAULT_TIMEZONE);
		
		$EventsQuery = "SELECT e.id AS Id, e.title AS Title, e.startdatetime AS StartDt, "
                        . "e.enddatetime AS EndDt, e.ownerid AS UserID, es.qualitycheck AS eChecked "
                        . "FROM event e "
                        . "JOIN eventsetting es ON es.eventid=e.id "
                        . "WHERE  e.status='1' AND e.startdatetime between '".$SDtYMD."' "
                        . "AND '".$EDtYMD."' and e.ticketsoldout = 1 "
                        . "ORDER BY e.startdatetime, e.title ASC";
   		$EventsOfMonth = $Global->SelectQuery($EventsQuery);
	}
	else
	{
		$_REQUEST['txtSDt']=date('d/m/Y');
		$_REQUEST['txtEDt']="31/12/".date("Y");
		$SDt = $_REQUEST['txtSDt'];
		$SDtExplode = explode("/", $SDt);
		$SDtYMD = $SDtExplode[2].'-'.$SDtExplode[1].'-'.$SDtExplode[0].' 00:00:00';
                $SDtYMD =$common->convertTime($SDtYMD, DEFAULT_TIMEZONE);
		
		$EDt = $_REQUEST['txtEDt'];
		$EDtExplode = explode("/", $EDt);
		$EDtYMD = $EDtExplode[2].'-'.$EDtExplode[1].'-'.$EDtExplode[0].' 23:59:59';
                $EDtYMD =$common->convertTime($EDtYMD, DEFAULT_TIMEZONE);
		
		$EventsQuery = "SELECT e.Id, e.title AS Title,e.url As URL, e.startdatetime AS StartDt, "
                        . "e.enddatetime AS EndDt, e.ownerid AS UserID, es.qualitycheck AS eChecked "
                        . "FROM event e "
                        . "JOIN eventsetting es ON es.eventid=e.id "
                        . "WHERE  e.startdatetime between '".$SDtYMD."' AND '".$EDtYMD."'  "
                        . "AND e.status='1' AND e.ticketsoldout = 1 "
                        . "ORDER BY e.startdatetime, e.title ASC";
		$EventsOfMonth = $Global->SelectQuery($EventsQuery);
	}
	
	include 'templates/future_soldout.tpl.php';	
?>
