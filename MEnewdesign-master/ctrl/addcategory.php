<?php
	session_start();
	
	include 'loginchk.php';
	
	include_once("MT/cGlobali.php");
	include_once("MT/cCategories.php");
	include_once 'includes/common_functions.php';
	
	$Global = new cGlobali();
	$commonFunctions=new functions();
	$MsgCountryExist = '';
	
	if($_POST['Submit'] == "Add")
	{	
		$inputArray = array();
		$category_name = $_POST['category_name'];
	    $category_name = trim($category_name);
		$inputArray['country_name'] = $category_name;
		$order = $_POST['order'];
	    $order = trim($order);
		$inputArray['order'] = $order;
		if(isset($_POST['featured']) &&  $_POST['featured'] == '1') {
        $inputArray['featured'] = 1;
        }
        else
         $inputArray['featured'] = 0;
		
		$status = $_POST['status'];	
        $inputArray['status'] = $status;		
		
		if (isset($_FILES['iconfile'])) {
        $iconId = $commonFunctions->fileUpload($Global, $_FILES['iconfile'], array('png', 'jpg', 'jpeg', 'gif'), "categorylogo",'categoryicon');
        if ($iconId !== false) {
             $inputArray['iconfile'] = $iconId;
        }
        else
              $inputArray['iconfile'] = 0;
    }
    else
        $inputArray['iconfile'] = 0;
	if (isset($_FILES['logofile'])) {
        $logoId = $commonFunctions->fileUpload($Global, $_FILES['logofile'], array('png', 'jpg', 'jpeg', 'gif'), "categorylogo",'categorythumb');
        if ($logoId !== false) {
            $inputArray['logofile'] = $logoId;
        }
        else
              $inputArray['logofile'] = 0;
    }
    else
        $inputArray['logofile'] = 0;
    if (isset($_FILES['defaultbanner'])) {
        $bannerlogoId = $commonFunctions->fileUpload($Global, $_FILES['defaultbanner'], array('png', 'jpg', 'jpeg', 'gif'), "categorylogo",'defaultbanner');
        if ($bannerlogoId !== false) {
            $inputArray['defaultbanner'] = $bannerlogoId;
        }
        else
              $inputArray['defaultbanner'] = 0;
    }
    else
        $inputArray['defaultbanner'] = 0;
    
    if (isset($_FILES['defaultthumbnail'])) {
        $thumbnaillogoId = $commonFunctions->fileUpload($Global, $_FILES['defaultthumbnail'], array('png', 'jpg', 'jpeg', 'gif'), "categorylogo",'defaultthumbnail');
        if ($thumbnaillogoId !== false) {
            $inputArray['defaultthumbnail'] = $thumbnaillogoId;
        }
        else
              $inputArray['defaultthumbnail'] = 0;
    }
    else
        $inputArray['defaultthumbnail'] = 0;
	
	$color = $_POST['color'];	
	$inputArray['color'] = trim($color);
		
		
		 $CategoryQuery = "SELECT id FROM category WHERE name='".$category_name."' and deleted = 0 "; 
		$CategoryId = $Global->SelectQuery($CategoryQuery);
		if(count($CategoryId) > 0)
		{
			 $MsgcategoryExist = 'Category Name already exist!'; 
		}
		else
		{
			try
			{	
				$Category = new cCategories(0, $inputArray);
				
				if($Category->Save())
				{
					header("location:editspcategory.php");
				}
			}
			catch (Exception $Ex)
			{
				echo $Ex->getMessage();
			}
		}
	}

	include 'templates/addcategory.tpl.php';
?>