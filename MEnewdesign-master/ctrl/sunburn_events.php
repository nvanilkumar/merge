<?php

session_start();
$uid = $_SESSION['uid'];

include 'loginchk.php';

include_once("MT/cGlobali.php");
$Global = new cGlobali();


//$sunburn_events_array = array(64948, 64950, 64951);
$sunburn_events_array = array(33124,33133);
$condition = '';

$SDt = $_REQUEST['txtSDt'];
$EDt = $_REQUEST['txtEDt'];

if ($SDt != '' && $EDt != '') {

    $SDtExplode = explode("/", $SDt);
    $SDtYMD = $SDtExplode[2] . '-' . $SDtExplode[1] . '-' . $SDtExplode[0] . ' 00:00:00';

    $EDtExplode = explode("/", $EDt);
    $EDtYMD = $EDtExplode[2] . '-' . $EDtExplode[1] . '-' . $EDtExplode[0] . ' 23:59:59';

    $condition .= " AND (es.signupdate >= '" . $SDtYMD . "' AND es.signupdate <='" . $EDtYMD . "') ";
}

$selected_event = $_REQUEST['event_name']; 
if ($selected_event != '') {

    $condition .= " AND e.id = " . $selected_event . " ";
}

/* Getting all the events including the filter creteria */
 $EventsQuery = "SELECT es.id as RegNo,e.title,e.id as event_id,es.id,es.signupdate,es.quantity,es.totalamount,
                sum(et.taxamount) as taxes,
                u.email as user_email,u.name as user_name,u.mobile,
                ticket.name as ticket_name,es.referraldiscountamount,es.discountamount,es.signupdate,
                IF(es.paymentstatus = 'Verified',es.totalamount, 0) AS 'verified_amount'
                FROM eventsignup es
				inner Join eventsignuptax as et on et.eventsignupid = es.id
                Inner Join event as e on e.id = es.eventid
                Inner Join eventsignupticketdetail estd on estd.eventsignupid = es.id
                LEFT JOIN user as u ON u.id = es.userid
                LEFT JOIN ticket ON ticket.id = estd.ticketid
                
                WHERE 1=1 $condition AND e.registrationtype=2 AND e.title!='' AND es.paymenttransactionid!='A1' AND e.id IN (" . implode(',', $sunburn_events_array) . ") "
        . "AND es.paymentstatus IN ('Verified','Captured','NotVerified')"
        . "ORDER BY e.id DESC";

//echo $EventsQuery;
$SunburnEvents = $Global->SelectQuery($EventsQuery);
//echo "<pre>";
////print_r($SunburnEvents);//exit;
$summery_details_array=array();
$previous_event_id=0;
$event_count=0;
foreach($SunburnEvents as $details){
    if($previous_event_id!=$details["event_id"]){
        $event_count++;
        $previous_event_id=$details["event_id"];
        $summery_details_array[$event_count]["event_id"]=$details["event_id"];
        $summery_details_array[$event_count]["Title"]=$details["title"];
        $summery_details_array[$event_count]["Qty"]=$details["quantity"];
        $summery_details_array[$event_count]["Taxes"]=$details["taxes"];
        $summery_details_array[$event_count]["DAmount"]=$details["discountamount"]+$details["referraldiscountamount"];
        $summery_details_array[$event_count]["verified_amount"]=$details["verified_amount"];
        $summery_details_array[$event_count]["total_amount"]=$details["totalamount"];
        
    }else{
        $summery_details_array[$event_count]["Qty"]+=$details["quantity"];
        $summery_details_array[$event_count]["Taxes"]+=$details["taxes"];
        $summery_details_array[$event_count]["DAmount"]+=$details["discountamount"]+$details["referraldiscountamount"];
        $summery_details_array[$event_count]["total_amount"]+=$details["totalamount"];
        
    }
}



/* Getting partial payments done for the events */
 $selAllPayments = "SELECT SUM(amountpaid) AS tot_payments,eventid FROM settlement WHERE eventid IN (" . implode(',', $sunburn_events_array) . ") "
        . "AND paymenttype='Partial Payment' AND status='1'  group by eventid order by eventid ";
$total_partial_payments = $Global->SelectQuery($selAllPayments);

//To Return the event related partial payment info
function get_partial_payment($total_partial_payments,$event_id){
    
    if(count($total_partial_payments) > 0){
        foreach($total_partial_payments as $payment){
            if($payment["eventid"]===$event_id){
                return $payment["tot_payments"];
            }
        }
        
    }
    return 0;
    
}

//To Get the sunburn event related info
 $event_list_query = "SELECT  id, title FROM event WHERE deleted=0 and id IN (" . implode(',', $sunburn_events_array) . ") ";
$event_list = $Global->SelectQuery($event_list_query);



include 'templates/sunburn_events.tpl.php';
?>

