<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Display list of News letters
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 25th Aug 2009
******************************************************************************************************************************************/
	
	session_start();
	
	include_once("MT/cGlobal.php");
	 include 'loginchk.php';

	$Global = new cGlobal();
	
	
	include 'templates/newsletter.tpl.php';	
?>