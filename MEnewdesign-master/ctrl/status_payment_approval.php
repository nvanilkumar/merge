<?php
include_once("includes/application_top.php");
include_once("includes/functions.php");
include('includes/logincheck.php');

$task = $_GET['task'];
$tid = $_GET['tid'];
/**************************commented on 17082009 need to remove afterwords**************************
if($task == "success"){
	
	// Update transaction history
	$sql_success = "UPDATE transaction_history SET status='Successfull' WHERE tid=".$tid;
	mysql_query($sql_success) or die("Error :".mysql_error());

	///////////// GET INVOICE INFO AND MAIL IT TO USER
	$sql = "SELECT * FROM transaction_history WHERE tid=".$tid;
	$res = mysql_query($sql) or die("Error in select transction:".mysql_error());
	$row = mysql_fetch_array($res);
	$uid = $row['uid'];
	$invoice = $row['invoice_no'];
	
	$sql_get_invoice = "SELECT * FROM payment_by_cheque WHERE uid=".$uid." AND invoice_no='".$invoice."' ";
	$sql_res = mysql_query($sql_get_invoice) or die("Error IN SELECT invoice info :".mysql_error());
	$sql_row = mysql_fetch_array($sql_res);
	
	$to = $sql_row['email '];
	$subject = "Payment Succesfull : Invoice No-".$sql_row['invoice'];
	$message = '<table width="60%" align="center" cellpadding="3" cellspacing="3">
 
  <tr>
    <td colspan="3">M/S Phi Creative Solutions<br />
    6th Floor,<br>
    Bhakti Marg,<br>
    Mulund West,<br>
    Mumbai 200080<br>	</td>
  </tr>
  <tr>
    <td colspan="2"><strong>Performa Invoice No:</strong></td>
    <td>'. $sql_row[invoice_no].'</td>
  </tr>
  <tr>
    <td colspan="2"><strong>Dated:</strong></td>
    <td>'.$sql_row[dated].'</td>
  </tr>
  <tr>
    <td colspan="2"><strong>Cheque No:</strong></td>
    <td>'.$sql_row[cheque_no].'</td>
  </tr>
  <tr>
    <td colspan="2"><strong>Name &amp; Branch of Bank</strong> <strong>:</strong> </td>
    <td>'.$sql_row[name_bank].'</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Details</strong></td>
    <td><strong>Qty</strong></td>
    <td><strong>Amount</strong></td>
  </tr>
  <tr>
    <td>'.$sql_row[plan].'</td>
    <td>'.$sql_row[quantity].'</td>
    <td>'.$sql_row[total].'</td>
  </tr>
  <tr>
    <td>Discout </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Tax</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div align="right"><strong>Total</strong></div></td>
    <td>'.$sql_row[total].'</td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">All cheques to be made in favour of Big Bang India pvt. Ltd. and mailed to the following address</td>
  </tr>
  <tr>
    <td colspan="3">MeraEvents Pvt. Ltd.<br />
      703, Corporate Center,<br />
      Nirmal Lifestyle<br />
      Mulund West<br />
    Mumbai - 400080</td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">You can also directly deposit the cheque in ICICI Bank.<br />
    Account Number - 0494049204858</td>
  </tr>
</table>';

// To send HTML mail, the Content-type header must be set
     $headers  = 'MIME-Version: 1.0' . "\r\n";
     $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	 
	  $mail = mail($to, $subject, $message, $headers);

/////////////////////////////////////////////////////////////////////////////////////

}// END OF if success

if($task == "fail"){
	$sql_fail = "UPDATE transaction_history SET status='Failed' WHERE tid=".$tid;
	mysql_query($sql_fail) or die("Error :".mysql_error());
}

if($task == "pending"){
	$sql_pending = "UPDATE transaction_history SET status='Pending' WHERE tid=".$tid;
	mysql_query($sql_pending) or die("Error :".mysql_error());
}
****************************************************/
?>
<script type="text/javascript">
	<!--
	/**************************commented on 17082009 need to change and remove afterwords**************************
	window.location = 'http://admin.meraevents.com/payment_approval.php';-->
	*/
	window.location='payment_approval.php';
</script>