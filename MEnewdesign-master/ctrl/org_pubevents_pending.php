<?php
	include_once("includes/application_top.php");
	include('includes/functions.php');
	include('includes/logincheck.php');
	
	$arr_pending = array('uid' => array(),'name' => array(),'city' => array(),'events' => array());

/**************************commented on 17082009 need to remove afterwords**************************
	$sele_pending_org = "SELECT node.title, node.nid, users. * , content_type_add_event.field_event_city_value FROM node,users,content_type_add_event WHERE node.uid=users.uid AND node.published=1 AND node.approve=0 AND node.type='add_event' AND content_type_add_event.nid = node.nid";
	
	$sql_pending_org = mysql_query($sele_pending_org);
	while($row_pending_org = mysql_fetch_array($sql_pending_org))
	{
			array_push($arr_pending['uid'],$row_pending_org['uid']);
			array_push($arr_pending['name'],$row_pending_org['name']);
			array_push($arr_pending['city'],$row_pending_org['field_event_city_value']);
			array_push($arr_pending['events'],$row_pending_org['title']);
	}
****************************************************/
	$current_page_content	=	'org_pubevents_pending.tpl.php';
	include_once(_CURRENT_TEMPLATE_DIR.'main.tpl.php');
?>
