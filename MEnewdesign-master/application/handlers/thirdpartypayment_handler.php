<?php

/**
 * Connected to MAXMIND GEO IP 3rd party data( from local) to get the IP corresponding country name & city name
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     12-06-2015
 * @Last Modified 12-06-2015
 */
require_once(APPPATH . 'handlers/handler.php');
require_once(APPPATH . 'handlers/booking_handler.php');

class Thirdpartypayment_handler extends Booking_handler {

    public function __construct() {
        parent::__construct();
        $this->ci->load->model('Thirdpartypayment_model');
        
        $hostname=strtolower($_SERVER['HTTP_HOST']);
        if(strcmp($hostname,'www.meraevents.com')==0 || strcmp($hostname,'meraevents.com')==0 || strcmp($hostname, "dhamaal.meraevents.com")==0)
        {
            define('EBS_SECRET_KEY', '67624ee7bb021090f9c0ef1bb3dcd53f');
            define('EBS_ACCOUNT_ID', '7300');
        }
        else
        {
            define('EBS_SECRET_KEY', '67624ee7bb021090f9c0ef1bb3dcd53f');
            define('EBS_ACCOUNT_ID', '7300');
        }
    }

    public function addPayment($inputArray) {
        $output = array();
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('referenceid', 'reference_no', 'required_strict|min_length[4]');
        $this->ci->form_validation->set_rules('source', 'source', 'required_strict');

        if ($this->ci->form_validation->run() === FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        //check the reference no in our db
        $response = $this->getReferenceNo($inputArray);
//        echo $this->ci->db->last_query();
//        print_r($response);
        if ($response['response']['total'] > 0) {
            $output['status'] = FALSE;
            $output['response']['messages'] = "Invalid Reference NO";
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }

        $createData[$this->ci->Thirdpartypayment_model->referenceid] = $inputArray['referenceid'];
        $createData[$this->ci->Thirdpartypayment_model->data] = json_encode($inputArray['data']);
        $createData[$this->ci->Thirdpartypayment_model->paymentsourceid] = $inputArray['paymentSourceId'];
        $createData[$this->ci->Thirdpartypayment_model->amount] = $inputArray['amount'];
        $this->ci->Thirdpartypayment_model->setInsertUpdateData($createData);
        $pointId = $this->ci->Thirdpartypayment_model->insert_data();
        if ($pointId) {
            $output['status'] = TRUE;
            $output['response']['messages'] = SUCCESS_VIRALTICKETSALE_ADDED;
            $output['response']['pointId'] = $pointId;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = FALSE;
        $output['response']['messages'] = SOMETHING_WRONG;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }

    //To store the payment process related information
    public function thirdPartyWebsitePayment() {
        $jsonObjData = getNormalJSONinput();
        $inputArray = (array) $jsonObjData;

        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('name', 'name', 'required_strict|min_length[3]');
        $this->ci->form_validation->set_rules('email', 'email', 'required_strict|valid_email');
        $this->ci->form_validation->set_rules('phone', 'phone', 'required_strict|min_length[10]');
        $this->ci->form_validation->set_rules('reference_no', 'reference_no', 'required_strict|min_length[4]');
        $this->ci->form_validation->set_rules('address', 'address', 'required_strict|min_length[4]');
        $this->ci->form_validation->set_rules('return_url', 'return_url', 'required_strict|min_length[4]');
        $this->ci->form_validation->set_rules('amount', 'amount', 'required_strict|is_natural_no_zero');
        $this->ci->form_validation->set_rules('description', 'description', 'required_strict|min_length[4]');
//        $this->ci->form_validation->set_rules('postal_code', 'postal_code', 'required_strict|min_length[2]');
//        $this->ci->form_validation->set_rules('city', 'city', 'required_stric');
//        $this->ci->form_validation->set_rules('state', 'state', 'required_strict');

        if ($this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
            return $output;
        }

        $paymentHandler = new Paymentsource_handler();
        $paymentArray['paymentSourceName'] = $inputArray['source'];
        $paymentDetails = $paymentHandler->getPaymentSourceId($paymentArray);
        if (!$paymentDetails['status']) {
            return $paymentDetails;
        }
        $paymentSourceId = $paymentDetails['response']['paymentSource'][0]['id'];

        $paymentData = array();
        $paymentData['referenceid'] = $inputArray['reference_no'];
        $paymentData['data'] = $inputArray;
        $paymentData['source'] = $inputArray['source'];
        $paymentData['paymentSourceId'] = $paymentSourceId;
        $paymentData['amount'] =$inputArray['amount'];
        $paymentDataResponse = $this->addPayment($paymentData);
        if (!$paymentDataResponse['status']) {
            return $paymentDataResponse;
        }
        $inputArray['eventId'] = $paymentDetails['response']['paymentSource'][0]['eventid'];
        $this->eventHandler = new Event_handler();
        $eventDetails = $this->eventHandler->getEventDetailsForBooking($inputArray);
        if (!$eventDetails['status']) {
            return $eventDetails;
        }
        $organizerId = $eventDetails['response']['details']['ownerId'];

        //bring the first ticket id    
        $this->ticketHandler = new Ticket_handler();
        $ticketDetails = $this->ticketHandler->getEventTicketList($inputArray);
        if (!$ticketDetails['status']) {
            return $ticketDetails;
        }
        $ticketId = $ticketDetails['response']['ticketList'][0]['id'];
        $ticketSoldCount = $ticketDetails['response']['ticketList'][0]['totalSoldTickets'];
        $transactionToCurrencyId = $transactionCurrencyId = $ticketDetails['response']['ticketList'][0]['currencyId'];


        $eventSignupInsert['userid'] = $organizerId;
        $eventSignupInsert['eventid'] = $inputArray['eventId'];
        $eventSignupInsert['quantity'] = 1;
        $eventSignupInsert['totalamount'] = $inputArray['amount'];
        $eventSignupInsert['attendeeid'] = 0;
        $eventSignupInsert['transactionstatus'] = 'pending';
        $eventSignupInsert['paymentstatus'] = 'NotVerified';
        $eventSignupInsert['paymentGatewayId'] = 1;
//        $eventSignupInsert['promotercode'] = "X";
        $eventSignupInsert['transactiontickettype'] = "paid";
        $eventSignupInsert['transactionticketids'] = $ticketId;
        $eventSignupInsert['fromcurrencyid'] = $transactionCurrencyId;
        $eventSignupInsert['tocurrencyid'] = $transactionToCurrencyId;
        $eventSignupInsert['paymentmodeid'] = 1; //one for ebs
        $eventSignupInsert['eventextrachargeamount'] = 0;
        $eventSignupInsert['eventextrachargeid'] = 0;
        $eventSignupInsert['discountamount'] = 0;
        $eventSignupInsert['referraldiscountamount'] = 0;
        $eventSignupInsert['signupdate'] = gmdate('Y-m-d h:i:s');
        $eventSignupInsert['discount'] = "X";
        $eventSignupInsert['barcodenumber'] = 0;
        $eventSignupInsert['discountcodeid'] = 0;
        $eventSignupInsert['referralcode'] = 0;
        $eventSignupInsert['userpointid'] = 0;
        $eventSignupInsert['deleted'] = 0;
        $eventSignupInsert['createdby'] = $organizerId;
        $eventSignupInsert['modifiedby'] = $organizerId;

        $eventSignupId = 0;
        $this->eventsignupHandler = new Eventsignup_handler();
        $eventSignupReturn = $this->eventsignupHandler->add($eventSignupInsert);
        if ($eventSignupReturn['status']) {
            $eventSignupId = $eventSignupReturn['response']['eventSignUpId'];
        } else {
            $response['status'] = FALSE;
            $response['statusCode'] = $eventSignupReturn['statusCode'];
            $response['response']['messages'] = $eventSignupReturn['response']['messages'][0];
            return $response;
        }

        $eventSignupTicketArray = $inputArray;
        $eventSignupTicketArray["eventSignupId"] = $eventSignupId;
        $eventSignupTicketArray["ticketId"] = $ticketId;
        $eventSignupTicketArray['ticketquantity'] = 1;
        $this->insertEventSignupTicketDetails($eventSignupTicketArray);
        $eventSignupTicketArray["totalsoldtickets"] = $ticketSoldCount;
        $generateNumber=substr($inputArray['eventId'],1,4).$eventSignupId;
        $eventSignupTicketArray["barcodenumber"] = $generateNumber;
        
        $eventSignupTicketArray['attendeeid'] = $this->insertAttendee($eventSignupTicketArray);
        $this->updateEventsingup($eventSignupTicketArray);
        $eventSignupTicketArray['formUrl']=site_url("payment/thirdPartyEbsPrepare" );
        $data = $this->ci->load->view('api/thirdPartyWebsitePayment', $eventSignupTicketArray,true);
        print_r($data);exit;
    }

    public function insertEventSignupTicketDetails($data) {
        $eventSignupTicketInsert['eventsignupid'] = $data['eventSignupId'];
        $eventSignupTicketInsert['ticketid'] = $data['ticketId'];
        $eventSignupTicketInsert['ticketquantity'] = $data['ticketquantity'];
        $eventSignupTicketInsert['amount'] = $data['amount'];
        $eventSignupTicketInsert['totalamount'] = $data['amount'];
        $eventSignupTicketInsert['totaltaxamount'] = 0;
        $eventSignupTicketInsert['discountcode'] = 0;
        $eventSignupTicketInsert['discountcodeid'] = 0;
        $eventSignupTicketInsert['discountamount'] = 0;
        $eventSignupTicketInsert['bulkdiscountamount'] = 0;
        $eventSignupTicketInsert['referraldiscountamount'] = 0;
        $this->eventsignupticketdetailHandler = new Eventsignup_Ticketdetail_handler();
        $eventSignUpTicketDetailResponse = $this->eventsignupticketdetailHandler->add($eventSignupTicketInsert);
        return $eventSignUpTicketDetailResponse['response']['eventSignUpTicketDetailId'];
    }

    //To Insert the attendee 
    //returns the primary attendee id
    public function insertAttendee($data) {
        $ticektQty = $data['ticketquantity'];
        for ($i = 0; $i < $ticektQty; $i++) {
            $attendeeInsert = array();
            $attendeeInsert['eventSignupId'] = $data['eventSignupId'];
            $attendeeInsert['ticketId'] = $data['ticketId'];

            $attendeeInsert["primary"] = 0;
            if ($i == 0) {
                $attendeeInsert["primary"] = 1;
            }
            $this->attendeeHandler = new Attendee_handler();
            $attendeeId = $this->attendeeHandler->add($attendeeInsert);

            if ($i == 0) {
                $primaryAttendeeId = $attendeeId['response']['attendeeId'];
            }
            //Inserting attendeedetails table
            $customFiedArray = $data;
            unset($customFiedArray['ticketid']);
            $this->configureHandler = new Configure_handler();
            $eventCustomFieldsArr = $this->configureHandler->getCustomFields($data);
            if (!$eventCustomFieldsArr['status']) {
                return $eventCustomFieldsArr;
            }
            $eventCustomFieldsList = $eventCustomFieldsArr['response']['customFields'];
            //inserting fullname custom field data
            $attendeeDetailInsert = array();
            foreach ($eventCustomFieldsList as $list) {
                $attendeeDetailInsert['customFieldId'] = $list['id'];
                $attendeeDetailInsert['value'] = $this->getCustomfieldValues($data, $list['fieldname']);
                $attendeeDetailInsert['attendeeId'] = $primaryAttendeeId;
                $attendeeDetailInsert['commonFieldId'] = $list['commonfieldid'];
                $this->attendeeDetailHandler = new Attendeedetail_handler();
                $addAttendeeDetailReturn = $this->attendeeDetailHandler->add($attendeeDetailInsert);
            }
        }
        return $primaryAttendeeId;
    }

    public function getCustomfieldValues($data, $fieldName) {
        switch ($fieldName) {
            case "Full Name":
                return $data['name'];
            case "Email Id":
                return $data['email'];
            case "Mobile No":
                return $data['phone'];
            case "Address":
                return $data['address'];
            case "Pin Code":
                return $data['postal_code'];
            case "State":
                return $data['state'];
            case "City":
                return $data['city'];
            default:
                return "";
        }
    }

    public function updateThirdPartyTicketSoldCount($data) {
        $eventSignup['eventsignupId'] = $data['transactionid'];
        $this->eventsignupHandler = new Eventsignup_handler();
        $eventSignupResponse = $this->eventsignupHandler->getEventSignupData($eventSignup);
        if ($eventSignupResponse['status'] && isset($eventSignupResponse['response']['eventSignupData'])) {
            $ticketId = $eventSignupResponse['response']['eventSignupData'][0]['transactionticketids'];
            $inputArray['eventId']=$eventSignupResponse['response']['eventSignupData'][0]['eventid'];
            //bring the first ticket id   
            $this->ticketHandler = new Ticket_handler();
            $ticketDetails = $this->ticketHandler->getEventTicketList($inputArray);
            
            $totalsoldtickets = $ticketDetails['response']['ticketList'][0]['totalSoldTickets'];
            $ticketUpdateData = array();
            $ticketQuantity=$eventSignupResponse['response']['eventSignupData'][0]['quantity'];
            $ticketUpdateData['condition']['ticketId'] = $ticketId; 
            $ticketUpdateData['update']['totalSoldTickets'] = $totalsoldtickets+$ticketQuantity;
            return $this->ticketHandler->ticketIndividualUpdate($ticketUpdateData);
        }
    }

    public function updateEventsingup($data) {
        $eventSignupUpdate['eventSignUpId'] = $data['eventSignupId'];
        $eventSignupUpdate['attendeeid'] = $data['attendeeid'];
        $eventSignupUpdate['barcodenumber'] = $data['barcodenumber'];
        $this->eventsignupHandler = new Eventsignup_handler();
        return $this->eventsignupHandler->updateEventSignUp($eventSignupUpdate);
    }

    //To get the third party reference no
    public function getReferenceNo($data) {
        $selectInput['id'] = $this->ci->Thirdpartypayment_model->id;

        $this->ci->Thirdpartypayment_model->setSelect($selectInput);

        //fetching active tickets & not deleted
        $where[$this->ci->Thirdpartypayment_model->referenceid] = $data['referenceid'];
        $this->ci->Thirdpartypayment_model->setWhere($where);
        $details = $this->ci->Thirdpartypayment_model->get();

        if (count($details) > 0) {

            $output['status'] = TRUE;
            $output['response']['paymentDetails'] = $details;

            $output['messages'] = array();
            $output['response']['total'] = count($details);
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $output['status'] = TRUE;
        $output['messages'][] = ERROR_NO_DATA;
        $output['response']['total'] = 0;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }

    public function updateThirdPartyPayment($data) {
        $updateData = array();

        if (isset($data['transactionid']) && $data['transactionid'] != '') {
            $updateData['transactionid'] = $data['transactionid'];
        }
        if (isset($data['amount']) && $data['amount'] != '') {
            $updateData['amount'] = $data['amount'];
        }
        if (isset($data['status']) && $data['status'] != '') {
            $updateData['status'] = $data['status'];
        }

        $where['referenceid'] = $data['referenceid'];
        $this->ci->Thirdpartypayment_model->setInsertUpdateData($updateData);
        $this->ci->Thirdpartypayment_model->setWhere($where);
        $response = $this->ci->Thirdpartypayment_model->update_data();
        if ($response) {
            $output['status'] = TRUE;
            $output["response"]["messages"][] = SUCCESS_EVENTSIGNUP_UPDATE;
            $output['statusCode'] = STATUS_UPDATED;
        } else {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_EVENTSIGNUP_UPDATE;
            $output['statusCode'] = STATUS_SERVER_ERROR;
        }
        return $output;
    }

    function ebsPrepare($inputArray) {

        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);

        $this->ci->form_validation->set_rules('reference_no', 'reference number', 'required_strict|alpha_dash');
        $this->ci->form_validation->set_rules('return_url', 'return url', 'required_strict');
        $this->ci->form_validation->set_rules('amount', 'amount', 'required_strict');
        $this->ci->form_validation->set_rules('description', 'description', 'required_strict');
        $this->ci->form_validation->set_rules('email', 'email', 'required_strict|valid_email');
        $this->ci->form_validation->set_rules('name', 'name', 'required_strict|alphanumeric');
        $this->ci->form_validation->set_rules('phone', 'phone', 'required_strict|numeric');
        $this->ci->form_validation->set_rules('address', 'address', 'required_strict');
        $this->ci->form_validation->set_rules('city', 'city', 'required_strict');
        $this->ci->form_validation->set_rules('state', 'state', 'required_strict');
        $this->ci->form_validation->set_rules('postal_code', 'postal_code', 'required_strict');

        if ($this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'][0], STATUS_BAD_REQUEST);
            return $output;
        }

        $data['reference_no'] = $inputArray['reference_no'];
        $data['return_url'] = urlencode($inputArray['return_url']);
        $data['amount'] = $inputArray['amount'];
        $data['description'] = urlencode($inputArray['description']);
        $data['name'] = $inputArray['name'];
        $data['address'] = $inputArray['address'];
        $data['city'] = $inputArray['city'];
        $data['state'] = $inputArray['state'];
        $data['postal_code'] = $inputArray['postal_code'];
        $data['email'] = $inputArray['email'];
        $data['phone'] = $inputArray['phone'];

        $data['mode'] = "LIVE";
        if (isset($inputArray['mode']) && $inputArray['mode'] == "LIVE") {
            $data['mode'] = "LIVE";
        }
        $data['account_id'] = EBS_ACCOUNT_ID;
        $data['ebs_secret_key'] = EBS_SECRET_KEY;
        $data['meRedirectURL'] = site_url() . "payment/thirdPartyEbsResponseSave?client_reference_no=".$inputArray['client_reference_no']."&reffId=" . $data['reference_no'] . "&returntourl=" . $data['return_url'] . "&DR={DR}";

        $string = $data['ebs_secret_key'] . "|" . $data['account_id'] . "|" . $data['amount'] . "|" . $data['reference_no'] . "|" . html_entity_decode($data['meRedirectURL']) . "|" . $data['mode'];
        $data['secure_hash'] = md5($string);

        $retData = $this->ci->load->view('api/ebs_prepare', $data,true);
        print_r($retData);exit;
    }

    function ebsResponseSave($inputArray) {

        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);

        $this->ci->form_validation->set_rules('DR', 'Data set', 'required_strict');
        $this->ci->form_validation->set_rules('reffId', 'referrance Id', 'required_strict|numeric');

        if ($this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'][0], STATUS_BAD_REQUEST);
            return $output;
        }

        require_once(APPPATH . 'libraries/Rc43.php');
        $DR = preg_replace("/\s/", "+", $inputArray['DR']);
        $secret_key = EBS_SECRET_KEY;
        $rc4 = new Crypt_RC4($secret_key);
        $QueryString = base64_decode($DR);
        $rc4->decrypt($QueryString);
        $QueryString = explode('&', $QueryString);

        $response = array();
        foreach ($QueryString as $param) {
            $param = explode('=', $param);
            $response[$param[0]] = urldecode($param[1]);
        }
        $paymentStatus = '';
        $qty = '';
        $suspect = 0;

        $eventSignupId = $inputArray['reffId'];
        $paymentId = $response['PaymentID'];
        $paymentStatus = $response['ResponseCode'];
        $statusMessage = $response['ResponseMessage'];

        $eventSignupInput['eventsignupId'] = $eventSignupId;
        $this->eventsignupHandler = new Eventsignup_handler();
        $eventSignUpDetailResponse = $this->eventsignupHandler->getEventSignupData($eventSignupInput);
        $eventSignUpDetails = $eventSignUpDetailResponse['response']['eventSignupData'][0];

        if ($eventSignupId == $response['MerchantRefNo']) {
            if ($eventSignupId != '' && $eventSignupId > 0) {

                $orginalAmount = $eventSignUpDetails['totalamount'];
            }
            if ($response['ResponseCode'] == 0) {
                if ($orginalAmount == $response['Amount']) {

                    $paymentStatus = '0300';
                } else {

                    $eventSignupUpdateInput['eventSignUpId'] = $eventSignupId;
                    $eventSignupUpdateInput['transactionStatus'] = 'Suspect Transaction';
                    $this->eventsignupHandler->updateEventSignUp($eventSignupUpdateInput);
                    $paymentStatus = '0399';
                    $suspect = 1;
                }
            } else {
                $paymentStatus = '0399';
            }
        } else {
            $suspect = 1;
        }

        $eventSignupUpdateInput = array();
        $eventSignupUpdateInput['eventSignUpId'] = $eventSignupId;
        $stat=0;
        
        if ($response['ResponseCode'] == 0) {

            $eventSignupUpdateInput['transactionId'] = $response['TransactionID'];
            $eventSignupUpdateInput['transactionStatus'] = 'success';
            $stat = 1;
        } else if ($paymentStatus == '0399' || $paymentStatus == 'NA' || $paymentStatus == '0002' || $paymentStatus == '0001') {
           
            if ($paymentStatus == '0399') {

                $eventSignupUpdateInput['transactionStatus'] = 'failed';
            } else if ($paymentStatus == 'NA') {

                $eventSignupUpdateInput['transactionStatus'] = 'failed';
            } else if ($paymentStatus == '0002') {

                $eventSignupUpdateInput['transactionStatus'] = 'pending';
            } else if ($paymentStatus == '0001') {

                $eventSignupUpdateInput['transactionStatus'] = 'failed';
            }
        } else {
            $eventSignupUpdateInput['transactionStatus'] = 'failed';
        }
        $returnResponse = $this->eventsignupHandler->updateEventSignUp($eventSignupUpdateInput);

        $gatewayInsert['eventSignupId'] = $eventSignupId;
        $gatewayInsert['paymentId'] = $paymentId;
        $gatewayInsert['transactionId'] = $response['TransactionID'];
        $gatewayInsert['statusCode'] = $paymentStatus;
        $gatewayInsert['statusMessage'] = $statusMessage;
        $res = $this->eventsignupHandler->saveGatewayPaymentResponse($gatewayInsert);
        
        $thirdPartyUpdateArray=array();
        $thirdPartyUpdateArray['transactionid']=$eventSignupId;
        $thirdPartyUpdateArray['status']="1";//sucessfull transaction
        $thirdPartyUpdateArray['referenceid']=$inputArray['client_reference_no'];
        $this->updateThirdPartyTicketSoldCount($thirdPartyUpdateArray);
       
        $this->updateThirdPartyPayment($thirdPartyUpdateArray);

        if (isset($inputArray['returntourl']) && $inputArray['returntourl'] != '') {
            $returnUrl = $inputArray['returntourl'];
            $returnUrl .= '?status=' . $stat . '&reference_no=' . $eventSignupId . '&ME_reference_no=' . $eventSignupId;
            header("Location: " . $returnUrl);
            exit;
        }
        print_r($inputArray);exit;
    }

}
