<?php

class Taxmapping_model extends Common_model {

    var $id;
    var $taxid;
    var $value;
    var $countryid;
    var $stateid;
    var $cityid;
    var $type;
    var $deleted;
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
        $this->taxid = "taxid";
        $this->ticketid = "ticketid";
        $this->value = "value";
        $this->countryid = "countryid";
        $this->stateid="stateid";
        $this->cityid="cityid";
        $this->type="type";//state,city,country
        $this->deleted="deleted";
        $this->cts = "cts";
        $this->mts = "mts";
        $this->createdby = "createdby";
        $this->modifiedby = "modifiedby";
        $this->dbTable = "taxmapping";
    }

}

?>