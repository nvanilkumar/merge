<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Ad Banner Management
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 14th Sep 2009
******************************************************************************************************************************************/
	
	session_start();
	include 'loginchk.php';

	include_once("MT/cGlobal.php");

	$Global = new cGlobal();

/**************************commented on 17082009 need to remove afterwords**************************
// GET THE LIST OF IMAGE ADDS 
$sql_ad = "SELECT node.title,node.status,node.created,ad_image.url,ad_image.aid FROM node,ad_image,files WHERE ad_image.fid = files.fid AND files.nid = node.nid" ;
$sql_ad_res = mysql_query($sql_ad);
****************************************************/


	include 'templates/banner.tpl.php';
?>