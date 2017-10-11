<?php

namespace AdminBundle\Services;

use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\LiveAgents;

class HelperHandler
{

    private $connection;

    public function __construct($request, $em, $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->em = $em;
        $this->request = $request->getCurrentRequest();
        $this->connection = $this->em->getConnection();
    }

    /**
     * To insert the live agent details . Details present we will update the agent status
     * @param $campainId Campain Id
     * @param $userId User id
     */
    public function insertLiveAgentDetails($campainId, $userId)
    {


        //Check agent live entry exist or not
        $sql = "select * from live_agents where user_id=" . $userId . " and campaign_id=" . $campainId;
        $statement = $this->connection->prepare($sql);
        $statement->execute();
        $data = $statement->fetchAll();
        if (count($data) > 0) {
            $uquery = "UPDATE live_agents SET status='active' where user_id=" . $userId . " and campaign_id=" . $campainId;
            $this->connection->executeUpdate($uquery);
        } else {//To Insert the live agent
            $livAgentInsert = " INSERT INTO live_agents ( user_id,campaign_id,status)
                             VALUES ($userId,$campainId, 'active')";
            $this->connection->executeUpdate($livAgentInsert);
        }

        //To Update  the 
    }

    /**
     * To update the assign colum in campaign table
     * @param type $campainId
     * @param type $customerId
     * @param type $agentId
     */
    public function updateCampainData($campainId, $customerId)
    {

        $uquery = "UPDATE campaign_data cd"
                . "  INNER JOIN campaign as c on c.campaign_id=cd.campaign_id"
                . " SET cd.retry_count=cd.retry_count+1, cd.updated_on = " . time() . ", c.is_running=1"
                . " where cd.campaign_id=" . $campainId . " and cd.customer_id=" . $customerId;
        $this->connection->executeUpdate($uquery);
    }

    /**
     * To update the assign colum in campaign table
     * @param type $campainId
     * @param type $customerId
     * @param type $status
     */
    public function updateCampainDataStatus($campainId, $customerId, $status)
    {

        $uquery = "UPDATE campaign_data SET ds_id=" . $status . ", updated_on = " . time() .
                " where campaign_id=" . $campainId . " and customer_id=" . $customerId;
        $this->connection->executeUpdate($uquery);
    }
    
     /**
     * To update the campaign data table dial status value by its id
     * @param type $campainDataId
     * @param type $status
     */
    public function updateCampainDataStatusById($campainDataId,  $status)
    {

        $uquery = "UPDATE campaign_data SET ds_id=" . $status . ", updated_on = " . time() .
                " where cd_id=" . $campainDataId ;
        $this->connection->executeUpdate($uquery);
    }

    /**
     * To Bring the user related campaign details
     * @param type $campaignType  campaign type
     * @param type $extension user extention
     * @return type
     */
    public function getAgentCampaign($campaignType, $extension)
    {
        $todayDate = date("Y-m-d");
        $timeStamp = date("H:i:s");
        $day = " and " . strtolower(date("l")) . "='1'";
        $sql = "SELECT c.campaign_id,u.user_id  
               FROM campaign as c 
               join campaign_agents as ca on ca.campaign_id =c.campaign_id
               join user  as u on u.user_id = ca.user_id 
               join campaign_type as ct on ct.ct_id=c.ct_id
               WHERE c.from_date <= '" . $todayDate . " 00:00:00' and c.to_date > '" . $todayDate . " 23:59:59' 
               and c.from_time <= '" . $timeStamp . "'" . $day . " and c.to_time >= '" . $timeStamp . "' 
               and c.campaign_status='active' and c.is_deleted='0'  and c.is_complete = 0
            and u.extension='" . $extension . "' ";

        $sql = "SELECT c.campaign_id,u.user_id , c.campaign_name 
               FROM campaign as c 
               join campaign_agents as ca on ca.campaign_id =c.campaign_id
               join user  as u on u.user_id = ca.user_id 
               join campaign_type as ct on ct.ct_id=c.ct_id
               WHERE c.from_date <= '" . $todayDate . " 00:00:00' and c.to_date > '" . $todayDate . " 23:59:59' 
              
               and c.campaign_status='active' and c.is_deleted='0'   and c.is_complete = 0
            and u.extension='" . $extension . "' ";

        $statement = $this->connection->prepare($sql);
        $statement->execute();



        $data = $statement->fetchAll();
        return $data;
    }

    /**
     * To Bring the user related campaign details
     * @param type $campaignType  campaign type
     * @param type $extension user extention
     * @return type
     */
    public function getCompletedAgentCampaign($campaignType, $extension)
    {
        $todayDate = date("Y-m-d");
        $timeStamp = date("H:i:s");
        $day = " and " . strtolower(date("l")) . "='1'";


        $sql = "SELECT c.campaign_id,u.user_id , c.campaign_name 
               FROM campaign as c 
               join campaign_agents as ca on ca.campaign_id =c.campaign_id
               join user  as u on u.user_id = ca.user_id 
               join campaign_type as ct on ct.ct_id=c.ct_id
               and c.campaign_status='active' and c.is_deleted='0'   and c.is_complete = 1
            and u.extension='" . $extension . "' ";

        $statement = $this->connection->prepare($sql);
        $statement->execute();



        $data = $statement->fetchAll();
        return $data;
    }

