<?php
require_once 'common_model.php';
class User_model extends Common_model {
    var $id;
    var $username;
    var $password;
    var $email;
    var $company;
    var $name;
    var $profileimagefileid;
    var $address ;
    var $countryid;
    var $stateid;
    var $cityid;
    var $pincode;
    var $phone;
    var $mobile ;
    var $status;  //0=inactive , 1=active, 2=notverified
    var $facebookid;
    var $twitterid;
    var $googleid;
    var $ipaddress;
    var $totalcredits;
    var $deleted;
	var $isattendee;
	var $usertype;
    

    

    
     function __construct() {
        
                parent::__construct();
        $this->select[] = $this->id;
         //setting the table name
        $this->setTableName("user");
        //Giving alias names to table field names
        $this->_setFieldNames();
    }
    

   //Set the field values
    private function _setFieldNames() {
        $this->id = "id";
        $this->username = "username";
        $this->password = "password";
        $this->email = "email";
        $this->company = "company";
        $this->name = "name";
        $this->profileimagefileid = "profileimagefileid";
        $this->address = "address";
        $this->countryid = "countryid";
        $this->stateid = "stateid";
        $this->cityid = "cityid";
        $this->pincode = "pincode";
        $this->phone = "phone";
        $this->mobile = "mobile";
        $this->status = "status";
        $this->verificationstring = "verificationstring";		
        $this->eventmode = "eventmode";
        $this->facebookid = "facebookid";
        $this->twitterid = "twitterid";
        $this->googleid = "googleid";
        $this->ipaddress = "ipaddress";
        $this->totalcredits = "totalcredits";
        $this->deleted = "deleted";
		$this->isattendee = "isattendee";
		$this->usertype = "usertype";
 
    }

    
    

}
?>