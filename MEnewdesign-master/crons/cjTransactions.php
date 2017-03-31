<?php
include("commondbdetails.php");
/* * ********************************************************************************************** 
 * 	Page Details : Cron Job Transactions happen yesterday and Send Mail
 * 	Created by Sunil / Last Updation Details : 

 * ********************************************************************************************** */
include('../ctrl/MT/cGlobali.php');
//include('../ctrl/includes/commondbdetails.php');
include_once '../ctrl/includes/common_functions.php';
$Global = new cGlobali();
$commonFunctions = new functions();


$_GET = $commonFunctions->stripData($_GET, 1);
$_POST = $commonFunctions->stripData($_POST, 1);
$_REQUEST = $commonFunctions->stripData($_REQUEST, 1);

if ($_GET['runNow'] == 1) {

    ini_set('memory_limit', '300M');

    /* $db_conn = mysql_connect($DBServerName,$DBUserName,$DBPassword);
      mysql_select_db($DBIniCatalog);
     */


    //CRETAE THE YESTERDAYS START / END DATE
    $yesterdaySDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 1), date("Y"))) . ' 00:00:01';
    $yesterdaySDate =$commonFunctions->convertTime($yesterdaySDate, DEFAULT_TIMEZONE);
    $yesterdayEDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 1), date("Y"))) . ' 23:59:59';
    $yesterdayEDate =$commonFunctions->convertTime($yesterdayEDate, DEFAULT_TIMEZONE);

    $TotalAmount = 0;
    $TotCard = 0;
    $TotPay = 0;
    $TotChk = 0;
    $cntTransactionRES = 1;

    //Display list of Successful Transactions
    $TransactionQuery = "SELECT s.eventid as EventId, s.id as Id, s.signupdate as SignupDt, s.quantity as Qty, s.totalamount as Fees, s.paymenttransactionid as PaymentTransId, e.title as Title FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate <= '" . $yesterdayEDate . "' AND (s.totalamount != 0 AND (s.paymentmodeid =1 and s.paymenttransactionid != 'A1')) ORDER BY s.eventid, s.signupdate DESC";
    //$resPGTransaction=mysql_query($TransactionQuery);
    $resPGTransaction = $Global->justSelectQuery($TransactionQuery);

    $cntPGTran = 0;

    while ($row = $resPGTransaction->fetch_assoc()) {
        $TransactionRES[$cntPGTran] = $row;
        $cntPGTran++;
    }

    //$TransactionRES = $Global->SelectQuery($TransactionQuery);


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
			<td class='tblinner' valign='middle' width='10%' align='center'>Receipt No.</td>
            <td class='tblinner' valign='middle' width='16%' align='center'>Date</td>
            <td class='tblinner' valign='middle' width='32%' align='center'>Event Details</td>
            <td class='tblinner' valign='middle' width='21%' align='center'>Transaction No.</td>
            <td class='tblinner' valign='middle' width='19%' align='center'>Qty</td>
            <td class='tblinner' valign='middle' width='11%' align='center'>Amount (Rs.)</td>
          </tr>
        </thead>";

    for ($i = 0; $i < count($TransactionRES); $i++) {
        $Msg .= "<tr>
			<td class='tblinner' valign='middle' width='6%' align='center' ><font color='#000000'>" . $cntTransactionRES . "</font></td>
			<td class='tblinner' valign='middle' width='19%' align='center'><font color='#000000'>" . $TransactionRES[$i]['Id'] . "</font></td>
			<td class='tblinner' valign='middle' width='16%' align='center' ><font color='#000000'>" . $TransactionRES[$i]['SignupDt'] . "</font></td>
			<td class='tblinner' valign='middle' width='32%' align='left'><font color='#000000'>" . $TransactionRES[$i]['Title'] . "</font></td>
			<td class='tblinner' valign='middle' width='21%' align='left'><font color='#000000'>TR: " . $TransactionRES[$i]['PaymentTransId'] . "</font></td>     		
			<td class='tblinner' valign='middle' width='19%' align='right'><font color='#000000'>" . $TransactionRES[$i]['Qty'] . "</font></td>
			<td class='tblinner' valign='middle' width='11%' align='right'><font color='#000000'>" . $TransactionRES[$i]['Fees'] . "</font></td>
          </tr>";
        $TotalAmount += $TransactionRES[$i]['Fees'];
        $TotCard +=$TransactionRES[$i]['Fees'];
        $cntTransactionRES++;
    }
    $Msg .= "<tr><td colspan='4'>Total Card Transactions:</td><td colspan='3' align='right'><font color='#000000'>Rs. " . $TotCard . "</font></td></tr>";
    //Pay at Counter
    $PayatCounterQuery = "SELECT s.eventid as EventId, s.id as Id, s.signupdate as SignupDt, s.quantity as Qty, s.totalamount as Fees, s.paymenttransactionid as PaymentTransId, e.title as Title FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate <= '" . $yesterdayEDate . "' AND (s.totalamount != 0 AND (s.discount = 'Y' or s.discount ='PayatCounter' )) ORDER BY s.eventid, s.signupdate DESC";
    //$resPayatCounter=mysql_query($PayatCounterQuery);
    $resPayatCounter = $Global->justSelectQuery($PayatCounterQuery);

    $cntPayatCounter = 0;

    while ($row1 = $resPayatCounter->fetch_assoc()) {
        $PayatCounterRES[$cntPayatCounter] = $row1;
        $cntPayatCounter++;
    }

    for ($i = 0; $i < count($PayatCounterRES); $i++) {
        $Msg .= "<tr>
			<td class='tblinner' valign='middle' width='6%' align='center' ><font color='#000000'>" . $cntTransactionRES . "</font></td>
			<td class='tblinner' valign='middle' width='19%' align='center'><font color='#000000'>" . $PayatCounterRES[$i]['Id'] . "</font></td>
			<td class='tblinner' valign='middle' width='16%' align='center' ><font color='#000000'>" . $PayatCounterRES[$i]['SignupDt'] . "</font></td>
			<td class='tblinner' valign='middle' width='32%' align='left'><font color='#000000'>" . $PayatCounterRES[$i]['Title'] . "</font></td>
			<td class='tblinner' valign='middle' width='21%' align='left'><font color='#000000'>PayatCounter</font></td>     		
			<td class='tblinner' valign='middle' width='19%' align='right'><font color='#000000'>" . $PayatCounterRES[$i]['Qty'] . "</font></td>
			<td class='tblinner' valign='middle' width='11%' align='right'><font color='#000000'>" . $PayatCounterRES[$i]['Fees'] . "</font></td>
          </tr>";
        $TotalAmount += $PayatCounterRES[$i]['Fees'];
        $TotPay += $PayatCounterRES[$i]['Fees'];
        $cntTransactionRES++;
    }
    $Msg .= "<tr><td colspan='4'>Total Pay at Counter:</td><td colspan='3' align='right'><font color='#000000'>Rs. " . $TotPay . "</font></td></tr>";
