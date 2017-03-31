<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	DISPLAY ALL PARTNERS LIST.
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 25th Aug 2009
******************************************************************************************************************************************/
	
	session_start();
	
	include_once("MT/cGlobal.php");
	include_once("MT/cUser.php");
	
 include 'loginchk.php';

	$Global = new cGlobal();

	if($_POST['Submit'] == "Delete")
	{
		include_once("MT/cPartners.php");
		
		$delPartnerIds = $_POST['chkpartners'];
		foreach($delPartnerIds as $Id)
		{
			try
			{	
				$objPartners = new cPartners($Id);
				$Id = $objPartners->Delete();
				if($Id > 0)
				{	
					//delete successful statement
				}
			}
			catch (Exception $Ex)
			{
				echo $Ex->getMessage();
			}
		}
	}// END IF delete	

	//Query For All Partners
	//$PartnerQuery = "SELECT po.*, u.Company, u.UserName FROM partneroffers AS po, user AS u WHERE po.UserId = u.Id ORDER BY u.UserName ASC";
	$PartnerQuery = "SELECT p.*, u.Company, u.UserName FROM partners AS p, user AS u WHERE p.UserId = u.Id ORDER BY p.Partners ASC";
	$PartnerList = $Global->SelectQuery($PartnerQuery);

	include 'templates/editpartners.tpl.php';
?>