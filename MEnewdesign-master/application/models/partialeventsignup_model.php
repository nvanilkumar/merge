<?php

require_once 'common_model.php';

class Partialeventsignup_model extends Common_model {

    var $id;
    var $eventsignupid;
    var $userid;
    var $signupdate;
    var $eventid;
    var $quantity;
    var $discountamount;
    var $referraldiscountamount;
    var $currencyid;
    var $fromcurrencyid;
    var $tocurrencyid;
    var $conversionrate;
    var $convertedamount;
    var $attendeeid;
    var $discount;
    var $discountcodeid;
    var $paymentmodeid;
    var $paymenttransactionid;
    var $transactiontickettype;
    var $transactionstatus;
    var $transactionresponse;
    var $paymentgatewayid;
    var $paymentstatus;
    var $settlementdate;
    var $ticketwidgettransaction;
    var $depositdate;
    var $source;
    var $userpointid;
    var $referralcode;
    var $promotercode;
    var $barcodenumber;
    var $totalamount;
    var $deleted;
    var $cts;
    var $mts;
    var $createdby;
    var $modifiedby;
    var $eventextrachargeamount;
    var $eventextrachargeid;
	var $tableName;

    public function __construct() {
        parent::__construct();
        //setting the table name
		$this->tableName = "partialeventsignup";
        $this->setTableName($this->tableName);

        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        $this->id = "id";
        $this->eventsignupid = "eventsignupid";
        $this->userid = "userid";
        $this->signupdate = "signupdate";
        $this->eventid = "eventid";
        $this->quantity = "quantity";
        $this->discountamount = "discountamount";
        $this->referraldiscountamount = "referraldiscountamount";
        $this->fromcurrencyid = "fromcurrencyid";
        $this->tocurrencyid = "tocurrencyid";
        $this->conversionrate = "conversionrate";
        $this->convertedamount = "convertedamount";
        $this->attendeeid = "attendeeid";
        $this->discount = "discount";
        $this->discountcodeid = "discountcodeid";
        $this->paymentmodeid = "paymentmodeid";
        $this->paymenttransactionid = "paymenttransactionid";
        $this->transactionstatus = "transactionstatus";
        $this->transactionresponse = "transactionresponse";
        $this->paymentgatewayid = "paymentgatewayid";
        $this->paymentstatus = "paymentstatus";
        $this->settlementdate = "settlementdate";
        $this->ticketwidgettransaction = "ticketwidgettransaction";
        $this->depositdate = "depositdate";
        $this->source = "source";
        $this->userpointid = "userpointid";
        $this->referralcode = "referralcode";
        $this->promotercode = "promotercode";
        $this->barcodenumber = "barcodenumber";
        $this->totalamount = "totalamount";
        $this->deleted = "deleted";
        $this->cts = "cts";
        $this->mts = "mts";
        $this->transactiontickettype = "transactiontickettype";
        $this->createdby = "createdby";
        $this->modifiedby = "modifiedby";
        $this->eventextrachargeamount = "eventextrachargeamount";
        $this->eventextrachargeid = "eventextrachargeid";
    }

	

}
