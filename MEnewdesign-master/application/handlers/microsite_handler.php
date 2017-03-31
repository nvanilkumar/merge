<?php

/**
 * Reports related business logic will be defined in this class
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @param		
 * @addTicket		
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0
 * @Created     03-08-2015
 * @Last Modified 03-08-2015
 */
require_once(APPPATH . 'handlers/handler.php');
require_once(APPPATH . 'handlers/event_handler.php');
require_once(APPPATH . 'handlers/eventsignup_handler.php');
require_once(APPPATH . 'handlers/user_handler.php');
require_once(APPPATH . 'handlers/ticket_handler.php');
require_once(APPPATH . 'handlers/common_handler.php');
require_once (APPPATH . 'handlers/eventsignupticketdetail_handler.php');
require_once(APPPATH . 'handlers/email_handler.php');
require_once (APPPATH . 'handlers/verificationtoken_handler.php');
require_once (APPPATH . 'handlers/state_handler.php');
require_once (APPPATH . 'handlers/city_handler.php');
require_once(APPPATH . 'handlers/confirmation_handler.php');
require_once(APPPATH . 'handlers/orderlog_handler.php');
require_once(APPPATH . 'handlers/partialeventsignup_handler.php');
require_once(APPPATH . 'handlers/messagetemplate_handler.php');

class Microsite_handler extends Handler {

