<?php

require_once 'common_model.php';

class Comment_model extends Common_model {

    var $id;
    var $eventsignupid;
    var $eventid;
    var $comment;
    var $type;
    var $createdBy;
    var $modifiedBy;

    public function __construct() {
        parent::__construct();
        //setting the table name
        $this->setTableName("comment");

        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        $this->id = "id";
        $this->eventsignupid = "eventsignupid";
        $this->eventid = "eventid";
        $this->comment = "comment";
        $this->type = "type";
        $this->createdby = "createdby";
        $this->modifiedby = "modifiedby";
    }

}
