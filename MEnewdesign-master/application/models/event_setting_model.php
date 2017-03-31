<?php
require_once 'common_model.php';
class Event_setting_model extends Common_model {

    var $eventid;
    var $webhookurl;
    var $eticketmessage;
    var $registraionemailtext;
    var $displayticketwidget;
    var $displayamountonticket;
    var $nonormalwhenbulk;
    //var $sendubermails;
    var $sendfeedbackemails;
    var $qualitycheck;
    var $cts;
    var $mts;
    var $createdby;
    var $modifiedby;    
    var $nodates;
    var $notmore;
    var $needvol;
    var $qpid;
    var $qdate;
    var $percentage;
    var $commentpercentage;
    var $paidbit;
    var $compiorg;
    var $leftforpayment;
    var $exception;
    var $nodiscount;
    var $nofbcomments;
    var $collectmultipleattendeeinfo;
    var $customemail;
    var $geolocalitydisplay;
    var $calculationmode;

    public function __construct() {
        parent::__construct();
        //setting the table name
        $this->setTableName("eventsetting");
         
        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        $this->eventid = "eventid";
        $this->webhookurl = "webhookurl";
        $this->eticketmessage = "eticketmessage";
        $this->registraionemailtext = "registraionemailtext";
        $this->displayticketwidget = "displayticketwidget";
        $this->displayamountonticket = "displayamountonticket";
        $this->nonormalwhenbulk = "nonormalwhenbulk";
        //$this->sendubermails = "sendubermails";
        $this->sendfeedbackemails = "sendfeedbackemails";
        $this->qualitycheck = "qualitycheck";
        $this->nodates = "nodates";
        $this->notmore = "notmore";
        $this->needvol = "needvol";
        $this->qpid = "qpid";
        $this->qdate = "qdate";
        $this->percentage = "percentage";
        $this->commentpercentage = "commentpercentage";
        $this->paidbit = "paidbit";
        $this->compiorg = "compiorg";
        $this->leftforpayment = "leftforpayment";
        $this->exception = "exception";
        $this->nodiscount = "nodiscount";
        $this->nofbcomments = "nofbcomments";        
        $this->createdby = "createdby";
        $this->modifiedby = "modifiedby";
        $this->collectmultipleattendeeinfo = "collectmultipleattendeeinfo";
        $this->customemail = "customemail";
        $this->geolocalitydisplay = "geolocalitydisplay";
        $this->calculationmode="calculationmode";
    }
}
