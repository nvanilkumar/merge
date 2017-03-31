<?php
require_once 'common_model.php';
class Timezone_model extends Common_model {

    var $id;
    var $name;
    var $zone;
    var $timezone;
    var $status;
    var $deleted;

    function __construct() {
        parent::__construct();
        $this->setTableName('timezone');
        $this->_setFieldNames();
    }
    //Seting the properties of the table
    private function _setFieldNames() {
        $this->id = "id";
        $this->name = "name";
        $this->zone = "zone";
        $this->timezone = "timezone";
        $this->status = "status";
        $this->deleted = "deleted";
        $this->mts = "mts";
    }
}
?>