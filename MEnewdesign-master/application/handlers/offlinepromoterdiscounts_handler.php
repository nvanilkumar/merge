<?php

/**
 * offflien promoter discounts mapping Source Data will be defined in this class
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @param		eventId - required
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0
 * @Created     13-11-2015
 * @Last Modified 13-11-2015
 */
require_once (APPPATH . 'handlers/handler.php');

class Offlinepromoterdiscounts_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('offlinepromoterdiscounts_model');
    }

  public function insertofflinePromoterDiscounts($input) {
        $this->ci->offlinepromoterdiscounts_model->resetVariable();
        $this->ci->offlinepromoterdiscounts_model->setTableName('offlinepromoterdiscounts');
        $offlineTickets=array();
        foreach ($input['ticketDiscount'] as $key => $value) {
            $list=explode("-",$value);
            $createOfflineTicket[$this->ci->offlinepromoterdiscounts_model->ticketid] = $list[0];
            $createOfflineTicket[$this->ci->offlinepromoterdiscounts_model->discountid] = $list[1];
            $createOfflineTicket[$this->ci->offlinepromoterdiscounts_model->promoterid] = $input['promoterId'];
            $createOfflineTicket[$this->ci->offlinepromoterdiscounts_model->eventid] = $input['eventId'];
            $this->ci->offlinepromoterdiscounts_model->setInsertUpdateData($createOfflineTicket);
            $offlineTickets[] = $this->ci->offlinepromoterdiscounts_model->insert_data();
            
        }
        $output['status'] = TRUE;
        $output["response"]["messages"] = array();
        $output["response"]["offlineticketIds"][] =$offlineTickets;
        $output['statusCode'] = 200;
        return $output;
    }
    
    //To get the prometer event related ticket discount list
    public function getPrometerEvetTicketDiscounts($inputArray){
        $this->ci->offlinepromoterdiscounts_model->resetVariable();
        $this->ci->offlinepromoterdiscounts_model->setTableName('offlinepromoterdiscounts');
        $select['id'] = $this->ci->offlinepromoterdiscounts_model->id;
        $select['ticketid'] = $this->ci->offlinepromoterdiscounts_model->ticketid;
        $select['discountid'] = $this->ci->offlinepromoterdiscounts_model->discountid;
        $select['promoterid'] = $this->ci->offlinepromoterdiscounts_model->promoterid;
        $select['eventid'] = $this->ci->offlinepromoterdiscounts_model->eventid;
        $select['deleted'] = $this->ci->offlinepromoterdiscounts_model->deleted;
         
        $this->ci->offlinepromoterdiscounts_model->setSelect($select);
        
        $where[$this->ci->offlinepromoterdiscounts_model->eventid] = $inputArray['eventId'];
        if(isset($inputArray['ticketId'])){
             $where[$this->ci->offlinepromoterdiscounts_model->ticketid] = $inputArray['ticketId'];
        }
        if(isset($inputArray['discountId'])){
             $where[$this->ci->offlinepromoterdiscounts_model->discountid] = $inputArray['discountId'];
        }
        if(isset($inputArray['promoterId'])){
             $where[$this->ci->offlinepromoterdiscounts_model->promoterid] = $inputArray['promoterId'];
        }
        if(!isset($inputArray['deletedStatus'])){
            $where[$this->ci->offlinepromoterdiscounts_model->deleted] =0;
        }
        

        $this->ci->offlinepromoterdiscounts_model->setWhere($where);

        $promoterDetails = $this->ci->offlinepromoterdiscounts_model->get();
        if(count($promoterDetails)>0){
            
            $output['status'] = TRUE;
            $output['response']['prometerDiscountList'] = $promoterDetails;
             
            $output['messages'] = array();
            $output['response']['total'] = count($promoterDetails);
            $output['statusCode'] = STATUS_OK;
            
        } else if ($promoterDetails != FALSE && count($promoterDetails) == 0) {//No records are fetched
            $output['status'] = TRUE;
            $output['messages'][] = ERROR_NO_DATA;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
        } else {
            $output['status'] = FALSE;
            $output['messages'][] = ERROR_INTERNAL_DB_ERROR;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_SERVER_ERROR;
        }
        return $output;
        
        
    }
    
    //To update the prometer ticket related discount list
    public function updateOfflinePromoterTicketDiscounts($inputArray) {
        $this->ci->offlinepromoterdiscounts_model->resetVariable();
        $this->ci->offlinepromoterdiscounts_model->setTableName('offlinepromoterdiscounts');
        
        //Update all previeous records deleted status to 1
        $whereArray=array();
        $createTicket[$this->ci->offlinepromoterdiscounts_model->deleted] = 1;
        $whereArray[$this->ci->offlinepromoterdiscounts_model->eventid] = $inputArray['eventId'];
        $whereArray[$this->ci->offlinepromoterdiscounts_model->promoterid] = $inputArray['promoterId'];
        $this->ci->offlinepromoterdiscounts_model->setWhere($whereArray);
        $this->ci->offlinepromoterdiscounts_model->setInsertUpdateData($createTicket);
        $offlineTickets[] = $this->ci->offlinepromoterdiscounts_model->update_data();
        
        //check the record exist or not if not exist insert the reocrd otherwise change the deleted status 0
        $checkArray=array();
        $whereArray=array();
        $output=array();
        foreach ($inputArray['ticketDiscount'] as $key => $value) {
            $list=explode("-",$value);
            
            $checkArray['ticketId']= $list[0];
            $checkArray['discountId']= $list[1];
            $checkArray['eventId']= $inputArray['eventId'];
            $checkArray['promoterId']= $inputArray['promoterId'];
            $checkArray['deletedStatus']= TRUE;
            $discountDetails=$this->getPrometerEvetTicketDiscounts($checkArray);
            if( $discountDetails['response']['total'] > 0){
                $createTicket[$this->ci->offlinepromoterdiscounts_model->deleted] = 0;
                $whereArray[$this->ci->offlinepromoterdiscounts_model->ticketid] = $checkArray['ticketId'];
                $whereArray[$this->ci->offlinepromoterdiscounts_model->discountid] = $checkArray['discountId'];
                $whereArray[$this->ci->offlinepromoterdiscounts_model->eventid] = $inputArray['eventId'];
                $whereArray[$this->ci->offlinepromoterdiscounts_model->promoterid] = $inputArray['promoterId'];
                $this->ci->offlinepromoterdiscounts_model->setWhere($whereArray);
                $this->ci->offlinepromoterdiscounts_model->setInsertUpdateData($createTicket);
                $offlineTickets[] = $this->ci->offlinepromoterdiscounts_model->update_data();

            }else{
                $insertArray['ticketDiscount'][]=$value;
                $insertArray['eventId']= $inputArray['eventId'];
                $insertArray['promoterId']= $inputArray['promoterId'];
                $this->insertofflinePromoterDiscounts($insertArray);
            }
        }
        $output = parent::createResponse(TRUE, "Updated the ticket discount data", STATUS_CREATED);
        return $output;
    }


}

?>