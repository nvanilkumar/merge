<?php 
$DBServerName = "mestgdbv2.ckqsu4xn3xb3.us-east-1.rds.amazonaws.com:56456"; //"localhost";
	$DBUserName = "Mstgv2"; //"root";
	$DBPassword = "hMjK1P8#$"; //"root";
	$DBIniCatalog = "MeStgV2"; //"meraevents";
        date_default_timezone_set('Asia/Calcutta');
        if ($_SERVER['HTTP_HOST'] != "localhost") {
    define('_DOC_ROOT', $_SERVER["DOCUMENT_ROOT"]);
} else {
    define('_DOC_ROOT', $_SERVER["DOCUMENT_ROOT"]."/master/Meraevents");
}

error_reporting(0);
?>
