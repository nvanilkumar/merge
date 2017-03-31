<?php
require_once 'common_model.php';
class Eventtag_model extends Common_model {

    var $id;
    var $eventid;
    var $tagid;
    var $deleted;
    

    function __construct() {
        parent::__construct();
        $this->setTableName('eventtag');
        $this->_setFieldNames();
    }
    //Seting the properties of the table
    private function _setFieldNames() {
        $this->id = "id";
        $this->eventid = "eventid";
        $this->tagid = "tagid";
        $this->deleted="deleted";
    }
}
?>