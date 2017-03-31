<?php

require_once 'common_model.php';

class Offlinepromoterdiscounts_model extends Common_model {

    function __construct() {
        parent::__construct();
        $this->setTableName("offlinepromoterdiscounts");

        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        $this->id = "id";
        $this->promoterid = "promoterid";
        $this->eventid = "eventid";
        $this->ticketid = "ticketid";
         
        $this->discountid = "discountid";
        $this->deleted = "deleted";
        $this->createdby = "createdby";
        $this->modifiedby = "modifiedby";
    }

}

?>