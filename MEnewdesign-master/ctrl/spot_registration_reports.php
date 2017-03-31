<?php

session_start();
include_once("MT/cGlobali.php");
include 'loginchk.php';
$Globali = new cGlobali();
$dates='';
include_once("includes/common_functions.php");
$common=new functions();

if ($_REQUEST['txtSDt'] != "" && $_REQUEST['txtEDt'] != "") {
    $SDt = $_REQUEST['txtSDt'];
    $SDtExplode = explode("/", $SDt);
    $yesterdaySDate = $SDtExplode[2] . '-' . $SDtExplode[1] . '-' . $SDtExplode[0] . ' 00:00:00';
    $yesterdaySDate =$common->convertTime($yesterdaySDate, DEFAULT_TIMEZONE);

    $EDt = $_REQUEST['txtEDt'];
    $EDtExplode = explode("/", $EDt);
    $yesterdayEDate = $EDtExplode[2] . '-' . $EDtExplode[1] . '-' . $EDtExplode[0] . ' 23:59:59';
    $yesterdayEDate =$common->convertTime($yesterdayEDate, DEFAULT_TIMEZONE);
    $dates = " and s.settlementdate >= '" . $yesterdaySDate . "' AND s.settlementdate <= '" . $yesterdayEDate . "' ";
}
else if ($_REQUEST['txtSDt'] != "") {
$SDt = $_REQUEST['txtSDt'];
    $SDtExplode = explode("/", $SDt);
    $yesterdaySDate = $SDtExplode[2] . '-' . $SDtExplode[1] . '-' . $SDtExplode[0] . ' 00:00:00';
    $yesterdaySDate =$common->convertTime($yesterdaySDate, DEFAULT_TIMEZONE);
 
    $yesterdayEDate = $SDtExplode[2] . '-' . $SDtExplode[1] . '-' . $SDtExplode[0]  . ' 23:59:59';
    $yesterdayEDate =$common->convertTime($yesterdayEDate, DEFAULT_TIMEZONE);
    $dates = " and s.settlementdate >= '" . $yesterdaySDate . "' AND s.settlementdate <= '" . $yesterdayEDate . "' ";
} else {
    $SDt = date("d/m/Y", mktime(0, 0, 0, date("m"), (date("d") - 1), date("Y")));
    $EDt = date("d/m/Y", mktime(0, 0, 0, date("m"), (date("d") - 1), date("Y"))); 
}
$event_id="";
if($_REQUEST['eventIdSrch'] ){
    $EventId=$_REQUEST['eventIdSrch'];
    $event_id=" and s.eventid=".$_REQUEST['eventIdSrch']." ";
}

if($_REQUEST['submit']!= ""){
    $EventQuery = "SELECT s.id, s.eventid, e.Title AS Details, s.signupdate AS SignupDt, s.quantity AS Qty, "
            ." ROUND(s.totalamount/s.quantity)AS Fees,  "
            ." s.fromcurrencyid AS CurrencyId, "
            ." s.transactionstatus AS PaymentStatus, "
            ." s.paymentgatewayid AS PaymentGateway, "
            ." s.paymentstatus AS eChecked, "
            ." s.settlementdate AS SettlementDate "
            ." FROM eventsignup AS s "
            ." INNER JOIN event AS e ON s.eventid = e.id "
            ." WHERE  e.deleted = 0 AND ((s.paymentmodeid = 5 and s.paymenttransactionid in ('SpotCash','SpotCard'))) "
            . $dates . $event_id
            ." ORDER BY e.title DESC ";
    $EventQueryRES = $Globali->SelectQuery($EventQuery);   
}

include 'templates/spot_registration_reports_tpl.php';
