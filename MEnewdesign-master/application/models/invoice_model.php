<?php
require_once 'common_model.php';
class Invoice_model extends Common_model {
	
  

    function __construct() {
        parent::__construct();
         $this->setTableName("invoice");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }
    
    
    private function _setFieldNames() {
    	$this->id = "id";
    	$this->eventid = "eventid";
    	$this->paymentgatewayid = "paymentgatewayid";
    	$this->deleted = "deleted";
    	$this->createdby = "createdby";
    	$this->modifiedby = "modifiedby";
    }


}

?>