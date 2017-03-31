<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require(APPPATH . 'libraries/REST_Controller.php');

class Common_requests extends REST_Controller {

    public $response, $data = array('status' => false);

    public function __construct() {
        parent::__construct();
        $this->load->helper('cookie');
    }

    public function processRequest_post() {
        $input = $this->input->post();
        $functionName = $input['call'];
        if (function_exists($this->$functionName($input))) {
            $this->$functionName($input);
        } else {
            $this->data['response']['message'] = 'No function';
            $this->response($this->data);
        }
    }

    private function updateCookie($inputArray) {
        $cookieNames = ($inputArray['cookieName']);
        $cookieValues = ($inputArray['cookieValue']);
        $this->data['response']['message'] = 'Failed setting cookie';
        $updatedCookies = array();
        if (is_array($cookieNames) && count($cookieNames) == count($cookieValues)) {
            foreach ($cookieNames as $key => $cookieName) {
                if (!empty($cookieName) && $cookieValues[$key] >= 0) {
                    $cookie = array('name' => $cookieName, 'value' => $cookieValues[$key], 'expire' => COOKIE_EXPIRATION_TIME);
                    $this->input->set_cookie($cookie);
                    if ($cookieName == 'countryId') {
                        $cookieCity = array('name' => 'cityId', 'value' => 0, 'expire' => COOKIE_EXPIRATION_TIME);
                        $this->input->set_cookie($cookieCity);
                        $cookieCategory = array('name' => 'categoryId', 'value' => 0, 'expire' => COOKIE_EXPIRATION_TIME);
                        $this->input->set_cookie($cookieCategory);
                        $cookieCustomFilter = array('name' => 'CustomFilter', 'value' => 6, 'expire' => COOKIE_EXPIRATION_TIME);
                        $this->input->set_cookie($cookieCustomFilter);
                    }
                    $updatedCookies[] = $cookieName;
                }else{
                	$cookie = array('name' => $cookieName, 'value' => '', time()-3600);
                	$this->input->set_cookie($cookie);
                }
            }
            $this->data['status'] = TRUE;
            $this->data['response']['message'] = 'Successfully updated';
            $this->data['response']['types'] = $updatedCookies;
            // }
            $this->response($this->data);
        }
    }

    public function updateCookie_post() {
        $inputArray = $this->input->post();
        $cookieNames = ($inputArray['cookieName']);
        $cookieValues = ($inputArray['cookieValue']);
        $this->data['response']['message'] = 'Failed setting cookie';
        $statusCode=504;
        $updatedCookies = array();
        if (is_array($cookieNames) && count($cookieNames) == count($cookieValues)) {
            foreach ($cookieNames as $key => $cookieName) {
                if (!empty($cookieName) && $cookieValues[$key] >= 0) {
                    $cookie = array('name' => $cookieName, 'value' => $cookieValues[$key], 'expire' => COOKIE_EXPIRATION_TIME);
                    $this->input->set_cookie($cookie);
                    if ($cookieName == 'countryId') {
                        $cookieCity = array('name' => 'cityId', 'value' => 0, 'expire' => COOKIE_EXPIRATION_TIME);
                        $this->input->set_cookie($cookieCity);
                        $cookieCategory = array('name' => 'categoryId', 'value' => 0, 'expire' => COOKIE_EXPIRATION_TIME);
                        $this->input->set_cookie($cookieCategory);
                        $cookieCustomFilter = array('name' => 'CustomFilter', 'value' => 6, 'expire' => COOKIE_EXPIRATION_TIME);
                        $this->input->set_cookie($cookieCustomFilter);
                    }
                    if(isset($inputArray['footerCategory']) && $inputArray['footerCategory']) {
                        $cookieCategory = array('name' => 'subCategoryId', 'value' => 0, 'expire' => COOKIE_EXPIRATION_TIME);
                        $this->input->set_cookie($cookieCategory);
                        $cookieCategory = array('name' => 'subCategoryName', 'value' => '', 'expire' => COOKIE_EXPIRATION_TIME);
                        $this->input->set_cookie($cookieCategory);
                    }
                    $updatedCookies[] = $cookieName;
                }
            }
            $this->data['status'] = TRUE;
            $this->data['response']['message'] = 'Successfully updated';
            $this->data['response']['types'] = $updatedCookies;
            $statusCode=200;
            $this->response($this->data,$statusCode);
        }
    }

}
