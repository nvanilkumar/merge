<?php
require_once 'common_model.php';
class Userpoint_model extends Common_model {
	
  

    function __construct() {
        parent::__construct();
         $this->setTableName("userpoint");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }
    
    
    private function _setFieldNames() {
    	$this->id = "id";
    	$this->userid = "userid";
    	$this->points = "points";
		$this->eventsignupid = "eventsignupid";
    	$this->type = "type";
    	$this->status = "status";
    	$this->affiliate = "affiliate";
    	$this->createdby = "createdby";
    	$this->modifiedby = "modifiedby";
    }


}

?>