<?php

/**
 * Confirmation Page related business logic will be defined in this class
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0
 * @Created     11-06-2015
 * @Last Modified 03-07-2015
 * @Last Modified by Raviteja
 */
require_once (APPPATH . 'handlers/handler.php');
//require_once(APPPATH . 'handlers/profile_handler.php');

class Confirmation_handler extends Handler {

    var $ci;
    var $eventsignupHandler;
    var $emailHandler;
    var $sentmessageHandler;
   // var $profileHandler;
    var $eventHandler;
    var $viralTicketSaleHandler;
    var $userHandler;
    var $alertHandler;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
    }
    
    public function getConfirmationeventsignupDetailData($eventsignupArray){
    	$this->ci->form_validation->reset_form_rules();
    	$this->ci->form_validation->pass_array($eventsignupArray);
    	$this->ci->form_validation->set_rules('orderId', 'order id', 'trim|xss_clean|alpha_numeric');
    	$this->ci->form_validation->set_rules('eventsignupId', 'eventsignup id', 'is_natural_no_zero');
    	$this->ci->form_validation->set_rules('userId', 'user id', 'is_natural_no_zero');
    	if ($this->ci->form_validation->run() == FALSE) {
    		$response = $this->ci->form_validation->get_errors();
    		$output['status'] = FALSE;
    		$output['response']['messages'] = $response['message'];
    		$output['statusCode'] = STATUS_BAD_REQUEST;
    		return $output;
    	}
    	require_once(APPPATH . 'handlers/eventsignup_handler.php');
    	$this->eventsignupHandler = new Eventsignup_handler();
    	if($eventsignupArray['updateData'] == 1){
    		$responseData = $this->eventsignupHandler->UpdateEventsignupBooking($eventsignupArray);
    		$eventsignupArray['eventsignupId'] = $responseData['eventsignupId'];
    		$eventsignupArray['viralrefferalCode'] = $responseData['viralrefferalCode'];
    		unset($eventsignupArray['updateData']);
    	}
    	$eventsignupDetails = $this->eventsignupHandler->getEventsignupDetaildata($eventsignupArray);
    	return $eventsignupDetails;
    	
    }
