<?php

session_start();

include_once("MT/cGlobali.php");
include 'loginchk.php';


$Global = new cGlobali();
include_once("includes/common_functions.php");
$common=new functions();

$reqCountry = $reqState = $reqTxtSDt = $reqCity = "";


if (isset($_REQUEST['txtSDt']) && $_REQUEST['txtSDt'] != "") {
    $SDt = $_REQUEST['txtSDt'];
    $SDtExplode = explode("/", $SDt);
    $SDtYMD = $SDtExplode[2] . '-' . $SDtExplode[1] . '-' . $SDtExplode[0] . ' 00:00:00';
    $EDtYMD = $SDtExplode[2] . '-' . $SDtExplode[1] . '-' . $SDtExplode[0] . ' 23:59:59';
    $SDtYMD =$common->convertTime($SDtYMD, DEFAULT_TIMEZONE);
    $EDtYMD =$common->convertTime($EDtYMD, DEFAULT_TIMEZONE);
    
    $reqTxtSDt = "txtSDt=" . $_REQUEST['txtSDt'];
    
    $incpay.=" AND e.enddatetime between '" . $SDtYMD . "' and '" . $EDtYMD . "'";
} else {
    $SDt = date("d/m/Y", mktime(0, 0, 0, date("m"), (date("d") - 1), date("Y")));
    $EDt = date("d/m/Y", mktime(0, 0, 0, date("m"), (date("d") - 1), date("Y")));
    $SDtYMD = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 1), date("Y"))) . ' 00:00:01';
    $EDtYMD = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 1), date("Y"))) . ' 23:59:59';
    $SDtYMD =$common->convertTime($SDtYMD, DEFAULT_TIMEZONE);
    $EDtYMD =$common->convertTime($EDtYMD, DEFAULT_TIMEZONE);
    
    $incpay.=" AND e.enddatetime between '" . $SDtYMD . "' and '" . $EDtYMD . "'";
}

if (isset($_REQUEST['CountryId']) && $_REQUEST['CountryId'] != 0 && $_REQUEST['CountryId'] != "") {
    $reqCountry = "&CountryId=" . $_REQUEST['CountryId'];
    $incpay.=" AND e.countryid=" . $_REQUEST['CountryId'];
}
if (isset($_REQUEST['StateId']) && $_REQUEST['StateId'] != 0 && $_REQUEST['StateId'] != "") {
    $reqState = "&StateId=" . $_REQUEST['StateId'];
    $incpay.=" AND e.stateid=" . $_REQUEST['StateId'];
}
if (isset($_REQUEST['CityId']) && $_REQUEST['CityId'] != 0 && $_REQUEST['CityId'] != "") {
    $reqCity = "&CityId=" . $_REQUEST['CityId'];
    $incpay.=" AND e.cityid=" . $_REQUEST['CityId'];
}

$targetpage = "eventsbyenddate.php?" . $reqTxtSDt . "" . $reqCountry . "" . $reqState . "" . $reqCity;
$pagenum = $_REQUEST['pagenum'];
if (!(isset($pagenum))) {
    $pagenum = 1;
}
$page_rows = 20;

$sqlc = "SELECT count(e.id) as tot "
        . "FROM eventdetail ed "
        . "JOIN event as e ON ed.eventid = e.id "
        . "JOIN eventsetting AS es ON e.id = es.eventid "
        . "JOIN user u ON u.id=e.ownerid "
        . "WHERE e.Title != '' AND e.deleted = 0 ".$incpay." AND e.status=1 "
        . "ORDER BY e.startdatetime ASC";
$r = $Global->SelectQuery($sqlc);
$rows = $r[0]['tot'];

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

$EventsQuery = "SELECT e.id AS Id,e.ownerid AS UserID,e.title AS Title,es.qpid AS QPid,e.url AS URL, "
            . "es.qualitycheck AS eChecked,es.qdate AS QDate,ed.extrareportingemails AS OEmails, "
            . "u.name AS FirstName,u.company AS Company, u.email AS Email, u.mobile AS Mobile "
            . "FROM eventdetail ed "
            . "JOIN event as e ON ed.eventid = e.id "
            . "JOIN eventsetting AS es ON e.id = es.eventid "
            . "JOIN user u ON u.id=e.ownerid "
            . "WHERE e.Title != '' AND e.deleted = 0 ". $incpay ." and e.status=1 "
            . "ORDER BY e.startdatetime ASC ".$max;
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

$countrQuery = "SELECT `id`,`name` FROM country WHERE `status` = 1 AND `deleted` = 0 "
        . "ORDER BY `name`";
$countryQueryRES = $Global->SelectQuery($countrQuery);
if (isset($_REQUEST['CountryId']) && $_REQUEST['CountryId'] != 0 && $_REQUEST['CountryId'] != '') {
    $StateQuery = "SELECT `id`,`name` FROM state WHERE `countryid` = '" . $_REQUEST['CountryId'] . "' AND `status` = 1 AND `deleted` = 0 "
            . "ORDER BY `name`";
    $StateQueryRES = $Global->SelectQuery($StateQuery);
    if (isset($_REQUEST['StateId']) && $_REQUEST['StateId'] != 0 && $_REQUEST['StateId'] != '') {
        $CityQuery = "SELECT c.id, c.name FROM city c JOIN statecity sc ON c.id = sc.cityid "
                . "WHERE sc.stateid = '" . $_REQUEST['StateId'] . "' AND c.name NOT LIKE '%Other%' AND c.status = 1 AND c.deleted = 0 "
                . "ORDER BY `name`";
        $CityQueryRES = $Global->SelectQuery($CityQuery);
    }
}

include 'templates/eventsbyenddate.tpl.php';
?>