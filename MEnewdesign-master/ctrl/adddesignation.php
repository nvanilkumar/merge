<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Add the designation
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 24th Aug 2009
 *	2.	Removed strtolower then ucfirst and ucfirst foreach loop
******************************************************************************************************************************************/
	session_start();
	
	include 'loginchk.php';
		
	include_once("MT/cGlobal.php");
	
	$Global = new cGlobal();	

	$msgDesignationExist = '';

	if($_POST['Submit'] == "Add")
	{
		$designation = $_POST['designation'];
		$designation = trim($designation); 
//		$designation = strtolower($designation);
//		$designation = ucfirst($designation);
		
		// MAKE ALL FIRST LETTERS OF EACH WORD CAPITAL
//		$names = explode(" ",$designation);
//		foreach($names as $key => $val)
//		{
//			$words[] = ucfirst($val);
//		}
//		$designation = implode(" ",$words);
		
		
		include_once("MT/cDesignations.php");		//include the cDesignation MT
		
		$designationQuery = "SELECT Id FROM Designations WHERE Designation = '".$designation."'";
		$desId = $Global->SelectQuery($designationQuery); //Returns the Designation Id if exist
		
		if(count($desId) > 0)
		{
			$msgDesignationExist = 'Designation already exist!';
		}
		else
		{
			try
			{
				$Designations = new cDesignations(0, $designation);
				
				if($Designations->Save())
				{
					header("location:editdesignation.php");
				}
			}
			catch (Exception $Ex)
			{
				echo $Ex->getMessage();
			}	
		}
	}// ENDS Add New Designation	
	
	include 'templates/adddesignation.tpl.php';
?>