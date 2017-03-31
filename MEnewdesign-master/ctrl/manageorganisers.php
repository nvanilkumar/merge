<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	list of organizers
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 25 Aug 2009
 *	2.	Updated on 10th Oct 2009 - added new search field - city
******************************************************************************************************************************************/
	session_start();
	
	include_once("MT/cGlobal.php");
	$Global = new cGlobal();		
	 include 'loginchk.php';

	//Rather than the city id we are selecting the city name here for uniqueness
	$sqlCities = "SELECT DISTINCT c.City FROM Cities AS c ORDER BY c.City ASC";
	$dtlCities = $Global->SelectQuery($sqlCities);
	
	include 'templates/manageorganisers.tpl.php';
?>