<?php
require_once 'common_model.php';
class Sentmessage_model extends Common_model {

    var $id;
    var $userid;
    var $messageid;
    var $eventsignupid;
    var $createdby;
    var $modifiedby;
    var $status;

    public function __construct() {
        parent::__construct();
        //setting the table name
        $this->setTableName("sentmessage");        
        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        $this->id = "id";
        $this->userid = "userid";
        $this->messageid = "messageid";
        $this->eventsignupid = "eventsignupid";
        $this->createdby = "createdBy";
        $this->modifiedby = "modifiedBy";
        $this->status = "status";
    }

}
