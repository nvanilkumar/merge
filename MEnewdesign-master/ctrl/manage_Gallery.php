<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Manage Popular Events
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is creaed on 02nd Oct 2009
 *		i. MT - cPopEvents
 *		ii. Table Name - PopEvents
 *		iii. Fields List -  Id, Title, FileName, URL, Active, SeqNo
******************************************************************************************************************************************/
	
	session_start();
		
	 include 'loginchk.php';

	$uid =	$_SESSION['uid'];
	
	include_once("MT/cGlobal.php");
	include_once("MT/cGalleryImages.php");

	$Global = new cGlobal();

	$base_path = '/home/meraeven/public_html';		
	$msgActionStatus = '';
	
	//Delete the Popular Events
	if(isset($_REQUEST['delete'])	)
	{
		$Id = $_REQUEST['delete'];
		
		try
		{	
			$objGalleryImages = new cGalleryImages($Id);
			if($objGalleryImages->Delete())
			{	
				//delete successful statement
				$msgActionStatus = "Gallery Image Deleted Successfully.";
			}
		}
		catch (Exception $Ex)
		{
			echo $Ex->getMessage();
		}
	}
	
	//Add new Popular Event
	if(isset($_POST['Submit']) == 'Upload')
	{
		if($_FILES['fileGalleryImage']['error']==0)			//If file is attached.
		{
			$sFileName = $_FILES['fileGalleryImage']['name'];
			move_uploaded_file($_FILES['fileGalleryImage']['tmp_name'],"../galleryimages/".$_FILES['fileGalleryImage']['name']);
		     $sgalleryid=$_REQUEST[GalleryName];
			$sTitle = addslashes(trim($_POST['txtTitle']));		
			$sFileName = "/galleryimages/".$sFileName;
			
			$sActive = 1;
					
			$objGalleryImages = new cGalleryImages(0,$sgalleryid, $sTitle, $sFileName, $sActive);
			if($objGalleryImages->Save())
			{
				//successfully Popular Event uploaded!
				$msgActionStatus = "New Gallery Image Added Successfully.";
			}
		}
	}
	
	//Change Pop Events Status (Active / Inactive)
	if($_REQUEST['Edit'] == 'ChangeActive')
	{
		$Id = $_REQUEST['Id'];
		$sActive = $_REQUEST['Active'];
		
		$objGalleryImages = @new cGalleryImages($Id);
		$objGalleryImages -> Load();
					
		$objGalleryImages -> Active = $sActive;
					
		if($objGalleryImages -> Save())
		{
			$msgActionStatus = "Gallery Image Status Changed.";
		}
	}        
	
	//Query For All Popular Events List
//	$GalleryQuery = "SELECT * FROM gallery";
        $GalleryQuery = "SELECT `Id`,`Title` FROM gallery";
    $GalleryList = $Global->SelectQuery($GalleryQuery);
    
    $GalleryImagesQuery = "SELECT * FROM galleryimages"; //using 4/5
	$GalleryImagesList = $Global->SelectQuery($GalleryImagesQuery);
	
	include 'templates/manage_Gallery.tpl.php';
?>