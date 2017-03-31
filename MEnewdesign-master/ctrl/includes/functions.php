<?php
//require_once('email_message.php');

////
// This funstion validates a plain text password with an
// encrpyted password
  function validate_password($plain, $encrypted) {
    if (check_not_null($plain) && check_not_null($encrypted)) {
// split apart the hash / salt
      $stack = explode(':', $encrypted);

      if (sizeof($stack) != 2) return false;

      if (md5($stack[1] . $plain) == $stack[0]) {
        return true;
      }
    }

    return false;
  }

////
// This function makes a new password from a plaintext password.
  function encrypt_password($plain) {
    $password = '';

    for ($i=0; $i<10; $i++) {
      $password .= get_rand();
    }

    $salt = substr(md5($password), 0, 2);

    $password = md5($salt . $plain) . ':' . $salt;

    return $password;
  }

  
////
// Return a random value
  function get_rand($min = null, $max = null) {
    static $seeded;

    if (!isset($seeded)) {
      mt_srand((double)microtime()*1000000);
      $seeded = true;
    }

    if (isset($min) && isset($max)) {
      if ($min >= $max) {
        return $min;
      } else {
        return mt_rand($min, $max);
      }
    } else {
      return mt_rand();
    }
  }
  
  
  // send email  fucntion by MIME
  function sendMail($from, $to, $subject, $message, $htmlformat=true, $charset='iso-8859-1') {
  
  	$header="From:" . $from . "\n" ."Reply-To:". $from;
	 	if($htmlformat)
		{
			$header .= "MIME-Version: 1.0\n";
			$header .= "Content-type: text/html; charset=$charset\n";
		}

	  
	  return mail($to,$subject,$message,$header);

		  
  }
  

// display datetime that match to  SERVICE_DATE_FORMAT 
function showDateTime($unx_stamp, $date_format='',$time_format='')
{
	$date_format	=	($date_format=='') ? SERVICE_DATE_FORMAT : $date_format;	
	$time_format	=	($time_format=='') ? SERVICE_TIME_FORMAT : $time_format;		
	
	//$date_str=(date($date_format,$unx_stamp)); 
	//$time_str=(date($time_format,$unx_stamp));
  // fix - for timestamps with datetime format (on new mysql)
	$date_str=(date($date_format,strtotime($unx_stamp))); 
	$time_str=(date($time_format,strtotime($unx_stamp)));
	
	return($date_str." ".$time_str);
}//end date function


// display date that match to  SERVICE_DATE_FORMAT 
function showDateOnly($unx_stamp, $date_format='')
{
	$date_format	=	($date_format=='') ? SERVICE_DATE_FORMAT : $date_format;	

	//$date_str=(date($date_format,$unx_stamp)); 
	$date_str=(date($date_format,strtotime($unx_stamp))); // fix - for timestamps with datetime format (on new mysql)
	
	return($date_str);
}//end date function


// display date that match to  SERVICE_DATE_FORMAT 
function showTimeOnly($unx_stamp, $time_format='')
{

	$time_format	=	($time_format=='') ? SERVICE_TIME_FORMAT : $time_format;			
	
	$time_str=(date($time_format,$unx_stamp));
	
	return($time_str);
}//end date function


// get skill rating of provider profile
//	return : ARRAY['skills_count','skill_rating','skill_rating_score']

function getProviderSkillRating($skills, $skill_levels) {

	$skillname_array		=	explode(SKILLS_SAPERATE_STRING,$skills);
	$skilllevel_array	=	explode(',',$skill_levels);	
	// get skills infor
	$skills_count	=	0;
	$skill_rating_score	=	0;
	for ($i=0; $i < count($skillname_array); $i++ ) {
		if (trim($skillname_array[$i]) !='' ) {	
			$skills_count++;
			$skill_rating_score	+=	$skilllevel_array[$i];
		}
	}	
	
	$skill_rating	=	($skills_count>0) ? round($skill_rating_score/$skills_count,2) : 0;
	
	return array('skill_count'	=> $skills_count , 'skill_rating_score'	=>	$skill_rating_score,	'skill_rating'	=>$skill_rating);
}

