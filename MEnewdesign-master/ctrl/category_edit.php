<?php

session_start();
include 'loginchk.php';

include_once("MT/cGlobali.php");
include_once("MT/cCategories.php");
include_once 'includes/common_functions.php';

$Global = new cGlobali();
$commonFunctions = new functions();

if ($_POST['Submit'] == "Update") {
    $inputArray = array();
    $category_name = $_POST['category_name'];
    $category_name = trim($category_name);
    $inputArray['country_name'] = $category_name;
    $order = $_POST['order'];
    $order = trim($order);
    $inputArray['order'] = $order;
    if (isset($_POST['featured']) && $_POST['featured'] == '1') {
        $inputArray['featured'] = 1;
    } else
        $inputArray['featured'] = 0;

    $status = $_POST['status'];
    $inputArray['status'] = $status;

    if (isset($_FILES['iconfile']) && !empty($_FILES['iconfile'])) {
        $iconId = $commonFunctions->fileUpload($Global, $_FILES['iconfile'], array('png', 'jpg', 'jpeg', 'gif'), "images/categoryicon", 'categoryicon');
        if ($iconId !== false) {
            $inputArray['iconfile'] = $iconId;
        } else
            $inputArray['iconfile'] = $_POST['iconid'] != "" ? $_POST['iconid'] : 0;
    } else
        $inputArray['iconfile'] = $_POST['iconid'] != "" ? $_POST['iconid'] : 0;
    
   
    if ( $_FILES['logofile']['error'] == 0 > 0 && !empty($_FILES['logofile'])) {
         
        $logoId = $commonFunctions->fileUpload($Global, $_FILES['logofile'], array('png', 'jpg', 'jpeg', 'gif'), "categorylogo", 'categorythumb');
        if ($logoId !== false) {
            $inputArray['logofile'] = $logoId;
        } else {
           
            $inputArray['logofile'] = $_POST['categoryFileId'] != "" ? $_POST['categoryFileId'] : 0;
        }
    } else {
       
        $inputArray['logofile'] = $_POST['categoryFileId'] != "" ? $_POST['categoryFileId'] : 0;
    }
    // default banner
      if ( $_FILES['defaultbanner']['error'] == 0 > 0 && !empty($_FILES['defaultbanner'])) {
         
        $bannerlogoId = $commonFunctions->fileUpload($Global, $_FILES['defaultbanner'], array('png', 'jpg', 'jpeg', 'gif'), "categorylogo", 'defaultbanner');
        if ($bannerlogoId !== false) {
            $inputArray['defaultbanner'] = $bannerlogoId;
        } else {
           
            $inputArray['defaultbanner'] = $_POST['defaultBannerFileId'] != "" ? $_POST['defaultBannerFileId'] : 0;
        }
    } else {
       
        $inputArray['defaultbanner'] = $_POST['defaultBannerFileId'] != "" ? $_POST['defaultBannerFileId'] : 0;
    }

// default thumbnail    
      if ( $_FILES['defaultthumbnail']['error'] == 0 > 0 && !empty($_FILES['defaultthumbnail'])) {
         
        $thumbnaillogoId = $commonFunctions->fileUpload($Global, $_FILES['defaultthumbnail'], array('png', 'jpg', 'jpeg', 'gif'), "categorylogo", 'defaultthumbnail');
        if ($thumbnaillogoId !== false) {
            $inputArray['defaultthumbnail'] = $thumbnaillogoId;
        } else {
           
            $inputArray['defaultthumbnail'] = $_POST['defaultthumbnailFileId'] != "" ? $_POST['defaultthumbnailFileId'] : 0;
        }
    } else {
       
        $inputArray['defaultthumbnail'] = $_POST['defaultthumbnailFileId'] != "" ? $_POST['defaultthumbnailFileId'] : 0;
    }
    $color = $_POST['color'];
    $inputArray['color'] = trim($color);


    $Id = $_POST['category_id'];
//    echo '<pre>';
//print_r($inputArray);
//exit;

    // UPDATE country master WITH UPDATED VALUE
    $UpdateCategory = new cCategories($Id, $inputArray);
    $Id = $UpdateCategory->Save();
    if ($Id > 0) {
        header("location:editspcategory.php");
    }
}

$CategoryId = $_GET['id'];

//	 $CategoryQuery = "SELECT * FROM category WHERE Id = '".$CategoryId."'";
$CategoryQuery = "SELECT * FROM category WHERE id = '" . $CategoryId . "'";
$EditCategory = $Global->SelectQuery($CategoryQuery);
if ($EditCategory[0]['iconimagefileid'] != "" && $EditCategory[0]['iconimagefileid'] != "0") {
    $categoryiconImageQuery = "SELECT *  FROM file WHERE id = '" . $EditCategory[0]['iconimagefileid'] . "'";
    $categoryiconimgres = $Global->SelectQuery($categoryiconImageQuery);
    $EditCategory[0]['iconimagefile'] = $categoryiconimgres[0]['path'];
}
if ($EditCategory[0]['imagefileid'] != "" && $EditCategory[0]['imagefileid'] != "0") {
    $categoryImageQuery = "SELECT *  FROM file WHERE id = '" . $EditCategory[0]['imagefileid'] . "'";
    $categoryimgres = $Global->SelectQuery($categoryImageQuery);
    $EditCategory[0]['imagefile'] = $categoryimgres[0]['path'];
}

if ($EditCategory[0]['categorydefaultbannerid'] != "" && $EditCategory[0]['categorydefaultbannerid'] != "0") {
    $categoryDefaultBannerQuery = "SELECT *  FROM file WHERE id = '" . $EditCategory[0]['categorydefaultbannerid'] . "'";
    $defaultBanner = $Global->SelectQuery($categoryDefaultBannerQuery);
    $EditCategory[0]['categorydefaultbanner'] = $defaultBanner[0]['path'];
}

if ($EditCategory[0]['categorydefaultthumbnailid'] != "" && $EditCategory[0]['categorydefaultthumbnailid'] != "0") {
    $categoryDefaultThumbnailQuery = "SELECT *  FROM file WHERE id = '" . $EditCategory[0]['categorydefaultthumbnailid'] . "'";
    $defaultThumbail = $Global->SelectQuery($categoryDefaultThumbnailQuery);
    $EditCategory[0]['categorydefaultthumbnail'] = $defaultThumbail[0]['path'];
}

include 'templates/category_edit.tpl.php';
?>