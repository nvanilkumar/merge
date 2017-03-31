<?php

session_start();
error_reporting(-1);
include_once("MT/cGlobali.php");

$Global = new cGlobali();
include 'loginchk.php';

class spot_registration {

    public $globali = '';

    public function __construct() {
        $this->globali = new cGlobali();
    }

    //To get the event related ticket information
    public function get_event_ticket_details($event_id) {
        if ($event_id > 0) {

            $query = "SELECT e.id AS Id, e.title AS Title,t.id as ticket_id, t.name AS Name,t.price AS Price 
                FROM event as e 
                left join ticket as t on t.eventid=e.id
                WHERE t.eventid = " . $event_id ." and t.deleted=0 and e.deleted=0";
            $tickets = $this->globali->SelectQuery($query, MYSQLI_ASSOC);
        }
        echo json_encode($tickets);
//        print_r($tickets);exit;
    }

    //To get the ticket minimum & maximum qty
    public function get_ticket_qty($ticket_id) {
        if ($ticket_id > 0) {
            $sel_tickets_query = "select t.maxorderquantity AS OrderQtyMax,t.minorderquantity AS OrderQtyMin
                     from ticket t 
                     where t.id=" . $ticket_id;
            $tickets_qty = $this->globali->SelectQuery($sel_tickets_query, MYSQLI_ASSOC);
        }
        echo json_encode($tickets_qty);
    }

    //To book the ticket in box office
    public function spot_booking() {
        $eventSignupId = 0;
        include_once("MT/cEventSignupFields.php");
        include_once 'includes/common_functions.php';

        $commonFunctions = new functions();

        $transactionData = array();
        $transactionData['eventId'] = $_POST['eventid'];
        $transactionData['ticketId'] = $_POST['tktid'];
        $transactionData['qty'] = $_POST['tktQty'];
        $transactionData['promoCode'] = trim($_POST['promo_code']);
        $transactionData['name'] = $_POST['name'];
        $transactionData['email'] = $_POST['email'];
        $transactionData['mobileNo'] = $_POST['mobileno'];
        $transactionData['userId'] = 1;
        $transactionData['total_amount'] = $_POST['total_amount'];
        $transactionData['paymentType'] = $_POST['bookint_type'];
        $transactionData['ticketAmount'] = $_POST['ticketamount'];
        $transactionData['eventSignupId']=$this->insertIntoEventSignup($transactionData);
        $this->insertEventSignupTicketDetails($transactionData);
         $this->updateTotalsoldCount($_POST['tktid'],$_POST['tktQty']);
        $transactionData['primaryAttendeeId']=$this->insertAttendee($transactionData);
        $this->updateEventsingup($transactionData);
        echo $transactionData['eventSignupId'];exit;
    }

    //To get the ticket minimum & maximum qty
    public function get_event_singup_ids($event_id, $payment_type) {
        if ($ticket_id > 0) {
            $event_signup_query = "select es.id 
                     from eventsignup as es 
                     where es.eventid=" . $event_id . " and paymentgatewayid=" . $payment_type;
            $tickets_qty = $this->globali->SelectQuery($event_signup_query, MYSQLI_ASSOC);
        }
        echo json_encode($tickets_qty);
    }

    //To get the payment gatewayid
    public function getPaymentGatewayId($paymentName) {

        $paymentQuery = "select p.name,p.id
                     from paymentgateway as p 
                     where p.name='" . $paymentName . "' and deleted=0";
        $paymentList = $this->globali->SelectQuery($paymentQuery, MYSQLI_ASSOC);
        if (count($paymentList) > 0) {
            return $paymentList[0]['id'];
        }
        return 7;
    }

    //To bring the specific ticket detail
    public function getSpecificTicketCurrencyId($ticketId) {
        $SelTickets = "SELECT id AS Id , currencyid FROM ticket "
                . "WHERE id=" . $this->globali->dbconn->real_escape_string($ticketId);
        $ResTickets = $this->globali->SelectQuery($SelTickets);
        if (count($ResTickets) > 0) {
            return $ResTickets[0]['currencyid'];
        }
        return 1;
    }

