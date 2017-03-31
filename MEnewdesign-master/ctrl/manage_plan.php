<?php
	include_once("includes/application_top.php");
	include('includes/functions.php');
	include('includes/logincheck.php');

/**************************commented on 17082009 need to remove afterwords**************************	
	$sql_plans = "SELECT * FROM subscription_types";
	$sql_res_plans = mysql_query($sql_plans) or die("Error in sunbcription select :".mysql_query());
****************************************************/	
	
	$current_page_content	=	'manage_plan.tpl.php';
	include_once(_CURRENT_TEMPLATE_DIR.'main.tpl.php');
?>
