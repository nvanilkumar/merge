<?php

class Bookmark_model extends Common_model {

    var $id;
    var $eventid;
    var $userid;
    var $deleted;
    var $createdby;
    var $modifiedby;
    var $dbTable;

    function __construct() {
        parent::__construct();
        $this->setTableName('bookmark');
        $this->_setFieldNames();
    }

    //Seting the properties of the table
    private function _setFieldNames() {
        $this->id = "id";
        $this->eventid = "eventId";
        $this->userid = "userId";
        $this->deleted = "deleted";
        $this->createdby = "createdby";
        $this->modifiedby = "modifiedby";
        $this->dbTable = "bookmark";
    }
}

?>