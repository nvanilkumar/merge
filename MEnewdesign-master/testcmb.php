<?php


  $url = 'http://menew.com/api/postebs.php';         //URL to which data is being posted
  $returnurl="http://menew.com";
  //Return domain url
  
    $data = array("reference_no" => "sky1234_".date('Ymd'),"return_url" => $returnurl,"amount"=>"100","currency"=>"INR","email"=>"anilkumar.murikipudi@qison.com","address" => "address" ,"city" => "Hyderabad","state" => "Telangana", "name" =>  "MAK","description" =>  "third party booking","phone" => "995343434",  "postal_code" => "506319",'source'=>"cmb" );
	
//print_r($data); exit;
	
    //$data_string = json_encode($data);
	


	
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL,$url);
	# Setup request to send json via POST.
	$payload = json_encode( $data);
	//print_r($payload);
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	# Return response instead of printing.
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	# Send request.
	$result = curl_exec($ch);
	curl_close($ch);
	# Print response.
	
	
	
	echo "<pre>$result</pre>";
	
	
	
?>

