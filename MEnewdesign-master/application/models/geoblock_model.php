<?php
require_once 'common_model.php';
class Geoblock_model extends Common_model {
  function __construct() {
        parent::__construct();
        $this->setTableName("geoblock");

        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        $this->startIpNum = "startIpNum";
        $this->endIpNum = "endIpNum";
        $this->locId = "locId";
    }
    
}

?>