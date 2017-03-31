<?php
/**
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0
 * @Created     07-12-2015
 * @Last Modified 07-12-2015
 * @Last Modified By Raviteja V
 */
require_once 'common_model.php';
class Organization_model extends Common_model {
	
  

    function __construct() {
        parent::__construct();
         $this->setTableName("organization");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }
    
    
    private function _setFieldNames() {
    	$this->id = "id";
    	$this->name = "name";
    	$this->information = "information";
    	$this->about = "about";
    	$this->intendedfor = "intendedfor";
    	$this->logopathid = "logopathid";
    	$this->bannerpathid = "bannerpathid";
    	$this->information = "information";
    	$this->sequencenumber = "sequencenumber";
    	$this->eventsnumber = "eventsnumber";
    	$this->viewcount = "viewcount";
    	$this->createdby = "createdby";
    	$this->modifiedby = "modifiedby";
    	$this->status = "status";
    	$this->deleted = "deleted";
    }


}

?>