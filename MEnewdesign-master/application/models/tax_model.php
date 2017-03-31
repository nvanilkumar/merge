<?php

class Tax_model extends Common_model {

    var $id;
    var $label;
    var $status;
    var $deleted;

    function __construct() {
        parent::__construct();
        $this->setTableName('tax');
        $this->_setFieldNames();
    }
    //Seting the properties of the table
    private function _setFieldNames() {
        $this->id = "id";
        $this->label = "label";
        $this->status = "status";
        $this->deleted = "deleted";
    }
//     function get($select = "", $where = "",$returnArray=false) {
//        if (empty($select))
//        $select = $this->select;
//        $selectString = sqlHelperGetSelect($select);
//        $this->db->select($selectString);
//        $this->db->where($where);
//        $this->db->from($this->dbTable);
//        if($returnArray) {
//            $response = $this->db->get()->result_array();
//        } else {
//            $response = $this->db->get()->result();
//        }
//        
//        if (count($response) > 0) {
//            return $response;
//        } else {
//            return FALSE;
//        }
//	}
        function add($data = "") {
//        $data[$this->name] = $inputArray['name'];
//        $data[$this->zone] = $inputArray['defaultCurrencyId'];
//        $data[$this->timezone] = $inputArray['iconId'];
//        $data[$this->status] = $inputArray['priceMin'];
//        $data[$this->deleted] = $inputArray['priceMax'];
          $status = $this->db->insert($this->dbTable, $data);
          return $status;
    }
     function update($data = "",$where = "") {
        $this->db->where($where);
        $status = $this->db->update($this->dbTable, $data);
        return $status;
    }

}
?>