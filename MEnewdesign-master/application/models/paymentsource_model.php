<?php
require_once 'common_model.php';
class Paymentsource_model extends Common_model {

    var $id;
    var $name;
    var $status;
    var $deleted;
    var $createdby;
    var $modifiedby;
    var $eventid;

    public function __construct() {
        parent::__construct();
        //setting the table name
        $this->setTableName("paymentsource");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        $this->id = "id";
        $this->name = "name";
        $this->status = "status";
        $this->deleted = "deleted";
        $this->createdby = "createdby";
        $this->modifiedby = "modifiedby";
        $this->eventid = "eventid";
    }

}
