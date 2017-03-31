<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	EDIT PARTNER DETAILS
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 27th Aug 2009
******************************************************************************************************************************************/
	
	session_start();
	include 'loginchk.php';
	include_once("MT/cGlobal.php");
	include_once("MT/cUser.php");
	include_once("MT/cPartners.php");
	
	$Global = new cGlobal();
	
	if($_POST['Submit'] == "Update")
	{
		$Id = $_POST['Id'];
		$UserId = $_POST['UserId'];
		
		$UserName = $_POST['p_user'];
		$Partner = $_POST['p_name'];
		$Company = $_POST['p_cname'];
		$OfferDesc = $_POST['p_offer'];
		$Phone = $_POST['p_contact'];
		$EMail = $_POST['p_email'];
		$URL = $_POST['p_url'];
		$Active = $_POST['p_status'];
		
		$Salutation = $_POST['Salutation'];
		$FirstName = $_POST['f_name'];
		$MiddleName = $_POST['m_name']; 
		$LastName = $_POST['l_name'];

		

		$PartnerUQuery = "UPDATE partners SET Partners='".$Partner."', URL='".$URL."', OfferDesc='".$OfferDesc."' WHERE Id='".$Id."'";
		$pId = $Global->ExecuteQuery($PartnerUQuery);
		
		$UserUQuery = "UPDATE user SET UserName='".$UserName."', Company='".$Company."', Salutation='".$Salutation."', FirstName='".$FirstName."', MiddleName='".$MiddleName."', LastName='".$LastName."', Phone='".$Phone."', Email='".$EMail."', Active='".$Active."' WHERE Id='".$UserId."' and Id not in(1,2)";
		$uId = $Global->ExecuteQuery($UserUQuery);

		header("location:editpartners.php");
	}// END IF update
	
	$Id = $_GET['id'];
	
	$PartnerQuery = "SELECT p.*, u.UserName, u.Company, u.Salutation, u.FirstName, u.MiddleName, u.LastName, u.Email, u.Phone, u.Active FROM partners AS p, user AS u WHERE p.UserId = u.Id AND p.Id='".$Id."'";
	$PartnerList = $Global->SelectQuery($PartnerQuery);
	
	for($i = 0; $i < count($PartnerList); $i++)
	{
		$UserId = $PartnerList[$i]['UserId'];
		$p_user = $PartnerList[$i]['UserName'];
		$p_name = $PartnerList[$i]['Partners'];
		
		$salutation = $PartnerList[$i]['Salutation'];
		$f_name = $PartnerList[$i]['FirstName'];
		$m_name = $PartnerList[$i]['MiddleName']; 
		$l_name = $PartnerList[$i]['LastName'];
		
		$p_cname = $PartnerList[$i]['Company'];
		$p_offer = $PartnerList[$i]['OfferDesc'];
		$p_contact = $PartnerList[$i]['Phone'];
		$p_email = $PartnerList[$i]['Email'];
		$p_url = $PartnerList[$i]['URL'];
		$p_status = $PartnerList[$i]['Active'];
	}

	include 'templates/partner_edit.tpl.php';	
?>
