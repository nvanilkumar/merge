<?php
include("commondbdetails.php");
include_once '../ctrl/includes/common_functions.php';
include("../ctrl/MT/cGlobali.php");
$commonFunctions = new functions();
$_GET = $commonFunctions->stripData($_GET, 1);
$_POST = $commonFunctions->stripData($_POST, 1);
$_REQUEST = $commonFunctions->stripData($_REQUEST, 1);

$global = new cGlobali();

//echo '<pre>';

if ($_GET['runNow'] == 1) {


    $select_sales = "SELECT e.id as Id,e.title as Title,ae.eventid as EventId,ae.salesid as SalesId,ae.paymentinterval as iDays,s.email as Email,s.name as SalesName,ae.paymenttype as Payment,e.enddatetime as EndDt FROM eventsalespersonmapping ae "
            . " LEFT JOIN salesperson s ON s.id = ae.salesid"
            . " LEFT JOIN event e ON e.id = ae.eventid WHERE ae.paymenttype!=''";

    $sales_persons = $global->SelectQuery($select_sales, MYSQLI_ASSOC);

    $bcc = $replyto = $content = $filename = NULL;
    //$cc = 'delivery@meraevents.com,souravir@meraevents.com,support@meraevents.com';
    $from = 'MeraEvents<admin@meraevents.com>';

    if (count($sales_persons) > 0) {

        foreach ($sales_persons as $sales_person) {

            $event_id = $sales_person['EventId'];
            $event_title = $sales_person['Title'];
            $SalesId = $sales_person['SalesId'];
            $sales_person_Email = $sales_person['Email'];
            $days = $sales_person['iDays'];
            $sales_person_Name = ($sales_person['SalesName'] != '') ? $sales_person['SalesName'] : $sales_person['Email'];
            if ($sales_person['Payment'] == 'complete') {
                $datetime1 = new DateTime($sales_person['EndDt']);
                $datetime2 = new DateTime();
                $interval = $datetime1->diff($datetime2);
                $daysInt = $interval->format('%R%a');
                if (strcmp($daysInt, '+1') == 0) {
                    $subject = $sales_person['Payment'] . " Payment is pending for the Event '$event_title'(".$sales_person['Id'].")";
                    $Msg = "Hi,<br>
                           " . $sales_person['Payment'] . " Payment Pending for " . $event_title . " (" . $event_id . ")";
                    if ($sales_person_Email != '') {
                        $status = $commonFunctions->sendEmail($sales_person_Email, $cc, $bcc, $from, $replyto, $subject, $Msg, $content, $filename);
                    }
                }
            } else if($sales_person['Payment'] == 'partial' && $days>0){
                $select_events = "SELECT COUNT(es.id) as tot_bookings FROM eventsignup es "
                        . " LEFT JOIN event e ON es.eventid = e.id "
                        . "WHERE es.eventid = " . $event_id . " AND (date_format(signupdate, '%Y-%m-%d') + interval $days day = curdate()) "
                        . " AND es.paymentstatus IN ('Verified', 'Captured', 'NotVerified') AND paymenttransactionid != 'A1'" . $enddt;
                $events = $global->SelectQuery($select_events, MYSQLI_ASSOC);
                if (is_array($events) && $events[0]['tot_bookings'] > 0) {

                    $subject = $sales_person['Payment'] . " Payment is pending for the Event '$event_title'(".$sales_person['Id'].")";

                    $Msg = "Hi,<br>
                            " . $sales_person['Payment'] . " Payment Pending for " . $event_title . " (" . $event_id . ")";
                    if ($sales_person['Payment'] == 'partial') {
                        $Msg.=".(which were done in last " . $sales_person['iDays'] . " days )";
                    }

                    if ($sales_person_Email != '') {
						 $commonFunctions->sendEmail($sales_person_Email, $cc, $bcc, $from, $replyto, $subject, $Msg, $content, $filename);
                    }
                }
            }
        }
    }
    //exit;
}
?>
