<?php

/* * ********************************************************************************************** 
 * 	Page Details : Cron Job Transactions happen yesterday and Send Mail
 * 	Created by Sunil / Last Updation Details : 

 * ********************************************************************************************** */
include_once("commondbdetails.php");
include('../ctrl/MT/cGlobali.php');
include_once '../ctrl/includes/common_functions.php';
$Global = new cGlobali();
$commonFunctions = new functions();
$_GET = $commonFunctions->stripData($_GET, 1);
$_POST = $commonFunctions->stripData($_POST, 1);
$_REQUEST = $commonFunctions->stripData($_REQUEST, 1);


if ($_GET['runNow'] == 1) {
    ini_set('memory_limit', '300M');

    //CRETAE THE YESTERDAYS START / END DATE
    $yesterdaySDateIST = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 1), date("Y"))) . ' 00:00:01';
    $yesterdaySDate = $commonFunctions->convertTime($yesterdaySDateIST, DEFAULT_TIMEZONE);
    $yesterdayEDateIST = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 1), date("Y"))) . ' 23:59:59';
    $yesterdayEDate = $commonFunctions->convertTime($yesterdayEDateIST, DEFAULT_TIMEZONE);


    //$yesterdaySDate = '2013-12-31 00:00:01';
    //$yesterdayEDate = '2013-12-31 23:59:59';


    /* ----------------BengaluruCity daily transactions---------------- */

