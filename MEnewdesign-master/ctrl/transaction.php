<?php
	include_once("includes/application_top.php");
	include_once("includes/functions.php");
	include('includes/logincheck.php');
	include('includes/paginator.php');

/**************************commented on 17082009 need to remove afterwords**************************	
	///////////// BUILD USER TYPES select list//////////////
	$sql_types = "SELECT * FROM user_types_type";
	$sql_res_types = mysql_query($sql_types) or die("error in usr type : ".mysql_error());
	////////////////////////////////////////////////////////
	
	
	$sql_trans = "SELECT users.name AS pname,transaction_history.*,user_types_user.user_type_id,user_types_type.name FROM transaction_history,payment_by_cheque,user_types_user,users,user_types_type WHERE transaction_history.invoice_no=payment_by_cheque.invoice_no AND user_types_user.uid=transaction_history.uid AND user_types_type.user_type_id = user_types_user.user_type_id AND users.uid = transaction_history.uid";

	
	
	if($_POST['Submit'] == "Export As CSV"){
	     
		$query = $_POST['sql_query'];
		$query = stripslashes($query);
		$rsSearchResults = mysql_query($query) or die("Error in export :".mysql_error());
		
		$out = 'Name,Transaction No,User_id,Date,Details,Invoice_no,Amount,Quantity,Status';
		$out .="\n";
		$columns = 9;
		while ($l = mysql_fetch_array($rsSearchResults)) {
		for ($i = 0; $i < $columns; $i++) {
		$out .='"'.$l["$i"].'",';
		}
		$out .="\n";
		}
		// Output to browser with appropriate mime type, you choose ;)
		header("Content-type: text/x-csv");
		//header("Content-type: text/csv");
		//header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=search_results.csv");
		echo $out;
		exit;
	}
	
	
	
	if($_POST['Submit'] == "Search"){
		
		//$sql_trans = "SELECT transaction_history.*,users.name AS pname,user_types_user.user_type_id FROM transaction_history,payment_by_cheque,users,user_types_user WHERE transaction_history.invoice_no=payment_by_cheque.invoice_no AND user_types_user.uid=transaction_history.uid AND users.uid = transaction_history.uid";
		
		
	$sql_trans = "SELECT users.name AS pname,transaction_history.*,user_types_user.user_type_id,user_types_type.name FROM transaction_history,payment_by_cheque,user_types_user,users,user_types_type WHERE transaction_history.invoice_no=payment_by_cheque.invoice_no AND user_types_user.uid=transaction_history.uid AND user_types_type.user_type_id = user_types_user.user_type_id AND users.uid = transaction_history.uid";
	
		list($d,$m,$y) = explode("/",$_POST['date_from']);
		$from_date = $y."-".$m."-".$d;
		
		list($dd,$mm,$yy) = explode("/",$_POST['date_to']);
		$to_date = $yy."-".$mm."-".$dd;
		
		if($_POST['name'] != ''){
			$sql_trans.=" AND users.name='".$_POST['name']."' "; 
		}
		
		if($_POST['email'] != ''){
			$sql_trans.=" AND payment_by_cheque.email='".$_POST['email']."' "; 
		}
		
		if($_POST['invoice_no'] != ''){
			$sql_trans.=" AND transaction_history.invoice_no='".$_POST['invoice_no']."' "; 
		}
		
		if($_POST['cheque'] != ''){
			$sql_trans.=" AND payment_by_cheque.cheque_no='".$_POST['cheque']."' "; 
		}
		
		if($_POST['date_from'] != ''){
			$sql_trans.=" AND transaction_history.date >='".$from_date."' "; 
		}
		
		if($_POST['date_to'] != ''){
			$sql_trans.=" AND transaction_history.date <='".$to_date."' "; 
		}
		
		if($_POST['view'] != "All"){
			$sql_trans.=" AND transaction_history.status='".$_POST['view']."' "; 
		}
		
		if($_POST['user_types'] != "All"){
			$sql_trans.=" AND user_types_user.user_type_id='".$_POST['user_types']."' "; 
		}
		if($_POST['eve_name'] != '')
		{
			$sql_trans.=" AND transaction_history.details  LIKE '%".$_POST['eve_name']."%' "; 
		}
		
	}//// END IF SEARCH
	
	$sql_for_csv = $sql_trans;
	$sql_res = mysql_query($sql_trans) or die("Error :".mysql_error());
****************************************************/
	
	
	$current_page_content = 'transaction.tpl.php'; 
	include_once(_CURRENT_TEMPLATE_DIR.'main.tpl.php');	
?>