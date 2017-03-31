<?php

require_once 'common_model.php';

class Customfieldvalue_model extends Common_model {

 
    var $customfieldid;
    var $value;
    var $isdefault;
    var $deleted;
    var $id;

    public function __construct() {
        parent::__construct();
        //setting the table name
        $this->setTableName("customfieldvalue");
        //Giving alias names to table field names
        $this->_setFieldNames();
    }
    /**
     * To set the field names
     */
    private function _setFieldNames() {
        $this->customfieldid = "customfieldid";
        $this->value = "value";
        $this->isdefault = "isdefault";
        $this->deleted = "deleted";
        $this->id="id";
        
    }
    

}
