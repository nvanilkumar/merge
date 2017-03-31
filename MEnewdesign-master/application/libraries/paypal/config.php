<?php
//start session in all pages
if (session_status() == PHP_SESSION_NONE) { session_start(); } //PHP >= 5.4.0
$PayPalMode = 'sandbox'; // sandbox or live

$hostname = strtolower($_SERVER['HTTP_HOST']);
if(strcmp($hostname,'www.meraevents.com')==0 || strcmp($hostname,'meraevents.com')==0 || strcmp($hostname, "dhamaal.meraevents.com")==0 || strcmp($hostname,'http://mndprodlb-1993234352.us-west-1.elb.amazonaws.com')==0 || strcmp($hostname,'mndprodlb-1993234352.us-west-1.elb.amazonaws.com')==0)
{
    $PayPalMode = 'live'; // sandbox or live
}

$PayPalCurrencyCode 	= 'USD'; //Paypal Currency Code
$PayPalReturnURL 		= commonHelperGetPageUrl('payment_paypalProcessingPage'); //Point to process.php page
$PayPalCancelURL 		= commonHelperGetPageUrl('payment_paypalProcessingPage'); //Cancel URL if user clicks cancel
?>
