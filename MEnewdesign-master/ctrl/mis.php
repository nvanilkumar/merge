<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	MIS Report
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 26th Aug 2009
******************************************************************************************************************************************/
	
	session_start();
	include 'loginchk.php';
	
	include_once("MT/cGlobal.php");
	
	$Global = new cGlobal();

	include 'templates/mis.tpl.php';
?>	   