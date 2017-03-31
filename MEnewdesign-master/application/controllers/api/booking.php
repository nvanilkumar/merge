<?php

/**
 * To save the booking related data
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     11-08-2015
 * @Last Modified On  11-08-2015
 * @Last Modified By  Gautam
*/

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
	
require_once(APPPATH . 'libraries/REST_Controller.php');

require_once(APPPATH . 'handlers/booking_handler.php');
require_once(APPPATH . 'handlers/paytm_handler.php');

class Booking extends REST_Controller {
	
	var $attendeeHandler;
	var $fileHandler;
	var $attendeeDetailHandler;
	var $configureHandler;

    public function __construct() {
        parent::__construct();
        $this->bookingHandler = new Booking_handler();
		
		$this->load->library('form_validation');
    }

    /*
     * Api to save the booking data
     *
     * @access	public
     * @param
     *      	All the POST & FILE data that came from custom fields,tickts
     *      	ticketArr - array will be in `array(ticketId => ticketCount)` formet
     * @return	gives the json response regards the saving signup data
     */
	public function saveData_post() {
		
		$postArray = $this->input->post();
		$fileArray = $_FILES;
		$inputArray = array_merge($postArray,$fileArray);
		
		$bookingResponse = $this->bookingHandler->saveBookingData($inputArray);
		
		$resultArray = $bookingResponse;
        $statusCode = $bookingResponse['statusCode'];
        $this->response($resultArray, $statusCode);
	}
	
	/*
     * Api to save the booking data from "Mobile"
     *
     * @access	public
     * @param
     *      	All the POST & FILE data that came from custom fields,tickts
     *      	ticketArr - array will be in `array(ticketId => ticketCount)` formet
     * @return	gives the json response regards the saving signup data
     */
	public function saveMobileAttendeeData_post() {
		
		$postArray = $this->input->post();
		$fileArray = $_FILES;
		$inputArray = array_merge($postArray,$fileArray);
		$inputArray['isMobile'] = TRUE;
		
		$bookingResponse = $this->bookingHandler->saveBookingData($inputArray);
		
		$resultArray = $bookingResponse;
        $statusCode = $bookingResponse['statusCode'];
        $this->response($resultArray, $statusCode);
	}
	
	/*
     * Function to save the gateway data of the booking from mobile
     *
     * @access	public
     * @param
     *      	eventSignupId - integer
     *      	orderId - string
     *      	paymentGatewayId - integer
     * @return	gives the response status based on saving the gateway adding
     */
	public function saveMobileGatewayData_post() {
		
		$postArray = $this->input->post();
		$bookingResponse = $this->bookingHandler->saveGatewayData($postArray);
		
		$resultArray = $bookingResponse;
        $statusCode = $bookingResponse['statusCode'];
        $this->response($resultArray, $statusCode);
	}
	
    public function offlineBooking_post() {
        $inputArray = $this->input->post();
        $response = $this->bookingHandler->offlineBooking($inputArray);
        $statusCode = $response['statusCode'];
        $this->response($response, $statusCode);
    }
	
	
	/*
     * Function to create checksum for paytm
     *
     * @access	public
     * @param
     * 			ORDER_ID
     * 			TXN_AMOUNT
     *      	
     * @return	gives the checksum as response
     */
	public function createPaytmChecksum_post() {
        $this->paytmHandler = new Paytm_handler();
        $inputArray = $this->input->post();
        $response = $this->paytmHandler->createChecksum($inputArray);
        echo $response;exit;
    }

	/*
     * Function to validate the checksum that comes from paytm
     *
     * @access	public
     * @param
     * 			All post variables comes from paytm
     *      	
     * @return	gives the validation status of checksum
     */
    public function validatePaytmChecksum_post() {

        $this->paytmHandler = new Paytm_handler();
        $inputArray = $this->input->post();
        $response = $this->paytmHandler->validateChecksum($inputArray);
        $data['encodedJson'] = $response;
        $this->load->view('payment/paytm_validation_checksum', $data);
    }
	
