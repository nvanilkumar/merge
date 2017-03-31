<?php
include_once 'MT/cGlobali.php';
//include_once 'uploadToS3.php';
class functions {

    private $includelink;
    var $cGloabli;

    public function __construct() {
        $this->includelink = $this->getLink();
    }

    public function getLink() {
        $uri = $_SERVER['REQUEST_URI'];
        $pos = strpos($uri, 'ctrl/');

        $includelink = NULL;

        if ($pos !== false) {
            $includelink = '../';
        }
        if (stripos($_SERVER['PHP_SELF'], '/dashboard/') !== false || stripos($_SERVER['PHP_SELF'], '/developers/') !== false) { // condition to set dashbaord file upload path
            $includelink = '../';
        }

        return $includelink;
    }

    function optimizeAllImages($optimizeImageScriptPath) {
        include_once $this->includelink . 'uploadToS3.php';
        try {
            $OptimizeImageScriptError = NULL;
// exec("/var/test/bash/optimizeimage.sh");
            exec($optimizeImageScriptPath);
        } catch (Exception $e) {
            $OptimizeImageScriptError = "OptimzeAllImageScript Error:" . $e->getMessage();
        }
        return $OptimizeImageScriptError;
    }
     /* currency list */
    function getCurrencyList($global) {
        $currencyQuery = "SELECT * FROM currency WHERE status = '1' AND deleted = '0' AND `name` != 'free'";
        return $global->SelectQuery($currencyQuery);
    }
    /* timezone list */
    function getTimezoneList($global) {
        $timeZoneQuery = "SELECT * FROM timezone WHERE status = '1' AND deleted = '0'";
        return $global->SelectQuery($timeZoneQuery);
    }
    
    function appendTimeStamp($fileName) {
    	$currentTime = strtotime("now");
    	$path_parts = pathinfo($fileName);
    	$path_parts['filename']= $this->cleanUrl($path_parts['filename']);
    	$newFileName = $path_parts['filename'] . $currentTime . "." . $path_parts['extension'];
    	return $newFileName;
    }
    
    function cleanUrl($string) {
    	$string = preg_replace('/[^A-Za-z0-9\-]/', ' ', $string);
    	$string = str_replace(' ', '-', $string);
    	return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }
       public function createImage($filePath, $type) {
        $errorMessage = FALSE;
        switch ($type) {
            case 'gif' :
                $image = imagecreatefromgif($filePath);
                break;
            case 'jpeg' :
                $image = imagecreatefromjpeg($filePath);
                break;
            case 'jpg' :
                $image = imagecreatefromjpeg($filePath);
                break;
            case 'png' :
                $image = imagecreatefrompng($filePath);
                break;
            default :
                $errorMessage = TRUE;
        }
        if ($errorMessage) {
            $output['status'] = FALSE;
            $output['response']['messages'][] = "Only jpg|jpeg|gif|png are allowed";
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['image'] = $image;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

    public function createImageType($destImage, $sourcePath,$type) {
        $errorMessage = FALSE;
        switch ($type) {
            case 'gif' :
                $imageStaus = imagegif($destImage,$sourcePath);
                break;
            case 'jpeg' :
                $imageStaus = imagejpeg($destImage,$sourcePath);
                break;
            case 'jpg' :
                $imageStaus = imagejpeg($destImage,$sourcePath);
                break;
            case 'png' :
                $imageStaus = imagepng($destImage,$sourcePath);
                break;
            default :
                $errorMessage = TRUE;
        }
        if ($errorMessage) {
            $output['status'] = FALSE;
            $output['response']['messages'][] = "Only jpg|jpeg|gif|png are allowed";
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['imageStaus'] = $imageStaus;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

    public function resizeImage($sourcePath,$fileExtention,$resize,$tempDestinationPath=''){
                if(empty($tempDestinationPath)){
                    $tempDestinationPath=$sourcePath;
                }
                $srcImageResponse = $this->createImage($sourcePath, $fileExtention);
                if ($srcImageResponse['status'] == FALSE) {
                    return $srcImageResponse;
                }
                $srcImage = $srcImageResponse['response']['image'];
                //Finding start and end points where to start craping
                $srcWidth = imagesx($srcImage);
                $srcHeight = imagesy($srcImage);
                //Create new image with given dimentions
                $destImage = imagecreatetruecolor($resize['width'], $resize['height']);
                //Resizing the image into the new image acc to given dimentions
                imagecopyresampled($destImage, $srcImage, 0, 0, 0, 0, $resize['width'], $resize['height'], $srcWidth, $srcHeight);
                $createImageResponse = $this->createImageType($destImage,$tempDestinationPath, $fileExtention);
                if (!$createImageResponse['status']) {
                    return $createImageResponse;
                }        
    }
    //file upload
    function fileUpload($global, $file, $extensions, $imgfolder, $type, $image="", $resize=array()){
       // error_reporting(-1);
        $imgfolder = ltrim($imgfolder, '/');
    	$target_dir = "content/".$imgfolder."/";
        $file["name"] = $this->appendTimeStamp( $file["name"]);
        $target_file = $target_dir . basename($file["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

        $successUpload = false;
         if(isset($image) && $image == "urlImage"){
            $tempDestinationPath=FILE_UPLOAD_TEMP_PATH.$file["name"];
            $this->resizeImage($file["tmp_name"], $imageFileType, $resize,$tempDestinationPath);
            $file["tmp_name"]=$tempDestinationPath;
         }else if((isset($image) && $image != "" && isset($_FILES['fileBannerImage']))||(isset($image) && $image == "urlImage")){ 
            //At present we are resizing 1)only banners from fileupload or 2.banner resize from url when editing     
            //echo 'coming in file upload by manual';
             $response=$this->resizeImage($file["tmp_name"], $imageFileType, $resize);
            // print_r($this->resizeImage($file["tmp_name"], $imageFileType, $resize));exit;
        }
        //upload to s3
        $uri = str_replace("../images/", "", $imgfolder);
        $uriInsert = $imgfolder."/".$file["name"];
        $target_dir = $target_dir.$file["name"];
        if($imgfolder == "organizerbanners"){
            $uriInsert = $target_dir;
        }
       
 
     //   $imgfolder = CONTENT_CLOUD_PATH.$imgfolder;
     //echo $target_dir;exit;
        try  {
            if ($this->uploadImageToS3($file["tmp_name"], $target_dir, $imageFileType)) {
                $successUpload = true;
                if(isset($file['unlink']) && $file['unlink']==TRUE){
                    unlink($file["tmp_name"]);
                }
            }
        }
        catch (Exception $ex) {
            echo  $ex->getMessage();
        }
        if ($successUpload) {           
         $query="INSERT INTO file (`path`, `type`,createdby, `modifiedby`) VALUES ('".$uriInsert."', '".$type."', '".$_SESSION['uid']."', '".$_SESSION['uid']."')";	
            $fileId= $global->ExecuteQueryId($query);
            if ($fileId != 0) {
                if($type=='eventdescription'){
                    $output['descriptionImage']=CONTENT_CLOUD_PATH.$uriInsert;
                    return $output;
                }else{
                    return $fileId;
                }
            }
            else {
                return false;
            }
        }
        }
    
    function uploadImageToS3($file, $uri, $fileType) {
       
        try {            
            require_once $_SERVER['DOCUMENT_ROOT'].'/application/libraries/S3.php';
            $return = false;            
            $s3 = new S3(AWS_ACCESS_KEY, AWS_SECRET_KEY);
            //pdf mime type
            if($fileType=="pdf"){
                $fileType ='application/pdf';
            }
            $upStatus = $s3->putObjectFile($file, AWS_BUCKET, $uri, S3::ACL_PUBLIC_READ, array(), $fileType);
            //var_dump($upStatus);
            if ($upStatus) {return true;}
            else {return false;}      
            
        } catch (Exception $e) {
            echo $return = date("d-m-Y H:i:s") + "  Uploading to S3 Error:" . $e->getMessage() . "   " . $fileName;
        }
        return $return;
    }

    function extract_emails($string) {  ///// Extracts email from the given string else returns false
        $pattern = "/(?:[a-z0-9!#$%&'*+=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/";

        preg_match_all($pattern, $string, $matches);

        if (isset($matches[0][0]))
            return $matches[0];


        return false;
    }

    function getResizedImagepath($normalPath) {
        $resizedPath = '';
        if (!empty($normalPath)) {
            $ex = explode('.', $normalPath);
            $ex[count($ex) - 2].="_t";
            $resizedPath = implode('.', $ex);
        }
        return $resizedPath;
    }

    function authenticateFacebookToken($facebooktoken) {
        $return = NULL;
//$graph_url_friends = "https://graph.facebook.com/me/friends?fields=id,name,username&access_token=" . $facebooktoken;
        $graph_url_friends = "https://graph.facebook.com/me/friends?fields=first_name,last_name&access_token=" . $facebooktoken;
        $user_friends = json_decode(file_get_contents($graph_url_friends));

        if (isset($user_friends->data)) {
            $return = $user_friends;
        } else {
            unset($_SESSION['facebook_loggedin']);
        }


        return $return;
    }

    function pagination($per_page = 10, $page = 1, $url = '', $total) {

        $adjacents = "2";

        $page = ($page == 0 ? 1 : $page);
        $start = ($page - 1) * $per_page;

        $prev = $page - 1;
        $next = $page + 1;
        $lastpage = ceil($total / $per_page);
        $lpm1 = $lastpage - 1;

        $pagination = "";
        if ($lastpage > 1) {
            $pagination .= "<ul class='pagination'>";
            $pagination .= "<li class='details'>Page $page of $lastpage</li>";
            if ($lastpage < 7 + ($adjacents * 2)) {
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination.= "<li><a class='current'>$counter</a></li>";
                    else
                        $pagination.= "<li><a href='{$url}$counter'>$counter</a></li>";
                }
            }
            elseif ($lastpage > 5 + ($adjacents * 2)) {
                if ($page < 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                        if ($counter == $page)
                            $pagination.= "<li><a class='current'>$counter</a></li>";
                        else
                            $pagination.= "<li><a href='{$url}$counter'>$counter</a></li>";
                    }
                    $pagination.= "<li class='dot'>...</li>";
                    $pagination.= "<li><a href='{$url}$lpm1'>$lpm1</a></li>";
                    $pagination.= "<li><a href='{$url}$lastpage'>$lastpage</a></li>";
                }
                elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                    $pagination.= "<li><a href='{$url}1'>1</a></li>";
                    $pagination.= "<li><a href='{$url}2'>2</a></li>";
                    $pagination.= "<li class='dot'>...</li>";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                        if ($counter == $page)
                            $pagination.= "<li><a class='current'>$counter</a></li>";
                        else
                            $pagination.= "<li><a href='{$url}$counter'>$counter</a></li>";
                    }
                    $pagination.= "<li class='dot'>..</li>";
                    $pagination.= "<li><a href='{$url}$lpm1'>$lpm1</a></li>";
                    $pagination.= "<li><a href='{$url}$lastpage'>$lastpage</a></li>";
                }
                else {
                    $pagination.= "<li><a href='{$url}1'>1</a></li>";
                    $pagination.= "<li><a href='{$url}2'>2</a></li>";
                    $pagination.= "<li class='dot'>..</li>";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                        if ($counter == $page)
                            $pagination.= "<li><a class='current'>$counter</a></li>";
                        else
                            $pagination.= "<li><a href='{$url}$counter'>$counter</a></li>";
                    }
                }
            }

            if ($page < $counter - 1) {
                $pagination.= "<li><a href='{$url}$next'>Next</a></li>";
// $pagination.= "<li><a href='{$url}$lastpage'>Last</a></li>";
            } else {
//$pagination.= "<li><a class='current'>Next</a></li>";
// $pagination.= "<li><a class='current'>Last</a></li>";
            }
            $pagination.= "</ul>\n";
        }
        return $pagination;
    }

    function sendEmail($to, $cc, $bcc, $from, $replyto, $subject, $message, $content = NULL, $filename = NULL, $folder = NULL) {
        /*         * ********************* CODE RELATED TO MANDRILL MAIL ************************************ */
//$to, $cc, $bcc - list of mail id's seperated by comma (,)
//$from - from name<from mail id>. Ex: Meraevents<admin@meraevents.com> 
//$replyto - single mail id
//$subject - subject of the mail
//$message - message/body of the mail. it may be html  or plain text
//$content -  attached file content. if no attachment is there, then value must be NULL
//$filename - attached file name. if no attachment is there, then value must be NULL
//		if($folder=='ctrl' || $folder=='dashboard' || $folder=='event-ticketsales-accelerator')
//		{
//			include_once ("../../Swift/lib/swift_required.php");
//			include_once('../../webservice/lib/nusoap.php');
//		}
//        elseif ($folder=='mobile') 
//		{
//            include_once ("../../../Swift/lib/swift_required.php");
//			include_once('../../../webservice/lib/nusoap.php');
//        }
//		else  {
//        include_once (_DOC_ROOT . "/Extras/Swift/lib/swift_required.php");
//        include_once(_DOC_ROOT . "/Extras/webservice/lib/nusoap.php");
        require( "../application/libraries/Swift/lib/swift_required.php");
        
//      }

        $transport = Swift_SmtpTransport::newInstance('smtp.mandrillapp.com', 587);
        $transport->setUsername('admin@meraevents.com');
        $transport->setPassword('514cc489-5519-40d1-8f15-dc0721d083f2');
        $swift = Swift_Mailer::newInstance($transport);
        /*         * ********************* CODE RELATED TO MANDRILL MAIL ************************************ */

        $mailto = explode(',', $to);
        foreach ($mailto as $key => $value) {
            $value = trim($value);
            if (strlen(trim($value)) > 0 && filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $mailto[$key] = $value;
            } else {
                unset($mailto[$key]);
            }
        }


        $mailcc = strlen($cc) > 0 ? explode(',', $cc) : array();
        $mailbcc = strlen($bcc) > 0 ? explode(',', $bcc) : array();



        if (strlen($filename) > 0) {
            $filenameEx = explode(".", $filename);

            $fileExtension = end($filenameEx);
        }
        try {
//throw new Exception('shashi');

            $fromEx = explode("<", $from);
            $from_name = $fromEx[0];
            $from_mail = substr($fromEx[1], 0, -1);


            $mess = new Swift_Message($subject);
            $mess->setFrom(array($from_mail => $from_name));
            $mess->setBody($message, 'text/html');
            $mess->addPart($message, 'text/plain');
            $mess->setTo($mailto);
            $mess->setCc($mailcc);
            $mess->setBcc($mailbcc);

            if (strlen($content) > 0) {
                if (strtolower($fileExtension) == 'pdf') {
                    $mess->attach(Swift_Attachment::newInstance($content, $filename, 'application/pdf'));
                } elseif (strtolower($fileExtension) == 'csv') {
                    $mess->attach(Swift_Attachment::newInstance($content, $filename, 'application/csv'));
                }
            }

            $is_sent = $swift->send($mess, $failures);
            if ($is_sent)
                return true;
            else
                return false;
        } catch (Exception $e) {
            /* echo $e->getMessage();
              exit; */
            $uid = md5(uniqid(time()));

            $header = "From:" . $from . "\r\n" .
                    'Cc: ' . $cc . "\r\n" .
                    'Bcc: ' . $bcc . "\r\n";

            if (strlen($replyto) > 0) {
                $header.="Reply-To: " . $replyto . "\r\n";
            }
            $header.='X-Mailer: PHP/' . phpversion() . "\r\n" .
                    "MIME-Version: 1.0\r\n" .
                    "Content-Type: text/html; charset=iso-8859-1\r\n" .
                    "Content-Transfer-Encoding: 8bit\r\n\r\n";

            $fileheaders = '\r\n';
            if (strlen($content) > 0) {

                if (strtolower($fileExtension) == 'pdf') {
                    $content = chunk_split(base64_encode($content));
                    $header = "From:" . $from . "\r\n" .
                            'Cc: ' . $cc . "\r\n" .
                            'Bcc: ' . $bcc . "\r\n";
                    $header .= "MIME-Version: 1.0\r\n";
                    $header .= "Content-Type: multipart/mixed; boundary=\"" . $uid . "\"\r\n\r\n";
                    $header .= "This is a multi-part message in MIME format.\r\n";
                    $header .= "--" . $uid . "\r\n";
                    $header .= "Content-type:text/html; charset=iso-8859-1\r\n";
                    $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
                    $header .= $message . "\r\n\r\n";
                    $header .= "--" . $uid . "\r\n";
                    $header .= "Content-Type: application/pdf; name=\"" . $filename . "\"\r\n";

                    $header .= "Content-Transfer-Encoding: base64\r\n";
                    $header .= "Content-Disposition: attachment; filename=\"" . $filename . "\"\r\n\r\n";
                    $header .= $content . "\r\n\r\n";
                    $header .= "--" . $uid . "--";
                } elseif (strtolower($fileExtension) == 'csv') {
                    $header = "From:" . $from . "\r\n" .
                            'Cc: ' . $cc . "\r\n" .
                            'Bcc: ' . $bcc . "\r\n";
                    $header .= "MIME-Version: 1.0\r\n";
                    $header .= "Content-Type: multipart/mixed; boundary=\"" . $uid . "\"\r\n\r\n";
                    $header .= "This is a multi-part message in MIME format.\r\n";
                    $header .= "--" . $uid . "\r\n";
                    $header .= "Content-type:text/html; charset=iso-8859-1\r\n";
                    $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
                    $header .= $message . "\r\n\r\n";
                    $header .= "--" . $uid . "\r\n";
                    $header.= "Content-Type: application/csv; name=\"" . $filename . "\"\r\n" . // use different content types here
                            "Content-Disposition: attachment; filename=\"" . $filename . "\"\r\n\r\n" .
                            $content . "\r\n\r\n";
                    $header .= "--" . $uid . "--";
                }
            }


            if (@mail($to, $subject, $message, $header))
                return true;
            else
                return false;
        }
    }

    function variable_filter_for_search($variable) {
        $variable = stripslashes(strip_tags($variable));
        $lenn = strlen($variable);
        if ($lenn > 0) {
            if (preg_match('/[^\w\d_ -]/si', $variable)) {
                //deprecated 
                return preg_replace("/[\/\@*?<>%\$]/", "", $variable);
                //return preg_replace('/[^a-zA-Z0-9_@,\s-\.\&\*\(\):\/\+;|=]/s', '', $variable);
                //return preg_replace_callback('/[^a-zA-Z0-9_@,\s-\.\(\):\/\+;|]/s',function(){return '';},$variable);
            } else {
                return preg_replace('/\s/', ' ', $variable);
            }
        } else {
            return trim($variable);
        }
    }

    function variable_filter($variable) {
        $lenn = strlen($variable);
        if ($lenn > 0) {
            if (preg_match('/[^\w\d_ -]/si', $variable)) {
//deprecated 
                return preg_replace('/[^a-zA-Z0-9_@,\s-\.\(\):\/\+;|=]/s', '', $variable);
//return preg_replace_callback('/[^a-zA-Z0-9_@,\s-\.\(\):\/\+;|]/s',function(){return '';},$variable);
            } else {
                return preg_replace('/\s/', ' ', $variable);
            }
        } else {
            return trim($variable);
        }
    }

    /*
     * 0-only strip_tag
     *  
     * 2-only variable_filter
     */

    function stripData($array, $exclude = NULL) {
//  $array=(array)$array;
        foreach ($array as $key => $value) {


//    if($default==1)

            if (is_object($value)) {//if result value is an object
                $value = (array) $value;
            }
            if (is_array($value)) {
                $array[$key] = $this->stripData($value);
            } else {
                if (is_array($exclude)) {
                    if (in_array($key, $exclude)) {

                        continue;
                    }//end of in_array
                }//end of is_array
                $array[$key] = $this->variable_filter(strip_tags($value));
            }
        }

        return $array;
    }

//function to remove all special charecters from a string
    function cleanString($string) {
        $string = str_replace(' ', '', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9]/', '', $string); // Removes special chars.
    }

//returns an json object bytaking input from complete php Input
    public function getJSONinputObject($stripData = true, $exclude = NULL) {
        $jsonOutput = file_get_contents('php://input');
// $passPhrase="testing";
// echo $jsonOutput.'krishna';
        $jsonOutput = $this->decrypt($jsonOutput, _ME_PRIV_KEY, _ME_PASS_PHRASE);





//      echo "------";
//    exit;
        if ($stripData) {
            $jsonOutputArray = (array) json_decode($jsonOutput);
            $jsonOutputString = json_encode($this->stripData($jsonOutputArray, $exclude));
            $jsonOutputObject = json_decode($jsonOutputString);
        } else {
            $jsonOutputObject = json_decode($jsonOutput);
        }
        return $jsonOutputObject;
    }

    public function getNormalJSONinput($stripData = true, $exclude = NULL) {
        $jsonOutput = file_get_contents('php://input');
// $passPhrase="testing";
// $jsonOutput=$this->decrypt($jsonOutput, _IPAY_PRIV_KEY, _IPAY_PASS_PHRASE);
//      echo "------";
//       exit;
        if ($stripData) {
            $jsonOutputArray = (array) json_decode($jsonOutput);
            $jsonOutputString = json_encode($this->stripData($jsonOutputArray, $exclude));
            $jsonOutputObject = json_decode($jsonOutputString);
        } else {
            $jsonOutputObject = json_decode($jsonOutput);
        }
        return $jsonOutputObject;
    }

    function generateRandomString($length = 10, $Globali = NULL) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        if (!is_null($Globali) && $this->check_oid_exists($randomString, $Globali)) {
            $randomString = $this->generateRandomString($length, $Globali);
        }
        return $randomString;
    }
	
