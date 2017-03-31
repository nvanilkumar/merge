<?php
	session_start();
	include 'loginchk.php';
	
	include_once("MT/cGlobali.php");
	include_once("MT/cSubCategories.php");
	
	$Global = new cGlobali();
	$MsgCountryExist = '';
	
	if($_POST['Submit'] == "Add")
	{	
		$subcategory_name = $_POST['subcategory_name'];
	    $subcategory_name = trim($subcategory_name);
		
		
		$id = $_POST['category_name'];
		
		$CategoryQuery = "SELECT id FROM subcategory WHERE name='".$subcategory_name."' AND categoryid=".$id."";
		$CategoryId = $Global->SelectQuery($CategoryQuery);
		if(count($CategoryId) > 0)
		{
			$MsgcategoryExist = 'Sub-Category Name already exist!';
		}
		else
		{
			try
			{	
				$Category = new cSubCategories(0, $id, $subcategory_name);
				
				if($Category->Save())
				{
					header("location:editspsubcategory.php");
				}
			}
			catch (Exception $Ex)
			{
				echo $Ex->getMessage();
			}
		}
	}
	
	$cSql = "SELECT * FROM category";
	$editCat = $Global->selectQuery($cSql); //using All -pH

	include 'templates/addsubcategory.tpl.php';
?>