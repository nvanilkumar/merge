<?php
	session_start();
	include 'loginchk.php';	
	include_once("MT/cGlobali.php");
	
	$Global = new cGlobali();	
	
	if($_POST['Submit'] == "Delete")
	{
		include_once("MT/cSubCategories.php");		
		$delsubcategoryIds = $_POST['subcategory'];
		
		foreach($delsubcategoryIds as $CId)
		{
			$Id = $CId;
		
			try
			{	
				$subcategory = new cSubCategories($Id);
				$Id = $subcategory->Delete();
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
        $categoryQuery = "SELECT * FROM `category` WHERE `deleted` = 0 AND `status` = 1 ORDER BY `name` ASC";
        $categoryList = $Global->SelectQuery($categoryQuery);
        if (isset($_GET['category']) && $_GET['category'] != "" && $_GET['category'] != 0) {
            $categoryId = $_GET['category'];    
        }
        else {
            $categoryId = $categoryList[0]['id'];
        }
        $categoryName = getCategoryName($Global, $categoryId );
        
	$subcategoryquery = "SELECT id as Id, categoryid as CatId, name as SCatName, `status` as subcatstatus FROM subcategory WHERE `deleted` = 0 AND `categoryid` = ".$categoryId." ORDER BY  `name` ";
	$subcategoryList = $Global->SelectQuery($subcategoryquery);
	////////////////////////////////
        

    function getCategoryName ($Global, $id) {
        $query = "SELECT name,id FROM `category` WHERE `deleted` = 0 AND `status` =1 AND `id` = ".$id." ORDER BY `name` ASC";
        $countryName = $Global->SelectQuery($query);
        if (is_array($countryName)) {
            return $countryName[0]['name'];
        }
    }
        
	include 'templates/editspsubcategory.tpl.php';
?>