	function getServiceTax($global, $signupdatetime){	   
           $recComm = $global->GetSingleFieldValue("select`value` from `commission` where global = 1 and type = 12");			
		if ($signupdatetime > '2016-05-31 18:30:01') {
                      $serTax = $recComm;
                    }else
                    if ($signupdatetime > '2012-03-31 23:59:59' and $signupdatetime < '2016-05-31 18:30:01') {
                        $serTax = 14.5 ;
                    } else {
                        $serTax = 10.3 ;
                    }
		return 	$serTax;		
	}

    function check_oid_exists($oid, $Globali) {
        $status = true;
        if (!empty($oid)) {
            $selOid = "SELECT id from orderlogs where oid='" . $Globali->dbconn->real_escape_string($oid) . "'";
            $oidRes = $Globali->SelectQuery($selOid);
            $status = count($oidRes) > 0 ? true : false;
        }
        return $status;
    }

    function isOrganizerForEvent($eventId, $Globali) {
        $SelectUserQuery = "SELECT UserID FROM events where Id='" . $Globali->dbconn->real_escape_string($eventId) . "' AND UserID='" . $Globali->dbconn->real_escape_string($_SESSION["uid"]) . "'";
        $UserRes = $Globali->SelectQuery($SelectUserQuery);
        if (count($UserRes) == 1) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * To check whether given promotiona code, EventId and userId are same
     */

    function isPromoterForEvent($eventId, $promoterCode, $Globali) {
        $SelectUserQuery = "SELECT uid FROM promoters where eventId='" . $Globali->dbconn->real_escape_string($eventId) . "' AND uid='" . $Globali->dbconn->real_escape_string($_SESSION["uid"]) . "' AND promoter='" . $Globali->dbconn->real_escape_string($promoterCode) . "'";
        $UserRes = $Globali->SelectQuery($SelectUserQuery);
        if (count($UserRes) == 1) {
            return true;
        } else {
            return false;
        }
    }

    function checkCustomFieldsExistsForEvent($eventId, $customId, $Globali) {
        $selectQuery = "SELECT Id FROM eventcustomfields WHERE EventId='" . $eventId . "'";
        $resultSet = $Globali->SelectQuery($selectQuery);
        if (!empty($resultSet) and count($resultSet) > 0) {
            for ($i = 0; $i < count($resultSet); $i++) {
                if (in_array($customId, $resultSet[$i]))
                    return true;
            }
        }
        return false;
    }

    function checkDiscountExistsForEvent($eventId, $discountId, $discountmode, $Globali) {
        $selectQuery = "SELECT Id FROM discounts WHERE EventId='" . $eventId . "' and `Id`='" . $discountId . "' and `discountmode`='" . $discountmode . "'";
        $resultSet = $Globali->SelectQuery($selectQuery);
        if (count($resultSet) > 0) {
            return true;
        } else {
            return false;
        }
    }

    function isPastEvent($eventId, $Globali) {
        $SelectUserQuery2 = "SELECT e.UserID FROM events e LEFT JOIN event_collaborators ec ON ec.eventid=e.id where e.Id='" . $Globali->dbconn->real_escape_string($eventId) . "'  AND (e.UserID='" . $Globali->dbconn->real_escape_string($_SESSION["uid"]) . "' or (e.id=ec.eventid and ec.status=1))  and e.EndDt > now()";
        $UserRes2 = $Globali->SelectQuery($SelectUserQuery2);
        if (count($UserRes2) > 0) {
            return false;
        } else {
            return true;
        }
    }

    function isSuperAdmin() {
        //if ($_SESSION['uid'] == 2) {
        if ($_SESSION['adminUserType'] == 'superadmin') {
            return true;
        } else {
            return false;
        }
    }
    
    function isSupportTeam() {
        $supportArray=array('support@meraevents.com');
        if (in_array($_SESSION['useremail'], $supportArray)) {
            return true;
        } else {
            return false;
        }
    }

    function isAdmin() {
        //if ($_SESSION['uid'] == 2 || $_SESSION['uid'] == 1) {
        if ($_SESSION['adminUserType'] == 'superadmin' || $_SESSION['adminUserType'] == 'admin') {
            return true;
        } else {
            return false;
        }
    }

    function login_check($Globali) {
// Check if all session variables are set 
        if (isset($_SESSION['uid'], $_SESSION['username'], $_SESSION['login_string'])) {

            $status = false;
            $user_id = $_SESSION['uid'];
            $login_string = $_SESSION['login_string'];
            $username = $_SESSION['username'];

// Get the user-agent string of the user.
            $user_browser = $_SERVER['HTTP_USER_AGENT'];
// $Globali = new cGlobali();
//$Globali->dbconn = new mysqli($Global->DBServerNameOnly, $Global->DBUserName,$Global->DBPassword,$Global->DBIniCatalog,$Global->portNumber);
//$Globali->dbconn->connect();


            $stmt = $Globali->dbconn->stmt_init();
            if ($stmt->prepare("SELECT `Password` 
                      FROM `user` 
                      WHERE `Id` = ? LIMIT 1")) {
// Bind "$user_id" to parameter. 
                $stmt->bind_param('i', $user_id);
                $stmt->execute();   // Execute the prepared query.
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    // If the user exists get variables from result.
                    $stmt->bind_result($password);
                    $stmt->fetch();
                    $login_check = hash('sha512', $password . $user_browser);

                    if ($login_check == $login_string) {
                        // Logged In!!!! 
                        $status = true;
                    } else {
                        // Not logged in 
                        $status = false;
                    }
                } else {
                    // Not logged in 
                    $status = false;
                }
            } else {
// Not logged in 
                $status = false;
            }
        } else {
// Not logged in 
            $status = false;
        }
        if (!$status) {
            unset($_SESSION['uid']);
            unset($_SESSION['username']);
            unset($_SESSION['login_string']);
        }
        return $status;
    }

    function special_login($Globali, $uid) {
        if ($uid > 0) {
//making login function more secured -pH
// Get the user-agent string of the user.
            $user_browser = $_SERVER['HTTP_USER_AGENT'];
// XSS protection as we might print this value
            $user_id = preg_replace("/[^0-9]+/", "", $uid);
            $_SESSION['uid'] = $user_id;
// XSS protection as we might print this value

            $sql = "select `username`,`password` from `user` where `Id`='$uid'";

            $res = $Globali->selectQuery($sql);

            if (count($res) == 1) {
                $username = $res[0]['username'];
                $password = $res[0]['password'];

                $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);
                $_SESSION['username'] = $username;
                $_SESSION['login_string'] = hash('sha512', $password . $user_browser);
// Login successful.
//return $this->Id;
                return true;
            } else
                return false;
        } else {
            return false;
        }
    }

