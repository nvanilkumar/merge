<?php

session_start();

include 'loginchk.php';


include_once("MT/cGlobali.php");
$Global = new cGlobali();

$esid = NULL;




if (isset($_REQUEST['esid'])) {
    $esid = $_REQUEST['esid'];
    $sqlEditES = "select es.`Id`,es.eventid AS EventId, es.signupdate AS SignupDt,es.quantity AS Qty, (es.totalamount/es.quantity) AS Fees, "
            . " es.fromcurrencyid AS CurrencyId, es.conversionrate AS conversionRate, "
            . " es.convertedamount AS paypal_converted_amount,e.title AS Title, e.url AS URL, c.code AS 'currencyCode' " 
            . " FROM eventsignup es LEFT JOIN event as e on  es.eventid=e.id "
            . " INNER JOIN currency c on es.fromcurrencyid=c.id "
            . " where  e.deleted = 0 and  es.`id`='" . $esid . "'";
    //echo $sqlEditES;
    $edidESdata = $Global->SelectQuery($sqlEditES);
    //print_r($edidESdata);
}

if (isset($_POST['updateConversion'])) {
    $esid = $_POST['esid'];
    $crate = $_POST['crate'];
    //print_r($_POST); 
    $sqlUp = "update `eventsignup` set `conversionrate`='" . $crate . "',`tocurrencyid`=1 where `id`='" . $esid . "'";
    $Global->ExecuteQuery($sqlUp);

    $_SESSION['currConversion'] = true;
    header("Location: currencyConversion.php");
    exit;
}










include 'templates/currencyConversion.tpl.php';
?>
