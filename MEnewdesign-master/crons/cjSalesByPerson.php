<?php

/* * ********************************************************************************************** 
 * 	Page Details : Cron Job Transactions happen yesterday and Send Mail
 * 	Created by Sunil / Last Updation Details : 

 * ********************************************************************************************** */
include_once("commondbdetails.php");
include("../ctrl/MT/cGlobali.php");

$Global = new cGlobali();

//        include_once 'includes/functions.php';
include_once '../ctrl/includes/common_functions.php';

$commonFunctions = new functions();
$_GET = $commonFunctions->stripData($_GET, 1);
$_POST = $commonFunctions->stripData($_POST, 1);
$_REQUEST = $commonFunctions->stripData($_REQUEST, 1);


if ($_GET['runNow'] == 1) {

    ini_set('memory_limit', '300M');

    $MsgCountryExist = '';

    $sqlCities = "SELECT DISTINCT c.name as City,c.id as Id FROM city AS c ORDER BY City ASC";
    $dtlCities = $Global->SelectQuery($sqlCities);

    $SalesQuery = "SELECT id as SalesId,name as SalesName,email as Email from  salesperson where status=1  ORDER BY SalesName";
    $SalesQueryRES = $Global->SelectQuery($SalesQuery);


    //CRETAE THE YESTERDAYS START / END DATE
    $SDt = date("d/m/Y", mktime(0, 0, 0, date("m"), (date("d") - 1), date("Y")));
    $EDt = date("d/m/Y", mktime(0, 0, 0, date("m"), (date("d") - 1), date("Y")));
    $yesterdaySDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 1), date("Y"))) . ' 00:00:01';
    $yesterdaySDate = $commonFunctions->convertTime($yesterdaySDate, DEFAULT_TIMEZONE);
    $yesterdayEDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 1), date("Y"))) . ' 23:59:59';
    $yesterdayEDate = $commonFunctions->convertTime($yesterdayEDate, DEFAULT_TIMEZONE);

    $TotalAmount = 0;
    $cntTransactionRES = 1;

// function to get data for Transaction	
    function getSalesReportsForTransaction($SalesId) {
        global $Global, $yesterdaySDate, $yesterdayEDate;
        $TransactionQuery = "SELECT s.eventid as EventId, s.id as Id, s.signupdate as SignupDt, s.quantity as Qty, (s.totalamount-s.eventextrachargeamount) as Fees, s.paymenttransactionid as PaymentTransId, e.title as Title,s.paymentstatus as eChecked  FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id INNER JOIN eventdetail AS ed ON s.eventid = ed.eventid WHERE s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate <= '" . $yesterdayEDate . "' AND (s.totalamount != 0 AND (s.paymenttransactionid != 'A1'))   AND ed.salespersonid=" . $SalesId . "  and s.paymentstatus!='Canceled' ORDER BY s.eventid, s.signupdate DESC";
        $TransactionRES = $Global->SelectQuery($TransactionQuery);
        return $TransactionRES;
    }

// function to get data for PayatCounter	
    function getSalesReportsForPayatCounter($SalesId) {
        global $Global, $yesterdaySDate, $yesterdayEDate;
        $PayatCounterQuery = "SELECT s.eventid as EventId, s.id as Id, s.signupdate as SignupDt, s.quantity as Qty, (s.totalamount-s.eventextrachargeamount) as Fees, s.paymenttransactionid as PaymentTransId, e.title as Title,s.delStatus FROM eventsignup AS s INNER JOIN event AS e ON s.EventId = e.Id WHERE s.SignupDt >= '" . $yesterdaySDate . "' AND s.SignupDt <= '" . $yesterdayEDate . "' AND (s.Fees != 0 AND (s.PromotionCode = 'Y' or s.PromotionCode ='PayatCounter' ))   AND e.SalesId=" . $SalesId . "  and s.eChecked!='Canceled' ORDER BY s.EventId, s.SignupDt DESC";
        $PayatCounterRES = $Global->SelectQuery($PayatCounterQuery);
        return $PayatCounterRES;
    }

