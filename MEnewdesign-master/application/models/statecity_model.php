<?php

require_once 'common_model.php';

class Statecity_model extends Common_model {

    var $id;
    var $stateid;
    var $cityid;
    var $createdby;
    var $modifiedby;
    var $mts;

    public function __construct() {
        parent::__construct();
        //setting the table name
        $this->setTableName("statecity");

        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        $this->id = "id";
        $this->stateid = "stateid";
        $this->cityid = "cityid";
        $this->createdby = "createdby";
        $this->modifiedby = "modifiedby";
        $this->mts="mts";
    }

}
