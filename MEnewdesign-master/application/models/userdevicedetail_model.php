<?php

require_once 'common_model.php';

class Userdevicedetail_model extends Common_model {

    var $id;
    var $userid;
    var $name;
    var $value;
    var $parentid;    
    var $cts;
    var $mts;
    var $createdby;
    var $modifiedby;
   
    function __construct() {
        parent::__construct();
        //setting the table name
        $this->setTableName("userdevicedetail");
        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    //Set the field values
    private function _setFieldNames() {
        $this->id = "id";
        $this->userid = "userid";
        $this->name = "name";
        $this->value = "value";
        $this->parentid = "parentid";
    }
}

?>