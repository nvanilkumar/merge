<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Change the admin password - it confirm with the old password
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 24th Aug 2009
******************************************************************************************************************************************/

	session_start();
	include 'loginchk.php';
	header('Location:'._HTTP_SITE_ROOT.'/ctrl');
	include_once("MT/cGlobal.php");
	include_once("MT/cUser.php");
	
	$Global = new cGlobal();
	
	$msgChangePWD = '';
	
	$u_id = $_SESSION['uid'];
	$uname = $_SESSION['UserName'];
//	$upass = $_SESSION['login_pwd'];
	
	if($_POST['Change'] == 'Change')
	{
		$old_pass = $_POST['old_pass'];
		$new_pass = $_POST['new_pass'];
		$password = md5($_POST['new_pass']);

		$userQuery = "SELECT Id FROM user WHERE Id = '".$u_id."' AND Password = '".md5($old_pass)."'";
		$userId = $Global->SelectQuery($userQuery); //Returns the User Id if exist
		
		if($userId > 0)
		{
			/**************************commented on 17082009 need to change afterwords **************************/
			 $update_pass = "UPDATE user SET Password = '".$password."' WHERE Id = '".$u_id."' AND UserName = '".$uname."'";
            $REsUp = $Global->ExecuteQuery($update_pass);
			//mysql_query($update_pass);   
			/****************************************************/
			$msgChangePWD = "Your Password is sucessfully changed";
		}
		else
		{
			$msgChangePWD = "You have Entered the Wrong Old Password";
		}
	}

	include 'templates/change_pass.tpl.php';
?>