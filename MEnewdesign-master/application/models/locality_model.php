<?php
class Locality_model extends Common_model {

	var $id="id";
	var $name="name";
	var $status="status";
	var $featured="featured";
	var $order="order";
	var $countryId="countryid";
	var $deleted="deleted";
	var $dbTable="locality";
        var $error1='Required Input Details are missing';
    
    var $select=array();
	
	function __construct()
    {
        parent::__construct();
		$this->load->helper(array('sql'));
		$this->select[]=$this->id;
    }
	
	function get($select="",$where="")
	{
		if(empty($select))
			 $select=$this->select;	
		$selectString=sqlHelperGetSelect($select);
		$this->db->select($selectString);
		$this->db->where($where);
        $this->db->from($this->dbTable);
		$response = $this->db->get()->result();
		if (count($response) > 0) {
            return $response;
        } else {
            return FALSE;
        }
	}
}
?>