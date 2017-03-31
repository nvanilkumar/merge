<?php
require_once 'common_model.php';
class Event_model extends Common_model {

    var $id ;
    var $ownerid ;
    var $startdatetime;
    var $enddatetime;
    var $timezoneid ;
    var $title ;
    var $description;
    var $countryid;
    var $cityid;
    var $stateid ;
    var $localityid ;
    var $latitude ;
    var $longitude ;
    var $venue ;
    var $url ;
    var $registrationtype ;
    var $eventmode ;
    var $venuename ;
    var $venueaddress1;
    var $venueaddress2;
    var $logo ;
    var $banner ;
    var $categoryid;
    var $subcategoryid ;
    var $pincode ;
    var $status;
    var $deleted;
    var $error1;
    var $error2;
    var $error3 ;
    var $error4 ;
    var $private;
    var $ticketsoldout;
    var $deleterequest;
    var $acceptmeeffortcommission;
    
     function __construct() {
        parent::__construct();
        //setting the table name
        $this->setTableName("event");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
       
    }
     //Set the field values
    private function _setFieldNames() {
        $this->id = "id";
        $this->ownerid = "ownerid";
        $this->startdatetime = "startdatetime";
        $this->enddatetime = "enddatetime";
        $this->timezoneid = "timezoneid";
        $this->title = "title";
        $this->description = "description";
        $this->countryid = "countryid";
        $this->cityid = "cityid";
        $this->stateid = "stateid";
        $this->localityid = "localityid";
        $this->latitude = "latitude";
        $this->longitude = "longitude";
        $this->venue = "venuename";
        $this->url = "url";
        $this->registrationtype = "registrationtype";
        $this->eventmode = "eventmode";
        $this->venuename = "venuename";
        $this->venueaddress1 = "venueaddress1";
        $this->venueaddress2 = "venueaddress2";
        $this->logo = "thumbnailfileid";
        $this->banner = "bannerfileid";
        $this->categoryid = "categoryid";
        $this->subcategoryid = "subcategoryid";
        $this->pincode = "pincode";
        $this->status = "status";
        $this->deleted = "deleted";
        $this->error1 = 'Required Input Details are missing';
        $this->error2 = 'Not Acceptable';
        $this->error3 = 'Invalid Event id';
        $this->error4 = 'Something went wrong. Please try again later';
        $this->private = 'private';
        $this->ticketsoldout = 'ticketsoldout';
	$this->deleterequest = 'deleterequest';	
        $this->acceptmeeffortcommission='acceptmeeffortcommission';
    }

}

?>