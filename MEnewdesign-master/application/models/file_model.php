<?php

require_once 'common_model.php';
class File_model extends Common_model {

    var $id;
    var $filePath;
    var $fileType;
    var $deleted;
    var $createdby;
    var $modifiedby;

    function __construct() {
        parent::__construct();

        $this->select[] = $this->id;
        //setting the table name
        $this->setTableName("file");

        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        $this->id = "id";
        $this->filePath = "path";
        $this->fileType = "type";
        $this->deleted = "deleted";
        $this->createdby = "createdby";
        $this->modifiedby = "modifiedby";
    }

//    function get($select = "", $where = "", $customWhere = array()) {
//        if (empty($select))
//            $select = $this->select;
//        $selectString = sqlHelperGetSelect($select);
//        $this->db->select($selectString);
//        $this->db->where($where);
//        if (count($customWhere) > 0) {
//            foreach ($customWhere as $cwhere) {
//                $this->db->where($cwhere);
//            }
//        }
//        $this->db->from($this->dbTable);
//        $response = $this->db->get()->result();
//        if (count($response) > 0) {
//            return $response;
//        } else {
//            return FALSE;
//        }
//    }

    function custom_query($query = "") {
        $q = $this->db->query($query);
        $response = $q->result_array();
        if (count($response) > 0) {
            return $response;
        } else {
            return FALSE;
        }
    }

    /*
     * Function to get the File data
     *
     * @access	public
     * @param
     *       $fields - Fields that can be selected or by default '*'
     *       $where  - Optional condition
     *       $where_in - Array that we need to filter with the category table `imagefileid` and `iconimagefileid`
     *       
     * @return	array
     */

//    function getFileData($where_in) {
//        $this->db->where(array($this->deleted => 0));
//        if (is_array($where_in)) {
//            $this->db->where_in($this->id, $where_in);
//        }
//
//        $this->db->select($this->id);
//        $this->db->select($this->filePath);
//        $result = $this->db->get($this->dbTable)->result_array();
//        return $result;
//    }

}

?>