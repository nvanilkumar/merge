<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	list of organizers
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 25 Aug 2009
 *	2.	Updated on 10th Oct 2009 - added new search field - city
******************************************************************************************************************************************/
session_start();

include_once("MT/cGlobali.php");
$Global = new cGlobali();
include 'loginchk.php';
//error_reporting(-1);
include_once 'includes/common_functions.php';
$commonFunctions = new functions();
include_once 'includes/resize.php';


//Rather than the city id we are selecting the city name here for uniqueness
/* 	$sqlOrg = "SELECT  u.id AS Id, u.company AS Company, u.email AS Email "
                . "FROM organizer AS org, user AS u "
                . "WHERE org.userid = u.id AND u.company!='' "
                . "AND org.deleted = 0 "
                . "ORDER BY u.company";	 */
                $sqlOrg = "SELECT  u.id AS Id, u.company AS Company, u.email AS Email "
                . "FROM organizer AS org, user AS u "
                . "WHERE org.userid = u.id "
                . "AND org.deleted = 0 "
                . "ORDER BY u.company";
	$dtlOrg = $Global->SelectQuery($sqlOrg);//FOR SELECT ORGANIZER, REQUIRED-Ph.
        //print_r($dtlOrg);
       
    
    //$sqlOrgdisp = "SELECT o.Id,o.orgDispName,o.Active FROM orgdispname AS o ORDER BY o.orgDispName ASC";
   // $dtlOrgdisp = $Global->SelectQuery($sqlOrgdisp);//NOT BEING USED ANYWHERE-Ph.
    
    
    if($_REQUEST['delete']!="")
    {
        // $sqldel="delete from organizationuser where Id=".$_REQUEST['delete'];
        $sqldel="UPDATE organizationuser SET deleted=1, modifiedby= '".$_SESSION['uid']."'  WHERE id=".$_REQUEST['delete'];
        $db_sqldel=$Global->ExecuteQuery($sqldel);    
        
    }
      //To Add the organization
      if($_REQUEST['Add'] && $_REQUEST['orgname'] !="")
      { 
          //To insert org banner
          $banner_db_path="";
          if(file_exists($_FILES['org_banner']['tmp_name']) || is_uploaded_file($_FILES['org_banner']['tmp_name'])) { //new banner image uploaded
             //$banner_db_path=insert_organizer_banner();
            $_FILES['fileBannerImage'] = $_FILES['org_banner'];
            $imagefolder = "organizerbanners";
            $imagType = "banner";
            $image = new SimpleImage();
            $resize['width'] = 1140;
            $resize['height'] = 330;
            $bannerImageInsId = $commonFunctions->fileUpload($Global, $_FILES['fileBannerImage'], array('png', 'jpg', 'jpeg', 'gif'), $imagefolder, $imagType, $image, $resize);
            //ECHO $bannerImageInsId; exit;
            if ($bannerImageInsId == false) {
                echo "Error in uploading.";
            }
          }
          //$banner_db_path=0;
          $description=addslashes($_REQUEST['org_description']);
         

           $sqlins="INSERT INTO organization (name,status,information, bannerpathid, createdby) values ('".$_REQUEST['orgname']."','".$_REQUEST['sts']."','".$description."','".$bannerImageInsId."', '". $_SESSION['uid'] ."')";
   
          $OrgId=$Global->ExecuteQueryId($sqlins); 
          //$OrgId= mysqli_insert_id($Global->dbconn);
       
          
    $n=count($_REQUEST["selOrg"]);
     for($i=0;$i<$n;$i++)
     {
        //$iorgid.=$_REQUEST[selOrg][$i].",";
         $sql="INSERT INTO organizationuser (organizationid,userid, createdby) values ('".$OrgId."','".$_REQUEST['selOrg'][$i]."', '". $_SESSION['uid'] ."')";
   
        $db_sql=$Global->ExecuteQuery($sql);                    
     }
    
    header('Location: manage_organizersnames.php');
    exit;
      
       
       
      } 
      //To Update the organization
