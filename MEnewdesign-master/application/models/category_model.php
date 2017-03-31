<?php

class Category_model extends Common_model {
    var $id = "id";
    var $name = "name";
    var $iconimagefileid = "iconimagefileid";
//	var $coloriconimagefileid="coloriconimagefileid";
    var $imagefileid = "imagefileid";
    var $status = "status";
    var $featured = "featured";
    var $deleted = "deleted";
    var $dbTable = "category";
    var $themecolor = "themecolor";
    var $ticketsetting = "ticketsetting";
    var $categorydefaultbannerid = "categorydefaultbannerid";
    var $categorydefaultthumbnailid = "categorydefaultthumbnailid";
    var $mts = "mts";
    var $blogfeedurl = "blogfeedurl";
    var $value = "value";
     
    var $select = array();

    function __construct() {
        parent::__construct();
        $this->load->helper(array('sql'));
        $this->select[] = $this->id;
    }

    /*
    * Function to get the Category list
    *
    * @access	public
    * @param
    *       $select - Fields that can be selected or by default '*'
    *       $where  - Optional condition
    *       $array_output - 'true' if the result need to be returned as Array or by default 'false'
    *       
    * @return	Either array or an object
    */
    function get($select = "", $where = "", $array_output = false) {
        if (empty($select))
        $select = $this->select;
        $selectString = sqlHelperGetSelect($select);
        $this->db->select($selectString);
        $this->db->where($where);
        $this->db->from($this->dbTable);
        if($array_output) {
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