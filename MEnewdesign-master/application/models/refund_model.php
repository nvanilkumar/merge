<?php

require_once 'common_model.php';

class Refund_model extends Common_model {

    var $id;
    var $eventsignupid;
    var $amount;
    var $initiateddate;
    var $refunddate;
    var $deleted;
    var $cts;
    var $mts;
    var $createdby;
    var $modifiedby;
    var $tableName;

    public function __construct() {
        parent::__construct();
        //Giving alias names to table field names
        $this->_setFieldNames();
        $this->setTableName($this->tableName);
    }

    private function _setFieldNames() {
        $this->id = "id";
        $this->eventsignupid = "eventsignupid";
        $this->amount = "amount";
        $this->initiateddate = "initiateddate";
        $this->refunddate = "refunddate";
        $this->deleted = "deleted";
        $this->cts = "cts";
        $this->mts = "mts";
        $this->createdby = "createdby";
        $this->modifiedby = "modifiedby";
        $this->tableName="refund";
    }

}
