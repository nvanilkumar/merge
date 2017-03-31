<?php

/* * ****************************************************************************************************************************************
 * 	File deatils:
 * 	list of organizers
 * 	
 * 	Created / Updated on:
 * 	1.	Using the MT the file is updated on 25 Aug 2009
 * 	2.	Updated on 10th Oct 2009 - added new search field - city
 * **************************************************************************************************************************************** */
session_start();

include_once("MT/cGlobali.php");
$Global = new cGlobali();
include 'loginchk.php';

//Rather than the city id we are selecting the city name here for uniqueness
if ($_REQUEST[Edit] == 'ChangeStatus') {
    $Id = $_REQUEST['Id'];
    $sActive = $_REQUEST['newStatus'];
    $sqlup = "update organization set status=$sActive where id=$_REQUEST[Id]";
    $db_sqlup = $Global->ExecuteQuery($sqlup);
}
/*  $sqlOrg = "SELECT org.*, u.id AS Id, u.username AS UserName,  u.company AS Company "
        . "FROM organizer AS org, user AS u WHERE org.userid = u.id "
        . "ORDER BY u.company ASC";
$dtlOrg = $Global->SelectQuery($sqlOrg);  */

$sqlOrgdisp = "SELECT o.id AS Id,o.name AS orgDispName, o.status AS Active FROM organization AS o ORDER BY o.name ASC";
$dtlOrgdisp = $Global->SelectQuery($sqlOrgdisp);

include 'templates/manageorganisersname.tpl.php';
?>