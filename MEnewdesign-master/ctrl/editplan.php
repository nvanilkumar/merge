<?php
	include_once("includes/application_top.php");
	include('includes/functions.php');
	include('includes/logincheck.php');
	
	if($_POST['Submit'] == "Apply"){
		$plan_name = $_POST['plan_name'];
		$type_note = $_POST['type_note'];
		$type_charge = $_POST['type_charge'];
		$reg_charge = $_POST['reg_charge'];
		$plan_id = $_POST['plan_id'];
		/**************************commented on 17082009 need to remove afterwords**************************
		$sql_update = "UPDATE subscription_types SET	 
													type_note='".$type_note."',
													registration_charges='".$reg_charge."',
													charges=".$type_charge."
												 WHERE
												 	sub_type_id=".$plan_id." 
													";
		mysql_query($sql_update) or die("Error in update plan:".mysql_error());
		****************************************************/
		header("location:manage_plan.php");
	}
	
	$id = $_GET['id'];
	
	/**************************commented on 17082009 need to remove afterwords**************************
	$sql_plan = "SELECT * FROM subscription_types WHERE sub_type_id=".$id;
	$sql_res = mysql_query($sql_plan) or die("Error in plan :".mysql_error());
	$sql_row = mysql_fetch_array($sql_res);
	****************************************************/
	$plan_name = "test plan";//$sql_row['type_name'];/**************************commented on 17082009 need to remove afterwords**************************
	$type_note = "test note";//$sql_row['type_note'];/**************************commented on 17082009 need to remove afterwords**************************
	$type_charge = "1";//$sql_row['charges'];/**************************commented on 17082009 need to remove afterwords**************************
	$reg_charges =  "1";//$sql_row['registration_charges'];/**************************commented on 17082009 need to remove afterwords**************************
	$plan_id = 1;//$sql_row['sub_type_id'];/**************************commented on 17082009 need to remove afterwords**************************
	
	$current_page_content	=	'editplan.tpl.php';
	include_once(_CURRENT_TEMPLATE_DIR.'main.tpl.php');
?>
