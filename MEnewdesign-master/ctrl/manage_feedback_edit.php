<?php
session_start();
	
	 include 'loginchk.php';

	$uid =	$_SESSION['uid'];
	
	include_once("MT/cGlobal.php");
	
	$Global = new cGlobal();

          
       $base_path = '/home/meraeven/public_html/';
	$msgActionStatus = '';

	 $Id = $_REQUEST['editid'];  //Banner Id
	
	//Update Banner Information

	if($_REQUEST['Update'] == 'UpdateTestimonials')
	{
	     if($_FILES['fileLogoImage']['error']==0)	
		 {
		 	$sFileName = $_FILES['fileLogoImage']['name'];
			move_uploaded_file($_FILES['fileLogoImage']['tmp_name'],$base_path."images/".$_FILES['fileLogoImage']['name']);
			  $uploadedImages=  fopen("../TextFiles/imageUploadInfo.txt", 'a' );
            fwrite($uploadedImages, $base_path."images/".$_FILES['fileLogoImage']['name']."\r\n");//writing into text file all the image paths, just to know the count of them
            fclose($uploadeImages);
                        $sFileName = "/images/".$sFileName;
		 }else{
		 $sFileName=$_REQUEST[editlogo];
		 }
	
		 $sqlup="update feedbacks set vFName='".$_REQUEST[txtName]."',CompanyName='".$_REQUEST[txtcName]."',Title='".$_REQUEST[txtTitle]."',vEmail='".$_REQUEST[txtemail]."',vMobile='".$_REQUEST[txtMobile]."',tComment='".$_REQUEST[txtComment]."',logo='".$sFileName."' where Id=$Id";
         $res=$Global->ExecuteQuery($sqlup);
					
		if($res)
		{
			$msgActionStatus = "Testimonials Updated Successfully.";
		?>
			<script>
				window.location="manage_feedback.php";
			</script>
		<?php
		}
	}
	
	//Query For All Banners List
	$TestQuery = "SELECT * FROM feedbacks WHERE Id='".$Id."'";
	$ResTestQuery = $Global->SelectQuery($TestQuery);
	
	include 'templates/manage_feedback_edit.tpl.php';
?>