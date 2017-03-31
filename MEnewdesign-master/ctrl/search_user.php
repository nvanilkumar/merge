<?php
	include_once("includes/application_top.php");
	include('includes/functions.php');
	include('includes/logincheck.php');
	
	if($_POST['Submit'] == "Search"){
			
		$search_str = $_POST['search_str'];
 		$sql_search = "SELECT DISTINCT(profile_values.uid),users.*,user_types_type.name AS uname FROM profile_values,users,user_types_user,user_types_type WHERE users.uid=profile_values.uid AND (profile_values.value LIKE '%".$search_str."%' OR users.name LIKE '%".$search_str."%' OR users.mail LIKE '%".$search_str."%') AND user_types_user.uid=users.uid AND user_types_user.user_type_id=user_types_type.user_type_id";	
		
		$sql_search_res = mysql_query($sql_search) or die("Error in search : ".mysql_error());
			
	}
		
	$current_page_content	=	'search_user.tpl.php';
	include_once(_CURRENT_TEMPLATE_DIR.'main.tpl.php');
?>
