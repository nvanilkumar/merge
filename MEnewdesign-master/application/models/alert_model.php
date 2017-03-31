<?php
require_once 'common_model.php';
class Alert_model extends Common_model {
	
  

    function __construct() {
        parent::__construct();
         $this->setTableName("alert");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }
    
    
    private function _setFieldNames() {
    	$this->id = "id";
    	$this->userid = "userid";
    	$this->type = "type";
    	$this->createdby = "createdby";
    	$this->modifiedby = "modifiedby";
    	$this->status = "status";
    }


}

?>