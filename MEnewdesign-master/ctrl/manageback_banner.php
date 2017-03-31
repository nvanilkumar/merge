<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Manage Banner
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 14th Sep 2009
 *	2.	Changes according to the new db design - updated on 15th Sep 2009
 *		i. MT - cBanners 
 *		ii. Table Name - Banners 
 *		iii. Fields List -  Id, Title, FileName, URL, Active
 *	3.	Changes according to the new field added in db for banner Sequence Number and Update Banner information - updated on 01 Oct 2009
 *		i.	MT - cBanners
 *		ii. manage_banner, manage_banner.tpl.php
 *		iii. Field - SeqNo
******************************************************************************************************************************************/
	
	session_start();
		
	 include 'loginchk.php';

	$uid =	$_SESSION['uid'];
	
	include_once("MT/cGlobal.php");
      //  include_once 'uploadToS3.php';
        include_once 'includes/common_functions.php';
   $commonFunctions=new functions();
	

	$Global = new cGlobal();

	$base_path = '/home/meraeven/public_html/content';		
	$msgActionStatus = '';
	
	//Delete the banner
	if(isset($_REQUEST['delete']))
	{
		$Id = $_REQUEST['delete'];
		
		try
		{	
			 $sqlup="delete from Backgroundimages where Id=".$Id ;           
			
		$res=$Global->ExecuteQuery($sqlup);
			if($res)
			{	
				//delete successful statement
				$msgActionStatus = "Banner Deleted Successfully.";
			}
		}
		catch (Exception $Ex)
		{
			echo $Ex->getMessage();
		}
	}
	
	//Add new banner
	if(isset($_POST['Submit']) == 'Upload')
	{
		if($_FILES['fileBannerImage']['error']==0)			//If file is attached.
		{
			$sFileName = $_FILES['fileBannerImage']['name'];
			move_uploaded_file($_FILES['fileBannerImage']['tmp_name'],_HTTP_Content."/background/".$_FILES['fileBannerImage']['name']);
                        //writing image paths to the  textfile (imageUploadInfo.txt) 
                        	    $uploadedImages=  fopen("../TextFiles/imageUploadInfo.txt", 'a' );
            fwrite($uploadedImages, _HTTP_Content."/background/".$_FILES['fileBannerImage']['name']."\r\n");//writing into text file all the image paths, just to know the count of them
            fclose($uploadeImages);
            
            //need to call optimization script  (optimizeimage.sh)
            $commonFunctions->optimizeAllImages(_OPTIMIZEPATH."optimizeimage.sh");              
                        
           /*****************************Uploading to S3******************************************/         
                     if ($_SERVER['HTTP_HOST'] != "localhost") {
                           $commonFunctions->uploadImageToS3(_HTTP_Content."/background/".$_FILES['fileBannerImage']['name'],_BUCKET);
//                        $modPath=  substr("../content/background/".$_FILES['fileBannerImage']['name'], 3);
//                $S3Sucess=uploadToS3("../content/background/".$_FILES['fileBannerImage']['name'], $modPath, _BUCKET);
//                  if (file_exists("../content/background/".$_FILES['fileBannerImage']['name']) && $S3Sucess) {
//                  //  unlink("../content/background/".$_FILES['fileBannerImage']['name']);
//                }
            }
                        
                        
                        
                        
	
                        $CategoryId=$_REQUEST[CategoryId];
			
			
	 $sqlup="insert into Backgroundimages (Image,CategoryId) values ('".$sFileName."','".$CategoryId."')" ;           
			
		$res=$Global->ExecuteQuery($sqlup);
			if($res)
			{
				//successfully banner uploaded!
				$msgActionStatus = "New Banner Added Successfully.";
			}
		}
	}
	
	
	
	//Query For All Banners List
	$categoryquery = "SELECT * FROM categories";
	$categoryList = $Global->SelectQuery($categoryquery);

	 $BannerQuery = "SELECT * FROM Backgroundimages";
	$BannerList = $Global->SelectQuery($BannerQuery);
	
	include 'templates/manageback_banner.tpl.php';
?>