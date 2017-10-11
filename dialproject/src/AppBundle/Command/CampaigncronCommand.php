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

class CampaigncronCommand extends ContainerAwareCommand {

    protected $em;
    private $container;

    public function __construct($em, Container $container) {
        $this->em = $em;
        $this->container = $container;
        parent::__construct();
    }

    protected function configure() {
        $this
                ->setName('cron:campaign')
                ->setDescription('Campaign cron');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $text = "Corn works fine.";
        $output->writeln($text);
        $connection = $this->em->getConnection();
        $todayDate = date("Y-m-d");
        $timeStamp = date("H:i:s");
        $day = " and " . strtolower(date("l")) . "='1'";
        $sql = "UPDATE campaign 
                set campaign_status = 'active'
               WHERE from_date <= '" . $todayDate . " 00:00:00' and to_date > '" . $todayDate . " 23:59:59' 
               and from_time <= '" . $timeStamp . "'" . $day . " and to_time >= '" . $timeStamp . "' 
               and is_deleted='0' and campaign_status = 'inactive' ";
        
        $sql = "UPDATE campaign 
                set campaign_status = 'active'
               WHERE from_date <= '" . $todayDate . " ".$timeStamp."' and to_date > '" . $todayDate . " ".$timeStamp."' 
              
               and is_deleted='0' and campaign_status = 'inactive' ";
$output->writeln($sql);
        $statement = $connection->prepare($sql);
        $result = $statement->execute();
        $output->writeln($result);
    }

}
