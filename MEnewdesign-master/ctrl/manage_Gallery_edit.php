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
	include_once("MT/cGalleryImages.php");

	$Global = new cGlobal();

	$base_path = '/home/meraeven/public_html';    	
	$msgActionStatus = '';

	$Id = $_REQUEST['Id'];//PopEvents Id
	
	//Update Popular Event Information
	if($_REQUEST['Update'] == 'UpdateImages')
	{
		$sActive = $_REQUEST['Active'];
	
		if($_FILES['fileGalleryImage']['error']==0)			//If file is attached.
		{
			$sFileName = $_FILES['fileGalleryImage']['name'];
			move_uploaded_file($_FILES['fileGalleryImage']['tmp_name'],$base_path."/galleryimages/".$_FILES['fileGalleryImage']['name']);
			$sFileName = "/galleryimages/".$sFileName;	
		}
		else
		{
			$objGalleryImages = @new cGalleryImages($Id);
			$objGalleryImages -> Load();
			$sFileName = $objGalleryImages->FileName;
		}
		
		$sTitle = addslashes(trim($_POST['txtTitle']));		
		
		$UpImages = new cGalleryImages($Id, $sTitle, $sFileName,$sActive);
					
		if($UpImages->Save())
		{
			$msgActionStatus = "Gallery Images Updated Successfully.";
		?>
			<script>
				window.location="manage_Gallery.php";
			</script>
		<?php
		}
	}
	
	//Query For All Popular Events List
	$GalleryImagesQuery = "SELECT * FROM galleryimages WHERE Id='".$Id."'"; //using 4/5 -pH
	$GalleryImagesList = $Global->SelectQuery($GalleryImagesQuery);
	
	include 'templates/manage_Gallery_edit.tpl.php';
?>