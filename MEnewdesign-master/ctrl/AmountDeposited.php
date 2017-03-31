<?php
session_start();
ini_set('max_execution_time', 2000);
include 'loginchk.php';

include_once("MT/cGlobali.php");
include_once("includes/common_functions.php");


$Global = new cGlobali();
$common=new functions();
$MsgCountryExist = '';


//error_reporting(-1);

include_once("includes/functions.php");
if ($_REQUEST['txtSDt'] != "") {
    $SDt = $_REQUEST['txtSDt'];
    $SDtExplode = explode("/", $SDt);
    $yesterdaySDate = $SDtExplode[2] . '-' . $SDtExplode[1] . '-' . $SDtExplode[0].' 00:00:00';
    $yesterdaySDate =$common->convertTime($yesterdaySDate, DEFAULT_TIMEZONE);
    
    $yesterdayEDate = $SDtExplode[2].'-'.$SDtExplode[1].'-'.$SDtExplode[0].' 23:59:59';
    $yesterdayEDate =$common->convertTime($yesterdayEDate, DEFAULT_TIMEZONE);

    $settdates = " and s.settlementdate between '".$yesterdaySDate."' and '".$yesterdayEDate."' ";
    $dates = " and s.depositdate between '".$yesterdaySDate."' and '".$yesterdayEDate."' ";
   
    $event_dates=" and ((s.depositdate between '".$yesterdaySDate."' and '".$yesterdayEDate."')  "
                . "or (s.settlementdate between '".$yesterdaySDate."' and '".$yesterdayEDate."')) ";
}


$EventId = NULL;
if (!empty($_REQUEST['EventId']) || !empty($_REQUEST['eventIdSrch'])) {
    if (!empty($_REQUEST['EventId'])) {
        $EventId = $_REQUEST['EventId'];
        $EventIdSql = " and s.eventid='" . $_REQUEST['EventId'] . "'";
    } else if (!empty($_REQUEST['eventIdSrch'])) {
        $EventId = $_REQUEST['eventIdSrch'];
        $EventIdSql = " and s.eventid='" . $_REQUEST['eventIdSrch'] . "'";
    }
}


$offTransSql = " AND  s.paymenttransactionid not in ('A1','Offline') ";
if (isset($_REQUEST['offTrans'])) {
    $offTransSql = " AND s.paymenttransactionid != 'A1' ";
}


/*if ($_REQUEST['SerEventName'] != "") {
    $sqlid = "SELECT Id,UserId FROM orgdispnameid where OrgId=" . $_REQUEST['SerEventName'];
    $dtsqlid1 = $Global->SelectQuery($sqlid);
    for ($i = 0; $i < count($dtsqlid1); $i++) {
        $orgid1.=$dtsqlid1[$i][UserId] . ",";
    }

    $orgid = substr($orgid1, 0, -1);
    $SearchQuery = " AND e.UserID in (" . $orgid . ") ";
}*/
$TotalAmount = 0;
$cntTransactionRES = 1;
if ($_REQUEST['txtSDt'] != "" || $_REQUEST["eventIdSrch"] != "" || $_REQUEST['SerEventName'] != "") {

    //Display list of Successful Transactions
    $TransactionQuery = "SELECT distinct(s.eventid) FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id  where 1 and s.depositdate!='0000-00-00 00:00:00' ".$offTransSql ." AND (s.paymentgatewayid='1') ". $event_dates . $EventIdSql . $SearchQuery ."  AND e.deleted = 0 order by e.startdatetime DESC"; //and e.Published=1
    $TransactionRES = $Global->SelectQuery($TransactionQuery);
//    print_r($TransactionRES);exit;
}



 $EventQuery = "SELECT s.eventid, e.title AS Details FROM eventsignup AS s 
	   INNER JOIN event AS e ON s.eventid = e.id  
	   where 1  AND (s.totalamount!=0 and (s.paymentmodeid=1 " . $offTransSql . ") or (s.discount='CashonDelivery' or s.discount='PayatCounter')) AND e.deleted = 0 AND e.status=1 ORDER BY e.Title  DESC";
//$EventQueryRES = $Global->SelectQuery($EventQuery);


include 'templates/AmountDeposited.tpl.php';
?>