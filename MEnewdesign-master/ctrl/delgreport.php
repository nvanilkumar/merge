<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	MIS Report - Delegate Report Generation - Select the Event to generate the report.
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 26th Aug 2009
******************************************************************************************************************************************/
	
	session_start();
	include 'loginchk.php';
	
	include_once("MT/cGlobal.php");

	$Global = new cGlobal();
	
/*	if($_REQUEST['show'] == 'Generate Report')
	{
		include_once("MT/cEventNames.php");
		$Id = $_REQUEST['event'];
	}
*/
	//Query For Event Names List
	$EventQuery = "SELECT Id, EventName FROM eventnames";
	$EventList = $Global->SelectQuery($EventQuery);
	
	include 'templates/delgreport.tpl.php';
?>