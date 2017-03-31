<?php
	session_start();
	
	include_once("MT/cGlobal.php");
	include_once("MT/cECategories.php");
	 include 'loginchk.php';

	$Global = new cGlobal();
		
	if($_POST['Submit'] == "Update")
	{
		$category_name = $_POST['category_name'];
		$country_name = trim($category_name);
              $DispOrder = $_POST['DispOrder'];
		
		
		$Id = $_POST['category_id'];
		
		// UPDATE country master WITH UPDATED VALUE
		$UpdateCategory = new cECategories($Id, $country_name, $DispOrder);
		$Id = $UpdateCategory->Save();
		if($Id > 0)
		{
			header("location:editcategory.php");
		}
	}
	
	$CategoryId = $_GET['id'];
	
	 $CategoryQuery = "SELECT * FROM categories WHERE Id = '".$CategoryId."'";
	$EditCategory = $Global->SelectQuery($CategoryQuery);

	include 'templates/eventcategory_edit.tpl.php';
?>