<?php
/**
 * Banner related business logic will be defined in this class
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     11-06-2015
 * @Last Modified 02-07-2015
 * @Last Modified By Sridevi
  */
require_once 'common_model.php';

class Banner_mapping_model extends Common_model {

    var $id;
    var $bannerid;
    var $url;
    var $type;
    var $title;
    var $status;
    var $eventid;
    var $startdatetime;
    var $enddatetime;
    var $imagefileid;
    var $countryid;
    var $cityid;
    var $categoryid;
    var $allcategories;
    var $othercities;
    var $allcities;
    var $order;
    var $deleted;
    var $dbTable;
    

    function __construct() {
        parent::__construct();
		//Giving alias names to table field names
        $this->_setFieldNames();
		//setting the table name
  	    $this->setTableName($this->dbTable);
		
	}

	private function _setFieldNames() {
		$this->id = "id";
		$this->bannerid = "bannerid";
		$this->url = "url";
		$this->type = "type";
		$this->title = "title";
		$this->status = "status";
		$this->eventid = "eventid ";
		$this->startdatetime = "startdatetime";
		$this->enddatetime = "enddatetime";
		$this->imagefileid = "imagefileid";
		$this->countryid = "countryid ";
		$this->cityid = "cityid";
		$this->categoryid = "categoryid";
		$this->allcategories = "allcategories";
		$this->othercities = "othercities";
		$this->allcities = "allcities";
		$this->order = "order";
		$this->deleted = "deleted";
		$this->dbTable = "bannermapping";
	}    

}

?>