<?php
/*
//test details
$mobiwikMerchantId="MBK9002"; //test id
$mobiwikSecretKey = 'ju6tygh7u7tdg554k098ujd5468o';
*/

Class Checksum {
	static function calculateChecksum($secret_key, $all) {
		$hash = hash_hmac('sha256', $all , $secret_key);
		$checksum = $hash;
		return $checksum;
	}

	static function verifyChecksum($checksum, $all, $secret) {
		$cal_checksum = Checksum::calculateChecksum($secret, $all);
		$bool = 0;
		if($checksum == $cal_checksum)	{
			$bool = 1;
		}

		return $bool;
	}

	static function sanitizedParam($param) {
		$pattern[0] = "%,%";
	        $pattern[1] = "%#%";
	        $pattern[2] = "%\(%";
       		$pattern[3] = "%\)%";
	        $pattern[4] = "%\{%";
	        $pattern[5] = "%\}%";
	        $pattern[6] = "%<%";
	        $pattern[7] = "%>%";
	        $pattern[8] = "%`%";
	        $pattern[9] = "%!%";
	        $pattern[10] = "%\\$%";
	        $pattern[11] = "%\%%";
	        $pattern[12] = "%\^%";
	        $pattern[13] = "%=%";
	        $pattern[14] = "%\+%";
	        $pattern[15] = "%\|%";
	        $pattern[16] = "%\\\%";
	        $pattern[17] = "%:%";
	        $pattern[18] = "%'%";
	        $pattern[19] = "%\"%";
	        $pattern[20] = "%;%";
	        $pattern[21] = "%~%";
	        $pattern[22] = "%\[%";
	        $pattern[23] = "%\]%";
	        $pattern[24] = "%\*%";
	        $pattern[25] = "%&%";
        	$sanitizedParam = preg_replace($pattern, "", $param);
		return $sanitizedParam;
	}

	static function sanitizedURL($param) {
		$pattern[0] = "%,%";
	        $pattern[1] = "%\(%";
       		$pattern[2] = "%\)%";
	        $pattern[3] = "%\{%";
	        $pattern[4] = "%\}%";
	        $pattern[5] = "%<%";
	        $pattern[6] = "%>%";
	        $pattern[7] = "%`%";
	        $pattern[8] = "%!%";
	        $pattern[9] = "%\\$%";
	        $pattern[10] = "%\%%";
	        $pattern[11] = "%\^%";
	        $pattern[12] = "%\+%";
	        $pattern[13] = "%\|%";
	        $pattern[14] = "%\\\%";
	        $pattern[15] = "%'%";
	        $pattern[16] = "%\"%";
	        $pattern[17] = "%;%";
	        $pattern[18] = "%~%";
	        $pattern[19] = "%\[%";
	        $pattern[20] = "%\]%";
	        $pattern[21] = "%\*%";
        	$sanitizedParam = preg_replace($pattern, "", $param);
		return $sanitizedParam;
	}
	
	function getActionUrl() {
		
		$hostname=strtolower($_SERVER['HTTP_HOST']);
		if(strcmp($hostname,'www.meraevents.com')==0 || strcmp($hostname,'meraevents.com')==0 || strcmp($hostname,'mndprodlb-1993234352.us-west-1.elb.amazonaws.com')==0 || strcmp($hostname, "dhamaal.meraevents.com")==0|| strcmp($hostname,'mndprodlb-1993234352.us-west-1.elb.amazonaws.com')==0 || strcmp($hostname,'www.mndprodlb-1993234352.us-west-1.elb.amazonaws.com')==0)
		{
			$_SESSION['url']="https://www.mobikwik.com/checkstatus";
			$actionUrl="https://www.mobikwik.com/wallet";
		}
		else
		{
			$_SESSION['url'] = "https://test.mobikwik.com/checkstatus"; 
			$actionUrl="https://test.mobikwik.com/wallet";
		}
		return $actionUrl;
	}
	
	function calculateWalletChecksum($merchantId,$secretKey,$orderId) 
		{
			$algo = 'sha256';
			$checksum_string = "'{$merchantId}''{$orderId}'";
			$checksum =  hash_hmac($algo, $checksum_string, $secretKey);
			return $checksum;
		}
		
		// This function is used to make checksum to verify checksum received from check-status api call 
		function validateChecksumMobikwik($statuscode,$orderid,$refid,$amount,$statusmessage,$ordertype,$WorkingKey)
		{    	 
			$action = "gettxnstatus"; // fixed value
			$algo = 'sha256';
			$checksum_string = "'{$statuscode}''{$orderid}''{$refid}''{$amount}''{$statusmessage}''{$ordertype}'";    	
			$checksum = hash_hmac($algo, $checksum_string, $WorkingKey);    	    	 
			return $checksum;
		}
		
		// This function makes check-status api call // it is master function 
		function verifyTransaction($MerchantId, $OrderId , $Amount,$WorkingKey)
		{
			
		    //error_log("entered in verif function");
            $version = '2'; // version value
			$return = array();
			$checksum = $this->calculateWalletChecksum($MerchantId,$WorkingKey,$OrderId);
			
			$hostname = strtolower($_SERVER['HTTP_HOST']);
			if(strcmp($hostname,'www.meraevents.com')==0 || strcmp($hostname,'meraevents.com')==0 || strcmp($hostname,'mndprodlb-1993234352.us-west-1.elb.amazonaws.com')==0 || strcmp($hostname, "dhamaal.meraevents.com")==0 || strcmp($hostname,'mndprodlb-1993234352.us-west-1.elb.amazonaws.com')==0 || strcmp($hostname,'www.mndprodlb-1993234352.us-west-1.elb.amazonaws.com')==0)
			{
				$url = "https://www.mobikwik.com/checkstatus";
			}
			else
			{
				$url = "https://test.mobikwik.com/checkstatus";
			}
			
			$fields = "mid=$MerchantId&orderid=$OrderId&checksum=$checksum&ver=2";
			//error_log("curl check");
			// is cURL installed yet?
			if (!function_exists('curl_init')){
				die('Sorry cURL is not installed!');
			}
			// then let's create a new cURL resource handle
			$ch = curl_init();
			 
			// Now set some options (most are optional)
			 
			// Set URL to hit
			curl_setopt($ch, CURLOPT_URL, $url);
			 
			// Include header in result? (0 = yes, 1 = no)
			curl_setopt($ch, CURLOPT_HEADER, 0);
			 
			curl_setopt($ch, CURLOPT_POST, 1);
			 
			curl_setopt($ch, CURLOPT_POSTFIELDS,  $fields);
			 
			// Should cURL return or print out the data? (true = return, false = print)
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			 
			// Timeout in seconds
			curl_setopt($ch, CURLOPT_TIMEOUT, 60);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			 
			// Download the given URL, and return output
			$outputXml = curl_exec($ch);
			//error_log("excecuted");
			// Close the cURL resource, and free system resources
			curl_close($ch);
			
			$outputXmlObject = simplexml_load_string($outputXml);
			
			error_log("The response received is = " . $outputXml);
			//error_log("before function");			
			
			$recievedChecksum = $this->validateChecksumMobikwik(
													$outputXmlObject->statuscode,  
													$outputXmlObject->orderid,
													$outputXmlObject->refid,
													$outputXmlObject->amount,
													$outputXmlObject->statusmessage,
													$outputXmlObject->ordertype,
													$WorkingKey);
			//error_log("before condition");
			
			error_log("values , our order = {$OrderId} & xml order = {$outputXmlObject->orderid} , our amount = {$Amount} & xml amount = {$outputXmlObject->amount} , our checksum = {$recievedChecksum} & xml checksum = {$outputXmlObject->checksum}");
			if(($OrderId == $outputXmlObject->orderid) && ($outputXmlObject->amount == $Amount) && ($outputXmlObject->checksum == $recievedChecksum)){
                //error_log("entered in verifcation final box");
				$return['statuscode'] = $outputXmlObject->statuscode;
				$return['orderid'] 	= $outputXmlObject->orderid;
				$return['refid'] 		= $outputXmlObject->refid;
				$return['amount']	 	= $outputXmlObject->amount;
				$return['statusmessage'] 	= $outputXmlObject->statusmessage;
				$return['ordertype']		= $outputXmlObject->ordertype;
				$return['checksum']		= $outputXmlObject->checksum;
				$return['flag'] = true;
				//error_log("condition satisfy : status code = {$return['statuscode']} , orderid = {$return['orderid'] } , refid = {$return['refid']} , msg = {$return['statusmessage']} , ordertype = {$return['ordertype']}");
			}
            //\error_log("sending return = " . print_r($return));
			return $return;
		}
}
?>