<?php
require_once 'common_model.php';
class Attendeedetail_model extends Common_model {
	var $id;
        var $customfieldid;
        var $value;
        var $attendeeid;
        var $commonfieldid;
        var $deleted;
        var $createdby;
        var $modifiedby;
        
    function __construct() {
        parent::__construct();
         $this->setTableName("attendeedetail");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }
    
    
    private function _setFieldNames() {
    	$this->id = "id";
    	$this->customfieldid = "customfieldid";
    	$this->value = "value";
    	$this->attendeeid = "attendeeid";
    	$this->commonfieldid = "commonfieldid";
    	$this->deleted = "deleted";
    	$this->createdby = "createdby";
    	$this->modifiedby = "modifiedby";
    }


}

?>