//Cash On Delivery
    $CODQuery = "SELECT s.eventid as EventId, s.id as Id, s.signupdate as SignupDt, s.quantity as Qty, s.totalamount as Fees, s.paymenttransactionid as PaymentTransId, e.title as Title FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate <= '" . $yesterdayEDate . "' AND (s.totalamount != 0 AND (s.paymentgatewayid =2 )) and s.paymentstatus!='Canceled' ORDER BY s.eventid, s.signupdate DESC";
    //$resCOD=mysql_query($CODQuery);
    $resCOD = $Global->justSelectQuery($CODQuery);

    $cntCOD = 0;

    while ($row2 = $resCOD->fetch_assoc()) {
        $CODRES[$cntCOD] = $row2;
        $cntCOD++;
    }


    for ($j = 0; $j < count($CODRES); $j++) {
        $Msg .= "<tr>
			<td class='tblinner' valign='middle' width='6%' align='center' ><font color='#000000'>" . $cntTransactionRES . "</font></td>
			<td class='tblinner' valign='middle' width='19%' align='center'><font color='#000000'>" . $CODRES[$j]['Id'] . "</font></td>
			<td class='tblinner' valign='middle' width='16%' align='center' ><font color='#000000'>" . $CODRES[$j]['SignupDt'] . "</font></td>
			<td class='tblinner' valign='middle' width='32%' align='left'><font color='#000000'>" . $CODRES[$j]['Title'] . "</font></td>
			<td class='tblinner' valign='middle' width='21%' align='left'><font color='#000000'>CashonDelivery</font></td>     		
			<td class='tblinner' valign='middle' width='19%' align='right'><font color='#000000'>" . $CODRES[$j]['Qty'] . "</font></td>
			<td class='tblinner' valign='middle' width='11%' align='right'><font color='#000000'>" . $CODRES[$j]['Fees'] . "</font></td>
          </tr>";
        $TotalAmount += $CODRES[$j]['Fees'];
        $TotCOD += $CODRES[$j]['Fees'];
        $cntTransactionRES++;
    }
    $Msg .= "<tr><td colspan='4'>Total Cash on Delivery:</td><td colspan='3' align='right'><font color='#000000'>Rs. " . $TotCOD . "</font></td></tr>";
    //Display list of Cheque Transactions 

    $ChqTranQuery = "SELECT s.eventid as EventId, s.id as Id, s.signupdate as SignupDt, s.quantity as Qty, s.totalamount as Fees, s.paymenttransactionid as PaymentTransId, e.title as Title, cq.chequenumber as ChqNo, cq.chequedate as ChqDt, cq.chequebank as ChqBank, cq.status as Cleared, cq.id AS chequeId FROM chequepayments AS cq, eventsignup AS s, event AS e WHERE s.id = cq.eventsignupid AND s.paymentmodeid = 3 AND s.eventid = e.id AND s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate <= '" . $yesterdayEDate . "' AND s.totalamount != 0 ORDER BY s.eventid, s.signupdate DESC";
    //$ChqTranRES = $Global->SelectQuery($ChqTranQuery);
    //$resChqTran=mysql_query($ChqTranQuery);
    $resChqTran = $Global->justSelectQuery($ChqTranQuery);

    $cntChqTran = 0;

    while ($row = $resChqTran->fetch_assoc()) {
        $ChqTranRES[$cntChqTran] = $row;
        $cntChqTran++;
    }

    for ($i = 0; $i < count($ChqTranRES); $i++) {
        $ChqStatus = '';
        //Cheque Status : Pending[0] / Received[1] / Deposited[2] / Cleared[3] / Failure [4] 
        if ($ChqTranRES[$i]['Cleared'] == 0)
            $ChqStatus = "Pending";
        if ($ChqTranRES[$i]['Cleared'] == 1)
            $ChqStatus = "Received";
        if ($ChqTranRES[$i]['Cleared'] == 2)
            $ChqStatus = "Deposited";
        if ($ChqTranRES[$i]['Cleared'] == 3)
            $ChqStatus = "Cleared";
        if ($ChqTranRES[$i]['Cleared'] == 4)
            $ChqStatus = "Failure";

        $Msg .= "<tr >
				<td class='tblinner' valign='top' width='6%' align='center'><font color='#000000'>" . $cntTransactionRES . "</font></td>
				<td class='tblinner' valign='top' width='8%' align='center'><font color='#000000'>" . $ChqTranRES[$i]['Id'] . "</font></td>
				<td class='tblinner' valign='top' width='15%' align='center' ><font color='#000000'>" . $ChqTranRES[$i]['SignupDt'] . "</font></td>
				<td class='tblinner' valign='top' width='22%' align='left'><font color='#000000'>" . $ChqTranRES[$i]['Title'] . "</font></td>
				<td class='tblinner' valign='top' width='21%' align='left'><font color='#000000'>CH: " . $ChqTranRES[$i]['ChqNo'] . "<br /> Bank: " . $ChqTranRES[$i]['ChqBank'] . "<br /> Date: " . $ChqTranRES[$i]['ChqDt'] . "<br />Status: " . $ChqStatus . "</font></td>
				<td class='tblinner' valign='top' width='5%' align='right'><font color='#000000'>" . $ChqTranRES[$i]['Qty'] . "</font></td>
				<td class='tblinner' valign='top' width='5%' align='right'><font color='#000000'>" . $ChqTranRES[$i]['Fees'] . "</font></td>
			  </tr>";

        $TotalAmount += $ChqTranRES[$i]['Fees'];
        $TotChk += $ChqTranRES[$i]['Fees'];
        $cntTransactionRES++;
    }
    $Msg .= "<tr><td colspan='4'>Total Cheque Transactions:</td><td colspan='3' align='right'><font color='#000000'>Rs. " . $TotChk . "</font></td></tr>";
    $Msg .= "<tr><td>Total:</td><td colspan='6' align='right'><font color='#000000'>Rs. " . $TotalAmount . "</font></td></tr>";

    $Msg .= '<tr><td colspan="7">' . 'Total Payment Gateway Transactions : ' . $cntPGTran . '</td></tr>';
    $Msg .= '<tr><td colspan="7">' . 'Total Pay at Counter : ' . $cntPayatCounter . '</td></tr>';
    $Msg .= '<tr><td colspan="7">' . 'Total Cheque Transactions : ' . $cntChqTran . '</td></tr>';


    //Get the count of todays event signup
    $cntTodaySignup = 0;
    $todaySignupQuery = "SELECT sum(s.quantity) as totusers   FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate <= '" . $yesterdayEDate . "' AND (s.totalamount=0 or (s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2 or s.paymentmodeid=3 or s.discount !='X') ORDER BY s.eventid, s.signupdate DESC";
    //Updated query by changing  (s.PromotionCode !='X' and s.Fees=0)    TO (s.PromotionCode !='X') to match ctrl report
    $cntTodaySignup = $Global->SelectQuery($todaySignupQuery);
    $Msg .= '<tr><td colspan="7">' . 'Total Delegates Signed up for Tickets  : ' . $cntTodaySignup[0][totusers] . '</td></tr>';



    //Get the count of days free event signup
    $cntFreeTodaySignup = 0;
    $FreeSignupQuery = "SELECT sum(s.quantity) as totusersfree  FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate <= '" . $yesterdayEDate . "' AND (e.registrationtype=1 or s.totalamount=0) ORDER BY s.eventid, s.signupdate DESC";
    /* $FreeSignup = mysql_query($FreeSignupQuery);
      $cntFreeTodaySignup = mysql_fetch_array($FreeSignup); */
    $cntFreeTodaySignup = $Global->SelectQuery($FreeSignupQuery);
    $Msg .= '<tr><td colspan="7">' . ' Delegates Signed up for Free Tickets : ' . $cntFreeTodaySignup[0][totusersfree] . '</td></tr>';




    //Get the count of todays user registered
    $cntPaidUsers = 0;
    $todaysRegUserQuery = "SELECT sum(s.quantity) as totuserspaid  FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate <= '" . $yesterdayEDate . "' AND ((s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2 or s.paymentmodeid=3 or s.discount !='X') and s.totalamount!=0 ORDER BY s.eventid, s.signupdate DESC";
    //Updated query by changing  (s.PromotionCode !='X' and s.Fees=0)    TO (s.PromotionCode !='X') to match ctrl report
    //$cntPaidUsers = mysql_fetch_array(mysql_query($todaysRegUserQuery));
    $cntPaidUsers = $Global->SelectQuery($todaysRegUserQuery);
    $Msg .= '<tr><td colspan="7">' . 'Delegates Signed up for Paid Tickets : ' . $cntPaidUsers[0][totuserspaid] . '</td></tr>';




    $cntUsers = 0;
    $totUserQuery = "SELECT u.id  FROM `user` u WHERE 1 and u.cts between '" . $yesterdaySDate . "' and '" . $yesterdayEDate . "'";
    //$cntUsers = mysql_num_rows(mysql_query($totUserQuery));
    $cntUsers = mysqli_num_rows($Global->justSelectQuery($totUserQuery));
    $Msg .= '<tr><td colspan="7">' . 'Total Users Signed up : ' . $cntUsers . '</td></tr>';

    //Get the count of todays organizer registered
    $cntTodaysOrgReg = 0;
    $todaysRegOrgQuery = "SELECT u.id FROM user AS u, organizer AS org WHERE u.id = org.userid AND u.cts >= '" . $yesterdaySDate . "' AND u.cts <= '" . $yesterdayEDate . "'";
    //$cntTodaysOrgReg = mysql_num_rows(mysql_query($todaysRegOrgQuery));
    $cntTodaysOrgReg = mysqli_num_rows($Global->justSelectQuery($todaysRegOrgQuery));
    $Msg .= '<tr><td colspan="7">' . 'Todays Total Organizer Registered : ' . $cntTodaysOrgReg . '</td></tr>';

    //Get the count of todays event registered
    $cntTodaysEvtReg = 0;

    // old query $todaysRegEvtQuery = "SELECT e.Id FROM events AS e WHERE e.RegnDt >= '".$yesterdaySDate."' AND e.RegnDt <= '".$yesterdayEDate."'";
    $todaysRegEvtQuery = "SELECT e.id FROM event AS e WHERE (categoryid !=0 or (categoryid=0 and status=1)) and e.cts between '" . $yesterdaySDate . "' and '" . $yesterdayEDate . "'";
    //$cntTodaysEvtReg = mysql_num_rows(mysql_query($todaysRegEvtQuery));
    $cntTodaysEvtReg = mysqli_num_rows($Global->justSelectQuery($todaysRegEvtQuery));
    $Msg .= '<tr><td colspan="7">' . 'Todays Total Events added : ' . $cntTodaysEvtReg . '</td></tr>';


    //Paid Events
    $cntTodaysPaid = 0;
