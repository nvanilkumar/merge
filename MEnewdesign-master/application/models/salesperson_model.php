<?php
require_once 'common_model.php';
class Salesperson_model extends Common_model {
	
  

    function __construct() {
        parent::__construct();
         $this->setTableName("salesperson");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }
    
    
    private function _setFieldNames() {
    	$this->id = "id";
    	$this->name = "name";
    	$this->mobile = "mobile";
    	$this->email = "email";
    	$this->signature = "signature";
    	$this->userid = "userid";
    	$this->status = "status";
    	$this->createdby = "createdby";
    	$this->modifiedby = "modifiedby";
        $this->deleted = "deleted";
    }


}

?>