    var $ci;
    var $eventSignupHandler;
    var $eventHandler;
    var $userHandler;
    var $ticketHandler;
    var $commonHandler;
    var $eventsignupticketdetailHandler;
    var $verificationtokenHandler;
    var $emailHandler;
    var $state;
    var $city;
    var $confirmationHandler;
    var $orderlogHandler;
    var $partialeventsignupHandler;
    
    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->userHandler = new User_handler();
        $this->eventHandler = new Event_handler();
        $this->ticketHandler = new Ticket_handler();
        $this->commonHandler = new Common_handler();
        $this->eventsignupHandler = new Eventsignup_handler();
        $this->eventsignupticketdetailHandler = new Eventsignup_Ticketdetail_handler();
        $this->verificationtokenHandler = new Verificationtoken_handler();
        $this->state = new State_handler();
        $this->city = new City_handler();
        $this->confirmationHandler = new Confirmation_handler();
        $this->partialeventsignupHandler = new Partialeventsignup_handler();
    }
    
    public function generateMailLinks($eventId,$tickets){
       $validTrancations=array();
        $eventSignupDetails=$this->eventsignupHandler->getSuccessfullTransactionsByEventId($eventId);
        if($eventSignupDetails['status'] && $eventSignupDetails['response']['total']>0){
            $signUpIds=$signUpIdsWithUserId=array();
            foreach ($eventSignupDetails['response']['eventsignupData'] as $key => $value) {
                $signUpIds[]=$value['id'];
                $signUpIdsWithUserId[$value['id']]=$value['userid'];
                $signUpUserIds[]=$value['userid'];
            }
            $signUpUserIdsList['userIdList']=$signUpUserIds;
            $userDetails=$this->userHandler->getUserDetails($signUpUserIdsList);
             if($userDetails['status'] && $userDetails['response']['total']>0){
                 $userDataByUserId=array();
                 foreach ($userDetails['response']['userData'] as $ukey => $uvalue) {
                     $userDataByUserId[$uvalue['id']]=$uvalue;
                 }
                
               $inputs=$actualData=array();
               $inputs['eventsignupids']=$signUpIds;
               $inputs['ticketids']=$tickets;
               $inputs['transactiontype']='All';
               $validTrancations= $this->eventsignupticketdetailHandler->getListByEventsignupIds($inputs);
             
               if($validTrancations['status'] && $validTrancations['response']['total']>0){
                   foreach ($validTrancations['response']['eventSignupTicketDetailList'] as $vtkey => $vtvalue) {
                       $actualData[$vtkey]['ticketid']=$vtvalue['ticketid'];
                       $actualData[$vtkey]['eventsignupid']=$vtvalue['eventsignupid'];
                       $actualData[$vtkey]['userid']=$signUpIdsWithUserId[$vtvalue['eventsignupid']];
                       $actualData[$vtkey]['name']=$userDataByUserId[$actualData[$vtkey]['userid']]['name'];
                       $actualData[$vtkey]['email']=$userDataByUserId[$actualData[$vtkey]['userid']]['email'];
                       
                   }
                   
                    if(!empty($actualData)){
                        $this->ci->load->library('parser');
                        $this->emailHandler = new Email_handler();
                        $templateInputs['type'] = 'vh1supersonic2015';
                        $templateInputs['mode'] = 'email';
                        $this->messagetemplateHandler = new Messagetemplate_handler();
                        $promoterTemplate = $this->messagetemplateHandler->getTemplateDetail($templateInputs);
                        $templateId = $promoterTemplate['response']['templateDetail']['id'];
                        $from = $promoterTemplate['response']['templateDetail']['fromemailid'];
                        $templateMessage = $promoterTemplate['response']['templateDetail']['template'];
                       
                        foreach ($actualData as $adkey => $advalue) {
                        $esid = $code = $link = '';
                        $esid=$advalue['eventsignupid'];
                        $code=md5($esid.time().$advalue['userid']);
                        $link=  site_url()."vh1supersonic2015/?esid=".$esid."&code=".$code;    
                        
                        $to = $advalue['email'];
                        $subject = "MeraEvents - Complete your booking for Vh1 Supersonic GOA event - ".$esid;
                        $data=array();
                        $data['Username'] = ucfirst($advalue['name']);
                        $data['Tlink'] = $link;
                        $message = $this->ci->parser->parse_string($templateMessage, $data, TRUE);
                        
                        $sentmessageInputs['messageid'] = $templateId;
                        $emailResponse = $this->emailHandler->sendEmail($from, $to, $subject, $message, '', '', '', $sentmessageInputs);
                        return $emailResponse;
                        }
                    }
                    
                    
                    
               }
             }
              
               
        }
        
   
    }
    
    
    public function codeVerification($code){
        $token = $code;
           
            $tokenDetails = $this->verificationtokenHandler->details($token);
            return $tokenDetails;
    }
    
    
    public function paynow($data){
     
       $orderData=$ticketDetails=array();
       $orderData['eventid']=$data['eventSignupDetails'][0]['eventid'];
       $orderData['totalamount']=$data['validTrancations'][0]['remainingamount'];
       $orderData['userid']=$data['eventSignupDetails'][0]['userid'];
       $orderData['oldsignupid']=$data['oldsignupid'];
       foreach ($data['ticketdetails'] as $tdkey => $tdvalue) {
           if($tdvalue['id']==$data['ticketsmaparr'][$data['validTrancations'][0]['ticketid']]){
            $ticketDetails[$tdvalue['id']]=1;
           }else{
            $ticketDetails[$tdvalue['id']]=0;   
           }
       }
       $orderData['ticketarray']=$ticketDetails;
       $orderData['eventsignup']=$data['eventSignupDetails'][0]['id'];
       $orderData['calculationDetails']=$data['calculationDetails'];
       // generate order
       
       $orderResult=$this->generateOrder($orderData);
       
       if($orderResult['status']==TRUE){
       $userDetails=array();
       $UserIdsList['userIds']=$orderData['userid'];
       // user details
       $userDetails=$this->userHandler->getUserInfo($UserIdsList);
       if($userDetails['status'] && $userDetails['response']['total']>0){
       $stateData['stateId']=$userDetails['response']['userData']['stateid'];  
       // get state details
       $stateResult=array();
       $stateResult=$this->state->getStateListById($stateData);
       $cityData['cityId']=$userDetails['response']['userData']['cityid'];
       $cityData['countryId']=$userDetails['response']['userData']['countryid'];  
       //get city details
       $cityResult=array();
       $cityResult=$this->city->getCityDetailById($cityData);
       $state=isset($stateResult['response']['stateList'][0]['name'])?$stateResult['response']['stateList'][0]['name']:'';
       $city=isset($cityResult['response']['detail']['name'])?$cityResult['response']['detail']['name']:'';
       $primaryAddress=$userDetails['response']['userData']['name'].'##'.$userDetails['response']['userData']['email'].'##'.$userDetails['response']['userData']['phone'].'##'.$userDetails['response']['userData']['address'].'##'.$state.'##'.$city.'##'.$userDetails['response']['userData']['pincode'];  
       
       //get event details
       $eventData=array();
       $eventData['eventId']=$orderData['eventid'];
       $eventResult=$this->eventHandler->getEventDetails($eventData);
       $eventTitle=  isset($eventResult['response']['details']['title'])?$eventResult['response']['details']['title']:'';
       $paymentData=array();
       $paymentData['TxnAmount']=$orderData['totalamount'];
       $paymentData['EventId']=$orderData['eventid'];
       $paymentData['EventTitle']=$eventTitle;
       $paymentData['primaryAddress']=$primaryAddress;
       $paymentData['eventSignup']=$data['eventSignupDetails'][0]['id'];
       $paymentData['uid']=$orderData['userid'];
       $paymentData['orderId']=$orderResult['response']['order']['id'];
       
        $output['status'] = TRUE;
        $output["response"]["paymentdata"] = $paymentData;
        $output['statusCode'] = STATUS_OK;
        return $output;  
     
       
       }else{
        $output['status'] = FALSE;
        $output["response"]["messages"][] = ERROR_SOMETHING_WENT_WRONG;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;  
       }
       }else{
        $output['status'] = FALSE;
        $output["response"]["messages"][] = ERROR_SOMETHING_WENT_WRONG;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;  
       }
       
       
    }
    
    function paymentResponse($orderid) {
        $userId = $this->ci->customsession->getData('userId');
        $this->orderlogHandler = new Orderlog_handler();
        $orderlogData['orderId']=$orderid;
        $orderlogData['call']=1;
        $orderlogs = $this->orderlogHandler->getOrderlog($orderlogData);
         if ($orderlogs['status'] && $orderlogs['response']['total'] > 0) {
             
            $eventsignupArray['orderId']=$orderid;
            $eventsignupArray['eventsignupId']=$orderlogs['response']['orderLogData']['eventsignup'];
            $eventsignupArray['userId']=$userId;
        
           $purchaseData=  unserialize($orderlogs['response']['orderLogData']['data']);
           
           if(isset($purchaseData['paymentResponse']) && !empty($purchaseData['paymentResponse'])){
              
               if(isset($purchaseData['micrositePaymentStatus'])){
                $output['status'] = FALSE;
                $output["response"]["messages"][] = ERROR_SOMETHING_WENT_WRONG;
                $output['statusCode'] = STATUS_SERVER_ERROR;   
                return $output;
               }
               if(isset($purchaseData['paymentResponse']['mode']) && $purchaseData['paymentResponse']['mode']==''){
                $output['status'] = FALSE;
                $output["response"]["messages"][] = ERROR_SOMETHING_WENT_WRONG;
                $output['statusCode'] = STATUS_SERVER_ERROR;  
                return $output;
               }
               if($purchaseData['paymentResponse']['TransactionID']=='' || $purchaseData['paymentResponse']['ResponseCode'] > 0){
                $output['status'] = FALSE;
                $output["response"]["messages"][] = ERROR_SOMETHING_WENT_WRONG;
                $output['statusCode'] = STATUS_SERVER_ERROR;
                return $output;
               }else{
                $eventSignupDetailsForUpdate=$this->eventsignupHandler->getSuccessfullTransactionsByEventId('',$eventsignupArray['eventsignupId']);   
                $eventSignupDetailsForUpdate['response']['eventsignupData'][0]['eventsignupid']=$eventSignupDetailsForUpdate['response']['eventsignupData'][0]['id'];
                
                // copy old transaction
                $partialSignupAddResponse=$this->partialeventsignupHandler->add($eventSignupDetailsForUpdate['response']['eventsignupData'][0]);
                if($partialSignupAddResponse['status']){
                   
                   // update eventsignup 
                   $paymentResponse= unserialize($eventSignupDetailsForUpdate['response']['eventsignupData'][0]['transactionresponse']);
                   $paymentResponse['Amount']=$purchaseData['paymentResponse']['Amount']+$eventSignupDetailsForUpdate['response']['eventsignupData'][0]['totalamount'];
                   $updatedEventData['paymenttransactionid'] = $purchaseData['paymentResponse']['TransactionID'];
                   $updatedEventData['transactionresponse'] = serialize($paymentResponse);
                   $updatedEventData['totalamount'] = $paymentResponse['Amount'];
                   $updatedEventData['id'] = $eventsignupArray['eventsignupId'];
                  
                   $updateEventSignup=$this->eventsignupHandler->updateEventSignUpForMicrositEvents($updatedEventData); 
                   
                   // update orderlog
                   $purchaseData['paymentResponse']['Amount']=$paymentResponse['Amount'];
                   $purchaseData['calculationDetails']['totalPurchaseAmount']=$paymentResponse['Amount'];
                   $purchaseData['micrositePaymentStatus']='success';
                   $updatedOrderlogData['update']['data']=  serialize($purchaseData);
                   $updatedOrderlogData['condition']['eventSignupId']=$eventsignupArray['eventsignupId'];
                   $updatedOrderlogData['condition']['userId']=$userId;
                   $updatedOrderlogData['condition']['orderId']=$orderid;
                   $updatedOrderlogData['allMandatory']=true;
                   
                   $updateOrderlog=$this->orderlogHandler->orderLogUpdate($updatedOrderlogData);
                   // update eventsignupticketdetail
                   foreach ($purchaseData['ticketarray'] as $key => $value) {
                        if ($value > 0){
                   $updateEventSignupTicketDetailsData=array();         
                   $updateEventSignupTicketDetailsData['eventsignupid']=$eventsignupArray['eventsignupId'];
                   $updateEventSignupTicketDetailsData['ticketid']=$purchaseData['calculationDetails']['ticketsData'][$key]['ticketId'];
                   $updateEventSignupTicketDetailsData['amount']=$purchaseData['calculationDetails']['ticketsData'][$key]['ticketPrice'];
                   $updateEventSignupTicketDetailsData['ticketquantity']=$purchaseData['calculationDetails']['ticketsData'][$key]['selectedQuantity'];
                   $updateEventSignupTicketDetailsData['totalamount']=$purchaseData['calculationDetails']['ticketsData'][$key]['totalAmount'];
                   
                   $updateEventSignupTicketDetailsDataResult=$this->eventsignupticketdetailHandler->update($updateEventSignupTicketDetailsData);
                 
                        } 
                    }
                   
                 // send confirmation mails
                   $result=$this->confirmationHandler->eventSignupDetailData($eventsignupArray); 
                   $updateOrderlog['response']['eventsignup']=$eventsignupArray['eventsignupId'];
                   return $updateOrderlog;
                }else{
                    return $partialSignupAddResponse;
                }
                
               }
     
           }else{
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_SOMETHING_WENT_WRONG;
            $output['statusCode'] = STATUS_SERVER_ERROR; 
            return $output;
           }
           
         }else {
                return $orderlogs;
            }
        
     }
    
    public function generateOrder($inputArray) {

        $ticketarray = array();

        foreach ($inputArray['ticketarray'] as $key => $value) {
            if ($value > 0)
                $ticketarray[$key] = $value;
        }
        $datavalue['oldsignupid']=$inputArray['oldsignupid'];
        $datavalue['eventid'] = $inputArray['eventid'];
        $datavalue['totalamount'] = $inputArray['totalamount'];
        $datavalue['promocode'] = '';
        $datavalue['referralcode'] = '';
        $datavalue['widgetredirecturl'] = site_url().'micrositePaymentResponse';
        $datavalue['ticketarray'] = $ticketarray;
        $datavalue['eventid'] = $inputArray['eventid'];
        $datavalue['calculationDetails'] = $inputArray['calculationDetails'];
        
        $datainput['data'] = serialize($datavalue);
        $datainput['userid'] = $inputArray['userid'];
        $datainput['eventsignup'] = $inputArray['eventsignup'];

        $datainput['userip'] = commonHelperGetClientIp();

        //Setting Data for inserting or update
        $datainput['orderid'] = $this->eventHandler->generateOrderId();



        $this->ci->load->model('Orderlog_model');
        $this->ci->Orderlog_model->resetVariable();

        //setting data for inserting
        $this->ci->Orderlog_model->setInsertUpdateData($datainput);

        //executing insert query
        $response = $this->ci->Orderlog_model->insert_data();


        if ($response) {

            $responseData = array('id' => $datainput['orderid']);
            //creating response output 
            //    $output = parent::createResponse(TRUE, SUCCESS_SIGNUP, STATUS_OK, 0, 'userData', $responseData);

            $output = parent::createResponse(TRUE, "Successfully Inserted", STATUS_OK, 0, 'order', $responseData);
            return $output;
        } else {
            //creating response output
            $output = parent::createResponse(FALSE, ERROR_SOMETHING_WENT_WRONG, STATUS_SERVER_ERROR);
            return $output;
        }

        //  }// end of validation else
    }
}
