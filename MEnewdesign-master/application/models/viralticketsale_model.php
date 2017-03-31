<?php
require_once 'common_model.php';
class ViralticketSale_model extends Common_model {
    var $id;
    var $referreruserid;
    var $eventsignupticketdetailid;
    var $viralticketsettingid;
    var $referreruserpointid;
    var $referralcode;
    var $createdby;
    var $modifiedby;	
  

    function __construct() {
        parent::__construct();
         $this->setTableName("viralticketsale");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }
    
    
    private function _setFieldNames() {
    	$this->id = "id";
    	$this->referreruserid = "referreruserid";
    	$this->eventsignupticketdetailid = "eventsignupticketdetailid";
    	$this->viralticketsettingid = "viralticketsettingid";
    	$this->referreruserpointid = "referreruserpointid";
    	$this->referralcode = "referralcode";
    	$this->createdby = "createdby";
    	$this->modifiedby = "modifiedby";
    }


}

?>