// function to get data for COD (Cash on Delivery)
    function getSalesReportsForCOD($SalesId) {
        global $Global, $yesterdaySDate, $yesterdayEDate;
        $CODQuery = "SELECT s.eventid as EventId, s.id asId, s.signupdate as SignupDt, s.quantity as Qty, (s.totalamount-s.eventextrachargeamount) as Fees, s.paymenttransactionid as PaymentTransId, e.title as Title FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id INNER JOIN eventdetail AS ed ON s.eventid = ed.eventid WHERE s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate <= '" . $yesterdayEDate . "' AND (s.totalamount != 0 AND (s.paymentgatewayid = 3 ))   AND ed.salespersonid=" . $SalesId . "  and s.paymentstatus!='Canceled' ORDER BY s.eventid, s.signupdate DESC";
        $CODRES = $Global->SelectQuery($CODQuery);
        return $CODRES;
    }

// function to get data for Cheque Transations
    function getSalesReportsForChqTran($SalesId) {
        global $Global, $yesterdaySDate, $yesterdayEDate;
        $ChqTranQuery = "SELECT s.eventid asEventId, s.id asId, s.signupdate as SignupDt, s.quantity asQty, (s.totalamount-s.eventextrachargeamount) as Fees, s.paymenttransactionid as PaymentTransId, e.title as Title, cq.ChqNo, cq.ChqDt, cq.ChqBank, cq.Cleared, cq.Id AS chequeId FROM ChqPmnts AS cq, EventSignup AS s, events AS e WHERE s.Id = cq.EventSignupId AND s.PaymentModeId = 2 AND s.EventId = e.Id AND s.SignupDt >= '" . $yesterdaySDate . "' AND s.SignupDt <= '" . $yesterdayEDate . "' AND s.Fees != 0   AND e.SalesId=" . $SalesId . "  and s.eChecked!='Canceled' ORDER BY s.EventId, s.SignupDt DESC";
        $ChqTranRES = $Global->SelectQuery($ChqTranQuery);
        return $ChqTranRES;
    }

// function to get data for Cheque TotalUsers
    function getSalesReportsForTotalUsers($SalesId) {
        global $Global, $yesterdaySDate, $yesterdayEDate;
        $TotalUsers = "SELECT sum(s.quantity) as totusers  FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id INNER JOIN eventdetail AS ed ON s.eventid = ed.eventid WHERE s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate <= '" . $yesterdayEDate . "' AND (s.totalamount=0 or (s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2 or s.discount !='X')   AND ed.salespersonid=" . $SalesId . "  ORDER BY s.eventid, s.signupdate DESC";
        $TotalUsersRES = $Global->SelectQuery($TotalUsers);
        return $TotalUsersRES;
    }

// function to get data for Cheque TotalUsersFree
    function getSalesReportsForTotalUsersFree($SalesId) {
        global $Global, $yesterdaySDate, $yesterdayEDate;
        $TotalUsersFree = "SELECT sum(s.quantity) as totusersfree  FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id INNER JOIN eventdetail AS ed ON s.eventid = ed.eventid WHERE s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate <= '" . $yesterdayEDate . "' AND (e.registrationtype=1 or s.totalamount=0)   AND ed.salespersonid=" . $SalesId . "  ORDER BY s.eventid, s.signupdate DESC";

        $TotalUsersFreeRES = $Global->SelectQuery($TotalUsersFree);
        return $TotalUsersFreeRES;
    }

// function to get data for Cheque TotalUsersPaid
    function getSalesReportsForTotalUsersPaid($SalesId) {
        global $Global, $yesterdaySDate, $yesterdayEDate;
        $TotalUsersPaid = "SELECT sum(s.quantity) as totuserspaid  FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id INNER JOIN eventdetail AS ed ON s.eventid = ed.eventid WHERE s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate <= '" . $yesterdayEDate . "' AND ((s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2 or s.discount !='X') and s.totalamount!=0    AND ed.salespersonid=" . $SalesId . "  ORDER BY s.eventid, s.signupdate DESC";
        $TotalUsersPaidRES = $Global->SelectQuery($TotalUsersPaid);
        return $TotalUsersPaidRES;
    }

