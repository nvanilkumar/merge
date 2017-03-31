<?php

session_start();
include 'loginchk.php';

include_once("MT/cGlobali.php");


$Global = new cGlobali();


if ($_REQUEST['submit'] == "Submit") {

    $SalesId = $_REQUEST['SalesId'];
    $echk = $_REQUEST['echk'];
    $ddate = gmdate("Y-m-d\TH:i:s\Z");
    $EventsQuery = "UPDATE eventsetting SET qpid='" . $SalesId . "',qualitycheck='" . $echk . "',qdate='" . $ddate . "'   where eventid = '" . $_REQUEST[eid] . "' ";
    $EventsOfMonth = $Global->ExecuteQuery($EventsQuery);
    if ($EventsOfMonth) {
        ?>
        <script>
            window.location = "qchecking.php";
        </script>
    <?php

    }
}

$SalesQuery = "SELECT `id` AS SalesId,`name` AS SalesName FROM salesperson ORDER BY `name` ";
$SalesQueryRES = $Global->SelectQuery($SalesQuery);

$SelQuery = "SELECT qpid AS QPid, qualitycheck AS eChecked from  eventsetting WHERE eventid = '" . $_REQUEST[eid] . "' ";
$SelQueryRES = $Global->SelectQuery($SelQuery);
$salesid = $SelQueryRES[0][QPid];
$echkd = $SelQueryRES[0][eChecked];

include 'templates/qcheckingedit.tpl.php';
?>