    //preparing the eventsignup field values 
    public function insertIntoEventSignup($data) {
        $eventSignupInsert = array();
        $eventSignupInsert['userid'][] = $data['userId'];
        $eventSignupInsert['userid'][] = "i";
        $eventSignupInsert['eventid'][] = $data['eventId'];
        $eventSignupInsert['eventid'][] = "i";
        $eventSignupInsert['quantity'][] = $data['qty'];
        $eventSignupInsert['quantity'][] = "i";
        $eventSignupInsert['totalamount'][] = $data['total_amount'];
        $eventSignupInsert['totalamount'] [] = "d";
        $eventSignupInsert['attendeeid'] [] = 0;
        $eventSignupInsert['attendeeid'][] = "i";
        $eventSignupInsert['transactionstatus'][] = 'success';
        $eventSignupInsert['transactionstatus'][] = 's';
        $eventSignupInsert['paymentstatus'] [] = 'Verified';
        $eventSignupInsert['paymentstatus'][] = 's';
        $eventSignupInsert['signupdate'] [] = date('Y-m-d h:i:s');
        $eventSignupInsert['signupdate'][] = 's';
        $eventSignupInsert['paymenttransactionid'] [] =  $data['paymentType'];
        $eventSignupInsert['paymenttransactionid'][] = 's';
        // Save extra Charge Id and Extra Charge Amount
        $eventSignupInsert['eventextrachargeid'][] = 0;
        $eventSignupInsert['eventextrachargeid'][] = "d";
        $eventSignupInsert['eventextrachargeamount'][] = 0;
        $eventSignupInsert['eventextrachargeamount'][] = "d";
        $eventSignupInsert['paymentgatewayid'][] = $this->getPaymentGatewayId($data['paymentType']); //paymentgateway
        $eventSignupInsert['paymentgatewayid'][] = "i";

        $eventSignupInsert['transactiontickettype'][] = "paid";
        $eventSignupInsert['transactiontickettype'][] = "s";
        $eventSignupInsert['transactionticketids'][] = $data['ticketId'];
        $eventSignupInsert['transactionticketids'][] = "s";
        $eventSignupInsert['tocurrencyid'][] = $eventSignupInsert['fromcurrencyid'][] = $this->getSpecificTicketCurrencyId($data['ticketId']);
        $eventSignupInsert['fromcurrencyid'][] = "i";

        $eventSignupInsert['tocurrencyid'][] = "i";
        $eventSignupInsert['paymentmodeid'][] = 5; //Means Spot
        $eventSignupInsert['paymentmodeid'][] = "i";
        $eventSignupInsert['discountamount'][] = 0;
        $eventSignupInsert['discountamount'][] = "d";
        $eventSignupInsert['referraldiscountamount'] [] = 0;
        $eventSignupInsert['referraldiscountamount'][] = "d";


        $eventSignupInsert['discount'] [] = 'X';
        $eventSignupInsert['discount'][] = 's';

        $eventSignupInsert['discountcodeid'][] = 0;
        $eventSignupInsert['discountcodeid'][] = "i";
        $eventSignupInsert['referralcode'][] = 0;
        $eventSignupInsert['referralcode'][] = "s";

        $eventSignupInsert['promotercode'][] = (empty($data['promoCode'])) ? '' : $data['promoCode'];
        $eventSignupInsert['promotercode'][] = "s";


        $eventSignupInsert['settlementdate'][] = date('Y-m-d h:i:s');
        $eventSignupInsert['settlementdate'][] = "s";

        $eventSignupInsert['depositdate'][] = date('Y-m-d h:i:s');
        $eventSignupInsert['depositdate'][] = "s";

        $eventSignupInsert['userpointid'][] = 0;
        $eventSignupInsert['userpointid'][] = "s";

        $eventSignupInsert['barcodenumber'][] = 0;
        $eventSignupInsert['barcodenumber'][] = "s";

        $eventSignupInsert['deleted'][] = 0;
        $eventSignupInsert['deleted'][] = "i";


        $eventSignupId = 0;
        $tableName = "eventsignup";

        $eventSignupId = $this->insertQuery($tableName, $eventSignupInsert);
        return $eventSignupId;
    }
    
    public function updateEventsingup($data){
        $generateNumber=substr($data['eventId'],1,4).$data['eventSignupId'];
        $uQuery="update eventsignup set barcodenumber='".$generateNumber."', attendeeid=".$data['primaryAttendeeId']."  where id=".$data['eventSignupId'];
        $ResUp= $this->globali->ExecuteQuery($uQuery);
    }
    
