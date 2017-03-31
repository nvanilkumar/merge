<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Display list of News
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 25th Aug 2009
******************************************************************************************************************************************/
	
	session_start();
	
	include_once("MT/cGlobal.php");
	
 include 'loginchk.php';

	$Global = new cGlobal();
		
	if($_REQUEST['action'] == "delete")
	{
		$Id = $_REQUEST['id'];
		
	}
		

	include 'templates/news.tpl.php';	
?>	   