<?php

require_once 'vendor/autoload.php';
require_once 'configlocal.php';

/**
 * This class to interact with the web portal and ZMQ as per pami server request
 */
class Handler
{

    private $webObject;
    private $apiEndPoints;
    private $lastTimeStamp;
    private $commandUrl;

    public function __construct()
    {
        global $apiuri;
        global $webCommandUrl;
        $this->lastTimeStamp = new DateTime();
        $this->commandUrl = $webCommandUrl;

        $this->webObject = new Pest($apiuri); //Web portal api base url path
        $this->apiEndPoints = array(
            "campaigns_data" => array(
                "url" => "api/v1/campaigns_data",
                "type" => "put"
            ),
            "live_campaign_customer" => array(
                "url" => "api/v1/live_campaign_customer",
                "type" => "get"
            ),
            "live_calls" => array(
                "url" => "api/v1/live_calls",
                "type" => "post"
            ),
            "update_live_calls_data" => array(
                "url" => "api/v1/live_calls",
                "type" => "put"
            ),
            "campaign_next_customer" => array(
                "url" => "api/v1/campaign_next_customer",
                "type" => "post"
            ),
            "sbc_info" => array(
                "url" => "api/v1/sbc_info",
                "type" => "get"
            ),
            "campaign_running_status" => array(
                "url" => "api/v1/campaign_running_status",
                "type" => "post"
            ),
            "change_customer_answer_type" => array(
                "url" => "api/v1/change_customer_answer_type",
                "type" => "post"
            ),
            "redis_called_customers" => array(
                "url" => "api/v1/redis_called_customers",
                "type" => "post"
            ),
        );
    }

    public function setLastTimeStamp($timeStamp)
    {
        $this->lastTimeStamp = $timeStamp;
    }

    public function getLastTimeStamp()
    {
        return $this->lastTimeStamp;
    }

    /**
     * To connect to the web portal specified end point 
     * @param type $endPointName
     * @param type $data
     * @return type php object
     */
    public function connectWebServer($endPointName, $data = NULL)
    {
        $apiUrl = $this->apiEndPoints[$endPointName]["url"];
        $apiType = $this->apiEndPoints[$endPointName]["type"];
        $result = $this->webObject->$apiType($apiUrl, $data);

        return json_decode($result);
    }

    /**
     * To communicate to Web socket
     * @global type $webSocketurl
     * @param type $entryData
     */
    public function sendWebSocketMessage($entryData)
    {
        global $webSocketurl;
        $context = new ZMQContext();
        $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect($webSocketurl);
        $socket->send(json_encode($entryData));
    }

    public function onDialBegin($eventData)
    {
        $dialStringValue = $eventData->getKeys()['destaccountcode'];
    }

    /**
     * Informs the wss and insert the live call data in db
     * using event data when Dial End Event fired
     * @param type $event
     */
    public function onDialEnd($event)
    {
        $eventData = $event->getKeys();

        //Remove the customer dial string from queue
        $dialStringValue = $eventData['destaccountcode'];
        $data = array("dialerId" => $dialStringValue, "type" => "removeValue");
        $this->connectWebServer("redis_called_customers", $data);



        // echo "\n Dial end : ".$eventData['dialstatus'];
        if ($eventData['dialstatus'] == 'ANSWER') {
            $eventExtractData = $this->getEventCampaignData($dialStringValue);

            //kept the dial time in db
            if (count($eventExtractData) > 1) {
                $campaignData = array(
                    'type' => 'call_answered',
                    'customerExtn' => $eventExtractData[1], //phone number
                    'campaignId' => $eventExtractData[0], //campaing Id
                    'ansTime' => time()
                );
                $this->connectWebServer("update_live_calls_data", $campaignData);
                //update the dial status
                $this->connectWebServer("change_customer_answer_type", $campaignData);
                //inform the wss
                $this->sendWebSocketMessage($campaignData);
            }
        }
    }

    /**
     * Informs the wss and insert the live call data in db
     * using event data when Hangup Event fired
     * @param type $event
     */
    public function onHangup($event)
    {
        $eventData = $event->getKeys();
        if ($eventData['channelstatedesc'] == 'Up') {
            $dialStringValue = $eventData['accountcode'];
            $eventExtractData = $this->getEventCampaignData($dialStringValue);

            //kept the dial time in db
            if (count($eventExtractData) > 1) {
                $campaignData = array(
                    'type' => 'call_ended',
                    'customerExtn' => $eventExtractData[1], //phone number
                    'campaignId' => $eventExtractData[0], //campaing Id
                    'endTime' => time()
                );
                $this->connectWebServer("update_live_calls_data", $campaignData);
                //inform the wss
                $this->sendWebSocketMessage($campaignData);
            }
        }
    }

