<?php
require_once 'common_model.php';
class Eventsignup_Ticketdetail_model extends Common_model {
	
  

    function __construct() {
        parent::__construct();
         $this->setTableName("eventsignupticketdetail");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }
    
    
    private function _setFieldNames() {
    	$this->id = "id";
    	$this->eventsignupid = "eventsignupid";
    	$this->ticketid = "ticketid";
    	$this->ticketquantity = "ticketquantity";
    	$this->bulkdiscountamount = "bulkdiscountamount";
    	$this->deleted = "deleted";
    	$this->discountamount = "discountamount";
    	$this->amount = "amount";
        $this->totalamount = "totalamount";
    	$this->createdBy = "createdBy";
    	$this->modifiedBy = "modifiedBy";
    }


}

?>