    function isDhamaalRequest() {
        if ((strpos($_SERVER['HTTP_HOST'], 'dhamaal.stage.meraevents.com') !== false) || (strpos($_SERVER['HTTP_HOST'], 'dhamaal.meraevents.com') !== false)) {
//if(strcmp(array_shift(explode(".",$_SERVER['HTTP_HOST'])),"dhamaal")===0)
//        if(strpos($_SERVER['HTTP_HOST'], 'localhost') !== false)
            return true;
        } else {
            return false;
        }
    }

    function includeHeader() {
//   var_dump($this->isDhamaalRequest());
//   echo $_SERVER['HTTP_HOST'];
        if ($this->isDhamaalRequest()) {
            return 'includes/dheader.php';
//include ('includes/dheader.php'); 
        } else {
            return 'includes/header.php';
//include ('includes/header.php');
        }
    }

    function includeFooter() {
        if ($this->isDhamaalRequest()) {
            return 'includes/dfooter.php';
        } else {
            return 'includes/footer.php';
        }
    }

// function to add any (in this case 'rel') attribute dynamically to all the anchor links
// for preview event description
    function addRELattrToAllAnchorLinks($str) {
        $dom = new DOMDocument;
        $dom->loadHTML($str);
        $anchors = $dom->getElementsByTagName('a');
        foreach ($anchors as $anchor) {
            $rel = array();
            if ($anchor->hasAttribute('rel') AND ( $relAtt = $anchor->getAttribute('rel')) !== '') {
                $rel = preg_split('/\s+/', trim($relAtt));
            }
            if (in_array('nofollow', $rel)) {
                continue;
            }
            $rel[] = 'nofollow';
            $anchor->setAttribute('rel', implode(' ', $rel));
        }

//var_dump($dom->saveHTML());
        return $dom->saveHTML();
    }

    function endsWith($haystack, $needle) {
        return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
    }

    function sendUberMail($email, $Globali, $justView = false) {
        $message = $Globali->GetSingleFieldValue("select `Msg` from `EMailMsgs` where `MsgType`='emUber'");
        $message = str_replace("images", _HTTP_CF_ROOT . "/images", $message);
        $message = str_replace("uberOnlinePage", _HTTP_SITE_ROOT . "/uber/index.php", $message);
        $message = str_replace("meraeventsHome", _HTTP_SITE_ROOT . "/Home", $message);

        if ($justView) {
            echo $message;
            exit;
        }

        $subject = "Ride to your event in luxury car for free!";
        $bcc = 'qison@meraevents.com';
        $from = 'MeraEvents<admin@meraevents.com>';
        $cc = $content = $filename = $replyto = NULL;
//   $cc = NULL;
        $this->sendEmail($email, $cc, $bcc, $from, $replyto, $subject, $message, $content, $filename);
    }

    function isUberAvailable($cityId, $eventId, $Globali) {
        /*
         * 14 	Mumbai
         * 37 	Bengaluru
         * 38 	NewDelhi
         * 39 	Chennai
         * 47 	Hyderabad
         * 321  Noida
         * 55   Gurgaon
         * 397  New Delhi
         */
        /* Event Ids that dont need uber mails
         *  57182
         *  57180
         *  57176
         *  57173
         *  57170
         */
        $ubermailsQuery = "SELECT `sendUberMails` FROM events where Id='" . $eventId . "'";
        $sendUberMails = $Globali->GetSingleFieldValue($ubermailsQuery);
        $availableCityIds = array(14, 37, 38, 39, 47, 321, 55, 397);
//$noUberEvents=array(57182, 57180, 57176, 57173, 57170,33087);
        if (in_array($cityId, $availableCityIds) && $sendUberMails == 1) {
            return true;
        } else {
            return false;
        }
    }

