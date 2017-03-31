<?php

require_once 'common_model.php';

class Collaboratoraccess_model extends Common_model {

    var $id;
    var $collaboratorid;
    var $module;
    var $status;
    var $cts;
    var $mts;
    var $createdby;
    var $modifiedby;

    function __construct() {
        parent::__construct();
        $this->setTableName("collaboratoraccess");

        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        $this->id = "id";
        $this->collaboratorid = "collaboratorid";
        $this->module = "module";
        $this->status = "status";
        $this->cts = "cts";
        $this->mts = "mts";
        $this->createdby = "createdby";
        $this->modifiedby = "modifiedby";
    }

}

?>