// get address infor base on _ADDRESS_FORMAT 
function getAddress($state, $country) {
	return $state.', '.$country;
}


function check_not_null($value) {
    if (is_array($value)) {
      if (sizeof($value) > 0) {
        return true;
      } else {
        return false;
      }
    } else {
      if (($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0)) {
        return true;
      } else {
        return false;
      }
    }
  }

//	require_once('email_message.php');

// Send HTML-encoded emails with optional attachment
function send_mail($to_address, $to_name, $from_address='info@chiapasip.com', $from_name='ChiapasIP',
                   $subject='ChiapasIP Contact Form', $messageHTML='', $messageTXT='' , $file='', $att_name='',
                   $mime='application/pdf', $smtp_params=array())
{
	$reply_name=$from_name;
	$reply_address=$from_address;
	$reply_address=$from_address;
	$error_delivery_name=$from_name;
	$error_delivery_address=$from_address;

	$email_message=new email_message_class;
	$email_message->SetEncodedEmailHeader("To",$to_address,$to_name);
	$email_message->SetEncodedEmailHeader("From",$from_address,$from_name);
	$email_message->SetEncodedEmailHeader("Reply-To",$reply_address,$reply_name);
	$email_message->SetHeader("Sender",$from_address);

	if(defined("PHP_OS")
	&& strcmp(substr(PHP_OS,0,3),"WIN"))
		$email_message->SetHeader("Return-Path",$error_delivery_address);

	$email_message->SetEncodedHeader("Subject",$subject);

	$email_message->CreateQuotedPrintableHTMLPart($messageHTML,"",$html_part);
	$email_message->CreateQuotedPrintableTextPart($email_message->WrapText($messageTXT),"", $text_part);
	$alternative_parts=array(
		$text_part,
		$html_part
	);
	$email_message->AddAlternativeMultipart($alternative_parts);

     $error=$email_message->Send();
     return $error;
}