    public function updateTotalsoldCount($ticketId,$qty){
    	
    	$uQuery="update ticket set totalsoldtickets=totalsoldtickets+$qty  where id=".$ticketId;
    	$ResUp= $this->globali->ExecuteQuery($uQuery);
    	
    }

    //To get ticket related tax information
    public function getTicetTaxInformation($data) {
        $taxQuery = "select tm.value,tm.id
                     from tickettax as t 
                     join taxmapping as tm on tm.id=t.taxmappingid
                    where ticketid=" . $data['ticketId'] . " and tm.deleted=0  order by tm.id desc";
        $taxList = $this->globali->SelectQuery($taxQuery, MYSQLI_ASSOC);

        return $taxList;
    }

    //To Calculate the tax amount
    public function calculateTaxAmount($data) {

        $taxTotal = (($data['ticketAmount'] * $data['taxValue']) / 100);
        return $taxTotal;
    }

    //To get event custom field informatio 
    public function getEventCustomField($data) {
        $query = "select * from customfield where eventid=" . $data['eventId'];
        $list = $this->globali->SelectQuery($query, MYSQLI_ASSOC);
        return $list;
    }

    public function insertEventSignupTicketDetails($data) {
        $eventSignupTicketInsert['eventsignupid'][] = $data['eventSignupId'];
        $eventSignupTicketInsert['eventsignupid'] [] = "i";
        $eventSignupTicketInsert['ticketid'][] = $data['ticketId'];
        $eventSignupTicketInsert['ticketid'] [] = "i";
        $eventSignupTicketInsert['ticketquantity'] [] = $data['qty'];
        $eventSignupTicketInsert['ticketquantity'][] = "i";
        $eventSignupTicketInsert['amount'][] = $data['ticketAmount'];
        $eventSignupTicketInsert['amount'][] = "i";
        $eventSignupTicketInsert['totalamount'] [] = $data['total_amount'];
        $eventSignupTicketInsert['totalamount'][] = "i";

        //Inserting the ticket related tax information
        $taxList = $this->getTicetTaxInformation($data);
        $totalTaxAmount = 0;
        if (count($taxList) > 0) {
            $ticketAmount=$data['ticketAmount'];
            foreach ($taxList as $taxKey => $taxValue) {
                $taxAmount = 0;
                $eventSignupTaxInput = array();
                $eventSignupTaxInput['eventsignupid'][] = $data['eventSignupId'];
                $eventSignupTaxInput['eventsignupid'][] = "i";
                $eventSignupTaxInput['ticketid'][] = $data['ticketId'];
                $eventSignupTaxInput['ticketid'][] = "i";
                $eventSignupTaxInput['taxmappingid'][] = $taxValue['id'];
                $eventSignupTaxInput['taxmappingid'][] = "i";
                //Preparing taxcalculating amount array
                $taxCal['ticketAmount'] = $ticketAmount;
                $taxCal['qty'] = $data['qty'];
                $taxCal['taxValue'] = $taxValue['value'];

                $taxAmount = $this->calculateTaxAmount($taxCal);
                //for next tax ticketamout= firsttaxamunt+ticketamount
                $ticketAmount+=$taxAmount;
                $totalTaxAmount+=$taxAmount;
                $eventSignupTaxInput['taxamount'][] = $taxAmount;
                $eventSignupTaxInput['taxamount'][] = "d";
                $tableName = 'eventsignuptax';
                $this->insertQuery($tableName, $eventSignupTaxInput);
            }
        }

        $eventSignupTicketInsert['totaltaxamount'] [] = $totalTaxAmount;
        $eventSignupTicketInsert['totaltaxamount'][] = "i";
        $eventSignupTicketInsert['discountcode'][] = 0;
        $eventSignupTicketInsert['discountcode'][] = "i";
        $eventSignupTicketInsert['discountcodeid'][] = 0;
        $eventSignupTicketInsert['discountcodeid'][] = "i";
        $eventSignupTicketInsert['discountamount'][] = 0;
        $eventSignupTicketInsert['discountamount'][] = "i";
        $eventSignupTicketInsert['bulkdiscountamount'] [] = 0;
        $eventSignupTicketInsert['bulkdiscountamount'][] = "i";
        $eventSignupTicketInsert['referraldiscountamount'] [] = 0;
        $eventSignupTicketInsert['referraldiscountamount'][] = "i";

        $tableName = 'eventsignupticketdetail';
        $this->insertQuery($tableName, $eventSignupTicketInsert);
    }

