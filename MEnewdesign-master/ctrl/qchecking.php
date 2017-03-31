<?php

session_start();

include_once("MT/cGlobali.php");
include 'loginchk.php';

$Global = new cGlobali();
include_once("includes/common_functions.php");
$common=new functions();


 $categoryQuery = "SELECT * FROM `category` WHERE `deleted` = 0 AND `status` = 1 ORDER BY `name` ASC";
        $categoryList = $Global->SelectQuery($categoryQuery);


$MsgCountryExist = '';
$msg = "";
$std = "";
$incpay = "";
$cat = "";
$SalesId = $_REQUEST['SalesId'];
$echk = $_REQUEST['echk'];
$eadd = $_REQUEST['eadd'];
$dd = " and e.startdatetime > now()";
if(isset($_REQUEST['categoryid']) && $_REQUEST['categoryid'] != "")
{
	$categoryId = $_REQUEST['categoryid'];
	$cat = " AND e.categoryid = ".$categoryId;
}
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
    $incpay.=" AND es.qdate BETWEEN '" . $SDtYMD . "' AND '" . $EDtYMD . "'";
    
}
if ($_REQUEST['paid'] != "") {
    if ($_REQUEST['paid'] == "Paid") {
        $incpay.=" AND e.registrationtype=2 ";
    } else {
        $incpay.=" AND e.registrationtype=1 ";
    }
}
if ($_REQUEST['more5'] == 1) {
    $incpay.=" AND DATEDIFF(e.enddatetime,e.startdatetime)>5 ";
}

if ($_REQUEST['nocat'] == 1) {
    $incpay.=" AND (e.categoryid = 0 OR e.categoryid = '') ";
}

if ($_REQUEST['EventId'] != "") {
    $incpay.=" AND e.id=" . $_REQUEST['EventId'];
    $dd = "";
}
if ($echk == "Checked") {
    $incpay.=" AND es.qualitycheck = '1'";
} else if ($echk == "All") {
    
} else {
    $incpay.=" AND es.qualitycheck = '0'";
}
if ($_REQUEST['nologo'] == 1) {
    $sql_re = "SELECT e.id AS Id,e.ownerid AS UserID from event as e WHERE (e.thumbnailfileid='eventlogo/' || e.thumbnailfileid='') ".$incpay . $dd ." and e.deleted=0 and e.status=1";
    $re = $Global->SelectQuery($sql_re);
    for ($r = 0; $r < count($re); $r++) {
        $sql_logo = "SELECT logofileid FROM organizer WHERE userid=" . $re[$r]['UserID'];
        $sel_logo = $Global->SelectQuery($sql_logo);
        if ($sel_logo[0]['logofileid'] == "" || $sel_logo[0]['logofileid'] == "logo/" || $sel_logo[0]['logofileid'] == "content/logo/") {
            $lid.=$re[$r]['Id'] . ",";
        }
    }
    $orgid = substr($lid, 0, -1);
    $nolog = " AND e.id  IN (" . $orgid . ")";
}

$targetpage = "qchecking.php?SalesId=" . $_REQUEST['SalesId'] . "&categoryid=".$_REQUEST['categoryid']. "&echk=" . $_REQUEST['echk'] . "&txtSDt=" . $_REQUEST['txtSDt'] . "&txtEDt=" . $_REQUEST['txtEDt'] . "&paid=" . $_REQUEST['paid'] . "&more5=" . $_REQUEST['more5'] . "&nocat=" . $_REQUEST['nocat'] . "&eadd=" . $_REQUEST['eadd'] . "&nologo=" . $_REQUEST['nologo'];
$pagenum = $_REQUEST[pagenum];
if (!(isset($pagenum))) {
    $pagenum = 1;
}
$page_rows = 20;

 $sqlc = "SELECT count(e.id) as tot "
        . " FROM eventdetail AS ed JOIN event AS e ON ed.eventid=e.id "
        . " JOIN eventsetting AS es ON e.id=es.eventid "
		. " left JOIN subcategory AS sb ON e.subcategoryid=sb.id"
		. " left JOIN salesperson AS s ON s.id = ed.salespersonid "
        . " WHERE e.title != '' AND e.deleted = 0 ". $incpay . $nolog . $dd . $cat ." and e.status=1";
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
    $max = ' limit ' . ($pagenum - 1) * $page_rows . ',' . $page_rows;
} else {
    $max = ' limit 0,' . $page_rows;
}




    $EventsQuery = "SELECT s.name AS salesName, e.id AS Id, e.ownerid AS UserID, e.title AS Title, e.url AS URL, "
        . " es.qpid AS QPid, es.qualitycheck AS eChecked, es.qdate AS QDate, ed.extrareportingemails AS OEmails , sb.name as Subcategory"
        . " FROM eventdetail AS ed JOIN event AS e ON ed.eventid=e.id "
        . " JOIN eventsetting AS es ON e.id=es.eventid "
		. " left JOIN subcategory AS sb ON e.subcategoryid=sb.id"
        . " left JOIN salesperson AS s ON s.id = ed.salespersonid "
        . " WHERE e.title != '' AND e.deleted = 0 ". $incpay . $dd . $nolog . $cat . " AND e.status=1 ORDER BY e.startdatetime ASC ".$max;
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



$SalesQuery = "SELECT id AS SalesId, `name` AS SalesName FROM  salesperson ORDER BY `name`";
$SalesQueryRES = $Global->SelectQuery($SalesQuery);

include 'templates/qchecking.tpl.php';
?>