	/*
     * Function to create checksum for mobikwik
     *
     * @access	public
     * @param
     * 			ORDER_ID
     * 			TXN_AMOUNT
     *      	
     * @return	gives the checksum as response
     */
	public function createMobikwikChecksum_post() {
			
        $inputArray = $this->post();
        $response = $this->bookingHandler->createMobikwikChecksum($inputArray);
        if(!$response['status']) {
            $resultArray = array('response' => $response['response']);
            $this->response($resultArray, $response['statusCode']);
        }
		header('Content-Type: text/xml');
        $string = '<?xml version="1.0" encoding="UTF-8"?>
					<checksum> 
						<status>SUCCESS</status>
						<checksumValue>'.$response['response']['checkSum'].'</checksumValue> 
				   </checksum>';
        echo $string;exit;
    }

	/*
     * Function to validate checksum for mobikwik
     *
     * @access	public
     * @param
     * 			All post variables comes from mobikwik response
     *      	
     * @return	gives the status of the checksum
     */
    public function validateMobikwikChecksum_post() {
	
        $inputArray = $this->post();
        $inputArray['paymentGatewayKey'] = $this->input->get_post('paymentGatewayKey');
        $response = $this->bookingHandler->validateMobikwikChecksum($inputArray);
        if(!$response['status']) {
            $resultArray = array('response' => $response['response']);
            $this->response($resultArray, $response['statusCode']);
        }
        $responseData = $response['response']['responseData'];
		header('Content-Type: text/xml');
        $string = '<?xml version="1.0" encoding="UTF-8"?>
            <paymentResponse> 
                <orderid>'.$responseData['orderId'].'</orderid>
                <amount>'.$responseData['amount'].'</amount> 
                <status>' .$responseData['status']. '</status>
                <statusMsg>'. $responseData['statusMsg'] .'</statusMsg>
            </paymentResponse>';    
        echo $string;exit;
    }
	
	/*
     * Function to save the payment response data for Paytm
     *
     * @access	public
     * @param
     *      	eventSignup - integer
     *      	orderId - string
     *      	paymentGatewayId - integer
     *      	email - string
     *      	mobile - integer
     *      	POST variables those are coming from paytm response
     * @return	gives the response status based on saving the payment response
     */
	public function updatePaytmResponse_post() {
		
		$postArray = $this->input->post();
		$bookingResponse = $this->bookingHandler->paytmProcessingApi($postArray);
		
		$resultArray = $bookingResponse;
        $statusCode = $bookingResponse['statusCode'];
        $this->response($resultArray, $statusCode);
	}
	
	/*
     * Function to save the payment response data for Mobikwik
     *
     * @access	public
     * @param
     *      	orderId - string
     *      	paymentGatewayId - integer
     *      	POST variables those are coming from paytm response
     * @return	gives the response status based on saving the payment response
     */
	public function updateMobikwikResponse_post() {
		
		$postArray = $this->input->post();
		$postArray['paymentGatewayKey'] = $this->input->get_post('paymentGatewayKey');
		$postArray['isMobile'] = true;
		$bookingResponse = $this->bookingHandler->mobikwikProcessingApi($postArray);
		
		$resultArray = $bookingResponse;
        $statusCode = $bookingResponse['statusCode'];
        $this->response($resultArray, $statusCode);
	}
	
	/*
     * Function to save the payment response data for EBS
     *
     * @access	public
     * @param
     *      	orderId - string
     *      	paymentGatewayId - integer
     *      	POST variables those are coming from paytm response
     * @return	gives the response status based on saving the payment response
     */
	public function updateEbsResponse_post() {
		
		$postArray = $this->input->post();
		$postArray['paymentGatewayKey'] = $this->input->get_post('paymentGatewayKey');
		$bookingResponse = $this->bookingHandler->ebsProcessingApi($postArray);
		
		$resultArray = $bookingResponse;
        $statusCode = $bookingResponse['statusCode'];
        $this->response($resultArray, $statusCode);
	}
}
