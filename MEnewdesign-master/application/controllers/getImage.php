<?php

require_once APPPATH . 'controllers/simpleImage.php';

class GetImage extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function get()
	 {
		    $path =  realpath('E:/ME/test');
			$folder = $path;
			foreach( scandir( $path ) as $file )
 			 {
    $filepath = $folder . '/' . $file;
    if( preg_match( '/^\./', $file ) ) continue; // not . and ..
    if( is_dir( $filepath ) )    
	{
		$foldername= $filepath;
		foreach( scandir( $filepath ) as $eachfile )
		{
			
			//echo $eachfile."<br>";
			if( !is_dir( $eachfile ) )    {
			$ext = pathinfo($eachfile, PATHINFO_EXTENSION);
			$newfile = $foldername."/".$eachfile;
			$image = new SimpleImage();
                $image->load($newfile);
             //   $image->resize(1180, 380);
               // $thumb_contents = $image->getResizedImagepath($eachfile);
                //echo $thumb_contents;exit;
                //$image->save($thumb_contents);
			
			 
			}
		}
	}
	//if( !is_dir( $filepath ) )    
	//echo $file.	"<br>";
	
	}
			
	}
   }
    