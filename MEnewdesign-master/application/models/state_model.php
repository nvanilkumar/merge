<?php
require_once 'common_model.php';
class State_model extends Common_model {

	var $id="id";
	var $name="name";
	var $shortname="shortname";
	var $countryId="countryid";
	var $status="status";
	var $deleted="deleted";
        var $mts="mts";
    var $createdby="createdby";
    var $modifiedby="modifiedby";
	//var $featured="featured";
	//var $order="order";
	var $error1='Required Input Details are missing';
    
    var $select=array();
	
	function __construct()
    {
        parent::__construct();
		$this->load->helper(array('sql'));
		$this->select[]=$this->id;
        //setting the table name
        $this->setTableName("state");
    }
	
	function get($select = "", $where = "", $returnArray = false,$likeArray=array(),$recordsPerPage=0) {
        if (empty($select)) {
            $select = $this->select;
        }
        $selectString = sqlHelperGetSelect($select);
        $this->db->select($selectString);
        $this->db->where($where);
        if(is_array($likeArray) && count($likeArray) > 0) {
            //$this->db->like($likeArray);
			foreach($likeArray as $key => $value) {
                $this->db->like($key,$value,'after');  
            }
        }
        $this->db->order_by('name', 'ASC');
        $this->db->from($this->dbTable);
        
        if($recordsPerPage > 0){
            $this->db->limit($recordsPerPage);
        }
        
        if ($returnArray) {
            $response = $this->db->get()->result_array();
        } else {
            $response = $this->db->get()->result();
        }
        if (count($response) > 0) {
            return $response;
        } else {
            return FALSE;
        }
    }
}
?>