//Added by Nilesh Patil on 11th June 2007
//echo datediff('w', '9 July 2003', '4 March 2004', false);
function datediff($interval, $datefrom, $dateto, $using_timestamps = true)
{
  /*
    $interval can be:
    yyyy - Number of full years
    q - Number of full quarters
    m - Number of full months
    y - Difference between day numbers
      (eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
    d - Number of full days
    w - Number of full weekdays
    ww - Number of full weeks
    h - Number of full hours
    n - Number of full minutes
    s - Number of full seconds (default)
  */
  
	if (!$using_timestamps)
	{
    	$datefrom = strtotime($datefrom, 0);
    	$dateto = strtotime($dateto, 0);
	}
	$difference = $dateto - $datefrom; // Difference in seconds
  
	switch($interval)
	{
  
    	case 'yyyy': // Number of full years
		$years_difference = floor($difference / 31536000);
		if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), 						
		date("Y", $datefrom)+$years_difference) > $dateto)
		{
        	$years_difference--;
		}	
      	if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto)-($years_difference+1)) > $datefrom) {
        $years_difference++;
      	}
      	$datediff = $years_difference;
      	break;

    	case "q": // Number of full quarters

      	$quarters_difference = floor($difference / 8035200);
      	while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($quarters_difference*3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
        $months_difference++;
      	}
      	$quarters_difference--;
      	$datediff = $quarters_difference;
      	break;

    	case "m": // Number of full months

      	$months_difference = floor($difference / 2678400);
      	while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
        $months_difference++;
      	}
      	$months_difference--;
      	$datediff = $months_difference;
      	break;

    	case 'y': // Difference between day numbers

	    $datediff = date("z", $dateto) - date("z", $datefrom);
      	break;

    	case "d": // Number of full days

      	$datediff = floor($difference / 86400);
      	break;

    	case "w": // Number of full weekdays

      	$days_difference = floor($difference / 86400);
      	$weeks_difference = floor($days_difference / 7); // Complete weeks
      	$first_day = date("w", $datefrom);
      	$days_remainder = floor($days_difference % 7);
      	$odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?
      	if ($odd_days > 7) { // Sunday
        $days_remainder--;
      	}
      	if ($odd_days > 6) { // Saturday
      	  $days_remainder--;
      	}
      	$datediff = ($weeks_difference * 5) + $days_remainder;
      	break;

    	case "ww": // Number of full weeks

      	$datediff = floor($difference / 604800);
      	break;

    	case "h": // Number of full hours

      	$datediff = floor($difference / 3600);
      	break;

    	case "n": // Number of full minutes

	    $datediff = floor($difference / 60);
      	break;

    	default: // Number of full seconds (default)

      	$datediff = $difference;
      	break;
  }    

  return $datediff;

}

  // $files : $_FILES array
  // $newimagename : New Image name for the file to be stored
  // $path : path where image will b stored
  function image_upload($files,$path,$max_size){
        
		
  		define ("MAX_SIZE",$max_size); 
  
 	 	//This variable is used as a flag. The value is initialized with 0 (meaning no error  found)  
		//and it will be changed to 1 if an errro occures.  
		//If the error occures the file will not be uploaded.
		 $errors=0;
		 $errmsg = '';
			//reads the name of the file the user submitted for uploading
			$image=$files['banner']['name'];
			
			//if it is not empty
			if($image) 
			{
			//get the original name of the file from the clients machine
				$filename = stripslashes($files['banner']['name']);
			//get the extension of the file in a lower case format
				
				$extension = getExtension($filename);
				$extension = strtolower($extension);
			//if it is not a known extension, we will suppose it is an error and will not  upload the file,  
			//otherwise we will do more tests
			 if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) 
			{
				//print error message
					$errmsg .= '<br>Unknown extension!</br>';
					$errors=1;
			}
				else
			{
				 //get the size of the image in bytes
				 //$_FILES['image']['tmp_name'] is the temporary filename of the file
				 //in which the uploaded file was stored on the server
				 $size=filesize($files['banner']['tmp_name']);
				
				//compare the size with the maxim size we defined and print error if bigger
				if ($size > MAX_SIZE*1024)
				{
					$errmsg .= '<br>You have exceeded the size limit!<br>';
					$errors=1;
				}
				
				$newname=$path.$extension;
				
				//we verify if the image has been uploaded, and print error instead
				$copied = copy($files['banner']['tmp_name'], $newname);
				
				if (!$copied) 
				{
					$errmsg .= '<br>Copy unsuccessfull!</br>';
					$errors=1;
				}
				
			}// CHECK EXTENSION
		}// IF IMAGE
		
		
		//If no errors registred, print the success message
		 if(isset($_POST['Submit']) && !$errors) 
		 {
			$errmsg .= "<br>File Uploaded Successfully! Try again!</br>";
		 }
		return $errmsg;
  }// END FUNCTION image_upload
  
  
  //This function reads the extension of the file. It is used to determine if the file  is an image by checking the extension.
		 function getExtension($str) {
				 $i = strrpos($str,".");
				 if (!$i) { return ""; }
				 $l = strlen($str) - $i;
				 $ext = substr($str,$i+1,$l);
				 return $ext;
		 }// EDN getExtension
                
    function sameCurrencyCode($transacationArray){
     $currType=''; 
        if(count($transacationArray)>0){
            $count=0;
            foreach($transacationArray as $k=>$v){
                if($v['conversionRate']!=1 && $v['currencyCode']!="INR"){
                    $v['currencyCode']="INR";
                }
                if($count==0 && !empty($v['currencyCode'])){
                    if($v['conversionRate'] == 1 && $v['paypal_converted_amount'] > 0){
                        $currType = "USD";
                    }else{
                        $currType=$v['currencyCode'];
                    }
                    $count++;	
                }
                if($currType!=$v['currencyCode'] && !empty($v['currencyCode']))
                {
                   $currType='';break;
                }
            }
        }
        return $currType;
    }
                 
                 function totalStrWithCurrencies($totalArray){
                     $totalArrayStr='';
                     if(count($totalArray)>0){
                        ksort($totalArray);
                        $tot=NULL;
                        foreach($totalArray as $k=>$v){
                            if($v!=0){
                                $tot.=$k." ".round($v,2)."<br>";
                            }
                                
                        }
                            $totalArrayStr=$tot;
                     }
                     return strlen($totalArrayStr)>0?$totalArrayStr:0;
                 }
?>