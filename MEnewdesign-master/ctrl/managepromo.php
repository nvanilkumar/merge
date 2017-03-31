<?php

		include_once("includes/application_top.php");

		include('includes/functions.php');
		include('includes/logincheck.php');
		

		

		

		$sql_event = "SELECT * FROM node WHERE type='add_event' ";

		$qry_event = mysql_query($sql_event);

		

		$sql_user_type = "SELECT * FROM user_types_type";

		$qry_user_type = mysql_query($sql_user_type);

		

		

		

		/*

		if($_REQUEST['next'])

		{

			$event_name = $_POST['selEvt'].'<br>';

			$user_type = $_POST['selUserType'];

			

			header('location:generate_promo.php?event_name='.$event_name.'&user_type='.$user_type.');

		}

		*/

		

		$current_page_content	=	'managepromo.tpl.php';

		include_once(_CURRENT_TEMPLATE_DIR.'main.tpl.php');

?>	   