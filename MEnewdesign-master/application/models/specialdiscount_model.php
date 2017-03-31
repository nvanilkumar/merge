<?php
require_once 'common_model.php';
class SpecialDiscount_model extends Common_model {

    var $id;
    var $type;
    var $title;
    var $promocode;
    var $eventid;
	var $cityid;
    var $status;
    var $createdby;
    var $modifiedby;
    var $deleted;
 

    public function __construct() {
        parent::__construct();
        $this->setTableName("specialdiscount");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        
        $this->id = "id";
		$this->type = "type";
        $this->title = "title";
		$this->promocode = "promocode";
        $this->eventid = "eventid";
		$this->cityid = "cityid";
        $this->status = "status";
        $this->createdby = "createdby";
        $this->modifiedby = "modifiedby";
		$this->deleted = "deleted";
		$this->cts = "cts";
    }

}
