<?php

/**
 * common filds related business logic will be defined in this class
 * Getting Banners Related data
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @param		CountryId - required
 *                      cityId,categoryId,type (optional)
 * @addTicket		name,type,description,eventId,price,quantity,
 *                     minOrderQuantity,maxOrderQuantity,startTime,endTime,order,currencyId
 *                    soldOut,endDate,startDate,displayStatus,label[0],value[0]
 *                    @lable and value should be arrays
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0
 * @Created     16-06-2015
 * @Last Modified 16-06-2015
 */
require_once (APPPATH . 'handlers/handler.php');

//require_once (APPPATH . 'handlers/ticketdiscount_handler.php');

class Commonfield_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Commonfield_model');
    }

    /*
     * Function to get the commonfield Details
     *
     * @access public
     * 
     */

    function getCommonfieldList() {
        $this->ci->Commonfield_model->resetVariable();
        $selectData['id'] = $this->ci->Commonfield_model->id;
        $selectData['name'] = $this->ci->Commonfield_model->name;
        $selectData['type'] = $this->ci->Commonfield_model->type;
        $selectData['order'] = $this->ci->Commonfield_model->order;
        $this->ci->Commonfield_model->setSelect($selectData);
        $where[$this->ci->Commonfield_model->deleted] = 0;
        $this->ci->Commonfield_model->setWhere($where);
        $commonFieldList = $this->ci->Commonfield_model->get();
        if (count($commonFieldList) == 0) {
            $output = parent::createResponse(TRUE, ERROR_NO_REFUNDS, STATUS_OK, 0);
            return $output;
        }
        $output = parent::createResponse(TRUE, array(), STATUS_OK, count($commonFieldList), 'commonfieldList', $commonFieldList);
        return $output;
    }
}

?>