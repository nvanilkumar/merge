<?php
//include_once 'commonModel.php';
class Currency_model extends Common_model {

    var $id;
    var $name;
    var $code;
    var $symbol;
    var $status;
    var $deleted;

    public function __construct() {
        parent::__construct();
        //setting the table name
        $this->setTableName("currency");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        $this->id = "id";
        $this->name = "name";
        $this->code = "code";
        $this->symbol = "symbol";
        $this->deleted = "deleted";
        $this->status = "status";
        $this->mts = "mts";
    }

}
