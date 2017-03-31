<?php
require_once 'common_model.php';
class Messagetemplate_model extends Common_model {

    var $id;
    var $template;
    var $mode;
    var $type;
    var $eventid;
    var $fromemailid;
    var $deleted;
    var $createdby;
    var $modifiedby;
    var $params;
    
    public function __construct() {
        parent::__construct();
        //setting the table name
        $this->setTableName("messagetemplate");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        $this->id = "id";
        $this->template = "template";
        $this->mode = "mode";
        $this->type = "type";
        $this->eventid = "eventid";
        $this->fromemailid = "fromemailid";
        $this->deleted = "deleted";
        $this->createdby = "createdby";
        $this->modifiedby = "modifiedby";
        $this->params = "params";
    }

}
