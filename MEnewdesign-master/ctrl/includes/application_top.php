<?php
	include_once('includes/session.php');
	include_once('includes/config.php');
	include_once("includes/dblogin_connect.php");
	include_once("includes/html_output.php");	

	define('_CURRENT_TEMPLATE_DIR',_TEMPLATE_DIR);	
	define('_CURRENT_TEMPLATE_URL',_HTTP_SITE_ROOT.'/templates/');		
	define('_IMAGES_TEMPLATE_URL',_HTTP_SITE_ROOT.'/images/');
	
	// end of config setting 
?>