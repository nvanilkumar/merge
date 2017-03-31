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
	include_once("MT/cGallery.php");

	$Global = new cGlobal();

	$base_path = '/home/meraeven/public_html';		
	$msgActionStatus = '';
	
	//Delete the Popular Events
	if(isset($_REQUEST['delete'])	)
	{
		$Id = $_REQUEST['delete'];
		
		try
		{	
			$objGalleryImages = new cGallery($Id);
			if($objGalleryImages->Delete())
			{	
				//delete successful statement
				$msgActionStatus = "Gallery  Deleted Successfully.";
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
		if($_FILES['fileGalleryAdd']['error']==0)			//If file is attached.
		{
			$sFileName = $_FILES['fileGalleryAdd']['name'];
			move_uploaded_file($_FILES['fileGalleryAdd']['tmp_name'],$base_path."/galleryimages/".$_FILES['fileGalleryAdd']['name']);
		
			$sTitle = addslashes(trim($_POST['txtTitle']));		
			$sFileName = "/galleryimages/".$sFileName;
			
			$sActive = 1;
					
			$objGalleryImages = new cGallery(0, $sTitle, $sFileName, $sActive);
			if($objGalleryImages->Save())
			{
				//successfully Popular Event uploaded!
				$msgActionStatus = "New Gallery  Added Successfully.";
			}
		}
	}
	
	//Change Pop Events Status (Active / Inactive)
	if($_REQUEST['Edit'] == 'ChangeActive')
	{
		$Id = $_REQUEST['Id'];
		$sActive = $_REQUEST['Active'];
		
		$objGalleryImages = @new cGallery($Id);
		$objGalleryImages -> Load();
					
		$objGalleryImages -> Active = $sActive;
					
		if($objGalleryImages -> Save())
		{
			$msgActionStatus = "Gallery  Status Changed.";
		}
	}        
	
	//Query For All Popular Events List
	$GalleryImagesQuery = "SELECT * FROM gallery";
	$GalleryImagesList = $Global->SelectQuery($GalleryImagesQuery);
	
	include 'templates/manage_GalleryAdd.tpl.php';
?>