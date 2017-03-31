<?php

/**
 * Event Gallery Data will be defined in this class
 * Getting Banners Related data
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @param		eventId - required
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0
 * @Created     21-07-2015
 * @Last Modified 21-06-2015
 */
require_once (APPPATH . 'handlers/handler.php');
require_once (APPPATH . 'handlers/booking_handler.php');
require_once (APPPATH . 'libraries/paytm/paytm_functions.php');

class Paytm_handler extends Handler {

    var $ci;
    var $orderlogHandler;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
		$this->bookingHandler = new Booking_handler();
    }

    public function createChecksum($input) {

        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0");

        $checkSum = "";
		$orderLogDetails = array();
		$paymentGatewayKey = 6;
        //Here checksum string will return by getChecksumFromArray() function.W
		$orderLogInput['orderId'] = $input["ORDER_ID"];
		$orderLogDetails = $this->bookingHandler->orderLogValidation($orderLogInput);
		if($orderLogDetails['status'] && $orderLogDetails['eventSignupData']['paymentgatewayid'] > 0) {
			$paymentGatewayKey = $orderLogDetails['eventSignupData']['paymentgatewayid'];
		}
		$gatewayData = $this->getGatewayData($paymentGatewayKey);
		
        $checkSum = getChecksumFromArray($input, $gatewayData['hashkey']);
        $paramList['CHECKSUMHASH'] = $checkSum;

        $responseData = array("CHECKSUMHASH" => $checkSum, "ORDER_ID" => $input["ORDER_ID"], "payt_STATUS" => "1");
		return json_encode($responseData);
    }
	
    public function validateChecksum($inputCheck) {
		
        $paytmChecksum = "";
		$paramList = array();
		$orderLogDetails = array();
		$isValidChecksum = FALSE;
		$paymentGatewayKey = 6;
		
		$paramList = $inputCheck;
		$return_array = $inputCheck;
		$paytmChecksum = isset($paramList["CHECKSUMHASH"]) ? $paramList["CHECKSUMHASH"] : ""; //Sent by Paytm pg
		
		$orderLogInput['orderId'] = $inputCheck["ORDERID"];
		$orderLogDetails = $this->bookingHandler->orderLogValidation($orderLogInput);
		if($orderLogDetails['status'] && $orderLogDetails['eventSignupData']['paymentgatewayid'] > 0) {
			$paymentGatewayKey = $orderLogDetails['eventSignupData']['paymentgatewayid'];
		}
		
		$gatewayData = $this->getGatewayData($paymentGatewayKey);
		
		//Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application’s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
		$isValidChecksum = verifychecksum_e($paramList, $gatewayData['hashkey'], $paytmChecksum); //will return TRUE or FALSE string.
		
		if ($isValidChecksum === TRUE) {
			$return_array["IS_CHECKSUM_VALID"] = "Y";
		} else {
			$return_array["IS_CHECKSUM_VALID"] = "N";
		}
		
		$return_array["TXNTYPE"] = "";
		$return_array["REFUNDAMT"] = "";
		unset($return_array["CHECKSUMHASH"]);
		$encoded_json = htmlentities(json_encode($return_array));
		return $encoded_json;
    }
	
	public function getGatewayData($paymentGatewayKey) {
		
		$gatewayArr = array();
		if ($paymentGatewayKey > 0) {
            $gatewayArr = $this->bookingHandler->getPaymentgatewayKeys($paymentGatewayKey);
            if (count($gatewayArr) > 0) {
                $paytmSecretKey = $gatewayArr['hashkey'];
                $paytmMerchantId = $gatewayArr['merchantid'];

                $extraParams = unserialize($gatewayArr['extraparams']);
                define('PAYTM_MERCHANT_KEY', $paytmSecretKey);
                define('PAYTM_MERCHANT_MID', $paytmMerchantId);

                define('PAYTM_MERCHANT_WEBSITE', $extraParams['PAYTM_MERCHANT_WEBSITE']);
                define('INDUSTRY_TYPE_ID', $extraParams['INDUSTRY_TYPE_ID']);
                define('CHANNEL_ID', $extraParams['CHANNEL_ID']);
            }
        }
		return $gatewayArr;
	}
}
?>