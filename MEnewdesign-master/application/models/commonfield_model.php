<?php

require_once 'common_model.php';

class Commonfield_model extends Common_model {

    var $id;
    var $name;
    var $type;
    var $order;
    var $deleted;
    var $cts;
    var $mts;
    var $createdby;
    var $modifiedby;
    var $tableName;
    var $displaystatus;
    var $status;
    var $fieldmandatory;
    var $displayonticket;
    public function __construct() {
        parent::__construct();
        //Giving alias names to table field names
        $this->_setFieldNames();
        $this->setTableName($this->tableName);
    }

    private function _setFieldNames() {
        $this->id = "id";
        $this->name = "name";
        $this->type = "type";
        $this->order = "order";
        $this->deleted = "deleted";
        $this->cts = "cts";
        $this->mts = "mts";
        $this->createdby = "createdby";
        $this->modifiedby = "modifiedby";
        $this->tableName="commonfield";
        $this->displaystatus="displaystatus";
        $this->status="status";
        $this->fieldmandatory="fieldmandatory";
        $this->displayonticket="displayonticket";
    }

}
