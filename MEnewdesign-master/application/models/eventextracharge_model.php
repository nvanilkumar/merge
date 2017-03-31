<?php

require_once 'common_model.php';

class Eventextracharge_model extends Common_model {

    function __construct() {
        parent::__construct();
        $this->setTableName("eventextracharge");

        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        $this->id = "id";
    	$this->eventid = "eventid";
    	$this->label = "label";
    	$this->value = "value";
    	$this->currencyid = "currencyid";
		$this->type = "type";
		$this->status = "status";
    	$this->deleted = "deleted";
    	$this->amount = "amount";
    	$this->createdBy = "createdby";
    	$this->modifiedBy = "modifiedby";
    }

}

?>