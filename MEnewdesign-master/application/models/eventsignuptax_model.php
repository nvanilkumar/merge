<?php

class Eventsignuptax_model extends Common_model {

    var $id;
    var $eventsignupid;
    var $ticketid;
    var $taxmappingid;
    var $taxamount;
    var $cts;
    var $mts;
    var $createdby;
    var $modifiedby;
    var $dbTable;

    function __construct() {
        parent::__construct();
        $this->_setFieldNames();
        $this->setTableName($this->dbTable);
    }

    //Seting the properties of the table
    private function _setFieldNames() {
        $this->id = "id";
        $this->eventsignupid = "eventsignupid";
        $this->ticketid = "ticketid";
        $this->taxmappingid = "taxmappingid";
        $this->taxamount = "taxamount";
        $this->cts = "cts";
        $this->mts = "mts";
        $this->createdby = "createdby";
        $this->modifiedby = "modifiedby";
        $this->dbTable = "eventsignuptax";
    }

}

?>