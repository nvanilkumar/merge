<?php
	include_once("includes/application_top.php");
	include('includes/functions.php');
	include('includes/logincheck.php');
	
	$tid = $_GET['id'];
	$sql_trigger_info = "SELECT * FROM sms_trigger WHERE tid=".$tid." ";
	$trigger = mysql_fetch_array(mysql_query($sql_trigger_info));
	
	if($trigger['status'] == 1){
		$checked = "checked";
	}
	
	if($_POST['Submit'] == "Submit"){
		$tid = $_POST['tid'];
		$message = mysql_escape_string($_POST['message']);
		
		if($_POST['enable'] == 1){
			$enable = 1;
		}else{
			$enable = 0;
		}
		
		 $sql_update = "UPDATE sms_trigger SET message='".$message."',
											  status='".$enable."'
										  WHERE 
										  	  tid = ".$tid." 
											";
	    mysql_query($sql_update) or die(mysql_error());
		header("location:sms_manage.php");
	}
	
	
	$current_page_content	=	'edit_sms.tpl.php';
	include_once(_CURRENT_TEMPLATE_DIR.'main.tpl.php');
?>