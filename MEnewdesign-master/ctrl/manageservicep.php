<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	list of organizers
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 25 Aug 2009
******************************************************************************************************************************************/
	
	session_start();
	include 'loginchk.php';
	
	include_once("MT/cGlobal.php");


	$Global = new cGlobal();		
	

	include 'templates/manageservicep.tpl.php';
?>