if ($_REQUEST['Update'] && $_REQUEST['orgname'] != "") {
    //Edit image
    $description = addslashes($_REQUEST['org_description']); 
    $status = $_REQUEST['sts'];
    if (file_exists($_FILES['org_banner']['tmp_name']) || is_uploaded_file($_FILES['org_banner']['tmp_name'])) { //new banner image uploaded
       // $banner_db_path = insert_organizer_banner();
         $_FILES['fileBannerImage'] = $_FILES['org_banner'];
         $imagefolder = "organizerbanners";
         $imagType = "banner";
         $image = new SimpleImage();
         $resize['width'] = 1140;
         $resize['height'] = 330;
        $bannerImageInsId = $commonFunctions->fileUpload($Global, $_FILES['fileBannerImage'], array('png', 'jpg', 'jpeg', 'gif'), $imagefolder, $imagType, $image, $resize);
            //ECHO $bannerImageInsId; exit;
            if ($bannerImageInsId == false) {
                echo "Error in uploading.";
            }
        $sqlup = "update organization set name='" . $_REQUEST['orgname'] . "', information='" . $description . "', bannerpathid= ". $bannerImageInsId . ", status = " . $status ." where Id=".$_REQUEST['edit'];
        } else {
        $sqlup = "update organization set name='" . $_REQUEST['orgname'] . "', information='" . $description . "',status = '.$status.' where Id=".$_REQUEST['edit'];
    }

    $db_sqlup = $Global->ExecuteQuery($sqlup);

    $n = count($_REQUEST["selOrg"]);
    for ($i = 0; $i < $n; $i++) {

        $sql = "INSERT INTO organizationuser (organizationid,userid) values ('" . $_REQUEST['edit'] . "','" . $_REQUEST['selOrg'][$i] . "')";
        $db_sql = $Global->ExecuteQuery($sql);
    }
//$iorgid1=substr($iorgid,0,-1);
}

//To retrive the organiztion details  
if($_REQUEST['edit']!="") {//"edit" VLAUE COMES FROM THE PREVIOUS PAGE IN URL-Ph.
    $sqlOrgnameedit = "SELECT  name,status,information, bannerpathid FROM organization where id=".$_REQUEST['edit'];
    $dtlOrgnameedit = $Global->SelectQuery($sqlOrgnameedit);//FOR ORGANIZER DISPLAY NAME AND STATUS , REQUIRED-Ph.
    if($dtlOrgnameedit[0]['bannerpathid'] > 0){
	$sqlOrgnameeditBannerPath = " select path from file where id =".$dtlOrgnameedit[0]['bannerpathid'];
    $sqlOrgnameeditBannerPath = $Global->SelectQuery($sqlOrgnameeditBannerPath);    
    $dtlOrgnameedit[0]['bannerpath']=  isset($sqlOrgnameeditBannerPath[0]['path'])? str_replace('content/', '', $sqlOrgnameeditBannerPath[0]['path']):0;
	}else{
		$dtlOrgnameedit[0]['bannerpath']=" ";
	}
}
      
 //To Inser the organizer banner image     
// function insert_organizer_banner() {
//    $MaxOrgId = $_REQUEST['orgname'] . time();
//    $banner_db_path = _HTTP_Content . "/organizerbanners/" . $MaxOrgId . str_replace(" ", "", $_FILES['org_banner']['name']);
//    $banner_path = "../" . $banner_db_path;
//
//
//    $banner_path_file = "organizerbanners/" . $MaxOrgId . str_replace(" ", "", $_FILES['org_banner']['name']);
//    move_uploaded_file($_FILES['org_banner']['tmp_name'], $banner_path);
//
//
//    //writing image paths to the textfile (imageUploadInfo.txt)      
//    $uploadedImages = fopen("../" . "TextFiles/imageUploadInfo.txt", 'a');
//    fwrite($uploadedImages, $banner_path_file . "\r\n");
//    fclose($uploadedImages);
//
//    //upload to cloud front
//    include_once 'includes/common_functions.php';
//    $commonFunctions = new functions();
//    if ($_SERVER['HTTP_HOST'] != "localhost") {
//        $uploadToS3ErrorLogo = $commonFunctions->uploadImageToS3($banner_path, _BUCKET);
//        //trying uploading 2 more times if some problem occured while uploading the first time -pH
//        for ($i = 0; $i < 2; $i++) {
//            if (!empty($uploadToS3ErrorLogo)) {
//                $uploadToS3ErrorLogo = $commonFunctions->uploadImageToS3($banner_path, _BUCKET);
//            }
//        }
//    }
//    return $banner_db_path;
//}

include 'templates/manageorganisersnameadd.tpl.php';
?>