// function to get data for Cheque TotalEvents
    function getSalesReportsForTotalEvents($SalesId) {
        global $Global, $yesterdaySDate, $yesterdayEDate;
        $TotalEvents = "SELECT count(id) as eventcount FROM event as e INNER JOIN eventdetail AS ed ON e.id = ed.eventid where e.registrationdate between '" . $yesterdaySDate . "' and '" . $yesterdayEDate . "'   AND ed.salespersonid=" . $SalesId . " ";
        $TotalEventsRES = $Global->SelectQuery($TotalEvents);
        return $TotalEventsRES;
    }

// function to get data for Cheque TotalEventsFree
    function getSalesReportsForTotalEventsfree($SalesId) {
        global $Global, $yesterdaySDate, $yesterdayEDate;
        $TotalEventsfree = "SELECT count(id) as freecount FROM event as e INNER JOIN eventdetail AS ed ON e.id = ed.eventid where e.registrationdate between '" . $yesterdaySDate . "' and '" . $yesterdayEDate . "' and e.registrationtype=1 AND ed.salespersonid=" . $SalesId . " ";
        $TotalEventsfreeRES = $Global->SelectQuery($TotalEventsfree);
        return $TotalEventsfreeRES;
    }

// function to get data for Cheque TotalEventsPaid
    function getSalesReportsForTotalEventspaid($SalesId) {
        global $Global, $yesterdaySDate, $yesterdayEDate;
        $TotalEventspaid = "SELECT count(id) as paidcount FROM event as e INNER JOIN eventdetail AS ed ON e.id = ed.eventid where e.registrationdate between '" . $yesterdaySDate . "' and '" . $yesterdayEDate . "' and e.registrationtype=2 AND ed.salespersonid=" . $SalesId . " ";
        $TotalEventspaidRES = $Global->SelectQuery($TotalEventspaid);
        return $TotalEventspaidRES;
    }

// function to get data for Cheque TotalEventsnoreg
    function getSalesReportsForTotalEventsnoreg($SalesId) {
        global $Global, $yesterdaySDate, $yesterdayEDate;
        $TotalEventsnoreg = "SELECT count(id) as noregcount FROM event as e INNER JOIN eventdetail AS ed ON e.id = ed.eventid where e.registrationdate between '" . $yesterdaySDate . "' and '" . $yesterdayEDate . "' and e.registrationtype=3   AND ed.salespersonid=" . $SalesId . " ";
        $TotalEventsnoregRES = $Global->SelectQuery($TotalEventsnoreg);
        return $TotalEventsnoregRES;
    }

// function to get data for Cheque TotalunqEvents
    function getSalesReportsForTotalunqEvents($SalesId) {
        global $Global, $yesterdaySDate, $yesterdayEDate;
        $unqEvents = " select distinct(e.id) FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id INNER JOIN eventdetail AS ed ON e.id = ed.eventid WHERE s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate <= '" . $yesterdayEDate . "' AND ((s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2 or s.discount !='X') and s.totalamount!=0   AND ed.salespersonid=" . $SalesId . "  and s.paymentstatus!='Canceled'";
        $TotalunqEvents = $Global->SelectQuery($unqEvents);
        return $TotalunqEvents;
    }

// function to get data for Cheque TotalunqUserID
    function getSalesReportsForTotalunqUserID($SalesId) {
        global $Global, $yesterdaySDate, $yesterdayEDate;
        $unqUserID = " select distinct(e.ownerid) FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id INNER JOIN eventdetail AS ed ON s.eventid = ed.eventid WHERE s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate <= '" . $yesterdayEDate . "' AND ((s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2 or s.discount !='X') and s.totalamount!=0   AND ed.salespersonid=" . $SalesId . "  and s.paymentstatus!='Canceled'";
        $TotalunqUserID = $Global->SelectQuery($unqUserID);
        return $TotalunqUserID;
    }

