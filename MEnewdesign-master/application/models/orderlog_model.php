<?php
require_once 'common_model.php';
class Orderlog_model extends Common_model {

    var $id;
    var $orderid;
    var $userid;
    var $eventsignup;
    var $data;
    var $userip;
    var $status;
    var $deleted;
    var $createdby;
    var $modifiedby;

    public function __construct() {
        parent::__construct();
        //setting the table name
        $this->setTableName("orderlog");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        $this->id = "id";
        $this->orderid = "orderid";
        $this->userid = "userid";
        $this->eventsignup = "eventsignup";
        $this->data = "data";
        $this->userip = "userip";
        $this->status = "status";
        $this->deleted = "deleted";
        $this->createdby = "createdby";
        $this->modifiedby = "modifiedby";
    }

}
