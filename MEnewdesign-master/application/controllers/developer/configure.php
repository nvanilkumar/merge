<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once(APPPATH . 'libraries/REST_Controller.php');
require_once(APPPATH . 'handlers/developer_handler.php');

class Configure extends REST_Controller {

    var $configureHandler;

    public function __construct() {
        parent::__construct();
        $this->developerHandler = new Developer_handler();
    }

    /*
     * Function to get the custom fields of an event or order
     *
     * @access	public
     * @param	$inputArray contains
     * 				Either eventId - integer
     * 				Or orderId - integer are required
     * 				collectMultipleAttendeeInfo - 1 or 0
     * 				customFieldId - integer
     * 				ticketid - integer
     * @return	array
     */

    public function getCustomFields_post() {
        $inputArray = $this->input->post();
        $resultArray = $this->developerHandler->getCustomFieldForms($inputArray);
        $this->response($resultArray, $resultArray['statusCode']);
         
    }

    /*
     * Function to get the custom field values of an custom field
     *
     * @access	public
     * @param	$inputArray contains
     * 				customFieldId - integer
     * @return	array
     */

    public function getCustomFieldValues_post() {
        $inputArray = $this->input->post();
        $customFieldsList = $this->developerHandler->getCustomFieldValues($inputArray);
        $resultArray = array('response' => $customFieldsList['response']);
        $statusCode = $customFieldsList['statusCode'];
        $this->response($resultArray, $statusCode);
    }

}

?>