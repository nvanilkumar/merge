<?php

class Banner_model extends Common_model {

    var $id = "id";
    var $title = "title";
    var $eventid = "eventid ";
    var $startdatetime = "startdatetime";
    var $enddatetime = "enddatetime";
    var $imagefileid = "imagefileid";
    var $url = "url";
    var $status = "status";
    var $order = "order";
    var $type = "type";
    var $timezoneid = "timezoneid";
    var $deleted = "deleted";
    var $dbTable = "banner";
    var $select = array();

    function __construct() {
        parent::__construct();
        $this->load->helper(array('sql'));
        $this->select[] = $this->id;
    }

    function get($select = "", $where = "",$customWhere = "") {
        if (empty($select))
            $select = $this->select;
        $selectString = sqlHelperGetSelect($select);
        $this->db->select($selectString);
        $this->db->where($where);
		$this->db->where($customWhere);
        $this->db->from($this->dbTable);
        $response = $this->db->get()->result();
        if (count($response) > 0) {
            return $response;
        } else {
            return FALSE;
        }
    }
     function custom_query($query = "") {
        $resultQuery = $this->db->query($query);
        $response = $resultQuery->result_array();
        //echo $this->db->last_query();
        if (count($response) > 0) {
            return $response;
        } else {
            return FALSE;
        }
    }

}

?>