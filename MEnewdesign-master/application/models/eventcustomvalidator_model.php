<?php

require_once 'common_model.php';

class Eventcustomvalidator_model extends Common_model {

    function __construct() {
        parent::__construct();
        $this->setTableName("eventcustomvalidator");

        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        $this->id = "id";
        $this->value = "value";
        $this->eventid = "eventid";
        $this->name = "name";
        $this->status = "status";
        $this->deleted = "deleted";
        $this->createdby = "createdby";
        $this->modifiedby = "modifiedby";
    }

}

?>