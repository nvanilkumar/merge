<?php

/*
 * @Description : News letter cron - sends email to subscribers
 * @Author : Upendra Kumar (CES) 
 */

namespace AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class WorkshopremCommand extends ContainerAwareCommand {

    protected $em;
    protected $mailer;
    private $container;

    public function __construct($mailer,$em,Container $container) {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->from = "upendrakumar@cestechservices.com";
        $this->container = $container;
        parent::__construct();
    }

    protected function configure() {
        $this
                ->setName('cron:workshoprem')
                ->setDescription('Workshop rem cron')
                ->addArgument(
                        'limit'
                )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $connection = $this->em->getConnection();
        $count = 0;
        $limit = $input->getArgument('limit');
        $todays_ago = strtotime(date('Y-m-d', strtotime("1 days")));
         $todays = strtotime(date('Y-m-d 23:59:59', strtotime("+2 days")));
        if ($limit > 0) {
            $statement = $connection->prepare("  select * from dah_workshop_enrollment dwe 
                                            join dah_workshops dw on dwe.wid = dw.wid
                                            where dw.from_date >=  $todays_ago and dw.from_date <= $todays " . $limit . " ");
        } else {
            $statement = $connection->prepare(" select * from dah_workshop_enrollment dwe 
                                            join dah_workshops dw on dwe.wid = dw.wid
                                            where dw.from_date >=  $todays_ago and dw.from_date <= $todays limit 10 ");
        }
        $statement->execute();
        $subscribers = $statement->fetchAll();
        foreach ($subscribers as $sub) {


            $message = \Swift_Message::newInstance()
                    ->setSubject( 'Dar al Hekama :: Workshop remainder ' . date('d-m-Y') . ' ' . $sub['workshop_title'] . ' ')
                    ->setFrom($this->from)
                    ->setTo($sub['email'])
                    ->setBody($this->container->get('templating')->render('AdminBundle:Emails:workshopRemainder.html.twig', $sub), 'text/html');
            

            $this->mailer->send($message);

            $count++;
        }
        $text = "Total messages sent $count  ";
        $output->writeln($text);
    }

}
