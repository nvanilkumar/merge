<?php
require_once 'common_model.php';
class Commission_model extends Common_model {

    var $id;
    var $EventId;
    var $contractdocument;
    var $value;
    var $type;
    var $global;
    var $deleted;
    var $createdby;
    var $modifiedby;

    public function __construct() {
        parent::__construct();
        //setting the table name
        $this->setTableName("commission");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        $this->id = "id";
        $this->EventId = "EventId";
        $this->contractdocument = "contractdocument";
        $this->value = "value";
        $this->type = "type";
        $this->global = "global";
        $this->deleted = "deleted";
        $this->createdby = "createdby";
        $this->modifiedby = "modifiedby";
    }

}
