<?php
require_once 'common_model.php';
class ViralticketSetting_model extends Common_model {
    var $id;
    var $eventid;
    var $ticketid;
    var $type;
    var $referrercommission;
    var $receivercommission;
    var $createdby;
    var $modifiedby;


    function __construct() {
        parent::__construct();
         $this->setTableName("viralticketsetting");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }
    
    
    private function _setFieldNames() {
    	$this->id = "id";
    	$this->eventid = "eventid";
    	$this->ticketid = "ticketid";
        $this->type = "type";
    	$this->referrercommission = "referrercommission";
    	$this->receivercommission = "receivercommission";
        $this->status = "status";
    	$this->createdby = "createdby";
    	$this->modifiedby = "modifiedby";
    }
    public function onduplicate($data) {
        $sql = $this->db->insert_string('viralticketsetting', $data) . ' ON DUPLICATE KEY UPDATE type=' . $data['type'] . ',' . 'referrercommission=' . $data['referrercommission'] . ',' . 'receivercommission=' . $data['receivercommission'] . ',' . 'status=' . $data['status'];
        $this->db->query($sql);
        $id = $this->db->insert_id();
        return $id;
    }


}

?>