<?php
	include_once("includes/application_top.php");
	include('includes/functions.php');
	include('includes/logincheck.php');
	
	$msg = "";
	
	if($_POST['Submit'] == "Add"){
		$plan_name = $_POST['plan_name'];
		$type_note = $_POST['type_note'];
		$type_charge = $_POST['type_charge'];
		
		
		$sql_insert = "INSERT INTO subscription_types SET	 
													type_name='".$plan_name."',
													type_note='".$type_note."',
													type_charge='".$type_charge."'
													";
		mysql_query($sql_insert) or die("Error in update plan:".mysql_error());
		$msg = "Plan Added Successfully";
	}
	
	
	
	$current_page_content	=	'addplan.tpl.php';
	include_once(_CURRENT_TEMPLATE_DIR.'main.tpl.php');
?>
