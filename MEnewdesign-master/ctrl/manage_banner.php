<?php

/* * ****************************************************************************************************************************************
 * 	File deatils:
 * 	Manage Banner
 * 	
 * 	Created / Updated on:
 * 	1.	Using the MT the file is updated on 14th Sep 2009
 * 	2.	Changes according to the new db design - updated on 15th Sep 2009
 * 		i. MT - cBanners 
 * 		ii. Table Name - Banners 
 * 		iii. Fields List -  Id, Title, FileName, URL, Active
 * 	3.	Changes according to the new field added in db for banner Sequence Number and Update Banner information - updated on 01 Oct 2009
 * 		i.	MT - cBanners
 * 		ii. manage_banner, manage_banner.tpl.php
 * 		iii. Field - SeqNo
 * **************************************************************************************************************************************** */

session_start();

include 'loginchk.php';

include_once("MT/cGlobali.php");
include_once("MT/cBanners.php");
//  include_once 'uploadToS3.php';
include_once 'includes/common_functions.php';
$commonFunctions = new functions();
include_once 'includes/resize.php';

$Global = new cGlobali();

$base_path = '/home/meraeven/public_html/content';
$msgActionStatus = '';

//Delete the banner
if (isset($_REQUEST['delete'])) {
    $Id = $_REQUEST['delete'];
    
    $deleteQry = "UPDATE banner SET deleted = 1, modifiedby = '".$_SESSION['uid']."' WHERE id = '".$Id."' ";
    $resDelete = $Global->ExecuteQuery($deleteQry);
    
    $deleteMappingQry = "UPDATE bannermapping SET deleted =1, modifiedby = '".$_SESSION['uid']."' WHERE bannerid = '".$Id."' ";
    $resMeppingDelete = $Global->ExecuteQuery($deleteMappingQry);
    if ($resMeppingDelete) {
        $msgActionStatus = "Banner Deleted Successfully.";
    }
}