    //To Insert the attendee 
    //returns the primary attendee id
    public function insertAttendee($data) {
        $ticektQty = $data['qty'];
        for ($i = 0; $i < $ticektQty; $i++) {
            $attendeeInsert = array();

            $attendeeInsert['eventsignupid'][] = $data['eventSignupId'];
            $attendeeInsert['eventsignupid'][] = "i";
            $attendeeInsert['ticketid'][] = $data['ticketId'];
            $attendeeInsert['ticketid'][] = "i";
            
            if ($i == 0){
                $attendeeInsert["`primary`"][]= 1;
            }else{
               $attendeeInsert["`primary`"][] = 0; 
            }
            $attendeeInsert["`primary`"][] = "i";
            //Inserting the attendee table
            $tableName = 'attendee';
            $attendeeId = $this->insertQuery($tableName, $attendeeInsert);
           if ($i == 0){
                $primaryAttendeeId=$attendeeId;
            }

            
            //Inserting attendeedetails table

            $customfieldList = $this->getEventCustomField($data);

            //inserting fullname custom field data
            $attendeeDetailInsert = array();
            $attendeeDetailInsert['customFieldId'][] = $customfieldList[0]['id'];
            $attendeeDetailInsert['customFieldId'][] = "i";
            $attendeeDetailInsert['value'][] = "spot booking";
            $attendeeDetailInsert['value'][] = "s";
            $attendeeDetailInsert['attendeeId'][] = $attendeeId;
            $attendeeDetailInsert['attendeeId'][] = "i";
            $attendeeDetailInsert['commonFieldId'][] = $customfieldList[0]['commonfieldid'];
            $attendeeDetailInsert['commonFieldId'][] = "i";

            $tableName = 'attendeedetail';
            $this->insertQuery($tableName, $attendeeDetailInsert);
            //inserting email customfield data
            $attendeeDetailInsert = array();
            $attendeeDetailInsert['customFieldId'][] = $customfieldList[1]['id'];
            $attendeeDetailInsert['customFieldId'][] = "i";
            $attendeeDetailInsert['value'][] = "spot booking";
            $attendeeDetailInsert['value'][] = "s";
            $attendeeDetailInsert['attendeeId'][] = $attendeeId;
            $attendeeDetailInsert['attendeeId'][] = "i";
            $attendeeDetailInsert['commonFieldId'][] = $customfieldList[1]['commonfieldid'];
            $attendeeDetailInsert['commonFieldId'][] = "i";
            $this->insertQuery($tableName, $attendeeDetailInsert);
        }
        return $primaryAttendeeId;
    }

    //preparing the insert query and returns the inserted value
    public function insertQuery($tableName, $data) {
        $fieldNames = "";
        $fieldValues = "";
        $fieldValuesarray = array();
        
        $data['modifiedby'][]=$data['createdby'][]=$_SESSION['uid'];
        $data['createdby'][]= "i";
        $data['modifiedby'][]= "i";

        foreach ($data as $key => $value) {
            $fieldNames.=$key . ",";
            if ($value[1] === "s") {
                $fieldValues.="'" . $value[0] . "',";
            } else {
                $fieldValues.=$value[0] . ",";
            }
            $fieldValuesarray[] = $value[0];
        }

        $fieldNames = substr($fieldNames, 0, -1);
        $fieldValues = substr($fieldValues, 0, -1);

        $signup_query = "INSERT INTO " . $tableName . " (" . $fieldNames . ") VALUES "
                . "(" . $fieldValues . " )";

        $insertId = $this->globali->ExecuteQueryId($signup_query);
        return $insertId;
    }

}

$spot_object = new spot_registration();
$method_name = filter_input(INPUT_POST, 'call');
if (strlen($method_name) > 0) {
    switch ($method_name) {
        case "event_details":
            $event_id = filter_input(INPUT_POST, 'event_id');
            $spot_object->get_event_ticket_details($event_id);
            exit;
            break;
        case "ticket_qty":
            $ticket_id = filter_input(INPUT_POST, 'ticket_id');
            $spot_object->get_ticket_qty($ticket_id);
            exit;
            break;
        case "spot_booking":
            $spot_object->spot_booking($ticket_id);
            exit;


        default:
            echo "error";
            exit;
    }//end of switch
}





include 'templates/spot_registration_tpl.php';
?>