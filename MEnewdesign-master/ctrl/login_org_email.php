<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Display Events of the Month
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 26th Aug 2009
 *	2.	Added the new filed IsFamous in db which is used to display the Famous Events on the front end.
 * 		The check box property checked shows the event is famous, visible on front end and vice versa.
******************************************************************************************************************************************/
	
	session_start();
	$uid =	$_SESSION['uid'];
	
 include 'loginchk.php';
	 include 'includes/common_functions.php';
	include_once("MT/cGlobali.php");
	
	$Global = new cGlobali();
	$commonFunctions=new functions();
       // echo $commonFunctions->getRedirectionUrl($Global);exit;
	if($_REQUEST[convert]=="yes" && $_REQUEST[conid]!="" && $_REQUEST[Email]!="")
	{
       $sqlselect="select id,address,countryid,stateid,cityid,pincode,mobile from user where email='".$_REQUEST[Email]."'";
	$Resselect = $Global->SelectQuery($sqlselect);

	$sqldel="update user set isattendee = 0 where id=".$Resselect[0][id];
	$Global->ExecuteQuery($sqldel);
		
	$sqlins="insert into organizer(userid,address,countryid,stateid,cityid,pincode,phone,email) values('".$Resselect[0][id]."','".$Resselect[0][address]."','".$Resselect[0][countryid]."','".$Resselect[0][stateid]."','".$Resselect[0][cityid]."','".$Resselect[0][pincode]."','".$Resselect[0][mobile]."','".$_REQUEST[Email]."')";
	$Global->ExecuteQuery($sqlins);
	}
	

	if($_REQUEST['submit'] == 'Submit')
	{
	    $Email=$_REQUEST['Email'];
		$OrgQuery = "SELECT u.id,u.email,u.password, u.name, u.username, u.signupdate FROM user u WHERE u.email='".$Email."'";
   		$ResOrgQuery = $Global->SelectQuery($OrgQuery);
		
		//$DelQuery = "SELECT u.Id, u.FirstName, u.LastName, u.UserName, u.RegnDt,u.auth_code FROM user u,delegate d WHERE u.Id=d.UserId and u.Email='".$Email."'";
   		//$ResDelQuery = $Global->SelectQuery($DelQuery);
	}

	
	include 'templates/login_org_email_tpl.php';	
?>
