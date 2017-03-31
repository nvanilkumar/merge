<?php

/**
 * Piwik recommendations related business logic will be defined in this class
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

class PiwikRecommendations_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('PiwikRecommendations_model');
    }

    

    /*
     * Function to get the Discount Details
     *
     * @access public
     * @param $inputArray contains
     *     eventId - integer
     *     discountId - integer(optional)
     * @return array
     */
	 
	

    public function getSpecialDiscounts($inputArray) {
        
		
        $this->ci->SpecialDiscount_model->resetVariable();
        $selectData['id'] = $this->ci->SpecialDiscount_model->id;
        $selectData['type'] = $this->ci->SpecialDiscount_model->type;
        $selectData['title'] = $this->ci->SpecialDiscount_model->title;
        $selectData['promocode'] = $this->ci->SpecialDiscount_model->promocode;
        $selectData['eventid'] = $this->ci->SpecialDiscount_model->eventid;
		$selectData['cityid'] = $this->ci->SpecialDiscount_model->cityid;
        $selectData['status'] = $this->ci->SpecialDiscount_model->status;
		
        $this->ci->SpecialDiscount_model->setSelect($selectData);
        $where[$this->ci->SpecialDiscount_model->type] = $inputArray['micrositename'];
		if($inputArray['cityId'] > 0)
		{
			$where[$this->ci->SpecialDiscount_model->cityid] = $inputArray['cityId'];
		}
		$where[$this->ci->SpecialDiscount_model->status] = $inputArray['status'];
        $where[$this->ci->SpecialDiscount_model->deleted] = 0;
		$where['YEAR(`'.$this->ci->SpecialDiscount_model->cts.'`)'] = date("Y");
       
	    
        
        $this->ci->SpecialDiscount_model->setWhere($where);
        $allDiscountList = $this->ci->SpecialDiscount_model->get();
        if (count($allDiscountList) == 0) {
            $output = parent::createResponse(TRUE, ERROR_NO_DISCOUNT, STATUS_OK, 0, 'discountList', array());
            return $output;
        }
        $output = parent::createResponse(TRUE, array(), STATUS_OK, count($allDiscountList), 'discountList', $allDiscountList);
        return $output;
    }

    
    

}

?>