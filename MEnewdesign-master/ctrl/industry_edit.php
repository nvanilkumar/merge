<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Edit the Industry name
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 24th Aug 2009
******************************************************************************************************************************************/

	session_start();
	
	include_once("MT/cGlobal.php");
	include_once("MT/cIndustries.php");
	 include 'loginchk.php';

	$Global = new cGlobal();

	if($_POST['Submit'] == "Update")
	{
		$industry = $_POST['txtIndustry'];
		$industry = trim($industry); 
		$CategoryId= $_POST['CategoryId'];

//		$industry = strtoupper($industry);//strtolower($industry);
		
		// MAKE ALL FIRST LETTERS OF EACH WORD CAPITAL
//		$names = explode(" ",$industry);
//		foreach($names as $key => $val)
//		{
//			$words[] = ucfirst($val);
//		}
//		$industry = implode(" ",$words);
		
		$industry = str_replace("&","AND",$industry);
		
		$Id = $_POST['industry_id'];
		
		try
		{
			$objIndustries = new cIndustries($Id, $industry, $CategoryId);

			$Id = $objIndustries->Save();
	
			if($Id > 0)
			{
				header("location:editindustry.php");
			}
		}
		catch (Exception $Ex)
		{
			echo $Ex->getMessage();
		}	
	}
	else
	{
		$Id = $_GET['id'];
		
		//Query For Industry Details
//		$IndustryQuery = "SELECT * FROM industries WHERE Id = '".$Id."'";
                $IndustryQuery = "SELECT `Industries`,`CategoryId` FROM industries WHERE Id = '".$Id."'";
		$IndustryList = $Global->SelectQuery($IndustryQuery);
		
		for($i = 0; $i <  count($IndustryList); $i++)
		{
			$industry = $IndustryList[$i]['Industries'];
			$CategoryId= $IndustryList[$i]['CategoryId'];
		}
	}// END Get Industry Details
         $CategoriesQuery = "SELECT Id, Category FROM categories";
	      $CategoriesRES = $Global->SelectQuery($CategoriesQuery);

	include 'templates/industry_edit.tpl.php';
?>