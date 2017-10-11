<?php

require_once 'vendor/autoload.php';
require_once 'configlocal.php';
require_once 'handler.php';

use PAMI\Client\Impl\ClientImpl;
use PAMI\Listener\IEventListener;
use PAMI\Message\Event\EventMessage;

class PamiEventListener implements IEventListener
{

    public function __construct($cli)
    {
        $this->cli = $cli;
        $this->eventHandler = new Handler();
    }

    /**
     * To Handle the All Pami Events
     * @param EventMessage $event
     */
    public function handle(EventMessage $event)
    {

        $strevt = $event->getKeys()['event'];
        $this->var_error_log($event->getKeys());
        
        if ($strevt == 'DialBegin') {
            $this->eventHandler->onDialBegin($event);
        }
        if ($strevt == 'DialEnd') {
            $this->eventHandler->onDialEnd($event);
        }

        if ($strevt == 'Hangup') {
            $this->eventHandler->onHangup($event);
        }
    }
    
    public function var_error_log($object = null)
    {
        $datetime = date("Y-m-d h:i:s a");

        $contents = PHP_EOL . $datetime . " :";
        ob_start();                    // start buffer capture
        var_dump($object);           // dump the values
        $contents .= ob_get_contents(); // put the buffer into a variable
        ob_end_clean();                // end capture
        //create the log file if not exist
        $log_file_path = "/var/www/html/cesdialer/pamilog.txt";


        if (file_exists($log_file_path) == false) {
            fopen($log_file_path, "w");
        }
        error_log($contents, 3, $log_file_path);
    }

}

////////////////////////////////////////////////////////////////////////////////
// Code STARTS.
////////////////////////////////////////////////////////////////////////////////
error_reporting(E_ALL);
ini_set('display_errors', 1);
try {
    $options = array(
        'host' => $astrihost,
        'scheme' => 'tcp://',
        'port' => 5038,
        'username' => $astriuser,
        'secret' => $astripass,
        'connect_timeout' => 10,
        'read_timeout' => 10000000
    );

    $a = new ClientImpl($options);
    $handler = new Handler();
    $eventListener = new PamiEventListener($a);
    $a->registerEventListener($eventListener);
    $a->open();
    $time = time();
    
    //Reset the Dialer Count value in Redis
    $data=array("type"=>"resetValue");
    $handler->connectWebServer("redis_called_customers", $data);
    
    while (true) {//(time() - $time) < 60) // Wait for events.
        usleep(1000); // 1ms delay
        //check the Avilable Agents status
        if (!$handler->checkAvayaStatus()) {
            echo "\n No agents available on sbc side \n ";
            usleep(1000);
        } else {

            //get the customer 

            $customerData = $handler->connectWebServer("live_campaign_customer");
            // echo "\n ***********\n";
            // print_r($customerData);
            // echo "\n ***********\n";
            $status = $customerData->status;

            // $status = $customerData->status;
            if ($status == 'success') {
                $handler->startCampaignData($customerData);
            }
            
            //call the pre & post campaign crons
            $handler->callPrePostCampaignTasks();
            
        }



        $a->process();
    }
    $a->close(); // send logoff and close the connection.
} catch (Exception $e) {
    echo $e->getMessage() . "\n";
}
