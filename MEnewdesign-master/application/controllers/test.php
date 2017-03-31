<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Default landing page controllersdfsfsdfsddd
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     11-06-2015
 * @Last Modified On 25-06-2015
 * @Last Modified By Sridevi
 */


class Test extends CI_Controller {

  public function __construct() {
        parent::__construct();
   	   $this->load->model('File_model');
    }
    
    public function hello() {
            $this->load->view('testing_view');
    }

public function upload() {
		$awsAccessKey = $this->config->item('awsAccessKey');
        $awsSecretKey = $this->config->item('awsSecretKey');
        require_once(APPPATH . 'libraries/S3.php');
		ini_set('memory_limit', '1000M'); 
		ini_set('max_execution_time', 0);
		//$readpath = 'C:\Users\Sridevi\Downloads\hyd-350_200/';
		//$readpath ='C:\Users\Sridevi\Downloads\mumbai-350_200/';
		//$readpath = 'C:\Users\Sridevi\Downloads\Bengaluru1/';
//		$readpath ='C:\Users\Sridevi\Downloads\delhi/';
//		$readpath='C:\Users\Sridevi\Downloads\ThumbnailImages\delhi-thumbnails/';
		$readpath='/opt/MND-Thumbnail-Banners/Pune-Thumbnails/';
		$path =  realpath($readpath);
		$folder = $path;
		foreach( scandir( $path ) as $file )
 		 {
		    $filepath = $folder . '/' . $file;
		    if( preg_match( '/^\./', $file ) ) continue; // not . and ..
		    if( is_dir( $filepath ) )    
			{	
				$foldername= $filepath; 
				$eventfoldername = str_replace($readpath,"",$foldername);
				foreach( scandir( $filepath ) as $eachfile )
				{
					//echo $eachfile."<br>";
					if( !is_dir( $eachfile ) )    {
					$ext = pathinfo($eachfile, PATHINFO_EXTENSION);
					 $newfile = $foldername."/".$eachfile;
					 $this->load->helper('common');
					$s3 = new S3($awsAccessKey, $awsSecretKey);
					$bucketName = $this->config->item('bucketName');
					$destinationPath = "eventlogo/".$eventfoldername;
					$sourcepath =  $foldername."/".$eachfile;
					$fileName = $eachfile;
					$fileType = $ext;
					if (!is_null($fileName)) {
						$fileName = str_replace(".".$ext,date("mdYHis").".".$ext,$fileName);
						$uri = 'content/' . $destinationPath . '/' . $fileName;
						
						 $upStatus = $s3->putObjectFile($sourcepath, $bucketName, $uri, S3::ACL_PUBLIC_READ, array(), $fileType);
						if ($upStatus) {
							$data['status'] = TRUE;
							$data['response']['messages'][] = SUCCESS_FILE_UPLOAD;
						$InsertUpdateData = array();
						$InsertUpdateData['path'] = "eventlogo/".$eventfoldername."/".$fileName;
						$InsertUpdateData['type'] = "eventthumbnail";
						$this->db->insert("file", $InsertUpdateData); 
						$newFileid = 0;
						$newFileid = $this->db->insert_id();
						$this->db->query("update event set thumbnailfileid='".$newFileid."' where id = ".$eventfoldername);
						//echo "event table updated".$updateventfalg."<br>";
						
						echo	$data['response']['uploadPath'] = $this->config->item('images_cloud_path') . $uri;
						echo "<br>";
							$data['statusCode'] = 200;
							//var_dump($data);
							
						} else {
							$data['status'] = FALSE;
							$data['response']['messages'][] = ERROR_FILE_UPLOAD;
							$data['statusCode'] = 400;
							var_dump($data);
						}
					}


					
					}
				}
		  	}
		}

		}
public function uploadBanner() {
		$awsAccessKey = $this->config->item('awsAccessKey');
        $awsSecretKey = $this->config->item('awsSecretKey');
        require_once(APPPATH . 'libraries/S3.php');
		ini_set('memory_limit', '1000M'); 
		ini_set('max_execution_time', 0);
		//$readpath = 'C:\Users\Sridevi\Downloads\hyd-350_200/';
		//$readpath ='C:\Users\Sridevi\Downloads\mumbai-350_200/';
		//$readpath = 'C:\Users\Sridevi\Downloads\Bengaluru1/';
//		$readpath ='C:\Users\Sridevi\Downloads\delhi/';
//		$readpath='C:\Users\Sridevi\Downloads\ThumbnailImages\delhi-thumbnails/';
		$readpath='/opt/MND-Thumbnail-Banners/Chennai-Banners/';
		$path =  realpath($readpath);
		$folder = $path;
		foreach( scandir( $path ) as $file )
 		 {
		    $filepath = $folder . '/' . $file;
		    if( preg_match( '/^\./', $file ) ) continue; // not . and ..
		    if( is_dir( $filepath ) )    
			{	
				$foldername= $filepath; 
				$eventfoldername = str_replace($readpath,"",$foldername);
				foreach( scandir( $filepath ) as $eachfile )
				{
					//echo $eachfile."<br>";
					if( !is_dir( $eachfile ) )    {
					$ext = pathinfo($eachfile, PATHINFO_EXTENSION);
					 $newfile = $foldername."/".$eachfile;
					 $this->load->helper('common');
					$s3 = new S3($awsAccessKey, $awsSecretKey);
					$bucketName = $this->config->item('bucketName');
					$destinationPath = "eventbanner/".$eventfoldername;
					$sourcepath =  $foldername."/".$eachfile;
					$fileName = $eachfile;
					$fileType = $ext;
					if (!is_null($fileName)) {
						$fileName = str_replace(".".$ext,date("mdYHis").".".$ext,$fileName);
						$uri = 'content/' . $destinationPath . '/' . $fileName;
						
						 $upStatus = $s3->putObjectFile($sourcepath, $bucketName, $uri, S3::ACL_PUBLIC_READ, array(), $fileType);
						if ($upStatus) {
							$data['status'] = TRUE;
							$data['response']['messages'][] = SUCCESS_FILE_UPLOAD;
						$InsertUpdateData = array();
                                                $InsertUpdateData['path'] = "eventbanner/".$eventfoldername."/".$fileName;
						$InsertUpdateData['type'] = "eventbanner";
						$this->db->insert("file", $InsertUpdateData); 
						$newFileid = 0;
						$newFileid = $this->db->insert_id();
						$this->db->query("update event set bannerfileid='".$newFileid."' where id = ".$eventfoldername);
						//echo "event table updated".$updateventfalg."<br>";
						
						echo	$data['response']['uploadPath'] = $this->config->item('images_cloud_path') . $uri;
						echo "<br>";
							$data['statusCode'] = 200;
							//var_dump($data);
							
						} else {
							$data['status'] = FALSE;
							$data['response']['messages'][] = ERROR_FILE_UPLOAD;
							$data['statusCode'] = 400;
							var_dump($data);
						}
					}


					
					}
				}
		  	}
		}



       
		}
                
		
}
?>