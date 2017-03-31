<?php
include_once("includes/application_top.php");
include('includes/functions.php');
include('includes/logincheck.php');

if($_POST['Submit'] == "Update Details"){
	$sql_update = "UPDATE payment_by_cheque SET cheque_no='".$_POST['cheque_no']."',
												dated='".$_POST['dated']."',
												name_bank='".$_POST['name_bank']."'					
												 ";
	mysql_query($sql_update) or die("Error in update : ".mysql_error());
	header("Location:payment_approval.php");
}



$invoice = $_GET['invoice'];

$sql_trans = "SELECT *,payment_by_cheque.name FROM transaction_history,payment_by_cheque WHERE transaction_history.invoice_no=payment_by_cheque.invoice_no AND payment_by_cheque.invoice_no='".$invoice."' ";

$res = mysql_query($sql_trans) or die("Error in transaction:".mysql_error());
$row = mysql_fetch_array($res);

$current_page_content	=	'payment_details.tpl.php';
include_once(_CURRENT_TEMPLATE_DIR.'main.tpl.php');
?>