//old query	$todaysPaidQuery = "SELECT Id  FROM events where `RegnDt` between '".$yesterdaySDate."' and '".$yesterdayEDate."' and Free=0 and NoReg = 0";

    $todaysPaidQuery = "SELECT id  FROM event as e where  (categoryid !=0 or (categoryid=0 and status=1)) and e.cts between '" . $yesterdaySDate . "' and '" . $yesterdayEDate . "' and e.registrationtype=2";
    //$cntTodaysPaid = mysql_num_rows(mysql_query($todaysPaidQuery));
    $cntTodaysPaid = mysqli_num_rows($Global->justSelectQuery($todaysPaidQuery));
    $Msg .= '<tr><td colspan="7">' . 'Todays Total Paid Events : ' . $cntTodaysPaid . '</td></tr>';


    //Free Events
    $cntTodaysFree = 0;

    //old query $todaysFree = "SELECT Id  FROM events where `RegnDt` between '".$yesterdaySDate."' and '".$yesterdayEDate."' and Free=1 and NoReg = 0";

    $todaysFree = "SELECT id  FROM event as e where  (categoryid !=0 or (categoryid=0 and status=1)) and e.cts between '" . $yesterdaySDate . "' and '" . $yesterdayEDate . "' and e.registrationtype=1";
    //$cntTodaysFree = mysql_num_rows(mysql_query($todaysFree));
    $cntTodaysFree = mysqli_num_rows($Global->justSelectQuery($todaysFree));
    $Msg .= '<tr><td colspan="7">' . 'Todays Total Free Events : ' . $cntTodaysFree . '</td></tr>';

    //No Registration Events
    $cntTodaysNoReg = 0;
