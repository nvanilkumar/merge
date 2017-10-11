<?php

namespace AdminBundle\Model;

class CampaignModel
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
     * LiveCampain whose status is active, not deleted , not completed ,not passed
     * customer data retry count not more than 3, not skipped, dail status =0
     * Brings the first customer related to live campaign
     */
    public function liveCampaings()
    {
        $todayDate = date("Y-m-d");
        $timeStamp = date("H:i:s");
        $sql = "SELECT c.campaign_id,cu.customer_id,cu.title,cu.first_name,cu.last_name,cu.phone_number,cd.cd_id
                from customer as cu
                join campaign_data as cd on cd.customer_id=cu.customer_id
                join campaign c on c.campaign_id = cd.campaign_id
                WHERE c.from_date <= '" . $todayDate . " ".$timeStamp."' and c.to_date >= '" . $todayDate . " ".$timeStamp."' 
                and c.campaign_status='active' and c.is_deleted='0' and c.is_complete = 0 
                and ds_id = 0 and cd.retry_count < cd.max_retry_count and is_running=0 and is_paused = 0 and cd.skipped_by =0 limit 0,1";
				//and ds_id = 0 and cd.retry_count < 3 and is_paused = 0 and cd.skipped_by =0 limit 0,1";

        $statement = $this->connection->prepare($sql);
        $statement->execute();
        $data = $statement->fetchAll();
        return $data;
    }

}
