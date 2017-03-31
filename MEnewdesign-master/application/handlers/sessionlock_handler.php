<?php

/**
 * session lock related business logic will be defined in this class
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     11-06-2015
 * @Last Modified 11-06-2015
 */
require_once (APPPATH . 'handlers/handler.php');
require_once(APPPATH . 'handlers/orderlog_handler.php');
//require_once(APPPATH . 'handlers/ticket_handler.php');

class Sessionlock_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Sessionlock_model');
        //$this->ticketHandler = new Ticket_handler();
    }

    public function add($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        //Checking validation using Group Validation (signup)
        if ($this->ci->form_validation->run('addsessionlock') === FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
            return $output;
        }
        $orderId = $inputArray['orderId'];
        $ticketArray = $inputArray['ticketArray'];
        $inputOrderlog['orderId'] = $orderId;
        $inputOrderlog['call'] = 'eventdetail';
        $orderlogHandler = new Orderlog_handler();
        $orderlogResponse = $orderlogHandler->getOrderlog($inputOrderlog);
        if ($orderlogResponse['status'] && $orderlogResponse['response']['total'] > 0) {
            //$insertData[$this->Sessionlock_model->orderid]=$orderId;
            $x = 0;
            $insert = array();
            $this->ci->Sessionlock_model->resetVariable();
            foreach ($ticketArray as $key => $value) {
                //print_r($key.' '.$value);
                $insert[$x][$this->ci->Sessionlock_model->orderid] = $orderId;
                $insert[$x][$this->ci->Sessionlock_model->ticketid] = $key;
                $insert[$x][$this->ci->Sessionlock_model->quantity] = $value;
                $insert[$x][$this->ci->Sessionlock_model->starttime] = allTimeFormats('',11);
                $insert[$x][$this->ci->Sessionlock_model->endtime] = allTimeFormats('+10min',11);
                $x++;
            }
            $this->ci->Sessionlock_model->setInsertUpdateData($insert);
            $insertCount = $this->ci->Sessionlock_model->insertMultiple_data();
            if ($insertCount > 0) {
                $output['status'] = TRUE;
                $output['response']['sessionLock'] = array('status' => true);
                $output['response']['total'] = $insertCount;
                return $output;
            }
            $output['status'] = FALSE;
            $output['response']['messages'][] = SESSIONLOCK_INSERTION_FAILED;
            $output['response']['total'] = 0;
            return $output;
        } else {
            return $orderlogResponse;
        }
    }

    public function getCountByTicketIds($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        //Checking validation using Group Validation (signup)
        if ($this->ci->form_validation->run('getsessionlock') === FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
            return $output;
        }
        $this->ci->Sessionlock_model->resetVariable();
        $ticketIds = $inputArray['ticketIds'];
        $select['totaltemptickets'] = 'SUM(' . $this->ci->Sessionlock_model->quantity . ')';
        $select['ticketid'] = $this->ci->Sessionlock_model->ticketid;
        $this->ci->Sessionlock_model->setSelect($select);
        $where[$this->ci->Sessionlock_model->endtime . ' > '] = allTimeFormats('',11);;
        $where[$this->ci->Sessionlock_model->deleted] = 0;
        $whereIn[$this->ci->Sessionlock_model->ticketid] = $ticketIds;
        $this->ci->Sessionlock_model->setWhere($where);
        $this->ci->Sessionlock_model->setWhereIns($whereIn);
        $groupBy[] = $this->ci->Sessionlock_model->ticketid;
        $this->ci->Sessionlock_model->setGroupBy($groupBy);
        $countTempTicketsResponse = $this->ci->Sessionlock_model->get();
        //echo $this->ci->db->last_query();
        $indexedTempTickets = commonHelperGetIdArray($countTempTicketsResponse, 'ticketid');
        // if ($countTempTicketsResponse == 0) {
        foreach ($ticketIds as $id) {
            $tempTicketsList[$id] = isset($indexedTempTickets[$id]) ? ($indexedTempTickets[$id]['totaltemptickets']) : 0;
        }
        $output['status'] = TRUE;
        $output['response']['sessionlockList'] = $tempTicketsList;
        $output['response']['total'] = count($tempTicketsList);
        return $output;
        // }
//        foreach ($ticketIds as $id) {
//            $tempTicketsList[$id]=isset()
//        }
    }

    /*
     * Function to delete the Session lock of the order
     * @access	public
     * @param
     *          - orderId (required)
     * @return  release the tickets which are locked for the order
     */

    public function deleteTicketLock($insertArr) {

        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($insertArr);
        $this->ci->form_validation->set_rules('orderId', 'order id', 'required_strict');

        if (!empty($insertArr) && $this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        $this->ci->Sessionlock_model->resetVariable();
        $updateSessionLockParam[$this->ci->Sessionlock_model->deleted] = 1;

        $where[$this->ci->Sessionlock_model->orderid] = $insertArr['orderId'];
        $this->ci->Sessionlock_model->setWhere($where);

        $this->ci->Sessionlock_model->setInsertUpdateData($updateSessionLockParam);
        $updateSessionLock = $this->ci->Sessionlock_model->update_data();

        if ($updateSessionLock) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = SUCCESS_SESSIONLOCK_UPDATED;
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_SESSIONLOCK_UPDATED;
            $output['statusCode'] = STATUS_SERVER_ERROR;
            return $output;
        }
    }

}
