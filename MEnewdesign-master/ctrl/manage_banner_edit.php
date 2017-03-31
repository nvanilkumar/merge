<?php
/*
 * ****************************************************************************************************************************************
 * File deatils:
 * Edit Banner Information
 *
 * Created / Updated on:
 * 1.	Using the MT the file is updated on 01st Oct 2009
 *
 * ****************************************************************************************************************************************
 */
session_start ();

include 'loginchk.php';
$uid = $_SESSION ['uid'];

include_once ("MT/cGlobali.php");
include_once ("MT/cBanners.php");
// include_once 'uploadToS3.php';
include_once 'includes/common_functions.php';
$commonFunctions = new functions ();
include_once 'includes/resize.php';

$Global = new cGlobali ();

$base_path = '/home/meraeven/public_html/content';
$msgActionStatus = '';

$Id = $_REQUEST ['Id']; // Banner Id
$queryString = '';
if (! empty ( $_GET ['q'] ) && $_GET ['q'] == 'past')
	$queryString = 'q=past';
	// Update Banner Information
if(!empty($_REQUEST['EventId'])){
           $query="SELECT id FROM event WHERE id=".$_REQUEST['EventId']." and deleted=1";
           $output=$Global->SelectQuery($query);
           if($output){
               $EventId=$_REQUEST['EventId'];   
               $deletedEvent=1;
        }else{
            $deletedEvent=0;
        }       
}
if (($_REQUEST ['Update'] == 'UpdateBanner' && empty($_REQUEST['EventId']))||($_REQUEST ['Update'] == 'UpdateBanner' && $deletedEvent==0)) {
	$successResponce = true;
	$imageinsetion = "";
	$sActive = $_REQUEST ['Active'];
	// $bannerImageInsId = $bannerInsId =0;
	$Main = $_REQUEST ['frmType'];
	switch ($Main) {
		case "1" :
			$imagType = "topbanner";
			break;
		case "2" :
			$imagType = "bottombanner";
			break;
		default :
			$imagType = "banner";
	}
        if($Main!=$_POST['bannerType']){
            $image='urlImage';
        }
	if($_FILES['fileBannerImage']['error'] == 0 || (isset($image) && $image=='urlImage')) { // If file is attached.	
		$resize ['width'] = $resize ['height'] = 0;
		if ($Main == "1") { // top
                        $resize ['width'] = 1170;
			$resize ['height'] = 370;
		} else if ($Main == "2") { // Bottom
			$resize ['width'] = 1170;
			$resize ['height'] = 200;
		}else{//For others like holi and newyear images
                    $resize['width'] = 1170;
                    $resize['height'] = 370;
            }
             if (isset ( $_FILES ) && ! empty ( $_FILES ['fileBannerImage'] ) && $_FILES ['fileBannerImage'] ['error'] == 0) {//File upload
                 $image = new SimpleImage ();	
                    $bannerImageInsId = $commonFunctions->fileUpload ($Global, $_FILES ['fileBannerImage'], array (
					'png',
					'jpg',
					'jpeg',
					'gif' 
			), "images/banners", $imagType, $image, $resize);
                    if ($bannerImageInsId == false) {
			echo "Error in uploading.";
                    } 
		}else if($image=='urlImage'){//File upload from the url
                    $file["name"]=basename($_POST['imageFilePath']);
                    $file["tmp_name"]=$_POST['imageFilePath'];
                    $bannerImageInsId = $commonFunctions->fileUpload ($Global,$file, array ('png','jpg','jpeg','gif'), "images/banners", $imagType, $image, $resize);
                    if ($bannerImageInsId == false) {
			echo "Error in uploading.";
                    }                    
                }
	}
	$sTitle = addslashes ( trim ( $_POST ['txtTitle'] ) );
	$sEventId = $_POST ['EventId'];
	
	$SDt = $_REQUEST ['txtSDt'];
	$SDtExplode = explode ( "/", $SDt );
	$sStartDt = $SDtExplode [2] . '-' . $SDtExplode [1] . '-' . $SDtExplode [0] . ' 00:00:00';
        $sStartDt =$commonFunctions->convertTime($sStartDt, DEFAULT_TIMEZONE);
	
	$EDt = $_REQUEST ['txtEDt'];
	$EDtExplode = explode ( "/", $EDt );
	$sEndDt = $EDtExplode [2] . '-' . $EDtExplode [1] . '-' . $EDtExplode [0] . ' 23:59:59';
        $sEndDt =$commonFunctions->convertTime($sEndDt, DEFAULT_TIMEZONE);
	$sURL = $_POST ['txtURL'];
	$sActive = 1;
	$sSeqNo = $_POST ['txtSeqNo'];
	$countryIdAdd = strlen($_POST['frmCountry']>0)?$_POST['frmCountry']:'14';
	$cityListAdd = isset($_REQUEST['frmCT'])?$_REQUEST['frmCT']:array('All');
	$categoryListAdd = isset($_REQUEST['frmCategory'])?$_REQUEST['frmCategory']:array('All');
	
	
	if (isset ( $bannerImageInsId ) && $bannerImageInsId != 0)
		$imageinsetion = ", `imagefileid` = '" . $bannerImageInsId . "'";
	
	$insertQry = "UPDATE `banner` SET  `title` = '" . $sTitle . "', `eventid` = '" . $sEventId . "', " . "`startdatetime`  = '" . 
			$sStartDt . "', `enddatetime` = '" . $sEndDt . "' " . $imageinsetion . ", " . "`url` = '" . 
			$sURL . "', `status` = '" . $sActive . "', `order` = '" . $sSeqNo . "', `type` = '" . $Main . 
			"' WHERE id = '" . $Id . "' ";
	// echo "<br><br><br>";
	$bannerRes = $Global->ExecuteQuery ( $insertQry );
	
	if (!$bannerRes) {
		$successResponce = false;
	}
	if (in_array ( 'All', $categoryListAdd )) {
		// echo "Yes";
		$allCategoryVal = 1;
	} else {
		$allCategoryVal = 0;
	}
	if (in_array ( 'All', $cityListAdd )) {
		// echo "Yes All city";
		$allCityVal = 1;
	} else {
		$allCityVal = 0;
	}
	if (in_array ( 'Other', $cityListAdd )) {
		// echo "Yes Other city";
		$otherCityVal = 1;
	} else {
		$otherCityVal = 0;
	}
	
	// echo "<br><br>";
	$deleteMappingQry = "UPDATE bannermapping SET deleted =1, modifiedby = '" . $_SESSION ['uid'] . "' WHERE bannerid = '" . $Id . "' ";
	$resMeppingDelete = $Global->ExecuteQuery ( $deleteMappingQry );
	if (!isset ( $bannerImageInsId ) && $bannerImageInsId == 0) {
		$bannerImageInsId = $_REQUEST['imagefileid']; 
	}
	// echo "<br><br> This ALL OTHERr: ";
	/*$insertAllOtherQry = "INSERT INTO `bannermapping` " . "(`bannerid`, `title`, `eventid`, `startdatetime`, `enddatetime`, `imagefileid`, `url`, `status`, `order`, " 
				. "`countryid`, `cityid`, `categoryid`, `allcategories`, `othercities`, `allcities`, `type`, `createdby`, `deleted`) " 
				. "VALUES " 
				. "('" . $Id . "', '" . $sTitle . "', '" . $sEventId . "', '" . $sStartDt . "', '" . $sEndDt . "', '" 
				. $bannerImageInsId . "', '" . $sURL . "', '" . $sActive . "', '" . $sSeqNo . "', " . "'" 
				. $countryIdAdd . "','0','0','" . $allCategoryVal . "','" . $otherCityVal . "','" . $allCityVal . "','" . $Main 
				. "', '" . $_SESSION ['uid'] . "', '0')";
	$BannerAllOtherIns = $Global->ExecuteQuery ( $insertAllOtherQry );*/
	// echo "<br><br>";
	foreach ( $cityListAdd as $cityAdd ) {
		if (($cityAdd == "All") or ($cityAdd=="Other")) {
                    $cityAdd=0;
                }
                foreach ( $categoryListAdd as $categoryAdd ) {
				if ($categoryAdd == 'All') {
                                    $categoryAdd=0;
                                }
					$insertCityCategory = "INSERT INTO `bannermapping` " . "(`bannerid`, `title`, `eventid`, `startdatetime`, `enddatetime`, `imagefileid`, `url`, `status`, `order`, " . 
										"`countryid`, `cityid`, `categoryid`, `allcategories`, `othercities`, `allcities`, `type`, `createdby`, `deleted`) " . 
										"VALUES " . 
										"('" . $Id . "', '" . $sTitle . "', '" . $sEventId . "', '" . $sStartDt . 
											"', '" . $sEndDt . "', '" . $bannerImageInsId . "', '" . $sURL . "', '" . $sActive . 
											"', '" . $sSeqNo . "', " . "'" . $countryIdAdd . "','" . $cityAdd . "','" . $categoryAdd . 
											"','" . $allCategoryVal . "','" . $otherCityVal . "','" . $allCityVal . 
											"','" . $Main . "', '" . $_SESSION ['uid'] . "', '0')";
					$BannerInsCatCity = $Global->ExecuteQuery ( $insertCityCategory );
					// echo "<br><br>";
					if (! $BannerInsCatCity) {
						$successResponce = false;
					}
				
			}
		
	}
	if ($successResponce) {
		// echo "successfully banner uploaded!";
		$msgActionStatus = "Banner Updated Successfully.";
		?>
<!--script>
            window.location = "manage_banner.php?" +<?php echo $queryString; ?>;
        </script-->
<?php
}
}

