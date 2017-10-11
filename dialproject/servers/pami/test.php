<?php

//require_once 'handler.php';

require_once 'vendor/autoload.php';
require_once 'configlocal.php';
error_reporting(-1);
echo " testing";

 
$pest = new Pest("http://localhost/cesdialer/web/");
$thing = $pest->post('api/v1/check_customer_retry_count', array(
    
    'campaignDataId' => 539,
    'dailStatus' => 'New Call'
        )
);
echo "tstin222";
print_r($thing);
$result=json_decode($thing);
echo "test:@@".$result->message;
print_r(json_decode($thing));
exit;


$data = array(
);
$campaignData = array(
    'type' => 'call_initiated',
    'customerExtn' => "",
    'campaignId' => 40,
    'dailStatus' => 'Busy'
);
$handler = new Handler();

//while(true){
//    $handler->callPrePostCampaignTasks();
//}
//$handler->callPrePostCampaignTasks();


//$data=$handler->connectWebServer("campaign_next_customer",$campaignData);
//print_r($data);exit;

 