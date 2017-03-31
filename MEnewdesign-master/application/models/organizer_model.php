<?php
require_once 'common_model.php';
class Organizer_model extends Common_model {
	
	var $id;
	var $userid;
    var $companyname;
	var $designation;
	var $description;
	var $address;
	var $countryid;
	var $stateid;
	var $cityid;
	var $pincode;
	var $phone;
	var $fax;
	var $email;
	var $url;
	var $logofileid;
	var $facebooklink;
	var $twitterlink;
	var $linkedinlink;
	var $googlepluslink;
	var $deleted;
	var $createdby;
	var $modifiedby;
	
    function __construct() {
        parent::__construct();
         $this->setTableName("organizer");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }
    
    
    private function _setFieldNames() {
    	$this->id = "id";
    	$this->userid = "userid";
        $this->companyname = "companyname";
        $this->designation = "designation";
    	$this->description = "description";
    	$this->address = "address";
    	$this->countryid = "countryid";
    	$this->stateid = "stateid";
    	$this->cityid = "cityid";
    	$this->pincode = "pincode";
    	$this->phone = "phone";
    	$this->fax = "fax";
    	$this->email = "email";
    	$this->url = "url";
    	$this->logofileid = "logofileid";
    	$this->facebooklink = "facebooklink";
    	$this->twitterlink = "twitterlink";
    	$this->linkedinlink = "linkedinlink";
    	$this->googlepluslink = "googlepluslink";
		$this->deleted = "deleted";
    	$this->createdby = "createdby";
    	$this->modifiedby = "modifiedby";
    }


}

?>