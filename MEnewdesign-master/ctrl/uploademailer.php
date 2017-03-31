<?php
session_start();
	
include_once 'MT/cGlobali.php';
include 'loginchk.php';
	
$Global = new cGlobali();


include_once 'includes/common_functions.php';
$commonFunctions = new functions();
		

$uploadTempPath = $_SERVER['DOCUMENT_ROOT'].'/images/content/temp/';
	
	
	
	
function recursive_dir($dir) {
    foreach(scandir($dir) as $file) {
	    if ('.' === $file || '..' === $file) continue;
		    if (is_dir("$dir/$file")) recursive_dir("$dir/$file");
				    else unlink("$dir/$file");
			}
	    rmdir($dir);
	}
 
if($_FILES["zip_file"]["name"]) {
	$filename = $_FILES["zip_file"]["name"];
	$source = $_FILES["zip_file"]["tmp_name"];
	$type = $_FILES["zip_file"]["type"];
 
	$name = explode(".", $filename);
	$accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
	foreach($accepted_types as $mime_type) {
		if($mime_type == $type) {
		$okay = true;
		break;
		}
	}
	 
	$continue = strtolower($name[1]) == 'zip' ? true : false;
	if(!$continue) {
		$myMsg = "Please upload a valid .zip file.";
	}
	 
	/* PHP current path */
	 $path = dirname(__DIR__).'/emailer/'; 
	$filenoext = basename ($filename, '.zip'); 
	$filenoext = basename ($filenoext, '.ZIP');
	 
	 $myDir = $uploadTempPath . $filenoext; // target directory
	 $myFile = $uploadTempPath . $filename; // target zip file
	 
	 
	 
	
	//if (is_dir($myDir)) recursive_dir ( $myDir);
		 
	mkdir($myDir, 0777);
	 
	/* here it is really happening */
	 
	if(move_uploaded_file($source, $myFile)) {
		$zip = new ZipArchive();
		$x = $zip->open($myFile); // open the zip file to extract
		if ($x === true) {
			$zip->extractTo($myDir); // place in the directory with same name
			$zip->close();
			unlink($myFile);
		}
		//uploading to CF
		$successUpload = FALSE;
		
		
		
		//echo $myDir; exit;
		//print_r($files); exit;
		
		
		$FolderFiles = $commonFunctions->dir_scan($myDir);
		/*echo "<pre>";
		print_r($FolderFiles);
		exit;*/
		
		
		foreach($FolderFiles as $individualFile)
		{
			$target_dir = "";
			
			$individualFileEx = explode($uploadTempPath,$individualFile);
			$justFilePath = $individualFileEx[1];
			
			$target_dir = "emailer/".$justFilePath;
			
			
			//echo $individualFile."<===>".$target_dir."<br>";
			
			$successUpload = $commonFunctions->uploadImageToS3($individualFile, $target_dir, NULL);
			//var_dump($successUpload)."<br>";
			if($successUpload){ 
					//
			}
			else{
				for($l=1;$l<3;$l++)
				{
					$successUpload = $commonFunctions->uploadImageToS3($individualFile, $target_dir, NULL);
					if($successUpload){ continue; }
				}
			}
		}
		
		$commonFunctions->deleteDir($myDir); 
		
		
		
		
	}
            
		
		
	
	if($successUpload){
		$myMsg = "Your .zip file uploaded and unziped to <br/>"._ME_CF_PATH."/emailer/".$filenoext."/index.html";
	} else {	
		$myMsg = "There was a problem with the upload.";
	}
	
}
 include 'templates/uploademailer.tpl.php';
?>