    function getBitlyURL($url) { //function to get Bitly URL
        /* URL: http://bitly.com
          Username: QisonMera/qison@meraevents.com
          Pwd: Mera@2014 */


//$url=urlencode($url);
        $api_user = "qisonmera";
        $api_key = 'R_0cb67f81aa1941a184caebeb91da92b4';

//send it to the bitly shorten webservice
        $ch = curl_init("http://api.bitly.com/v3/shorten?login=$api_user&apiKey=$api_key&longUrl=$url&format=json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

//the response is a JSON object, send it to your webpage
        $res = curl_exec($ch);
        $result = json_decode($res, true);

        if (isset($result['status_code']) && isset($result['data'])) {
            if (count($result['data']) > 0) {
                return $result['data']['url'];
            } else {
                return $url;
            }
        } else {
            return $url;
        }
//return $result;
    }

    function decrypt($encrypted, $privatekeyPath, $passPhrase) {

// Get the private Key
        if (!$privateKey = openssl_pkey_get_private($privatekeyPath, $passPhrase)) {
            die('Private Key failed');
        }
        $a_key = openssl_pkey_get_details($privateKey);

// Decrypt the data in the small chunks
        $chunkSize = ceil($a_key['bits'] / 8);
        $output = '';

        while ($encrypted) {
            $chunk = substr($encrypted, 0, $chunkSize);
            $encrypted = substr($encrypted, $chunkSize);
            $decrypted = '';
            if (!openssl_private_decrypt($chunk, $decrypted, $privateKey)) {
                die('Failed to decrypt data');
            }
            $output .= $decrypted;
        }
        openssl_free_key($privateKey);

// Uncompress the unencrypted data.
        $output = gzuncompress($output);

        return $output;
    }

    function encrypt($data, $publicKeyPath) {

        $plaintext = gzcompress($data);

// Get the public Key of the recipient
        $publicKey = openssl_pkey_get_public($publicKeyPath);
        $a_key = openssl_pkey_get_details($publicKey);

// Encrypt the data in small chunks and then combine and send it.
        $chunkSize = ceil($a_key['bits'] / 8) - 11;
        $output = '';

        while ($plaintext) {
            $chunk = substr($plaintext, 0, $chunkSize);
            $plaintext = substr($plaintext, $chunkSize);
            $encrypted = '';
            if (!openssl_public_encrypt($chunk, $encrypted, $publicKey)) {
                die('Failed to encrypt data');
            }
            $output .= $encrypted;
        }
        openssl_free_key($publicKey);

// This is the final encrypted data to be sent to the recipient
        $encrypted = $output;


        return $encrypted;
    }

//function to get tinyurl for long URL
    function get_tiny_url($url) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, 'http://tinyurl.com/api-create.php?url=' . $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    function formatUrl($str, $sep = '-') {
        $res = strtolower($str);
//$res = preg_replace('/[^[:alnum:]]/', ' ', $res);
        $res = preg_replace('/[[:space:]]+/', $sep, $res);
        return trim($res, $sep);
    }

//promoter details
    function get_promoter_code($uid, $eventId, $Globali, $status = 1, $type = 'online') {
        $promoterCode = $Globali->GetSingleFieldValue("SELECT promoter FROM promoters WHERE uid='" . $Globali->dbconn->real_escape_string($uid) . "' AND eventId='" . $Globali->dbconn->real_escape_string($eventId) . "' AND status='" . $status . "' and type='" . $type . "'");
        return $promoterCode;
    }

    /*     * ****
     * Send delegate details on ebsresponse page to other server through api
     * *** */

    function send_delegate_details_through_api($data, $url) {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
# Setup request to send json via POST.
        $payload = $data;
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:text/xml'));
# Return response instead of printing.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
# Send request.
        $result = curl_exec($ch);
        if ($result === FALSE)
            return false;
        curl_close($ch);
    }

//function to calculate Discount amount for an event
    function applyDiscount($EventId, $txtPromoCode, $arrTicketsId, $tot_amt, $reffCode, $Globali, $page = NULL) {
        $totalDiscount = '0.00';
        $currentTicketIds = NULL;
        $selectedTickets = array();
        $discountError = 0;


        foreach ($arrTicketsId as $tid => $tqty) {
            if ($tqty > 0) {
                $selectedTickets[] = $tid;
            }
        }
        $currentTicketIds = implode(",", $selectedTickets);


        if (!empty($currentTicketIds)) {
            if (count($selectedTickets) > 0) {
                $sqlTktIds = ' AND dat.ticketid IN (' . $currentTicketIds . ')';
            } else {
                echo $totalDiscount;
                exit;
            }


            $sqlPromoCodeAdd = '';
            if (strlen($txtPromoCode) > 0) {
                $sqlPromoCodeAdd.=" AND (d.code = '" . $txtPromoCode . "' or d.code = '')";
            } else {
                $sqlPromoCodeAdd.=" AND d.type='bulk' ";
            }


            $date = date('Y-m-d H:i:s');


            $sqlDiscounts = "SELECT d.id 'Discount Id', d.usagelimit UsageLimit, d.totalused DiscountLevel, dat.ticketid TicketsId, d.type discountmode, d.calculationmode DiscountType, d.value DiscountAmt, d.minticketstobuy tickets_from, t.price Price,
        IF (d.maxticketstobuy =0, t.maxorderquantity, d.maxticketstobuy) AS 'tickets_to',
        IF (d.calculationmode =0, d.value, ((d.value * t.price)/100)) AS 'MaxDiscountAmount'
        FROM `discount` AS d
        RIGHT JOIN `ticketdiscount` AS dat ON d.id = dat.`discountid`
        RIGHT JOIN `ticket` AS t ON dat.ticketid = t.id
        WHERE d.eventid = '" . $EventId . "'
        AND d.status= 1
        AND d.usagelimit > d.totalused
        $sqlPromoCodeAdd
        AND d.startdatetime < '" . $date . "'
        AND d.enddatetime > '" . $date . "'
        $sqlTktIds  order by MaxDiscountAmount desc";

//echo $sqlDiscounts."<br>";

            $ResDiscounts = $Globali->SelectQuery($sqlDiscounts);
            $UsageLimit = $ResDiscounts[0]['UsageLimit'] - $ResDiscounts[0]['DiscountLevel'];
            if ($UsageLimit < 0)
                $UsageLimit = 0;

            $promocode = '';
            $tktsAndDiscountsArr = array();


            $promo_code_records = 0;



            if (count($ResDiscounts) > 0) {

                //for loop to confirm if its a specific rule or intersects or inbetween.
                $counter = 0; // 0 = inbetween , 1 = specific, >1 intersection
                $intersect = NULL;





                $modeid = NULL;

                foreach ($ResDiscounts as $key => $val) {
                    $newarray[$val['TicketsId']]['counter'] = 0;
                    $newarray[$val['TicketsId']]['lastnumber'] = 0;
                    $newarray[$val['TicketsId']]['intersect'] = NULL;
                    $newarray[$val['TicketsId']]['choose'] = 0;

                    if (isset($newarray[$val['TicketsId']]['discountid']))
                        $newarray[$val['TicketsId']]['discountid'].=',' . $val['Discount Id'];
                    else
                        $newarray[$val['TicketsId']]['discountid'] = $val['Discount Id'];

                    if (isset($newarray[$val['TicketsId']]['type']))
                        $newarray[$val['TicketsId']]['type'].=',' . $val['discountmode'];
                    else
                        $newarray[$val['TicketsId']]['type'] = $val['discountmode'];

                    if ($val['discountmode'] == 'normal') {
                        $promo_code_records++;
                    }
                }

                foreach ($ResDiscounts as $keys => $vals) {
                    $tktQty = $arrTicketsId[$vals['TicketsId']];       // current look ticket qty
                    //$MaxDiscountAmountPerTkt=$tktQty*$val['MaxDiscountAmount'];   //current loop discount
                    //echo $keys."#".$vals['TicketsId']."#".$tktQty."-";
                    $newarray[$vals['TicketsId']]['tktQty'] = $tktQty;


                    if ($tktQty >= $vals['tickets_to'] && strcmp($vals['discountmode'], 'normal') != 0) {  //setting last number variable for inbetween scenario
                        if (strcmp($newarray[$vals['TicketsId']]['lastnumber'], '0') != 0) {
                            $ex = explode(',', $newarray[$vals['TicketsId']]['lastnumber']);
                            if ((int) $vals['tickets_to'] > (int) $ex[1])
                                $newarray[$vals['TicketsId']]['lastnumber'] = $keys . ',' . $vals['tickets_to'];
                        } else
                            $newarray[$vals['TicketsId']]['lastnumber'] = $keys . ',' . $vals['tickets_to'];

                        $newarray[$vals['TicketsId']]['choose'] = 1;

                        $modeid.=',' . $vals['Discount Id'];
                    }


                    if (($tktQty >= $vals['tickets_from'] && $tktQty <= $vals['tickets_to']) && strcmp($vals['discountmode'], 'normal') != 0) {
                        $newarray[$vals['TicketsId']]['counter'] = $newarray[$vals['TicketsId']]['counter'] + 1;
                        $newarray[$vals['TicketsId']]['intersect'].=$keys . ',';

                        $modeid.=',' . $vals['Discount Id'];
                        $newarray[$vals['TicketsId']]['choose'] = 2;
                    }

                    if (strcmp($vals['discountmode'], 'normal') == 0) {
                        $newarray[$vals['TicketsId']]['normaldiscount'] = $vals['DiscountAmt'];
                        $newarray[$vals['TicketsId']]['discounttype'] = $vals['DiscountType'];
                        $newarray[$vals['TicketsId']]['price'] = $vals['Price'];
                        $newarray[$vals['TicketsId']]['normalcounter'] = '-';
                        $modeid.=',' . $vals['Discount Id'];
                    }
                }


//print_r($newarray);


                foreach ($newarray as $key => $val) {

                    if (strcmp($val['type'], 'normal') != 0) {
                        $tktQty = $val['tktQty'];

                        if (strcmp($val['counter'], '0') == 0 && strcmp($val['choose'], '1') == 0) { //in between 
                            $last = explode(',', $val['lastnumber']);
                            $tktsAndDiscountsArr[$key]['totaldiscount'] = $tktQty * $ResDiscounts[$last[0]]['MaxDiscountAmount'];
                            $tktsAndDiscountsArr[$key]['discountmode'] = $ResDiscounts[$last[0]]['discountmode'];


                            $tktsAndDiscountsArr[$key]['DiscountId'] = $ResDiscounts[$last[0]]['Discount Id'];
                        }

                        if ($val['counter'] == 1) { //if specific 
                            $specific = substr($val['intersect'], 0, -1);
                            $tktsAndDiscountsArr[$key]['totaldiscount'] = $tktQty * $ResDiscounts[$specific]['MaxDiscountAmount'];
                            $tktsAndDiscountsArr[$key]['discountmode'] = $ResDiscounts[$specific]['discountmode'];
                            $tktsAndDiscountsArr[$key]['DiscountId'] = $ResDiscounts[$specific]['Discount Id'];
                        }

                        if ($val['counter'] > 1) { //if intersect

                            $interEx = explode(',', substr($val['intersect'], 0, -1));

                            $insersetMax = array();

                            foreach ($interEx as $rowMatch) {
                                if (strcmp($ResDiscounts[$rowMatch]['discountmode'], 'normal') != 0)
                                    $insersetMax[$rowMatch] = $tktQty * $ResDiscounts[$rowMatch]['MaxDiscountAmount'];
                            }


                            $tktsAndDiscountsArr[$key]['totaldiscount'] = max($insersetMax);
                            $tktsAndDiscountsArr[$key]['discountmode'] = $ResDiscounts[array_search(max($insersetMax), $insersetMax)]['discountmode'];
                            $tktsAndDiscountsArr[$key]['DiscountId'] = $ResDiscounts[array_search(max($insersetMax), $insersetMax)]['Discount Id'];
                        }
                    }//end of normaldiscount if condition
                }



                foreach ($newarray as $key => $val) {
                    $tktQty = $val['tktQty'];
                    if (isset($val['normaldiscount'])) {

                        if ($val['discounttype'] == 1) {
                            $totalamount = $val['price'];
                            $amountafterbulk = $totalamount - (($tktsAndDiscountsArr[$key]['totaldiscount']) / $tktQty);
                            $normaldiscount = ($amountafterbulk * $val['normaldiscount']) / 100;
                        } else
                            $normaldiscount = $val['normaldiscount'];
                        $maxticketsfordiscount = 0;
                        if ($tktQty >= $UsageLimit) {
                            $maxticketsfordiscount = $UsageLimit;
                            $UsageLimit = 0;
                        } else {
                            if ($tktQty < $UsageLimit) {
                                $maxticketsfordiscount = $tktQty;
                                $UsageLimit = $UsageLimit - $tktQty;
                            }
                        }
                        $ndis = $maxticketsfordiscount * $normaldiscount;
                        $ntype = 'normal';

                        //  $tktsAndDiscountsArr[$key]['DiscountId']=$val['discountid'];	
                        //echo $tktsAndDiscountsArr[$key]['totaldiscount'].'---';
                        if (isset($tktsAndDiscountsArr[$key]['totaldiscount'])) {

                            $tktsAndDiscountsArr[$key]['totaldiscount'] = $ndis + $tktsAndDiscountsArr[$key]['totaldiscount'];
                            $tktsAndDiscountsArr[$key]['discountmode'] = 'bulk,normal';
                        } else {
                            $tktsAndDiscountsArr[$key]['totaldiscount'] = $ndis;
                            $tktsAndDiscountsArr[$key]['discountmode'] = $ntype;
                        }

                        if (!isset($tktsAndDiscountsArr[$key]['promodiscount']))
                            $tktsAndDiscountsArr[$key]['promodiscount'] = $ndis;
                        else
                            $tktsAndDiscountsArr[$key]['promodiscount']+=$ndis;
                    }
                }
            }

            //code to check whether reffcode is valid or not
            $dataReffChk = 0;
            if (strlen($reffCode) > 0 && $reffCode != '0') {
                $sqlReffChk = "select id from eventsignup where `referralcode`='" . $reffCode . "'";
                $dataReffChk = count($Globali->SelectQuery($sqlReffChk));
            }



            //code to calculate service tax
            if (strlen($reffCode) > 0 && $reffCode != '0' && $dataReffChk > 0) {
                $sqlST = "SELECT t.id Id, t.price Price , t.`ServiceTax` , t.`ServiceTaxValue` , t.`EntertainmentTax` , t.`EntertainmentTaxValue` , 
			IFNULL(IF (aet.`commissiontype` =0, aet.`commissionvalue`, ((aet.`commissionvalue` * t.Price)/100)),0) AS 'reffAmount',aet.status 
			FROM ticket AS t
			LEFT JOIN `viralticketsetting` AS aet ON t.Id = aet.ticketid
			WHERE t.`Id`
			IN (" . $currentTicketIds . ")";
            } else {
                $sqlST = "select id as `Id`,price as `Price` from ticket where `id` in (" . $currentTicketIds . ")";
            }

            //echo $sqlST;
            //$sqlST="select `Id`,`Price`,`ServiceTax`,`ServiceTaxValue` from tickets where `Id` in (".$currentTicketIds.")";
            $dataST = $Globali->SelectQuery($sqlST);


            foreach ($dataST as $skey => $sval) {
                if ($sval['status'] == 1) {
                    $reffDiscount = (isset($sval['reffAmount'])) ? $sval['reffAmount'] : 0;
                } else {
                    $reffDiscount = 0;
                }
                //echo $reffDiscount;

                if (array_key_exists($sval['Id'], $tktsAndDiscountsArr)) {
                    $tktsAndDiscountsArr[$sval['Id']]['totalTicketPrice'] = $arrTicketsId[$sval['Id']] * $sval['Price'];
                    $tktsAndDiscountsArr[$sval['Id']]['referralDiscountAmount'] = $arrTicketsId[$sval['Id']] * $reffDiscount;

                    $updatedPrice = (($sval['Price'] * $arrTicketsId[$sval['Id']]) - ($reffDiscount * $arrTicketsId[$sval['Id']]) - $tktsAndDiscountsArr[$sval['Id']]['totaldiscount']);
                    $issetEntTax = $entTax = 0;
                    if ($sval['EntertainmentTax'] == 1) {
                        $tktsAndDiscountsArr[$sval['Id']]['EntertainmentTax'] = (($updatedPrice * $sval['EntertainmentTaxValue']) / 100);
                        $entTax = $tktsAndDiscountsArr[$sval['Id']]['EntertainmentTax'];
                        $issetEntTax = 1;
                    } else {
                        $tktsAndDiscountsArr[$sval['Id']]['EntertainmentTax'] = 0;
                    }



                    if ($sval['ServiceTax'] == 1) {
                        if ($issetEntTax == 1) {
                            $tktsAndDiscountsArr[$sval['Id']]['ServiceTax'] = ((($updatedPrice + $entTax) * $sval['ServiceTaxValue']) / 100);
                        } else {
                            $tktsAndDiscountsArr[$sval['Id']]['ServiceTax'] = (($updatedPrice * $sval['ServiceTaxValue']) / 100);
                        }
                    } else {
                        $tktsAndDiscountsArr[$sval['Id']]['ServiceTax'] = 0;
                    }
                } else {
                    $tktsAndDiscountsArr[$sval['Id']]['totalTicketPrice'] = $arrTicketsId[$sval['Id']] * $sval['Price'];
                    $tktsAndDiscountsArr[$sval['Id']]['totaldiscount'] = $tktsAndDiscountsArr[$sval['Id']]['promodiscount'] = 0;
                    $updatedPrice = $sval['Price'] * $arrTicketsId[$sval['Id']] - ($reffDiscount * $arrTicketsId[$sval['Id']]);
                    $tktsAndDiscountsArr[$sval['Id']]['referralDiscountAmount'] = $arrTicketsId[$sval['Id']] * $reffDiscount;

                    $issetEntTax = $entTax = 0;

                    if ($sval['EntertainmentTax'] == 1) {
                        $tktsAndDiscountsArr[$sval['Id']]['EntertainmentTax'] = ($updatedPrice * $sval['EntertainmentTaxValue']) / 100;
                        $issetEntTax = 1;
                        $entTax = $tktsAndDiscountsArr[$sval['Id']]['EntertainmentTax'];
                    } else {
                        $tktsAndDiscountsArr[$sval['Id']]['EntertainmentTax'] = 0;
                    }
                    if ($sval['ServiceTax'] == 1) {
                        if ($issetEntTax == 1) {
                            $tktsAndDiscountsArr[$sval['Id']]['ServiceTax'] = (( ($updatedPrice + $entTax) * $sval['ServiceTaxValue']) / 100);
                        } else {
                            $tktsAndDiscountsArr[$sval['Id']]['ServiceTax'] = ($updatedPrice * $sval['ServiceTaxValue']) / 100;
                        }
                    } else {
                        $tktsAndDiscountsArr[$sval['Id']]['ServiceTax'] = 0;
                    }
                }
            }




            $finalTotalDiscount = $returnBulkDiscount = $returnNormalDiscount = $returnReferralDiscount = $returnServiceTax = $returnTotalTktPrice = 0;
            foreach ($tktsAndDiscountsArr as $idividualDiscount) {
                $returnBulkDiscount+=$idividualDiscount['totaldiscount'] - $idividualDiscount['promodiscount'];

                $returnNormalDiscount+=$idividualDiscount['promodiscount'];
                $finalTotalDiscount+=$idividualDiscount['totaldiscount'];
                $returnReferralDiscount+=$idividualDiscount['referralDiscountAmount'];
                $returnServiceTax+=$idividualDiscount['ServiceTax'];
                $returnEntertainmentTax+=$idividualDiscount['EntertainmentTax'];
                $returnTotalTktPrice+=$idividualDiscount['totalTicketPrice'];
                if ($idividualDiscount['discountmode'] == 'bulk') {
                    $promocodeApplied = 'bulk';
                }
            }

            if (strlen($txtPromoCode) > 0 && $promo_code_records == 0) {
                $discountError = 1;
            }

            $purchaseTotal = $returnTotalTktPrice - $finalTotalDiscount + $returnServiceTax + $returnEntertainmentTax - $returnReferralDiscount;
            if ($purchaseTotal < 0) {
                $purchaseTotal = 0;
            }
            $returnArr = array("totalST" => number_format($returnServiceTax, 2, '.', ''), "totalET" => number_format($returnEntertainmentTax, 2, '.', ''), "totalNormalDiscount" => number_format($returnNormalDiscount, 2, '.', ''), "totalBulkDiscount" => number_format($returnBulkDiscount, 2, '.', ''), "totalReferralDiscount" => number_format($returnReferralDiscount, 2, '.', ''), "purchaseTotal" => number_format($purchaseTotal, 2, '.', ''), "discountError" => $discountError);



            //print_r($tktsAndDiscountsArr);

            if ($page == 'payment') {
                return serialize($tktsAndDiscountsArr) . "^^" . $promocodeApplied;
            } elseif ($page == "offline") {
                return json_encode($returnArr, true);
                $totalDiscount = number_format($returnBulkDiscount, 2, '.', '');
                //if($totalDiscount>$tot_amt){ echo $tot_amt.'.00'; }else{echo $totalDiscount;}
            } else {
                echo json_encode($returnArr, true);
                $totalDiscount = number_format($returnBulkDiscount, 2, '.', '');
                //if($totalDiscount>$tot_amt){ echo $tot_amt.'.00'; }else{echo $totalDiscount;}
            }
        } else {
            if (strlen($txtPromoCode) > 0 && $promo_code_records == 0) {
                $discountError = 1;
            }
            $returnArr = array("totalST" => '0.00', "totalET" => '0.00', "totalNormalDiscount" => '0.00', "totalBulkDiscount" => '0.00', "totalReferralDiscount" => '0.00', "purchaseTotal" => '0.00', "discountError" => $discountError);
            echo json_encode($returnArr, true);
        }


//$totalDiscount = 0;
    }

    function calculateDiscount($amount, $discount) {
        $discount = ($amount / 100) * $discount;
        return $discount;
    }

    function bookingCalculations($EventId, $txtPromoCode, $arrTicketsId, $tot_amt, $reffCode, $Globali, $page = NULL) {

        $totalDiscount = '0.00';
        $currentTicketIds = NULL;
        $selectedTickets = array();
        $discountError = 0;

        foreach ($arrTicketsId as $tid => $tqty) {
            if ($tqty > 0) {
                $selectedTickets[] = $tid;
            }
        }
        $currentTicketIds = implode(",", $selectedTickets);

        if (count($selectedTickets) > 0) {
            $sqlTktIds = ' AND dat.ticketid IN (' . implode(",", $selectedTickets) . ')';
        } else {
            $returnArr = array("totalST" => '0.00', "totalET" => '0.00', "totalNormalDiscount" => '0.00', "totalBulkDiscount" => '0.00', "totalReferralDiscount" => '0.00', "purchaseTotal" => '0.00', "discountError" => 0);
        }



        if (!empty($currentTicketIds)) {

            /* Taxes calculation 
			$sqlST = "select t.`id` ,tax.label,tm.`value`,t.`price` 
from ticket t
left join tickettax tt on tt.`ticketid` =  t.`id`
left join taxmapping tm on tm.`id` = tt.`taxmappingid` 
left join tax on tax.`id` = tm.`taxid` where t.`id` in (" . $currentTicketIds . ")";
			
			*/
            $sqlST = "select `id` Id,`price` Price,`ServiceTax`, "
                    . "`ServiceTaxValue`, `EntertainmentTax` , "
                    . "`EntertainmentTaxValue` "
                    . "FROM ticket "
                    . "WHERE `id` IN (" . $currentTicketIds . ")";

            $dataST = $Globali->SelectQuery($sqlST);

            $taxesArr = $finalArr = array();
            foreach ($dataST as $skey => $sval) {
                if (array_key_exists($sval['Id'], $arrTicketsId)) {
                    $taxesArr[$sval['Id']]['promodiscount'] = $taxesArr[$sval['Id']]['referralDiscountAmount'] = 0;

                    $selTktQty = $arrTicketsId[$sval['Id']];
                    $totTktPrice = $selTktQty * $sval['Price'];
                    $taxesArr[$sval['Id']]['totalTktPrice'] = $totTktPrice;
                    if ($sval['EntertainmentTax'] == 1) {
                        $taxesArr[$sval['Id']]['EntertainmentTax'] = $entTax = (($totTktPrice * $sval['EntertainmentTaxValue']) / 100);
                    } else {
                        $taxesArr[$sval['Id']]['EntertainmentTax'] = $entTax = 0;
                    }

                    //$totTktPrice+=$entTax;
                    //$finalArr['purchaseTotal']=$totTktPrice;

                    if ($sval['ServiceTax'] == 1) {
                        $taxesArr[$sval['Id']]['ServiceTax'] = $stTax = (($totTktPrice * $sval['ServiceTaxValue']) / 100);
                    } else {
                        $taxesArr[$sval['Id']]['ServiceTax'] = $stTax = 0;
                    }

                    $totTktPrice+=$stTax;
                    $taxesArr[$sval['Id']]['updatedTktPrice'] = $totTktPrice;
                }
            }

            //print_r($taxesArr);

            /* Taxes calculation */


            /* Discounts calculation */

            $sqlPromoCodeAdd = '';
            $promo_code_records = 0;
            if (strlen($txtPromoCode) > 0) {
                $date = date('Y-m-d H:i:s');
                $sqlPromoCodeAdd.=" AND (d.code = '" . $txtPromoCode . "' or d.code = '')";

                $sqlDiscounts = "SELECT t.price AS Price, d.id 'Discount Id',d.usagelimit UsageLimit,d.totalused DiscountLevel, dat.ticketid TicketsId, d.type discountmode, d.calculationmode DiscountType,
			 d.value DiscountAmt, d.minticketstobuy tickets_from, d.maxticketstobuy tickets_to,
				CASE WHEN d.calculationmode ='flat' THEN d.DiscountAmt
					 WHEN d.calculationmode ='percentage' THEN ((d.DiscountAmt * t.Price)/100)
				END 'MaxDiscountAmount'
				FROM `discount` AS d
				RIGHT JOIN `ticketdiscount` AS dat ON d.Id = dat.`discountid`
				RIGHT JOIN `ticket` AS t ON dat.ticketid = t.Id
				WHERE d.eventid = '" . $EventId . "'
				AND d.usagelimit > d.totalused
				$sqlPromoCodeAdd
				AND d.startdatetime < '" . $date . "'
				AND d.enddatetime > '" . $date . "'
				$sqlTktIds  order by MaxDiscountAmount desc";

                $ResDiscounts = $Globali->SelectQuery($sqlDiscounts);
                //print_r($ResDiscounts);


                    foreach ($ResDiscounts as $indDiscounts) {
                    if (array_key_exists($indDiscounts['TicketsId'], $taxesArr)) {
                        $UsageLimit = $indDiscounts['UsageLimit'] - $indDiscounts['DiscountLevel'];
                        if ($UsageLimit <= 0)
                            $UsageLimit = 0;

                        $tktQty = $arrTicketsId[$indDiscounts['TicketsId']];
                        if ($UsageLimit >= $tktQty) {
                            $taxesArr[$indDiscounts['TicketsId']]['promodiscount'] = $taxesArr[$indDiscounts['TicketsId']]['totaldiscount'] = $tktQty * $indDiscounts['MaxDiscountAmount'];
                            $taxesArr[$indDiscounts['TicketsId']]['discountmode'] = "normal";
                            $promo_code_records++;
                        } elseif ($UsageLimit < $tktQty) {
                            $taxesArr[$indDiscounts['TicketsId']]['promodiscount'] = $taxesArr[$indDiscounts['TicketsId']]['totaldiscount'] = $UsageLimit * $indDiscounts['MaxDiscountAmount'];
                            $taxesArr[$indDiscounts['TicketsId']]['discountmode'] = "normal";
                            $promo_code_records++;
                        }
                    } else {
                        $taxesArr[$indDiscounts['TicketsId']]['promodiscount'] = $taxesArr[$indDiscounts['TicketsId']]['totaldiscount'] = 0;
                    }
                }
            }


            /* Discounts calculation */

            $finalTotalDiscount = $returnBulkDiscount = $returnNormalDiscount = $returnReferralDiscount = $returnServiceTax = $returnTotalTktPrice = 0;
            foreach ($taxesArr as $idividualDiscount) {
                $returnNormalDiscount+=$idividualDiscount['promodiscount'];
                $returnServiceTax+=$idividualDiscount['ServiceTax'];
                $returnEntertainmentTax+=$idividualDiscount['EntertainmentTax'];
                $returnTotalTktPrice+=$idividualDiscount['totalTktPrice'];
            }

            if (strlen($txtPromoCode) > 0 && $promo_code_records == 0) {
                $discountError = 1;
            }

            $purchaseTotal = $returnTotalTktPrice + $returnServiceTax + $returnEntertainmentTax - $returnNormalDiscount;
            if ($purchaseTotal < 0) {
                $purchaseTotal = 0;
            }
            $returnArr = array("totalST" => number_format($returnServiceTax, 2, '.', ''), "totalET" => number_format($returnEntertainmentTax, 2, '.', ''), "totalNormalDiscount" => number_format($returnNormalDiscount, 2, '.', ''), "totalBulkDiscount" => 0.00, "totalReferralDiscount" => 0.00, "purchaseTotal" => number_format($purchaseTotal, 2, '.', ''), "discountError" => $discountError);



            //print_r($tktsAndDiscountsArr);

            if ($page == 'payment') {
                return serialize($taxesArr) . "^^" . $promocodeApplied;
            } elseif ($page == "offline") {
                return json_encode($returnArr, true);
                $totalDiscount = number_format($returnBulkDiscount, 2, '.', '');
                //if($totalDiscount>$tot_amt){ echo $tot_amt.'.00'; }else{echo $totalDiscount;}
            } else {
                echo json_encode($returnArr, true);
                $totalDiscount = number_format($returnBulkDiscount, 2, '.', '');
                //if($totalDiscount>$tot_amt){ echo $tot_amt.'.00'; }else{echo $totalDiscount;}
            }
        } else {
            if (strlen($txtPromoCode) > 0 && $promo_code_records == 0) {
                $discountError = 1;
            }
            $returnArr = array("totalST" => '0.00', "totalET" => '0.00', "totalNormalDiscount" => '0.00', "totalBulkDiscount" => '0.00', "totalReferralDiscount" => '0.00', "purchaseTotal" => '0.00', "discountError" => $discountError);
            echo json_encode($returnArr, true);
        }


//$totalDiscount = 0;
    }

    function isPaidTicketsAvailableForThisEvent($eventid, $Globali) {
        $sql = "select Id from `tickets` where `EventId`='" . $eventid . "' and `Price`>0";
        $data = $Globali->SelectQuery($sql);
        if (count($data) > 0) {
            return true;
        } else {
            return false;
        }
    }

    function getAPIdetails($secretKey, $Globali) {
        $detailsQ = "select `userid`,`public_key`,`source`,`paymentGateway` from api_keys where secret_key='$secretKey'";
        $details = $Globali->selectQuery($detailsQ, MYSQLI_ASSOC);
        return $details;
    }

    function getTicketDetails($eventId, $Globali, $condition = NULL) {
        //`Id`,`Name`,`Description`,`SalesEndOn`,`Price`,`ServiceTax`,`ServiceTaxValue`,`MaxQtyOnSale`,`ticketLevel`,`OrderQtyMax`,`OrderQtyMin`,`donation`

        $SelTickets = "select *,(soldTickets * Price) as soldTicketPrice from (
select  t.`Id`,t.`Name`,t.`Description`,t.`MaxQtyOnSale`,t.`OrderQtyMax`,t.`OrderQtyMin`,
        t.`Price`,
        t.`SalesStartOn`,
        t. `SalesEndOn`,
        t. `ServiceTax`,
		t. `ServiceTaxValue`,
		t. `EntertainmentTax`,
		t. `EntertainmentTaxValue`,
        t. `Status`,
        t. `ticketLevel`,
        t.`donation`,
		t.`DispOrder`,
		t.`dispno`,
		(
              select count(a.id) as cnt FROM Attendees as a
            Inner Join EventSignup as es on a.EventSignupId=es.Id  WHERE   ((((es.paymentgateway='CashonDelivery') or es.paymenttransid!='A1' or
        (es. PaymentModeId=2 and PaymentTransId='A1' and es.echecked='Verified') )
        and es.eChecked NOT IN('Canceled','Refunded'))  or es.fees=0 or es.promotioncode='FreeTicket' ) and es.EventId='" . $Globali->dbconn->real_escape_string($eventId) . "'  and t.id=a.ticketId
) as soldTickets,
        c.code 'currencyCode'
         from tickets t INNER JOIN currencies c ON c.Id=t.currencyId 
		where t.EventId='" . $Globali->dbconn->real_escape_string($eventId) . "' 
		" . $condition . "  
		group by t.`Id` order by t.DispOrder ) as evn";

        return $Globali->SelectQuery($SelTickets);
    }

