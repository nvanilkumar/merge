<?php
require_once 'common_model.php';
class Attendee_model extends Common_model {
	
  

    function __construct() {
        parent::__construct();
         $this->setTableName("attendee");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }
    
    
    private function _setFieldNames() {
    	$this->id = "id";
    	$this->eventsignupid = "eventsignupid";
    	$this->ticketid = "ticketid";
    	$this->primary = "primary";
    	$this->order = "order";
    	$this->deleted = "deleted";
    	$this->createdby = "createdby";
    	$this->modifiedby = "modifiedby";
    }


}

?>