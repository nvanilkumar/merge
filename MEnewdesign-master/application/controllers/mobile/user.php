<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once (APPPATH . 'libraries/REST_Controller.php');
require_once (APPPATH . 'handlers/user_handler.php');

class User extends REST_Controller {

    public $userHandler;

    public function __construct() {
        parent::__construct();
        $this->userHandler = new User_handler();
    }

    public function login_post() {
        $inputArray = $this->post();
        $loginResponse = $this->userHandler->login_mobile($inputArray);
        $resultArray = array('response' => $loginResponse['response']);
        $this->response($resultArray, $loginResponse['statusCode']);
    }

    public function logout_post() {

        $this->loginCheck();
        $inputArray = $this->post();
        $loginResponse = $this->userHandler->logout($inputArray);
        $resultArray = array('response' => $loginResponse['response']);
        $this->response($resultArray, $loginResponse['statusCode']);
    }

    function resendActivationLink_post() {//user acttivation link
        $inputArray = $this->post();
        $success = $this->userHandler->resendActivationLink_mobile($inputArray);
        $resultArray = array('response' => $success['response']);
        $this->response($resultArray, $success['statusCode']);
    }

    public function signup_post() {

        $inputArray = $this->post();
        $signupResponse = $this->userHandler->signup_mobile($inputArray);
        $resultArray = array('response' => $signupResponse['response']);
        $this->response($resultArray, $signupResponse['statusCode']);
    }

    //Reset password api
    public function resetPassword_post() {
        $inputArray = $this->post();
        $resetPasswordResponse = $this->userHandler->resetPassword_mobile($inputArray);
        unset($resetPasswordResponse['response']['total']);
        $resultArray = array('response' => $resetPasswordResponse['response']);
        $this->response($resultArray, $resetPasswordResponse['statusCode']);
    }

    //Change password api
    public function changePassword_post() {
        $inputArray = $this->post();
        $changePasswordResponse = $this->userHandler->changePassword($inputArray);
        unset($changePasswordResponse['response']['total']);
        $resultArray = array('response' => $changePasswordResponse['response']);
        $this->response($resultArray, $changePasswordResponse['statusCode']);
    }

    public function signupEmailCheck_post() {
        $inputArray = $this->post();
        $response = $this->userHandler->signupEmailCheck($inputArray);
        $resultArray = array('response' => $response['response']);
        $this->response($resultArray, $response['statusCode']);
    }

    /*
     * Function to login the user as Guest
     *
     * @access	public
     * @param
     *      	`userEmail` - Mandatory
     * @return	starts the session with the user input
     */

    public function userGuestLogin_post() {

        $inputArray = $this->post();
        $response = $this->userHandler->userGuestLogin($inputArray);
        $resultArray = array('response' => $response['response']);
        $this->response($resultArray, $response['statusCode']);
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