    function getBookedTicketCount($Globali, $uid = NULL) {
        //global $Globali;
        $user_id = !is_null($uid) ? $uid : $_SESSION['uid'];
        $sql = "select sum(if(e.endDt>=now(),1,0)) as 'current_tickets',sum(if(e.endDt<now(),1,0)) as 'past_tickets' FROM Attendees as a 
            Inner Join EventSignup as es on a.EventSignupId=es.Id
            Inner Join eventsignupticketdetails estd on a.eventsignupid=estd.eventsignupid
            Inner Join tickets t on t.id=estd.ticketid inner join events e on t.EventId=e.Id
            Left Outer Join ChqPmnts ch on es.id=ch.eventsignupid
            
    WHERE  
		es.UserId='" . $user_id . "'
        and ((es.paymentgateway='CashonDelivery') or es.paymenttransid!='A1' or (es. PaymentModeId=2 and PaymentTransId='A1' and es.echecked='Verified')   or es.Fees=0)
        and es.eChecked!='Canceled'
        and es.eChecked!='Refunded'
		group by  es.Id order by es.Id desc";
        return $Globali->SelectQuery($sql);
    }

    function getEventsCount($Globali, $uid = NULL) {
        $user_id = !is_null($uid) ? $uid : $_SESSION['uid'];
        $sql = "select sum(count) as 'count',IFNULL(sum(current_events),0) as 'current_events',IFNULL(sum(past_events),0) as 'past_events' from ((select count(e.id) as count,sum(if(e.enddatetime >= now(),1,0)) as 'current_events',sum(if(e.enddatetime < now(),1,0)) as 'past_events' 
    from event as e 
     where e.deleted=0 and e.url!='' and  e.ownerid = '" . $user_id . "')
    
