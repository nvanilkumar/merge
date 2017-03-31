<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Display SMS Report
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is created on 05th Oct 2009
******************************************************************************************************************************************/
	
	session_start();
	$uid =	$_SESSION['uid'];
	
	include 'loginchk.php';

	include_once("MT/cGlobali.php");
	//include_once("MT/cSMSSent.php");
	
	$Global = new cGlobali();
	

	if($_REQUEST['submit'] == 'Show SMS Report')
	{
		$SDt = $_REQUEST['txtSDt'];
		$SDtExplode = explode("/", $SDt);
		$SDtYMD = $SDtExplode[2].'-'.$SDtExplode[1].'-'.$SDtExplode[0].' 00:00:00';
		
		$EDt = $_REQUEST['txtEDt'];
		$EDtExplode = explode("/", $EDt);
		$EDtYMD = $EDtExplode[2].'-'.$EDtExplode[1].'-'.$EDtExplode[0].' 23:59:59';
		
		$SMSReportQuery = "SELECT sms.id, sms.messageid, s.type, sms.cts, u.name,u.mobile FROM sentmessage AS sms, messagetemplate AS s, user AS u WHERE sms.cts >= '".$SDtYMD."' AND sms.messageid=s.id AND sms.cts <='".$EDtYMD."' AND u.id = sms.userid and s.mode = 'sms' ORDER BY sms.cts, u.name ASC";
		$SMSReport = $Global->SelectQuery($SMSReportQuery);
	}

	include 'templates/smsReport.tpl.php';	
?>