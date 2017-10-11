<?php

/*
 * To Start the Active Campains
 */

require_once 'configlocal.php';
require_once 'handler.php';

$handler = new Handler();


$customerData = $handler->connectWebServer("live_campaign_customer");
echo "\n ***********\n";
print_r($customerData);
echo "\n ***********\n";
$status = $customerData->status;
if ($status == 'success') {
    $handler->startCampaignData($customerData);
}

