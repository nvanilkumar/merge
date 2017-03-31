<?php

require_once 'common_model.php';

class Eventpromocodes_model extends Common_model {

    var $id;
    var $eventid;
    var $promocode;
    var $soldquantity;
    var $totalquantity;
    var $status;
    var $deleted;
    var $cts;
    var $mts;
    var $createdby;
    var $modifiedby;

    public function __construct() {
        parent::__construct();
        //setting the table name
        $this->setTableName("eventpromocodes");

        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    //Set the field values
    private function _setFieldNames() {
        $this->id = "id";
        $this->eventid = 'eventid';
        $this->promocode = 'promocode';
        $this->soldquantity = 'soldquantity';
        $this->totalquantity = 'totalquantity';
        $this->status = "status";
        $this->deleted = "deleted";
        $this->cts = 'cts';
        $this->mts = 'mts';
        $this->createdby = 'createdby';
        $this->modifiedby = 'modifiedby';
    }

}

?>