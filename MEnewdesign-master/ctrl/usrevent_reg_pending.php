<?php
	include_once("includes/application_top.php");
	include('includes/functions.php');
	include('includes/logincheck.php');
	
	$arr_pending = array('uid' => array(),'name' => array(),'details' => array(),'city' => array());
/**************************commented on 17082009 need to remove afterwords**************************
	$sele_pending_org = "SELECT transaction_history .*,users.name,registeredevents.city
						 FROM users, transaction_history, registeredevents
						 WHERE users.uid = transaction_history.uid
						 AND transaction_history.details LIKE 'Registration For :%'
						 AND transaction_history.status = 'Pending' 
						 AND registeredevents.r_id =  transaction_history.r_id;
						 ";
	
	$sql_pending_org = mysql_query($sele_pending_org);
	while($row_pending_org = mysql_fetch_array($sql_pending_org))
	{
	        $details = str_replace('Registration For :',' ',$row_pending_org['details']);
			array_push($arr_pending['uid'],$row_pending_org['uid']);
			array_push($arr_pending['name'],$row_pending_org['name']);
			array_push($arr_pending['details'],$details);
			array_push($arr_pending['city'],$row_pending_org['city']);
	}
****************************************************/	
	$current_page_content	=	'usrevent_reg_pending.tpl.php';
	include_once(_CURRENT_TEMPLATE_DIR.'main.tpl.php');
?>
