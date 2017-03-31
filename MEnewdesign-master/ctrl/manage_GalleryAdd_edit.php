<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Edit Popular Event Information
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 02nd Oct 2009
 *
******************************************************************************************************************************************/
	
	session_start();
		
	 include 'loginchk.php';

	$uid =	$_SESSION['uid'];
	
	include_once("MT/cGlobal.php");
	include_once("MT/cGallery.php");

	$Global = new cGlobal();

	$base_path = '/home/meraeven/public_html';    	
	$msgActionStatus = '';

	$Id = $_REQUEST['Id'];//PopEvents Id
	
	//Update Popular Event Information
	if($_REQUEST['Update'] == 'UpdateGallery')
	{
		$sActive = $_REQUEST['Active'];
	
		if($_FILES['fileGalleryAdd']['error']==0)			//If file is attached.
		{
			$sFileName = $_FILES['fileGalleryAdd']['name'];
			move_uploaded_file($_FILES['fileGalleryAdd']['tmp_name'],"../galleryimages/".$_FILES['fileGalleryAdd']['name']);
			$sFileName = "/galleryimages/".$sFileName;	
		}
		else
		{
			$objGalleryImages = @new cGallery($Id);
			$objGalleryImages -> Load();
			$sFileName = $objGalleryImages->FileName;
		}
		
		$sTitle = addslashes(trim($_POST['txtTitle']));		
		
		$UpImages = new cGallery($Id, $sTitle, $sFileName,$sActive);
					
		if($UpImages->Save())
		{
			$msgActionStatus = "Gallery  Updated Successfully.";
		?>
			<script>
				window.location="manage_GalleryAdd.php";
			</script>
		<?php
		}
	}
	
	//Query For All Popular Events List
	$GalleryImagesQuery = "SELECT * FROM gallery WHERE Id='".$Id."'";
	$GalleryImagesList = $Global->SelectQuery($GalleryImagesQuery);
	
	include 'templates/manage_GalleryAdd_edit.tpl.php';
?>