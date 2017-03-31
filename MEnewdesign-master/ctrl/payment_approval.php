<?php
	include_once("includes/application_top.php");
	include_once("includes/functions.php");
	include('includes/logincheck.php');
	include('includes/paginator.php');
	//global $user;
	//$uid=$user->uid;
	
	//if($user -> uid == '')
		//{ 
	//			header('Location: user/login');
		//}
/**************************commented on 17082009 need to remove afterwords**************************	
	$sql_trans = "SELECT transaction_history.*,users.name FROM transaction_history,payment_by_cheque,users WHERE transaction_history.invoice_no=payment_by_cheque.invoice_no AND users.uid=transaction_history.uid";
	
	if($_POST['Submit'] == "List"){
		
		if($_POST['view'] == "Successfull" || $_POST['view'] == "Failed" || $_POST['view'] == "Pending"){
			$sql_trans = "SELECT transaction_history.*,users.name FROM transaction_history,payment_by_cheque,users WHERE transaction_history.invoice_no=payment_by_cheque.invoice_no AND users.uid=transaction_history.uid AND transaction_history.status='".$_POST['view']."' ";
		}
	}//// END IF LIST
	
	if($_POST['Submit'] == "Search"){
		
		$sql_trans = "SELECT transaction_history.*,users.name FROM transaction_history,payment_by_cheque,users WHERE transaction_history.invoice_no=payment_by_cheque.invoice_no AND users.uid=transaction_history.uid";
		
		list($d,$m,$y) = explode("/",$_POST['date_from']);
		$from_date = $m."/".$d."/".$y;
		
		list($dd,$mm,$yy) = explode("/",$_POST['date_to']);
		$to_date = $mm."/".$dd."/".$yy;
		
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
			$sql_trans.=" AND transaction_history.cheque_no='".$_POST['cheque']."' "; 
		}
		
		if($_POST['date_from'] != ''){
			$sql_trans.=" AND transaction_history.date >='".$from_date."' "; 
		}
		
		if($_POST['date_to'] != ''){
			$sql_trans.=" AND transaction_history.date <='".$to_date."' "; 
		}
		
		
	}//// END IF SEARCH

	$sql_for_csv = $sql_trans;
	$sql_res = mysql_query($sql_trans) or die("Error :".mysql_error());
****************************************************/	
	/*$project_numbers = mysql_num_rows($sql_res);
	///////////// Code For Paging//////////////////////////
	$projectpage =& new Paginator($_GET['page'],$project_numbers);
	$projectpage->set_Limit(15); 
	$projectpage->set_Links(3);
	$limit1 = $projectpage->getRange1(); 
	$limit2 = $projectpage->getRange2();
	
	$sql_trans.= " LIMIT ".$limit1.",".$limit2;
	$sql_res = mysql_query($sql_trans) or die("Error :".mysql_error());
	*/
	//$sql_res = mysql_query($sql_trans) or die("Error :".mysql_error());
	$current_page_content = 'payment_approval.tpl.php';
	include_once(_CURRENT_TEMPLATE_DIR.'main.tpl.php');	
?>