<?php

session_start();
ini_set('max_execution_time', 2000);

include_once("MT/cGlobali.php");
include 'loginchk.php';
include_once("includes/common_functions.php");


$Global = new cGlobali();
$MsgCountryExist = '';
$common=new functions();

if ($_REQUEST['txtSDt'] != "") {
    $SDt = $_REQUEST['txtSDt'];
    $SDtExplode = explode("/", $SDt);
    $yesterdaySDate = $SDtExplode[2] . '-' . $SDtExplode[1] . '-' . $SDtExplode[0] . ' 00:00:00';
    $yesterdaySDate =$common->convertTime($yesterdaySDate, DEFAULT_TIMEZONE);

    $EDt = $_REQUEST['txtEDt'];
    $EDtExplode = explode("/", $EDt);
    $yesterdayEDate = $EDtExplode[2] . '-' . $EDtExplode[1] . '-' . $EDtExplode[0] . ' 23:59:59';
    $yesterdayEDate =$common->convertTime($yesterdayEDate, DEFAULT_TIMEZONE);

    $dates = " and s.signupdate between '" . $yesterdaySDate . "' and '" . $yesterdayEDate . "' ";
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


if (isset($_REQUEST['compeve']) && $_REQUEST['compeve'] == 1) {
    $compeve = " AND e.enddatetime < now() ";
}


/*if ($_REQUEST['SerEventName'] != "") {
    $sqlid = "SELECT id AS Id, ownerid AS UserId FROM orgdispnameid where OrgId=" . $_REQUEST['SerEventName'];
    $dtsqlid1 = $Global->SelectQuery($sqlid);
    for ($i = 0; $i < count($dtsqlid1); $i++) {
        $orgid1.=$dtsqlid1[$i][UserId] . ",";
    }

    $orgid = substr($orgid1, 0, -1);
    $SearchQuery = " AND e.UserID in (" . $orgid . ") ";
}*/
$TotalAmount = 0;
$cntTransactionRES = 1;
if ($_REQUEST['txtSDt'] != "" || $_REQUEST[EventId] != "" || $_REQUEST['SerEventName'] != "" || $_REQUEST['eventIdSrch'] != "") {
    if ($_REQUEST[Status] != "") {
        if ($_REQUEST[Status] == "Pending") {
            //Display list of Successful Transactions
            $TransactionQuery = "SELECT distinct(s.eventid) FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.Id     where 1 and s.paymentstatus='Verified' and s.eventid not in (select eventid from settlement)   AND (s.totalamount!=0 and (1  $offTransSql) )  $dates $EventIdSql $compeve $SearchQuery and e.status=1 order by e.startdatetime DESC";
        } else {
            $TransactionQuery = "SELECT distinct(s.eventid) FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.Id   where 1 and s.paymentstatus='Verified' and s.eventid in (select eventid from settlement where amountpaid!=0)   AND (s.totalamount!=0 and (1 $offTransSql) or ( s.paymentgatewayid='2' ))  $dates $EventIdSql $compeve $SearchQuery and e.status=1 order by e.startdatetime DESC";
        }
    } else {
   $TransactionQuery = "SELECT distinct(s.eventid) FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.Id    where 1 and s.paymentstatus='Verified'   AND (s.totalamount!=0 and (1 $offTransSql) or ( s.paymentgatewayid='2' ))  $dates $EventIdSql $compeve $SearchQuery and e.status=1 order by e.startdatetime DESC";
    }
    $TransactionRES = $Global->SelectQuery($TransactionQuery);
}



 $EventQuery = "SELECT distinct(s.eventid), e.title AS Details FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id JOIN discount ds ON ds.eventid = e.id WHERE 1  AND (s.totalamount!=0 and (s.paymentmodeid=1 $offTransSql) or ( s.paymentgatewayid='2' or ds.code='PayatCounter')) ORDER BY e.title DESC";
//$EventQueryRES = $Global->SelectQuery($EventQuery);





include 'templates/paymentsummary.tpl.php';
?>