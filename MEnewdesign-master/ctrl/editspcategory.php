<?php
	session_start();
		
	include_once("MT/cGlobali.php");
	
	$Global = new cGlobali();	
	 include 'loginchk.php';
	if($_POST['Submit'] == "Delete")
	{
		$delcategoryIds = $_POST['category'];
		foreach($delcategoryIds as $CId)
		{
			 $Id = $CId;
		
			try
			{	
				/* $category = new cCategories($Id);
				$Id = $category->Delete(); */
				$myQuery="update category set deleted = 1 WHERE id =".$Id;
				$res = $Global->ExecuteQuery($myQuery);
				if($res > 0)
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
		
	////////////////////////////////Query For All categories////////////////////////////////
	$categoryquery = "SELECT * FROM category where deleted = 0"; //using all -pH
	$categoryList = $Global->SelectQuery($categoryquery);
	////////////////////////////////

	include 'templates/editspcategory.tpl.php';
?>