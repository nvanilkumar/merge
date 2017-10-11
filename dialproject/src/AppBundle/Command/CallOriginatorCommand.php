<?php

/*
 * @Description : Campaign cron - 
 * @Author : Upendra Kumar (CES) 
 */

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use PAMI\Message\Event\DialEvent;
use PAMI\Message\Action\SIPShowRegistryAction;
use PAMI\Message\Action\PingAction;
use PAMI\Message\Action\OriginateAction;
use PAMI\Message\Action\EventsAction;
use PAMI\Message\Action\SIPNotifyAction;
use PAMI\Message\Action\StatusAction;

class CallOriginatorCommand extends ContainerAwareCommand {

    protected $em;
    private $container;

    public function __construct($em, Container $container) {
        $this->em = $em;
        $this->container = $container;
        parent::__construct();
    }

    protected function configure() {
        $this
                ->setName('cron:calloriginator')
                ->setDescription('Calloriginator cron');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $text = "Corn works fine.";
        $output->writeln($text);
        $connection = $this->em->getConnection();

        $todayDate = date("Y-m-d");
        $timeStamp = date("H:i:s");
        $day = " and " . strtolower(date("l")) . "='1'";
        $sql = "SELECT c.campaign_id,u.user_id , u.extension 
               FROM campaign as c 
               join campaign_agents as ca on ca.campaign_id =c.campaign_id
               join user  as u on u.user_id = ca.user_id 
               join campaign_type as ct on ct.ct_id=c.ct_id
               WHERE c.from_date <= '" . $todayDate . " 00:00:00' and c.to_date > '" . $todayDate . " 23:59:59' 
               and c.from_time <= '" . $timeStamp . "'" . $day . " and c.to_time >= '" . $timeStamp . "' 
               and c.campaign_status='active' and c.is_deleted='0' and u.astrisk_login = 0 ";
        
        $sql = "SELECT c.campaign_id,u.user_id , u.extension 
               FROM campaign as c 
               join campaign_agents as ca on ca.campaign_id =c.campaign_id
               join user  as u on u.user_id = ca.user_id 
               join campaign_type as ct on ct.ct_id=c.ct_id and c.ct_id = 2
               WHERE c.from_date <= '" . $todayDate . " 00:00:00' and c.to_date > '" . $todayDate . " 23:59:59' 
               
               and c.campaign_status='active' and c.is_deleted='0' and c.is_complete = 0 and u.astrisk_login = 0 ";

        $statement = $connection->prepare($sql);
        $statement->execute();
        $data = $statement->fetchAll();
       // print('<pre>');
       // print_r($data);
       // print('</pre>');
       // exit;
        foreach ($data as $campaign) {

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
            $client = new \PAMI\Client\Impl\ClientImpl($options);
            $client->open();
            $actionid = md5(uniqid());
            $response = $client->send(new StatusAction());
            $originateMsg = new OriginateAction('SIP/' . $campaign['extension']);
            $originateMsg->setContext('scopPBX_incoming');
            $originateMsg->setPriority('1');
            $originateMsg->setExtension($campaign['extension']);
            $originateMsg->setCallerId($campaign['extension']);
            $originateMsg->setAsync(false);
            $originateMsg->setActionID($actionid);
            $orgresp = $client->send($originateMsg);
            //$notify = new SIPNotifyAction('marcelog');
            //$notify->setVariable('a', 'b');
            // $response = $client->send($notify);
            //print('<pre>');
           // print_r($orgresp);
           // print('</pre>');
            sleep(5);
            // 
            echo "Called agent  : ".$campaign['extension']."\n";
        }
        exit;


        $output->writeln($result);
    }

}
