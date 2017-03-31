<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Edit the Designations name
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 24th Aug 2009
******************************************************************************************************************************************/

	session_start();
	include 'loginchk.php';
	include_once("MT/cGlobal.php");
	include_once("MT/cDesignations.php");
	
	$Global = new cGlobal();

	if($_POST['Submit'] == "Update")
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
	
		$Id = $_POST['d_id'];
		
		try
		{
			$Designations = new cDesignations($Id, $designation);

			$Id = $Designations->Save();
	
			if($Id > 0)
			{
				header("location:editdesignation.php");
			}
		}
		catch (Exception $Ex)
		{
			echo $Ex->getMessage();
		}
	}// END IF update
	else
	{
		$Id = $_GET['id'];
		
		//Query For Designation Details
//		$DesignationQuery = "SELECT * FROM Designations WHERE Id = '".$Id."'";
                $DesignationQuery = "SELECT `Designation` FROM Designations WHERE Id = '".$Id."'";
		$DesignationList = $Global->SelectQuery($DesignationQuery);
		
		for($i = 0; $i <  count($DesignationList); $i++)
		{
			$Designation = $DesignationList[$i]['Designation'];
		}
	}// END Get Designation Details

	include 'templates/designation_edit.tpl.php';
?>