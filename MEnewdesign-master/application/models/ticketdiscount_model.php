<?php
require_once 'common_model.php';
class Ticketdiscount_model extends Common_model {

    var $id;
    var $discountid;
    var $ticketid;
    var $status;
    var $createdby;
    var $modifiedby;
    var $deleted;

    public function __construct() {
        parent::__construct();
        //setting the table name
        $this->setTableName("ticketdiscount");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        $this->id = "id";
        $this->discountid = "discountid";
        $this->ticketid = "ticketid";
        $this->status = "status";
        $this->createdby = "createdby";
        $this->modifiedby = "modifiedby";
        $this->deleted = "deleted";
    }

}
