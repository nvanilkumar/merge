<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Edit the User details.
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 25th Aug 2009
******************************************************************************************************************************************/
	
	session_start();
	include 'loginchk.php';
	include_once("MT/cGlobal.php");
	include_once("MT/cUser.php");
	include_once("MT/cDesignations.php");
	
	$Global = new cGlobal();
	
	if($_POST['save'] == 'SUBMIT')
	{
		$Id = $_REQUEST['id'];
		
		$email = $_REQUEST['txtemail'];
		$UserName = $_REQUEST['txtname'];
		$DesignationId = $_REQUEST['usrrole'];
		$status = $_REQUEST['status'];
		
                /*try
		{
			$objUsers = new cUser($Id, $UserName, $email, $DesignationId, $status);

		//	$lId, $sUserName,$sPassword, $sEmail, $sCompany, $sSalutation, $sFirstName, $sMiddleName, $sLastName, 
	//$sAddress, $sCountryId, $sStateId, $sCityId,  $sPIN, $sPhone, $sMobile, $sNewsletterSub, $sDesignationId, $sActive

			$Id = $objUsers->Save();
	
			if($Id > 0)
			{
				header("location:edituser.php");
			}
		}
		catch (Exception $Ex)
		{
			echo $Ex->getMessage();
		}*/
		$update_user = "UPDATE user SET UserName='".$UserName."', Email='".$email."', DesignationId='".$DesignationId."', Active='".$status."' WHERE Id='".$Id."' and Id not in(1,2)";
	//	mysql_query($update_user);
	
		$UserId = $Global->ExecuteQuery($update_user);
		if($UserId > 0)
			header("location:edituser.php");
	}
	else if($_REQUEST['id'] != '')
	{
		$Id = $_REQUEST['id'];
	
		//$UsersQuery = "SELECT * FROM user WHERE Id = '".$Id."'";
                $UsersQuery = "SELECT `UserName`,`Email`,`DesignationId`,`Active` FROM user WHERE Id = '".$Id."'";
		$UsersList = $Global->SelectQuery($UsersQuery);
		
		for($i = 0; $i < count($UsersList); $i++)
		{
			$UserName = $UsersList[$i]['UserName'];
			$Email = $UsersList[$i]['Email'];
			$Designation = $UsersList[$i]['DesignationId'];
			$status = $UsersList[$i]['Active'];
		}
	}

	$DesignationQuery = "SELECT * FROM Designations"; //using all -pH
	$DesignationList = $Global->SelectQuery($DesignationQuery);

	include 'templates/adduser.tpl.php';	
?>