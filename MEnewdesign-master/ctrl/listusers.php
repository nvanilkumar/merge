<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	list user
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 25 Aug 2009
******************************************************************************************************************************************/
	
	session_start();
	include 'loginchk.php';
	
	include_once("MT/cGlobal.php");
	include_once("MT/cUser.php");
		include_once("MT/cCities.php");
	$Global = new cGlobal();	



	//------------------------------------------------------------------------------------------------------------------------------------------------
	
	if($_REQUEST['action'] != '')
	{
		$act = $_REQUEST['act_id'];

		if($_REQUEST['action'] == 'activate')
		{
			$update_user = "UPDATE user SET Active='1' WHERE Id='".$Id."'";
			$UserId = $Global->ExecuteQuery($update_user);
		}		
		if($_REQUEST['action'] == 'deactivate')
		{
			$update_user = "UPDATE user SET Active='0' WHERE Id='".$Id."'";
			$UserId = $Global->ExecuteQuery($update_user);
		}
	}
	else
	{
		$name = $_REQUEST['user'];
		$email = $_REQUEST['email'];
		$startdt = strtotime($_REQUEST['strt_date'].' 00:00:00');
		$enddt = strtotime($_REQUEST['end_date'].' 23:59:00');
		$stats = $_REQUEST['sts'];
		
		//Query For All Users
		$UsersQuery = "SELECT u.*, c.City FROM user AS u, Cities AS c WHERE u.CityId=c.Id";
		
		if($name!='') $UsersQuery .= " AND u.FirstName = '".$name."'";
		if($email!='') $UsersQuery .= " AND u.Email='".$email."'";
		if($stats!='-1') $UsersQuery .= " AND u.Active='".$stats."'";
		
		$UsersQuery .= " ORDER BY u.UserName ASC";
	//	$UsersQuery = "SELECT u.*, c.City FROM user AS u, Cities AS c WHERE u.FirstName = '".$name."' OR u.Email='".$email."' OR u.Active='".$stats."' OR u.CityId=c.Id ORDER BY u.UserName ASC";
		$UsersList = $Global->SelectQuery($UsersQuery);
	}
	
	include 'templates/listusers.tpl.php';
?>