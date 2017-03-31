<?php

/**
 * Promoter related business logic will be defined in this class
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     11-06-2015
 * @Last Modified 11-06-2015
 */
require_once (APPPATH . 'handlers/handler.php');
require_once (APPPATH . 'handlers/event_handler.php');
require_once (APPPATH . 'handlers/user_handler.php');
require_once(APPPATH . 'handlers/eventsignup_handler.php');
require_once(APPPATH . 'handlers/ticket_handler.php');
require_once(APPPATH . 'handlers/reports_handler.php');
require_once (APPPATH . 'handlers/currency_handler.php');
require_once (APPPATH . 'handlers/offlinepromoterticketmapping_handler.php');
require_once (APPPATH . 'handlers/offlinepromoterdiscounts_handler.php');
require_once(APPPATH . 'handlers/messagetemplate_handler.php');

class Promoter_handler extends Handler {

    var $ci;
    var $currencyHanlder;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Promoter_model');
        $this->eventHandler = new Event_handler();
        $this->userHandler = new User_handler();
        $this->ticketHandler = new Ticket_handler();
        $this->offlinepromoterticketmappingHandler = new Offlinepromoterticketmapping_handler();
        $this->offlinepromoterdiscountsHandler = new Offlinepromoterdiscounts_handler();
    }

    public function insertPromoter($inputArray, $eventDetails) {
        $this->ci->form_validation->pass_array($inputArray);
        //Checking validation using Group Validation (signup)
        if ($this->ci->form_validation->run('promoter') === FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
            return $output;
        }
        $this->ci->Promoter_model->resetVariable();
        //Checking promoter code is already there or not
        $select['id'] = $this->ci->Promoter_model->id;
        $this->ci->Promoter_model->setSelect($select);
        $where[$this->ci->Promoter_model->eventid] = $inputArray['eventId'];
        $where[$this->ci->Promoter_model->type] = $inputArray['type'];
        $where[$this->ci->Promoter_model->code] = $inputArray['code'];
        $this->ci->Promoter_model->setWhere($where);
        $result = $this->ci->Promoter_model->get();
        if ($result) {
            $output = parent::createResponse(FALSE, ERROR_DUPLICATE_PROMOTER, STATUS_BAD_REQUEST);
            return $output;
        }

        $userDetails = $this->userHandler->getUserData($inputArray);
        if ($userDetails['response']['total'] == 0) {//Insert into user table and get email
            $inputUser['email'] = $inputArray['email'];
            $inputUser['name'] = $inputArray['name'];
            $inputUser['password'] = $password = random_password();
            $addUserResponse = $this->userHandler->add($inputUser);
            if ($addUserResponse['status']) {
                $userId = $addUserResponse['response']['userId'];
            } else {
                return $addUserResponse;
            }
        } else if ($userDetails['response']['total'] == 1) {//Get the user id of that email if user is already existed
            //Checking whether promoter existed with that mail id  for this event
            $password='';
            $userId = $userDetails['response']['userData']['id'];            
            $selectArray['userid'] = $this->ci->Promoter_model->userid;
            $this->ci->Promoter_model->setSelect($selectArray);
            $whereArray[$this->ci->Promoter_model->eventid] = $inputArray['eventId'];
            $whereArray[$this->ci->Promoter_model->userid] = $userId;
            $whereArray[$this->ci->Promoter_model->type] = $inputArray['type'];
            $whereArray[$this->ci->Promoter_model->status] = 1;
            $this->ci->Promoter_model->setWhere($whereArray);
            $result = $this->ci->Promoter_model->get();
            if ($result) {
                $output = parent::createResponse(FALSE, ERROR_DUPLICATE_PROMOTER_EMAIL, STATUS_BAD_REQUEST);
                return $output;
            }           
        } else {
            return $output;
        }
        //Inserting into promoter table
        $createPromoter[$this->ci->Promoter_model->name] = $inputArray['name'];
        $createPromoter[$this->ci->Promoter_model->userid] = $userId;
        $createPromoter[$this->ci->Promoter_model->code] = $inputArray['code'];
		$createPromoter[$this->ci->Promoter_model->orgpromoteurl] = $inputArray['orgpromoteurl'];
        $createPromoter[$this->ci->Promoter_model->eventid] = $inputArray['eventId'];
        $createPromoter[$this->ci->Promoter_model->status] = 1;
        $this->ci->Promoter_model->setInsertUpdateData($createPromoter);
        $output = $this->ci->Promoter_model->insert_data();
        if ($output) {
            $output = parent::createResponse(TRUE, SUCCESS_ADDED_PROMOTER, STATUS_CREATED);  
            $this->sendPromoterEmail($inputArray, $userId, $password, $eventDetails);
            return $output; 
        }else{
            $output = parent::createResponse(FALSE, ERROR_SOMETHING_WENT_WRONG, STATUS_SERVER_ERROR);
            return $output;            
        }

    }
    public function insertGlobalPromoter($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        //Checking validation using Group Validation (signup)
        $this->ci->form_validation->set_rules('code', 'code', 'required_strict');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $code=$inputArray['code'];
        $notAllowedCodes=array('meraevents','meraevent','organizer');
        if(in_array(strtolower($code), $notAllowedCodes)){
            $output = parent::createResponse(FALSE, 'Organizer, meraevents are predefined words, you cant use them', STATUS_BAD_REQUEST);
            return $output;
        }
        $this->ci->Promoter_model->resetVariable();
        //Checking promoter code is already there or not
        $select['id'] = $this->ci->Promoter_model->id;
        $this->ci->Promoter_model->setSelect($select);
       // $where[$this->ci->Promoter_model->eventid] = $inputArray['eventId'];
        $where[$this->ci->Promoter_model->type] = 'global';
        $where[$this->ci->Promoter_model->status] = 1;
        $where[$this->ci->Promoter_model->deleted] = 0;
        $where[$this->ci->Promoter_model->code] = $code;
        $this->ci->Promoter_model->setWhere($where);
        $result = $this->ci->Promoter_model->get();
        if ($result) {
            $output = parent::createResponse(FALSE, ERROR_DUPLICATE_GLOBAL_PROMOTER, STATUS_BAD_REQUEST);
            return $output;
        }

        //Inserting into promoter table
        $createPromoter[$this->ci->Promoter_model->name] = $this->ci->customsession->getData('userName');
        $createPromoter[$this->ci->Promoter_model->userid] = getUserId();
        $createPromoter[$this->ci->Promoter_model->code] = $code;
        $createPromoter[$this->ci->Promoter_model->type] = 'global';
        //$createPromoter[$this->ci->Promoter_model->eventid] = $inputArray['eventId'];
        //$createPromoter[$this->ci->Promoter_model->status] = 1;
        $this->ci->Promoter_model->setInsertUpdateData($createPromoter);
        $output = $this->ci->Promoter_model->insert_data();
        if ($output) {
            $output = parent::createResponse(TRUE, SUCCESS_ADDED_PROMOTER, STATUS_CREATED);  
            $inputEmail['code']=$code;
            $inputEmail['email']=$this->ci->customsession->getData('userEmail');
            $this->sendGlobalPromoterEmail($inputEmail);
            return $output; 
        }else{
            $output = parent::createResponse(FALSE, ERROR_SOMETHING_WENT_WRONG, STATUS_SERVER_ERROR);
            return $output;            
        }
    }
    public function sendGlobalPromoterEmail($inputArray) {
            $this->ci->load->library('parser');
            $this->emailHandler = new Email_handler();
            $templateInputs['type'] = TYPE_GLOBAL_PROMOTER_INVITE;
            $templateInputs['mode'] = 'email';        
            //Sending promoter invitation mail
            $this->messagetemplateHandler = new Messagetemplate_handler();
            $promoterTemplate = $this->messagetemplateHandler->getTemplateDetail($templateInputs);
            $templateId = $promoterTemplate['response']['templateDetail']['id'];
            $from = $promoterTemplate['response']['templateDetail']['fromemailid'];
            $to = $inputArray['email'];
            $templateMessage = $promoterTemplate['response']['templateDetail']['template'];
            $subject = SUBJECT_GLOBAL_PROMOTER_INVITE;
            //Data for email template (by using parser)
            $data['AFFILIATE_USERNAME'] = ucfirst($this->ci->customsession->getData('userName'));
            $data['GLOBAL_AFFILIATE_COMMISSION'] = GLOBAL_AFFILIATE_COMMISSION;
            $data['AFFILIATE_CODE'] = $inputArray['code'];
            $data['CLOUD_URL']=$this->ci->config->item('images_cloud_path');
            $data['meraeventLogoPath'] = $this->ci->config->item('images_static_path') . 'me-logo.png';
            $message = $this->ci->parser->parse_string($templateMessage, $data, TRUE);
            //print_r($message);exit;
            $sentmessageInputs['messageid'] = $templateId;
            $emailResponse = $this->emailHandler->sendEmail($from, $to, $subject, $message, '', '', '', $sentmessageInputs);
            return $emailResponse;
    }
    public function sendPromoterEmail($inputArray, $userId, $password = '', $eventDetails) {
            $this->ci->load->library('parser');
            $this->emailHandler = new Email_handler();
            $templateInputs['type'] = $inputArray['templateType'];
            $templateInputs['mode'] = $inputArray['templateMode'];           
            //Sending promoter invitation mail
            $this->messagetemplateHandler = new Messagetemplate_handler();
            $promoterTemplate = $this->messagetemplateHandler->getTemplateDetail($templateInputs);
            $templateId = $promoterTemplate['response']['templateDetail']['id'];
            //$from = $promoterTemplate['response']['templateDetail']['fromemailid'];
			$from = $this->ci->config->item('me_not_exist_mailid');
			$replyEmail = $promoterTemplate['response']['templateDetail']['fromemailid'];
            $to = $inputArray['email'];
            $templateMessage = $promoterTemplate['response']['templateDetail']['template'];
            $subject = SUBJECT_PROMOTER_INVITE;
            //Data for email template (by using parser)
            $data['passwordLabel'] = "";
            $data['password'] = "";
            $data['address'] = "";
            $data['webinar'] = '';
            $data['userName'] = ucfirst($inputArray['name']);
            $data['promoterCode'] = $inputArray['code'];

            if ($password) {
                $data['passwordLabel'] = "Your password: ";
                $data['password'] = $password;
            }
            $data['date'] = allTimeFormats($eventDetails['response']['details']['startDate'], 7);
            $data['date'].='   -   ';
            $data['date'].=allTimeFormats($eventDetails['response']['details']['endDate'], 7);
            if ($eventDetails['response']['details']['eventMode'] == 1) {
                $data['webinar'] = 'This is a Webinar Event';
            } else if ($eventDetails['response']['details']['eventMode'] == 0) {
                $data['address'] = $eventDetails['response']['details']['location']['venueName'];
                if ($eventDetails['response']['details']['location']['address1']) {
                    $data['address'].=',' . $eventDetails['response']['details']['location']['address1'];
                }
                if ($eventDetails['response']['details']['location']['address2']) {
                    $data['address'].=',' . $eventDetails['response']['details']['location']['address2'];
                }
                if ($eventDetails['response']['details']['location']['cityName']) {
                    $data['address'].=',' . $eventDetails['response']['details']['location']['cityName'];
                }
            }
			
			
			$data['ticketWidgetUrl'] = commonHelperGetPageUrl('ticketWidget','','?eventId=' . $inputArray['eventId']. "&ucode=" . $inputArray['code']);
			
            
			
			
            $data['year'] = allTimeFormats(' ',17);
            $data['loginLink'] = commonHelperGetPageUrl('user-login');
            $data['email'] = $inputArray['email'];
            $data['title'] = $eventDetails['response']['details']['title'];
            $data['eventUrl'] = $eventDetails['response']['details']['eventUrl'] . "?ucode=" . $inputArray['code'];
			
			if(strlen(trim($inputArray['orgpromoteurl'])) > 0){
				$URLSeperater = '?';
				if (strpos($inputArray['orgpromoteurl'],"?") !== false) {
					$URLSeperater = '&';
				}
				$data['eventUrl'] =  $inputArray['orgpromoteurl'] . $URLSeperater.'meprcode=' . $inputArray['code'];
			}
			
            $data['eventMode'] = $eventDetails['response']['details']['eventMode'];
            $data['siteUrl'] = site_url();
            $data['supportLink'] = commonHelperGetPageUrl('contactUs');
            $data['meraeventLogoPath'] = $this->ci->config->item('images_static_path') . 'me-logo.png';
            $message = $this->ci->parser->parse_string($templateMessage, $data, TRUE);
            $sentmessageInputs['messageid'] = $templateId;
            $emailResponse = $this->emailHandler->sendEmail($from, $to, $subject, $message, '', $replyEmail, '', $sentmessageInputs);
            return $emailResponse;
    }

    public function generateCodeForGlobalAff($len='6') {
        $validCode=false;
        while(!$validCode){
            $validCode=true;
            $code=commonHelpergenerateRandomString($len);
            $inputCode['promoterCode']=$code;
            $inputCode['type']='global';
            $checkCodeResponse=  $this->checkPromoterCode($inputCode);
            if($checkCodeResponse['status'] && $checkCodeResponse['response']['total']>0){
                $validCode=false;
            }
        }
        return $code;
    }
    public function getPromoterList($inputArray) {
        $this->ci->Promoter_model->resetVariable();
        $select['id'] = $this->ci->Promoter_model->id;
        $select['name'] = $this->ci->Promoter_model->name;
        $select['userid'] = $this->ci->Promoter_model->userid;
        $select['eventid'] = $this->ci->Promoter_model->eventid;
        $select['status'] = $this->ci->Promoter_model->status;
        $select['code'] = $this->ci->Promoter_model->code;
        $select['cts'] = $this->ci->Promoter_model->cts;
        $this->ci->Promoter_model->setSelect($select);
        $where[$this->ci->Promoter_model->eventid] = $inputArray['eventId'];
        if (isset($inputArray['type'])) {
            $where[$this->ci->Promoter_model->type] = $inputArray['type'];
        }
        if (isset($inputArray['promoterCodeList'])) {
            $where_in[$this->ci->Promoter_model->code] = $inputArray['promoterCodeList'];
        }
        $this->ci->Promoter_model->setWhere($where);
        if (isset($where_in)) {
            $this->ci->Promoter_model->setWhereIns($where_in);
        }
        $promoterDetails = $this->ci->Promoter_model->get();

        if ($promoterDetails) {
            $promoterCodeList = array();
            foreach ($promoterDetails as $key => $value) {
                $promoterCodeList[] = $value['code'];
            }
            //Getting amount and sold out tickets of the promoter code from eventsignup table    
            $inputs['eventId'] = $inputArray['eventId'];
            $inputs['codeList'] = $promoterCodeList;
            $this->eventsignupHandler = new Eventsignup_handler();
            $eventSignupDetails = $this->eventsignupHandler->getEventSignupPromoterData($inputs);
            $eventSignupData = $eventSignupDetails['response']['eventSignupDetails'];
            $EventSignupPromoterCodeList = array();
            foreach ($eventSignupData as $key => $value) {
                $eventSignupPromoterCodeList[] = $value['promotercode'];
            }
            $this->currencyHanlder = new Currency_handler();
            $currencyResponse = $this->currencyHanlder->getCurrencyList();
            if ($currencyResponse['status'] && $currencyResponse['response']['total'] > 0) {
                $indexedCurrencyListById = commonHelperGetIdArray($currencyResponse['response']['currencyList'], 'currencyId');
            } else {
                return $currencyResponse;
            }
            if ($eventSignupDetails['status'] && $eventSignupDetails['response']['total'] > 0) {
                //Writing total amount and quantity in promoterlist 
                foreach ($promoterDetails as $promoterIndex => $promoterRow) {
                    foreach ($eventSignupData as $signupIndex => $signupRow) {
                        if ($promoterRow['code'] == $signupRow['promotercode']) {
                            $promoterDetails[$promoterIndex][$promoterRow['code']]['quantity'] = $signupRow['sumquantity'];
                            $promoterDetails[$promoterIndex][$promoterRow['code']]['totalamount'] = $indexedCurrencyListById[$signupRow['fromcurrencyid']]['currencyCode'] . ' ' . $signupRow['sumtotalamount'];
                        } elseif (!in_array($promoterRow['code'], $eventSignupPromoterCodeList)) {//No transactions are made on this promotercode
                            $promoterDetails[$promoterIndex][$promoterRow['code']]['quantity'] = 0;
                            $promoterDetails[$promoterIndex][$promoterRow['code']]['totalamount'] = 0;
                        }
                    }
                }
                $output = parent::createResponse(TRUE, array(), STATUS_OK, count($promoterDetails), 'promoterList', $promoterDetails);
                return $output;
            } elseif ($eventSignupDetails['status'] && $eventSignupDetails['response']['total'] == 0) {
                //Writing total amount and quantity in promoterlist  
                foreach ($promoterDetails as $promoterIndex => $promoterRow) {
                    $promoterDetails[$promoterIndex][$promoterRow['code']]['quantity'] = 0;
                    $promoterDetails[$promoterIndex][$promoterRow['code']]['totalamount'] = 0;
                }
                $output = parent::createResponse(TRUE, array(), STATUS_OK, count($promoterDetails), 'promoterList', $promoterDetails);
                return $output;
            } else {
                return $eventSignupDetails;
            }
        } elseif (count($promoterDetails) == 0) {
            $output = parent::createResponse(TRUE, ERROR_NO_PROMOTER, STATUS_OK, 0, 'promoterList', array());
            return $output;
        } else {
            $output = parent::createResponse(FALSE, ERROR_SOMETHING_WENT_WRONG, STATUS_SERVER_ERROR);
            return $output;
        }
    }

    public function setPromoterStatus($id) {

        //Checking whether that code/promoter id is available or not
        $this->ci->Promoter_model->resetVariable();
        $select['status'] = $this->ci->Promoter_model->status;
        $select['code'] = $this->ci->Promoter_model->code;
        $this->ci->Promoter_model->setSelect($select);
        $where[$this->ci->Promoter_model->id] = $id;
        $this->ci->Promoter_model->setWhere($where);
        $result = $this->ci->Promoter_model->get();
        unset($where);
        if ($result) {
            $status = $result[0]['status'];

            if ($status == 1) {
                $promoterStatus[$this->ci->Promoter_model->status] = 0;
                $status = 0;
            } else {
                $promoterStatus[$this->ci->Promoter_model->status] = 1;
                $status = 1;
            }

            $where[$this->ci->Promoter_model->id] = $id;
            $this->ci->Promoter_model->setWhere($where);
            $this->ci->Promoter_model->setInsertUpdateData($promoterStatus);
            $result = $this->ci->Promoter_model->update_data();
            if ($result) {
                $output['status'] = TRUE;
                $output['response']['promoterStatus'] = $status;
                $output['response']['messages'][] = SUCCESS_UPDATED_PROMOTER_STATUS;
                $output['statusCode'] = STATUS_OK;
                return $output;
            }
        }
    }

    public function getOfflinePromoterList($inputArray) {
        //Geting prometer details by eventid
        $this->ci->Promoter_model->resetVariable();
        $select['id'] = $this->ci->Promoter_model->id;
        $select['name'] = $this->ci->Promoter_model->name;
        $select['userid'] = $this->ci->Promoter_model->userid;
        $select['eventid'] = $this->ci->Promoter_model->eventid;
        $select['status'] = $this->ci->Promoter_model->status;
        $select['code'] = $this->ci->Promoter_model->code;
        $this->ci->Promoter_model->setSelect($select);
        $where[$this->ci->Promoter_model->eventid] = $inputArray['eventId'];
        $where[$this->ci->Promoter_model->type] = $inputArray['type'];
        $where[$this->ci->Promoter_model->deleted] = 0;
        $this->ci->Promoter_model->setWhere($where);
        $promoterDetails = $this->ci->Promoter_model->get();
        if (count($promoterDetails) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_OFFLINE_PROMOTER;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_NO_DATA;
            return $output;
        }
        $promids = commonHelperGetIdArray($promoterDetails, 'id');
        if ($promoterDetails) {
            $promoterCodeList = array();
            foreach ($promoterDetails as $key => $value) {
                $promoterCodeList[] = $value['code'];
            }
            $promoterIds = array();
            foreach ($promoterDetails as $key => $value) {
                $promoterIds[] = $value['id'];
            }
            //getting data from offlinepromoterticketmapping table
            $inputArray['promoterIds'] = $promoterIds;
            $offlinepromoterticketmappingData = $this->offlinepromoterticketmappingHandler->getOfflinePromoterTicketMappingData($inputArray);
            foreach ($offlinepromoterticketmappingData['response']['offlinepromoterticketmapping'] as $key => $ticket) {
                $promids[$ticket['promoterid']]['ticketId'][] = $ticket['ticketid'];

                $ticketIds[] = $ticket['ticketid'];
            }
            $inputArray['ticketId'] = $ticketIds;
            $ticketName = $this->ticketHandler->getTicketName($inputArray);
            $ticketNames = $ticketName['response']['ticketName'];
            foreach ($ticketNames as $key => $value) {
                $tickets[] = $value['id'];
            }
            $ticketNames = $ticketName['response']['ticketName'];
            $ticket = commonHelperGetIdArray($ticketNames, 'id');
            // getting data eventsignup ids from eventsignup datable
            $this->eventsignupHandler = new Eventsignup_handler();
            $inputArray['paymentType'] = 'Offline';
            $eventSignupIds = $this->eventsignupHandler->getEventSignupId($inputArray);
            $signupIdscodes = array();
            if ($eventSignupIds['response']['total'] > 0) {
                foreach ($eventSignupIds['response']['eventsignupids']['0'] as $key => $value) {
                    $signupIdsInfo[] = $value['id'];
                    $signupIdscodes[$value['id']]['code'] = $value['code'];
                }

                $signupIds = array();
                //  foreach ($promoterDetails as $promoterIndex => $promoterRow) {
                //    foreach ($signupIdsInfo as $signupIndex => $signupRow) {
                //   if ($promoterRow['code'] == $signupRow['code']) {                     
                // getting eventsignup tickets from eventsignupticket detals table from eventsignupid

                $inputArray['signupIds'] = $signupIdsInfo;
                $inputArray['tikcets'] = $tickets;
                $eventSignupTickets = $this->eventsignupHandler->geteventSignupTickets($inputArray);
                $signupTickets = commonHelperGetIdArray($eventSignupTickets['response']['eventsignupticket'], 'ticketid');
                foreach ($eventSignupTickets['response']['eventsignupticket'] as $val) {

                    $signupIdsInfo[$val['eventsignupid']]['ticketid'] = $val['ticketid'];
                    $signupIdsInfo[$val['eventsignupid']]['ticketname'] = $ticket[$val['ticketid']]['name'];
                    $signupIdsInfo[$val['eventsignupid']]['amount'] = $val['amount'];
                    $signupIdsInfo[$val['eventsignupid']]['quanty'] = $val['ticketquantity'];
                    $signupIdsInfo[$val['eventsignupid']]['promoterCode'] = $signupIdscodes[$val['eventsignupid']]['code'];
                }
                $finalData = array();
                foreach ($signupIdsInfo as $key => $val) {
                    if (isset($val['ticketid'])) {
                        $finalData[$val['promoterCode']][$val['ticketid']]['ticketid'] = $val['ticketid'];
                        $finalData[$val['promoterCode']][$val['ticketid']]['ticketname'] = $val['ticketname'];
                        if (!isset($finalData[$val['promoterCode']][$val['ticketid']]['totalamount'])) {
                            $finalData[$val['promoterCode']][$val['ticketid']]['totalamount'] = 0;
                        }
                        if (!isset($finalData[$val['promoterCode']][$val['ticketid']]['quantity'])) {
                            $finalData[$val['promoterCode']][$val['ticketid']]['quantity'] = 0;
                        }
                        $finalData[$val['promoterCode']][$val['ticketid']]['totalamount'] += $val['amount'];
                        $finalData[$val['promoterCode']][$val['ticketid']]['quantity'] += $val['quanty'];
                    }
                }
                foreach ($promids as $promo) {
                    foreach ($promo['ticketId'] as $ticketId) {
                        $promo['ticketData'][] = $finalData[$promo['code']][$ticketId];
                        if (!isset($promo['finalAmount'])) {
                            $promo['finalAmount'] = 0;
                        }
                        if (!isset($promo['totalsold'])) {
                            $promo['totalsold'] = 0;
                        }
                        $promo['totalsold'] += $finalData[$promo['code']][$ticketId]['quantity'];
                        $promo['finalAmount'] += $finalData[$promo['code']][$ticketId]['totalamount'];
                    }
                    $totalData[] = $promo;
                }
            } else {
                $output['status'] = TRUE;
                $output['response']['offlinePromotorList'] = $promoterDetails;
                $output['response']['total'] = count($promoterDetails);
                $output['statusCode'] = STATUS_OK;
                return $output;
            }
            if ($totalData) {
                $output['status'] = TRUE;
                $output['response']['offlinePromotorList'] = $totalData;
                $output['response']['total'] = count($totalData);
                $output['statusCode'] = STATUS_OK;
                return $output;
            } else {
                $output['status'] = FALSE;
                $output['response']['messages'][] = ERROR_NO_DATA;
                $output['response']['total'] = 0;
                $output['statusCode'] = STATUS_NO_DATA;
            }
            // }
            // }
            //   } 
        }
    }


    public function addOfflinePromoter($inputArray, $eventDetails) {
        $this->ci->form_validation->pass_array($inputArray);
        if ($this->ci->form_validation->run('OfflinePromoter') === FALSE) {
            $error_messages = $this->ci->form_validation->get_errors('message');
            $output = parent::createResponse(FALSE, $error_messages, STATUS_BAD_REQUEST);
            return $output;
        }
        $isUserExisted = $this->userHandler->getUserData($inputArray);
        $this->ci->Promoter_model->resetVariable();
        if ($isUserExisted['response']['total'] > 0) {
            $userId = $isUserExisted['response']['userData']['id'];
            $password = '';
            //Checking whether offline promoter existed with that mail id for this event
            $select['userid'] = $this->ci->Promoter_model->userid;
            $this->ci->Promoter_model->setSelect($select);
            $whereOffline[$this->ci->Promoter_model->eventid] = $inputArray['eventId'];
            $whereOffline[$this->ci->Promoter_model->userid] = $userId;
            $whereOffline[$this->ci->Promoter_model->type] = 'offline';
            $whereOffline[$this->ci->Promoter_model->status] = 1;
            $this->ci->Promoter_model->setWhere($whereOffline);
            $result = $this->ci->Promoter_model->get();
            if ($result) {
                $output = parent::createResponse(FALSE, ERROR_DUPLICATE_PROMOTER_EMAIL, STATUS_BAD_REQUEST);
                return $output;
            }
        } else {
            $inputUser['email'] = $inputArray['email'];
            $inputUser['name'] = $inputArray['name'];
            $inputUser['password'] = $password = random_password();
            $addUserResponse = $this->userHandler->add($inputUser);
            if ($addUserResponse['status']) {
                $userId = $addUserResponse['response']['userId'];
            } else {
                return $addUserResponse;
            }
        }
        $inputArray['userId'] = $userId;
        $inputArray['password'] = $password;
        $offlinePromoter['name'] = $inputArray['name'];
        $offlinePromoter['userid'] = $inputArray['userId'];
        $offlinePromoter['eventid'] = $inputArray['eventId'];
        $offlinePromoter['mobile'] = $inputArray['mobile'];
        $offlinePromoter['ticketslimit'] = $inputArray['ticketslimit'];
        $offlinePromoter['type'] = 'offline';
        $offlinePromoter['code'] = 'OFFLINE_' . $userId;
        $this->ci->Promoter_model->setInsertUpdateData($offlinePromoter);
        $promoterId = $this->ci->Promoter_model->insert_data();
        if ($promoterId) {
            $input['promoterid'] = $promoterId;
            $input['eventId'] = $inputArray['eventId'];
            $input['ticketIds'] = $inputArray['ticketIds'];
            $input['email'] = $inputArray['email'];
            $input['userId'] = $inputArray['userId'];
            $input['name'] = $inputArray['name'];
            $input['templateType'] = $inputArray['templateType'];
            $input['templateMode'] = $inputArray['templateMode'];
            $offlinePromoter = $this->offlinepromoterticketmappingHandler->addOfflineTickets($input, $password, $eventDetails);
            
            //add prometer ticket discount related data
            $prometerDiscountInput['promoterId']=$promoterId;
            $prometerDiscountInput['eventId']=$inputArray['eventId'];
            $prometerDiscountInput['ticketDiscount']=$inputArray['ticketDiscount'];
            $prometerDiscountInfo = $this->offlinepromoterdiscountsHandler->insertofflinePromoterDiscounts($prometerDiscountInput);
            
            
            $output['status'] = TRUE;
            $output["response"]["messages"][] = $offlinePromoter['response']['messages']['0'];
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = SOMETHING_WENT_WRONG;
            $output['statusCode'] = STATUS_SERVER_ERROR;
            return $output;
        }
    }

    public function getOfflinePromoterData($inputArray) {
        $this->ci->Promoter_model->resetVariable();
        $selectInput['id'] = $this->ci->Promoter_model->id;
        $selectInput['name'] = $this->ci->Promoter_model->name;
        $selectInput['eventid'] = $this->ci->Promoter_model->eventid;
        $selectInput['userid'] = $this->ci->Promoter_model->userid;
        $selectInput['mobile'] = $this->ci->Promoter_model->mobile;
        $selectInput['ticketslimit'] = $this->ci->Promoter_model->ticketslimit;
        $this->ci->Promoter_model->setSelect($selectInput);
        $where[$this->ci->Promoter_model->id] = $inputArray['id'];
        $where[$this->ci->Promoter_model->status] = 1;
        $this->ci->Promoter_model->setWhere($where);
        $offlinePromoterData = $this->ci->Promoter_model->get();
        if ($offlinePromoterData) {
            $input['userIds'] = $offlinePromoterData['0']['userid'];
        }
        if ($offlinePromoterData) {
            // getting username
            $userDetails = $this->userHandler->getUserInfo($input);
            $email = $userDetails['response']['userData']['email'];
            $uId = $userDetails['response']['userData']['id'];
            // getting tickets data
            $inputArray['ticketStatus'] = 1;
            $tickets = $this->offlinepromoterticketmappingHandler->getOfflineTickets($inputArray);
            $promoterTickets = $tickets['response']['offline'];
            //print_r($promoterTickets);
            $offlineUserIndex = commonHelperGetIdArray($offlinePromoterData);

            foreach ($promoterTickets as $key => $val) {
                $offlineUserIndex[$val['promoterid']]['email'] = $email;
                $offlineUserIndex[$val['promoterid']]['ticketid'][] = $val['ticketid'];
            }
            if ($offlineUserIndex) {
                $output['status'] = TRUE;
                $output["response"]["offlinePromoter"] = $offlineUserIndex;
                $output['statusCode'] = STATUS_OK;
                $output['response']['total'] = count($offlineUserIndex);
                return $output;
            } else {
                $output['status'] = TRUE;
                $output["response"]["messages"][] = ERROR_NO_DATA;
                $output['statusCode'] = STATUS_OK;
                $output['response']['total'] = 0;
                return $output;
            }
        }
    }

    public function updateOfflinePromoterData($inputArray) {
        $this->ci->Promoter_model->resetVariable();
        $offlinePromoterInfo['name'] = $inputArray['name'];
        $offlinePromoterInfo['mobile'] = $inputArray['mobile'];
        $offlinePromoterInfo['ticketslimit'] = $inputArray['ticketslimit'];
        $where['id'] = $inputArray['id'];
        $where['eventid'] = $inputArray['eventId'];
        $this->ci->Promoter_model->setInsertUpdateData($offlinePromoterInfo);
        $this->ci->Promoter_model->setWhere($where);
        $response = $this->ci->Promoter_model->update_data();
        if ($response) {
            $updateTickets = $this->offlinepromoterticketmappingHandler->updateOfflinePromoterTickets($inputArray);
            $updateDiscounts = $this->offlinepromoterdiscountsHandler->updateOfflinePromoterTicketDiscounts($inputArray);
            if ($updateTickets) {
                $output['status'] = TRUE;
                $output["response"]["messages"][] = SUCCESS_UPDATED;
                $output['statusCode'] = STATUS_UPDATED;
                return $output;
            } else {
                $output['status'] = FALSE;
                $output["response"]["messages"][] = ERROR_NO_DATA;
                $output['statusCode'] = STATUS_NO_DATA;
                return $output;
            }
        } else {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = SOMETHING_WENT_WRONG;
            $output['statusCode'] = STATUS_SERVER_ERROR;
            return $output;
        }
    }


    public function checkPromoterAccess() {
        $this->ci->customsession->loginCheck();
        $userId = getUserId();
        $this->ci->Promoter_model->resetVariable();
        //Checking promoter code is already there or not
        
        $selectInput['numRows'] = "COUNT('".$this->ci->Promoter_model->id."')";
        $this->ci->Promoter_model->setSelect($selectInput);
        $where[$this->ci->Promoter_model->userid] = $userId;
        $where[$this->ci->Promoter_model->deleted] = 0;
        $this->ci->Promoter_model->setWhere($where);
        $result = $this->ci->Promoter_model->get();
        
        $numRows = $result[0]['numRows'];
        if ($numRows > 0) {
            $output = parent::createResponse(TRUE, '', STATUS_OK, '', 'isPromoter', 1);
        } else {
            $output = parent::createResponse(TRUE, '', STATUS_OK, '', 'isPromoter', 0);
        }
        return $output;
    }

    public function getPromoterDataById($inputArray) {
        $this->ci->Promoter_model->resetVariable();        
        $select['id'] = $this->ci->Promoter_model->id;
        $select['name'] = $this->ci->Promoter_model->name;
        $select['userid'] = $this->ci->Promoter_model->userid;
        $select['eventid'] = $this->ci->Promoter_model->eventid;
        $select['status'] = $this->ci->Promoter_model->status;
        $select['type'] = $this->ci->Promoter_model->type;
        $select['code'] = $this->ci->Promoter_model->code;
        $select['ticketslimit'] = $this->ci->Promoter_model->ticketslimit;
        $this->ci->Promoter_model->setSelect($select);
        $where[$this->ci->Promoter_model->id] = $inputArray['id'];
        $where[$this->ci->Promoter_model->deleted] = 0;

        $this->ci->Promoter_model->setWhere($where);
        $promoterDetails = $this->ci->Promoter_model->get();

        $output['status'] = TRUE;
        $output["response"]["promoters"] = $promoterDetails;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    public function getPromoterEvents($inputArray, $eventid = '') {
        $this->ci->Promoter_model->resetVariable();
        $select['id'] = $this->ci->Promoter_model->id;
        $select['name'] = $this->ci->Promoter_model->name;
        $select['userid'] = $this->ci->Promoter_model->userid;
        $select['eventid'] = $this->ci->Promoter_model->eventid;
        $select['status'] = $this->ci->Promoter_model->status;
        $select['type'] = $this->ci->Promoter_model->type;
        $select['code'] = $this->ci->Promoter_model->code;
        $this->ci->Promoter_model->setSelect($select);
        $where[$this->ci->Promoter_model->userid] = $inputArray['userId'];
        $where[$this->ci->Promoter_model->deleted] = 0;
        if (isset($eventid) && $eventid != '') {
            $where[$this->ci->Promoter_model->eventid] = $eventid;
        }
        if (isset($inputArray['promoterId']) && $inputArray['promoterId'] != '') {
            $where[$this->ci->Promoter_model->id] = $inputArray['promoterId'];
        }
        $this->ci->Promoter_model->setWhere($where);
        $promoterDetails = $this->ci->Promoter_model->get();
        if (count($promoterDetails) > 0) {
            foreach ($promoterDetails as $key => $value) {
                $eventIds[] = $value['eventid'];
                $userIds[] = $value['userid'];
                $promoterCodes[] = $value['code'];
            }
            $inputArray['eventId'] = $eventIds;
            $inputArray['promotroCode'] = $promoterCodes;
            // For setting the User redirection URL After Login
            if (isset($inputArray['loginredirectCheck']) && $inputArray['loginredirectCheck'] == true) {
                $count = 0;
                if (count($eventIds) > 0) {
                    $count = count($eventIds);
                }
                $output['status'] = TRUE;
                $output['messages'][] = '';
                $output['response']['total'] = $count;
                $output['statusCode'] = STATUS_OK;
                return $output;
            }
            $eventDetails = $this->eventHandler->getPromoterEventsData($inputArray);
            if ($eventDetails['status'] == TRUE && $eventDetails['response']['total'] > 0) {
                $eventInfo = commonHelperGetIdArray($eventDetails['response']['eventData'], 'id');                
            }else{
                $output['status'] = FALSE;
                $output["response"]["messages"][] = ERROR_NO_DATA;
                $output['statusCode'] = STATUS_NO_DATA;
                return $output;
            }
//            $eventInfo = commonHelperGetIdArray($eventDetails['response']['eventData'], 'id');
            $this->eventsignupHandler = new Eventsignup_handler();
            $eventSignupIds = $this->eventsignupHandler->getMultipleEventSignupData($inputArray);
            $eventData = $eventSignupIds['response']['eventsignupData'];
            $events = commonHelperGetIdArray($eventData, 'eventid');
            $response = array();
            $key = 0;
            foreach ($promoterDetails as $val) {
                $response[$key]['promoterId'] = $val['id'];
                $response[$key]['eventId'] = $val['eventid'];
                $response[$key]['type'] = $val['type'];
                $response[$key]['code'] = $val['code'];
                $response[$key]['status'] = $val['status'];
                $response[$key]['quantity'] = isset($events[$val['eventid']]['quantity']) ? $events[$val['eventid']]['quantity'] : 0;
                $response[$key]['totalamount'] = isset($events[$val['eventid']]['totalamount']) ? $events[$val['eventid']]['totalamount'] : 0;
                $key++;
            }
            foreach ($response as $key => $value) {
                if (!in_array($value['eventId'], array_keys($eventInfo))) {
                    unset($response[$key]);
                } else {
                    $response[$key]['eventTitle'] = $eventInfo[$value['eventId']]['title'];
                    $response[$key]['city'] = $eventInfo[$value['eventId']]['cityname'];
                    $response[$key]['startdate'] = $eventInfo[$value['eventId']]['startDate'];
                    $response[$key]['enddate'] = $eventInfo[$value['eventId']]['endDate'];
                }
            }
            if ($response) {
                $output['status'] = TRUE;
                $output["response"]["promoters"] = $response;
                $output['statusCode'] = STATUS_OK;
                return $output;
            } else {
                $output['status'] = FALSE;
                $output["response"]["messages"][] = ERROR_NO_DATA;
                $output['statusCode'] = STATUS_NO_DATA;
                return $output;
            }
        } else {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_NO_DATA;
            $output['statusCode'] = STATUS_NO_DATA;
            return $output;
        }
    }

    public function promoterEventsList($inputArray) {
        $this->ci->Promoter_model->resetVariable();
        $select['id'] = $this->ci->Promoter_model->id;
        $select['userid'] = $this->ci->Promoter_model->userid;
        $select['eventid'] = $this->ci->Promoter_model->eventid;
        $select['status'] = $this->ci->Promoter_model->status;
        $select['type'] = $this->ci->Promoter_model->type;
        $select['ticketslimit'] = $this->ci->Promoter_model->ticketslimit;
        $this->ci->Promoter_model->setSelect($select);
        $where[$this->ci->Promoter_model->userid] = $inputArray['userId'];
        $where[$this->ci->Promoter_model->type] = 'offline';
        $where[$this->ci->Promoter_model->status] = 1;
        $where[$this->ci->Promoter_model->deleted] = 0;
        if(isset($inputArray['id'])){
        $where[$this->ci->Promoter_model->id] =$inputArray['id'];
        }
        $groupBy = array($this->ci->Promoter_model->eventid);
        $this->ci->Promoter_model->setGroupBy($groupBy);
        $this->ci->Promoter_model->setWhere($where);
        $promoterDetails = $this->ci->Promoter_model->get();
        if (!$promoterDetails) {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_NO_DATA;
            $output['statusCode'] = STATUS_NO_DATA;
            return $output;
        }
        foreach ($promoterDetails as $key => $value) {
            $eventIds[] = $value['eventid'];
        }
        $inputArray['eventId'] = $eventIds;
        $eventDetails = $this->eventHandler->getPromoterEventsData($inputArray);
        if ($eventDetails['status'] == TRUE && $eventDetails['response']['total'] > 0) {
            $eventInfo = commonHelperGetIdArray($eventDetails['response']['eventData'], 'id');
        }else{
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_NO_DATA;
            $output['statusCode'] = STATUS_NO_DATA;
            return $output;
        }
//        $eventInfo = commonHelperGetIdArray($eventDetails['response']['eventData'], 'id');
        $key = 0;
        $response = array();
        foreach ($promoterDetails as $val) {
            if ($eventInfo[$val['eventid']]['title']) {
                $response[$key]['promoterid'] = $val['id'];
                $response[$key]['ticketslimit'] = $val['ticketslimit'];
                $response[$key]['id'] = $val['eventid'];
                $response[$key]['title'] = $eventInfo[$val['eventid']]['title'];
            }
            $key++;
        }
        if (count($response) > 0) {
            $output['status'] = TRUE;
            $output["response"]["events"] = $response;
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_NO_DATA;
            $output['statusCode'] = STATUS_NO_DATA;
            return $output;
        }
    }

    public function isPromoterForEvent($input) {
        //$this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($input);
        $this->ci->form_validation->set_rules('eventid', 'eventid', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('promoterid', 'promoterid', 'required_strict|is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $eventId = $input['eventid'];
        $promoterId = $input['promoterid'];
        $this->ci->Promoter_model->resetVariable();
        $select['id'] = $this->ci->Promoter_model->id;
		$select['userid'] = $this->ci->Promoter_model->userid;
        $select['name'] = $this->ci->Promoter_model->name;
        $select['code'] = $this->ci->Promoter_model->code;
		$select['orgpromoteurl'] = $this->ci->Promoter_model->orgpromoteurl;
        $this->ci->Promoter_model->setSelect($select);
        $where[$this->ci->Promoter_model->deleted] = 0;
        $where[$this->ci->Promoter_model->eventid] = $eventId;
        $where[$this->ci->Promoter_model->id] = $promoterId;
        //$where[$this->ci->Promoter_model->type] = 'affiliate';
        $this->ci->Promoter_model->setWhere($where);

        $promoterResponse = $this->ci->Promoter_model->get();
        //echo $this->ci->db->last_query();exit;
        if (count($promoterResponse) == 0) {
            $output['status'] = TRUE;
            $output["response"]["total"] = 0;
            $output["response"]["messages"][] = ERROR_NO_DATA;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
		
		
		
		
		
        $output['status'] = TRUE;
        $output["response"]["promoterResponse"] = $promoterResponse;
        $output["response"]["total"] = count($promoterResponse);
        $output["response"]["messages"] = array();
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    public function offlineTicketsByEvent($inputArray) {
        $inputArray['ticketStatus'] = 1;
        $tickets = $this->offlinepromoterticketmappingHandler->getOfflineTickets($inputArray);
        if ($tickets['status'] == FALSE) {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_NO_DATA;
            $output['statusCode'] = STATUS_NO_DATA;
            return $output;
        }
        $eventTickets = $tickets['response']['offline'];
        foreach ($eventTickets as $key => $value) {
            $ticketIds[] = $value['ticketid'];
        }
        $inputArray['ticketId'] = $ticketIds;
        $inputArray['soldout'] = 0;
        $tickets = $this->ticketHandler->getTicketName($inputArray);
        $ticketNames = $tickets['response']['ticketName'];

        $ticketInfo = commonHelperGetIdArray($ticketNames, 'id');
        foreach ($eventTickets as $key => $value) {
            if (isset($ticketInfo[$value['ticketid']])) {
                $eventTickets[$key]['ticketname'] = $ticketInfo[$value['ticketid']]['name'];
                $eventTickets[$key]['minorderquantity'] = $ticketInfo[$value['ticketid']]['minorderquantity'];
                $eventTickets[$key]['maxorderquantity'] = $ticketInfo[$value['ticketid']]['maxorderquantity'];
            } else {
                unset($eventTickets[$key]);
            }
        }
        if (count($eventTickets) > 0) {
            $output['status'] = TRUE;
            $output["response"]["eventTickets"] = $eventTickets;
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_NO_DATA;
            $output['statusCode'] = STATUS_NO_DATA;
            return $output;
        }
    }

    public function getTicketData($inputArray) {
        $tickets = $this->ticketHandler->getTicketName($inputArray);
        $pArray['userId'] = getUserId();
        $promoterData = $this->getPromoterEvents($pArray, $inputArray['eventId']);
        $tcktLimit = $this->getPromoterDataById($inputArray);

        if ($tcktLimit['response']['promoters']['0']['ticketslimit'] > 0) {
            $promoterTckets = commonHelperGetIdArray($tcktLimit['response']['promoters'], 'id');
            foreach ($promoterTckets as $key => $val) {
                $promoterData['response']['promoters']['0']['maxTicketLimit'] = $val['ticketslimit'];
            }
            $tickets['response']['ticketName']['0']['ticketsSoldQty'] = $promoterData['response']['promoters']['0']['quantity'];
            $tickets['response']['ticketName']['0']['ticketsMaxLimitQty'] = $promoterData['response']['promoters']['0']['maxTicketLimit'];
            $maxQunatity = $tickets['response']['ticketName']['0']['maxorderquantity'];
            $proTicketsSoldQty = $tickets['response']['ticketName']['0']['ticketsSoldQty'];
            $proMaxLimitQty = $tickets['response']['ticketName']['0']['ticketsMaxLimitQty'];
            $promoTotalQty = $proMaxLimitQty - $proTicketsSoldQty;
            if ($promoTotalQty > 10) {
                $Qty = $maxQunatity;
            } else {
                $Qty = $promoTotalQty;
            }
        } else { 
            $minQunatity = $tickets['response']['ticketName']['0']['minorderquantity']; 
            $maxQunatity = $tickets['response']['ticketName']['0']['maxorderquantity'];
            $totalSoldTickets = $tickets['response']['ticketName']['0']['totalsoldtickets'];
            $totalQuantity = $tickets['response']['ticketName']['0']['quantity'];
            $total = $totalQuantity - $totalSoldTickets;        
            if ($total > $maxQunatity) {
                $Qty = $maxQunatity;
            } else {
                $Qty = $total;
            }
        }
        $tickets['response']['ticketName']['0']['finalQty'] = $Qty;
        if ($tickets) {
            return $tickets;
        } else {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_NO_DATA;
            $output['statusCode'] = STATUS_NO_DATA;
            return $output;
        }
    }

    /*
     * Function to check whether the given promoter code is active or not
     *
     * @access	public
     * @param
     *      	eventId - Id of the event
     *      	promoterCode - promoter Code
     */

    public function checkPromoterCode($inputArray) {

        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'event id', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('promoterCode', 'promoter code', 'required_strict');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $promoterCode = $inputArray['promoterCode'];
        if ($promoterCode == 'organizer') {
            $output = parent::createResponse(TRUE, array(), STATUS_OK, 1, 'promoterList', array());
            return $output;
        }
        $this->ci->Promoter_model->resetVariable();
        $select['id'] = $this->ci->Promoter_model->id;
        $select['name'] = $this->ci->Promoter_model->name;
        $select['userid'] = $this->ci->Promoter_model->userid;
        $select['eventid'] = $this->ci->Promoter_model->eventid;
        $select['status'] = $this->ci->Promoter_model->status;
        $select['code'] = $this->ci->Promoter_model->code;
        $this->ci->Promoter_model->setSelect($select);
        if(isset($inputArray['eventId'])){
        $where[$this->ci->Promoter_model->eventid] = $inputArray['eventId'];
        }
        if(isset($inputArray['type'])){
            $where[$this->ci->Promoter_model->type] = $inputArray['type'];
        }
        if (isset($inputArray['promoterCode'])) {
            $where[$this->ci->Promoter_model->code] = $inputArray['promoterCode'];
        }
        $where[$this->ci->Promoter_model->status] = 1;
        $where[$this->ci->Promoter_model->deleted] = 0;

        $this->ci->Promoter_model->setWhere($where);
        if (isset($where_in)) {
            $this->ci->Promoter_model->setWhereIns($where_in);
        }
        $promoterDetails = $this->ci->Promoter_model->get();

        if (count($promoterDetails) > 0) {
            $output = parent::createResponse(TRUE, array(), STATUS_OK, count($promoterDetails), 'promoterList', $promoterDetails);
            return $output;
        } elseif (count($promoterDetails) == 0) {
            $output = parent::createResponse(TRUE, ERROR_NO_PROMOTER, STATUS_OK, 0, 'promoterList', array());
            return $output;
        } else {
            $output = parent::createResponse(FALSE, ERROR_SOMETHING_WENT_WRONG, STATUS_SERVER_ERROR);
            return $output;
        }
    }

    public function getGlobalCode($inputArray) {

        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('userid', 'userid', 'required_strict|is_natural_no_zero');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $userId = $inputArray['userid'];
        $this->ci->Promoter_model->resetVariable();
        $select['id'] = $this->ci->Promoter_model->id;
        $select['name'] = $this->ci->Promoter_model->name;
        $select['userid'] = $this->ci->Promoter_model->userid;
        $select['eventid'] = $this->ci->Promoter_model->eventid;
        $select['status'] = $this->ci->Promoter_model->status;
        $select['code'] = $this->ci->Promoter_model->code;
        $this->ci->Promoter_model->setSelect($select);
        $where[$this->ci->Promoter_model->type] = 'global';
        $where[$this->ci->Promoter_model->userid] = $userId;
        $where[$this->ci->Promoter_model->status] = 1;
        $where[$this->ci->Promoter_model->deleted] = 0;

        $this->ci->Promoter_model->setWhere($where);
        
        $promoterDetails = $this->ci->Promoter_model->get();
        if (count($promoterDetails) > 0) {
            $output = parent::createResponse(TRUE, array(), STATUS_OK, count($promoterDetails), 'promoterList', $promoterDetails);
            return $output;
        } elseif (count($promoterDetails) == 0) {
            $output = parent::createResponse(TRUE, ERROR_NO_PROMOTER, STATUS_OK, 0, 'promoterList', array());
            return $output;
        } else {
            $output = parent::createResponse(FALSE, ERROR_SOMETHING_WENT_WRONG, STATUS_SERVER_ERROR);
            return $output;
        }
    }
    public function getPromoterSales($input) {
        //$getVar = $this->input->get();
        $eventId = $input['eventId'];
        $inputArray['eventid'] = $input['eventId'];
        $inputArray['reporttype'] = $input['reporttype'];
        $inputArray['transactiontype'] = $input['transactiontype'];
        $inputArray['page'] = $input['page'];
        $inputArray['promotercode'] = $input['promotercode'];
//        if (isset($getVar['promotercode'])) {
//            $inputArray['promotercode'] = $getVar['promotercode'];
//        }
//        if (isset($getVar['currencycode'])) {
//            $inputArray['currencycode'] = $getVar['currencycode'];
//        }
//        if (isset($getVar['ticketid']) && $getVar['ticketid'] > 0) {
//            $inputArray['ticketid'] = $getVar['ticketid'];
//        } 
        $response['status'] = true;
        $response['response']['total'] = 1;
        $this->reportsHandler = new Reports_handler();
        $tableHeaderResponse = $this->reportsHandler->getHeaderFields($inputArray);

        if ($tableHeaderResponse['status'] && $tableHeaderResponse['response']['total'] > 0) {
            $response['headerFields'] = $tableHeaderResponse['response']['headerFields'];
        } else {
            return $tableHeaderResponse;
        }
        $input['eventId'] = $eventId;
        $eventExistsResponse = $this->eventHandler->eventExists($input);
        if (!$eventExistsResponse['status']) {
            return $eventExistsResponse;
            //$this->load->view('templates/dashboard_template', $data);
        } else {
            $response['eventTitle'] =  commonHelperGetEventName($eventId);
        }
        $grandTotal = $this->reportsHandler->getGrandTotal($inputArray);
        //print_r($grandTotal);exit;
        if ($grandTotal['status'] && $grandTotal['response']['total'] > 0) {
            $response['grandTotal'] = $grandTotal['response']['grandTotalResponse'];
        } else {
            $transactionListResponse['headerFields'] = $response['headerFields'];
            return $grandTotal;
        }
        $transactionListResponse = $this->reportsHandler->getReportDetails($inputArray);
        if ($transactionListResponse['status'] && $transactionListResponse['response']['total'] > 0) {
            $response['transactionListResponse'] = $transactionListResponse;
        } else {
            $transactionListResponse['headerFields'] = $response['headerFields'];
            return $transactionListResponse;
        }
        return $response;
    }
   
    public function checkPromoterEventAccess ($inputArray) {
        $this->ci->customsession->loginCheck();
        $userId = getUserId();
        $eventId = $inputArray['eventId'];
        $this->ci->Promoter_model->resetVariable();
        $where[$this->ci->Promoter_model->userid] = $userId;
        $where[$this->ci->Promoter_model->eventid] = $eventId;
        $where[$this->ci->Promoter_model->deleted] = 0;
        $this->ci->Promoter_model->setWhere($where);
        $result = $this->ci->Promoter_model->getCount();
        if ($result != false && $result > 0) {
            $output = parent::createResponse(TRUE, '', STATUS_OK, '', 'isEventPromoter', 1);
        } else {
            $output = parent::createResponse(TRUE, '', STATUS_OK, '', 'isEventPromoter', 0);
        }
        return $output;
    }
    
    public function getUpcomingPastPromoterEventsCount(){
        $this->ci->Promoter_model->resetVariable();
        $select['eventid'] = $this->ci->Promoter_model->eventid;
        $this->ci->Promoter_model->setSelect($select);
        $where[$this->ci->Promoter_model->userid] = getUserId();
        $where[$this->ci->Promoter_model->deleted] = 0;
        $this->ci->Promoter_model->setWhere($where);
        $promoterDetails = $this->ci->Promoter_model->get();      
        if (count($promoterDetails) > 0) {
            foreach ($promoterDetails as $key => $value) {
                $eventIds[] = $value['eventid'];
            }
        }else if(count($promoterDetails)== 0){
            $output = parent::createResponse(TRUE, ERROR_NO_EVENTS, STATUS_OK);
            return $output;
        }
        $inputArray['countOnly']=true;
        $inputArray['type'] = 'currentEvents';
        $inputArray['eventId'] = $eventIds;
        $eventsCount = $this->eventHandler->getPromoterEventsData($inputArray);
        if($eventsCount['status'] && isset($eventsCount['response']['currentEvents']) && ($eventsCount['response']['currentEvents']>0)){
            $response=parent::createResponse(TRUE,"",STATUS_OK,' ', 'upcomingPromoterEventsCount', $eventsCount['response']['currentEvents']);
            return $response;
        }
        $inputArray['type'] = 'pastEvents';     
        $eventsCount = $this->eventHandler->getPromoterEventsData($inputArray);
        if($eventsCount['status'] && isset($eventsCount['response']['pastEvents']) && ($eventsCount['response']['pastEvents']>0)){
            $response=parent::createResponse(TRUE,"",STATUS_OK,' ', 'pastPromoterEventsCount', $eventsCount['response']['pastEvents']);
            return $response;
        }
        $output = parent::createResponse(FALSE, ERROR_SOMETHING_WENT_WRONG, STATUS_SERVER_ERROR);
        return $output;
    }
}
