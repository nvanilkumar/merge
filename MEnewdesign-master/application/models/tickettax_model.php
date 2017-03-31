<?php

class Tickettax_model extends Common_model {

    var $id;
    var $label;
    var $value;
    var $ticketid;
    var $status;
    var $deleted;
    var $dbTable;

    function __construct() {
        parent::__construct();
        $this->setTableName('tickettax');
        $this->_setFieldNames();
    }

    //Seting the properties of the table
    private function _setFieldNames() {
        $this->id = "id";
        $this->ticketid = "ticketid";
        $this->taxmappingid = "taxmappingid";
        $this->taxid = "taxid";
        $this->label = "label";
        $this->value = "value";
        $this->ticketid = "ticketid";
        $this->status = "status";
        $this->deleted = "deleted";
        $this->createdby = "createdby";
        $this->modifiedby = "modifiedby";
        $this->countryid = "countryid";
        $this->stateid = "stateid";
        $this->cityid = "cityid";
        $this->type = "type";
        $this->dbTable = "tickettax";
    }
    
    /*
     * Function to get the Taxes based on country,state,city
     *
     * @access	public
     * @param	$select - Fields to select
     * 			$where  - Condition
     * 			$returnArray - boolean to return result as array or object (optional)
     * 			$likeArray - Like Conditions (optional)
     * 			$table - Table Name
     * @return	array
     */
    public function getTaxes($select = "*", $where = "", $returnArray = false,$likeArray=array(),$table='') {
        if($table == '') {
            $table = $this->dbTable;
        }
        if (empty($select)) {
            $select = $select;
        }
        $selectString = sqlHelperGetSelect($select);
        $this->db->select($selectString);
        $this->db->where($where);
        if(is_array($likeArray) && count($likeArray) > 0) {
            $this->db->like($likeArray);
        }
        $this->db->from($table);
        
//        if($recordsPerPage > 0){
//            $this->db->limit($recordsPerPage);
//        }
        
        if ($returnArray) {
            $response = $this->db->get()->result_array();
        } else {
            $response = $this->db->get()->result();
        }
        //echo $this->db->last_query();
        if (count($response) > 0) {
            return $response;
        } else {
            return FALSE;
        }
    }
}

?>