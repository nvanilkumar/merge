<?php

/* * ****************************************************************************************************************************************
 * 	File deatils:
 * 	Display Cancel Transactions
 * 	
 * 	Created / Updated on:
 * 	1.	Using the MT the file is updated on 26th Aug 2009
 * 	2.	Added the new filed IsFamous in db which is used to display the Famous Events on the front end.
 * 		The check box property checked shows the event is famous, visible on front end and vice versa.
 * **************************************************************************************************************************************** */

session_start();
$uid = $_SESSION['uid'];

include 'loginchk.php';

//include_once("MT/cAttendees.php");
include_once("MT/cGlobali.php");

$Global = new cGlobali();


$famous = "";
if ($_REQUEST['Status'] == "Famous") {
    $famous = " And e.popularity=1";
}


/* $EventQuery = "SELECT  e.id,e.title,e.registrationtype, e.ViewCount,e.ownerid,e.startdatetime,e.enddatetime,e.salespersonid  FROM  events e where e.enddatetime > now() and e.registrationtype=0 and e.Published=1   $famous  ORDER BY e.ViewCount DESC"; 
  $EventQueryRES = $Global->SelectQuery($EventQuery);
  print_r($EventQueryRES); */


$EventQuery = "SELECT u.email, u.name, u.phone, u.mobile, "
                 . "e.id, e.title, e.registrationtype, "
                . "e.ownerid,e.startdatetime,e.enddatetime,"
                . "ed.salespersonid, s.name AS salesname "
                . "FROM eventdetail ed JOIN event e ON e.id=ed.eventid "
                . "LEFT OUTER JOIN salesperson s ON ed.salespersonid=s.id "
                . "LEFT OUTER JOIN user as u on u.id=e.ownerid "
                . "WHERE e.enddatetime > now() AND e.registrationtype=2 AND e.status=1 AND e.deleted = 0 "
                . $famous ;
                //. " ORDER BY evc.viewCount DESC";
//echo $EventQuery;
$EventQueryRES = $Global->SelectQuery($EventQuery);
//  echo "<br> NeW one <br>";
//print_r($EventQueryRES);
include 'templates/orgscore.tpl.php';
?>
