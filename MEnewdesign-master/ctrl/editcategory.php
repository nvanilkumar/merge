<?php
	session_start();
		
	include_once("MT/cGlobal.php");
	 include 'loginchk.php';

	$Global = new cGlobal();	
	
	if($_POST['Submit'] == "Delete")
	{
		include_once("MT/cECategories.php");		
		$delcategoryIds = $_POST['category'];
		
		foreach($delcategoryIds as $CId)
		{
			$Id = $CId;
		
			try
			{	
				$category = new cECategories($Id);
				$Id = $category->Delete();
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
		
	////////////////////////////////Query For All Countries////////////////////////////////
//	$categoryquery = "SELECT * FROM categories";
        $categoryquery = "SELECT `Id`,`Category`,`DispOrder` FROM categories";
	$categoryList = $Global->SelectQuery($categoryquery);
	////////////////////////////////

	include 'templates/editcategory.tpl.php';
?>