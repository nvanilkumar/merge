<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	It displays the list of Industries.
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
		include_once("MT/cIndustries.php");	//include the cIndustries MT
		$delIndustryIds = $_POST['industry'];		//get the list of industries in array of delIndustryIds
		
		foreach($delIndustryIds as $Id)			//get the Industry id one by one to delete
		{
			try
			{	
				$objIndustries = new cIndustries($Id);
				$Id = $objIndustries->Delete();
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
	}
	
	//Query For All Industries
	$IndustryQuery = "SELECT * FROM industries ORDER BY industries ASC"; //using all
	$IndustryList = $Global->SelectQuery($IndustryQuery);
	
	include 'templates/editindustry.tpl.php';
?>