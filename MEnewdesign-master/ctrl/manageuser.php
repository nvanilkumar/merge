<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Mangage user
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 22nd Aug 2009
******************************************************************************************************************************************/
	
	session_start();
	include 'loginchk.php';
	
	include_once("MT/cGlobal.php");
	include_once("MT/cUser.php");
	
	$Global = new cGlobal();	

	include 'templates/manageuser.tpl.php';
?>