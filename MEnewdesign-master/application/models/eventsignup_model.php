<?php

require_once 'common_model.php';

class Eventsignup_model extends Common_model {

    var $id;
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
    var $bookingtype;
    var $attendeeid;
    var $discount;
    var $discountcodeid;
    var $paymentmodeid;
    var $useragent;
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
    var $extrafield;
    var $extrainfo;
    var $paymentsourceid;

    public function __construct() {
        parent::__construct();
        //setting the table name
        $this->tableName = "eventsignup";
        $this->setTableName($this->tableName);

        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        $this->id = "id";
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
        $this->useragent = "useragent";
        $this->paymenttransactionid = "paymenttransactionid";
        $this->transactionstatus = "transactionstatus";
        $this->bookingtype = "bookingtype";
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
        $this->transactionticketids = "transactionticketids";
        $this->createdby = "createdby";
        $this->modifiedby = "modifiedby";
        $this->eventextrachargeamount = "eventextrachargeamount";
        $this->eventextrachargeid = "eventextrachargeid";
        $this->extrafield = "extrafield";
        $this->extrainfo = "extrainfo";
        $this->paymentsourceid = "paymentsourceid";
    }

    function getDateWiseData($inputArray) {
        $where = $response = array();
        $whereString = "";
        $type = isset($inputArray['type']) ? $inputArray['type'] : "all";
        switch ($type) {
            case 'month':
                $where[] = $this->signupdate . " >= DATE_SUB(NOW(), INTERVAL 1 MONTH) ";
                break;
            case 'thisweek':
                $where[] = $this->signupdate . " >= DATE_SUB(NOW(), INTERVAL 1 WEEK) ";
                break;
            case 'today':
                $where[] = $this->signupdate . " >= DATE_SUB(NOW(), INTERVAL 1 DAY) ";
                break;
            case 'all':
            default:
                break;
        }
        if (!isset($inputArray['eventId']))
            return false;

        if (isset($inputArray['ticketId']) && is_array($inputArray['ticketId'])) {
            //$where
        }
        $where[] = $this->transactionstatus . " = 'success' ";
        $where[] = $this->eventid . " = " . $inputArray['eventId'];
        $whereString = implode(" and ", $where);
        $tableName = $this->tableName;

        $query = "select sum(" . $this->totalamount . ") as totalAmount,sum(" . $this->quantity . ") as quantity," . $this->eventid . "," . $this->tocurrencyid . " as currencyId from " . $tableName . " where " . $whereString . " group by " . $this->tocurrencyid . "";
        $result = $this->db->query($query);
        $response = $result->result_array();
        if (count($response) > 0) {
            return $response;
        }
        return false;
    }

    public function getIncompleteTransData($inputArray) {
        $eventId = $inputArray['eventid'];
        $query = "select 
                        *, COUNT(a.id) as `failedcount`
                    from
                        (SELECT 
                            `id` as `id`,
                                `userid` as `userid`,
                                `signupdate` as `signupdate`,
                                `transactiontickettype` as `transactiontickettype`,
                                `paymentstatus` as `paymentstatus`,
                                `barcodenumber` as `barcodenumber`,
                                `fromcurrencyid` as `fromcurrencyid`,
                                `tocurrencyid` as `tocurrencyid`,
                                `conversionrate` as `conversionrate`,
                                `convertedamount` as `convertedamount`,
                                `quantity` as `quantity`,
                                `totalamount` as `totalamount`,
                                `discountamount` as `discountamount`,
                                `referraldiscountamount` as `referraldiscountamount`,
                                `eventextrachargeamount` as `eventextrachargeamount`,
                                `paymenttransactionid` as `paymenttransactionid`
                        FROM
                            (`eventsignup`)
                        WHERE
                            `eventid` = '" . $eventId . "' AND `deleted` = 0
                                AND `transactionstatus` = 'pending'
                                AND `paymentstatus` NOT IN ('Canceled' , 'Refunded')
                        ORDER BY `id` desc
                        LIMIT 1000) as a
                    GROUP BY a.`userid`";
        $result = $this->db->query($query);
        $response = $result->result_array();
        if (count($response) > 0) {
            return $response;
        }
        return false;
    }

}
