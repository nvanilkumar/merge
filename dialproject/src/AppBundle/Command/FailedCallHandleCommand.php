<?php

/*
 * @Description : To Set the customer call status depends upon there pami response
 * @Author : Anil Kumar M (CES) 
 */

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class FailedCallHandleCommand extends ContainerAwareCommand
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
                ->setName('task:failedCallCommand')
                ->setDescription('Failed Call Handling Command')
                // configure an argument
                ->addArgument('responseCode', InputArgument::REQUIRED, 'Call Response')
                ->addArgument('campaignDataId', InputArgument::REQUIRED, 'CampaignDataId of the customer');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $text = "Failed call  Command Started.";
        $output->writeln($text);
        $connection = $this->em->getConnection();
        $responseCode = intval($input->getArgument('responseCode'));
        $uquery="ch";
        $campaignDataId = intval ($input->getArgument('campaignDataId'));

        $s=is_int($responseCode);
        $output->writeln($s);
        if (is_int($responseCode) && is_int($campaignDataId)) {

            $dailStatus = array(
                0 => 7, //Voicemail
                3 => 5, //No Answer
                5 => 6, //Unreachable
            );
            $dailStatusNumber = ($dailStatus[$responseCode])? $dailStatus[$responseCode]:1;
            
            $uquery = " UPDATE campaign_data SET ds_id=" . $dailStatusNumber .
                    " WHERE retry_count=max_retry_count and cd_id=" . $campaignDataId;
            $connection->executeUpdate($uquery);
        }
        $text = "Failed call  Command  Ended...";
        $output->writeln($uquery);

        exit;
    }

}
