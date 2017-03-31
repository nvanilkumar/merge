<?php
require_once 'common_model.php';
class Organizerbankdetail_model extends Common_model {

    var $id;
    var $userid;
    var $accountname;
    var $bankname;
    var $accountnumber;
    var $accounttype;
    var $branch;
    var $ifsccode;
    var $createdby;
    var $modifiedby;

    public function __construct() {
        parent::__construct();
        //setting the table name
        $this->setTableName("organizerbankdetail");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        $this->id = "id";
        $this->userid = "userid";
        $this->bankname = "bankname";
        $this->accountname = "accountname";
        $this->accountnumber = "accountnumber";
        $this->accounttype = "accounttype";
        $this->branch = "branch";
        $this->ifsccode = "ifsccode";
        $this->createdby = "createdby";
        $this->modifiedby = "modifiedby";
    }

}
