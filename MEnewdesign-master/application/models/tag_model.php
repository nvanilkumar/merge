<?php
require_once 'common_model.php';
class Tag_model extends Common_model {

    var $id;
    var $name;
    var $status;
    var $deleted;

    function __construct() {
        parent::__construct();
        $this->setTableName('tag');
        $this->_setFieldNames();
    }
    //Seting the properties of the table
    private function _setFieldNames() {
        $this->id = "id";
        $this->name = "name";
        $this->status = "status";
        $this->deleted = "deleted";
    }
}
?>