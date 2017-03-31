<?php
require_once 'common_model.php';
class Geolocation_model extends Common_model {
  function __construct() {
        parent::__construct();
        $this->setTableName("geolocation");

        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        $this->id = "id";
        $this->country = "country";
        $this->region = "region";
        $this->city = "city";
        $this->postalCode = "postalCode";
        $this->latitude = "latitude";
        $this->longitude = "longitude";
        $this->dmaCode = "dmaCode";
        $this->areaCode = "areaCode";
      
    }
    
}

?>