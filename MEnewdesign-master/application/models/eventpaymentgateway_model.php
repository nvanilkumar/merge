<?php

require_once 'common_model.php';

class EventpaymentGateway_model extends Common_model {

    var $id;
    var $eventid;
    var $paymentgatewayid;
    var $gatewaytext;
    var $deleted;
    var $modifiedby;
    var $createdby;
    var $status;

    function __construct() {
        parent::__construct();
        $this->setTableName("eventpaymentgateway");
        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        $this->id = "id";
        $this->eventid = "eventid";
        $this->paymentgatewayid = "paymentgatewayid";
        $this->gatewaytext = "gatewaytext";
        $this->extraparams = "extraparams";
        $this->deleted = "deleted";
        $this->modifiedby = "modifiedby";
        $this->createdby = "createdby";
        $this->status = "status";
    }

}
?>

