<?php

session_start();

include 'loginchk.php';

include_once("MT/cGlobali.php");
include_once 'includes/common_functions.php';
include_once("MT/cCountries.php");

$globali = new cGlobali();
$commonFunctions = new functions();
$selectGateways="SELECT id,name,description,gatewaytext FROM paymentgateway WHERE status=1 AND deleted=0 AND type='gateway'";
$responseGateways=$globali->SelectQuery($selectGateways,MYSQLI_ASSOC);
$totalGateways=count($responseGateways);
include 'templates/add_gateways_text_tpl.php';
?>