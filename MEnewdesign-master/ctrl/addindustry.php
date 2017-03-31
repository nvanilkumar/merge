<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Add the new industry name
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 24th Aug 2009
******************************************************************************************************************************************/
	session_start();
	include 'loginchk.php';
		
	include_once("MT/cGlobal.php");
	
	$Global = new cGlobal();	

	$msgIndustryExist = '';

	if($_POST['Submit'] == "Add")
	{
		$industry = $_POST['txtIndustry'];
		$industry = trim($industry); 
//		$industry = strtoupper($industry);//strtolower($industry);
              $CategoryId= $_POST['CategoryId'];
		
//		$industry = ucfirst($industry);
		// MAKE ALL FIRST LETTERS OF EACH WORD CAPITAL
//		$names = explode(" ",$industry);
//		foreach($names as $key => $val)
//		{
//			$words[] = ucfirst($val);
//		}
//		$industry = implode(" ",$words);
				
		$industry = str_replace("&","AND",$industry);

		include_once("MT/cIndustries.php");		//include the cIndustries MT
		
		$IndustriesQuery = "SELECT Id FROM industries WHERE Industries = '".$industry."' and CategoryId=".$CategoryId;
		$indId = $Global->SelectQuery($IndustriesQuery); //Returns the Industry Id if exist

             

		
		if(count($indId) > 0)
		{
			$msgIndustryExist = 'Industry Name already exist!';
		}
		else
		{
			try
			{
				$objIndustries = new cIndustries(0, $industry, $CategoryId);
				
				if($objIndustries->Save())
				{
					header("location:editindustry.php");
				}
			}
			catch (Exception $Ex)
			{
				echo $Ex->getMessage();
			}	
		}		
	}
	      $CategoriesQuery = "SELECT Id, Category FROM categories";
	      $CategoriesRES = $Global->SelectQuery($CategoriesQuery);
	include 'templates/addindustry.tpl.php';	
?>