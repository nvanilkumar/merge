<?php 
	session_start();
	/********commented on 20082009 might be need to remove or change****************************/
	//session_unregister("login_user_id");
	/************************************/
	
	/********commented on 20082009 might be need to remove or change****************************/
	//session_unregister("uid");
	//session_unregister("UserName");
	/************************************/
	
	session_destroy();
	
	header('Location:login.php');
	exit;
?>