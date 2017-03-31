<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Display EMail Report
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is created on 05th Oct 2009
******************************************************************************************************************************************/
	
	session_start();
	$uid =	$_SESSION['uid'];
	
	include 'loginchk.php';

	include_once("MT/cGlobali.php");
	//include_once("MT/cEMailSent.php");
	
	$Global = new cGlobali();
	

	if($_REQUEST['submit'] == 'Show EMail Report')
	{
		$SDt = $_REQUEST['txtSDt'];
		$SDtExplode = explode("/", $SDt);
		$SDtYMD = $SDtExplode[2].'-'.$SDtExplode[1].'-'.$SDtExplode[0].' 00:00:00';
		
		$EDt = $_REQUEST['txtEDt'];
		$EDtExplode = explode("/", $EDt);
		$EDtYMD = $EDtExplode[2].'-'.$EDtExplode[1].'-'.$EDtExplode[0].' 23:59:59';
		
		 $EMailReportQuery = "SELECT s.id, s.messageid, s.cts, m.type, u.name, u.email FROM sentmessage AS s, messagetemplate AS m, user AS u 
                   WHERE s.cts >= '".$SDtYMD."'  AND 
                   s.cts <='".$EDtYMD."' AND s.messageid=m.id AND u.id=s.userid and m.mode = 'email' ORDER BY s.cts, u.name ASC";
          
		$EMailReport = $Global->SelectQuery($EMailReportQuery);
	}
	
	include 'templates/emailReport.tpl.php';	
?>