// Query For All Banners List
$BannerQuery = "SELECT b.id, b.title, b.eventid, b.imagefileid, b.order, " . "b.startdatetime, b.enddatetime, b.status, b.url, b.type, b.timezoneid, b.status, " . "f.path banner_path " . "FROM banner b " . "JOIN file f ON f.id = b.imagefileid " . "WHERE b.id='" . $Id . "' AND b.deleted = 0 "; // using 28/29 -pH
$BannerList = $Global->SelectQuery ( $BannerQuery );

$bannerMapQry = "SELECT bannerid, countryid, cityid, categoryid, allcategories, allcities, othercities  " . "FROM bannermapping " . "WHERE bannerid = '" . $Id . "' AND deleted = 0";
$bannerMapRes = $Global->SelectQuery ( $bannerMapQry );

$categories = $cities = array ();
$allcities = $othercoties = $allcategories = false;
foreach ( $bannerMapRes as $banner ) {
	if ($banner ['categoryid'] != 0 && ! in_array ( $banner ['categoryid'], $categories )) {
		$categories [] = $banner ['categoryid'];
	}
	if ($banner ['cityid'] != 0 && ! in_array ( $banner ['cityid'], $cities )) {
		$cities [] = $banner ['cityid'];
	}
	if ($banner ['allcities'] == 1) {
		$allcities = true;
	}
	if ($banner ['othercities'] == 1) {
		$othercoties = true;
	}
	if ($banner ['allcategories'] == 1) {
		$allcategories = true;
	}
	if ($banner ['countryid'] != 0 && $banner ['countryid'] != "") {
		$countryId = $banner ['countryid'];
	}
}
/*
 * echo "<pre>";
 * print_r($categories);
 * print_r($cities);
 * var_dump($othercoties);
 * var_dump($allcities);
 * var_dump($allcategories);
 * var_dump($countryId);
 * echo "</pre>";
 */

$countrySql = "SELECT `id`, `name` " . "FROM country " . "WHERE featured = 1 " . "ORDER BY `name`";
$countryList = $Global->SelectQuery ( $countrySql );

if (isset ( $countryId ) && $countryId != "" && $countryId != 0) {
	$cityQuery = "SELECT id, `name` " . "FROM city " . "WHERE countryid = '" . $countryId . "' " . "AND (`name` NOT LIKE '%Other%' AND `name` NOT LIKE '%Not From%') " . "AND `featured`=1 " . "AND status = 1 AND deleted = 0 " . "ORDER BY `name`";
	$cityList = $Global->SelectQuery ( $cityQuery );
}

$CatSql = "SELECT `id`, `name` FROM `category` " . "WHERE deleted = 0 AND status =1 AND featured=1 " . "ORDER BY `order`";
$CatList = $Global->SelectQuery ( $CatSql );

include 'templates/manage_banner_edit.tpl.php';
?>