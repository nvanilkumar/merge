<?php
require_once 'common_model.php';
class Verificationtoken_model extends Common_model {

    var $id;
    var $token;
    var $type;
    var $expirationdate;
    var $used;
    var $userid;
    var $deleted;

    public function __construct() {
        parent::__construct();
        //setting the table name
        $this->setTableName("verificationtoken");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        $this->id = "id";
        $this->token = "token";
        $this->type = "type";
        $this->expirationdate = "expirationdate";
        $this->used = "used";
        $this->userid = "userid";
        $this->deleted = "deleted";
    }

}
