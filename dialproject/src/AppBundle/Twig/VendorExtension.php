<?php

namespace AppBundle\Twig;

use Symfony\Bridge\Doctrine\RegistryInterface;

class VendorExtension extends \Twig_Extension {

    protected $doctrine;

    public function __construct(RegistryInterface $doctrine) {
        $this->doctrine = $doctrine;
    }

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('intGcount', array($this, 'getInterestCount')),
        );
    }

    public function getFunctions() {
        return array(
            'getName' => new \Twig_Function_Method($this, 'getName'),
            'valueInArray' => new \Twig_Function_Method($this, 'valueInArray'),
            'campaignAgentsCount' => new \Twig_Function_Method($this, 'campaignAgentsCount'),
            'agentCampaignCount' => new \Twig_Function_Method($this, 'agentCampaignCount'),
            'customerCampaignCount' => new \Twig_Function_Method($this, 'customerCampaignCount'),
            'agentTotalCalls' => new \Twig_Function_Method($this, 'agentTotalCalls'),
            'totalAgents' => new \Twig_Function_Method($this,'totalAgents'),
            'totalCampagins' => new \Twig_Function_Method($this,'totalCampagins'),
            'totalCompletedCampaigns' => new \Twig_Function_Method($this,'totalCompletedCampaigns'),
             'totalLiveCampaigns' => new \Twig_Function_Method($this,'totalLiveCampaigns')
      );
    }

    public function getName() {
        return 'acme_extension';
    }

    public function valueInArray($value, $array) {
        if (in_array($value, $array)) {
            echo true;
        } else {
            echo false;
        }
    }

    public function campaignAgentsCount($campaignid) {
        if ($campaignid > 0) {
            $em = $this->doctrine->getManager();
            $connection = $em->getConnection();
            $statement = $connection->prepare(" select * from campaign_agents where  campaign_id = " . $campaignid);
            $statement->execute();
            $cnt = $statement->fetchAll();
            return count($cnt);
        } else {
            return 0;
        }
    }

    public function agentCampaignCount($userid) {
        if ($userid > 0) {
            $em = $this->doctrine->getManager();
            $connection = $em->getConnection();
            $statement = $connection->prepare(" select * from campaign_agents where user_id = " . $userid);
            $statement->execute();
            $cnt = $statement->fetchAll();
            return count($cnt);
        } else {
            return 0;
        }
    }
    public function customerCampaignCount($campaignid) {
        if ($campaignid > 0) {
            $em = $this->doctrine->getManager();
            $connection = $em->getConnection();
            $statement = $connection->prepare(" select * from campaign_data where campaign_id = " . $campaignid);
            $statement->execute();
            $cnt = $statement->fetchAll();
            return count($cnt);
        } else {
            return 0;
        }
    }

    public function agentTotalCalls($agentId) {
          if ($agentId > 0) {
            $em = $this->doctrine->getManager();
            $connection = $em->getConnection();
            $statement = $connection->prepare(" select * from campaign_data where assigned_to = " . $agentId);
            $statement->execute();
            $cnt = $statement->fetchAll();
            return count($cnt);
        } else {
            return 0;
        }
    }
  
   
    public function totalCampagins(){
        $em = $this->doctrine->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare("select * from campaign where is_deleted = 0 ");
        $statement->execute();
        $cnt = $statement->fetchAll();
        return count($cnt);
    }
    public function totalCompletedCampaigns(){
        $em = $this->doctrine->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare("select * from campaign where is_deleted = 0 and is_complete=1 ");
        $statement->execute();
        $cnt = $statement->fetchAll();
        return count($cnt);
    }
    public function totalLiveCampaigns(){
        $em = $this->doctrine->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare("select * from campaign where is_deleted = 0 and is_running=1 ");
        $statement->execute();
        $cnt = $statement->fetchAll();
        return count($cnt);
    }
    public function dateTimeToString($datetime) {
        return date('Y-m-d', strtotime($datetime));
    }

}