// Booking flow and Offline Booking Flow
    public function eventSignupDetailData($eventsignupArray) {
    	require_once(APPPATH . 'handlers/sentmessage_handler.php');
    	require_once(APPPATH . 'handlers/email_handler.php');
    	$this->emailHandler = new Email_handler();
    	$this->sentmessageHandler = new Sentmessage_handler();
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($eventsignupArray);
        $this->ci->form_validation->set_rules('orderId', 'order id', 'trim|xss_clean|alpha_numeric');
        $this->ci->form_validation->set_rules('eventsignupId', 'eventsignup id', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('userId', 'user id', 'is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventsignupData=1;
         $eventsignupDetails = $this->getConfirmationeventsignupDetailData($eventsignupArray);
        if ($eventsignupDetails['status'] && $eventsignupDetails['response']['total'] > 0) {
            $data = $eventsignupDetails['response']['eventSignupDetailData'];
            $data['delegatepassTemplateData'] = $this->eventsignupHandler->getdelegatepassHtml($data);
            $eventsignup['eventsignupid'] = $data['eventsignupDetails']['id'];
            // Checking Whether Email is already sent Or not
            $messages = $this->sentmessageHandler->getEventsignupSentMessages($eventsignup);
        } else {
            return $eventsignupDetails;
        }
        if ($messages['status'] && isset($messages['response']['sentmessages'])) {
            $sentmessages = $messages['response']['sentmessages'];
        } else {
            $sentmessages = array();
        }
        $messageIds = array();
        foreach ($sentmessages as $key => $v) {
            $messageIds[] = $v['type'];
        }
        if (!in_array("orgEventSignupSuccess", $messageIds)) {
        	require_once(APPPATH . 'handlers/alert_handler.php');
        	require_once(APPPATH . 'handlers/event_handler.php');
        	$this->alertHandler = new Alert_handler();
        	$this->eventHandler = new Event_handler();
        	$orgId = $data['organizerDetails']['id'];
        	$inputArray['userId']=$orgId;
        	$alertDetails = $this->alertHandler->getAlerts($inputArray);
        	// Checking setting for Organizer Email
        	$data['organizerEmail']=array();
        	$data['organizerEmail']['emailsend'] = false;
        	$data['organizerEmail']['cc'] = '';
        	if($alertDetails['status'] == TRUE && $alertDetails['response']['total'] > 0){
        		//$data['organizerEmail']['emailsend'] = true;
        		 $ticketregistrationValue=  $alertDetails['response']['alertDetails']['ticketregistration']['status'];
        		if ($ticketregistrationValue == 1) {
                                $data['organizerEmail']['emailsend'] = true;
        			$inputArray['eventId']=$eventsignupDetails['response']['eventSignupDetailData']['eventsignupDetails']['eventid'];
        			$contactDetails = $this->eventHandler->getcontactInfo($inputArray);
        			$extratxnreportingemails = $contactDetails['response']['contactDetails']['extratxnreportingemails'];
        			/* $data['cc']=$extratxnreportingemails;
        			$this->emailHandler->sendTransactionSuccessEmailToOrganizer($data); */
        			$data['organizerEmail']['cc'] = $extratxnreportingemails;
        		}
        	}
        }
        if (!in_array("delegateEventRegistration", $messageIds)) {
            // offline booking mails
            // if (isset($eventsignupArray['deltype']) && $eventsignupArray['deltype'] == 'offlinedelegate') {
           //     $data['deltype'] = 'offlinedelegate';
            //    $data['email'] = $eventsignupArray['email'];
            // $this->emailHandler->sendTransactionSuccessEmailToDelegate($data);
//
         //   }
            // offline booking mails
            if (isset($eventsignupArray['orgtype']) && $eventsignupArray['orgtype'] == 'offlineOrgnizer') {
                $data['orgtype'] = 'offlineorgnizer';
                $data['userEmail'] = $this->ci->customsession->getData('userEmail');
                $data['orgemail'] = $data['userEmail'];
                $data['email'] = $eventsignupArray['email'];
                $data['offlineName']=$eventsignupArray['name'];
                $data['organizerEmail']['emailsend'] = false;
                $this->emailHandler->sendTransactionSuccessEmailToDelegate($data);

            } else {
                    $data['customemail']=$eventsignupDetails['response']['eventSignupDetailData']['eventSettings']['customemail'];
                    $this->emailHandler->sendTransactionSuccessEmailToDelegate($data);
                 }
              }
        if(strlen($data['eventsignupDetails']['referralcode'])==10){
        	if (!in_array("viralShare", $messageIds)) {
        		require_once(APPPATH . 'handlers/viralticketsale_handler.php');
        		$this->viralTicketSaleHandler = new Viralticketsale_handler();
        		$reffererData['userEmail'] = $data['userDetail']['email'];
        		$reffererData['name'] = $data['userDetail']['name'];
        		$reffererData['referralcodeDetails'] = $data['eventticketviralsetting'][0];
        		$reffererData['eventUrl'] = $data['eventData']['eventUrl'];
        		$reffererData['eventTitle'] = $data['eventData']['title'];
        		$reffererData['referralcode'] = $data['eventsignupDetails']['referralcode'];
        		$reffererData['currencyCode'] = $data['eventsignupDetails']['currencyCode'];
        		$this->emailHandler->EventsignupAffiliateShareEmail($reffererData);
        		//For sending referral bonus congrats mail
        		$reffererData['eventSignupId'] = $data['eventsignupDetails']['id'];
        		$viralInputs['referralcode']=$data['eventsignupDetails']['viralReferralCode'];
        		if(strlen($data['eventsignupDetails']['viralReferralCode'])>0){
	        		$viralTSData=$this->viralTicketSaleHandler->getViralticketSaleData($viralInputs);
	        		$userInputs['userIdList']=array($viralTSData['response']['viralTicket']['referreruserid']);
	        		require_once(APPPATH . 'handlers/user_handler.php');
	        		$this->userHandler=new User_handler();
	        		$userData=$this->userHandler->getUserDetails($userInputs);
	        		$reffererData['referrerEmail']=$userData['response']['userData'][0]['email'];
	        		$reffererData['referrerName']=$userData['response']['userData'][0]['name'];
	        		$getUserpoints['userId']= $viralTSData['response']['viralTicket']['referreruserid'];
	        		$getUserpoints['eventsignupId']= $data['eventsignupDetails']['id'];
	        		require_once(APPPATH . 'handlers/userpoint_handler.php');
                                $userPointHandler = new Userpoint_handler();
                                $userpoints = $userPointHandler->getAffiliateUserpoints($getUserpoints);
	        		if($userpoints['status'] && $userpoints['response']['total']>0){
	        		$points = $userpoints['response']['userPoints'][0]['points'];
	        		$reffererData['userPoints']= $points;
	        		$this->emailHandler->referralBonusCongratsEmail($reffererData);
	        		}
	        	}
        	}
        }
        if (!in_array("smOrgEvtSUpSucc", $messageIds)) {
        	$smsData = array();
        	$smsData['eventtitle'] = $data['eventData']['title'];
        	$smsData['eventsignupid'] = $data['eventsignupDetails']['id'];
        	$smsData['mobile'] = $data['userDetail']['mobile'];
                   $this->emailHandler->sendSuccessEventsignupsmstoDelegate($smsData);
                }
        if ($data['eventsignupDetails']['bookingtype']=='global' && !empty($data['eventsignupDetails']['promotercode']) && !in_array("globalPromoterCongratz", $messageIds)) {
            require_once(APPPATH . 'handlers/promoter_handler.php');
            $this->promoterHandler = new Promoter_handler();
            $promoterInput['promoterCode'] = $data['eventsignupDetails']['promotercode'];
            $promoterInput['type'] = 'global';
            $promoterStatus = $this->promoterHandler->checkPromoterCode($promoterInput);
            $userId=0;
            if ($promoterStatus['status'] && $promoterStatus['response']['total'] > 0) {
                $userId=$promoterStatus['response']['promoterList'][0]['userid'];
            }
            if($userId>0){
                require_once(APPPATH . 'handlers/user_handler.php');
                $userHandler=new User_handler();
                $inputUser['ownerId']=$userId;
                $userRes=$userHandler->getUserData($inputUser);
            }
            if(isset($userRes) && $userRes['status'] && $userRes['response']['total']>0){
                $globalEmail=$userRes['response']['userData']['email'];
            }
            if(isset($globalEmail)){
                $userPointInsert=array();
                $userPointInsert['points'] = (($data['eventsignupDetails']['totalamount']*GLOBAL_AFFILIATE_COMMISSION)/100);
                if($data['eventsignupDetails']['currencyCode']!='INR'){
                    $get = url_get_contents("https://www.google.com/finance/converter?a=1&from=".$data['eventsignupDetails']['currencyCode']."&to=INR");
                    $get = explode("<span class=bld>", $get);
                    $get = explode("</span>", $get[1]);
                    $currencyValue = explode(" ", $get[0]);
                    $currencyAmount = ($currencyValue[0]-2);
                    $userPointInsert['points']=($userPointInsert['points']*$currencyAmount);
//                    if($data['eventsignupDetails']['convertedamount']>0){
//                        $userPointInsert['points']=($data['eventsignupDetails']['convertedamount']*$currencyAmount);
//                    }
                }
                $userPointInsert['points']=round($userPointInsert['points']);
    //                echo "<pre>";
                //print_r($userPointInsert);
                if($userPointInsert['points']>0){
                    $userPointInsert['userId'] = $userId;
                    $userPointInsert['type'] = 'affiliate';
                    $userPointInsert['eventSignupId'] = $data['eventsignupDetails']['id'];
                    require_once(APPPATH . 'handlers/userpoint_handler.php');
                    $userPointHandler = new Userpoint_handler();
                    $userPointReturn = $userPointHandler->addUserPoint($userPointInsert); // Need to send mail ****
                   // print_r($userPointReturn);exit;
                    $globalAffUserPointId = $userPointReturn['response']['userPointId'];
                }
                //print_r($globalAffUserPointId);
                if(isset($globalAffUserPointId) && $globalAffUserPointId>0){
                    $this->ci->load->library('parser');
                    //$this->emailHandler = new Email_handler();
                    //Sending promoter invitation mail
                    $templateInputs['type'] = TYPE_GLOBAL_PROMOTER_CONGRATZ;
                    $templateInputs['mode'] = 'email';      
                    $this->messagetemplateHandler = new Messagetemplate_handler();
                    $promoterTemplate = $this->messagetemplateHandler->getTemplateDetail($templateInputs);
                    $templateId = $promoterTemplate['response']['templateDetail']['id'];
                    $from = $promoterTemplate['response']['templateDetail']['fromemailid'];
                    $to = $globalEmail;
                    $templateMessage = $promoterTemplate['response']['templateDetail']['template'];
                    $subject = SUBJECT_GLOBAL_PROMOTER_INVITE;
                    //Data for email template (by using parser)
                    $data['AFFILIATE_USERNAME'] = ucfirst($userRes['response']['userData']['name']);
                    $data['GLOBAL_AFFILIATE_COMMISSION'] = GLOBAL_AFFILIATE_COMMISSION;
                    $data['EVENT_TITLE'] = $data['eventData']['title'];
                    $data['EVENT_URL'] = $data['eventData']['eventUrl']."?acode=".$data['eventsignupDetails']['promotercode'];
                    $data['GLOBAL_EVENT_COMMISSION']='INR '.$userPointInsert['points'];
                    $data['CLOUD_URL']=$this->ci->config->item('images_cloud_path');
                    $data['DASHBOARD_URL']=commonHelperGetPageUrl('dashboard-global-affliate-bonus');
                    $data['meraeventLogoPath'] = $this->ci->config->item('images_static_path') . 'me-logo.png';
                    $message = $this->ci->parser->parse_string($templateMessage, $data, TRUE);
                    //print_r($message);exit;
                    $sentmessageInputs['messageid'] = $templateId;
                    $emailResponse = $this->emailHandler->sendEmail($from, $to, $subject, $message, '', '', '', $sentmessageInputs);
                }
            }
         }
        unset($data['delegatepassTemplateData']);
       // return $eventsignupDetails;
       return true;
    }

    public function printPass($eventsignupArray) {
        $eventsignupData = $this->getConfirmationeventsignupDetailData($eventsignupArray);
        if($eventsignupData['status'] && $eventsignupData['response']['total']>0){
        	$data = $eventsignupData['response']['eventSignupDetailData'];
        	$pdfhtml = $this->eventsignupHandler->getdelegatepassHtml($data);
        	$this->ci->load->library('pdf');
        	$pdf = $this->ci->pdf->load();
        	$pdf->AddPage('P');
        	$pdf->WriteHTML($pdfhtml);  // write the HTML into the PDF
        	$pdf->Output('delegatepass.pdf', 'I');
        	exit;
        }
        return $eventsignupData;
    
    }

    public function resendTransactionsuccessEmail($eventsignupArray) {
    	require_once(APPPATH . 'handlers/email_handler.php');
    	$this->emailHandler = new Email_handler();
        $eventsignupData = $this->getConfirmationeventsignupDetailData($eventsignupArray);
        $data = $eventsignupData['response']['eventSignupDetailData'];
        $data['delegatepassTemplateData'] = $this->eventsignupHandler->getdelegatepassHtml($data);
        $data['customuserEmail'] = isset($eventsignupArray['userEmail'])?$eventsignupArray['userEmail']:'';
        $response = $this->emailHandler->sendTransactionSuccessEmailToDelegate($data);
        return $response;
    }
    
    public function emailPrintpass($eventsignupArray) {
       // print_r($eventsignupArray);
    	require_once(APPPATH . 'handlers/email_handler.php');
    	$this->emailHandler = new Email_handler();
        $eventsignupData = $this->getConfirmationeventsignupDetailData($eventsignupArray);
        $data = $eventsignupData['response']['eventSignupDetailData'];
        $delegatepassTemplateData = $this->eventsignupHandler->getdelegatepassHtml($data);
        $data['customuserEmail'] = isset($eventsignupArray['userEmail'])?$eventsignupArray['userEmail']:'';
        $displayName=$data['userDetail']['name'];
        $eventTitle=$data['eventData']['title'];
        $mailto=$resuser[0]['Email'];
        $name=NULL;
        $subject = 'Customer Pass for the event '.$eventTitle;
        if($eventsignupArray['userEmail']===$data['userDetail']['email']){ 
            $name=$displayName;
            
        }
                        $message ='<table width="70%" border="0" cellpadding="1" cellspacing="2">
                                                 <tr>
                                                     <td colspan="2">Hello '.$name.',</td>
                                                 </tr>';
                         if(strlen($name)>0){
                                 $message.='         <tr>
                                                         <td colspan="2"> Thank you for registering on '.$eventTitle.'</td>
                                                 </tr>';
                         }
                        $message.='<tr>
                                                <td colspan="2">Please find your Pass as an attachment.</td>
                                        </tr>
                                        <tr>
                                                <td colspan="2">&nbsp;</td>
                                        </tr>
                                        <tr>
                                                <td colspan="2">Regards,</td>
                                        </tr>
                                        <tr>
                                                <td colspan="2"></td>
                                        </tr>
                                        <tr>
                                                <td colspan="2"></td>
                                        </tr>
                                        <tr>
                                                <td colspan="2">MeraEvents.com</td>
                                        </tr>
                                </table>';
    		$to = $eventsignupArray['userEmail'];
    		$from = 'MeraEvents<admin@meraevents.com>';
    		$bcc = '';
    		$this->ci->load->library('pdf');
    		$mpdf = $this->ci->pdf->load();
    		$mpdf->WriteHTML($delegatepassTemplateData);
    		$content = $mpdf->Output('', 'S');
    		$cc = '';
    		$filename = "delegatepass.pdf";
                $sentmessageInputs['notInsertIntoSentMessage'] = true;               
    		$email = $this->emailHandler->EmailSend($to, $cc, $bcc, $from, $subject,$message, $content, $filename,'',$sentmessageInputs);
                return $email;                                              
    }

    // Function to send Sms to Delegate
    public function sendSuccessEventsignupsmstoDelegate($smsData){
        $eventTitle = $smsData['eventtitle'];
        $eventSignupid = $smsData['eventsignupid'];
        $attendeemobile = $smsData['mobile'];
        $inputArray['type'] = 'smOrgEvtSUpSucc';
        $inputArray['mode'] = 'sms';
        require_once(APPPATH . 'handlers/messagetemplate_handler.php');
        require_once(APPPATH . 'handlers/email_handler.php');
        $this->emailHandler = new Email_handler();
        $this->messagetemplateHandler = new Messagetemplate_handler();
        $delegatepassEmailtemplate = $this->messagetemplateHandler->getTemplateDetail($inputArray);
        $smstemplatemsg = $delegatepassEmailtemplate['response']['templateDetail']['template'];
        $smstemplatemsg = str_replace('EventTitle', $eventTitle, $smstemplatemsg);
        $smstemplatemsg = str_replace('RandomInvoiceNo', $eventSignupid, $smstemplatemsg);
        $sentMessageArray['eventsignupid'] = $eventSignupid;
        $sentMessageArray['messageid'] = $delegatepassEmailtemplate['response']['templateDetail']['id'];        
        $result = $this->emailHandler->sendSms($attendeemobile, $smstemplatemsg,$sentMessageArray);
        return $result;
    }
    // Checking the Eventsignup Id is Valid or Not
    public function checkEventsignup($inputArray){
    	$this->ci->load->model('Eventsignup_model');
    	$this->ci->form_validation->pass_array($inputArray);
    	$this->ci->form_validation->set_rules('eventsignupId', 'Registration Number', 'is_natural_no_zero|required_strict');
    	$this->ci->form_validation->set_rules('userId', 'User Id', 'is_natural_no_zero|required_strict');
    	if (!empty($inputArray) && $this->ci->form_validation->run() == FALSE) {
    		$errorMsg = $this->ci->form_validation->get_errors();
    		$output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
    		return $output;
    	}
        $this->ci->Eventsignup_model->resetVariable();
    	$selectInput['userid'] = $this->ci->Eventsignup_model->userid;
    	$this->ci->Eventsignup_model->setSelect($selectInput);
    	$where[$this->ci->Eventsignup_model->id] = $inputArray['eventsignupId'];
    	$where[$this->ci->Eventsignup_model->deleted] = 0;
    	$where[$this->ci->Eventsignup_model->userid] = $inputArray['userId'];
    	$this->ci->Eventsignup_model->setWhere($where);
    	$this->ci->Eventsignup_model->setRecords(1);
    	$eventSignupUserId = $this->ci->Eventsignup_model->get();
    	if (count($eventSignupUserId)>0) {
    		$output['status'] = TRUE;
    		$output['response']['messages'][] = '';
    		$output['response']['total']=count($eventSignupUserId);
    		$output['statusCode'] = STATUS_OK;
    		return $output;
    	}
    	$output['status'] = FALSE;
    	$output['response']['messages'][] = ERROR_NO_DATA;
    	$output['response']['total']=0;
    	$output['statusCode'] = STATUS_NO_DATA;
    	return $output;
    
    }
    
    /*
     * To validate event specificvalidation after booking of the tickets
     */
    public function eventPmiValidations($inputArray){
        $membershipArray=array();
        $membershipArray['eventId']=$inputArray['response']['eventSignupDetailData']['eventData']['id'];
        $attendeeList=$inputArray['response']['eventSignupDetailData']['attendees']['otherAttendee']; 
        foreach($attendeeList as $key => $value){
            $attendeeArray["attendeeids"][]=$key;
            
        }
        require_once(APPPATH . 'handlers/configure_handler.php');
        //getting the event custom field information
        $configureHandler = new Configure_handler();
        $eventCustomFields= $configureHandler->getCustomFields($membershipArray);
        
        //extract the all custom filed ids related to "membership id" name
        $customFieldIdList=array();
        if($eventCustomFields['response']['total'] > 0){
            foreach($eventCustomFields['response']['customFields'] as $key => $value){
                if($value['fieldname']=="Member ID"){
                    $customFieldIdList[]=$value['id'];
                }
            }
        }
        //get the attendee details
        require_once(APPPATH . 'handlers/attendeedetail_handler.php');
        $attendeedetailHandler=new Attendeedetail_handler();
        $attendeeInfo=$attendeedetailHandler->getEventsignupattendees($attendeeArray);
        if($attendeeInfo['response']['total'] > 0){
            foreach($attendeeInfo['response']['attendeeDetails'] as $key => $value){
                if(in_array($value['customfieldid'], $customFieldIdList)){
                    $membershipArray['membershipList'][]=$value['value'];
                }
            }
            
        }
       //update the status of the membership values
        if(count($membershipArray['membershipList']) > 0 ){
        	require_once(APPPATH . 'handlers/eventcustomvalidator_handler.php');
            $eventcustomvalidatorHandler = new Eventcustomvalidator_handler();
            $response = $eventcustomvalidatorHandler->updateMemberShipDetails($membershipArray);
        }
        
        
    }
	
	/*tedx 2016 email validations*/
	public function eventTedX2016EmailValidation($inputArray){
        $tedXArr=array();
        $tedXArr['eventId']=$inputArray['response']['eventSignupDetailData']['eventData']['id'];
        $attendeeList=$inputArray['response']['eventSignupDetailData']['attendees']['otherAttendee']; 
        foreach($attendeeList as $key => $value){
            $attendeeArray["attendeeids"][]=$key;
        }
        require_once(APPPATH . 'handlers/configure_handler.php');
        //getting the event custom field information
        $configureHandler = new Configure_handler();
        $eventCustomFields= $configureHandler->getCustomFields($tedXArr);
        
        //extract the all custom filed ids related to "membership id" name
        $customFieldIdList=array();
        if($eventCustomFields['response']['total'] > 0){
            foreach($eventCustomFields['response']['customFields'] as $key => $value){
                if($value['fieldname']=="Email Id"){
                    $customFieldIdList[]=$value['id'];
                }
            }
        }
        //get the attendee details
        require_once(APPPATH . 'handlers/attendeedetail_handler.php');
        $attendeedetailHandler=new Attendeedetail_handler();
        $attendeeInfo=$attendeedetailHandler->getEventsignupattendees($attendeeArray);
        if($attendeeInfo['response']['total'] > 0){
            foreach($attendeeInfo['response']['attendeeDetails'] as $key => $value){
                if(in_array($value['customfieldid'], $customFieldIdList)){
                    $tedXArr['emailList'][]=$value['value'];
                }
            }
            
        }
       //update the status of the membership values
        if(count($tedXArr['emailList']) > 0 ){
        	require_once(APPPATH . 'handlers/eventcustomvalidator_handler.php');
            $eventcustomvalidatorHandler = new Eventcustomvalidator_handler();
            $response = $eventcustomvalidatorHandler->updateTedX2016Emails($tedXArr);
        }
        
        
    }
    
    /**
     * To checking the event related validation are enabled, 
     * If it is enabled calling the specific funion
     * @param type $inputArray
     */
    public function eventSpecificValidations($inputArray){
		
        $customvalidationflag="";
                
        $customvalidationflag=$inputArray['response']['eventSignupDetailData']['eventData']['eventDetails']['customvalidationflag'];
        if($customvalidationflag==1){
            $customvalidationfunction=$inputArray['response']['eventSignupDetailData']['eventData']['eventDetails']['customvalidationfunction'];
            switch ($customvalidationfunction) {
                case "eventPmiValidations":
                
                    $this->eventPmiValidations($inputArray);
                    break;
				case "eventTedX2016EmailValidation":
                
                    $this->eventTedX2016EmailValidation($inputArray);
                    break;

                default:
                    echo "Your favorite color is neither red, blue, nor green!";
            }
        }
    }
    

}
