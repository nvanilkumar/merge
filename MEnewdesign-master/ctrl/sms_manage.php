<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	SMS SETTINGS - It displays the list of SMS TRIGGERS
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 26th Aug 2009
******************************************************************************************************************************************/
	
	session_start();
	include 'loginchk.php';
	
	include_once("MT/cGlobal.php");
	
	$Global = new cGlobal();
	
//	$SMSQuery = "SELECT * FROM sms_trigger";
//	$SMSList = $Global->SelectQuery($SMSQuery);

	include 'templates/sms_manage.tpl.php';
?>