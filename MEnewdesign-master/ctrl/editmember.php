<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Display the all Associations Membership Names.
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 24th Aug 2009
******************************************************************************************************************************************/
	
	session_start();
	
	include_once("MT/cGlobal.php");
	include_once("MT/cAssociations.php");
	 include 'loginchk.php';

	$Global = new cGlobal();	
	
	if($_POST['Submit'] == "Delete")
	{
		$delMembershipIds = $_POST['members'];
		foreach($delMembershipIds as $Id)		//get the membership id one by one to delete
		{
			try
			{	
				$objAssociation = new cAssociations($Id);
				$Id = $objAssociation->Delete();
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
	
	//Query For All Functions
	$AssociationsQuery = "SELECT * FROM associations ORDER BY Associations ASC"; //using all -pH
	$AssociationsList = $Global->SelectQuery($AssociationsQuery);
	
	include 'templates/editmember.tpl.php';
?>