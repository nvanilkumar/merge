<?php

require_once 'common_model.php';

class Customfield_model extends Common_model {

    var $eventid;
    var $fieldname;
    var $fieldtype;
    var $commonfieldid;
    var $fieldmandatory;
    var $order;
    var $displaystatus;
    var $displayonticket;
    var $fieldlevel;
    var $ticketid;
    var $deleted;
    var $id;
    var $customvalidation;

    public function __construct() {
        parent::__construct();
        //setting the table name
        $this->setTableName("customfield");
        //Giving alias names to table field names
        $this->_setFieldNames();
    }
    /**
     * To set the field names
     */
    private function _setFieldNames() {
        $this->id = "id";
        $this->eventid = "eventid";
        $this->fieldname = "fieldname";
        $this->fieldtype = "fieldtype";
        $this->commonfieldid="commonfieldid";
        $this->fieldmandatory = "fieldmandatory";
        $this->order = "order";
        $this->displaystatus = "displaystatus";
        $this->displayonticket = "displayonticket";
        $this->fieldlevel = "fieldlevel";
        $this->ticketid = "ticketid";
        $this->deleted = "deleted";
        $this->customvalidation = "customvalidation";
         
    }
    
    public function getMaxCustomFieldOrderNo($data){
        $whereArray["eventid"]=$data["eventId"];
        $whereArray["deleted"]=0;
        $this->db->select_max($this->order,'ordering')->where($whereArray);
         
        $output = $this->db->get($this->dbTable)->result_array();
        return $output;
        
        
    }
    
    

}
