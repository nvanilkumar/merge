<?php

require_once 'common_model.php';

class Collaborator_model extends Common_model {

    var $id;
    var $eventid;
    var $userid;
    var $name;
    var $email;
    var $mobile;
    var $status;
    var $deleted;
    var $cts;
    var $mts;
    var $createdby;
    var $modifiedby;

    function __construct() {
        parent::__construct();
        $this->setTableName("collaborator");

        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        $this->id = "id";
        $this->eventid = "eventid";
        $this->userid = "userid";
        $this->name = "name";
        $this->email = "email";
        $this->mobile = "mobile";
        $this->status = "status";
        $this->deleted = "deleted";
        $this->cts = "cts";
        $this->mts = "mts";
        $this->createdby = "createdby";
        $this->modifiedby = "modifiedby";
    }

}

?>