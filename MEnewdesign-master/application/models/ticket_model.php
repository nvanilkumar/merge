<?php
require_once 'common_model.php';
class Ticket_model extends Common_model {

    var $id;
    var $name;
    var $description;
    var $eventid;
    var $price;
    var $quantity;
    var $minorderquantity;
    var $maxorderquantity;
    var $startdatetime;
    var $enddatetime;
    var $status;
    var $totalsoldtickets;
    var $type;
    var $order;
    var $displaystatus;
    var $currencyid;
    var $deleted;
    var $soldout;
    var $createdby;
    var $modifiedby;
 

    public function __construct() {
        parent::__construct();
        //setting the table name
        $this->setTableName("ticket");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        $this->id = "id";
        $this->name = "name";
        $this->description = "description";
        $this->eventid = "eventid";
        $this->price = "price";
        $this->quantity = "quantity";
        $this->minorderquantity = "minorderquantity";
        $this->maxorderquantity = "maxorderquantity";
        $this->startdatetime = "startdatetime";
        $this->enddatetime = "enddatetime";
        $this->status = "status";
        $this->totalsoldtickets = "totalsoldtickets";
        $this->type = "type";
        $this->order = "order";
        $this->displaystatus = "displaystatus";
        $this->currencyid = "currencyid";
        $this->deleted = "deleted";
        $this->soldout = "soldout";
        $this->createdby = "createdby";
        $this->modifiedby = "modifiedby";
    }

}