    /**
     * Takes the dial string returns the campaign id & customer phone number array
     * @param type $dialstring
     * @return type
     */
    public function getEventCampaignData($dialstring)
    {
        $list = explode("@", $dialstring);
        return $list;
    }

    /**
     * To call the campaign related customer
     * changes the dial status, insert the live data table and inform the wss
     * @param type $customerData
     */
    public function startCampaignData($customerData)
    {
        echo "*********************Sending Call To POPEN : " . $customerData->customer->phone_number;


        $phoneNumber = $customerData->customer->phone_number;
        $campaignId = $customerData->customer->campaign_id;
        $campaignDataId = $customerData->customer->cd_id;

        $campaignData = array(
            'type' => 'call_initiated',
            'customerExtn' => $customerData->customer->phone_number,
            'campaignId' => $customerData->customer->campaign_id,
            'dailStatus' => 'Busy'
        );
        //set the dail status
        $this->connectWebServer("campaigns_data", $campaignData);
        //insert into live calls table
        $this->connectWebServer("live_calls", $campaignData);

        $commandName = $this->commandUrl . " call:customer";
        echo "\n ####start call :" . date("h:i:s");
        $handle = popen('php ' . $commandName . "  " . $phoneNumber . " " . $campaignId . " " . $campaignDataId, 'r');
        echo "\n end call :" . date("h:i:s:") . "\n";
        $this->checkAvayaStatus();
        //Inform the websocket
        $this->sendWebSocketMessage($campaignData);

        usleep(1000000); //sleep for 1 sec
        //echo "\n end call :".date("h:i:s")."\n";
        //get the next customer
        $this->getNextCampaignCustomer($campaignData);
    }

    /**
     * Get the next customer related to specified campaign
     * @param type $campaignData
     */
    public function getNextCampaignCustomer($campaignData)
    {
        //check the agent availability on avaya sbc side
        if (!$this->checkAvayaStatus()) {
            echo "No agents available on sbc side";
            $this->connectWebServer("campaign_running_status", $campaignData);
        } else {
            $customerData = $this->connectWebServer("campaign_next_customer", $campaignData);
            $status = $customerData->status;
            if ($status == 'success') {
                $this->startCampaignData($customerData);
            }
        }
    }

    /**
     * Checks the avaya sbc Agents status
     * If no agents are available returns false
     * @return boolean
     */
    public function checkAvayaStatus()
    {
        usleep(1000000);
        $sbcData = $this->connectWebServer("sbc_info");
        $sbcStatus = $sbcData->status;
        if ($sbcStatus == 'success') {
            $allAgents = intval($sbcData->noOfAgents);
            $busyAgents = intval($sbcData->busyAgents);
            $dialUsersCount = json_decode($sbcData->dialUsersCount);
            $avilableAgents = $allAgents - ($busyAgents + count($dialUsersCount));

            echo "\n All Agents: " . $allAgents . "@ Busy Agents:" . $busyAgents . "@ AvilableAgents :" . $avilableAgents . "\n";
            echo "\n Dialed Users count:" . count($dialUsersCount) . "\n";
            print_r($dialUsersCount);
            if ($avilableAgents > 0) {
                return true;
            }
        }
        return false;
    }

    public function callPrePostCampaignTasks()
    {
        $timeDifference = $this->getTimeDiffrenceInMinutes();
        if ($timeDifference > 1) {
            //call the
            $campaignActivecommandPath = $this->commandUrl . " cron:campaign";
            $postCampaigncommandPath=$this->commandUrl. " cron:postcampaign";

            $handle = popen('php ' . $campaignActivecommandPath, 'r');
            $handle2 = popen('php ' . $postCampaigncommandPath, 'r');
            
            $this->setLastTimeStamp(new DateTime());
        }
    }

    /**
     * To retrieve  the stored time and current time difference
     * @return type difference in minutes
     */
    public function getTimeDiffrenceInMinutes()
    {
        $currentTime = new DateTime();
        $interval = $currentTime->diff($this->getLastTimeStamp());

        $hours = $interval->format('%h');
        $minutes = $interval->format('%i');
        return $hours * 60 + $minutes;
    }

}