//Add new banner
if (isset($_POST['Submit']) == 'Upload') {
	     	if(!empty($_REQUEST['EventId'])){
           $query="SELECT id FROM event WHERE id=".$_REQUEST['EventId']." and deleted=1";
           $output=$Global->SelectQuery($query);
           if(!$output){
	//echo "<pre>";print_r($_POST);print_r($_FILES);exit;
    $Main = $_REQUEST['frmType'];
    $bannerImageInsId = $bannerInsId =0;
    switch ($Main) {
        case "1":
            $imagType = "topbanner";
            break;
        case "2":
            $imagType = "bottombanner";
            break;
        default:
            $imagType = "banner";
    }
   
    if ($_FILES['fileBannerImage']['error'] == 0) {   //If file is attached.
        //resizing Image 
        $image = new SimpleImage();
        $resize['width'] = $resize['height'] = 0;
        if ($Main == "1") {//top
            $resize['width'] = 1170;
            $resize['height'] = 370;
        }
        else if ($Main == "2") {//Bottom
            $resize['width'] = 1170;
            $resize['height'] = 200;
        }else{//For others like holi and newyear images
            $resize['width'] = 1170;
            $resize['height'] = 370;
        }
        if (isset($_FILES) && !empty($_FILES['fileBannerImage']) && $_FILES['fileBannerImage']['error'] == 0) {
            $bannerImageInsId = $commonFunctions->fileUpload($Global, $_FILES['fileBannerImage'], array('png', 'jpg', 'jpeg', 'gif'), "images/banners", $imagType, $image, $resize);
           /// echo $bannerImageInsId;exit;
            if ($bannerImageInsId == false) {
                echo "Error in uploading.";
            }
        }
        
    }
    $sTitle = addslashes(trim($_POST['txtTitle']));
    $sEventId = $_POST['EventId'];

    $SDt = $_REQUEST['txtSDt'];
    $SDtExplode = explode("/", $SDt);
    $sStartDt = $SDtExplode[2] . '-' . $SDtExplode[1] . '-' . $SDtExplode[0] . ' 00:00:00';
    $sStartDt =$commonFunctions->convertTime($sStartDt, DEFAULT_TIMEZONE);

    $EDt = $_REQUEST['txtEDt'];
    $EDtExplode = explode("/", $EDt);
    $sEndDt = $EDtExplode[2] . '-' . $EDtExplode[1] . '-' . $EDtExplode[0] . ' 23:59:59';
    $sEndDt =$commonFunctions->convertTime($sEndDt, DEFAULT_TIMEZONE);

    $sURL = $_POST['txtURL'];
    $sActive = 1;
    $sSeqNo = $_POST['txtSeqNo'];
    $countryIdAdd = strlen($_POST['frmCountry']>0)?$_POST['frmCountry']:'14';
    $cityListAdd = isset($_REQUEST['frmCT'])?$_REQUEST['frmCT']:array('All');
    $categoryListAdd = isset($_REQUEST['frmCategory'])?$_REQUEST['frmCategory']:array('All');        
        
    $insertQry = "INSERT INTO `banner` ( `title`, `eventid`, `startdatetime`, `enddatetime`, `imagefileid`, "
                . "`url`, `status`, `order`, `type`) values "
            . "( '".$sTitle."', '".$sEventId."', '".$sStartDt."', '".$sEndDt."', '".$bannerImageInsId."',"
                . "'".$sURL."', '".$sActive."', '".$sSeqNo."', '".$Main."' )";
    //echo "<br><br><br>";
    $bannerInsId = $Global->ExecuteQueryId($insertQry);
    $allCategoryVal = 0;
    if (in_array('All', $categoryListAdd) ) {
        //echo "Yes";
        $allCategoryVal = 1;
    }
    $allCityVal = 0;
    if (in_array('All', $cityListAdd)  ) {
        //echo "Yes All city";
        $allCityVal = 1;
    }
    
    $otherCityVal = 0;
    if (in_array('Other', $cityListAdd)) {
        //echo "Yes Other city";
        $otherCityVal = 1;
    }
    /*$insertAllOtherQry = "INSERT INTO `bannermapping` "
                            . "(`bannerid`, `title`, `eventid`, `startdatetime`, `enddatetime`, `imagefileid`, `url`, `status`, `order`, "
                            . "`countryid`, `cityid`, `categoryid`, `allcategories`, `othercities`, `allcities`, `type`, `createdby`, `deleted`) "
                            . "VALUES "
                            . "('".$bannerInsId."', '".$sTitle."', '".$sEventId."', '".$sStartDt."', '".$sEndDt."', '".$bannerImageInsId."', '".$sURL."', '".$sActive."', '".$sSeqNo."', "
                            . "'".$countryIdAdd."','0','0','".$allCategoryVal."','".$otherCityVal."','".$allCityVal."','".$Main."', '".$_SESSION['uid']."', '0')";        
    $BannerAllOtherIns = $Global->ExecuteQuery($insertAllOtherQry);*/
    //echo "<br><br>";            

    foreach ($cityListAdd as $cityAdd) {
        if (($cityAdd == "All") or ($cityAdd=="Other")) {
            $cityAdd=0;
        }
            foreach ($categoryListAdd as $categoryAdd) {
                if ($categoryAdd == 'All') {
                    $categoryAdd=0;
                }
                    $insertCityCategory = "INSERT INTO `bannermapping` "
                            . "(`bannerid`, `title`, `eventid`, `startdatetime`, `enddatetime`, `imagefileid`, `url`, `status`, `order`, "
                            . "`countryid`, `cityid`, `allcities`, `othercities`, `categoryid`, `allcategories`, `type`, `createdby`, `deleted`) "
                            . "VALUES "
                            . "('".$bannerInsId."', '".$sTitle."', '".$sEventId."', '".$sStartDt."', '".$sEndDt."', '".$bannerImageInsId."', '".$sURL."', '".$sActive."', '".$sSeqNo."', "
                            . "'".$countryIdAdd."','".$cityAdd."','".$allCityVal."','".$otherCityVal."','".$categoryAdd."','".$allCategoryVal."','".$Main."', '".$_SESSION['uid']."', '0')";
                    $BannerIns = $Global->ExecuteQuery($insertCityCategory);
                    //echo "<br>";            
        
            }
        
    }
    if (isset ($BannerIns) && $BannerIns) {
        //successfully banner uploaded!
        $msgActionStatus = "New Banner Added Successfully.";
    }
        
    
}}}

//Change banner Status (Active / Inactive)
if ($_REQUEST['Edit'] == 'ChangeStatus') {//ChangeActive
    $Id = $_REQUEST['Id'];
    $sActive = $_REQUEST['Active'];
    
    $changeStatusQry = "UPDATE banner SET status = '".$sActive."', modifiedby = '".$_SESSION['uid']."' WHERE id = '".$Id."' ";
    $resChangeStatus = $Global->ExecuteQuery($changeStatusQry);
    
    if ($resChangeStatus) {
        $changeBMStatusQry = "UPDATE bannermapping SET status = '".$sActive."' WHERE bannerid = '".$Id."' ";
        $resChangeBMStatus = $Global->ExecuteQuery($changeBMStatusQry);
        if ($resChangeBMStatus) {
            $msgActionStatus = "Banner Status Changed.";
        }
    }
}

//Update Banner Information
//Query For All Banners List
$SCT = "";
$SCAT = "";
$active = '';

