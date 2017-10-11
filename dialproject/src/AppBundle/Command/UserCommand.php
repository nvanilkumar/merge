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

class UserCommand extends ContainerAwareCommand
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
                ->setName('user:create')
                ->setDescription('create user Command');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        echo "111";
        
        // outputs a message followed by a "\n"
        $output->writeln('Whoa!');
        
         $entryData = array(
                'type' => 'call_failed',
                
                'customerphone' =>1000,
                'campaign_id' => 40,
                'reason' => 'no response'
            );
			print_r($entryData );
            $context = new ZMQContext();
            $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
            $socket->connect("tcp://139.59.73.111:5555");
            $socket->send(json_encode($entryData));
        
        
        $pest = new Pest("http://139.59.73.111/cesdialer/web/app.php/api/v1/");
            $thing = $pest->put('campaigns_data', array(
               
                'customerExtn' => 27832783457,
                'campaignId' => 40,
                'dailStatus' => 'New Call'
                    )
            );
            print_r($thing);
             $output->writeln('end!');
    }

}
