<?php
	session_start();
	include 'loginchk.php';
	
	include_once("MT/cGlobal.php");
	include_once("MT/cECategories.php");
	
	$Global = new cGlobal();
	$MsgCountryExist = '';
	
	if($_POST['Submit'] == "Add")
	{	
		$category_name = $_POST['category_name'];
	    $category_name = trim($category_name);
		$DispOrder = $_POST[DispOrder];
		
		
		$CategoryQuery = "SELECT Id FROM categories WHERE Category='".$category_name."' ";
		$CategoryId = $Global->SelectQuery($CategoryQuery);
		if(count($CategoryId) > 0)
		{
			$MsgcategoryExist = 'Category Name already exist!';
		}
		else
		{
			try
			{	
				$Category = new cECategories(0, $category_name, $DispOrder);
				
				if($Category->Save())
				{
					header("location:editcategory.php");
				}
			}
			catch (Exception $Ex)
			{
				echo $Ex->getMessage();
			}
		}
	}

	include 'templates/addeventcategory.tpl.php';
?>