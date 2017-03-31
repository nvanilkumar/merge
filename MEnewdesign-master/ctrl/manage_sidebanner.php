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
	 include_once("MT/cSideBanners.php");
         //include_once 'uploadToS3.php';
           include_once 'includes/common_functions.php';
   $commonFunctions=new functions();
   include_once '../resize.php';

	$Global = new cGlobal();

	$base_path = '/home/meraeven/public_html/content';		
	$msgActionStatus = '';
	
	//Delete the banner
	if(isset($_REQUEST['delete']))
	{
		$Id = $_REQUEST['delete'];
		
		try
		{	
			$objBanner = new cSideBanners($Id);
			if($objBanner->Delete())
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
			move_uploaded_file($_FILES['fileBannerImage']['tmp_name'],"../"._HTTP_Content."/images/addbanner/".$_FILES['fileBannerImage']['name']);
                         //writing image paths to the  textfile (imageUploadInfo.txt) 
                        	    $uploadedImages=  fopen("../TextFiles/imageUploadInfo.txt", 'a' );
            fwrite($uploadedImages, "images/addbanner/".$_FILES['fileBannerImage']['name']."\r\n");//writing into text file all the image paths, just to know the count of them
            fclose($uploadeImages);
            
                             //resizing Image -Phinny
            $image = new SimpleImage();
            $image->load("../"._HTTP_Content."/images/addbanner/".$_FILES['fileBannerImage']['name']);
            //$image->resize(250,250);
            $image->save("../"._HTTP_Content."/images/addbanner/".$_FILES['fileBannerImage']['name']);
            
           //need to call optimization script (optimizeimage.sh)
         $OptimizeImageScriptError=   $commonFunctions->optimizeAllImages(_OPTIMIZEPATH."optimizeimage.sh");
                        
                        
             /*****************************Uploading to S3******************************************/         
                     if ($_SERVER['HTTP_HOST'] != "localhost") {
                   $uploadToS3Error=      $commonFunctions->uploadImageToS3("../"._HTTP_Content."/images/addbanner/".$_FILES['fileBannerImage']['name'],_BUCKET);
//                        $modPath=  substr("../"._HTTP_Content."/images/addbanner/".$_FILES['fileBannerImage']['name'], 3);
//                $S3Sucess=uploadToS3("../"._HTTP_Content."/images/addbanner/".$_FILES['fileBannerImage']['name'], $modPath, _BUCKET);
//                  if (file_exists("../"._HTTP_Content."/images/addbanner/".$_FILES['fileBannerImage']['name']) && $S3Sucess) {
//                   // unlink("../"._HTTP_Content."/images/addbanner/".$_FILES['fileBannerImage']['name']);
//                }
            }
                        
                        
                        
                        
	
		
			
		$SDt = $_REQUEST['txtSDt'];
		$SDtExplode = explode("/", $SDt);
		$sStartDt = $SDtExplode[2].'-'.$SDtExplode[1].'-'.$SDtExplode[0].' 00:00:00';
		
		$EDt = $_REQUEST['txtEDt'];
		$EDtExplode = explode("/", $EDt);
		$sEndDt = $EDtExplode[2].'-'.$EDtExplode[1].'-'.$EDtExplode[0].' 23:59:59';
                
				   
			$sFileName = "/images/addbanner/".$sFileName;		
			$sURL = $_POST['txtURL'];
			$sActive = 1;
			$sSeqNo = $_POST['txtSeqNo'];
			if(isset($_REQUEST['Main']))
        { 
            $Main = 1; 
        }
        else
        {    
            $Main = 0;     
        }
			if(isset($_REQUEST['Banglore']))
        { 
            $Banglore = 1; 
        }
        else
        {    
            $Banglore = 0;     
        }
		if(isset($_REQUEST['Chennai']))
        { 
            $Chennai = 1; 
        }
        else
        {    
            $Chennai = 0;     
        }
       if(isset($_REQUEST['Delhi']))
        { 
            $Delhi = 1; 
        }
        else
        {    
            $Delhi = 0;     
        }
		  if(isset($_REQUEST['Hyderabad']))
        { 
            $Hyderabad = 1; 
        }
        else
        {    
            $Hyderabad = 0;     
        }
		  if(isset($_REQUEST['Mumbai']))
        { 
            $Mumbai = 1; 
        }
        else
        {    
            $Mumbai = 0;     
        }
		 if(isset($_REQUEST['Pune']))
        { 
            $Pune = 1; 
        }
        else
        {    
            $Pune = 0;     
        }
		 if(isset($_REQUEST['Kolkata']))
        { 
            $Kolkata = 1; 
        }
        else
        {    
            $Kolkata = 0;     
        }
        if(isset($_REQUEST['Goa']))
        { 
            $Goa = 1; 
        }
        else
        {    
            $Goa = 0;     
        }
		if(isset($_REQUEST['Ahmedabad']))
        { 
            $Ahmedabad= 1;  
        }
        else
        {    
            $Ahmedabad = 0;     
        }
		if(isset($_REQUEST['Jaipur']))
        { 
            $Jaipur = 1; 
        }
        else
        {    
            $Jaipur = 0;     
        }
		 if(isset($_REQUEST['AllCities']))
        { 
            $AllCities = 1; 
        }
        else
        {    
            $AllCities = 0;     
        }
		 if(isset($_REQUEST['OtherCities']))
        { 
            $OtherCities = 1; 
        }
        else
        {    
            $OtherCities = 0;     
        }
         $CategoryId=0;
                    
			
			$clickscount= 0;
			
			$objBanner = new cSideBanners(0,$sStartDt, $sEndDt, $sFileName, $sURL, $sActive, $sSeqNo, $Main, $Banglore,$Chennai,$Delhi, $Hyderabad ,$Mumbai,$Pune,$Kolkata,$Jaipur,$Goa,$Ahmedabad,$AllCities,$OtherCities,$CategoryId,$clickscount );
			if($objBanner->Save())
			{
				
				$msgActionStatus = "New Banner Added Successfully.";
			}
		}
	}
	
	//Change banner Status (Active / Inactive)
	if($_REQUEST['Edit'] == 'ChangeStatus')//ChangeActive
	{
		$Id = $_REQUEST['Id'];
		$sActive = $_REQUEST['Active'];
		
		$objBanner = @new cSideBanners($Id);
		$objBanner -> Load();
					
		$objBanner -> Active = $sActive;
					
		if($objBanner -> Save())
		{
			$msgActionStatus = "Banner Status Changed.";
		}
	}
	
	//Update Banner Information
