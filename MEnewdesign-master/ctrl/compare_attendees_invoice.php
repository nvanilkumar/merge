<?php

session_start();
ini_set('max_execution_time', 2000);
include_once("MT/cGlobali.php");
include 'loginchk.php';

include_once("MT/cAttendees.php");
$Globali = new cGlobali();

$newarray = NULL;
if (isset($_GET['com_month']) && $_GET['com_month'] > 0) {

    $val = $_GET['com_month'];

    $month = strtotime("-$val month");
    $monthtime = date("Y-m-d 00:00:00", $month);

     $query = "SELECT distinct(s.eventid) as EventId, e.title AS Details FROM eventsignup AS s INNER JOIN event AS e "
            ."ON s.eventid = e.id "
            ."WHERE (e.startdatetime > '".$monthtime."' AND e.startdatetime < NOW()) AND (s.totalamount!=0 AND "
            . "(s.paymentmodeid=1 and s.paymenttransactionid != 'A1') OR "
            ."(s.paymentmodeid=2 or s.paymentgatewayid=2)) "
            ."ORDER BY e.title  DESC";
    //echo $query; exit;
    $queryres = $Globali->SelectQuery($query);

    foreach ($queryres as $key => $value) {
        $eventid = $value['EventId'];
        
        $freesql = "select e.registrationtype AS Free, 
		ed.extrareportingemails AS OEmails,
		e.title AS Title  from event AS e Inner JOIN eventdetail AS ed ON e.id = ed.eventid 
		WHERE id=" . $Globali->dbconn->real_escape_string($eventid);
        $free = $Globali->SelectQuery($freesql);
        $OEmails = $free[0]['OEmails'];

        if ($free[0]['Free'] == 1)
            $isfree = 2;
        else
            $isfree = 1;

        $atten = @new cAttendees();
        //list($totalRecordsCount,$atten_res) = $atten->LoadAllByEventId($eventid,$isfree);
		$atten_res = $atten->LoadAllByEventId($eventid, $isfree);

        $totamt = 0;
        
        //Attendees export code starts here ##################################################
        while ($list_row = $atten_res->fetch_array(MYSQLI_BOTH)) {
            //foreach($atten_res as $list_row) {
            //{ 
            //print_r($list_row)."<br><br>";
            //echo "loop time ".time();
            $PaymentTransId = '';
            //print_r($list_row);
            $PaymentTransId = $list_row['paymenttransactionid'];

            if (($list_row['discount'] != 'X' && $list_row['totalamount'] == 0) || $list_row['discount'] == 'PayatCounter' || $list_row['discount'] == 'CashonDelivery') {

                $PaymentTransId = $list_row['discount'];
            }

            if ($PaymentTransId != '') {
                //For calculating only amount value
                $totamt+=$list_row['Paid'];
            }
        }//end of while loop

        $LogDetails.= "$IP - after while loop : " . number_format((microtime(true) - $starttime), 10) . "\r\n";
        $starttime = microtime(true);

        $TickMsg = '';

        //taking this query out of the below if condition so that, same query can be used in both this and tpl file. -pH
//        $SelTickets = "SELECT * FROM tickets WHERE EventId='".$_REQUEST[EventId]."'";
        $SelTickets = "select t.`name`,
       t.`price`,
        t.`startdatetime`,
        t. `enddatetime`,
        t. `status`,
        t. `totalsoldtickets`,
        t.id,
        count(a.id) as soldTickets ,
        (count( a.id ) * t.price) as soldTicketPrice
FROM attendee as a
            Inner Join eventsignup as es on a.eventsignupid=es.id
            Inner Join ticket t on t.id=a.ticketid
    WHERE   ((((es.paymentgatewayid=2) or es.paymenttransactionid!='A1' or
        (es. paymentmodeid=2 and paymenttransactionid='A1' and es.paymentstatus='Verified') )
        and es.paymentstatus NOT IN('Canceled','Refunded'))  or es.totalamount=0 )
        and es.eventid='" . $Globali->dbconn->real_escape_string($eventid) . "'         
        group by t.id";
        // echo $SelTickets;
        $ResTickets = $Globali->SelectQuery($SelTickets);
        
        $totalAttendeesCount = 0;
        //$totalAmount=0;
        for ($cntTkts = 0; $cntTkts < count($ResTickets); $cntTkts++) {
            //$attentdeeInfo=getAttendeeCount($ResTickets[$cntTkts]['Id']);	
            //$totalAmount+=$attentdeeInfo['soldTicketPrice'];
            $totalAttendeesCount+=$ResTickets[$cntTkts]['soldTickets'];
        }

        if ($totalAttendeesCount == 0) {
            $acompareqty = $acompareamt = 0;
        } else {
            $acompareqty = $totalAttendeesCount;
            $acompareamt = $totamt;
        }
        $paytotalqty = 0;
        $paytotalamt = 0;
        $transql = "SELECT s.signupdate,  sum(s.quantity) as Qty,
 sum(s.totalamount-s.eventextrachargeamount) as amount FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE 1 AND (s.paymentmodeid=1 and s.paymenttransactionid != 'A1') and s.eventid=$eventid $SqDate and s.paymentstatus!='Refunded' and s.paymentstatus!='Canceled' order by s.signupdate";
        $TranRES = $Globali->SelectQuery($transql);

        if (isset($TranRES[0]['Qty']) && isset($TranRES[0]['Qty'])) {
            $paytotalqty+=$TranRES[0]['Qty'];
            $paytotalamt+=$TranRES[0]['amount'];
//echo "EBS-> There are ".$TranRES[0]['Qty']." Attendees for this event and the total amount is ".round($TranRES[0]['amount'],2)."<br />";
        }

        $COD = "SELECT s.signupdate, s.signupdate,  sum(s.quantity) as Qty,
 sum(s.totalamount-s.eventextrachargeamount) as amount FROM eventsignup AS s, event AS e where s.eventid = e.id and s.paymentgatewayid='2' and s.eventid=$eventid  $SqDate and s.paymentstatus ='Verified' order by s.signupdate";
        $TranCODRES = $Globali->SelectQuery($COD);
        if (isset($TranCODRES[0]['Qty']) && isset($TranCODRES[0]['Qty'])) {
            $paytotalqty+=$TranCODRES[0]['Qty'];
            $paytotalamt+=$TranCODRES[0]['amount'];
            //echo "COD-> There are ".$TranCODRES[0]['Qty']." Attendees for this event and the total amount is ".round($TranCODRES[0]['amount'],2)."<br />";
        }
        
        //creating new arrays to store the details and differences in between attendees and invoice
        $newarray[$eventid]['attendeesqty'] = $acompareqty;
        $newarray[$eventid]['attendeesamt'] = $acompareamt;
        $newarray[$eventid]['invoiceqty'] = $paytotalqty;
        $newarray[$eventid]['invoiceamt'] = $paytotalamt;

        if ($acompareqty != $paytotalqty || strcmp($acompareamt, $paytotalamt) != 0) {
            /* if($acompareqty!=$paytotalqty)
              echo "qtyyes--";

              if($acompareamt!=$paytotalamt)
              echo "amtyes--"; */

//echo "$eventid ---- ".$acompareqty.'!='.$paytotalqty.' || '.$acompareamt.'!='.$paytotalamt.'<br>';

            $newarray[$eventid]['diff'] = 1;
        }
    }
}//end of com_month condition

include 'templates/compare_attendees_invoice.tpl.php';
?>