<?php

/**
 * email related business logic will be defined in this class
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     17-07-2015
 * @Last Modified 17-07-2015
 */
require_once(APPPATH . 'handlers/handler.php');
require_once(APPPATH . 'handlers/sentmessage_handler.php');
require_once(APPPATH . 'handlers/messagetemplate_handler.php');

class Email_handler extends Handler {

    var $ci,$messagetemplateHandler;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->library('email');
    }

    //Function to send email with pdf 'path'
    function sendEmail($from, $to, $subject, $message, $attachment = '', $replyto = '', $content = NULL, $sentMessageArray = array()) {
        if ($this->ci->config->Item('emailEnable') == true) {
            //CODE RELATED TO MANDRILL MAIL   
            $this->ci->email->from($from);
			$this->ci->email->reply_to($replyto);         
            $this->ci->email->to($to);
            $this->ci->email->subject($subject);
            $this->ci->email->message($message);
           // $cc = '';
            $bcc = '';
            $uid = time();
            if (!empty($attachment)) {
                $this->ci->email->attach($attachment);
            }
            $mailResponse = $this->ci->email->send();
            if ($mailResponse) {
                $mailStatus = $mailResponse;
            } else {//If mandrilla mail send failed
                //MANUAL MAIL SENDING                 
                $email_txt = $message; // Message that the email has in it     
                $headers = "From: " . $from;
                $data = $fileatt = $fileatt_type = $fileatt_name = $data = '';
                if ($attachment) {
                    $fileatt = $attachment; // Path to the file
                    $fileatt_type = "application/octet-stream"; // File Type 
                    $start = strrpos($attachment, '/') == -1 ? strrpos($attachment, '//') : strrpos($attachment, '/') + 1;
                    $fileatt_name = substr($attachment, $start, strlen($attachment)); // Filename that will be used for the file as the attachment 
                    $file = fopen($fileatt, 'rb');
                    $data = fread($file, filesize($fileatt));
                    fclose($file);
                }
                $semi_rand = md5(time());
                $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
                $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";
                $email_message .= "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type:text/html; charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $email_txt . "\n\n";
                $data = chunk_split(base64_encode($data));
                $email_message .= "--{$mime_boundary}\n" . "Content-Type: {$fileatt_type};\n" . " name=\"{$fileatt_name}\"\n" . //"Content-Disposition: attachment;\n" . //" filename=\"{$fileatt_name}\"\n" . "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n" . "--{$mime_boundary}--\n";
                        $mailStatus = mail($to, $subject, $email_message, $headers);
            }
            $sentMessageArray['status'] = $mailStatus;
            if (!isset($sentMessageArray['notInsertIntoSentMessage'])) {//There are some emails which are not needed to be inserted in to sentmessages
                $this->sentmessageHandler = new Sentmessage_handler();
                $sendMessage = $this->sentmessageHandler->insertSentMessageDetail($sentMessageArray);
            }
            if ($mailStatus) {
                $output['status'] = TRUE;
                $output["response"]['email'] = Email_SENT;
                $output["response"]['messages'][] = SUCCESS_MAIL_SENT;
                $output['statusCode'] = STATUS_OK;
                return $output;
            } else {
                $output['status'] = FALSE;
                $output["response"]['messages'][] = ERROR_EMAIL_NOT_SENT;
                $output['statusCode'] = STATUS_SERVER_ERROR;
                return $output;
            }
        }
        $output['status'] = TRUE;
        $output["response"]['email'] = Email_SENT;
        $output["response"]['messages'][] = ERROR_EMAIL_NOT_SENT;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    // Function to send Email With mpdf Attachment(with pdf 'content')
    function EmailSend($to, $cc, $bcc, $from, $subject, $message, $content = NULL, $filename = NULL, $folder = NULL, $sentMessageArray = array()) {
        
        require_once (APPPATH . 'libraries/Swift/lib/swift_required.php');
        if ($this->ci->config->Item('emailEnable') == true) {
            $this->ci->config->load('email');
            /*             * ********************* CODE RELATED TO MANDRILL MAIL ************************************ */
            //$to, $cc, $bcc - list of mail id's seperated by comma (,)
            //$from - from name<from mail id>. Ex: MeraEvents<admin@meraevents.com>
            //$replyto - single mail id
            //$subject - subject of the mail
            //$message - message/body of the mail. it may be html  or plain text
            //$content -  attached file content. if no attachment is there, then value must be NULL
            //$filename - attached file name. if no attachment is there, then value must be NULL
            $transport = Swift_SmtpTransport::newInstance('smtp.mandrillapp.com', 587);
            $transport->setUsername($this->ci->config->item('smtp_user'));
            $transport->setPassword($this->ci->config->item('smtp_pass'));
            $swift = Swift_Mailer::newInstance($transport);
            /*             * ********************* CODE RELATED TO MANDRILL MAIL ************************************ */
            if (strpos($to, ',') !== FALSE) {
                $mailto = explode(',', $to);
                foreach ($mailto as $key => $value) {
                    $value = trim($value);
                    if (strlen(trim($value)) > 0 && filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $mailto[$key] = $value;
                    } else {
                        unset($mailto[$key]);
                    }
                }
            } else {
                $mailto = $to;
            }

            $mailcc = strlen($cc) > 0 ? explode(',', $cc) : array();
            $mailbcc = strlen($bcc) > 0 ? explode(',', $bcc) : array();
            if (strlen($filename) > 0) {
                $filenameEx = explode(".", $filename);
                $fileExtension = end($filenameEx);
            }
            //throw new Exception('shashi');
            try {
                $fromEx = explode("<", $from);
                $from_name = $fromEx[0];
                $from_mail = substr($fromEx[1], 0, -1);
                $mess = new Swift_Message($subject);
                $mess->setFrom(array($from_mail => $from_name));
                $mess->setBody($message, 'text/html');
                $mess->addPart($message, 'text/plain');
                $mess->setTo($mailto);

                if (!empty($mailbcc)) {
                    $mess->setBcc($mailbcc);
                }
                if (!empty($mailcc)) {
                    $mess->setCc($mailcc);
                }

                if (strlen($content) > 0) {
                    if (strtolower($fileExtension) == 'pdf') {
                        $mess->attach(Swift_Attachment::newInstance($content, $filename, 'application/pdf'));
                    } elseif (strtolower($fileExtension) == 'csv') {
                        $mess->attach(Swift_Attachment::newInstance($content, $filename, 'application/csv'));
                    }
                }

                $mailStatus = $swift->send($mess, $failures);
            } catch (Exception $e) {//Manual mail sending
                $fileatt_type = "application/octet-stream"; // File Type 
                $fileatt_name = $filename; // Filename that will be used for the file as the attachment 

                $email_from = $from; // Who the email is from
                //$subject = "New Attachment Message";

                $email_subject = $subject; // The Subject of the email 
                $email_txt = $message; // Message that the email has in it 
                $email_to = $to; // Who the email is to

                $headers = "From: " . $email_from;
                //$file = fopen($fileatt,'rb'); 
                $data = $content;
                $semi_rand = md5(time());
                $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
                $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";

                $email_message .= "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type:text/html; charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $email_txt . "\n\n";
                $data = chunk_split(base64_encode($data));
                $email_message .= "--{$mime_boundary}\n" . "Content-Type: {$fileatt_type};\n" . " name=\"{$fileatt_name}\"\n" . //"Content-Disposition: attachment;\n" . //" filename=\"{$fileatt_name}\"\n" . "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n" . "--{$mime_boundary}--\n";
                $mailStatus = mail($email_to, $email_subject, $email_message, $headers);
            }
            $sentMessageArray['status'] = $mailStatus;
            if (!isset($sentMessageArray['notInsertIntoSentMessage'])) {//There are some emails which are not needed to be inserted in to sentmessages
                $this->sentmessageHandler = new Sentmessage_handler();
                $sendMessage = $this->sentmessageHandler->insertSentMessageDetail($sentMessageArray);
            }
            if ($mailStatus) {
                $output['status'] = TRUE;
                $output["response"]['messages'][] = SUCCESS_MAIL_SENT;
                $output['statusCode'] = STATUS_OK;
                return $output;
            } else {
                $output['status'] = FALSE;
                $output["response"]['messages'][] = ERROR_EMAIL_NOT_SENT;
                $output['statusCode'] = STATUS_SERVER_ERROR;
                return $output;
            }
        }
        //If we disable email
        $output['status'] = TRUE;
        $output["response"]['email'] = Email_SENT;
        $output["response"]['messages'][] = '';
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    function manualMailSending($from, $to, $subject, $message, $cc, $bcc, $replyto='', $filename = NULL,$content = NULL){
                $uid = md5(uniqid(time()));
                $header = "From:" . $from . "\r\n" .
                        'Cc: ' . $cc . "\r\n" .
                        'Bcc: ' . $bcc . "\r\n";
                
                if (strlen($filename) > 0) {
                    $filenameEx = explode(".", $filename);
                    $fileExtension = end($filenameEx);
                }
                
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
                $mailStatus=@mail($to, $subject, $message, $header);
                return $mailStatus;
    }

     public function sendSms($Mobile, $Message,$sentMessageArray=array()) {
    	if($this->ci->config->Item('smsEnable')==true){
	    	$ch = curl_init();
	    	$user = $this->ci->config->item('smsUserName');//"srinivasrp@cbizsoftindia.com:Mera123";
	    	$receipientno = $Mobile;
	    	$senderID = "MERAEN";
	    	$msgtxt = $Message;
	    	curl_setopt($ch, CURLOPT_URL, "http://api.mVaayoo.com/mvaayooapi/MessageCompose");
	    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    	curl_setopt($ch, CURLOPT_POST, 1);
	    	curl_setopt($ch, CURLOPT_POSTFIELDS, "user=$user&senderID=$senderID&receipientno=$receipientno&msgtxt=$msgtxt");
	    	$buffer = curl_exec($ch);
	    	curl_close($ch);
                if (empty($buffer)) {
                    $smsStatus=0;
                }else{
                     $smsStatus=1;
                }
                $sentMessageArray['status']=$smsStatus;
                $this->sentmessageHandler=new Sentmessage_handler();
                $sendMessage=$this->sentmessageHandler->insertSentMessageDetail($sentMessageArray);               
                if (!$smsStatus) {
                    $output['status'] = FALSE;
                    $output['response']['messages'][] = ERROR_SMS_SEND;
                    $output['statusCode'] = STATUS_SMS_NOT_SENT;
                    return $output;
                } else {
                    $output['status'] = TRUE;
                    $output['response']['messages'][] = SUCCESS_SMS_SEND;
                    $output['statusCode'] = STATUS_OK;
                    return $output;
                }
        }
                $output['status'] = TRUE;
                $output['response']['messages'][] = SUCCESS_SMS_SEND;
                $output['statusCode'] = STATUS_OK;
                return $output;
    } 
    
    public function sendTransactionSuccessEmailToDelegate($data){
    	$this->ci->load->library('parser');
        
    	if(isset($data['customemail']) && $data['customemail']==1){
    		$inputArray['type'] = 'delegateConfrimation';
    		$inputArray['mode'] = 'email';
    		$inputArray['eventid'] = $data['eventData']['id'];
                $this->messagetemplateHandler = new Messagetemplate_handler();
    		$multipleDelegatepassEmailtemplate = $this->messagetemplateHandler->getTemplateDetail($inputArray,1);
    		if(isset($multipleDelegatepassEmailtemplate['response']['templateDetail']) && !empty($multipleDelegatepassEmailtemplate['response']['templateDetail'])){
    			foreach ($multipleDelegatepassEmailtemplate['response']['templateDetail'] as $tdkey => $tdvalue) {
    				$customeEmailResponse=$this->customEmails($tdvalue,$data,$data['eventData']['id']);
    			}
    			if ($customeEmailResponse) {
    				$output['status'] = TRUE;
    				$output['response']['messages'][] = Email_SENT;
    				$output['statusCode'] = STATUS_OK;
    				if($data['organizerEmail']['emailsend'] == true){
    					$messageData = $this->sendTransactionSuccessEmailToOrganizer($data);
    					$message = $messageData['message'];
    					$from = $messageData['from'];
    					$cc='';
    					$subject = SUBJECT_ORGEVENTSIGNUP_SUCCESS . stripslashes($data['eventData']['title']) . " " . $promocode . ' - ' . $eventsignupId;
    					if($data['organizerEmail']['emailsend'] && strlen($data['organizerEmail']['cc'])>0){
    						$cc = $data['cc'];
    					}
    					$to =$data['organizerDetails']['email'];
    					$sentmessageInputs=array();
    					$sentmessageInputs['eventsignupid'] = $messageData['eventsignupId'];
    					$sentmessageInputs['messageid'] = $messageData['messageid'];
    					$email = $this->EmailSend($to, $cc, $bcc, $from, $subject,$message, $content, $filename,'',$sentmessageInputs);
    				}
    				return $output;
    			} else {
    				$output['status'] = FALSE;
    				$output['response']['messages'][] = ERROR_SOMETHING_WENT_WRONG;
    				$output['statusCode'] = STATUS_MAIL_NOT_SENT;
    				return $output;
    			}
    		}
    	}else{
    		$configCustomDatemsg = json_decode(CONFIGCUSTOMDATEMSG,true);
    		$configTransactionDateonPass = json_decode(CONFIGTRANSACTIONDATEONPASS,true);
    		$configCustomTimemsg =  json_decode(CONFIGCUSTOMTIMEMSG,true);
    		$inputArray['type'] = TYPE_DELEGATEEVENT_REG;
    		$inputArray['mode'] = 'email';
    		$eventsignupid = $data['eventsignupDetails']['id'];
    		$displaytransaction=1;
                if(isset($data['offlineName'])){
                   $attendeeName = $data['offlineName'];
                }else{
                   $attendeeName = $this->ci->customsession->getData('userName'); 
                }
                $this->messagetemplateHandler = new Messagetemplate_handler();
    		$delegatepassEmailtemplate = $this->messagetemplateHandler->getTemplateDetail($inputArray);
    		$emailtemplate = $delegatepassEmailtemplate['response']['templateDetail']['template'];
    		
    		$transactiontickettypes = explode(',',$data['eventsignupDetails']['transactiontickettype']);
    		if((count($transactiontickettypes) ==1 && $transactiontickettypes[0] == 'free' ) || $data['eventsignupDetails']['paymenttransactionid'] == 'A1'){
    			$displaytransaction=0;
    		}
    		//SEND EMAIL AND SMS IF PAYMENT SUCCESSFULL
    		$fromemailid = $delegatepassEmailtemplate['response']['templateDetail']['fromemailid'];
    		$emailMsgid = $delegatepassEmailtemplate['response']['templateDetail']['id'];
    
    		//$attendeeName = isset($data['eventsignupattendeedetails'][0]['name'])?$data['eventsignupattendeedetails'][0]['name']:'';
    		$subject = SUBJECT_DELEGATEEVENT_REG .stripslashes($data['eventData']['title']);
    		//Data for parsing the template
    		$Eventid = $data['eventData']['id'];
    		$dataParseArray['userName']=ucfirst($attendeeName);
    		$dataParseArray['trueSemanticFeedBack']=$this->trueSemanticFeedBackFormHTML($data['eventsignupDetails']['id']);
    		$dataParseArray['eventTitle']=stripslashes($data['eventData']['title']);
    		$dataParseArray['venue'] = $data['eventData']['fullAddress'];
            if($data['eventData']['eventMode'] == 1 && $data['eventData']['fullAddress'] == '') {
                $dataParseArray['venue'] = 'Webinar';    
            }
    		$dataParseArray['eventUrl']= $data['eventData']['eventUrl'];
    		$dataParseArray['amountPaid']=$data['eventsignupDetails']['currencyCode'].' ';
    		/* if( $data['eventsignupDetails']['eventextrachargeid']>0){
    			$data['eventsignupDetails']['totalamount']=  $data['eventsignupDetails']['totalamount']- $data['eventsignupDetails']['eventextrachargeamount'];
    		} */
    		$dataParseArray['amountPaid'].= $data['eventsignupDetails']['totalamount']>0?round($data['eventsignupDetails']['totalamount']):'0';
    		$dataParseArray['numberOfCustomers']=$data['eventsignupDetails']['quantity'];
    		$dataParseArray['plural']=' ';
                $dataParseArray['supportLink'] = commonHelperGetPageUrl('contactUs');
    		if($data['eventsignupDetails']['quantity']>1){
    			$dataParseArray['plural']='s';
    		}
    		 
    		if($displaytransaction == 1){
    			$dataParseArray['transactionNumber']= "<span style='display:block;clear:both;font-weight:normal;'>Transaction No </span><span style='font-weight:bold;'>".$data['eventsignupDetails']['paymenttransactionid'].'</span>';
    
    		}else{
    			$dataParseArray['transactionNumber']= '';
    		}
    		$dataParseArray['regNumber']=$data['eventsignupDetails']['id'];
    		$convertedStartDate=convertTime($data['eventData']['startDate'],$data['eventData']['location']['timeZoneName'],TRUE);
    		$convertedEndDate=convertTime($data['eventData']['endDate'],$data['eventData']['location']['timeZoneName'],TRUE);
    		if(isset($configCustomDatemsg[$Eventid]) && !isset($configCustomTimemsg[$Eventid])){
    			$eventDate =$configCustomDatemsg[$Eventid]. " | ". allTimeFormats($convertedStartDate, 2) . " to " . allTimeFormats($convertedEndDate, 2);
    		}else if(isset($configCustomTimemsg[$Eventid]) && !isset($configCustomDatemsg[$Eventid])){
    			$eventDate = allTimeFormats($convertedStartDate, 8) . " - " . allTimeFormats($convertedEndDate, 8) . ' | ' .$configCustomTimemsg[$Eventid] ;
    		} else if(isset($configCustomTimemsg[$Eventid]) && isset($configCustomDatemsg[$Eventid])){
    			$eventDate =$configCustomDatemsg[$Eventid].  ' | ' .$configCustomTimemsg[$Eventid] ;
    		}else {
    			$eventDate = allTimeFormats($convertedStartDate, 8) . " - " . allTimeFormats($convertedEndDate, 8) . ' | ' . allTimeFormats($convertedStartDate, 2) . " to " . allTimeFormats($convertedEndDate, 2);
    		}
    		$dataParseArray['eventDate']=$eventDate;
    		$dataParseArray['contactInfo']=$data['eventData']['eventDetails']['contactdetails'];
    		$dataParseArray['supportPhone']=	GENERAL_INQUIRY_MOBILE;
    		$dataParseArray['supportEmail']=	GENERAL_INQUIRY_EMAIL;
    		$dataParseArray['siteUrl'] = site_url();
    		$dataParseArray['siteName']='MeraEvents.com';
    		$dataParseArray['currentYear']=allTimeFormats(' ',17);
    		$message = $this->ci->parser->parse_string($emailtemplate,$dataParseArray,TRUE);
    		
    		$to = $data['userDetail']['email'];
//    		if (isset($data['deltype'])&& $data['deltype']== 'offlinedelegate') {
//    			$to = $data['email'];
//    		}
                $cc = '';
    		if (isset($data['orgtype'])&& $data['orgtype'] == 'offlineorgnizer') {
    			$to = $data['orgemail'];
                        $cc=$data['email'];
    		}
    		if (isset($data['customuserEmail'])&& $data['customuserEmail'] != '') {
    			$to = $data['customuserEmail'];
    		}
    		$from = $fromemailid;
    		$bcc = '';
    		$delegatepasshtml = $data['delegatepassTemplateData'];
    		/*$this->ci->load->library('pdf');
    		$mpdf = $this->ci->pdf->load();
    		$mpdf->WriteHTML($delegatepasshtml);
    		$content = $mpdf->Output('', 'S');*/
            require_once(APPPATH . 'libraries/mpdf/mpdf.php');
            $mpdf = new mPDF('c');
            $mpdf->WriteHTML($delegatepasshtml);
            $content = $mpdf->Output('', 'S');
    		//$cc = '';
    		$filename = "delegatepass.pdf";
    		$sentmessageInputs['eventsignupid'] =$eventsignupid;
    		$sentmessageInputs['messageid'] = $delegatepassEmailtemplate['response']['templateDetail']['id'];                
    		$email = $this->EmailSend($to, $cc, $bcc, $from, $subject,$message, $content, $filename,'',$sentmessageInputs);
    		if($data['organizerEmail']['emailsend']){
    			$messageData = $this->sendTransactionSuccessEmailToOrganizer($data);
    			$message = $messageData['message'];
    			$from = $messageData['from'];
    			//$cc='';
	    		$subject = SUBJECT_ORGEVENTSIGNUP_SUCCESS . stripslashes($data['eventData']['title']) . " " . $promocode . ' - ' . $eventsignupId;
	    		if($data['organizerEmail']['emailsend'] && strlen($data['organizerEmail']['cc'])>0){
	    			$cc = $data['organizerEmail']['cc'];
	    		}
	    		$to =$data['organizerDetails']['email'];
	    		$sentmessageInputs=array();
	    		$sentmessageInputs['eventsignupid'] = $messageData['eventsignupId'];
	    		$sentmessageInputs['messageid'] = $messageData['messageid'];
	    		$email = $this->EmailSend($to, $cc, $bcc, $from, $subject,$message, $content, $filename,'',$sentmessageInputs);
	            return $email;
    		}else{
    			return $email;
    		}
    	}
    }
    
// Function To send Email to Organizer after Successfull Transaction
    public function sendTransactionSuccessEmailToOrganizer($data) {
    	$this->ci->load->library('parser');
        $inputArray['type'] = TYPE_ORGEVENTSIGNUP_SUCCESS;
        $inputArray['mode'] = 'email';
        //Promocode
        $promocode = '';
        $displaytransaction=1;
        $eventsignupId =  $data['eventsignupDetails']['id'];
        $templateData['organizerName'] = ucfirst($data['organizerDetails']['name']);
        $templateData['eventsignupId'] = $data['eventsignupDetails']['id'];
        $transactiontickettypes = explode(',',$data['eventsignupDetails']['transactiontickettype']);
        if(count($transactiontickettypes) ==1 && $transactiontickettypes[0] == 'free'){
        	$displaytransaction=0;
        }
        // Attendee Details
        $custAttName = ucfirst($data['attendees']['mainAttendee'][0]['Full Name']);
        $custAttComp = isset($data['attendees']['mainAttendee'][0]['Company Name']) ?$data['attendees']['mainAttendee'][0]['Company Name'] : '';
        $custAttDesig = isset($data['attendees']['mainAttendee'][0]['Designation']) ? $data['attendees']['mainAttendee'][0]['Designation'] : '';
        $custAttCity = isset($data['attendees']['mainAttendee'][0]['City']) ? $data['attendees']['mainAttendee'][0]['City'] : '';
        $custAttEmail = isset($data['attendees']['mainAttendee'][0]['Email Id']) ? $data['attendees']['mainAttendee'][0]['Email Id'] : '';
        $custAttMobile = $data['attendees']['mainAttendee'][0]['mobile'];
        $this->messagetemplateHandler = new Messagetemplate_handler();
        $OrganizerEmailtemplate = $this->messagetemplateHandler->getTemplateDetail($inputArray);
        $organizerTemplate = $OrganizerEmailtemplate['response']['templateDetail']['template'];
        //SEND EMAIL AND SMS IF PAYMENT SUCCESSFULL
        $subject = SUBJECT_ORGEVENTSIGNUP_SUCCESS . stripslashes($data['eventData']['title']) . " " . $promocode . ' - ' . $eventsignupId;
        $fromemailid = $OrganizerEmailtemplate['response']['templateDetail']['fromemailid'];
        $emailMsgid = $OrganizerEmailtemplate['response']['templateDetail']['id'];
        $EmailTxt = '';
        $SeatsNo = '';
        $templateData['eventsignupId'] =$eventsignupId;
        $Contact = $data['eventData']['eventDetails']['contactdetails'];
        $templateData['eventTitle'] = $data['eventData']['title'];
        $templateData['eventUrl'] = $data['eventData']['eventUrl'];
        $templateData['currencyCode'] = $data['eventsignupDetails']['currencyCode'];
        if($displaytransaction == 1){
        	$templateData['transactionId']= "<span style='display:block;clear:both;font-weight:normal;'>Transaction No </span><span style='font-weight:bold;'>".$data['eventsignupDetails']['paymenttransactionid'].'</span>';
        }else{
        	$templateData['transactionId']='';
        }
        $eventsignupticketdata = $data['eventsignupticketdetails'];
        if( $data['eventsignupDetails']['eventextrachargeamount']>0){
        	 $data['eventsignupDetails']['totalamount']=  $data['eventsignupDetails']['totalamount']- $data['eventsignupDetails']['eventextrachargeamount'];
        }

        $eventsignupTotalAmnt = $data['eventsignupDetails']['totalamount']>0?round($data['eventsignupDetails']['totalamount'])." " .$data['eventsignupDetails']['currencyCode']:0;
        $templateData['transactionTotalAmount'] = $eventsignupTotalAmnt;
	$templateData['transactionQuantity']  =    $data['eventsignupDetails']['quantity'];   
    	$templateData['plural']=' ';
    	if($data['eventsignupDetails']['quantity']>1){
            $templateData['plural']='s ';
    	}        
        $orgMailBody= '<table width="100%" style="border:1px solid #f2f2f2;" cellspacing="0" cellspacing="0">
                    <tbody>';   
        $orgMailBody.='<tr>
                            <th width="230" style="font-family: calibri,Helvetica, Arial, sans-serif; font-weight: bold; font-size:18px; text-align:left; color:#313131; padding:10px;border-bottom:1px solid #f2f2f2">Name <span style="float:right;">:</span>
                            </th>
                            <td style="font-family: calibri,Helvetica, Arial, sans-serif; font-weight: normal; font-size:18px; text-align:left; color:#313131; padding:10px;border-bottom:1px solid #f2f2f2">' . $custAttName . '</td>
                        </tr>';
        if (strlen($custAttComp) > 0) {
        $orgMailBody='<tr>
                            <th style="font-family: calibri,Helvetica, Arial, sans-serif; font-weight: bold; font-size:18px; text-align:left; color:#313131; padding:10px;border-bottom:1px solid #f2f2f2">Company <span style="float:right;">:</span>
                            </th>
                            <td style="font-family: calibri,Helvetica, Arial, sans-serif; font-weight: normal; font-size:18px; text-align:left; color:#313131; padding:10px;border-bottom:1px solid #f2f2f2">' . $custAttComp . '</td>
                        </tr>';
        }
        if (strlen($custAttDesig) > 0) {
            $orgMailBody.='<tr>
                            <th style="font-family: calibri,Helvetica, Arial, sans-serif; font-weight: bold; font-size:18px; text-align:left; color:#313131; padding:10px;border-bottom:1px solid #f2f2f2">Designation <span style="float:right;">:</span>
                            </th>
                            <td style="font-family: calibri,Helvetica, Arial, sans-serif; font-weight: normal; font-size:18px; text-align:left; color:#313131; padding:10px;border-bottom:1px solid #f2f2f2">' . $custAttDesig . '</td>
                        </tr>';
        }
        if (strlen($custAttCity) > 0) {
            $orgMailBody.='<tr>
                            <th style="font-family: calibri,Helvetica, Arial, sans-serif; font-weight: bold; font-size:18px; text-align:left; color:#313131; padding:10px;border-bottom:1px solid #f2f2f2">City <span style="float:right;">:</span>
                            </th>
                            <td style="font-family: calibri,Helvetica, Arial, sans-serif; font-weight: normal; font-size:18px; text-align:left; color:#313131; padding:10px;border-bottom:1px solid #f2f2f2">' . $custAttCity . '</td>
                        </tr>';
        }
        if (strlen($custAttEmail) > 0) {
            $orgMailBody.='<tr>
                            <th style="font-family: calibri,Helvetica, Arial, sans-serif; font-weight: bold; font-size:18px; text-align:left; color:#313131; padding:10px;border-bottom:1px solid #f2f2f2">Email Id <span style="float:right;">:</span>
                            </th>
                            <td style="font-family: calibri,Helvetica, Arial, sans-serif; font-weight: normal; font-size:18px; text-align:left; color:#313131; padding:10px;border-bottom:1px solid #f2f2f2">' .$custAttEmail . '</td>
                        </tr>';
        }if (strlen($custAttMobile) > 0) {
            $orgMailBody.='<tr>
                            <th style="font-family: calibri,Helvetica, Arial, sans-serif; font-weight: bold; font-size:18px; text-align:left; color:#313131; padding:10px;">Mobile <span style="float:right;">:</span>
                            </th>
                            <td style="font-family: calibri,Helvetica, Arial, sans-serif; font-weight: normal; font-size:18px; text-align:left; color:#313131; padding:10px;">' . $custAttMobile . '</td>
                        </tr>';
        }
        $orgMailBody.= '</tbody>
                </table>';
        $templateData['customerDetail'] = $orgMailBody;
        $templateData['supportLink'] = commonHelperGetPageUrl('contactUs');
        $templateData['promotionCode'] = "Promotion Code:<b>".$promocode."</b>";
        $TicketDetails='<tr>
                            <td width="200" style="font-family: calibri,Helvetica, Arial, sans-serif; font-weight: normal; font-size:18px; text-align:left;border-bottom:1px solid #f2f2f2;color:#313131;padding:10px 5px;"><strong>Ticket Type</strong>
                            </td>
                            <td style="font-family: calibri,Helvetica, Arial, sans-serif; font-weight: normal; font-size:18px; text-align:center; border-bottom:1px solid #f2f2f2;color:#313131;padding:10px 5px; "><strong>Price</strong>
                            </td>
                            <td style="font-family: calibri,Helvetica, Arial, sans-serif; font-weight: normal; font-size:18px; text-align:center; border-bottom:1px solid #f2f2f2;color:#313131;padding:10px 5px;"><strong>Quantity</strong>
                            </td>

                            <td style="font-family: calibri,Helvetica, Arial, sans-serif; font-weight: normal; font-size:18px; text-align:center; border-bottom:1px solid #f2f2f2;color:#313131;padding:10px 5px;"><strong>Total Amount</strong>
                            </td>
                        </tr>';
        foreach ($data['ticketDetails'] as $key => $value) {
        	if($value['amount']>0){
        		$ticketamount =round($value['amount'])." ".$data['eventsignupDetails']['currencyCode'];
        	}else{
        		$ticketamount =round($value['amount']);
            }
            $ticketUnitPrice = round($value['amount']/$value['ticketquantity']);
            $ticketUnitPrice = $ticketUnitPrice>0?$ticketUnitPrice." ".$data['eventsignupDetails']['currencyCode']:$ticketUnitPrice;
            foreach ($value['ticketTaxes'] as $tax => $taxvalues) {

                $ticketTaxes[$tax]+= $taxvalues;
            }
            $TicketDetails .= '<tr>
                            <td style="font-family: calibri,Helvetica, Arial, sans-serif; font-weight: normal; font-size:16px;  color:#313131;padding:10px 5px;">' . $value['name'] . '</td>

                            <td style="font-family: calibri,Helvetica, Arial, sans-serif; font-weight: normal; font-size:16px; text-align:center; color:#313131;padding:10px 5px;">' . $ticketUnitPrice . '</td>

                            <td style="font-family: calibri,Helvetica, Arial, sans-serif; font-weight: normal; font-size:16px; text-align:center; color:#313131;padding:10px 5px;">' . $value['ticketquantity'] . '</td>

                            <td style="font-family: calibri,Helvetica, Arial, sans-serif; font-weight: normal; font-size:16px; text-align:center; color:#313131;padding:10px 5px;">' . $ticketamount . '</td>
                        </tr>';
        }
        $templateData['ticketDetail'] = $TicketDetails;
        $templateData['supportMobile'] = GENERAL_INQUIRY_MOBILE;
        $templateData['supportEmail'] = GENERAL_INQUIRY_EMAIL;
        $templateData['currentYear'] =allTimeFormats(' ',17);   
        $messageData['from']   = $fromemailid;
        $messageData['messageid'] = $emailMsgid;
        $messageData['eventsignupId'] = $eventsignupId;
        $messageData['message'] = $this->ci->parser->parse_string($organizerTemplate, $templateData, TRUE);
        /* $from = $fromemailid;
        $bcc = '';
        $to =$data['organizerDetails']['email'];
        $delegatepasshtml = $data['delegatepassTemplateData'];
        $this->ci->load->library('pdf');
        $mpdf = $this->ci->pdf->load();
        $mpdf->WriteHTML($data['delegatepassTemplateData']);
        $content = $mpdf->Output('', 'S');
        $cc = ''; 
        $bcc = "";
        if($data['cc']){
        	$cc = $data['cc'];
        }
        $filename = "delegatepass.pdf";
        $sentmessageInputs['eventsignupid'] = $eventsignupId;
        $sentmessageInputs['messageid'] = $emailMsgid;        
        $sendEmail=$this->EmailSend($to, $cc, $bcc, $from, $subject,$message, $content, $filename,'',$sentmessageInputs) */;
        return $messageData;
    }

// Function to send Sms to Delegate
    public function sendSuccessEventsignupsmstoDelegate($request) {
        $output = array();
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($request);
        $this->ci->form_validation->set_rules('eventsignupid', 'eventsignupid', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('mobile', 'mobile', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('eventtitle', 'eventitle', 'required_strict');
        if (!empty($request) && $this->ci->form_validation->run() == FALSE) {
            $validationStatus = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        } else {
            $inputArray['type'] = 'smOrgEvtSUpSucc';
            $inputArray['mode'] = 'sms';
            $eventTitle = $request['eventtitle'];
            $eventSignupid = $request['eventsignupid'];
            $attendeemobile = $request['mobile'];
            $this->messagetemplateHandler = new Messagetemplate_handler();
            $delegatepassEmailtemplate = $this->messagetemplateHandler->getTemplateDetail($inputArray);
            $smstemplatemsg = $delegatepassEmailtemplate['response']['templateDetail']['template'];
            $smstemplatemsg = str_replace('EventTitle', $eventTitle, $smstemplatemsg);
            $smstemplatemsg = str_replace('RandomInvoiceNo', $eventSignupid, $smstemplatemsg);
            $sentMessageArray['eventsignupid'] = $eventSignupid;
            $sentMessageArray['messageid'] = $delegatepassEmailtemplate['response']['templateDetail']['id'];        
            $result = $this->sendSms($attendeemobile, $smstemplatemsg,$sentMessageArray);
            return $result;
        }
    }

    public function EventsignupAffiliateShareEmail($inputArray) {
        //print_r($inputArray);exit;
        $output = array();
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('referralcode', 'referralcode', 'required_strict');
        $this->ci->form_validation->set_rules('eventTitle', 'eventTitle', 'required_strict');
        $this->ci->form_validation->set_rules('eventUrl', 'eventUrl', 'required_strict');
        $this->ci->form_validation->set_rules('referralcodeDetails', 'referralcode Details', 'required_strict|is_array');
        if (!empty($inputArray) && $this->ci->form_validation->run() == FALSE) {
            $validationStatus = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        } else {
            $referralCode = $inputArray['referralcode'];
            $emailArray['type'] = TYPE_SHARE;
            $emailArray['mode'] = 'email';
            $type = ($inputArray['referralcodeDetails']['type']=='percentage')?'%':'';
            $refAmount = $inputArray['referralcodeDetails']['referrercommission'].$type;
            $bookAmount = $inputArray['referralcodeDetails']['receivercommission'].$type;
            if(strlen($type) == 0){
            	 $refAmount =$inputArray['currencyCode']." ".  $inputArray['referralcodeDetails']['referrercommission'].$type;
            	 $bookAmount = $inputArray['currencyCode']." ".$inputArray['referralcodeDetails']['receivercommission'].$type;
            }
            $name =  $inputArray['name'];
            $email =  $inputArray['userEmail'];
            $title = $inputArray['eventTitle'];
            $event_url = $inputArray['eventUrl'];
            $this->messagetemplateHandler = new Messagetemplate_handler();
            $AffiliateEmailtemplate = $this->messagetemplateHandler->getTemplateDetail($emailArray);
            $affiliatetemplatemsg = $AffiliateEmailtemplate['response']['templateDetail']['template'];
            
            $link_to_share = $inputArray['eventUrl'] . "?reffCode=" . $referralCode;
            $subject = SUBJECT_SHARE;
            $discountMessage = 'You get up to '.$refAmount.' reward and up to '.$bookAmount.' discount for your friends.';
            //	$maillink= _HTTP_SITE_ROOT."/shareWithMail.php?reffCode=".$referralCode;// Need to Update
            $maillink = '';
            $fblink = 'http://www.facebook.com/share.php?u=' . urlencode($link_to_share);
            $twiterlink = 'http://twitter.com/home?status=' . substr($title, 0, 100) . '...' . $this->getBitlyURL($link_to_share);
            $googlelink = 'https://plus.google.com/share?url=' . urlencode($link_to_share);
            $linkedinlink = 'http://www.linkedin.com/shareArticle?mini=true&amp;url=' . urlencode($link_to_share) . '&amp;title=' . $title . '&amp;source=Meraevents';
 
            $templateData['Name'] = ucfirst($name);
            $templateData['YOU_GET_UPTO_MESSAGE'] = $discountMessage;
            $templateData['sharelink'] = $link_to_share;
            $templateData['fblink'] = $fblink;
            $templateData['twiterlink'] = $twiterlink;
            $templateData['googlelink'] = $googlelink;
            $templateData['linkedinlink'] = $linkedinlink;
            $templateData['currentYear'] = allTimeFormats(' ',17);
            $templateData['supportLink'] = commonHelperGetPageUrl('contactUs');
            $filename = '';
            $to = $inputArray['userEmail'];
            $folder = '';
            $from = 'MeraEvents<admin@meraevents.com>';
            $bcc = '';
            $content = '';
            $cc = ''; 
            $this->ci->load->library('parser');
            $message = $this->ci->parser->parse_string($affiliatetemplatemsg, $templateData, TRUE);
            $sentmessageInputs['eventsignupid'] =$inputArray['eventsignupid'];
            $sentmessageInputs['messageid'] = $AffiliateEmailtemplate['response']['templateDetail']['id'];
            $sendEmail = $this->EmailSend($to, $cc, $bcc, $from, $subject, $message, $content, $filename,'',$sentmessageInputs);
            return $sendEmail;
        }
    }

    // Generate BItly URL for the URL

    public function getBitlyURL($url) { //function to get Bitly URL
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

    public function sendMailInvitations($inputArray){
        $this->ci->load->library('parser');
        $this->messagetemplateHandler = new Messagetemplate_handler();
        
        $templateInputs['mode'] = 'email';
        $from = $this->ci->config->item('me_not_exist_mailid');
		$replyEmail = $inputArray['from_email'];
        
        if(isset($inputArray['contactorg']) && $inputArray['contactorg'] == TRUE){
            $templateInputs['type'] = TYPE_CONTACT_ORGANIZER;

            $contactOrgTemplate = $this->messagetemplateHandler->getTemplateDetail($templateInputs);
            //preparing data for template
            $subject = $inputArray['from_name'] . " sent you a message ";
            $templateMessage = $contactOrgTemplate['response']['templateDetail']['template'];
            $data['message'] = $inputArray['message'];
            $data['mobile'] = $inputArray['mobile'];
            $data['mailId'] = $inputArray['from_email'];
            $data['name'] = $inputArray['from_name'];
            $sentmessageInputs['notInsertIntoSentMessage'] = 1;

            foreach ($inputArray['toMail'] as $id => $value) {
                $data['orgName'] = ucfirst($value['name']);
                $to = $value['mail'];
                $message = $this->ci->parser->parse_string($templateMessage, $data, TRUE);
                $emailResponse = $this->sendEmail($from, $to, $subject, $message, '', $replyEmail, '', $sentmessageInputs);
            }
            return $emailResponse;
        }else{
            $templateInputs['type'] = TYPE_INVITE_FRIEND;

            $emailFriendsTemplate = $this->messagetemplateHandler->getTemplateDetail($templateInputs);
            $to = $inputArray['to_email'];
            $subject = SUBJECT_INVITE_FRIEND . $inputArray['EventName'];
            //preparing data for template
            //$templateId = $emailFriendsTemplate['response']['templateDetail']['id'];
            $templateMessage = $emailFriendsTemplate['response']['templateDetail']['template'];
            $data['title'] = $inputArray['EventName'];
            $data['message'] = $inputArray['message'];
            $data['DateTime'] = $inputArray['DateTime'];
            $data['location'] = $inputArray['FullAddress'];
            $data['eventLink'] = $inputArray['EventUrl'];
            $data['displayShare'] = 'none';
            $data['supportLink'] = commonHelperGetPageUrl('contactUs');            
            if (!empty($inputArray['refcode'])) {
                $data['displayShare'] = 'block';
                $link_to_share = $inputArray['EventUrl'] . "?reffCode=" . $inputArray['refcode'];
                $data['eventLink'] = $link_to_share;
                $data['fblink'] = 'http://www.facebook.com/share.php?u=' . urlencode($link_to_share);
                $data['twitterlink'] = 'http://twitter.com/home?status=' . substr($inputArray['EventName'], 0, 100) . '...' . $this->getBitlyURL($link_to_share);
                $data['linkedinlink'] = 'http://www.linkedin.com/shareArticle?mini=true&amp;url=' . urlencode($link_to_share) . '&amp;title=' . $inputArray['EventName'] . '&amp;source=Meraevents';
                $data['googlelink'] = 'https://plus.google.com/share?url=' . urlencode($link_to_share);
            }
            $data['siteUrl'] = site_url();
            $data['year'] = allTimeFormats(' ', 17);
            $message = $this->ci->parser->parse_string($templateMessage, $data, TRUE);
            //$sentmessageInputs['messageid'] = $templateId;
            $sentmessageInputs['notInsertIntoSentMessage'] = 1;
            $emailResponse = $this->sendEmail($from, $to, $subject, $message, '', $replyEmail, '', $sentmessageInputs);
            return $emailResponse;
        }
    }


function customEmails($template,$data,$eventsignupid){
        $delegatepassemailtemplate = stripslashes($template['template']);
        $configCustomDatemsg = json_decode(CONFIGCUSTOMDATEMSG,true);
        $configTransactionDateonPass = json_decode(CONFIGTRANSACTIONDATEONPASS,true);
        $configCustomTimemsg =  json_decode(CONFIGCUSTOMTIMEMSG,true);
        $params = array();
        if(isset($template['params']) && $template['params']!=''){
            $params = explode(',', $template['params']);
        }
        //SEND EMAIL AND SMS IF PAYMENT SUCCESSFULL
        $subject = 'You have successfully registered for ' . stripslashes($data['eventData']['title']);
        $fromemailid = $template['fromemailid'];
        $emailMsgid = $template['id'];
        $Eventid = $data['eventData']['id'];
        //$attendeeName = isset($data['eventsignupattendeedetails'][0]['name'])?$data['eventsignupattendeedetails'][0]['name']:'';
    	$dataParseArray['contactInfo']=$data['eventData']['eventDetails']['contactdetails'];
    	$dataParseArray['eventTitle']=stripslashes($data['eventData']['title']);       
    	$dataParseArray['eventUrl']= $data['eventData']['eventUrl'];
    	$convertedStartDate=convertTime($data['eventData']['startDate'],$data['eventData']['location']['timeZoneName'],TRUE);
    	$convertedEndDate=convertTime($data['eventData']['endDate'],$data['eventData']['location']['timeZoneName'],TRUE);
    	if(isset($configCustomDatemsg[$Eventid]) && !isset($configCustomTimemsg[$Eventid])){
    		$eventDate =$configCustomDatemsg[$Eventid]. " | ". allTimeFormats($convertedStartDate, 2) . " to " . allTimeFormats($convertedEndDate, 2);
    	}else if(isset($configCustomTimemsg[$Eventid]) && !isset($configCustomDatemsg[$Eventid])){
    		$eventDate = allTimeFormats($convertedStartDate, 8) . " - " . allTimeFormats($convertedEndDate, 8) . ' | ' .$configCustomTimemsg[$Eventid] ;
    	} else if(isset($configCustomTimemsg[$Eventid]) && isset($configCustomDatemsg[$Eventid])){
    		$eventDate =$configCustomDatemsg[$Eventid].  ' | ' .$configCustomTimemsg[$Eventid] ;
    	}else {
    		$eventDate = allTimeFormats($convertedStartDate, 8) . " - " . allTimeFormats($convertedEndDate, 8) . ' | ' . allTimeFormats($convertedStartDate, 2) . " to " . allTimeFormats($convertedEndDate, 2);
    	}
    	
    	$dataParseArray['eventDate']=$eventDate;    	
    	$dataParseArray['venue'] = $data['eventData']['fullAddress'];
        if($data['eventData']['eventmode'] == 1 && $data['eventData']['fullAddress'] == '') {
            $dataParseArray['venue'] = 'Webinar';
        }
    	$dataParseArray['supportPhone']='+91-9396555888';
    	$dataParseArray['supportEmail']='support@meraevents.com';
    	$dataParseArray['siteUrl'] = site_url();
    	$dataParseArray['siteName']='MeraEvents.com';
        $dataParseArray['supportLink'] = commonHelperGetPageUrl('contactUs');
    	$dataParseArray['currentYear']=allTimeFormats(' ',17);        
        if(!empty($params)){
                $dataParseArray['userName']=ucfirst($this->ci->customsession->getData('userName'));
    		$dataParseArray['amountPaid']=$data['eventsignupDetails']['currencyCode'].' ';
    		$dataParseArray['amountPaid'].= $data['eventsignupDetails']['totalamount']>0?round($data['eventsignupDetails']['totalamount']):'0';
    		$dataParseArray['numberOfCustomers']=$data['eventsignupDetails']['quantity'];
    		$dataParseArray['plural']=' ';
    		if($data['eventsignupDetails']['quantity']>0){
    			$dataParseArray['plural']='s';
    		}
    		 
    		if($displaytransaction == 1){
    			$dataParseArray['transactionNumber']= "Transaction No: ".$data['eventsignupDetails']['paymenttransactionid'];
    
    		}else{
    			$dataParseArray['transactionNumber']= '';
    		}
    		$dataParseArray['regNumber']=$data['eventsignupDetails']['id'];

        }
        $to = $data['userDetail']['email'];
        if ($data['deltype'] == 'offlinedelegate') {
            $to = $data['email'];
        }
        if ($data['orgtype'] == 'offlineorgnizer') {
            $to = $data['orgemail'];
        }
        $from = 'MeraEvents<admin@meraevents.com>';
        $bcc = '';
        $cc = '';
    	$message = $this->ci->parser->parse_string($delegatepassemailtemplate,$dataParseArray,TRUE);
        $sentmessageInputs['eventsignupid'] = $eventsignupid;
        $sentmessageInputs['messageid'] = $emailMsgid; 
         if(!empty($params)){
            $delegatepasshtml = $data['delegatepassTemplateData'];
            $this->ci->load->library('pdf');
            $mpdf = $this->ci->pdf->load();
            $mpdf->WriteHTML($delegatepasshtml);
            $content = $mpdf->Output('', 'S');
            $filename = "delegatepass.pdf";       
            $sendEmail = $this->EmailSend($to, $cc, $bcc, $from, $subject, $message, $content, $filename,'',$sentmessageInputs);
         }else{
            $sendEmail = $this->EmailSend($to, $cc, $bcc, $from, $subject, $message,'','',$sentmessageInputs);
        }
        return $sendEmail;
    }

    public function sendOfflineNoDisplaybooking($data) {
        $this->ci->load->library('parser');
        $inputArray['type'] = TYPE_OFFLINENODISPLAY_BOOKING;
        $inputArray['mode'] = 'email';
        $eventsignupid = $data['eventsignupDetails']['id'];
        $attendeeName = ucfirst($this->ci->customsession->getData('userName'));
        $this->messagetemplateHandler = new Messagetemplate_handler();
        $delegatepassEmailtemplate = $this->messagetemplateHandler->getTemplateDetail($inputArray);
        $delegatepassemailtemplate = $delegatepassEmailtemplate['response']['templateDetail']['template'];

        $downloadLink=commonHelperGetPageUrl('multiple-offlinepass','',"?uniqueId=".$data['uniqueId']."&eventId=".$data['eventId']);
        $emailMsgid = $delegatepassEmailtemplate['response']['templateDetail']['id'];
        $EmailTxt = '';
        $data['VAR_DOWNLOADLINK'] = $downloadLink;
        $data['VAR_TITLE'] = $data['eventData']['title'];
        $convertedStartDate=convertTime($data['eventData']['startDate'],$data['eventData']['location']['timeZoneName'],TRUE);
        $convertedEndDate=convertTime($data['eventData']['endDate'],$data['eventData']['location']['timeZoneName'],TRUE);
        $data['VAR_DATE']=allTimeFormats($convertedStartDate,8)." - ".allTimeFormats($convertedEndDate,8).' | '.allTimeFormats($convertedStartDate,2)." to ".allTimeFormats($convertedEndDate,2);    	        
        $data['VAR_LOCATION'] = $data['eventData']['fullAddress'];
        $data['VAR_NAME'] = ucfirst($data['delegateName']);
        $data['VAR_EMAIL'] = $data['delegateEmail'];
        $data['VAR_MOBILE'] = $data['delegateMobile'];
        $data['VAR_QUANTITY'] = $data['delegateTicketQty'];
        $data['VAR_TICKETNAME'] = $data['delegateTicketType'];
        $data['VAR_PRICE'] = $data['delegateTicketPrice'];
        $data['VAR_TOTAL'] = $data['delegateTicketTotal'];
        $data['VAR_STARTSIGNUPID'] = $data['startSignupId'];
        $data['VAR_ENDSIGNUPID'] = $data['endSignupId'];
        $data['VAR_EVENTURL'] = $data['eventData']['eventUrl'];
        $data['VAR_YEAR']=allTimeFormats(' ',17);
        $data['userEmail'] = $this->ci->customsession->getData('userEmail');
        $data['supportLink'] = commonHelperGetPageUrl('contactUs');
        $to = $data['userEmail'];
        if($data['emailType'] == 'OfflinreNoDisplay'){
        $to = array($data['userEmail'],$data['delegateEmail']);
        }
        if($data['bookingType'] == 'guestBooking'){
          $to = $data['email'];  
        }
        $from = $delegatepassEmailtemplate['response']['templateDetail']['fromemailid'];
        $subject = SUBJECT_OFFLINENODISPLAY_BOOKING . stripslashes($data['eventData']['title']);
        $message = $this->ci->parser->parse_string($delegatepassemailtemplate, $data, TRUE);
        $sentmessageInputs['eventsignupid'] =  $inputArray['eventSignupId'];
        $sentmessageInputs['messageid'] = emailMsgid;      
        $emailResponse = $this->sendEmail($from, $to,  $subject, $message, '', '', '', $sentmessageInputs);  
        $smsData = array();
        $smsData['eventsignupid'] = $data['startSignupId'];
        $smsData['mobile'] = $data['delegateMobile'];
        $smsData['eventtitle'] = $data['eventData']['title'];
        $sms = $this->sendSuccessEventsignupsmstoDelegate($smsData);
        return $emailResponse;
    }
    
    public function referralBonusCongratsEmail($inputArray) {
    	$this->ci->load->library('parser');
    	$templateInputs['type'] = TYPE_REFERALBONUS_CONGRATS;
    	$templateInputs['mode'] = 'email';
    	$this->messagetemplateHandler = new Messagetemplate_handler();
    	$templateDetail = $this->messagetemplateHandler->getTemplateDetail($templateInputs);
    	$emailtemplate = $templateDetail['response']['templateDetail']['template'];
    	$from = $templateDetail['response']['templateDetail']['fromemailid'];
    	$emailMsgId = $templateDetail['response']['templateDetail']['id'];
    	//$attendeeName = isset($data['eventsignupattendeedetails'][0]['name'])?$data['eventsignupattendeedetails'][0]['name']:'';
    	$subject = SUBJECT_REFERALBONUS_CONGRATS;
    	//Data for parsing the template
    	$type = ($inputArray['referralcodeDetails']['type']=='percentage')?'%':'';
    	$dataParseArray['userName']=ucfirst($inputArray['referrerName']);
    	$dataParseArray['refereeName']=ucfirst($inputArray['name']);
    	$dataParseArray['userPoints'] = $inputArray['currencyCode'].'  '.$inputArray['userPoints'];
    	$dataParseArray['referralBonusAmount']=$inputArray['referralcodeDetails']['referrercommission'].$type;
    	$dataParseArray['referreAmount']  =	$inputArray['referralcodeDetails']['receivercommission'].$type;
    	if(strlen($type) == 0){
    		$dataParseArray['referralBonusAmount']=$inputArray['currencyCode'].'  '.$inputArray['referralcodeDetails']['referrercommission'].$type;
    		$dataParseArray['referreAmount']=$inputArray['currencyCode'].'  '.$inputArray['referralcodeDetails']['receivercommission'].$type;
    	}
    	$dataParseArray['eventTitle']=stripslashes($inputArray['eventTitle']);
    	$referalEventUrl = $inputArray['eventUrl']."?reffCode=".$inputArray['referralcode'];
    	$dataParseArray['fbShareUrl']= 'http://www.facebook.com/sharer/sharer.php?u='.$referalEventUrl;
    	$dataParseArray['twitterShareUrl']= "http://twitter.com/?status=Meraevents".$referalEventUrl;
    	$dataParseArray['linkedInShareUrl']= "http://www.linkedin.com/shareArticle?mini=true&amp;url=".$referalEventUrl ."title=Meraevents";
    	$dataParseArray['googlePlusShareUrl']= "https://plus.google.com/share?url=".$referalEventUrl;
    	$dataParseArray['earnedAmountLink']=' ';//Update link when the referral page is done
    	$dataParseArray['supportPhone']='+91-9396555888';
    	$dataParseArray['supportEmail']='support@meraevents.com';
    	$dataParseArray['siteUrl'] = site_url();
    	$dataParseArray['siteName']='MeraEvents.com';
        $dataParseArray['supportLink'] = commonHelperGetPageUrl('contactUs');
    	$dataParseArray['currentYear']=allTimeFormats(' ',17);
    	$message = $this->ci->parser->parse_string($emailtemplate,$dataParseArray,TRUE);
    	$to = $inputArray['referrerEmail'];
        $sentmessageInputs['eventsignupid'] =  $inputArray['eventSignupId'];
        $sentmessageInputs['messageid'] = $emailMsgId;
        $emailResponse = $this->sendEmail($from, $to, $subject, $message, '', '', '', $sentmessageInputs);
        return $emailResponse;
    }
    
    
    // Send Tickets Sold Data to Organizer from Dashboard
    
    public function SendticketSolddataToOrganizer($inputArray) {
    	$ticketList = $inputArray['ticketData'];
    	$taxDetails = $inputArray['taxDetails'];
    	$ticketData = '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:"Trebuchet MS",sans-serif;">
     
    	<thead>
    	<tr>
    	<th style="background-color: #6d6d6d; color:#fff; font-size:14px;padding:10px 0 10px 10px; text-align:left;">Name</th>
    	<th style="background-color: #6d6d6d; color:#fff; font-size:14px;padding:10px 0 10px 10px; text-align:left;">Price</th>
    	<th style="background-color: #6d6d6d; color:#fff; font-size:14px;padding:10px 0 10px 10px; text-align:left;">Start/ End Dates</th>
    	<th style="background-color: #6d6d6d; color:#fff; font-size:14px;padding:10px 0 10px 10px; text-align:left;">Tax(es)</th>
    	<th style="background-color: #6d6d6d; color:#fff; font-size:14px;padding:10px 0 10px 10px; text-align:left;">Sold</th>
    	<th style="background-color: #6d6d6d; color:#fff; font-size:14px;padding:10px 0 10px 10px; text-align:left;">Status</th>
    	</tr>
    	</thead>
    	<tbody>';
    	foreach ($ticketList as $value) { 
            if($value['type']=='paid' ||$value['type']=='addon' ){
                $ticketAmount=$value['currencyCode']." ".$value['price'];
            }elseif($value['type']=='donation'){
                $ticketAmount= "---".'<br> ('.$value['currencyCode'].')';                                                             
            }      
    			             $ticketData .=  ' <tr>
    			                             	<td style="border-bottom:1px solid #f9f9f9; color:#333; font-size:14px;padding:10px 0 10px 10px;text-align:left;">'.$value["name"].'</td>
    			                                <td style="border-bottom:1px solid #f9f9f9; color:#333; font-size:14px;padding:10px 0 10px 10px;text-align:left;">'.$ticketAmount.'</td>
    			                                 <td style="border-bottom:1px solid #f9f9f9; color:#333; font-size:14px;padding:10px 0 10px 10px;text-align:left;">Start: '.date("d-m-Y",strtotime($value['startDate'])).'</br>&nbsp;|&nbsp;End: '.date("d-m-Y",strtotime($value["endDate"])).'</td>
    			                                <td style="border-bottom:1px solid #f9f9f9; color:#333; font-size:14px;padding:10px 0 10px 10px;text-align:left;">';
    			                                
    			                                if(!empty($taxDetails[$value['id']])){
    			                                foreach($taxDetails[$value['id']] as $key => $v){
    			                              $ticketData .= $v['label'].":".$v['value']." %</br>";
    			                                }
    			                                }
    			                                
    			                               $ticketData .=' </td>';
    			                               
    			                                $ticketData .=' <td style="border-bottom:1px solid #f9f9f9; color:#333; font-size:14px;padding:10px 0 10px 10px;text-align:left;">'.$value["totalSoldTickets"].'</td>';
    			                                $ticketData .='<td style="border-bottom:1px solid #f9f9f9; color:#333; font-size:14px;padding:10px 0 10px 10px;text-align:left;">';
	    										$className = 'greenBtn';
			                                    $buttonValue = 'Active';
			                                    if ($value['soldout'] == 1) {
			                                        $className = 'orangrBtn';
			                                        $buttonValue = 'SoldOut';
			                                    }else if($value['displaystatus']==1){
			                                    	$className = 'orangrBtn';
			                                    	$buttonValue = 'Not to Display';
			                                    }
                                                            else if(strtotime($value['endDate'])<strtotime((convertTime(allTimeFormats('',11), $inputArray['eventTimeZoneName'], TRUE)))){
                                                        $className = 'orangrBtn';
		                                        $buttonValue = 'Sale Date Ended';
                                                        }
    			                                 $ticketData .='<button type="button" style="background-color:#00af8a;padding:5px;border:none;color:#fff;">'.$buttonValue.'</button>
    			                                </td></tr>';
    	
        }
        $ticketData .= '</tbody>    </table>';
        $subject = SUBJECT_EVENT_TICKETSOLDDATA.": " .$inputArray['eventId'];
        $from=ADMIN_EMAIL;
        $to = $inputArray['userEmail'];
        $emailResponse = $this->sendEmail($from, $to, $subject, $ticketData, '', '', '');
        return $emailResponse;
    
    
    }
    ///// True Semantic Feed abck form 
    
    public function trueSemanticFeedBackFormHTML($eventSignupId) {
       $content = "";
       if ($this->ci->config->Item('tsFeedbackEnable')) {
       $ratingurl=site_url()."tsfeedback/".$eventSignupId."/bsuccesspage";
       $content = '<tr><td width="10"></td>
                    <td bgcolor="#FFF" style="background:#fff; margin-top:10px;" valign="top">
                              <table class="table540" align="center" border="0" cellpadding="0" cellspacing="0" width="540" style="background:#fff">
                    <tbody><tr><td>
                    <div style="margin-top:20px;">
                    <div style="font-family: calibri,Helvetica, Arial, sans-serif; font-weight: normal; font-size:18px;margin-bottom:.5em;">
                        <strong>Help us improve</strong><br />
                        How likely is it that you would recommend MeraEvents to a friend or colleague?
                    </div>
                    <div>
                       <table width="100%" style="border-collapse:collapse;">
                           <tbody>
                           <tr style="border-bottom:1px solid #eee;height:40px; font-family: calibri,Helvetica, Arial, sans-serif; font-weight: normal;">
                               <td colspan="5" align="left">0- Not at all likely</td>
                               <td colspan="6" align="right">10- Extremely likely</td>
                           </tr>

                           <tr style="height:40px;">
                               <td align="center">
                                   <a href="'.$ratingurl.'/0" style="width:25px;display:block;border:2px solid #1e8bc3;line-height:1.8;text-decoration: none;background:#fff;color: #333;border-radius:50%;">
                                       <span style="font-size: 14px;">0</span>
                                   </a>
                               </td>
                               <td align="center">
                                   <a href="'.$ratingurl.'/1" style="width:25px;display:block;border:2px solid #1e8bc3;line-height:1.8;text-decoration: none;background:#fff;color: #333;border-radius:50%;">
                                       <span style="font-size: 14px;">1</span>
                                   </a>
                               </td>
                               <td align="center">
                                   <a href="'.$ratingurl.'/2" style="width:25px;display:block;border:2px solid #1e8bc3;line-height:1.8;text-decoration: none;background:#fff;color: #333;border-radius:50%;">
                                       <span style="font-size: 14px;">2</span>
                                   </a>
                               </td>
                               <td align="center">
                                   <a href="'.$ratingurl.'/3" style="width:25px;display:block;border:2px solid #1e8bc3;line-height:1.8;text-decoration: none;background:#fff;color: #333;border-radius:50%;">
                                       <span style="font-size: 14px;">3</span>
                                   </a>
                               </td>
                               <td align="center">
                                   <a href="'.$ratingurl.'/4" style="width:25px;display:block;border:2px solid #1e8bc3;line-height:1.8;text-decoration: none;background:#fff;color: #333;border-radius:50%;">
                                       <span style="font-size: 14px;">4</span>
                                   </a>
                               </td>
                               <td align="center">
                                   <a href="'.$ratingurl.'/5" style="width:25px;display:block;border:2px solid #1e8bc3;line-height:1.8;text-decoration: none;background:#fff;color: #333;border-radius:50%;">
                                       <span style="font-size: 14px;">5</span>
                                   </a>
                               </td>
                               <td align="center">
                                   <a href="'.$ratingurl.'/6" style="width:25px;display:block;border:2px solid #1e8bc3;line-height:1.8;text-decoration: none;background:#fff;color: #333;border-radius:50%;">
                                       <span style="font-size: 14px;">6</span>
                                   </a>
                               </td>
                               <td align="center">
                                   <a href="'.$ratingurl.'/7" style="width:25px;display:block;border:2px solid #1e8bc3;line-height:1.8;text-decoration: none;background:#fff;color: #333;border-radius:50%;">
                                       <span style="font-size: 14px;">7</span>
                                   </a>
                               </td>
                               <td align="center">
                                   <a href="'.$ratingurl.'/8" style="width:25px;display:block;border:2px solid #1e8bc3;line-height:1.8;text-decoration: none;background:#fff;color: #333;border-radius:50%;">
                                       <span style="font-size: 14px;">8</span>
                                   </a>
                               </td>
                               <td align="center">
                                   <a href="'.$ratingurl.'/9" style="width:25px;display:block;border:2px solid #1e8bc3;line-height:1.8;text-decoration: none;background:#fff;color: #333;border-radius:50%;">
                                       <span style="font-size: 14px;">9</span>
                                   </a></td>
                               <td align="center">
                                   <a href="'.$ratingurl.'/10" style="width:25px;display:block;border:2px solid #1e8bc3;line-height:1.8;text-decoration: none;background:#fff;color: #333;border-radius:50%;">
                                       <span style="font-size: 14px;">10</span>
                                   </a></td></tr>
                           </tbody></table></div></div></td></tr></tbody></table></td><td width="10"></td></tr>';
        }
        
       return $content;
       
    }


}