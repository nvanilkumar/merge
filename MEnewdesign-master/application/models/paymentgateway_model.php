<?php
require_once 'common_model.php';
class Paymentgateway_model extends Common_model {

    var $id;
    var $name;
    var $merchantid;
    var $hashkey;
    var $returnurl;
    var $posturl;
    var $status;
    var $type;
    var $extraparams;
    var $description;
    var $gatewaytext;
    var $deleted;
    var $createdby;
    var $modifiedby;
    var $default;

    public function __construct() {
        parent::__construct();
        //setting the table name
        $this->setTableName("paymentgateway");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        $this->id = "id";
        $this->name = "name";
        $this->merchantid = "merchantid";
        $this->hashkey = "hashkey";
        $this->returnurl = "returnurl";
        $this->posturl = "posturl";
        $this->extraparams = "extraparams";
        $this->description = "description";
        $this->gatewaytext="gatewaytext";
        $this->status = "status";
        $this->type = "type";
        $this->deleted = "deleted";
        $this->createdby = "createdby";
        $this->modifiedby = "modifiedby";
        $this->default = "default";
    }

}