    /**
     * To send the selected campaign status information
     * @param type $campaignId
     * @return type array with campaign status info
     */
    public function getCampainStatusInfo($campaignId)
    {
        $res = array();
        $campaignQuery = "select count(cd.cd_id) as campaigns,cd.ds_id,c.campaign_name
                            from campaign_data  as cd
                            join campaign c on c.campaign_id=cd.campaign_id
                            where cd.campaign_id=" . $campaignId . "
                            group by cd.ds_id";
        $statement = $this->connection->prepare($campaignQuery);
        $statement->execute();
        $data = $statement->fetchAll();
        $totalUsers = $compltedUsers = 0;
        $campaign_name = "";
        foreach ($data as $row) {
            $totalUsers += $row['campaigns'];
            //only completed calls
            if ($row['ds_id'] != 0) {
                $compltedUsers = $row['campaigns'];
                $campaign_name = $row['campaign_name'];
            }
        }

        $res = array('totalUsers' => $totalUsers, 'completedUsers' => $compltedUsers,
            'campaignName' => $campaign_name);
        return $res;
    }

    /**
     * To send the selected agent customer details
     * @param type $agentId
     * @return type array with data
     */
    public function getAgenCustomers($agentId, $campaignId)
    {
        $res = array();
        $customerQuery = "select cd.assigned_to , cd.retry_count , c.* , ds.dial_status
                            from campaign_data cd
                            Join customer c on c.customer_id = cd.customer_id
                            Join dial_status ds on ds.ds_id = cd.ds_id
                          WHERE cd.campaign_id=" . $campaignId . " and cd.assigned_to=" . $agentId . " ";
        $statement = $this->connection->prepare($customerQuery);
        $statement->execute();
        $data = $statement->fetchAll();
        return $data;
    }

    /**
     * To send the selected agent customer details
     * @param type $agentId
     * @return type array with data
     */
    public function getAgenCustomersCalldet($agentId, $campaignId)
    {
        $res = array();
        $customerQuery = "select lc.dial_time , lc.ans_time , lc.end_time , lc.duration , c.*
                        from live_calls lc
                        join customer c on lc.to_number = c.phone_number
                          WHERE lc.campaign_id=" . $campaignId . " and lc.user_id=" . $agentId . " ";
        $statement = $this->connection->prepare($customerQuery);
        $statement->execute();
        $data = $statement->fetchAll();
        return $data;
    }

    /**
     * To send the selected agent customer details
     * @param type $agentId
     * @return type array with data
     */
    public function getCampaignCustomers($campaignId)
    {
        $res = array();
        $customerQuery = "select cd.assigned_to , cd.retry_count , c.* , ds.dial_status 
                            from campaign_data cd
                            Join customer c on c.customer_id = cd.customer_id
                            Join dial_status ds on ds.ds_id = cd.ds_id
                          WHERE cd.campaign_id=" . $campaignId . " ";
        $statement = $this->connection->prepare($customerQuery);
        $statement->execute();
        $data = $statement->fetchAll();
        return $data;
    }

    // Campaign details 
    public function getCampaign($campaignId)
    {
        $res = array();
        $campaign = "select * from campaign WHERE campaign_id=" . $campaignId;
        $statement = $this->connection->prepare($campaign);
        $statement->execute();
        $data = $statement->fetchAll();
        return $data;
    }
    //Checking campaing is running or not
    public function checkRunningCampaign($campaignId) {
         $res = array();
        $customerQuery = "select * from campaign where is_running=1 and campaign_id=".$campaignId." ";
        $statement = $this->connection->prepare($customerQuery);
        $statement->execute();
        $data = $statement->fetchAll();
        return count($data);
    }
   
    /**
     * To Bring the specified live campaign related customers 
     * @param type $campaignId
     * @param type $limit
     * @param type $call
     * @return type
     */
    public function nextCustomers($campaignId,$limit,$call=NULL){
          //get the campagin related customer
        $skipped='';
        if($call=='api')
        {
           $skipped=" AND cd.skipped_by =0";
        }
       $campaignDataQuery = "SELECT cu.*,cd.*, cm.campaign_name
                                FROM customer AS cu
                                JOIN campaign_data AS cd ON cd.customer_id=cu.customer_id
                                JOIN campaign cm ON cm.campaign_id = cd.campaign_id
                                WHERE cm.campaign_id=" . $campaignId . " AND cm.is_running=1 and cd.ds_id = 0 AND cd.retry_count < cd.max_retry_count
                                AND cm.is_paused = 0".$skipped."  order by cu.customer_id desc LIMIT ".$limit;
        $statement = $this->connection->prepare($campaignDataQuery);
        $statement->execute();
        $data = $statement->fetchAll();
        return $data;
    }
    
     /**
     * To update the specified campaign related status
     * @param type $campainId
     * @param type $customerId
     * @param type $agentId
     */
    public function updateCampaignRunningStatus($campainId,$status=0)
    {
        $uquery = "UPDATE campaign c"
                . " SET  c.is_running=".$status
                . " where c.campaign_id=" . $campainId;
        $this->connection->executeUpdate($uquery);
    }

    public function fileUpload($file,$rootfolder){
        $filePath =  $rootfolder.'/../web/uploads/voicefiles/';
        if(!file_exists($filePath)){
            mkdir($filePath, 0777, true);
        }
         if (!move_uploaded_file($file['tmp_name'], $filePath.$file['name'])) {
            return false;
        }
        return  true;
   }

}
