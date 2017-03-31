<?php

session_start();
$uid =	$_SESSION['uid'];

include_once("MT/cGlobali.php");
include 'loginchk.php';

$Global = new cGlobali();
include_once("includes/common_functions.php");
$common=new functions();

if ($_REQUEST['value'] == "change") {
    $sId = $_REQUEST['sId'];
    $CompiOrg = $_REQUEST['CompiOrg'];
    $LeftForPayment = $_REQUEST['LeftForPayment'];
    $tck = $_REQUEST['Tckwdz'];
    $PaidBit = $_REQUEST['PaidBit'];
    $Exception = $_REQUEST['Exception'];
    $EventsUpQuery = "UPDATE eventsetting set displayticketwidget='" . $tck . "', paidbit='" . $PaidBit . "', "
                    . " compiorg='" . $CompiOrg . "', leftforpayment='" . $LeftForPayment . "', "
                    . " exception='" . $Exception . "' "
                    . " WHERE eventId = '" . $sId . "' ";
    $Global->ExecuteQuery($EventsUpQuery);
}

$MsgCountryExist = '';
$msg = "";
$std = "";
$incpay = "";
$SalesId = $_REQUEST['SalesId'];

$eadd = $_REQUEST['eadd'];
//$dd=" and e.StartDt > now()";
if ($SalesId != "") {
    $incpay.=" AND es.qpid=" . $SalesId;
}
if ($eadd != "") {
    if ($eadd == "Team") {
        $incpay.=" AND ed.extrareportingemails LIKE  '%@meraevents.com%'";
    } else {
        $incpay.=" AND ed.extrareportingemails NOT LIKE  '%@meraevents.com%'";
    }
}
if ($_REQUEST['txtSDt'] != "" && $_REQUEST['txtEDt'] != "") { 
    $SDt = $_REQUEST['txtSDt'];
    $SDtExplode = explode("/", $SDt);
    $SDtYMD = $SDtExplode[2] . '-' . $SDtExplode[1] . '-' . $SDtExplode[0] . ' 00:00:00';
    $SDtYMD =$common->convertTime($SDtYMD, DEFAULT_TIMEZONE);

    $EDt = $_REQUEST['txtEDt'];
    $EDtExplode = explode("/", $EDt);
    $EDtYMD = $EDtExplode[2] . '-' . $EDtExplode[1] . '-' . $EDtExplode[0] . ' 23:59:59';
    $EDtYMD =$common->convertTime($EDtYMD, DEFAULT_TIMEZONE);
    $incpay.=" AND e.startdatetime between '" . $SDtYMD . "' and '" . $EDtYMD . "'";
} else { 
    $SDt = date("d/m/Y", mktime(0, 0, 0, date("m"), (date("d") - 1), date("Y")));
    $EDt = date("d/m/Y", mktime(0, 0, 0, date("m"), (date("d") - 1), date("Y")));
    $SDtYMD = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 1), date("Y"))) . ' 00:00:01';
    $SDtYMD =$common->convertTime($SDtYMD, DEFAULT_TIMEZONE);
    $EDtYMD = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 1), date("Y"))) . ' 23:59:59';
    $EDtYMD =$common->convertTime($EDtYMD, DEFAULT_TIMEZONE);
    $incpay.=" AND e.startdatetime between '" . $SDtYMD . "' and '" . $EDtYMD . "'";
}
if ($_REQUEST['Tck'] != "") {
    if ($_REQUEST['Tck'] == "1") {
        $incpay.=" AND es.displayticketwidget='1'";
    } else {
        $incpay.=" AND es.displayticketwidget='0'";
    }
}

$EPublished = "";
if (isset($_REQUEST['EPublished']) && $_REQUEST['EPublished'] != "") {
    if ($_REQUEST['EPublished'] == "All") {
        $EPublished = "";
    } else if ($_REQUEST['EPublished'] == "Yes") {
        $EPublished = " and e.status =1";
    } else if ($_REQUEST['EPublished'] == "No") {
        $EPublished = " and e.status =0";
    }
} else {
    $EPublished = " and e.status=1";
    $_REQUEST['EPublished'] = 'Yes';
}


if ($_REQUEST['paid'] != "") {
    if ($_REQUEST['paid'] == "Yes") {
        $incpay.=" AND es.paidbit='1'";
    } else {
        $incpay.=" AND es.paidbit='0' ";
    }
}


