<?php
require_once 'common_model.php';
class Eventdetail_model extends Common_model {

    var $eventdetail_id ;
    var $booknowbuttonvalue ;
    var $eventdetail_contactdetails;
    var $eventdetail_facebooklink;
    var $eventdetail_googlelink;
    var $eventdetail_twitterlink;
    var $eventdetail_tnc;
    var $eventdetail_organizertnc;
    var $eventdetail_tnctype;
    var $eventdetail_meraeventstnc;
    var $eventdetail_contactwebsiteurl ;
    var $eventdetail_seotitle;
    var $eventdetail_seokeywords;
    var $eventdetail_seodescription;
    var $eventdetail_conanicalurl;
    var $eventdetail_limitsingletickettype;
    var $eventdetail_discountaftertax;
    var $eventdetail_extrareportingemails;
    var $eventdetail_extratxnreportingemails;
    var $salespersonid;
    var $contactdisplay;
    var $customvalidationflag;
    var $customvalidationfunction;
	var $googleanalyticsscripts;
	var $confirmationpagescripts;
	var $viewcount;
	var $promotionaltext;
     

    public function __construct() {
        parent::__construct();
        //setting the table name
        $this->setTableName("eventdetail");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }
    //Set the field values
    private function _setFieldNames() {
        $this->eventdetail_id = "eventid";
        $this->booknowbuttonvalue = 'booknowbuttonvalue';
        $this->eventdetail_contactdetails = "contactdetails";
        $this->eventdetail_facebooklink = "facebooklink";
        $this->eventdetail_googlelink = "googlelink";
        $this->eventdetail_twitterlink = "twitterlink";
        //$this->eventdetail_tnc = "tnc";
        $this->eventdetail_organizertnc = "organizertnc";
        $this->eventdetail_tnctype = "tnctype";
        $this->eventdetail_meraeventstnc = "meraeventstnc";
        $this->eventdetail_contactwebsiteurl = "contactwebsiteurl";
        $this->eventdetail_seotitle="seotitle";
        $this->eventdetail_seokeywords="seokeywords";
        $this->eventdetail_seodescription="seodescription";
        $this->eventdetail_conanicalurl="conanicalurl";
        $this->eventdetail_limitsingletickettype="limitsingletickettype";
        $this->eventdetail_discountaftertax="discountaftertax";
        $this->eventdetail_extrareportingemails="extrareportingemails";
        $this->eventdetail_extratxnreportingemails="extratxnreportingemails";
        $this->salespersonid = 'salespersonid';
        $this->contactdisplay = 'contactdisplay';
        $this->customvalidationfunction = 'customvalidationfunction';
        $this->customvalidationflag = 'customvalidationflag';
		$this->googleanalyticsscripts = "googleanalyticsscripts";
		$this->confirmationpagescripts = "confirmationpagescripts";
		$this->viewcount = 'viewcount';
		$this->promotionaltext = 'promotionaltext';
    }

}
