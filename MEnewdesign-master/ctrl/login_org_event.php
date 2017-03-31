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
       // include_once 'MT/cAttendees.php';
	
	$Global = new cGlobali();
	

	if($_REQUEST['getEveDtls'] == 'Submit' || isset($_REQUEST['EventId']))
	{
	    $EventId=$_REQUEST['EventId'];
		$OrgQuery = "SELECT u.id, u.name, u.username, u.signupdate as cts FROM user u,event e WHERE u.id=e.ownerid and e.id='".$EventId."'";
   		$ResOrgQuery = $Global->SelectQuery($OrgQuery);
		
                $editEventQuery="select e.`id`,e.`title`,e.`ownerid`, e.`status`,e.`registrationtype`,e.`ticketsoldout`,es.`sendfeedbackemails`,es.calculationmode,ed.discountaftertax from event e ,eventsetting es,eventdetail ed where e.`id`=es.`eventid` and ed.eventid=e.id and ed.eventid=es.eventid and e.`id`=".$EventId ." and e.deleted=0";
                $editEventRes=$Global->SelectQuery($editEventQuery);
		
		
		
	}

	
	include 'templates/login_org_event_tpl.php';	
?>
