<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	ADD PARTNERS
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 25th Aug 2009
******************************************************************************************************************************************/
	
	session_start();
	include 'loginchk.php';
	include_once("MT/cGlobal.php");
	include_once("MT/cUser.php");
	
	
	$Global = new cGlobal();
	$msgAddPartner = '';
	
	if($_POST['Submit'] == "Add")
	{
		include_once("MT/cPartners.php");
		
		//user fields
		$UserName = $_POST['p_user'];
		$Email = $_POST['p_email'];
		$Company = $_POST['p_cname'];
		$Salutation = $_POST['Salutation'];		
		$FirstName = $_POST['f_name'];
		$MiddleName = $_POST['m_name']; 
		$LastName = $_POST['l_name'];
		$Phone = $_POST['p_contact'];
		$Active = $_POST['p_status'];	
		
		//partners fields
		$Partners = $_POST['p_name'];
		$OfferDesc = $_POST['p_offer'];
		$URL = $_POST['p_url'];	

		
		try
		{	
			//$lPartnerId, $sPartner, $lUserId, $sURL. $sOfferDesc, $sUserName, $sEmail, $sCompany, $sSalutation, $sFirstName, $sMiddleName, $sLastName, $sPhone
			$objPartner = new cPartners(0, $Partners, 0, $URL, $OfferDesc, $UserName, $Email, $Company, $Salutation, $FirstName, $MiddleName, $LastName, $Phone);
			
			$PId = $objPartner->Save();
			
			header("location:editpartners.php");

		}
		catch (Exception $Ex)
		{
			echo $Ex->getMessage();
		}
	}// END IF Add
	
	include 'templates/addpartners.tpl.php';
?>