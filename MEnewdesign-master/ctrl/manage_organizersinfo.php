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
	
	include_once("MT/cGlobal.php");
	$Global = new cGlobal();		
	 include 'loginchk.php';
         //include_once 'uploadToS3.php';
     include_once 'includes/common_functions.php';
   $commonFunctions=new functions();


    
   
    
    if($_REQUEST[Update]!="" && $_REQUEST[orginfo]!="" && $_REQUEST[aboutorg]!="")
    {
        if($_FILES['logo']['error']==0)			//If file is attached.
		{
			$logo = $_FILES['logo']['name']; 
			move_uploaded_file($_FILES['logo']['tmp_name'],_HTTP_Content."/logo/".$_FILES['logo']['name']);
                          //writing image paths to the  textfile (imageUploadInfo.txt)  
                        		 $uploadedImages=  fopen("../TextFiles/imageUploadInfo.txt", 'a' );
            fwrite($uploadedImages, "logo/".$_FILES['logo']['name']."\r\n");//writing into text file all the image paths, just to know the count of them
            fclose($uploadeImages);
            
            //need to call optimization script (optimizeimage.sh)
            $commonFunctions->optimizeAllImages(_OPTIMIZEPATH."optimizeimage.sh");
            
            
                        
                        
         /*****************************Uploading to S3******************************************/         
                     if ($_SERVER['HTTP_HOST'] != "localhost") {
                          $commonFunctions->uploadImageToS3(_HTTP_Content."/logo/".$_FILES['logo']['name'],_BUCKET);
//                        $modPath=  substr("../content/logo/".$_FILES['logo']['name'], 3);
//                $S3Sucess=uploadToS3("../content/logo/".$_FILES['logo']['name'], $modPath, _BUCKET);
//                  if (file_exists("../content/logo/".$_FILES['logo']['name']) && $S3Sucess) {
//                   // unlink("../content/logo/".$_FILES['logo']['name']);
//                }
            }
                        
                        
                        
                        
	
                        $logo = "logo/".$logo;
		}
		else			
		{
			$logo = $_POST['org_logo']; //$Organizer->CLogo;
		}
		
		
		if($_FILES['banner']['error']==0)			//If file is attached.
		{
			$banner = $_FILES['banner']['name'];
			move_uploaded_file($_FILES['banner']['tmp_name'],_HTTP_Content."/images/banners/".$_FILES['banner']['name']);
                          //writing image paths to the  textfile (imageUploadInfo.txt)
                        	$uploadedImages=  fopen("../TextFiles/imageUploadInfo.txt", 'a' );
            fwrite($uploadedImages, "images/banners/".$_FILES['banner']['name']."\r\n");//writing into text file all the image paths, just to know the count of them
            fclose($uploadeImages);
            
            //need to call optimizatin script (optmizeimage.sh)
            $commonFunctions->optimizeAllImages(_OPTIMIZEPATH."optimizeimage.sh");
                        
              /*****************************Uploading to S3******************************************/         
                     if ($_SERVER['HTTP_HOST'] != "localhost") {
                       $commonFunctions->uploadImageToS3(_HTTP_Content."/images/banners/".$_FILES['banner']['name'],_BUCKET);  
//                        $modPath=  substr("../content/images/banners/".$_FILES['banner']['name'], 3);
//                $S3Sucess=uploadToS3("../content/images/banners/".$_FILES['banner']['name'], $modPath, _BUCKET);
//                  if (file_exists("../content/images/banners/".$_FILES['banner']['name']) && $S3Sucess) {
//                   // unlink("../content/images/banners/".$_FILES['banner']['name']);
//                }
            }
                        
                        
                        
                        
                        
		
                        $banner = "/images/banners/".$banner;
		}
		else			
		{
			$banner = $_POST['org_banner']; //$Organizer->CLogo;
		}
		

		
		  $orgname=$_REQUEST[orgname];
		  $orginfo=$_REQUEST[orginfo];
		  $aboutorg=$_REQUEST[aboutorg];
		  $intendedfor=$_REQUEST[intendedfor];
		  $seqno=$_REQUEST[seqno];
		  $EveNo=$_REQUEST[EveNo];
          $sqlup="update orgdispname set orginfo='".$orginfo."', aboutorg='".$aboutorg."',intendedfor='".$intendedfor."',logopath='".$logo."',bannerpath='".$banner."',seqno=$seqno,EveNo=$EveNo where Id=".$orgname;
           $db_sqlup=$Global->ExecuteQuery($sqlup);
          
     
    }
  
	
    $sqlOrgdisp = "SELECT o.Id,o.orgDispName,o.Active FROM orgdispname AS o ORDER BY o.orgDispName ASC";
    $dtlOrgdisp = $Global->SelectQuery($sqlOrgdisp);
	
	$orgid=$_REQUEST[orgid];
	if($orgid!=""){
    $sqlOrgdisp1 = "SELECT * FROM orgdispname where Id=$orgid";//using 8/10  -pH
    $dtlOrgdisp1 = $Global->SelectQuery($sqlOrgdisp1);
	}
	
  
     
	include 'templates/manageorganisersinfo.tpl.php';
?>