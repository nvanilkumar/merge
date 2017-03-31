<?php
	include_once("includes/application_top.php");
	include('includes/functions.php');
	include('includes/logincheck.php');
/**************************commented on 17082009 need to remove afterwords**************************	
	$select_events = "SELECT * FROM recommand_eve ORDER BY name ASC";
	$qry = mysql_query($select_events);
	
	$del_id = $_REQUEST['delid'];
	if(isset($del_id))
	{
		$del_record = "DELETE FROM recommand_eve WHERE id = '".$del_id."' ";
		mysql_query($del_record);
		header('location:recommended_events.php');
	}
****************************************************/	
	$current_page_content =	'recommended_events.tpl.php';
	include_once(_CURRENT_TEMPLATE_DIR.'main.tpl.php');
?>