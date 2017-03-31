<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Display Cancel Transactions
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
       if($_REQUEST[eventid]!="")
       {
       $id=" and id=".$_REQUEST[eventid];
       }
      if($_REQUEST[event]!="")
       {
       $Title=" and title LIKE '".$_REQUEST[event]."%'";
       }
       if($_REQUEST[eventid]=="" && $_REQUEST[event]=="")
{
$dt=" and startdatetime > now()";
}
   
	
//	$EventQuery = "SELECT * from events where  Published=1 $dt $id $Title order by StartDt ASC";  
            $EventQuery = "SELECT `id`, `title`, `startdatetime`, `cityid`, `ownerid` from event where  status=1 and deleted = 0 $dt $id $Title order by startdatetime ASC";  
		$EventQueryRES = $Global->SelectQuery($EventQuery);
		
		$Org="select u.username,u.id from user u,organizer o where u.id=o.userid order by u.cts DESC";
		$OrgRes= $Global->SelectQuery($Org);
		
	include 'templates/MoveEvents.tpl.php';	
?>
