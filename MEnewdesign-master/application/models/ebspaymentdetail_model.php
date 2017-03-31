<?php
require_once 'common_model.php';
class Ebspaymentdetail_model extends Common_model {

    var $id;
    var $eventsignupid;
    var $paymentid;
    var $transactionid;
    var $statuscode;
    var $statusmessage;
    var $deleted;
    var $createdby;
    var $modifiedby;

    public function __construct() {
        parent::__construct();
        //setting the table name
        $this->setTableName("ebspaymentdetail");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        $this->id = "id";
        $this->eventsignupid = "eventsignupid";
        $this->paymentid = "paymentid";
        $this->transactionid = "transactionid";
        $this->statuscode = "statuscode";
        $this->statusmessage = "statusmessage";
        $this->deleted = "deleted";
        $this->createdby = "createdby";
        $this->modifiedby = "modifiedby";
    }

}