/*	if($_REQUEST['Update'] == 'UpdateBanner')
	{
		$Id = $_REQUEST['Id'];
		$sActive = $_REQUEST['Active'];
	
		if($_FILES['fileBannerImage']['error']==0)			//If file is attached.
		{
			$sFileName = $_FILES['fileBannerImage']['name'];
			move_uploaded_file($_FILES['fileBannerImage']['tmp_name'],$base_path."images/banners/".$_FILES['fileBannerImage']['name']);
			$sFileName = "/images/banners/".$sFileName;	
		}
		else
		{
			$objBanner = @new cBanners($Id);
			$objBanner -> Load();
			$sFileName = $objBanner -> FileName;
		}
		
		$sTitle = addslashes(trim($_POST['txtTitle']));		
		$sURL = trim($_POST['txtURL']);
		$sSeqNo = $_POST['txtSeqNo'];

		$UpBanner = new cBanners($Id, $sTitle, $sFileName, $sURL, $sActive, $sSeqNo);
					
		if($UpBanner -> Save())
		{
			$msgActionStatus = "Banner Updated Successfully.";
		}
	}
*/	
	$active=''; 
        if(!empty($_GET['q']) && $_GET['q']=='past')
            $active .="EndDt < NOW()";
        else
            $active .=" EndDt >= NOW()";
	//Query For All Banners List
	 $BannerQuery = "SELECT * FROM SideBanners WHERE $active order by `Id` desc"; // using aall 
	$BannerList = $Global->SelectQuery($BannerQuery);
	
$CatSql="select * from category"; //using all
$CatList = $Global->SelectQuery($CatSql);

	//include 'templates/manage_sidebanner.tpl.php';
if(!empty($_GET['q']) && $_GET['q']=='past')
            include 'templates/manage_sidebanner_past.tpl.php';
        else
            include 'templates/manage_sidebanner.tpl.php';
?>