if (isset($_REQUEST['searchCountry']) && $_REQUEST['searchCountry'] != "") {
    $SCO = " AND bm.countryid = '" . $_REQUEST['searchCountry'] . "' ";
}
if (isset($_REQUEST['searchCT']) && $_REQUEST['searchCT'] != "") {
    if ($_REQUEST['searchCT'] == "All") {
        $SCT = " AND bm.allcities = 1 ";
    }
    else if ($_REQUEST['searchCT'] == "Other") {
        $SCT = " AND bm.othercities = 1 ";
    }
    else {
        $SCT = " AND bm.cityid = '" . $_REQUEST['searchCT'] . "' ";
    }
}
if (isset($_REQUEST['searchCA']) && $_REQUEST['searchCA'] != "") {
    
    if ($_REQUEST['searchCA'] == "All") {
        $SCT = " AND bm.allcategories = 1 ";
    }
    else {
        $SCAT = " AND bm.categoryid =  '" . $_REQUEST['searchCA'] . "' ";
    }
}

if (!empty($_GET['q']) && $_GET['q'] == 'past') {
    $active .=" AND b.enddatetime < NOW() ";
}
else {
    $active .=" AND b.enddatetime >= NOW() ";
}

 $BannerQuery = "SELECT b.id,b.title,b.eventid,b.imagefileid, b.order, "
        . "b.startdatetime,b.enddatetime,b.status,b.url, b.type,b.timezoneid, "
        . "bm.countryid,bm.cityid,bm.categoryid, bm.allcategories, "
        . "bm.othercities, bm.allcities,  f.path banner_path "
        . "FROM bannermapping bm JOIN banner b ON b.id = bm.bannerid "
        . "JOIN file f ON f.id = b.imagefileid "
        . "WHERE b.deleted = 0 AND bm.deleted=0"
        . " ".$SCO . $SCT . $SCAT . $active . " "
        . "GROUP BY b.id "
        . "ORDER BY b.id DESC"; //using 28/29
$BannerList = $Global->SelectQuery($BannerQuery);

$countrySql = "SELECT `id`, `name` "
        . "FROM country "
        . "WHERE featured = 1 AND deleted=0 "
        . "ORDER BY `name`";
$countryList = $Global->SelectQuery($countrySql);

if (isset($_REQUEST['searchCountry']) && $_REQUEST['searchCountry'] != "" && $_REQUEST['searchCountry'] != 0) {
    $cityQuery = "SELECT id, `name` "
            . "FROM city "
            . "WHERE countryid = '" . $_REQUEST['searchCountry'] . "' "
            . "AND (`name` NOT LIKE '%Other%' AND `name` NOT LIKE '%Not From%') "
            . "AND `featured`=1 "
            . "AND status = 1 AND deleted = 0 "
            . "ORDER BY `name`";
    $cityList=$Global->SelectQuery($cityQuery);
}

$CatSql = "SELECT `id`, `name` FROM `category` "
        . "WHERE deleted = 0 AND status =1 AND featured=1 "
        . "ORDER BY `order`";
$CatList = $Global->SelectQuery($CatSql);

function getCategories ($Global, $bannerId, $allCategories) {
    if ($bannerId != "") {
        $getCategoryQry = "SELECT c.name FROM category c JOIN bannermapping bm ON c.id=bm.categoryid WHERE bm.bannerid = '".$bannerId."' AND bm.deleted=0 GROUP BY c.id ";
        $resCategory = $Global->SelectQuery($getCategoryQry);
        $cateString = "";
        foreach ($resCategory as $category){
            $cateString .= $category['name'].", ";
        }
        $cateString=substr($cateString,0,-2);
        if ($allCategories == 1) {
            if ($cateString != "") {
               $cateString .= ", ";
            }
           $cateString .= "All Categories";
        }
        return $cateString;
    }
}

function getCities ($Global, $bannerId, $allCities, $otherCities) {
    if ($bannerId != "") {
        $getCategoryQry = "SELECT c.name FROM city c JOIN bannermapping bm ON c.id=bm.cityid WHERE bm.bannerid = '".$bannerId."' AND bm.deleted=0 GROUP BY c.id ";
        $resCategory = $Global->SelectQuery($getCategoryQry);
        $cityString = "";
        foreach ($resCategory as $category){
            $cityString .= $category['name'].", ";
        }
        $cityString=substr($cityString,0,-2);
        if ($allCities == 1) {
            if ($cityString != "") {
               $cityString .= ", ";
            }
           $cityString .= "All cities";
        }        
        if ($otherCities == 1) {
            if ($cityString != "") {
               $cityString .= ", ";
            }
           $cityString .= "Other cities";
        }
        return $cityString;
    }
}

if (!empty($_GET['q']) && $_GET['q'] == 'past')
    include 'templates/manage_banner_past.tpl.php';
else
    include 'templates/manage_banner.tpl.php';
?>