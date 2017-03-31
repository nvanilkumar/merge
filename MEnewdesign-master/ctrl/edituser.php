<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Display list of users.
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 25th Aug 2009
******************************************************************************************************************************************/
	
	session_start();
	include 'loginchk.php';
	
	include_once("MT/cGlobal.php");
	include_once("MT/cUser.php");
	
	$Global = new cGlobal();
	
	if($_REQUEST['action'] != '')
	{
		$Id = $_REQUEST['act_id'];

		if($_REQUEST['action'] == 'activate')
		{
			$status = 1;
			/*try
			{	
				$objUser = new cUser($Id, $status);
				$Id = $objUser->Save();
				if($Id > 0)
				{	
					//delete successful statement
				}
			}
			catch (Exception $Ex)
			{
				echo $Ex->getMessage();
			}*/
			$update_user = "UPDATE user SET Active='1' WHERE Id='".$Id."'";
			$UserId = $Global->ExecuteQuery($update_user);

		}
		if($_REQUEST['action'] == 'deactivate')
		{
			$status = 0;
			/*try
			{	
				$objUser = new cUser($Id, $status);
				$Id = $objUser->Save();
				if($Id > 0)
				{	
					//delete successful statement
				}
			}
			catch (Exception $Ex)
			{
				echo $Ex->getMessage();
			}*/
			$update_user = "UPDATE user SET Active='0' WHERE Id='".$Id."'";
			$UserId = $Global->ExecuteQuery($update_user);
		}
		if($_REQUEST['action'] == 'delete')
		{
			$Id = $_REQUEST['delid'];
			$status = 0;
		/*	try
			{	
				$objUser = new cUser($Id, $status);
				$Id = $objUser->Save();
				if($Id > 0)
				{	
					//delete successful statement
				}
			}
			catch (Exception $Ex)
			{
				echo $Ex->getMessage();
			}*/
			$update_user = "UPDATE user SET Active='0' WHERE Id='".$Id."'";
			$UserId = $Global->ExecuteQuery($update_user);
		}
	}

	//Query For All Users
	$UsersQuery = "SELECT u.*, d.Designation FROM user AS u, Designations AS d WHERE u.DesignationId=d.Id ORDER BY u.UserName ASC";
	$UsersList = $Global->SelectQuery($UsersQuery);

	include 'templates/edituser.tpl.php';
?>