<?php

require_once 'common_model.php';

class Venueseat_model extends Common_model {

    var $id;
    var $venueid;
    var $gridposition;
    var $seatno;
    var $price;
    var $type;
    var $status;
    var $eventid;
    var $eventsignupid;
    var $bdate;
    var $ticketid;
    var $group;
    var $createdby;
    var $modifiedby;
    var $deleted;

    function __construct() {
        parent::__construct();
        $this->setTableName('venueseat');
        $this->_setFieldNames();
    }

    //Seting the properties of the table
    private function _setFieldNames() {
        $this->id = "Id";
        $this->venueid = "VenueId";
        $this->gridposition = "GridPosition";
        $this->seatno = "Seatno";
        $this->price = 'Price';
        $this->type = 'Type';
        $this->status = 'Status';
        $this->eventid = 'EventId';
        $this->eventsignupid = 'eventsignupid';
        $this->bdate = 'BDate';
        $this->ticketid = 'ticketid';
        $this->group = 'group';
        $this->createdby = 'createdby';
        $this->modifiedby = 'modifiedby';
        $this->deleted = 'deleted';
    }

}

?>