if ($_REQUEST['Eventid'] != "") {
    $incpay.=" and e.id=" . $_REQUEST['Eventid'];
    $dd = "";
}

if ($_REQUEST['EException'] != "") {
    $exp = " AND es.exception='" . $_REQUEST['EException'] . "'";
}


$targetpage = "eventchk.php?SalesId=" . $_REQUEST['SalesId'] . "&echk=" . $_REQUEST['echk'] . "&txtSDt=" . $_REQUEST['txtSDt'] . "&txtEDt=" . $_REQUEST['txtEDt'] . "&paid=" . $_REQUEST['paid'] . "&Tck=" . $_REQUEST['Tck'] . "&more5=" . $_REQUEST['more5'] . "&nocat=" . $_REQUEST['nocat'] . "&eadd=" . $_REQUEST['eadd'] . "&nologo=" . $_REQUEST['nologo'] . "&EPublished=" . $_REQUEST['EPublished'] . "&EException=" . $_REQUEST['EException'] . "&Exception=" . $_REQUEST['Exception'];
$pagenum = $_REQUEST['pagenum'];
if (!(isset($pagenum))) {
    $pagenum = 1;
}
$page_rows = 20;

 $sqlc = "SELECT count(e.Id) as tot "
            . " FROM eventdetail AS ed JOIN event AS e ON ed.eventid=e.id "
            . " JOIN eventsetting AS es ON e.id=es.eventid "
            . " JOIN user AS u ON u.id=e.ownerid "
            . " WHERE e.title != '' AND e.deleted = 0 ". $incpay . $nolog . $dd . $EPublished . $exp  ;
$r = $Global->SelectQuery($sqlc);
$rows = $r[0][tot];

$last = ceil($rows / $page_rows);
if ($pagenum < 1) {
    $pagenum = 1;
} elseif ($pagenum > $last) {
    $pagenum = $last;
}

//This sets the range to display in our query 
if ($pagenum > 0) {
    $max = " LIMIT " . ($pagenum - 1) * $page_rows . ',' . $page_rows;
} else {
    $max = " LIMIT 0," . $page_rows;
}




 $EventsQuery = "SELECT e.id AS Id,e.ownerid AS UserID,e.title AS Title, es.qpid AS QPid, e.url AS URL, "
            . " es.qualitycheck AS eChecked, es.qdate AS QDate,"
            . " ed.extrareportingemails AS OEmails, es.displayticketwidget AS Tckwdz, es.paidbit AS PaidBit, "
            . " es.compiorg AS CompiOrg, es.leftforpayment AS LeftForPayment, es.exception AS Exception, "
            . " u.`name` AS FirstName,u.company AS Company, u.email AS Email, u.mobile AS Mobile"
            . " FROM eventdetail AS ed JOIN event AS e ON ed.eventid=e.id "
            . " JOIN eventsetting AS es ON e.id=es.eventid "
            . " JOIN user AS u ON u.id=e.ownerid "
            . " WHERE e.Title != '' AND e.deleted = 0 ".$incpay . $dd . $nolog . $EPublished . $exp
            . " ORDER BY e.startdatetime ASC ".$max;
$EventsOfMonth = $Global->SelectQuery($EventsQuery);

$pagination = "";

if ($pagenum == 1) {
    
} else {
    $pagination .= " <a href='$targetpage&pagenum=1'> <<-First</a> ";
    $previous = $pagenum - 1;
    $pagination .= " <a href='$targetpage&pagenum=$previous'> <-Previous</a> ";
}
//just a spacer
//$pagination .=  " ---- ";
//This does the same as above, only checking if we are on the last page, and then generating the Next and Last links
if ($pagenum == $last) {
    
} else {
    $next = $pagenum + 1;
    $pagination .= " <a href='$targetpage&pagenum=$next'>Next -></a> ";
    $pagination .= " ";
    $pagination .= " <a href='$targetpage&pagenum=$last'>Last ->></a> ";
}



$SalesQuery = "SELECT id AS SalesId,`name` AS SalesName FROM salesperson ORDER BY `name`";
$SalesQueryRES = $Global->SelectQuery($SalesQuery);

include 'templates/eventchk.tpl.php';
?>