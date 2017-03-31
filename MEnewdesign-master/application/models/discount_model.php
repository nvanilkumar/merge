<?php
require_once 'common_model.php';
class Discount_model extends Common_model {

    var $id;
    var $name;
    var $eventid;
    var $type;
    var $calculationmode;
    var $value;
    var $startdatetime;
    var $enddatetime;
    var $minticketstobuy;
    var $maxticketstobuy;
    var $usagelimit;
    var $code;
    var $status;
    var $totalused;
    var $createdby;
    var $modifiedby;
    var $deleted;
 

    public function __construct() {
        parent::__construct();
        $this->setTableName("discount");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        
        $this->id = "id";
        $this->name = "name";
        $this->eventid = "eventid";
        $this->type = "type";
        $this->calculationmode = "calculationmode";
        $this->value = "value";
        $this->startdatetime = "startdatetime";
        $this->enddatetime = "enddatetime";
        $this->minticketstobuy = "minticketstobuy";
        $this->maxticketstobuy = "maxticketstobuy";
        $this->usagelimit = "usagelimit";
        $this->code = "code";
        $this->totalused = "totalused";
        $this->status = "status";
        $this->deleted = "deleted";
        $this->createdby = "createdby";
        $this->modifiedby = "modifiedby";
    }

}