     union
    
     (select count(e1.id) as count,sum(if(e1.enddatetime >= now(),1,0)) as 'current_events',sum(if(e1.enddatetime < now(),1,0)) as 'past_events' from collaborator ecc,event as e1 where ecc.userid='" . $user_id . "' and e1.id=ecc.eventid  ) ) as a";

        /* $sql="select count(e.Id) as count,sum(if(e.endDt >= now(),1,0)) as 'current_events' 
          from events as e
          Left join event_collaborators as ec on e.userid=ec.userid and e.id=ec.eventid and ec.status=1
          where e.URL!='' and e.userid = '".$user_id."' or e.id in (select ecc.eventid from event_collaborators ecc where e.id=ecc.eventid)"; */

        return $Globali->SelectQuery($sql);
    }

    function isPromoter($Globali, $uid = NULL) {
        $user_id = !is_null($uid) ? $uid : $_SESSION['uid'];
        $isPromoter = "SELECT p.Id from promoters p INNER JOIN user u ON u.Id=p.uid WHERE u.id='" . $user_id . "'";
        return $Globali->SelectQuery($isPromoter);
    }

    function getRedirectionUrl($Globali, $uid = NULL) {
        $userEvents = $this->getEventsCount($Globali, $uid);
        $redirectUrl = "dashboard-account";
        if ($userEvents[0]['current_events'] > 0) {
            $redirectUrl = "dashboard-current";
        } else if ($userEvents[0]['past_events'] > 0) {
            $redirectUrl = "dashboard-pastevents";
        } else if (count($this->isPromoter($Globali, $uid)) > 0) {
            $redirectUrl = "promoter-view";
        } else {
            $ticketsCount = $this->getBookedTicketCount($Globali, $uid);
            if ($ticketsCount[0]['current_tickets'] > 0) {
                $redirectUrl = "dashboard-tickets";
            } else if ($ticketsCount[0]['past_tickets'] > 0) {
                $redirectUrl = "dashboard-archivetickets";
            }
        }
        //echo $redirectUrl;
        return $redirectUrl;
    }

    //To insert the country
    public function insert_country($country_string, $Globali, $page = NULL) {
        $country_list_query = "SELECT Id FROM Countries WHERE   
                    Country like '" . $Globali->dbconn->real_escape_string($country_string) . "'";
        $country_list = $Globali->SelectQuery($country_list_query, MYSQLI_ASSOC);
        $country_id = 0;
        //country name found in our db
        if (count($country_list) > 0) {
            $country_id = $country_list[0]['Id'];
        } else {    // if(strcmp($page, 'addevent')!=0)
            $status = 0;
            $countries_query = "INSERT INTO Countries(Country,status) VALUES (?,?)";
            $countries_stmt = $Globali->dbconn->prepare($countries_query);
            $countries_stmt->bind_param("si", $country_string, $status);
            $countries_stmt->execute();
            $country_id = $countries_stmt->insert_id;
            $countries_stmt->close();
        }
        return $country_id;
    }

    //To insert the country
    public function insert_state($state_string, $country_id, $Globali, $page = NULL) {
        $state_list_query = "SELECT Id  FROM States WHERE    
                    State like '" . $Globali->dbconn->real_escape_string($state_string) . "' and CountryId='" . $country_id . "'";
        $state_list = $Globali->SelectQuery($state_list_query, MYSQLI_ASSOC);
        $state_id = 0;
        //country name found in our db
        if (count($state_list) > 0) {
            $state_id = $state_list[0]['Id'];
        } else {    //if(strcmp($page, 'addevent')!=0)
            $zoneid = 0;
            $status = 0;
            $state_query = "INSERT INTO States(State, ZoneId, CountryId,status) VALUES (?,?,?,?)";
            $state_stmt = $Globali->dbconn->prepare($state_query);
            $state_stmt->bind_param("siii", $state_string, $zoneid, $country_id, $status);
            $state_stmt->execute();
            $state_id = $state_stmt->insert_id;
            $state_stmt->close();
        }
        return $state_id;
    }

    //To insert the city
    public function insert_city($city_string, $state_id, $Globali, $page = NULL) {
        $city_list_query = "SELECT Id  FROM Cities WHERE    
                    City like '" . $Globali->dbconn->real_escape_string($city_string) . "' and StateId='" . $Globali->dbconn->real_escape_string($state_id) . "'";
        $city_list = $Globali->SelectQuery($city_list_query, MYSQLI_ASSOC);
        $city_id = 0;
        //country name found in our db
        if (count($city_list) > 0) {
            $city_id = $city_list[0]['Id'];
        } else {    // if(strcmp($page,'addevent')!=0)
            $status = 0;
            $city_query = "INSERT INTO Cities(City, StateId, status) VALUES (?,?,?)";
            $city_stmt = $Globali->dbconn->prepare($city_query);
            $city_stmt->bind_param("sii", $city_string, $state_id, $status);
            $city_stmt->execute();
            $city_id = $city_stmt->insert_id;
            $city_stmt->close();
        }
        return $city_id;
    }

    //To insert the city
    public function insert_location($location_string, $city, $state, $Globali) {

        $location_list_query = "SELECT Id  FROM Location WHERE    
                    Loc like '" . $Globali->dbconn->real_escape_string($location_string) . "' and StateId='" . $state . "' and CityId='" . $city . "'";
        $location_list = $Globali->SelectQuery($location_list_query, MYSQLI_ASSOC);
        //country name found in our db
        if (count($location_list) > 0) {
            $location_id = $location_list[0]['Id'];
        } else {

            $status = 0;
            $location_query = "INSERT INTO Location(Loc, StateId, CityId,status) VALUES (?,?,?,?)";
            $location_stmt = $Globali->dbconn->prepare($location_query);

            $location_stmt->bind_param("siii", $location_string, $state, $city, $status);
            $location_stmt->execute();
            $location_id = $location_stmt->insert_id;
            $location_stmt->close();
        }
        return $location_id;
    }

    //To insert the designation list
    public function insert_designation($designation_string, $Globali) {
        $designation_list_query = "SELECT Id  FROM Designations WHERE    
                    Designation like '" . $Globali->dbconn->real_escape_string($designation_string) . "'";
        $designation_list = $Globali->SelectQuery($designation_list_query, MYSQLI_ASSOC);
        //country name found in our db
        if (count($designation_list) > 0) {
            $designation_id = $designation_list[0]['Id'];
        } else {
            $status = 0;
            $designation_query = "INSERT INTO Designations(Designation,status) VALUES (?,?)";
            $designation_stmt = $Globali->dbconn->prepare($designation_query);
            $designation_stmt->bind_param("si", $designation_string, $status);
            $designation_stmt->execute();
            $designation_id = $designation_stmt->insert_id;
            $designation_stmt->close();
        }
        return $designation_id;
    }

    //Gets the user ip address
    public function get_ip_address() {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']) {
            $clientIpAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $clientIpAddress = $_SERVER['REMOTE_ADDR'];
        }
        return $clientIpAddress;
    }

    /*
     * Function to get the Ticket details for the event from `API`
     */

    function getApiTicketDetails($eventId, $Globali, $condition = NULL) {

        $SelTickets = "SELECT  t.`Id`,t.`Name`,t.`MaxQtyOnSale`,t.`Price`,c.code 'currencyCode',
                            COUNT(IF(res.ticket_checked='Verified',res.Id, NULL)) 'soldTickets',
                            COUNT(IF(res.ticket_checked='Canceled',res.Id, NULL)) 'canceledTickets',
                            COUNT(IF(res.ticket_checked='Verified',res.Id, NULL))*t.Price 'soldTicketPrice'
                        FROM tickets t 
                        
                        INNER JOIN currencies c ON c.Id = t.currencyId
                        
                        LEFT OUTER JOIN (
                            SELECT a.id AS Id, a.ticketId AS ticketId, es.eChecked AS ticket_checked 
                            FROM Attendees AS a
                            INNER JOIN EventSignup AS es ON a.EventSignupId = es.Id 
                            WHERE   
                            (
                                (
                                    (
                                        (es.paymentgateway='CashonDelivery') 
                                        OR es.paymenttransid != 'A1' 
                                        OR (es. PaymentModeId = 2 AND PaymentTransId = 'A1' AND es.echecked = 'Verified') 
                                    )
                                    AND 
                                        es.eChecked NOT IN('Refunded')
                                )  
                                OR 
                                    es.fees = 0 
                                OR 
                                    es.promotioncode = 'FreeTicket' 
                            ) 
                            AND es.EventId = '" . $Globali->dbconn->real_escape_string($eventId) . "' 
                        ) AS res ON t.id = res.ticketId 

                        WHERE t.EventId = " . $Globali->dbconn->real_escape_string($eventId) . $condition . " 
                        GROUP BY t.`Id` 
                        ORDER BY t.DispOrder";
        //echo $SelTickets;exit;
        return $Globali->SelectQuery($SelTickets);
    }

    /* function to check given ticket is valid for the given event id or not */

    function isValidTicketForEvent($TicketId, $EventId, $Globali) {
        $uid = $_SESSION['uid'];

        if ($uid == 2) {
            return true;
        } //if superadmin, ignoring
        else {
            $sql = "select t.Id from tickets t
			Inner join events e on t.eventid=e.id
			Inner join user u on e.userid=u.id
			where t.Id='" . $Globali->dbconn->real_escape_string($TicketId) . "' 
			and e.id='" . $Globali->dbconn->real_escape_string($EventId) . "'
			and u.id='" . $Globali->dbconn->real_escape_string($uid) . "'";
            $data = $Globali->SelectQuery($sql);
            if (count($data) == 0) {
                return false;
            } else {
                return true;
            }
        }
    }

    function getCollaboratorEventsByModule($Globali, $uid, $eventid = NULL) {
        $eventCond = "1";
        if (!is_null($eventid)) {
            $eventCond = 'e.Id=' . $eventid;
        }
        $query = "SELECT ec.eventid,cm.module_name FROM `event_collaborators` ec INNER JOIN events e ON e.Id=ec.eventid and " . $eventCond . " and ec.userid='" . $uid . "' INNER JOIN collaborator_modules cm ON ec.Id=cm.collaborator_id WHERE ec.status=1";
        $res = $Globali->SelectQuery($query, MYSQLI_ASSOC);
        return $res;
    }

    function isCollaboratorForEveModule($Globali, $eventid, $uid, $module_name) {
        $isCollaborator = array();
        $collaboratorRes = $this->getCollaboratorEventsByModule($Globali, $uid, $eventid);
        if (count($collaboratorRes) > 0) {
            $collaborateEventsModule = array();
            foreach ($collaboratorRes as $k => $value) {
                $collaborateEventsModule[$value['eventid']][$k] = ($value['module_name']);
            }
            foreach ($module_name as $k => $value) {
                $isCollaborator[$value] = TRUE;
                if (!in_array($value, $collaborateEventsModule[$eventid])) {
                    $isCollaborator[$value] = false;
                }
            }
        }
        //print_r($isCollaborator);exit;
        return $isCollaborator;
    }

    function isValidUser($Globali, $eventid, $uid) {
        $isCollaborator = array('ManageEvent' => TRUE, 'Promote' => TRUE, 'Reports' => TRUE, 'iscollaborator' => FALSE);
        $module_name = array('ManageEvent', 'Promote', 'Reports');
        $isAdmin = $this->isAdmin();
        if (!$isAdmin) {
            $isOrganizer = $this->isOrganizerForEvent($eventid, $Globali);
            if (!$isOrganizer) {
                $isCollaborator = $this->isCollaboratorForEveModule($Globali, $eventid, $uid, $module_name);
                if ($isCollaborator['ManageEvent']) {
                    $isCollaborator['Promote'] = true;
                    $isCollaborator['Reports'] = true;
                    $isCollaborator['iscollaborator'] = true;
                }
            }
        }
        return $isCollaborator;
    }

    function getMetatags($city, $categoryname) {
        $cityname = $categoryname = $cityStr = $categoryStr = $cityDescStr = "";
        $data = array();
        if (isset($city)) {
            $cityname = $city;
            $cityStr = ' | ' . ucfirst($cityname);
            $cityDescStr = ' in ' . ucfirst($cityname);
        }

        if (isset($_GET['categoryname'])) {
            $categoryname = $_GET['categoryname'];
            $categoryStr = " " . $categoryname;
        }

        $pageTitle = 'Buy' . ucfirst($categoryStr) . ' Event Tickets Online' . $cityStr . ' | Meraevents.com';
        $metaDescription = 'Book event ticket for current and upcoming' . ucfirst($categoryStr) . ' events' . $cityDescStr . ' only at meraevents.com';
        switch (strtolower($categoryname)) {
            case 'professional':
                $metaKeywords = 'corporate events' . $cityDescStr . ', professional events' . $cityDescStr . ', professional events tickets' . $cityDescStr . ', professional events tickets online' . $cityDescStr . ' ';
                break;
            case 'entertainment':
                $metaKeywords = 'current events entertainment ' . ucfirst($cityname) . ', free music events' . $cityDescStr . ', upcoming entertainment events ' . ucfirst($cityname) . ', entertainment events  ' . ucfirst($cityname) . ', entertainment tickets  ' . ucfirst($city) . ', live music shows' . $cityDescStr;
                break;
            case 'sports':
                $metaKeywords = 'Sport Tickets ' . ucfirst($cityname) . ', latest sports events ' . ucfirst($cityname) . ', discount sporting event ticket ' . ucfirst($cityname) . ', sporting event tickets for sale ' . ucfirst($cityname) . ', recent sport events ' . ucfirst($cityname) . ', Hockey, Football, Formula 1, Tennis, Badminton, Adventure Sports, Cycling, Boxing, Cricket, T20, ODI, Test Matches' . $cityDescStr . ' Tickets Online';
                break;
            case 'campus':
                $metaKeywords = 'campus events' . $cityDescStr . ',educational events' . $cityDescStr . ', college events' . $cityDescStr . ', campus events tickets' . $cityDescStr . ', campus events tickets online' . $cityDescStr;
                break;
            case 'trade shows':
                $metaKeywords = 'trade shows ' . ucfirst($cityname) . ' , trade fairs' . $cityDescStr . ' , exhibitions' . $cityDescStr . ' , international trade shows' . $cityDescStr . ' , business trade shows ' . ucfirst($cityname) . ',  trade fairs and exhibitions' . $cityDescStr;
                break;
            case 'training':
                $metaKeywords = 'online training courses ' . ucfirst($cityname) . ', leadership training ' . ucfirst($cityname) . ', leadership training program ' . ucfirst($cityname) . ', online certification courses' . $cityDescStr . ', leadership skills training ' . ucfirst($cityname) . ', online IT training ' . ucfirst($cityname) . ', sales management training ' . ucfirst($cityname) . ', training for managers ' . ucfirst($cityname) . ', leadership courses ' . ucfirst($cityname) . ', professional training ' . ucfirst($cityname) . ', project management certification ' . ucfirst($cityname) . ', leadership management training ' . ucfirst($cityname);
                break;
            case 'spiritual':
                $metaKeywords = 'spiritual healing courses ' . ucfirst($cityname) . ', spiritual retreats' . $cityDescStr . ', meditation retreats' . $cityDescStr . ', spiritual courses' . $cityDescStr;
                break;
            default :
                $pageTitle = 'Online Event promotion' . $cityStr . ' | Upcoming Events | Professional Conferences | Professional Events ';
                $metaKeywords = 'Current Events' . $cityDescStr . ', Corporate Events Online Portals, Event Solutions, Event Management' . $cityDescStr . ', Cultural' . $cityDescStr . ', Event Management in Companies, Events' . $cityDescStr . ', Meeting and Conferences' . $cityDescStr . ', Special Event ticket booking, seminars, conferences, concert, upcoming events , today, weekend';
                $metaDescription = "MeraEvents.com is India's largest portal solely dedicated to Online Event promotions Upcoming Events Professional conferences Professional Events It offers many unique features.post your event and brand in front of a highly targeted audience with massive influence.";
                break;
        }
        $data['pageTitle'] = $pageTitle;
        $data['metaDescription'] = $metaDescription;
        $data['metaKeywords'] = $metaKeywords;
        return $data;
    }

    function getEBSpaymentStatusByESID($esid) {
        //$url="https://testing.secure.ebs.in/api/1_0"; //TEST
        $url = "https://secure.ebs.in/api/1_0"; //PROD
        $ch = curl_init();
        $data = array('Action' => 'statusByRef', 'AccountID' => '7300', 'SecretKey' => '67624ee7bb021090f9c0ef1bb3dcd53f', 'RefNo' => $esid);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        $resObj = simplexml_load_string($res);
        return $resObj;
    }

    //function to call TrueSemantic API
    function sendDataToTrueSemantic($esid, $Globali) {
        $sql = "select es.SignupDt,es.eventid, es.fees,es.qty, es.PromotionCode,es.PaymentModeId,es.PaymentTransId,es.PaymentGateway,es.ucode, u.firstname, u.lastname, 
		u.email,u.mobile,c.City ,s.State,cat.CatName,sc.SubCatName, CONCAT(u2.firstname,' ',u2.lastname) 'orgname',e.title,c2.City 'eventcity', e.startdt,e.venue,e.free,e.iswebinar,ct.Country
		from EventSignup es 
		  Inner Join events e on es.eventid=e.id
		  Inner join user u on es.userid=u.id
		  Inner Join user u2 on e.userid=u2.id
		  Inner Join States as s on u.StateId=s.Id
		  Inner Join Cities as c on u.CityId=c.Id
		  Left Join Cities as c2 on e.CityId=c2.Id
		  Left Join Countries as ct on u.CountryId=ct.Id
		  Left Join category as cat on e.categoryid=cat.Id
		  Inner join subcategory as sc on e.subcategoryid=sc.Id
		where es.id='" . $esid . "'";

        $data = $Globali->SelectQuery($sql);

        $amount = $data[0]['fees'] * $data[0]['qty'];

        if ($data[0]['fees'] != 0) {
            if ($data[0]['PaymentModeId'] == 1) {
                if ($data[0]['PaymentTransId'] == "PayPalPayment") {
                    $paymentmode = "PayPal Transaction";
                } else {
                    $paymentmode = "Card Transaction";
                }
            } else {
                $paymentmode = "Cheque Transaction";
            }
            if ($data[0]['PromotionCode'] == "Y") {
                $paymentmode = "Pay At Counter";
            } elseif ($data[0]['PaymentGateway'] == "CashonDelivery") {
                $paymentmode = "Cash On Delivery";
            }
        } else {
            $paymentmode = "Free Registration";
        }

        if ($data[0]['iswebinar'] == 1) {
            $TSEventType = "Webinar";
        }
        if ($data[0]['free'] == 1) {
            $TSEventType = "Free";
        } else {
            $TSEventType = "Paid";
        }


        $url = "http://www.truesemantic.com/ts-api";


        $data = array('ts_api_key' => '3e93579f21979ec265ad63371b048c12',
            'ts_survey_id' => 'na8',
            'ts_survey_source' => '67624ee7bb021090f9c0ef1bb3dcd53f',
            'ts_invite_key' => $esid,
            'fcategory' => "Booking Success",
            'trxid' => $esid,
            'bdate' => date("d M Y, h:i A", strtotime($data[0]['SignupDt'])),
            'revenue' => $amount,
            'pmethod' => $paymentmode,
            'pgateway' => $data[0]['PaymentGateway'],
            'qty' => $data[0]['qty'],
            'ccode' => $data[0]['PromotionCode'],
            'email' => $data[0]['email'],
            'mobile' => $data[0]['mobile'],
            'fname' => $data[0]['firstname'],
            'lname' => $data[0]['lastname'],
            'city' => $data[0]['City'],
            'state' => $data[0]['State'],
            'country' => $data[0]['Country'],
            'eveid' => $data[0]['eventid'],
            'evecategory' => $data[0]['CatName'],
            'evescategory' => $data[0]['SubCatName'],
            'evename' => $data[0]['title'],
            'eveorganizer' => $data[0]['orgname'],
            'evecity' => $data[0]['eventcity'],
            'evedate' => date("d M Y, h:i A", strtotime($data[0]['startdt'])),
            'evevenue' => str_replace("\r\n", ",", $data[0]['venue']),
            'evetype' => $TSEventType,
            'ucode' => $data[0]['ucode'],
        );

        //print_r($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        //var_dump($res);
        //$resObj=simplexml_load_string($res);
        //return $resObj;
        curl_close($ch);
    }

    function TrueSemanticRatingHtml($esid) {
        $content = '<div style="margin:0 auto;">
    <div style="font-family: Helvetica, sans-serif; font-size:13px; color:#333; line-height:22px">
        <div style="margin: 0px auto;">
            <form action="http://www.truesemantic.com/s/na@moJSgqMvKkac?ts_invite_key=' . $esid . '&fcategory=booking success" method="POST" target="_blank">
                <table width="100%" cellpadding="0" cellspacing="0" style="border: none;">
                    <tbody><input type="hidden" name="sdtype" id="sdtype" value="1"><input type="hidden" name="isq" id="isq" value="1"><input type="hidden" name="rspid" id="rspid" value="0"><input type="hidden" name="cpid" id="cpid" value="49"><tr>
                        <td>
                            <div>
                                <table width="100%">
                                    <tbody><tr>
                                        <td>
                                            <div style="margin-bottom:1em;">
                                                <div style="font-size:14px;margin-bottom:.5em;">
												Help us improve <br>
                                                    Based on your recent interaction, How likely is it that you would recommend MeraEvents to a friend or colleague?           </div>
                                                <div>
                                                    <table width="100%" style="border-collapse:collapse;">
                                                        <tbody><tr style="border-bottom:1px solid #eee;height:40px;"><td align="center" style="width:9%;">0</td>                                <td align="center" style="width:9%;">1</td>                                <td align="center" style="width:9%;">2</td>                                <td align="center" style="width:9%;">3</td>                                <td align="center" style="width:9%;">4</td>                                <td align="center" style="width:9%;">5</td>                                <td align="center" style="width:9%;">6</td>                                <td align="center" style="width:9%;">7</td>                                <td align="center" style="width:9%;">8</td>                                <td align="center" style="width:9%;">9</td>                                <td align="center" style="width:9%;">10</td>                        </tr>
                                                        <tr style="height:30px;">
                                                            <td align="center">
                                                                <input name="315_27" type="radio" value="0" style="vertical-align:middle;background-color:#f9f9f9;min-height:17px;width:17px;margin:0;padding:0">
                                                            </td>
                                                            <td align="center">
                                                                <input name="315_27" type="radio" value="1" style="vertical-align:middle;background-color:#f9f9f9;min-height:17px;width:17px;margin:0;padding:0">
                                                            </td>
                                                            <td align="center">
                                                                <input name="315_27" type="radio" value="2" style="vertical-align:middle;background-color:#f9f9f9;min-height:17px;width:17px;margin:0;padding:0">
                                                            </td>
                                                            <td align="center">
                                                                <input name="315_27" type="radio" value="3" style="vertical-align:middle;background-color:#f9f9f9;min-height:17px;width:17px;margin:0;padding:0">
                                                            </td>
                                                            <td align="center">
                                                                <input name="315_27" type="radio" value="4" style="vertical-align:middle;background-color:#f9f9f9;min-height:17px;width:17px;margin:0;padding:0">
                                                            </td>
                                                            <td align="center">
                                                                <input name="315_27" type="radio" value="5" style="vertical-align:middle;background-color:#f9f9f9;min-height:17px;width:17px;margin:0;padding:0">
                                                            </td>
                                                            <td align="center">
                                                                <input name="315_27" type="radio" value="6" style="vertical-align:middle;background-color:#f9f9f9;min-height:17px;width:17px;margin:0;padding:0">
                                                            </td>
                                                            <td align="center">
                                                                <input name="315_27" type="radio" value="7" style="vertical-align:middle;background-color:#f9f9f9;min-height:17px;width:17px;margin:0;padding:0">
                                                            </td>
                                                            <td align="center">
                                                                <input name="315_27" type="radio" value="8" style="vertical-align:middle;background-color:#f9f9f9;min-height:17px;width:17px;margin:0;padding:0">
                                                            </td>
                                                            <td align="center">
                                                                <input name="315_27" type="radio" value="9" style="vertical-align:middle;background-color:#f9f9f9;min-height:17px;width:17px;margin:0;padding:0">
                                                            </td>
                                                            <td align="center">
                                                                <input name="315_27" type="radio" value="10" style="vertical-align:middle;background-color:#f9f9f9;min-height:17px;width:17px;margin:0;padding:0">
                                                            </td>
                                                        </tr>
                                                        <tr style="border-bottom:1px solid #eee;">
                                                            <td colspan="5" align="left">0 - Not at all likely</td>
                                                            <td colspan="6" align="right">10 - Extremely likely</td>
                                                        </tr>
                                                        </tbody></table>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody></table>
                            </div>
                        </td>
                    </tr>
                    <input type="hidden" name="cqid" id="cqid" value="319">
                    <tr>
                        <td>
                            <div>
                                <table width="100%">
                                    <tbody><tr>
                                        <td align="center">
                                            <div>
                                                <button type="submit" style="padding: 0.8em 1.5em; display:inline-block; vertical-align:middle;outline:none;border:none;text-transform:uppercase;font-weight:bold;text-align:center;font-size:1rem;line-height:1;background:#df3c19;color:#fff;border-radius: 0.25em; cursor:pointer;">Submit</button>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody></table>
                            </div>
                        </td>
                    </tr>
                    </tbody></table>
            </form>
        </div>
    </div>
</div>';

        return $content;
    }

    function TrueSemanticFeedbackHtml() {
        $content = '<div style="margin:0 auto;">
    <div style="font-family: Helvetica, sans-serif; font-size:13px; color:#333; line-height:22px">
        <div style="margin: 0px auto;">
            <form action="TsActionUrl" method="POST" target="_blank"><input type="hidden" name="sdtype" id="sdtype" value="1"><input type="hidden" name="isq" id="isq" value="1"><input type="hidden" name="rspid" id="rspid" value="0"><input type="hidden" name="cpid" id="cpid" value="50">  
            <input type="hidden" name="cqid" id="cqid" value="320">              
            <table width="100%" cellpadding="0" cellspacing="0" style="border: none;"><tbody><tr><td><div>
                                <table width="100%">
                                    <tbody><tr>
                                        <td>
                                            <div style="margin-bottom:1em;">
                                                <div style="font-size:14px;margin-bottom:.5em;">
													Based on your experience at <b>EVENT_NAME</b>, How likely is it that you would recommend the event to a friend or colleague?           </div>
                                                <div>
                                                    <table width="100%" style="border-collapse:collapse;">
                                                        <tbody><tr style="border-bottom:1px solid #eee;height:40px;">
                                                            <td align="center" style="width:9%;">0</td>                                <td align="center" style="width:9%;">1</td>                                <td align="center" style="width:9%;">2</td>                                <td align="center" style="width:9%;">3</td>                                <td align="center" style="width:9%;">4</td>                                <td align="center" style="width:9%;">5</td>                                <td align="center" style="width:9%;">6</td>                                <td align="center" style="width:9%;">7</td>                                <td align="center" style="width:9%;">8</td>                                <td align="center" style="width:9%;">9</td>                                <td align="center" style="width:9%;">10</td>                        </tr>
                                                        <tr style="height:30px;">
                                                            <td align="center">
                                                                <input name="320_27" type="radio" value="0" style="vertical-align:middle;background-color:#f9f9f9;min-height:17px;width:17px;margin:0;padding:0">
                                                            </td>
                                                            <td align="center">
                                                                <input name="320_27" type="radio" value="1" style="vertical-align:middle;background-color:#f9f9f9;min-height:17px;width:17px;margin:0;padding:0">
                                                            </td>
                                                            <td align="center">
                                                                <input name="320_27" type="radio" value="2" style="vertical-align:middle;background-color:#f9f9f9;min-height:17px;width:17px;margin:0;padding:0">
                                                            </td>
                                                            <td align="center">
                                                                <input name="320_27" type="radio" value="3" style="vertical-align:middle;background-color:#f9f9f9;min-height:17px;width:17px;margin:0;padding:0">
                                                            </td>
                                                            <td align="center">
                                                                <input name="320_27" type="radio" value="4" style="vertical-align:middle;background-color:#f9f9f9;min-height:17px;width:17px;margin:0;padding:0">
                                                            </td>
                                                            <td align="center">
                                                                <input name="320_27" type="radio" value="5" style="vertical-align:middle;background-color:#f9f9f9;min-height:17px;width:17px;margin:0;padding:0">
                                                            </td>
                                                            <td align="center">
                                                                <input name="320_27" type="radio" value="6" style="vertical-align:middle;background-color:#f9f9f9;min-height:17px;width:17px;margin:0;padding:0">
                                                            </td>
                                                            <td align="center">
                                                                <input name="320_27" type="radio" value="7" style="vertical-align:middle;background-color:#f9f9f9;min-height:17px;width:17px;margin:0;padding:0">
                                                            </td>
                                                            <td align="center">
                                                                <input name="320_27" type="radio" value="8" style="vertical-align:middle;background-color:#f9f9f9;min-height:17px;width:17px;margin:0;padding:0">
                                                            </td>
                                                            <td align="center">
                                                                <input name="320_27" type="radio" value="9" style="vertical-align:middle;background-color:#f9f9f9;min-height:17px;width:17px;margin:0;padding:0">
                                                            </td>
                                                            <td align="center">
                                                                <input name="320_27" type="radio" value="10" style="vertical-align:middle;background-color:#f9f9f9;min-height:17px;width:17px;margin:0;padding:0">
                                                            </td>
                                                        </tr>
                                                        <tr style="border-bottom:1px solid #eee;">
                                                            <td colspan="5" align="left">0 - Not at all likely</td>
                                                            <td colspan="6" align="right">10 - Extremely likely</td>
                                                        </tr>
                                                        </tbody></table>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody></table>
                            </div>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            <div>
                                <table width="100%">
                                    <tbody><tr>
                                        <td align="center">
                                            <div>
                                                <button type="submit" style="padding: 0.8em 1.5em; display:inline-block; vertical-align:middle;outline:none;border:none;text-transform:uppercase;font-weight:bold;text-align:center;font-size:1rem;line-height:1;background:#df3c19;color:#fff;border-radius: 0.25em; cursor:pointer;">Submit</button>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody></table>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                    </table>

            </form>
        </div>

    </div>
</div>';
        return $content;
    }

    function recommendationsUserIdWithSalt($user_id) {
        $salt = 'me';

        $userslat = md5($user_id . $salt);
        //setcookie("user",$userslat , (2592000 + time()));
        return $userslat;
    }
    
    public function changeEventTicketSoldStatus($data) {
        $url = _HTTP_SITE_ROOT . "/api/event/eventTicketSoldout"; //api call to change the event soldout status
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                eventId => $data['eventId'],
                ticketSoldout => $data['ticketSoldout']
            )
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);
        return $resp;
    }
    
    /**
     * 
     * @param type $dateTime
     * @param type $timeZoneName
     * @param type $utc default false convert the passed  datetime to utc time
     * $utc passed as true convert the utc datetime to passed timezone format
     * @return type
     */
    function convertTime($dateTime, $timeZoneName, $utc = FALSE) {
        if (!$utc) {
            $sourceTimeZone = $timeZoneName;
            $destinationTimeZone = "UTC";
        } else {
            $sourceTimeZone = "UTC";
            $destinationTimeZone = $timeZoneName;
        }
        $date = new DateTime($dateTime, new DateTimeZone($sourceTimeZone));
        $date->setTimezone(new DateTimeZone($destinationTimeZone));
        return $date->format('Y-m-d H:i:s');
    }
    /**
     * 
     * @param type $data
     * @return type
     */
    public function makeSolrCall($data,$url) {
            
    $url = _HTTP_SITE_ROOT . $url; 
       
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS =>  $data
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
		//var_dump($resp);
        // Close request to clear up some resources
        curl_close($curl);
        return $resp;
    }
    
    public function getCronUserDetails(){
        $this->cGlobali= new cGlobali();
        $selectQuery = "select id from user as u where u.usertype='cron'";
        $selectQueryRe = $this->cGlobali->SelectQuery($selectQuery, MYSQLI_ASSOC);
        
        if(count($selectQueryRe) > 0 ){
            return $selectQueryRe[0]['id'];
        }
        return 1;
   }
   
     public function changeEventStatus($data) {
        $url = _HTTP_SITE_ROOT . "/api/event/changeStatus"; //api call to change the event soldout status
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                eventId => $data['eventId']
            )
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);
        return $resp;
    }
	
	
	/*function to delete a directory and its files/folders */
	public function deleteDir($dirPath) {
		if (! is_dir($dirPath)) {
			throw new InvalidArgumentException("$dirPath must be a directory");
		}
		if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
			$dirPath .= '/';
		}
		$files = glob($dirPath . '*', GLOB_MARK);
		foreach ($files as $file) {
			if (is_dir($file)) {
				$this->deleteDir($file);
			} else {
				unlink($file);
			}
		}
		rmdir($dirPath);
	}
	
	/*function to read/scan a directory and its files/folders */	
	public function dir_scan($path,&$files = array())
	{
		$dir = opendir($path."/.");
		while($item = readdir($dir))
			if(is_file($sub = $path."/".$item))
				$files[] = $sub;
				elseif($item != "." and $item != "..")
					$this->dir_scan($sub,$files); 
		return($files);
	}
        public function isDeveloper() 
        {
            $devArray = array('developer@meraevents.com');
            if (in_array($_SESSION['useremail'], $devArray)) {
                return true;
            } else {
                return false;
            }
        }

}
    
?>