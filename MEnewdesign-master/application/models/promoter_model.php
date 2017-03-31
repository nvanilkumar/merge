<?php
require_once 'common_model.php';
class Promoter_model extends Common_model {
	
    var $id;
    var $name;
    var $userid;
    var $eventid;
    var $code;
	var $orgpromoteurl;
    var $mobile;
    var $ticketslimit;
    var $type;
    var $status;
    var $deleted;
    var $cts;
    var $mts;    
    var $createdby;
    var $modifiedby;  

    function __construct() {
        parent::__construct();
         $this->setTableName("promoter");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }
    
    
    private function _setFieldNames() {
    	$this->id = "id";
    	$this->name = "name";
    	$this->userid = "userid";
    	$this->eventid = "eventid";
    	$this->code = "code";
		$this->orgpromoteurl = "orgpromoteurl";
    	$this->type = "type";
        $this->status = "status";
         $this->deleted = "deleted";
        $this->mobile = "mobile";
        $this->ticketslimit = "ticketslimit";
    	$this->cts = "cts";
    	$this->mts = "mts";        
    	$this->createdby = "createdby";
    	$this->modifiedby = "modifiedby";
    }
}

?>