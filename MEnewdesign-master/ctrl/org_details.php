<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	display organizer details
 *	
 *	Created / Updated on:
 *	1.	It displays organizer details - created on 16th Sep 2009
******************************************************************************************************************************************/
	
	session_start();
	$uid =	$_SESSION['uid'];
	
	include 'loginchk.php';
	
	include_once("MT/cGlobal.php");
	include_once("MT/cCities.php");	
	include_once("MT/cStates.php");	
	include_once("MT/cCountries.php");	
	include_once("MT/cOrganizer.php");
	include_once("MT/cDesignations.php");	
	
	$Global = new cGlobal();	

	$UserId = $_REQUEST['Id'];
	
	if(isset($_POST['Submit'])=='Submit')
	{
	//	$editEmailOrg = @new cOrganizer($UserId);
	//	$editEmailOrg->Load();
		
	//	$editEmailOrg->Email = $_POST['txtEmailId'];
	//	$editEmailOrg->Save();
		$txtEmailId = trim($_POST['txtEmailId']);
		
		$updateOrg = "UPDATE user SET Email='".$txtEmailId."' WHERE Id='".$UserId."'";
		$Global->ExecuteQuery($updateOrg);
	}
	
	
//	$OrgQuery = "SELECT org.*, u.*, ct.City, st.State FROM organizer AS org, user AS u, Cities AS ct, States AS st, Countries AS ctr WHERE org.UserId = '".$UserId."' AND u.Id = '".$UserId."' AND org.CityId = c.Id AND org.StateId = st.Id AND org.CountryId = ctr.Id";	 
//	$OrgDetails = $Global->SelectQuery($OrgQuery);
	
	$Organizer = new cOrganizer($UserId);
	$Organizer->Load();

	include 'templates/org_details.tpl.php';
?>