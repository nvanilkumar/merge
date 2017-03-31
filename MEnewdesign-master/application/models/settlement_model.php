<?php
require_once 'common_model.php';
class Settlement_model extends Common_model {

    var $id;
    var $eventid;
    var $paymentadvicefileid;
    var $chequefileid;
    var $cyberrecieptfileid;
    var $note;
    var $netamount;
    var $amountpaid;
    var $paymentdate;
    var $paymenttype;
    var $currencyid;
    var $status;
    var $createdby;
    var $modifiedby;
    var $cts;

    public function __construct() {
        parent::__construct();
        //setting the table name
        $this->setTableName("settlement");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        $this->id = "id";
        $this->eventid = "eventid";
        $this->paymentadvicefileid = "paymentadvicefileid";
        $this->chequefileid = "chequefileid";
        $this->cyberrecieptfileid = "cyberrecieptfileid";
        $this->note = "note";
        $this->netamount = "netamount";
        $this->amountpaid = "amountpaid";
        $this->paymentdate = "paymentdate";
        $this->paymenttype = "paymenttype";
        $this->currencyid = "currencyid";
        $this->status = "status";
        $this->createdby = "createdby";
        $this->modifiedby = "modifiedby";
        $this->cts = "cts";
    }

}
