<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Edit Banner Information
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 01st Oct 2009
 *
******************************************************************************************************************************************/
	
	session_start();
		
	 include 'loginchk.php';
	$uid =	$_SESSION['uid'];
	
	include_once("MT/cGlobal.php");
	include_once("MT/cNyeBanners.php");
       // include_once 'uploadToS3.php';
           include_once 'includes/common_functions.php';
   $commonFunctions=new functions();
   include_once '../resize.php';

	$Global = new cGlobal();

	$base_path = '/home/meraeven/public_html/content';		
	$msgActionStatus = '';
	
	
	$btype="newyear";
	if(isset($_GET['btype']))
	{
		if(strlen(trim($_GET['btype']))>0)
		{
			$btype=$_GET['btype'];
		}
		
	}
	

	$Id = $_REQUEST['Id'];//Banner Id
	
	//Update Banner Information
	if($_REQUEST['Update'] == 'UpdateBanner')
	{
		$sActive = $_REQUEST['Active'];
	
		if($_FILES['fileBannerImage']['error']==0)			//If file is attached.
		{
			$sFileName = $_FILES['fileBannerImage']['name'];
			move_uploaded_file($_FILES['fileBannerImage']['tmp_name'],"../"._HTTP_Content."/images/banners/".$_FILES['fileBannerImage']['name']);
                        //writing image paths to the  textfile (imageUploadInfo.txt)  
                     $uploadedImages = fopen("../TextFiles/imageUploadInfo.txt", 'a');
        fwrite($uploadedImages, "images/banners/" . $_FILES['fileBannerImage']['name'] . "\r\n"); //writing into text file all the image paths, just to know the count of them
        fclose($uploadeImages);
        
                 //resizing Image -Phinny
            $image = new SimpleImage();
            $image->load("../"._HTTP_Content."/images/banners/".$_FILES['fileBannerImage']['name']);
            $image->resize(1140,330);
            $image->save("../"._HTTP_Content."/images/banners/".$_FILES['fileBannerImage']['name']);
        
        //need to calll optimization script (optimizeimage.sh)
     $OptimizeImageScriptError=    $commonFunctions->optimizeAllImages(_OPTIMIZEPATH."optimizeimage.sh");               
			 
                     /*****************************Uploading to S3******************************************/         
                     if ($_SERVER['HTTP_HOST'] != "localhost") {
                   $uploadToS3Error=      $commonFunctions->uploadImageToS3("../"._HTTP_Content."/images/banners/".$_FILES['fileBannerImage']['name'],_BUCKET);
//                        $modPath=  substr("../"._HTTP_Content."/images/banners/".$_FILES['fileBannerImage']['name'], 3);
//                $S3Sucess=uploadToS3("../"._HTTP_Content."/images/banners/".$_FILES['fileBannerImage']['name'], $modPath, _BUCKET);
//                  if (file_exists("../"._HTTP_Content."/images/banners/".$_FILES['fileBannerImage']['name']) && $S3Sucess) {
//                   // unlink("../"._HTTP_Content."/images/banners/".$_FILES['fileBannerImage']['name']);
//                }
            }
                        
                        
                        
                        

                        $sFileName = "/images/banners/".$sFileName;	
		}
		else
		{      
			
			$sFileName = $Global->GetSingleFieldValue("select FileName from nye_banners WHERE Id=".$Id); 
		}
		
		$sTitle = addslashes(trim($_POST['txtTitle']));		
              $sEventId = $_POST['EventId'];
			  $SDt = $_REQUEST['txtSDt'];
		$SDtExplode = explode("/", $SDt);
		$sStartDt = $SDtExplode[2].'-'.$SDtExplode[1].'-'.$SDtExplode[0].' 00:00:00';
		
		$EDt = $_REQUEST['txtEDt'];
		$EDtExplode = explode("/", $EDt);
		$sEndDt = $EDtExplode[2].'-'.$EDtExplode[1].'-'.$EDtExplode[0].' 23:59:59';
		$sURL = trim($_POST['txtURL']);
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
		if(isset($_REQUEST['Goa']))
        { 
            $Goa = 1; 
        }
        else
        {    
            $Goa = 0;     
        }


		$UpBanner = new cNyeBanners($Id, $sTitle, $sEventId, $sStartDt, $sEndDt, $sFileName, $sURL, $sActive, $sSeqNo, $Main, $Banglore,$Chennai,$Delhi, $Hyderabad ,$Mumbai,$Pune,$Kolkata,$AllCities,$OtherCities,$Goa);
					
		if($UpBanner -> Save())
		{
			$msgActionStatus = "Banner Updated Successfully.";
		?>
			<script>
				window.location="manage_newyear_banner.php?btype=<?php echo $btype; ?>";
			</script>
		<?php
		}
	}
	
	//Query For All Banners List
	$BannerQuery = "SELECT * FROM nye_banners WHERE Id='".$Id."'"; //using 28/29 -pH
	$BannerList = $Global->SelectQuery($BannerQuery);
	


	include 'templates/manage_newyear_banner_edit.tpl.php';
?>