// function to get data for Cheque TotalunqCityId
    function getSalesReportsForTotalunqCityId($SalesId) {
        global $Global, $yesterdaySDate, $yesterdayEDate;
        $unqCityId = " select distinct(e.cityid) FROM eventsignup AS s INNER JOIN event AS e ON s.EventId = e.Id INNER JOIN eventdetail AS ed ON s.eventid = ed.eventid WHERE s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate <= '" . $yesterdayEDate . "' AND ((s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2 or s.discount !='X') and s.totalamount!=0   AND ed.salespersonid=" . $SalesId . "  and s.paymentstatus!='Canceled'";
        $TotalunqCityId = $Global->SelectQuery($unqCityId);
        return $TotalunqCityId;
    }

    $TotalUser = "SELECT count(u.id) as ucount FROM `user` u WHERE 1 and u.signupdate between '" . $yesterdaySDate . "' and '" . $yesterdayEDate . "' ";
    $TotalURES = $Global->SelectQuery($TotalUser);

    $TotalOrg = "SELECT count(u.id) as orgcount FROM `user` u, organizer o WHERE u.id=o.userid and u.signupdate between '" . $yesterdaySDate . "' and '" . $yesterdayEDate . "' ";
    $TotalOrgRES = $Global->SelectQuery($TotalOrg);

    $totalSalesId = count($SalesQueryRES);
    for ($s = 0; $s < $totalSalesId; $s++) {
        if (strtolower($SalesQueryRES[$s]['Email']) == "srilakshmis@meraevents.com") {
            continue;
        }

        $salePersonId = $SalesQueryRES[$s]['SalesId'];

        $TransactionRES = getSalesReportsForTransaction($salePersonId);
//	$PayatCounterRES=getSalesReportsForPayatCounter($salePersonId);
//	$CODRES=getSalesReportsForCOD($salePersonId);
//	$ChqTranRES=getSalesReportsForChqTran($salePersonId);
        $TotalUsersRES = getSalesReportsForTotalUsers($salePersonId);
        $TotalUsersFreeRES = getSalesReportsForTotalUsersFree($salePersonId);
        $TotalUsersPaidRES = getSalesReportsForTotalUsersPaid($salePersonId);
        $TotalEventsRES = getSalesReportsForTotalEvents($salePersonId);
        $TotalEventsfreeRES = getSalesReportsForTotalEventsfree($salePersonId);
        $TotalEventspaidRES = getSalesReportsForTotalEventspaid($salePersonId); //echo 113331;exit;
        $TotalEventsnoregRES = getSalesReportsForTotalEventsnoreg($salePersonId);
        $TotalunqEvents = getSalesReportsForTotalunqEvents($salePersonId);
        $TotalunqUserID = getSalesReportsForTotalunqUserID($salePersonId);
        $TotalunqCityId = getSalesReportsForTotalunqCityId($salePersonId);



        $Msg = "<table width='90%' border='1' cellpadding='0' cellspacing='0' >
			<tr><td colspan='4'>" . $SalesQueryRES[$s]['SalesName'] . "</td></tr>
			<thead>
            <tr bgcolor='#94D2F3'>
		  	<td class='tblinner' valign='middle' width='4%' align='center'>Sr. No.</td>
			<td class='tblinner' valign='middle' width='27%' align='center'>Event Details</td>
            <td class='tblinner' valign='middle' width='5%' align='center'>Qty</td>
            <td class='tblinner' valign='middle' width='11%' align='center'>Amount (Rs.)</td>
          
          </tr>
        </thead>";

        $TotalAmountcard = 0;
        $TotalAmountPayatCounter = 0;
        $TotalAmountchk = 0;
        $Totalchk = 0;
        $Totalcard = 0;
        $TotalPayatCounter = 0;
        for ($i = 0; $i <= count($TransactionRES); $i++) {

            if ($i == 0) {
                $title = $TransactionRES[$i]['Title'];
                $feesaa = 0;
                $qtyaa = 0;
                $same = 1;
                $cnt = 0;
            }

            if ($title == $TransactionRES[$i]['Title'] && $i != 0) {
                $same = 1;
            } else {
                $same = 0;
                $Titlepre = $title;
                $title = $TransactionRES[$i]['Title'];
            }



            $feesaa += $TransactionRES[$i - 1]['Fees'];
            $qtyaa +=$TransactionRES[$i - 1]['Qty'];

            if ($same == 0 && $i != 0) {
                $Msg.="<tr>
			<td class='tblinner' valign='middle' width='4%' align='center' ><font color='#000000'>" . (( ++$cnt)) . "</font></td>
			<td class='tblinner' valign='middle' width='27%' align='left'><font color='#000000'>" . $Titlepre . "</font></td>
			 		
			<td class='tblinner' valign='middle' width='5%' align='right'><font color='#000000'>" . $qtyaa . "</font></td>
			<td class='tblinner' valign='middle' width='11%' align='right'><font color='#000000'>" . round($feesaa) . "</font></td>
            </tr>";
                $feesaa = 0;
                $qtyaa = 0;
            }
            $TotalAmountcard += $TransactionRES[$i]['Fees'] ;
            $Totalcard +=1; // $TransactionRES[$i]['Qty'];
            $cntTransactionRES++;
        }
        $Msg.="<tr><td colspan='2' style='line-height:30px;'><strong>Total Card Transactions Amount:</strong></td><td colspan='2' align='right'><font color='#000000'>Rs. " . round($TotalAmountcard) . "</font></td></tr>";
        for ($i = 0; $i <= count($PayatCounterRES); $i++) {
            if ($i == 0) {
                $title = $PayatCounterRES[$i]['Title'];
                $feesaa = 0;
                $qtyaa = 0;
                $same = 1;
                $cnt = 0;
            }

            if ($title == $PayatCounterRES[$i]['Title'] && $i != 0) {
                $same = 1;
            } else {
                $same = 0;
                $Titlepre = $title;
                $title = $PayatCounterRES[$i]['Title'];
            }



            $feesaa += $PayatCounterRES[$i - 1]['Fees'];
            $qtyaa +=$PayatCounterRES[$i - 1]['Qty'];

            if ($same == 0 && $i != 0) {
                $Msg.="<tr>
			<td class='tblinner' valign='middle' width='4%' align='center' ><font color='#000000'>" . (( ++$cnt)) . "</font></td>
			
			<td class='tblinner' valign='middle' width='27%' align='left'><font color='#000000'>" . $Titlepre . "</font></td>
			<td class='tblinner' valign='middle' width='5%' align='right'><font color='#000000'>" . $qtyaa . "</font></td>
			<td class='tblinner' valign='middle' width='11%' align='right'><font color='#000000'>" . round($feesaa) . "</font></td>
           
          </tr>";
                $feesaa = 0;
                $qtyaa = 0;
            }
            $TotalAmountPayatCounter += $PayatCounterRES[$i]['Fees'] ;
            $TotalPayatCounter +=1; // $TransactionRES[$i]['Qty'];
        }
        $Msg.="<tr><td colspan='2' style='line-height:30px;'><strong>Total Pay at Counter Amount:</strong></td><td colspan='2' align='right'><font color='#000000'>Rs. " . round($TotalAmountPayatCounter) . "</font></td></tr>";
        $TotalAmountCOD = 0;
        $TotalCOD = 0;

        for ($i = 0; $i <= count($CODRES); $i++) {
            if ($i == 0) {
                $title = $CODRES[$i]['Title'];
                $feesaa = 0;
                $qtyaa = 0;
                $same = 1;
                $cnt = 0;
            }

            if ($title == $CODRES[$i]['Title'] && $i != 0) {
                $same = 1;
            } else {
                $same = 0;
                $Titlepre = $title;
                $title = $CODRES[$i]['Title'];
            }



            $feesaa += $CODRES[$i - 1]['Fees'] ;
            $qtyaa +=$CODRES[$i - 1]['Qty'];

            if ($same == 0 && $i != 0) {
                $Msg.="<tr>
			<td class='tblinner' valign='middle' width='4%' align='center' ><font color='#000000'>" . ( ++$cnt) . "</font></td>
			<td class='tblinner' valign='middle' width='27%' align='left'><font color='#000000'>" . $Titlepre . "</font></td>
			<td class='tblinner' valign='middle' width='5%' align='right'><font color='#000000'>" . $qtyaa . "</font></td>
			<td class='tblinner' valign='middle' width='11%' align='right'><font color='#000000'>" . round($feesaa) . "</font></td>
           
          </tr>";
                $feesaa = 0;
                $qtyaa = 0;
            }
            $TotalAmountCOD += $CODRES[$i]['Fees'] ;
            $TotalCOD +=1; // $TransactionRES[$i]['Qty'];
        }
        $Msg.="<tr><td colspan='2' style='line-height:30px;'><strong>Total Cash on Delivery Amount:</strong></td><td colspan='2' align='right'><font color='#000000'>Rs. " . round($TotalAmountCOD) . "</font></td></tr>";
        for ($i = 0; $i <= count($ChqTranRES); $i++) {
            if ($i == 0) {
                $title = $ChqTranRES[$i]['Title'];
                $feesaa = 0;
                $qtyaa = 0;
                $same = 1;
                $cnt = 0;
            }

            if ($title == $ChqTranRES[$i]['Title'] && $i != 0) {
                $same = 1;
            } else {
                $same = 0;
                $Titlepre = $title;
                $title = $ChqTranRES[$i]['Title'];
            }



            $feesaa += $ChqTranRES[$i - 1]['Fees'] ;
            $qtyaa +=$ChqTranRES[$i - 1]['Qty'];

            if ($same == 0 && $i != 0) {
                $Msg.="<tr >
				<td class='tblinner' valign='top' width='4%' align='center'><font color='#000000'>" . ( ++$cnt) . "</font></td>
				<td class='tblinner' valign='top' width='27%' align='left'><font color='#000000'>" . $Titlepre . "</font></td>
				<td class='tblinner' valign='top' width='5%' align='right'><font color='#000000'>" . $qtyaa . "</font></td>
			<td class='tblinner' valign='top' width='11%' align='right'><font color='#000000'>" . round($feesaa) . "</font></td>
               
		  </tr>";
                $feesaa = 0;
                $qtyaa = 0;
            }
            $TotalAmountchk += $ChqTranRES[$i]['Fees'] ;
            $Totalchk +=1; // $ChqTranRES[$i]['Qty'];
        }
        $TotalAmountcard = round($TotalAmountcard);
        $TotalAmountchk = round($TotalAmountchk);
        $TotalAmountPayatCounter = round($TotalAmountPayatCounter);
        $TotalAmountCOD = round($TotalAmountCOD);

        $Msg.="<tr><td colspan='2' style='line-height:30px;'><strong>Total Cheque Transactions Amount:</strong></td><td colspan='2' align='right'><font color='#000000'>Rs. " . $TotalAmountchk . "</font></td></tr>
<tr><td colspan='2' style='line-height:30px;'><strong>Total :</strong></td><td colspan='2' align='right'><font color='#000000'>Rs. " . ($TotalAmountcard + $TotalAmountchk + $TotalAmountPayatCounter + $TotalAmountCOD) . "</font></td></tr>
<tr>
  <td colspan='2'>Total Card Transactions Amount :</td>
  <td colspan='2' align='right'><font color='#000000'> " . $TotalAmountcard . "</font></td></tr>
  <tr>
  <tr>
  <td colspan='2'>Total Cheque Transactions Amount :</td>
  <td colspan='2' align='right'><font color='#000000'>" . $TotalAmountchk . "</font></td></tr>
  <tr>
  <tr>
  <td colspan='2'>Total PayatCounter Amount :</td>
  <td colspan='2' align='right'><font color='#000000'>" . $TotalAmountPayatCounter . "</font></td></tr>
  <tr>
  <td colspan='2'>Total CashonDelivery Amount :</td>
  <td colspan='2' align='right'><font color='#000000'>" . $TotalAmountCOD . "</font></td></tr>
   <tr>
  <td colspan='2'>Total  Amount :</td>
  <td colspan='2' align='right'><font color='#000000'>" . ($TotalAmountcard + $TotalAmountchk + $TotalAmountPayatCounter + $TotalAmountCOD) . "</font></td></tr>
  <tr>
<tr>
  <td colspan='2'>Total Card Transactions :</td>
  <td colspan='2' align='right'><font color='#000000'>" . $Totalcard . "</font></td></tr>
  <tr>
  <td colspan='2'>Total Pay at Counter :</td>
  <td colspan='2' align='right'><font color='#000000'>" . $TotalPayatCounter . "</font></td></tr>
  <tr>
  <td colspan='2'>Total COD Transactions :</td>
  <td colspan='2' align='right'><font color='#000000'>" . $TotalCOD . "</font></td></tr>
<tr><td colspan='2'>Total Cheque Transaction :</td><td colspan='2' align='right'><font color='#000000'>" . $Totalchk . "</font></td></tr>
<tr>
  <td colspan='2'>Total Delegates Signed up for Tickets :</td><td colspan='2' align='right'><font color='#000000'>" . $TotalUsersRES[0]['totusers'] . "</font></td></tr>
<tr>
  <td colspan='2'>Delegates Signed up for Free Tickets :</td>
  <td colspan='2' align='right'><font color='#000000'>" . $TotalUsersFreeRES[0]['totusersfree'] . "</font></td></tr>
<tr>
  <td colspan='2'>Delegates Signed up for Paid Tickets :</td>
  <td colspan='2' align='right'><font color='#000000'>" . $TotalUsersPaidRES[0]['totuserspaid'] . "</font></td></tr>
  <tr><td colspan='2'>Total Users Signed up :</td><td colspan='2' align='right'><font color='#000000'>" . $TotalURES[0]['ucount'] . "</font></td></tr>

<tr><td colspan='2'>Total Organizers Registred :</td><td colspan='2' align='right'><font color='#000000'>" . $TotalOrgRES[0]['orgcount'] . "</font></td></tr>
<tr><td colspan='2'>Total Events Added :</td><td colspan='2' align='right'><font color='#000000'>" . $TotalEventsRES[0]['eventcount'] . "</font></td></tr>
<tr><td colspan='2'>Paid Events :</td><td colspan='2' align='right'><font color='#000000'>" . $TotalEventspaidRES[0]['paidcount'] . "</font></td></tr>
<tr><td colspan='2'>Free Events :</td><td colspan='2' align='right'><font color='#000000'>" . $TotalEventsfreeRES[0]['freecount'] . "</font></td></tr>
<tr><td colspan='2'>No Registration Events :</td><td colspan='2' align='right'><font color='#000000'>" . $TotalEventsnoregRES[0]['noregcount'] . "</font></td></tr>
<tr><td colspan='2'>No Unique Events :</td><td colspan='2' align='right'><font color='#000000'>" . count($TotalunqEvents) . "</font></td></tr>
<tr><td colspan='2'>No Unique Organizers :</td><td colspan='2' align='right'><font color='#000000'>" . count($TotalunqUserID) . "</font></td></tr>
<tr><td colspan='2'>No Unique Cities :</td><td colspan='2' align='right'><font color='#000000'>" . count($TotalunqCityId) . "</font></td></tr>

</table>";

        //echo $Msg."<br><br>";
        //SEND EMAIL
        //$to = 'team@meraevents.com,kumard@meraevents.com';
        $subject = '[MeraEvents] Sales Report Details of ' . ucfirst($SalesQueryRES[$s]['SalesName']) . ' From: ' . $yesterdaySDate . ' To: ' . $yesterdayEDate;
        $message = 'Dear ' . ucfirst($SalesQueryRES[$s]['SalesName']) . ',<br /><br />Below is your Sales Report Details From: ' . $yesterdaySDate . ' To: ' . $yesterdayEDate . '<br /><br />It contains only Successful, COD, PayatCounter and Cheque Transactions.<br /><br />' . $Msg . '<br /><br />Regards,<br>Meraevents Team';


        $cc = $content = $filename = $bcc = $replyto = NULL;
        $to = $SalesQueryRES[$s]['Email'];
        $cc = 'sreekanthp@meraevents.com,naidu@meraevents.com';
        $bcc = 'qison@meraevents.com';
        //$to = 'shashidhar.enjapuri@qison.com,durgesh.mishra@qison.com';
        //$cc='shashi.enjapuri@gmail.com,sudhera99@gmail.com';
        //$cc='shashidhar.enjapuri@qison.com,sudhera.bagineni@qison.com';
        $from = 'MeraEvents<admin@meraevents.com>';
        $commonFunctions->sendEmail($to, $cc, $bcc, $from, $replyto, $subject, $message, $content, $filename);

        //mail($to, $subject, $message, $headers);
        //mail('durgeshmishra2525@gmail.com', $subject, $message, $headers);
        //mail('kumard@meraevents.com', $subject, $message, $headers);
    }


    //echo $Msg;
    //EMAIL SENT
    mysql_close();
}
?>
