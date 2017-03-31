<?php

/**
 * Ticket discount related business logic will be defined in this class
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     17-07-2015
 * @Last Modified 17-07-2015
 */
require_once(APPPATH . 'handlers/handler.php');
require_once(APPPATH . 'handlers/discount_handler.php');

class Ticketdiscount_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Ticketdiscount_model');
    }

    public function addTicketDiscount($inputArray) {
        $this->ci->Ticketdiscount_model->resetVariable();
        foreach ($inputArray['ticketIds'] as $key => $value) {
            $createTicketDiscount[$this->ci->Ticketdiscount_model->ticketid] = $value;
            $createTicketDiscount[$this->ci->Ticketdiscount_model->discountid] = $inputArray['discountId'];
            $createTicketDiscount[$this->ci->Ticketdiscount_model->status] = 1;
            $this->ci->Ticketdiscount_model->setInsertUpdateData($createTicketDiscount);
            $ticketDiscountData = $this->ci->Ticketdiscount_model->insert_data();
        }
        if ($ticketDiscountData) {
            $output = parent::createResponse(TRUE, SUCCESS_ADDED_TICKETDISCOUNT, STATUS_CREATED);
            return $output;
        } else {
            $output = parent::createResponse(FALSE, ERROR_SOMETHING_WENT_WRONG, STATUS_BAD_REQUEST);
            return $output;
        }
    }

    public function getTicketDiscountData($discountIdList) {    
        $this->ci->Ticketdiscount_model->resetVariable();
        $select['id'] = $this->ci->Ticketdiscount_model->id;
        $select['ticketid'] = $this->ci->Ticketdiscount_model->ticketid;
        $select['discountid'] = $this->ci->Ticketdiscount_model->discountid;
        $select['status'] = $this->ci->Ticketdiscount_model->status;
        $this->ci->Ticketdiscount_model->setSelect($select);
        $whereInArray[$this->ci->Ticketdiscount_model->discountid] = $discountIdList;
        $this->ci->Ticketdiscount_model->setWhereIns($whereInArray);
        $where[$this->ci->Ticketdiscount_model->deleted] = 0;
        $this->ci->Ticketdiscount_model->setWhere($where);
        $result = $this->ci->Ticketdiscount_model->get();
        $output = parent::createResponse(TRUE, array(), STATUS_OK, count($result), 'ticketDiscountList', $result);
        return $output;
    }

    // Fetch discount details by giving ticket id, 
    // accepts input array of ticket ids

    public function getDiscountByTicketId($inputArray) {

        $select = array();
        $where = array();
        $this->ci->Ticketdiscount_model->resetVariable();
        $select['id'] = $this->ci->Ticketdiscount_model->id;
        $select['ticketid'] = $this->ci->Ticketdiscount_model->ticketid;
        $select['discountid'] = $this->ci->Ticketdiscount_model->discountid;
        $this->ci->Ticketdiscount_model->setSelect($select);
        //$this->ci->Ticketdiscount_model->whereInArray = array('ticketid', $inputArray['ticketIds']);
        $whereIn[$this->ci->Ticketdiscount_model->ticketid] = $inputArray['ticketIds'];
        $where[$this->ci->Ticketdiscount_model->status] = 1;
        $where[$this->ci->Ticketdiscount_model->deleted] = 0;
        $this->ci->Ticketdiscount_model->setWhere($where);
        $this->ci->Ticketdiscount_model->setWhereIns($whereIn);


        $result = $this->ci->Ticketdiscount_model->get();
        if (count($result) == 0) {
            $output = parent::createResponse(TRUE, ERROR_NO_DISCOUNT, STATUS_OK, 0);
            return $output;
        }
        $uniqueDiscount = array();
        foreach ($result as $value) {
            $uniqueDiscount[$value['discountid']] = $value['discountid'];
        }

        $discountCode = isset($inputArray['discountCode']) ? strtolower($inputArray['discountCode']) : '';
        $input['eventId'] = $inputArray['eventId'];
        $input['discountId'] = $uniqueDiscount;

        $this->DiscountHandler = new Discount_handler();
        $response = $this->DiscountHandler->getDiscountData($input);



        if ($response['status'] == TRUE) {
            if ($response['response']['total'] > 0) {
                $responseDiscount = $response['response']['discountList'];

                $indexedDiscount = array();
                foreach ($responseDiscount as $value) {
                    if ($value['type'] == 'normal' && strcmp($discountCode, strtolower($value['code'])) == 0) {
                        $indexedDiscount[$value['id']] = $value;
                    } elseif ($value['type'] == 'bulk') {
                        $indexedDiscount[$value['id']] = $value;
                    }
                }





                foreach ($result as $key => $value) {

                    if (isset($indexedDiscount[$value['discountid']]))
                        $result[$key]['discountDetails'] = $indexedDiscount[$value['discountid']];
                }

                ;
                //creating final response 
                $finalResponse = array();
                $remainingQuantity = 0;
                foreach ($result as $key => $value) {
                    if ($value['discountDetails']['type'] == 'normal') {
                        $remainingQuantity = $value['discountDetails']['usagelimit'] - $value['discountDetails']['totalused'];
                        //checking if discount is available for the given ticket
                        if (isset($value['discountDetails']) && $remainingQuantity > 0) {
                            $value['discountDetails']['remainingDiscountQuantity'] = $remainingQuantity;
                        }
                    }
                    if (isset($value['discountDetails'])) {
                        $finalResponse[$value['ticketid']]['discount'][$value['discountid']] = $value['discountDetails'];
                    }
//            $finalResponse[$value['ticketid']]['discount'][$value['discountid']]['id']=$value['taxid'];
//            $finalResponse[$value['ticketid']]['discount'][$value['discountid']]['value']=$value['taxvalue'];
                }
            }
        }


        $output = parent::createResponse(TRUE, array(), STATUS_OK, count($finalResponse), 'ticketDiscountList', $finalResponse);
        return $output;
    }

    public function manipulateTicketDiscount($inputArray) {
        $this->ci->Ticketdiscount_model->resetVariable();
        $dbTicketIdList = $inputArray['dbTicketIdList'];
        $unselectedTicketIds=array();
        if (count($dbTicketIdList) > 0) {
            //Updating the status(from the ticket discount table) which are not seleted while editing the discount
            $unselectedTicketIds = array_diff($dbTicketIdList, $inputArray['ticketIds']);//status=0
            if (count($unselectedTicketIds) > 0) {
                $updateTicketDiscount[$this->ci->Ticketdiscount_model->status] = 0;
                $whereInArray[$this->ci->Ticketdiscount_model->ticketid] = $unselectedTicketIds;
                $this->ci->Ticketdiscount_model->setWhereIns($whereInArray);
                $where[$this->ci->Ticketdiscount_model->discountid] = $inputArray['id'];
                $this->ci->Ticketdiscount_model->setWhere($where);
                $this->ci->Ticketdiscount_model->setInsertUpdateData($updateTicketDiscount);
                $updateTicketDiscountData = $this->ci->Ticketdiscount_model->update_data();
            }
        }
        if (count($inputArray['ticketIds']) > 0) {       
            foreach ($inputArray['ticketIds'] as $key => $value) {
                if (!in_array($value, $dbTicketIdList)) { //Adding new ticket id in ticketdiscount table//0
                    $createTicketDiscount[$this->ci->Ticketdiscount_model->ticketid] = $value;
                    $createTicketDiscount[$this->ci->Ticketdiscount_model->discountid] = $inputArray['id'];
                    $createTicketDiscount[$this->ci->Ticketdiscount_model->status] = 1;
                    $this->ci->Ticketdiscount_model->setInsertUpdateData($createTicketDiscount);
                    $ticketDiscountData = $this->ci->Ticketdiscount_model->insert_data();
                }else if(!in_array($value,$unselectedTicketIds)){//Updating the status of selected tickets
                    $updateTicketDiscountArray[$this->ci->Ticketdiscount_model->status] = 1;
                    $whereArray[$this->ci->Ticketdiscount_model->ticketid] = $value;
                    $whereArray[$this->ci->Ticketdiscount_model->discountid] = $inputArray['id'];
                    $this->ci->Ticketdiscount_model->setWhere($whereArray);
                    $this->ci->Ticketdiscount_model->setInsertUpdateData($updateTicketDiscountArray);
                    $updateTicketDiscountData = $this->ci->Ticketdiscount_model->update_data();                    
                }
            }
        }

        $output = parent::createResponse(TRUE, "Updated the ticket discount data", STATUS_CREATED);
        return $output;
    }
}
