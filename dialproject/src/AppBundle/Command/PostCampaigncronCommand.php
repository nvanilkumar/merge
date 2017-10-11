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

class PostCampaigncronCommand extends ContainerAwareCommand {

    protected $em;
    private $container;
    private $rootdir;
    private $ftp;

    public function __construct($em, Container $container, $rootdir, $ftp) {
        $this->em = $em;
        $this->container = $container;
        $this->rootdir = $rootdir;
        $this->ftp = $ftp;
        parent::__construct();
    }

    protected function configure() {
        $this
                ->setName('cron:postcampaign')
                ->setDescription('Post Campaign cron');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
         $text = "Corn Execution started.....";
        $output->writeln($text);
        $helperHandler = $this->getApplication()->getKernel()->getContainer()->get('helper_handler');
        $connection = $this->em->getConnection();
//        $host = $this->container->getParameter('recordingsftphost');
//        $username = $this->container->getParameter('recordingsusername');
//        $password = $this->container->getParameter('recordingsftppassword');
//        $this->ftp->connect($host);
//        $this->ftp->login($username, $password);
        $text = "Corn Execution started";
        $output->writeln($text);

        $sql = "select * from campaign where is_complete = 0 and campaign_status = 'active' ";
        $statement = $connection->prepare($sql);
        $statement->execute();
         $text = "Corn Execution started";
        $output->writeln($sql);
        $data = $statement->fetchAll();

        foreach ($data as $cmp) {
            $text = "Campaign : " . $cmp['campaign_name'];
            $output->writeln($text);
            $today = time();
            $totime = strtotime($cmp['to_date']);
            $datewisedone = false;
            $callsdone = false;
            if ($today > $totime) {
                $datewisedone = true;
                $text = "Campaign completed as result of date expiry ";
                $output->writeln($text);
            }
            $sql = "select count(cd_id) as cnt from campaign_data where campaign_id = " . $cmp['campaign_id'] . "  ";
            $statement = $connection->prepare($sql);
            $statement->execute();
            $totalcount = $statement->fetch();
            $sql = "select count(cd_id) as cnt from campaign_data where campaign_id = " . $cmp['campaign_id'] . " and ds_id != 0 and skipped_by = 0 ";
            $statement = $connection->prepare($sql);
            $statement->execute();
            $completed = $statement->fetch();
            $sql = "select count(cd_id) as cnt from campaign_data where campaign_id = " . $cmp['campaign_id'] . " and skipped_by != 0 ";
            $statement = $connection->prepare($sql);
            $statement->execute();
            $skipped = $statement->fetch();

            $text = "Total Count : " . $totalcount['cnt'];
            $output->writeln($text);
            $text = "Completed Count : " . $completed['cnt'];
            $output->writeln($text);
            $text = "Skipped Count : " . $skipped['cnt'];
            $output->writeln($text);
            if ($totalcount['cnt'] == ($completed['cnt'] + $skipped['cnt'] )) {
                $callsdone = true;
                $text = "Campaign completed as calls processed ";
                $output->writeln($text);
            }
            if ($callsdone || $datewisedone) {

                $text = "Generating campaign report... ";
                $output->writeln($text);
                $pdf = $this->getApplication()->getKernel()->getContainer()->get("white_october.tcpdf")->create();
                $res = $helperHandler->getCampaignCustomers($cmp['campaign_id']);
                $datanew['res'] = $res;
                $datanew['campaignName'] = $cmp['campaign_name'];

                $filename = md5(uniqid()) . ".pdf";

                $html = $this->getContainer()->get('templating')->render('AdminBundle:Campaigns:campaignPdf.html.twig', $datanew);

                $pdf->setPrintFooter(false);
                $pdf->setPrintHeader(false);
                // set margins
                $pdf->SetMargins(0, 0, 0, true);
                $pdf->SetFont('aealarabiya', '', 18);
                // set auto page breaks false
                $pdf->SetAutoPageBreak(true, 0);
                // add a page
                $pdf->AddPage('L', 'A5');
                // $html = '<span style="color:white;text-align:center;font-weight:bold;font-size:80pt;">PAGE 3</span>';
                $pdf->writeHTML($html, true, false, true, false, '');
                $pdf->Output($this->rootdir . '/../web/uploads/' . $filename, 'F');
                chmod($this->rootdir . '/../web/uploads/' . $filename, 0777);
                $text = "Campaign report generated.";
                $output->writeln($text);
                $text = "Marking campaign as done... ";
                $output->writeln($text);
                $uquery = "UPDATE campaign SET is_complete = 1 , pdf_report = '" . $filename . "'  " .
                        " where campaign_id=" . $cmp['campaign_id'];
                $connection->executeUpdate($uquery);
                $text = "Campaign marked as done ";
                $output->writeln($text);
   
            }else{
                $uquery = "UPDATE campaign SET is_running = 0 where campaign_id=" . $cmp['campaign_id'];
                $connection->executeUpdate($uquery);
            }

           /* $sql = "select cd.cd_id , cp.campaign_name , c.phone_number 
                    from campaign_data cd
                    join customer c on cd.customer_id = c.customer_id
                    join campaign cp on cp.campaign_id = cd.campaign_id  where cd.campaign_id = " . $cmp['campaign_id'];
            $statement = $connection->prepare($sql);
            $statement->execute();
            $datarecs = $statement->fetchAll();
            foreach ($datarecs as $recs) {
                $text = "Looking up for call recordings...";
                $output->writeln($text);
                $cmpname = str_replace(' ', '_', $recs['campaign_name']);
                $custnum = $recs['phone_number'];
                //$agentnum = $recs['extension'];
                $filelist = $this->ftp->nlist("$cmpname*$custnum.wav");
                if (!empty($filelist)) {
                    $text = "Found call recording for $cmpname -- $custnum  ";
                    $output->writeln($text);
                    $newfile = array_pop($filelist);
                    $filename = md5(uniqid()) . ".wav";
                    $myfile = fopen($this->rootdir . '/../web/uploads/' . $filename, "w");
                    $this->ftp->fget($myfile, $newfile, FTP_ASCII);
                    fclose($myfile);
                    chmod($this->rootdir . '/../web/uploads/' . $filename, 0777);
                    $uquery = "UPDATE campaign_data SET  call_recording_file = '" . $filename . "'  " .
                            " where cd_id=" . $recs['cd_id'];
                    $connection->executeUpdate($uquery);
                    $text = "Saved call recording";
                    $output->writeln($text);
                }
            }*/
            $text = " ________________________Campaign processed________________________";
            $output->writeln($text);
        }
        $text = "Corn Execution ended";
        $output->writeln($text);
    }

}
