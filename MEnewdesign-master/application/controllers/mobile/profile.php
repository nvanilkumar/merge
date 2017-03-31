<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once (APPPATH . 'libraries/REST_Controller.php');
require_once (APPPATH . 'handlers/profile_handler.php');
require_once(APPPATH . 'handlers/city_handler.php');

class Profile extends REST_Controller {

    public $profileHandler;
    var $cityHandler;

    public function __construct() {
        parent::__construct();
        $this->profileHandler = new Profile_handler();
        $this->cityHandler = new City_handler();
    }

    public function viewProfile_post() {
        $this->loginCheck();
        //$inputArray = $this->get();
        $userInfoResponse = $this->profileHandler->getUserDetails();
        $resultArray = array('response' => $userInfoResponse['response']);
        $this->response($resultArray, $userInfoResponse['statusCode']);
    }

    public function edit_post() {

        $this->loginCheck();
        $userInfo = $this->post();
        $editProfileResponse = $this->profileHandler->updateUserProfile_mobile($userInfo);
        $resultArray = array('response' => $editProfileResponse['response']);
        $this->response($resultArray, $editProfileResponse['statusCode']);
    }

    public function changePassword_post() {

        $this->loginCheck();

        $inputArray = $this->post();
        $response = $this->profileHandler->profileChangePassword_mobile($inputArray);
        $resultArray = array('response' => $response['response']);
        $this->response($resultArray, $response['statusCode']);
    }

    public function getUserTicketList_post() {

        $this->loginCheck();

        $inputArray = $this->post();
        $UserticketDetails = $this->profileHandler->getUserTicketList_mobile($inputArray);
        $resultArray = array('response' => $UserticketDetails['response']);
        $this->response($resultArray, $UserticketDetails['statusCode']);
    }

    /*
     * Function to upload the profile image
     *
     * @access	public
     * @param
     *      	Uploaded image data as input
     * @return	gives the response with updated profile image path
     */

    function editProfileImage_post() {

        $this->loginCheck();

        $editProfileResponse = $this->profileHandler->updateProfileImage_mobile();
        $resultArray = array('response' => $editProfileResponse['response']);
        $this->response($resultArray, $editProfileResponse['statusCode']);
    }

    /*
     * Function to check for logged in user
     *
     * @access	public
     * @return	json response with status and message
     */

    public function loginCheck() {

        $loginCheck = $this->customsession->loginCheck();
        if ($loginCheck != 1 && !$loginCheck['status']) {
            $output['status'] = FALSE;
            $output['response']['messages'][] = $loginCheck['response']['messages'][0];
            $output['statusCode'] = STATUS_INVALID_SESSION;

            $resultArray = array('response' => $output['response']);
            $this->response($resultArray, $output['statusCode']);
        }
    }

}