//old query	$todaysNoRegQuery = "SELECT Id  FROM events where `RegnDt` between '".$yesterdaySDate."' and '".$yesterdayEDate."' and NoReg=1";

    $todaysNoRegQuery = "SELECT id  FROM event as e where  (categoryid !=0 or (categoryid=0 and status=1)) and e.cts between '" . $yesterdaySDate . "' and '" . $yesterdayEDate . "' and e.registrationtype=3";
    //$cntTodaysNoReg = mysql_num_rows(mysql_query($todaysNoRegQuery));
    $cntTodaysNoReg = mysqli_num_rows($Global->justSelectQuery($todaysNoRegQuery));
    $Msg .= '<tr><td colspan="7">' . 'Todays No Registration Events : ' . $cntTodaysNoReg . '</td></tr>';

    //No Unique Events
    $unqEvents = " select distinct(e.id) FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate <= '" . $yesterdayEDate . "' AND ((s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2 or s.discount !='X') and s.totalamount !=0  and s.paymentstatus!='Canceled'";




    //$TotalunqEvents = mysql_num_rows(mysql_query($unqEvents));
    $TotalunqEvents = mysqli_num_rows($Global->justSelectQuery($unqEvents));
    $Msg .= '<tr><td colspan="7">' . 'No Unique Events : ' . $TotalunqEvents . '</td></tr>';

    //No Unique Organizers
    $unqUserID = " select distinct(e.ownerid) FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate <= '" . $yesterdayEDate . "' AND ((s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2 or s.discount !='X') and s.totalamount!=0   and s.paymentstatus!='Canceled'";
    //$TotalunqUserID = mysql_num_rows(mysql_query($unqUserID));
    $TotalunqUserID = mysqli_num_rows($Global->justSelectQuery($unqUserID));
    $Msg .= '<tr><td colspan="7">' . 'No Unique Organizers : ' . $TotalunqUserID . '</td></tr>';

    //No Unique Cities
    $unqCityId = " select distinct(e.cityid) FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate <= '" . $yesterdayEDate . "' AND ((s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2 or s.discount !='X') and s.totalamount!=0   $SearchQuery3 $cityId and s.paymentstatus!='Canceled'";
    //$TotalunqCityId = mysql_num_rows(mysql_query($unqCityId));
    $TotalunqCityId = mysqli_num_rows($Global->justSelectQuery($unqCityId));
    $Msg .= '<tr><td colspan="7">' . 'No Unique Cities : ' . $TotalunqCityId . '</td></tr>';



    $Msg .= "</table></body></html>";
    //echo $Msg; exit;
    //SEND EMAIL
    //$to = 'team@meraevents.com';
    $to = 'sunila@meraevents.com,sudhera99@gmail.com';
    $subject = '[MeraEvents] Transaction Details From: ' . $yesterdaySDate . ' To: ' . $yesterdayEDate;
    $message = 'Dear Sir,<br /><br />Transaction Details From: ' . $yesterdaySDate . ' To: ' . $yesterdayEDate . '<br /><br />It contains only Successful, COD, PayatCounter and Cheque Transactions.<br /><br />' . $Msg . '<br /><br />Regards,<br>MeraEvents Team';


    $cc = $content = $filename = $bcc = $replyto = NULL;
    $from = 'MeraEvents<admin@meraevents.com>';
    $commonFunctions->sendEmail($to, $cc, $bcc, $from, $replyto, $subject, $message, $content, $filename);

    //mail($to, $subject, $message, $headers);
    //mail('durgeshmishra2525@gmail.com', $subject, $message, $headers);
    //mail('kumard@meraevents.com', $subject, $message, $headers);
    //EMAIL SENT
//mysql_close();
}
?>
