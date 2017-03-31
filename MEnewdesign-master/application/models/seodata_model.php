<?php
require_once 'common_model.php';
class Seodata_model extends Common_model {
	
  

    function __construct() {
        parent::__construct();
         $this->setTableName("seodata");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
    	$this->id = "id";
    	$this->seotitle = "seotitle";
    	$this->seokeywords = "seokeywords";
    	$this->seodescription = "seodescription";
        $this->canonicalurl = "canonicalurl";
    	$this->url = "url";
    	$this->pagedescription = "pagedescription";
    	$this->gascripts = "gascripts";
        $this->mappingurl = "mappingurl";
        $this->pagetype = "pagetype";
        $this->mappingtype = "mappingtype";
        $this->params = "params";
    	$this->deleted = "deleted";
    	$this->cts = "cts";
    	$this->mts = "mts";
    }

    
    function custom_query($keyword) {
        $query = $this->db->query('SELECT * FROM seodata WHERE BINARY url = "'.$keyword.'" and deleted = 0');
        $response = $query->result_array();
        //echo $this->db->last_query();exit;
            return $response;
    }
}

?>