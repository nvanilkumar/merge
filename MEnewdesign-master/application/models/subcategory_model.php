<?php
require_once 'common_model.php';
class Subcategory_model extends Common_model {
    var $id = "id";
    var $name = "name";
    var $value = "value";
    var $status = "status";
    var $deleted = "deleted";
    var $categoryid = "categoryid";
    var $createdby="createdby";
    var $modifiedby="modifiedby";
    var $order = "order";
    var $select = array();

    function __construct() {
        parent::__construct();
        $this->load->helper(array('sql'));
        $this->select[] = $this->id;
        $this->setTableName("subcategory");
    }

    /*
    * Function to get the Sub Category list
    *
    * @access	public
    * @param
    *       $select - Fields that can be selected or by default '*'
    *       $where  - Optional condition
    *       $searchKey - Optional Search keyword
    *       $array_output - 'true' if the result need to be returned as Array or by default 'false'
    *       
    * @return	Either array or an object
    */
    function get($select = "", $where = "", $searchKey = "", $array_output = false,$recordsPerPage="",$orderByArr=array()) {
        if (empty($select))
        $select = $this->select;
        $selectString = sqlHelperGetSelect($select);
        $this->db->select($selectString);
        $this->db->where($where);
        
        if($searchKey != "") {
            $this->db->like($this->name,$searchKey);
        }
        
        if(is_array($orderByArr) && count($orderByArr) > 0) {
            foreach($orderByArr as $key => $value) {
                if($key != '' && $value != '') {
                    $this->db->order_by($key,$value);   
                }
            }
        }
        
        if($recordsPerPage > 0){
            $this->db->limit($recordsPerPage);
        }
        
        if($array_output) {
            $response = $this->db->get($this->dbTable)->result_array();
        } else {
            $response = $this->db->get($this->dbTable)->result();
        }
        
        if (count($response) > 0) {
            return $response;
        } else {
            return FALSE;
        }
    }

}

?>