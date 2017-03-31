<?php
require_once 'common_model.php';
class Offlinepromoterticketmapping_model extends Common_model {
	
  

    function __construct() {
        parent::__construct();
         $this->setTableName("offlinepromoterticketmapping");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }
    
    
    private function _setFieldNames() {
    	$this->id = "id";
    	$this->promoterid = "promoterid";
    	$this->eventid = "eventid";
    	$this->ticketid = "ticketid";
	  	$this->status = "status";
    	$this->deleted = "deleted";
    	$this->createdby = "createdby";
    	$this->modifiedby = "modifiedby";
    }


}

?>