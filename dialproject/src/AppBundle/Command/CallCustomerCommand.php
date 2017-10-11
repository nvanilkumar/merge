<?php

/*
 * @Description : Campaign cron - 
 * @Author : Upendra Kumar (CES) 
 */

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use PAMI\Message\Action\OriginateAction;
use PAMI\Message\Action\StatusAction;
use Pest;
use ZMQContext;
use ZMQ;

class CallCustomerCommand extends ContainerAwareCommand
{

    protected $em;
    private $container;

    public function __construct($em, Container $container)
    {
        $this->em = $em;
        $this->container = $container;
        parent::__construct();
    }

    protected function configure()
    {
        $this
                ->setName('call:customer')
                ->setDescription('CallCustomer Command')
                // configure an argument
                ->addArgument('phonenumber', InputArgument::REQUIRED, 'Phonenumber of the customer')
                ->addArgument('campaignId', InputArgument::REQUIRED, 'CampaignId of the customer')
                ->addArgument('campaignDataId', InputArgument::REQUIRED, 'CampaignDataId of the customer');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $text = "CallCustomer Command Started.";
        $output->writeln($text);

        $phonenumber = $input->getArgument('phonenumber');
        $campaignId = $input->getArgument('campaignId');
        $campaignDataId = $input->getArgument('campaignDataId');

        $text = "Calling the Number :" . $phonenumber;
        $output->writeln($text);

        $amihost = $this->container->getParameter('amihost');
        $amiuser = $this->container->getParameter('amiuser');
        $amipassword = $this->container->getParameter('amipassword');
        $options = array(
            'host' => $amihost,
            'scheme' => 'tcp://',
            'port' => 5038,
            'username' => $amiuser,
            'secret' => $amipassword,
            'connect_timeout' => 10,
            'read_timeout' => 10000000
        );

        $setAccount = $campaignId . "@" . $phonenumber;

        $sipNumber = 14085121994;

        $apiuri = $this->container->getParameter('apiuri');
        $pest = new Pest($apiuri);


        //Inform the redis server
        $data = array("dialerId" => $setAccount, "type" => "setValue");
        //$this->connectWebServer("redis_called_customers", $data);
        $thing = $pest->post('/api/v1/redis_called_customers', $data);


        $client = new \PAMI\Client\Impl\ClientImpl($options);
        $client->open();
        $actionid = md5(uniqid());
        $response = $client->send(new StatusAction());
        $originateMsg = new OriginateAction('SIP/' . $phonenumber . "@voipgw");
        $originateMsg->setContext('dialer');
        $originateMsg->setPriority('1');
        $originateMsg->setExtension($sipNumber);
        $originateMsg->setCallerId($sipNumber);
        $originateMsg->setAsync(false);
        $originateMsg->setActionID($actionid);
        $originateMsg->setTimeout(30000);
        $originateMsg->setVariable("c_id", $campaignId);
        $originateMsg->setVariable("cd_id", $campaignDataId);
        $originateMsg->setVariable("phonenumber", $phonenumber);
        $originateMsg->setAccount($setAccount, $phonenumber);
        $orgresp = $client->send($originateMsg);
        // $this->var_error_log($originateMsg);
        $orgStatus = $orgresp->getKeys()['response'];



        $this->var_error_log($orgresp);
        if ($orgStatus != "Success") {

            //Remove the redis value
            $data = array("dialerId" => $setAccount, "type" => "removeValue");
            $thing = $pest->post('/api/v1/redis_called_customers', $data);

            //check the max retry count related to campaign data id
            $thing = $pest->post('/api/v1/check_customer_retry_count', array(
                'campaignDataId' => $campaignDataId,
                    )
            );
            $result = json_decode($thing);
              $this->var_error_log($thing);
            if ($result->message === "notMax") 
            {
                 $this->var_error_log("Not Max");
                $thing = $pest->put('/api/v1/campaigns_data', array(
                    'customerExtn' => $phonenumber,
                    'campaignId' => $campaignId,
                    'dailStatus' => 'New Call'
                        )
                );
            }





            $thing = $pest->put('/api/v1/live_calls', array(
                'customerExtn' => $phonenumber,
                'campaignId' => $campaignId,
                'ansTime' => time(),
                'endTime' => time()
                    )
            );

            //Informing wss
            $entryData = array(
                'type' => 'call_failed',
                'customerExtn' => $phonenumber,
                'campaignId' => $campaignId,
                'reason' => 'no response'
            );
            $context = new ZMQContext();
            $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
            $socket->connect("tcp://127.0.0.1:5555");
            $socket->send(json_encode($entryData));
        }

        $text = "CallCustomer Command Ended...";
        $output->writeln($text);

        exit;
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
        $log_file_path = "/var/www/html/cesdialer/customerlog.txt";
        if (file_exists($log_file_path) == false) {
            fopen($log_file_path, "w");
        }
        error_log($contents, 3, $log_file_path);
    }

}
