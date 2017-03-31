<?php
require_once 'common_model.php';
class Thirdpartypayment_model extends Common_model {

    var $id;
    var $referenceid;
    var $transactionid;
    var $data;
    var $status;
    var $paymentsourceid;
    var $amount;
    var $paymentgatewaytype;
    var $createdby;
    var $modifiedby;

    public function __construct() {
        parent::__construct();
        //setting the table name
        $this->setTableName("thirdpartypayment");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        $this->id = "id";
        $this->referenceid = "referenceid";
        $this->transactionid = "transactionid";
        $this->data = "data";
        $this->status = "status";
        $this->paymentsourceid = "paymentsourceid";
        $this->amount = "amount";
        $this->paymentgatewaytype = "paymentgatewaytype";
        $this->createdby = "createdby";
        $this->modifiedby = "modifiedby";
    }

}
