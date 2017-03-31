<?php
	session_start();
	include 'loginchk.php';
	
	include_once("MT/cGlobali.php");
	include_once("MT/cSubCategories.php");
	
	$Global = new cGlobali();
	
        if($_POST['Submit'] == "Update")
	{
		$category_name = $_POST['category_name'];
		$category_name = trim($category_name);		
		$Id = $_POST['subcategory_id'];
		$catid = $_POST['category_id'];
                $category_value = $_POST['category_value'];
                $category_value = trim($category_value);
                $SubCatStatus = $_POST['subcategory_status'];
                
		// UPDATE country master WITH UPDATED VALUE
		$UpdateCategory = new cSubCategories($Id, $catid, $category_name, $category_value, $SubCatStatus);
		$Id = $UpdateCategory->Save();
		if($Id > 0)
		{
			//header("location:editspsubcategory.php");
			?>
            <script type="text/javascript">
				window.location = "editspsubcategory.php";
			</script>
            <?php
		}
	}
	
	$SubCategoryId = $_GET['id'];
	
	$CategoryQuery = "SELECT a.id as Id, a.categoryid as CatId, a.name as SubCatName, a.value as SubCatValue,b.name as CatName, a.status AS subcatstatus  FROM subcategory a, category b WHERE a.id = '".$SubCategoryId."' AND b.id = a.categoryid";
	$EditCategory = $Global->SelectQuery($CategoryQuery);	
	

	include 'templates/subcategory_edit.tpl.php';
?>