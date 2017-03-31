<?php

require_once 'common_model.php';

class Sessionlock_model extends Common_model {

    function __construct() {
        parent::__construct();
        $this->setTableName("sessionlock");

        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        $this->id = "id";
        $this->orderid = "orderid";
        $this->deleted = "deleted";
        $this->ticketid = "ticketid";
        $this->quantity = "quantity";
        $this->starttime = "starttime";
        $this->endtime = "endtime";
    }

}

?>