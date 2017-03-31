<?php

/**
 * Print Pass logic will be defined in this class
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
require_once (APPPATH . 'handlers/user_handler.php');
require_once (APPPATH . 'handlers/eventsignup_handler.php');

class Printpass_handler extends Handler {

    var $ci;
    var $eventsignupHandler;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->eventsignupHandler = new Eventsignup_handler();
    }

    public function getUserEventsignup($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventsignupId', 'Registration Number', 'is_natural_no_zero|required_strict');
        if (isset($inputArray['userEmail'])) {
            $this->ci->form_validation->set_rules('userEmail', 'User Email', 'valid_email|required_strict');
        }
        if (!empty($inputArray) && $this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
            return $output;
        }
        $eventSignupDetails = $this->eventsignupHandler->getEventSignupData($inputArray);
        if (count($eventSignupDetails) > 0) {
            $userId = $eventSignupDetails['response']['eventSignupData'][0]['userid'];
            $userInputArray['userIdList'] = array($userId);
            $userHandler = new User_handler();
            $userDetails = $userHandler->getUserDetails($userInputArray);
            if ($userDetails['response']['total'] > 0) {
                $email = $userDetails['response']['userData'][0]['email'];
                $userId = $userDetails['response']['userData'][0]['id'];
                if ($email == $inputArray['userEmail']) {
                    $eventsignupArray['eventsignupId'] = $inputArray['eventsignupId'];
                    $eventsignupArray['userId'] =$userId;
                    $eventsignupData = $this->eventsignupHandler->getEventsignupDetailData($eventsignupArray);
                    if ($eventsignupData && count($eventsignupData) > 0) {
                        return $eventsignupData;
                    } else {
                        $output['status'] = TRUE;
                        $output['response']['messages'][] = ERROR_NO_RECORDS;
                        $output['response']['total'] = 0;
                        $output['statusCode'] = STATUS_OK;
                        return $output;
                    }
                }
            } else {
                return $userDetails;
            }
        } else {
            return $eventSignupDetails;
        }
    }

}
