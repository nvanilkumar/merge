<?php
require_once 'common_model.php';
class City_model extends Common_model {

    var $id = "id";
    var $name = "name";
    var $status = "status";
    var $featured = "featured";
    var $order = "order";
    var $countryId = "countryid";
    var $splCityStateId="splcitystateid";
    var $deleted = "deleted";
    var $mts="mts";
    var $createdby = "createdby";
    var $modifiedby = "modifiedby";
    var $statecityStateId = "stateid";
    var $statecityCityId = "cityid";
    var $statecityTable = "statecity";
    var $aliascityid = "aliascityid";    
    var $select = array();

    function __construct() {
        parent::__construct();
        $this->select[] = $this->id;
         //setting the table name
        $this->setTableName("city");
    }


    function getdata($select = "", $where = "",$whereIn=array(), $likeArray = array(),$recordsPerPage=0,$orderBy=array()) {
        $response=false;
        if (empty($select))
            $select = $this->select;
        $selectString = sqlHelperGetSelect($select);
        $this->db->select($selectString);
        $this->db->where($where);
        if(count($likeArray)>0){
            //$this->db->like($like);
            foreach($likeArray as $key => $value) {
                $this->db->like($key,$value,'after');  
            }
        }
        if(count($whereIn) > 0) {
            foreach ($whereIn as $key => $value) {
                $this->db->where_in($key, $value);
            }           
        }
        if (count($orderBy) > 0) {
            $orderText = "";
            $orderText = implode(",", $orderBy);           
            $this->db->order_by($orderText);
        }
        $this->db->from($this->dbTable);
        if($recordsPerPage > 0){
            $this->db->limit($recordsPerPage);
        }
        $output = $this->db->get()->result_array();
        if (count($output) >= 0) {
           $response=$output;
        }
        return $response;
    }
    
    function getStateCity($select = "", $where = "",$like = array()) {
        $response = false;
        if (empty($select))
            $select = $this->select;
        $selectString = sqlHelperGetSelect($select);
        $this->db->select($selectString);
        $this->db->where($where);
        if(isset($like) && count($like) > 0) {
            $this->db->like($like);
        }
        $this->db->from($this->statecityTable);
        $output = $this->db->get()->result_array();
        if (count($output) > 0) {
           $response = $output;
        }
        return $response;
    }

}

?>