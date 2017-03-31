<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	It displays the designation list.
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 24th Aug 2009
******************************************************************************************************************************************/
	session_start();
		
	include_once("MT/cGlobal.php");
	
	$Global = new cGlobal();	
        include 'loginchk.php';
	
	if($_POST['Submit'] == "Delete")
	{
		include_once("MT/cDesignations.php");	//include the cDesignation MT
		$delDesignationIds = $_POST['desig'];		//get the list of designations in array of delDesignationIds
		
		foreach($delDesignationIds as $Id)			//get the designation id one by one to delete
		{
			try
			{	
				$Designations = new cDesignations($Id);
				$Id = $Designations->Delete();
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
	
	//Query For to bring the active Designation 
	$DesignationQuery = "SELECT * FROM Designations where status=1 ORDER BY Designation ASC"; //using all
	$DesignationList = $Global->SelectQuery($DesignationQuery);

	include 'templates/editdesignation.tpl.php';
?>