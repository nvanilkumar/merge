<?php
require_once 'common_model.php';
class Gallery_model extends Common_model {
    
    var $id;
    var $eventid;
    var $imagefileid;
    var $thumbnailfileid;
    var $status;
    var $order;	
    var $deleted;	
  

    function __construct() {
        parent::__construct();
         $this->setTableName("gallery");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }
    
    
    private function _setFieldNames() {
    	$this->id = "id";
    	$this->eventid = "eventid";
    	$this->imagefileid = "imagefileid";
    	$this->thumbnailfileid = "thumbnailfileid";
    	$this->status = "status";
    	$this->order = "order";
        $this->deleted = "deleted";
    }


}

?>