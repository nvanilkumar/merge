<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Date Report of Registration
 *	
 *	Created / Updated on:
 *	1.	created on 02nd Oct 2009
 *		display the list of users registered between start and end date.
******************************************************************************************************************************************/
error_reporting(E_ALL);
	session_start();	
	include 'loginchk.php';
	
	include_once("MT/cGlobal.php");
	$Global = new cGlobal();
		
	if($_REQUEST['submit'] == 'Show Users')
	{
		$SDt = $_REQUEST['txtSDt'];
		$SDtExplode = explode("/", $SDt);
		$SDtYMD = $SDtExplode[2].'-'.$SDtExplode[1].'-'.$SDtExplode[0].' 00:00:00';
		
		$EDt = $_REQUEST['txtEDt'];
		$EDtExplode = explode("/", $EDt);
		$EDtYMD = $EDtExplode[2].'-'.$EDtExplode[1].'-'.$EDtExplode[0].' 23:59:59';
		
		$UsersQuery = "SELECT FirstName, MiddleName, LastName, RegnDt FROM user WHERE RegnDt >= '".$SDtYMD."' AND RegnDt <='".$EDtYMD."' ORDER BY RegnDt ASC";
		$UsersDateRegn = $Global->SelectQuery($UsersQuery);
	}
	
	include 'templates/dateReportRegn.tpl.php';
?>