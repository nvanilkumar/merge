<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	MIS Report - Organizer Report Generation
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 26th Aug 2009
******************************************************************************************************************************************/
	
	session_start();
	
	include_once("MT/cGlobal.php");
include 'loginchk.php';
	$Global = new cGlobal();		
	
	$OrganizerQuery = "SELECT u.FirstName, u.Company, c.City, org.UserId FROM organizer AS org, user AS u, Cities AS c WHERE org.UserId=u.Id AND u.CityId=c.Id ORDER BY u.FirstName ASC";	
//	$OrganizerQuery = "SELECT u.FirstName, u.Company, c.City, en.EventName FROM organizer AS org, user AS u, Cities AS c, eventnamepref AS enp, eventnames AS en WHERE org.UserId=u.Id AND u.CityId=c.Id AND enp.UserId = u.Id AND enp.EventName = en.Id GROUP BY u.FirstName ORDER BY u.FirstName ASC";	
	$AllOrganizer = $Global->SelectQuery($OrganizerQuery);
	
	include 'templates/orgreport.tpl.php';				
?>	