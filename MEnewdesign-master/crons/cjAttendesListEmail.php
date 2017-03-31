<?php

/* * ********************************************************************************************** 
 * 	Page Details : Cron Job Transactions happen yesterday and Send Mail
 * 	Created / Last Updation Details : 
 * 	1.	Created on 12th Nov 2010 : Created by SUNIL
 * ********************************************************************************************** */

include_once("commondbdetails.php");
include_once("../ctrl/MT/cGlobali.php");
include_once '../ctrl/includes/common_functions.php';
$commonFunctions = new functions();
$_GET = $commonFunctions->stripData($_GET, 1);
$_POST = $commonFunctions->stripData($_POST, 1);
$_REQUEST = $commonFunctions->stripData($_REQUEST, 1);

error_reporting(-1);


if ($_GET['runNow'] == 1) {

    include_once("MT/cAttendees.php");
    $cGlobali = new cGlobali();


    //Display list of Successful Transactions
    $yesterday_date =$commonFunctions->convertTime(date ( 'Y-m-d'),DEFAULT_TIMEZONE,TRUE);
 
    $sDate=date("Y-m-d")." 00:00:00";
    $eDate=date("Y-m-d")." 23:59:59";
    $sDate=$commonFunctions->convertTime($sDate,DEFAULT_TIMEZONE,TRUE);
    $eDate=$commonFunctions->convertTime($eDate,DEFAULT_TIMEZONE,TRUE);
    
    $EventQuery = "SELECT id as Id,ownerid as UserID,title as Title FROM event where startdatetime between '" . $sDate . "' and '" .$eDate. "' and status=1 and eventmode=0";
    $resEvent = $cGlobali->justSelectQuery($EventQuery);



    while ($row = $resEvent->fetch_assoc()) {
        $cntPGTran = 0;

        $TicketQuery = "SELECT id as `Id` FROM ticket where eventid=" . $row['Id'] . " and enddatetime < ".$sDate." and totalsoldtickets > 0 and totalsoldtickets < ticketsavailabilitycount and displaystatus=0";
        $resTicket = $cGlobali->justSelectQuery($TicketQuery);

        $cntPGTran =$resTicket->num_rows;

 

        if ($cntPGTran != 0) {
            $Attendee = 0;
            $ins = $Global->GetSingleFieldValue("SELECT Status FROM AttendeesList WHERE event='" . $row['Id'] . "'");

            if ($ins != 1) {
                $usersql = "SELECT u.email as Email, u.name as FirstName,  u.phone as Phone, u.mobile as Mobile, u.cityid as CityId FROM user AS u where u.id=" . $row['UserID'];
                $dtuser = $cGlobali->SelectQuery($usersql);
                $atten = @new cAttendees();
                $atten_res = $atten->LoadAllByEventId($row['Id']);
                $freesql = "select registrationtype as Free,extrareportingemails as OEmails,title as Title from event" 
                        ." INNER JOIN eventdetail AS ed ON ed.eventid = id  "
                        . "where id=" . $row['Id'];
                $free = $cGlobali->SelectQuery($freesql);
                $OEmails = $free[0][OEmails];

                $cou = 0;
                $signupId = '';
                $am = 0;
                $tickc = 1;
                $totamt = 0;
                while ($list_row = $atten_res->fetch_assoc()) {
                    $PaymentTransId = '';
                    $PromotionCode = '';

                    
                    $selEAQuery = "SELECT ticketquantity as `NumOfTickets`, amount as `TicketAmt` FROM `eventsignupticketdetail` WHERE eventsignupid='" . $list_row['EventSIgnupId'] . "'";
                    $dtlEA = $cGlobali->SelectQuery($selEAQuery);
                    if (count($dtlEA) > 1) {
                        if ($dtlEA[$am]['NumOfTickets'] < $tickc) {
                            $am++;
                            $tickc = 1;
                        } else {
                            if ($signupId != $list_row['EventSIgnupId']) {
                                $am = 0;
                                $signupId = $list_row['EventSIgnupId'];
                            }
                            $tickc++;
                        }
                    } else {
                        $am = 0;
                    }


                    $selESQuery = "SELECT PaymentTransId, PromotionCode,Qty,Fees,eChecked FROM eventsignup WHERE id='" . $list_row['EventSIgnupId'] . "' and eChecked!='Refunded' and eChecked!='Canceled'";
                    $dtlES = $cGlobali->SelectQuery($selESQuery);

                    if ($dtlES[0]['PaymentTransId'] != 'A1') {
                        $PaymentTransId = $dtlES[0]['PaymentTransId'];
                    } else if ($free[0]['Free'] == 1) {
                        $PaymentTransId = "Free";
                    }/* else {
                        $selChqPay = "SELECT EventSignupId, ChqNo, ChqDt, ChqBank, Cleared FROM ChqPmnts WHERE EventSignupId='" . $list_row['EventSIgnupId'] . "'";
                        $dtlCP = $Global->SelectQuery($selChqPay);

                        $PaymentTransId = $dtlCP[0]['ChqNo'];
                    }*/
                    if (($dtlES[0]['PromotionCode'] != 'X' && $dtlES[0]['Fees'] == 0) || $dtlES[0]['PromotionCode'] == 'PayatCounter' || $dtlES[0]['PromotionCode'] == 'CashonDelivery') {
                        $PromotionCode = $dtlES[0]['PromotionCode'];
                    }

                    if ($PaymentTransId != '' || $PromotionCode != '') {
                        $ExportAttendee[$cou]['EventSIgnupId'] = $list_row['EventSIgnupId'];
                        //get the event signup date				
                        $ExportAttendee[$cou]['SignupDt'] = $cGlobali->GetSingleFieldValue("SELECT signupdate FROM eventsignup WHERE Id='" . $list_row['EventSIgnupId'] . "'");
                        //ends get signup date
                        $ExportAttendee[$cou]['PaymentTransId'] = $PaymentTransId;
                        $ExportAttendee[$cou]['PromotionCode'] = $PromotionCode;
                        $ExportAttendee[$cou]['Name'] = $list_row['Name'];
                        $ExportAttendee[$cou]['Email'] = $list_row['Email'];
                        $ExportAttendee[$cou]['Company'] = $list_row['Company'];
//                        $ExportAttendee[$cou]['field1'] = $Global->GetSingleFieldValue("SELECT field1 FROM Attendees WHERE Id='" . $list_row['Id'] . "'");
//                        $ExportAttendee[$cou]['field2'] = $Global->GetSingleFieldValue("SELECT field2 FROM Attendees WHERE Id='" . $list_row['Id'] . "'");
//                        $ExportAttendee[$cou]['field3'] = $Global->GetSingleFieldValue("SELECT field3 FROM Attendees WHERE Id='" . $list_row['Id'] . "'");
//                        $ExportAttendee[$cou]['field4'] = $Global->GetSingleFieldValue("SELECT field4 FROM Attendees WHERE Id='" . $list_row['Id'] . "'");
                        $ExportAttendee[$cou]['Phone'] = $list_row['Phone'];
                        $ExportAttendee[$cou]['aId'] = $list_row['Id'];
                        $ExportAttendee[$cou]['Amount'] = ($dtlEA[$am]['TicketAmt'] / $dtlEA[$am]['NumOfTickets']);
                        $ExportAttendee[$cou]['Paid'] = ($dtlES[0]['Fees'] * $dtlES[0]['Qty']);
                        if ($dtlES[0]['eChecked'] == "NotVerified") {
                            $ExportAttendee[$cou]['eChecked'] = "Pending";
                        } else if ($dtlES[0]['eChecked'] == "Verified") {
                            $ExportAttendee[$cou]['eChecked'] = "Confirmed";
                        }
                        $totamt+=$dtlES[0]['Fees'];
                        $cou++;
                        $Attendee++;

                        //echo 
                    }
                }


                $out = 'Receipt No.,Signup Date,Transaction/Cheque No.,Name,Email,Company,Phone No.,Amount,Paid,Status';
                $out .="\n";
                $columns = 4;
                for ($i = 0; $i < count($ExportAttendee); $i++) {
                    $out .='"' . $ExportAttendee[$i]['EventSIgnupId'] . '",';
                    $out .='"' . $ExportAttendee[$i]['SignupDt'] . '",';
                    $out .='"' . $ExportAttendee[$i]['PaymentTransId'] . '",';
                    $out .='"' . $ExportAttendee[$i]['Name'] . '",';
                    $out .='"' . $ExportAttendee[$i]['Email'] . '",';
                    $out .='"' . $ExportAttendee[$i]['Company'] . '",';
                    $out .='"' . $ExportAttendee[$i]['Phone'] . '",';
                    $out .='"' . $ExportAttendee[$i]['Amount'] . '",';
                    $out .='"' . $ExportAttendee[$i]['Paid'] . '",';
                    $out .='"' . $ExportAttendee[$i]['eChecked'] . '",';
                    $out .="\n";
                }

                $to = $dtuser[0]['Email'];
                if ($OEmails != "") {
                    $to.="," . $OEmails;
                }
                $subject = "Your Attendees are ready to join your event. Here's who'll be joining you ";
                $message = "<p>Hi " . $dtuser[0]['FirstName'] . ",</p>
<p>Thank you for choosing MeraEvents as ticketing partners for the event " . stripslashes($row['Title']) . "</p>
<p>We have done our best to successfully promote your event. Kindly find attached the list of attendees.</p>
<p>We suggest that you Click <a href='http://www.meraevents.com/blog/meraevents-e-ticket-verification-process/'>HERE</a> to know more about our e-Ticket Verification Process.
</p>
<p>Thank you for availing our services and we look forward to working with you again in the future.</p>
<p><br />
</p>
<p>Sincerely,</p>";

                $filename = "attendee_" . $row['Id'] . ".csv";






                $cc = $replyto = NULL;
                $bcc = 'amareshwarilinga@meraevents.com';
                $from = 'MeraEvents<admin@meraevents.com>';
                $to = 'qison@meraevents.com';


                if ($Attendee > 0) {

                    $commonFunctions->sendEmail($to, $cc, $bcc, $from, $replyto, $subject, $message, $out, $filename);
                    //mail('sunila@meraevents.com', $subject,$message, $headers);
                    //mail($to, $subject, $message, $headers);

                    $InsQuery = "insert into AttendeesList (EventId,Status) values ('" . $row['Id'] . "','1')";
                    $resIns = $Global->ExecuteQuery($InsQuery);
                }
            }
        }
    }

 
    
}
?>