//list of cities
//    print_r($_GET);
    if (isset($_GET['reportType']) && $_GET['reportType'] == 'cjTransactions') {
        $citiesArr = array("All");
    } else {
        //list of cities
        $citiesArr = array("Hyderabad", "Chennai", "Bengaluru", "Delhi-NCR", "Pune", "Mumbai");
    }

    $EventSqlAdd = $UserSqlAdd = NULL;
    foreach ($citiesArr as $currentCity) {
         if ($currentCity == "All") {
            $to = 'team@meraevents.com';
        } elseif ($currentCity == "Hyderabad") {
            $to = 'naidu@meraevents.com,sreekanthp@meraevents.com,saikiranj@meraevents.com';
            $EventSqlAdd = " and e.cityid in (47, 448) ";
            $UserSqlAdd = " and u.cityd in (47, 448) ";
        } elseif ($currentCity == "Chennai") {
            $to = 'naidu@meraevents.com,sreekanthp@meraevents.com,harikrishnas@meraevents.com';
            $EventSqlAdd = " and e.cityid=39 ";
            $UserSqlAdd = " and u.cityid=39 ";
        } elseif ($currentCity == "Bengaluru") {
            $to = 'naidu@meraevents.com,sreekanthp@meraevents.com,harikrishnas@meraevents.com';
            $EventSqlAdd = " and e.cityid=37 ";
            $UserSqlAdd = " and u.cityid=37 ";
        } elseif ($currentCity == "Delhi-NCR") {
            $to = 'naidu@meraevents.com,sreekanthp@meraevents.com,gagandeeps@meraevents.com,harmeet@meraevents.com';
            $EventSqlAdd = " and (e.stateid=53 or e.cityid in (330,331,383,408,484)) ";
            $UserSqlAdd = " and (u.stateid=53 or u.cityid in (330,331,383,408,484)) ";
        } elseif ($currentCity == "Pune") {
            $to = 'naidu@meraevents.com,sreekanthp@meraevents.com,shaileshp@meraevents.com,sham@meraevents.com';
            $EventSqlAdd = " and e.cityid=77 ";
            $UserSqlAdd = " and u.cityid=77 ";
        } elseif ($currentCity == "Mumbai") {
            $to = 'naidu@meraevents.com,sreekanthp@meraevents.com,shaileshp@meraevents.com,sham@meraevents.com,aarond@meraevents.com';
            $EventSqlAdd = " and e.cityid in (14,393) ";
            $UserSqlAdd = " and u.cityid in (14, 393) ";
        }




        $TotalAmount = 0;
        $TotCard = 0;
        $TotPay = 0;
        $TotChk = 0;
        $cntTransactionRES = 1;

        //Display list of Successful Transactions
        $TransactionQuery = "SELECT count(s.id) 'TransCount',s.eventid as EventId, s.id as Id,  sum(s.quantity) 'ticketQty', sum(s.totalamount-s.eventextrachargeamount) 'totalAmount', e.title as Title FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate <= '" . $yesterdayEDate . "' AND (s.totalamount != 0 AND (s.paymenttransactionid != 'A1')) " . $EventSqlAdd . " and s.paymentstatus not in ('Canceled','refunded')  group by s.eventid ORDER BY s.eventid, s.signupdate DESC";
        $resPGTransaction = $Global->justSelectQuery($TransactionQuery);

        //echo $TransactionQuery."<br><br>";

        $cntPGTran = 0;





        $Msg = "<html xmlns='http://www.w3.org/1999/xhtml'>
			<head>
			<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
			<title>Untitled Document</title>
			<style>
			body {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 12px;
				font-weight: normal;
				color: #000000;
			}
			.style1 {font-size: 11px}
			</style>
			</head>
			<body>
			<table width='100%' border='1' cellpadding='0' cellspacing='0' bordercolor='#F5F4EF'>
			<thead>";
        $Msg .= "   <tr bgcolor='#94D2F3'>
		  	<td class='tblinner' valign='middle' width='6%' align='center'>Sr. No.</td>
			<td class='tblinner' valign='middle' width='32%' align='center'>Event Details</td>
            <td class='tblinner' valign='middle' width='19%' align='center'>Qty</td>
            <td class='tblinner' valign='middle' width='11%' align='center'>Amount (Rs.)</td>
          </tr>
        </thead>";
        while ($TransactionRES = $resPGTransaction->fetch_assoc()) {
            if (strlen($TransactionRES['EventId']) > 0) {
                $totalAmount = round($TransactionRES['totalAmount'], 2);
                $Msg .= "<tr>
			<td class='tblinner' valign='middle' width='6%' align='center' ><font color='#000000'>" . $cntTransactionRES . "</font></td>
			<td class='tblinner' valign='middle' width='32%' align='left'><font color='#000000'>" . $TransactionRES['Title'] . "(" . $TransactionRES['EventId'] . ")</font></td>   		
			<td class='tblinner' valign='middle' width='19%' align='right'><font color='#000000'>" . $TransactionRES['ticketQty'] . "</font></td>
			<td class='tblinner' valign='middle' width='11%' align='right'><font color='#000000'>" . $totalAmount . "</font></td>
          </tr>";
                $TotalAmount += $totalAmount;
                $TotCard +=$totalAmount;
                $cntTransactionRES++;
                $cntPGTran+=$TransactionRES['TransCount'];
            }
        }


        $Msg .= "<tr><td colspan='3'>Total Card Transactions:</td><td  align='right'><font color='#000000'>Rs. " . $TotCard . "</font></td></tr>";
        //Pay at Counter
        $PayatCounterQuery = "SELECT s.eventid as EventId, s.id as Id,  sum(s.quantity) 'ticketQty', sum(s.totalamount-s.eventextrachargeamount) 'totalAmount', e.title as Title FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate <= '" . $yesterdayEDate . "' AND (s.totalamount != 0 AND (s.discount = 'Y' or s.discount ='PayatCounter' )) " . $EventSqlAdd . "  group by s.eventid ORDER BY s.eventid, s.signupdate DESC";
        $resPayatCounter = $Global->justSelectQuery($PayatCounterQuery);

        $cntPayatCounter = 0;

        while ($PayatCounterRES = $resPayatCounter->fetch_assoc()) {


            if (strlen($PayatCounterRES['EventId']) > 0) {
                $totalPAmount = round($PayatCounterRES['totalAmount'], 2);
                $Msg .= "<tr>
			<td class='tblinner' valign='middle' width='6%' align='center' ><font color='#000000'>" . $cntTransactionRES . "</font></td>
			<td class='tblinner' valign='middle' width='32%' align='left'><font color='#000000'>" . $PayatCounterRES['Title'] . "(" . $PayatCounterRES['EventId'] . ")</font></td>  		
			<td class='tblinner' valign='middle' width='19%' align='right'><font color='#000000'>" . $PayatCounterRES['ticketQty'] . "</font></td>
			<td class='tblinner' valign='middle' width='11%' align='right'><font color='#000000'>" . $totalPAmount . "</font></td>
          </tr>";
                $TotalAmount += $totalPAmount;
                $TotPay += $totalPAmount;
                $cntTransactionRES++;
                $cntPayatCounter++;
            }
        }


        $Msg .= "<tr><td colspan='3'>Total Pay at Counter:</td><td  align='right'><font color='#000000'>Rs. " . $TotPay . "</font></td></tr>";
//Cash On Delivery
        $CODQuery = "SELECT s.eventid as EventId, s.id as Id,  sum(s.quantity) 'ticketQty', sum(s.totalamount-s.eventextrachargeamount) 'totalAmount', e.title as Title FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate <= '" . $yesterdayEDate . "' AND (s.totalamount != 0 AND (s.paymentgatewayid =2 )) and s.paymentstatus!='Canceled' " . $EventSqlAdd . "  group by s.eventid ORDER BY s.eventid, s.signupdate DESC";
        $resCOD = $Global->justSelectQuery($CODQuery);

        $cntCOD = 0;

        while ($CODRES = $resCOD->fetch_assoc()) {


            if (strlen($CODRES['EventId']) > 0) {
                $totalCAmount = round($CODRES['totalAmount'], 2);

                $Msg .= "<tr>
			<td class='tblinner' valign='middle' width='6%' align='center' ><font color='#000000'>" . $cntTransactionRES . "</font></td>
			<td class='tblinner' valign='middle' width='32%' align='left'><font color='#000000'>" . $CODRES['Title'] . "(" . $CODRES['EventId'] . ")</font></td>  		
			<td class='tblinner' valign='middle' width='19%' align='right'><font color='#000000'>" . $CODRES['ticketQty'] . "</font></td>
			<td class='tblinner' valign='middle' width='11%' align='right'><font color='#000000'>" . $totalCAmount . "</font></td>
          </tr>";
                $TotalAmount +=$totalCAmount;
                $TotCOD += $totalCAmount;
                $cntTransactionRES++;
            }
        }



        $Msg .= "<tr><td colspan='3'>Total Cash on Delivery:</td><td  align='right'><font color='#000000'>Rs. " . $TotCOD . "</font></td></tr>";
        //Display list of Cheque Transactions 

        $ChqTranQuery = "SELECT s.eventid as EventId, s.id as Id,  sum(s.quantity) 'ticketQty', sum(s.totalamount-s.eventextrachargeamount) 'totalAmount', e.title as Title, cq.chequenumber as ChqNo, cq.chequedate as ChqDt, cq.chequebank as ChqBank, cq.status as Cleared, cq.id AS chequeId FROM chequepayments AS cq, eventsignup AS s, event AS e WHERE s.id = cq.eventsignupid AND s.paymentmodeid = 3 AND s.eventid = e.id AND s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate <= '" . $yesterdayEDate . "' AND s.totalamount != 0 " . $EventSqlAdd . "  group by s.eventid ORDER BY s.eventid, s.signupdate DESC";
        $resChqTran = $Global->justSelectQuery($ChqTranQuery);

        $cntChqTran = 0;

        while ($ChqTranRES = $resChqTran->fetch_assoc()) {


            $ChqStatus = '';


            if (strlen($ChqTranRES['EventId']) > 0) {
                $totalChAmount = round($ChqTranRES['totalAmount'], 2);
                $Msg .= "<tr >
				<td class='tblinner' valign='top' width='6%' align='center'><font color='#000000'>" . $cntTransactionRES . "</font></td>
				<td class='tblinner' valign='top' width='22%' align='left'><font color='#000000'>" . $ChqTranRES['Title'] . " (" . $ChqTranRES['EventId'] . ")</font></td>
				<td class='tblinner' valign='top' width='5%' align='right'><font color='#000000'>" . $ChqTranRES['ticketQty'] . "</font></td>
				<td class='tblinner' valign='top' width='5%' align='right'><font color='#000000'>" . $totalChAmount . "</font></td>
			  </tr>";

                $TotalAmount += $totalChAmount;
                $TotChk +=$totalChAmount;
                $cntTransactionRES++;
                $cntChqTran++;
            }
        }


        $Msg .= "<tr><td colspan='3'>Total Cheque Transactions:</td><td  align='right'><font color='#000000'>Rs. " . $TotChk . "</font></td></tr>";
        $Msg .= "<tr><td colspan='2'>Total:</td><td colspan='2' align='right'><font color='#000000'>Rs. " . $TotalAmount . "</font></td></tr>";

        $Msg .= '<tr><td colspan="4">' . 'Total Payment Gateway Transactions : ' . $cntPGTran . '</td></tr>';
        $Msg .= '<tr><td colspan="4">' . 'Total Pay at Counter : ' . $cntPayatCounter . '</td></tr>';
        $Msg .= '<tr><td colspan="4">' . 'Total Cheque Transactions : ' . $cntChqTran . '</td></tr>';


        //Get the count of todays event signup
        $cntTodaySignup = 0;
        $todaySignupQuery = "SELECT sum(s.quantity) as totusers   FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate <= '" . $yesterdayEDate . "'  AND (s.totalamount=0 or (s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2 or s.paymentmodeid=3 or s.discount !='X') ORDER BY s.eventid, s.signupdate DESC";
        //Updated query by changing  (s.PromotionCode !='X' and s.Fees=0)    TO (s.PromotionCode !='X') to match ctrl report
        $cntTodaySignup = $Global->SelectQuery($todaySignupQuery);
        $Msg .= '<tr><td colspan="4">' . 'Total Delegates Signed up for Tickets  : ' . $cntTodaySignup[0][totusers] . '</td></tr>';

        //Get the count of days free event signup
        $cntFreeTodaySignup = 0;
        $FreeSignupQuery = "SELECT sum(s.quantity) as totusersfree  FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate <= '" . $yesterdayEDate . "' AND (e.registrationtype=1 or s.totalamount=0) " . $EventSqlAdd . "  ORDER BY s.eventid, s.signupdate DESC";
        $cntFreeTodaySignup = $Global->SelectQuery($FreeSignupQuery);
        $Msg .= '<tr><td colspan="4">' . ' Delegates Signed up for Free Tickets : ' . $cntFreeTodaySignup[0]['totusersfree'] . '</td></tr>';

        //Get the count of todays user registered
        $cntPaidUsers = 0;
        $todaysRegUserQuery = "SELECT sum(s.quantity) as totuserspaid  FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate <= '" . $yesterdayEDate . "' AND ((s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2 or s.paymentmodeid=3 or s.discount !='X') and s.totalamount!=0 " . $EventSqlAdd . "  ORDER BY s.eventid, s.signupdate DESC";
        $cntPaidUsers = $Global->SelectQuery($todaysRegUserQuery);
        $Msg .= '<tr><td colspan="4">' . 'Delegates Signed up for Paid Tickets : ' . $cntPaidUsers[0]['totuserspaid'] . '</td></tr>';

        $cntUsers = 0;
        $totUserQuery = "SELECT u.id  FROM `user` u WHERE 1 and u.cts between '" . $yesterdaySDate . "' and '" . $yesterdayEDate . "' " . $UserSqlAdd;
        $cntUsers = mysqli_num_rows($Global->justSelectQuery($totUserQuery));
        $Msg .= '<tr><td colspan="4">' . 'Total Users Signed up : ' . $cntUsers . '</td></tr>';

        //Get the count of todays organizer registered
        $cntTodaysOrgReg = 0;
        $todaysRegOrgQuery = "SELECT u.id FROM user AS u, organizer AS org WHERE u.id = org.userid AND u.cts >= '" . $yesterdaySDate . "' AND u.cts <= '" . $yesterdayEDate . "' " . $UserSqlAdd;
        $cntTodaysOrgReg = mysqli_num_rows($Global->justSelectQuery($todaysRegOrgQuery));
        $Msg .= '<tr><td colspan="4">' . 'Todays Total Organizer Registered : ' . $cntTodaysOrgReg . '</td></tr>';

        //Get the count of todays event registered
        $cntTodaysEvtReg = 0;
        $todaysRegEvtQuery = "SELECT e.id FROM event AS e WHERE (categoryid !=0 or (categoryid=0 and status=1)) and e.cts between '" . $yesterdaySDate . "' and '" . $yesterdayEDate . "'";
        $cntTodaysEvtReg = mysqli_num_rows($Global->justSelectQuery($todaysRegEvtQuery));
        $Msg .= '<tr><td colspan="4">' . 'Todays Total Events added : ' . $cntTodaysEvtReg . '</td></tr>';
        //Paid Events
        $cntTodaysPaid = 0;
        $todaysPaidQuery = "SELECT id  FROM event as e where  (categoryid !=0 or (categoryid=0 and status=1)) and e.cts between '" . $yesterdaySDate . "' and '" . $yesterdayEDate . "' and e.registrationtype=2 " . $EventSqlAdd;
        $cntTodaysPaid = mysqli_num_rows($Global->justSelectQuery($todaysPaidQuery));
        $Msg .= '<tr><td colspan="4">' . 'Todays Total Paid Events : ' . $cntTodaysPaid . '</td></tr>';
        //Free Events
        $cntTodaysFree = 0;
        $todaysFree = "SELECT id  FROM event as e where  (categoryid !=0 or (categoryid=0 and status=1)) and e.cts between '" . $yesterdaySDate . "' and '" . $yesterdayEDate . "' and e.registrationtype=1 " . $EventSqlAdd;
        $cntTodaysFree = mysqli_num_rows($Global->justSelectQuery($todaysFree));
        $Msg .= '<tr><td colspan="4">' . 'Todays Total Free Events : ' . $cntTodaysFree . '</td></tr>';

        //No Registration Events
        $cntTodaysNoReg = 0;
        $todaysNoRegQuery = "SELECT id  FROM event as e where  (categoryid !=0 or (categoryid=0 and status=1)) and e.cts between '" . $yesterdaySDate . "' and '" . $yesterdayEDate . "' and e.registrationtype=3 " . $EventSqlAdd;
        $cntTodaysNoReg = mysqli_num_rows($Global->justSelectQuery($todaysNoRegQuery));
        $Msg .= '<tr><td colspan="4">' . 'Todays No Registration Events : ' . $cntTodaysNoReg . '</td></tr>';

        //No Unique Events
        $unqEvents = " select distinct(e.id) FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate <= '" . $yesterdayEDate . "' AND ((s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2 or s.discount !='X') and s.totalamount !=0  and s.paymentstatus!='Canceled' " . $EventSqlAdd . " ";
        $TotalunqEvents = mysqli_num_rows($Global->justSelectQuery($unqEvents));
        $Msg .= '<tr><td colspan="4">' . 'No Unique Events : ' . $TotalunqEvents . '</td></tr>';

        //No Unique Organizers
        $unqUserID = " select distinct(e.ownerid) FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate <= '" . $yesterdayEDate . "' AND ((s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2 or s.discount !='X') and s.totalamount!=0   and s.paymentstatus!='Canceled' " . $EventSqlAdd . " ";
        $TotalunqUserID = mysqli_num_rows($Global->justSelectQuery($unqUserID));
        $Msg .= '<tr><td colspan="4">' . 'No Unique Organizers : ' . $TotalunqUserID . '</td></tr>';





        $Msg .= "</table></body></html>";

        //echo $currentCity."<br><hr>".$Msg."<br><br><hr>";
        //SEND EMAIL
        //$to = 'shashi.enjapuri@gmail.com,sudhera99@gmail.com,durgeshmishra2525@gmail.com';
        //$to = 'shashi.enjapuri@gmail.com,pranavim@meraevents.com';
        $subject = '[MeraEvents] Transaction Details From: ' . $yesterdaySDateIST . ' To: ' . $yesterdayEDateIST . ' for ' . $currentCity;
        $message = 'Dear Sir,<br /><br />Transaction Details From: ' . $yesterdaySDateIST . ' To: ' . $yesterdayEDateIST . '<br /><br />It contains only Successful, COD, PayatCounter and Cheque Transactions for ' . $currentCity . ' city.<br /><br />' . $Msg . '<br /><br />Regards,<br>Meraevents Team';


        $cc = $content = $filename = $replyto = NULL;
        $bcc = 'qison@meraevents.com';
        $from = 'MeraEvents<admin@meraevents.com>';
        $commonFunctions->sendEmail($to, $cc, $bcc, $from, $replyto, $subject, $message, $content, $filename);

        unset($TransactionRES);
        unset($PayatCounterRES);
        unset($CODRES);
        unset($ChqTranRES);
    }
//end cities array



    mysql_close();
}
?>