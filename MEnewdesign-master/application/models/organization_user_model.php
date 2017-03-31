<?php
/**
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
class Organization_user_model extends Common_model {
	
  

    function __construct() {
        parent::__construct();
         $this->setTableName("organizationuser");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }
    
    
    private function _setFieldNames() {
    	$this->id = "id";
    	$this->userid = "userid";
    	$this->organizationid = "organizationid";
    	$this->status = "status";
        $this->deleted = "deleted";
    	$this->createdby = "createdby";
    	$this->modifiedby = "modifiedby";
    }


}

?>