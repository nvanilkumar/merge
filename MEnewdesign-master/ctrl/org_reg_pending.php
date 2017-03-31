<?php
	include_once("includes/application_top.php");
	include('includes/functions.php');
	include('includes/logincheck.php');
	
	$arr_pending = array('uid' => array(),'name' => array(),'city' => array(),'plan' => array());

/**************************commented on 17082009 need to remove afterwords**************************
	$sele_pending_org = 'SELECT u.uid,u.name,pv.value,us.user_subscription_type FROM users AS u,users_roles AS ur,profile_values AS pv,user_subscription AS us WHERE u.uid = ur.uid AND u.uid = pv.uid AND pv.fid = "42" AND ur.rid = "3" AND u.status = "0" AND us.uid = u.uid';
	$sql_pending_org = mysql_query($sele_pending_org);
	while($row_pending_org = mysql_fetch_array($sql_pending_org))
	{
			array_push($arr_pending['uid'],$row_pending_org['uid']);
			array_push($arr_pending['name'],$row_pending_org['name']);
			array_push($arr_pending['city'],$row_pending_org['value']);
			array_push($arr_pending['plan'],$row_pending_org['user_subscription_type']);
	}
****************************************************/
	$current_page_content	=	'org_reg_pending.tpl.php';
	include_once(_CURRENT_TEMPLATE_DIR.'main.tpl.php');
?>
