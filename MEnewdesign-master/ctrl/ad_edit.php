<?php
include_once("includes/application_top.php");
include_once("includes/functions.php");
include('includes/logincheck.php');

// *---------------------- UPLOAD IMAGE ---------------------------------------------------------------------------

if($_POST['Submit'] == "Upload New Ad"){
             
			 list($name,$ext) = explode(".",$_POST['filename']);
			 $new_height=100;	//Height Setting for Thumbnail
			 $new_width=100;	//Width Setting for Thumbnail
			 
			 $allowed_types = array( 		//Validation for Images.
		
			 'image/pjpeg', 
		
			 'image/gif', 
		
			 'image/png', 
		
			 'image/jpeg'); 
		
			 if(in_array($_FILES['image']['type'], $allowed_types)) 
		
			 {			
				   copy ($_FILES['image']['tmp_name'], $_FILES['image']['name']) or die     ("Could not copy");
			
				   "Name: ".$_FILES['image']['name']."";
			
				   "Size: ".$_FILES['image']['size']."";
			
				   "Type: ".$_FILES['image']['type']."";     
			
				   $imagefile=$_FILES['image']['name'];
				   
				   move_uploaded_file($_FILES["image"]["tmp_name"],"../bigbang/files/".$name.".jpg");
		
			 }
			 header("location : banner.php");
}// END of if

// *----------------------------------------------------------------------------------------------------------------


$aid = $_GET['aid'];
// GET FILE NAME OF THE AD
$sql_file = "SELECT files.filepath,files.filename FROM files,ad_image WHERE files.fid = ad_image.fid AND ad_image.aid = ".$aid." ";
$sql_file_res = mysql_query($sql_file);
$sql_file_row = mysql_fetch_array($sql_file_res);

$current_page_content = 'ad_edit.tpl.php';
include_once(_CURRENT_TEMPLATE_DIR.'main.tpl.php');	
?>

