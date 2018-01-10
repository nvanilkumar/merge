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

class NewsletterCommand extends ContainerAwareCommand {

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
                ->setName('cron:newsletter')
                ->setDescription('News letter cron')
                ->addArgument(
                        'limit'
                )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $connection = $this->em->getConnection();
        $count = 0;
        $limit = $input->getArgument('limit');
        if ($limit > 0) {
            $statement = $connection->prepare("  select * from dah_newsletter_message_queue dn 
                                            join dah_news n on dn.newsid = n.newsid
                                            where dn.status = 'pending' and dn.publishdate < " . time() . " and newsletter='yes' limit " . $limit . " ");
        } else {
            $statement = $connection->prepare("  select * from dah_newsletter_message_queue dn 
                                            join dah_news n on dn.newsid = n.newsid
                                            where dn.status = 'pending' and dn.publishdate < " . time() . " and newsletter='yes' limit 10 ");
        }
        $statement->execute();
        $subscribers = $statement->fetchAll();
        foreach ($subscribers as $sub) {


            $message = \Swift_Message::newInstance()
                    ->setSubject( 'Dar al Hekama :: News Letter ' . date('d-m-Y') . ' ' . $sub['news_title'] . ' ')
                    ->setFrom($this->from)
                    ->setTo($sub['email'])
                    ->setBody($this->container->get('templating')->render('AdminBundle:Emails:newsLetter.html.twig', $sub), 'text/html');
            

            $this->mailer->send($message);


            $statement = $connection->prepare("  update   dah_newsletter_message_queue 
                                                set status = 'sent'
                                            where nqid = " . $sub['nqid'] . "  ");
            $statement->execute();
            $count++;
        }
        $text = "Total messages sent $count  ";
        $output->writeln($text);
    }

}
