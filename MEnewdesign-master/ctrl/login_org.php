<?php
	
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
		
		$EDt = $_REQUEST['txtEDt'];
		$EDtExplode = explode("/", $EDt);
		$EDtYMD = $EDtExplode[2].'-'.$EDtExplode[1].'-'.$EDtExplode[0].' 23:59:59';
                $SDtYMD =$common->convertTime($SDtYMD, DEFAULT_TIMEZONE);
                $EDtYMD =$common->convertTime($EDtYMD, DEFAULT_TIMEZONE);
		
		$EventsQuery = "SELECT id, title, startdatetime, enddatetime, popularity, ownerid FROM event WHERE deleted=0 and status='1' AND (startdatetime >= '".$SDtYMD."' AND enddatetime <='".$EDtYMD."') ORDER BY startdatetime, title ASC";
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
		
		$EventsQuery = "SELECT id, title, startdatetime, enddatetime, popularity, ownerid FROM event WHERE deleted=0 and status='1' AND (startdatetime >= '".$SDtYMD."' AND enddatetime <='".$EDtYMD."') ORDER BY startdatetime, title ASC";
		$EventsOfMonth = $Global->SelectQuery($EventsQuery);
	}
	
	include 'templates/login_org_tpl.php';	
?>
