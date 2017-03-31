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
    //$yesterdaySDate = date ("Y-m-d", mktime (0,0,0,date("m"),(date("d")-1),date("Y"))).' 00:00:01';
    //$yesterdayEDate = date ("Y-m-d", mktime (0,0,0,date("m"),(date("d")-1),date("Y"))).' 23:59:59';
    //$stdt=" AND s.SignupDt between '".$yesterdaySDate."' and '".$yesterdayEDate."'";

    $sqlCities = "SELECT DISTINCT c.name as City,c.id as Id FROM city AS c ORDER BY c.name ASC";
    $dtlCities = $Global->SelectQuery($sqlCities);



    $SalesQuery = "SELECT id as SalesId,name as SalesName from  salesperson   ORDER BY name";
    $SalesQueryRES = $Global->SelectQuery($SalesQuery);

    $SDt = date("d/m/Y", mktime(0, 0, 0, date("m"), (date("d") - 1), date("Y")));
    $EDt = date("d/m/Y", mktime(0, 0, 0, date("m"), (date("d") - 1), date("Y")));
    $yesterdaySDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 1), date("Y"))) . ' 00:00:01';
    $yesterdaySDate = $commonFunctions->convertTime($yesterdaySDate, DEFAULT_TIMEZONE);
    $yesterdayEDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 1), date("Y"))) . ' 23:59:59';
    $yesterdayEDate = $commonFunctions->convertTime($yesterdayEDate, DEFAULT_TIMEZONE);



    $TotalAmount = 0;
    $cntTransactionRES = 1;





    //Display list of Successful Transactions
    $TransactionQuery = "SELECT s.eventid as EventId, s.id as Id, s.signupdate as SignupDt, s.quantity as Qty, (s.totalamount/s.quantity) as Fees, s.paymenttransactionid as PaymentTransId, e.title as Title,s.paymentstatus as eChecked,e.stateid as StateId,e.cityid as CityId  FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate <= '" . $yesterdayEDate . "' AND (s.totalamount != 0 AND (s.paymenttransactionid != 'A1'))    and s.paymentstatus!='Canceled' ORDER BY s.eventid, s.signupdate DESC";
    $TransactionRES = $Global->SelectQuery($TransactionQuery);

    $PayatCounterQuery = "SELECT s.eventid as EventId, s.id as Id, s.signupdate as SignupDt, s.quantity as Qty, (s.totalamount/s.quantity) as Fees, s.paymenttransactionid as PaymentTransId, e.title as Title,e.stateid as StateId,e.cityid as CityId FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate <= '" . $yesterdayEDate . "' AND (s.totalamount != 0 AND (s.discount = 'Y' or s.discount ='PayatCounter' ))     and s.paymentstatus!='Canceled' ORDER BY s.eventid, s.signupdate DESC";
    $PayatCounterRES = $Global->SelectQuery($PayatCounterQuery);

    $CODQuery = "SELECT s.eventid as EventId, s.id as Id, s.signupdate as SignupDt, s.quantity as Qty, (s.totalamount/s.quantity) as Fees, s.paymenttransactionid as PaymentTransId, e.title as Title,e.stateid as StateId,e.cityid as CityId FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate <= '" . $yesterdayEDate . "' AND (s.totalamount != 0 AND (s.paymentgatewayid = 2 ))     and s.paymentstatus!='Canceled' ORDER BY s.eventid, s.signupdate DESC";
    $CODRES = $Global->SelectQuery($CODQuery);


//       $ChqTranQuery = "SELECT s.eventid as EventId, s.id as Id, s.signupdate as SignupDt, s.quantity as Qty, (s.totalamount/s.quantity) as Fees, s.paymenttransactionid as PaymentTransId, e.title as Title,e.stateid as StateId,e.cityid as CityId, cq.ChqNo, cq.ChqDt, cq.ChqBank, cq.Cleared, cq.Id AS chequeId FROM ChqPmnts AS cq, EventSignup AS s, events AS e WHERE s.Id = cq.EventSignupId AND s.PaymentModeId = 2 AND s.EventId = e.Id AND s.SignupDt >= '".$yesterdaySDate."' AND s.SignupDt <= '".$yesterdayEDate."' AND s.Fees != 0     and s.eChecked!='Canceled' ORDER BY s.EventId, s.SignupDt DESC"; 
//	$ChqTranRES = $Global->SelectQuery($ChqTranQuery);
    $ChqTranRES = "";

    $TotalUsers = "SELECT e.stateid as StateId,e.cityid as CityId  FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE s.signupdate  >= '" . $yesterdaySDate . "' AND s.signupdate  <= '" . $yesterdayEDate . "' AND (s.totalamount=0 or (s.paymentgatewayid=1 and s.paymenttransactionid != 'A1') or s.paymentgatewayid=2 or s.discount !='X')   ORDER BY s.eventid, s.signupdate DESC";
    $TotalUsersRES = $Global->SelectQuery($TotalUsers);

    $TotalUsersFree = "SELECT e.stateid as StateId,e.cityid as CityId  FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE s.signupdate >= '" . $yesterdaySDate . "' AND s.signupdate  <= '" . $yesterdayEDate . "' AND (registrationtype=1 or s.totalamount=0)   ORDER BY s.eventid, s.signupdate DESC";

    $TotalUsersFreeRES = $Global->SelectQuery($TotalUsersFree);

    $TotalUsersPaid = "SELECT e.stateid as StateId,e.cityid as CityId  FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE s.signupdate  >= '" . $yesterdaySDate . "' AND s.signupdate  <= '" . $yesterdayEDate . "' AND ((s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2 or s.discount !='X') and s.totalamount!=0    ORDER BY s.eventid, s.signupdate DESC";
    $TotalUsersPaidRES = $Global->SelectQuery($TotalUsersPaid);


    $TotalUser = "SELECT u.cityid as CityId FROM `user` u WHERE 1 and u.signupdate between '" . $yesterdaySDate . "' and '" . $yesterdayEDate . "'";
    $TotalURES = $Global->SelectQuery($TotalUser);

    $STotalOrg = "SELECT u.cityid as CityId FROM `user` u, organizer o WHERE u.id=o.userid and u.signupdate between '" . $yesterdaySDate . "' and '" . $yesterdayEDate . "' ";
    $TotalOrgRES = $Global->SelectQuery($STotalOrg);

    $STotalEvents = "SELECT e.stateid as StateId,e.cityid as CityId FROM event as e where e.registrationdate between '" . $yesterdaySDate . "' and '" . $yesterdayEDate . "'  ";
    $TotalEventsRES = $Global->SelectQuery($STotalEvents);


    $TotalEventsfree = "SELECT e.stateid as StateId,e.cityid as CityId FROM event as e where e.registrationdate between '" . $yesterdaySDate . "' and '" . $yesterdayEDate . "' and e.registrationtype=1  ";
    $TotalEventsfreeRES = $Global->SelectQuery($TotalEventsfree);

    $TotalEventspaid = "SELECT e.stateid as StateId,e.cityid as CityId FROM event as e where e.registrationdate between '" . $yesterdaySDate . "' and '" . $yesterdayEDate . "' and e.registrationtype=2  ";
    $TotalEventspaidRES = $Global->SelectQuery($TotalEventspaid);

    $TotalEventsnoreg = "SELECT e.stateid as StateId,e.cityid as CityId  FROM event as e where e.registrationdate between '" . $yesterdaySDate . "' and '" . $yesterdayEDate . "' and e.registrationtype=3  ";
    $TotalEventsnoregRES = $Global->SelectQuery($TotalEventsnoreg);


    $unqEvents = " select distinct(e.id),e.stateid as StateId,e.cityid as CityId FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE s.signupdate  >= '" . $yesterdaySDate . "' AND s.signupdate  <= '" . $yesterdayEDate . "' AND ((s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2 or s.discount !='X') and s.totalamount!=0      and s.paymentstatus!='Canceled'";
    $TotalunqEvents = $Global->SelectQuery($unqEvents);

    $unqUserID = " select distinct(e.ownerid )as UserID,e.stateid as StateId,e.cityid as CityId FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE s.signupdate  >= '" . $yesterdaySDate . "' AND s.signupdate  <= '" . $yesterdayEDate . "' AND ((s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2 or s.discount !='X') and s.totalamount!=0      and s.paymentstatus!='Canceled'";
    $TotalunqUserID = $Global->SelectQuery($unqUserID);


    /* $unqCityId=" select distinct(e.CityId) FROM EventSignup AS s INNER JOIN events AS e ON s.EventId = e.Id WHERE s.SignupDt >= '".$yesterdaySDate."' AND s.SignupDt <= '".$yesterdayEDate."' AND ((s.PaymentModeId=1 and s.PaymentTransId != 'A1') or s.PaymentModeId=2 or s.PromotionCode !='X') and s.Fees!=0     and s.eChecked!='Canceled'";	
      $TotalunqCityId = $Global->SelectQuery($unqCityId); */

    /* Processing Start herer */


    $TotalAmountcard = 0;
    $HydTotalAmountcard = 0;
    $MumTotalAmountcard = 0;
    $PuneTotalAmountcard = 0;
    $BangTotalAmountcard = 0;
    $ChenTotalAmountcard = 0;
    $DelTotalAmountcard = 0;
    $OthTotalAmountcard = 0;
    $TotalAmountPayatCounter = 0;
    $HydTotalAmountPayatCounter = 0;
    $MumTotalAmountPayatCounter = 0;
    $PuneTotalAmountPayatCounter = 0;
    $BangTotalAmountPayatCounter = 0;
    $ChenTotalAmountPayatCounter = 0;
    $DelTotalAmountPayatCounter = 0;
    $OthTotalAmountPayatCounter = 0;
    $TotalAmountchk = 0;
    $HydTotalAmountchk = 0;
    $MumTotalAmountchk = 0;
    $PuneTotalAmountchk = 0;
    $BangTotalAmountchk = 0;
    $ChenTotalAmountchk = 0;
    $DelTotalAmountchk = 0;
    $OthTotalAmountchk = 0;
    $TotalAmountCOD = 0;
    $HydTotalAmountCOD = 0;
    $MumTotalAmountCOD = 0;
    $PuneTotalAmountCOD = 0;
    $BangTotalAmountCOD = 0;
    $ChenTotalAmountCOD = 0;
    $DelTotalAmountCOD = 0;
    $OthTotalAmountCOD = 0;

    $Totalcard = 0;
    $HydTotalcard = 0;
    $MumTotalcard = 0;
    $PuneTotalcard = 0;
    $BangTotalcard = 0;
    $ChenTotalcard = 0;
    $DelTotalcard = 0;
    $OthTotalcard = 0;
    $Totalchk = 0;
    $HydTotalchk = 0;
    $MumTotalchk = 0;
    $PuneTotalchk = 0;
    $BangTotalchk = 0;
    $ChenTotalchk = 0;
    $DelTotalchk = 0;
    $OthTotalchk = 0;
    $TotalPayatCounter = 0;
    $HydTotalPayatCounter = 0;
    $MumTotalPayatCounter = 0;
    $PuneTotalPayatCounter = 0;
    $BangTotalPayatCounter = 0;
    $ChenTotalPayatCounter = 0;
    $DelTotalPayatCounter = 0;
    $OthTotalPayatCounter = 0;
    $TotalCOD = 0;
    $HydTotalCOD = 0;
    $MumTotalCOD = 0;
    $PuneTotalCOD = 0;
    $BangTotalCOD = 0;
    $ChenTotalCOD = 0;
    $DelTotalCOD = 0;
    $OthTotalCOD = 0;
    for ($i = 0; $i < count($TransactionRES); $i++) {
        if ($TransactionRES[$i]['CityId'] == 47 || $TransactionRES[$i]['CityId'] == 448) {
            $HydTotalAmountcard +=$TransactionRES[$i]['Fees'] * $TransactionRES[$i]['Qty'];
            $HydTotalcard +=1;
        }

        if ($TransactionRES[$i]['CityId'] == 14 || $TransactionRES[$i]['CityId'] == 393) {
            $MumTotalAmountcard +=$TransactionRES[$i]['Fees'] * $TransactionRES[$i]['Qty'];
            $MumTotalcard +=1;
        }

        if ($TransactionRES[$i]['CityId'] == 77) {
            $PuneTotalAmountcard +=$TransactionRES[$i]['Fees'] * $TransactionRES[$i]['Qty'];
            $PuneTotalcard +=1;
        }

        if ($TransactionRES[$i]['CityId'] == 37) {
            $BangTotalAmountcard += $TransactionRES[$i]['Fees'] * $TransactionRES[$i]['Qty'];
            $BangTotalcard +=1;
        }

        if ($TransactionRES[$i]['CityId'] == 39) {
            $ChenTotalAmountcard +=$TransactionRES[$i]['Fees'] * $TransactionRES[$i]['Qty'];
            $ChenTotalcard +=1;
        }

        if ($TransactionRES[$i]['StateId'] == 53 || $TransactionRES[$i]['CityId'] == 330 || $TransactionRES[$i]['CityId'] == 331 || $TransactionRES[$i]['CityId'] == 383 || $TransactionRES[$i]['CityId'] == 408) {
            $DelTotalAmountcard +=$TransactionRES[$i]['Fees'] * $TransactionRES[$i]['Qty'];
            $DelTotalcard +=1;
        }

        if ($TransactionRES[$i]['CityId'] != 47 && $TransactionRES[$i]['CityId'] != 448 && $TransactionRES[$i]['CityId'] != 14 && $TransactionRES[$i]['CityId'] != 393 && $TransactionRES[$i]['CityId'] != 77 && $TransactionRES[$i]['CityId'] != 37 && $TransactionRES[$i]['CityId'] != 39 && $TransactionRES[$i]['StateId'] != 53 && $TransactionRES[$i]['CityId'] != 330 && $TransactionRES[$i]['CityId'] != 331 && $TransactionRES[$i]['CityId'] != 383 && $TransactionRES[$i]['CityId'] != 408) {
            $OthTotalAmountcard +=$TransactionRES[$i]['Fees'] * $TransactionRES[$i]['Qty'];
            $OthTotalcard +=1;
        }


        $TotalAmountcard += $TransactionRES[$i]['Fees'] * $TransactionRES[$i]['Qty'];
        $Totalcard +=1; // $TransactionRES[$i]['Qty'];
        $cntTransactionRES++;
    }

    for ($i = 0; $i < count($PayatCounterRES); $i++) {
        if ($PayatCounterRES[$i]['CityId'] == 47 || $PayatCounterRES[$i]['CityId'] == 448) {
            $HydTotalAmountPayatCounter +=$PayatCounterRES[$i]['Fees'] * $PayatCounterRES[$i]['Qty'];
            $HydTotalPayatCounter+=1;
        }

        if ($PayatCounterRES[$i]['CityId'] == 14 || $PayatCounterRES[$i]['CityId'] == 393) {
            $MumTotalAmountPayatCounter +=$PayatCounterRES[$i]['Fees'] * $PayatCounterRES[$i]['Qty'];
            $MumTotalPayatCounter+=1;
        }

        if ($PayatCounterRES[$i]['CityId'] == 77) {
            $PuneTotalAmountPayatCounter +=$PayatCounterRES[$i]['Fees'] * $PayatCounterRES[$i]['Qty'];
            $PuneTotalPayatCounter+=1;
        }

        if ($PayatCounterRES[$i]['CityId'] == 37) {
            $BangTotalAmountPayatCounter +=$PayatCounterRES[$i]['Fees'] * $PayatCounterRES[$i]['Qty'];
            $BangTotalPayatCounter+=1;
        }

        if ($PayatCounterRES[$i]['CityId'] == 39) {
            $ChenTotalAmountPayatCounter +=$PayatCounterRES[$i]['Fees'] * $PayatCounterRES[$i]['Qty'];
            $ChenTotalPayatCounter+=1;
        }


        if ($PayatCounterRES[$i]['StateId'] == 53 || $PayatCounterRES[$i]['CityId'] == 330 || $PayatCounterRES[$i]['CityId'] == 331 || $PayatCounterRES[$i]['CityId'] == 383 || $PayatCounterRES[$i]['CityId'] == 408) {
            $DelTotalAmountPayatCounter +=$PayatCounterRES[$i]['Fees'] * $PayatCounterRES[$i]['Qty'];
            $DelTotalPayatCounter+=1;
        }

        if ($PayatCounterRES[$i]['CityId'] != 47 && $PayatCounterRES[$i]['CityId'] != 448 && $PayatCounterRES[$i]['CityId'] != 14 && $PayatCounterRES[$i]['CityId'] != 393 && $PayatCounterRES[$i]['CityId'] != 77 && $PayatCounterRES[$i]['CityId'] != 37 && $PayatCounterRES[$i]['CityId'] != 39 && $PayatCounterRES[$i]['StateId'] != 53 && $PayatCounterRES[$i]['CityId'] != 330 && $PayatCounterRES[$i]['CityId'] != 331 && $PayatCounterRES[$i]['CityId'] != 383 && $PayatCounterRES[$i]['CityId'] != 408) {
            $OthTotalAmountPayatCounter +=$PayatCounterRES[$i]['Fees'] * $PayatCounterRES[$i]['Qty'];
            $OthTotalPayatCounter+=1;
        }

        $TotalAmountPayatCounter += $PayatCounterRES[$i]['Fees'] * $PayatCounterRES[$i]['Qty'];
        $TotalPayatCounter +=1; // $TransactionRES[$i]['Qty'];
    }
    for ($i = 0; $i < count($CODRES); $i++) {
        if ($CODRES[$i]['CityId'] == 47 || $CODRES[$i]['CityId'] == 448) {
            $HydTotalAmountCOD +=$CODRES[$i]['Fees'] * $CODRES[$i]['Qty'];
            $HydTotalCOD +=1;
        }

        if ($CODRES[$i]['CityId'] == 14 || $CODRES[$i]['CityId'] == 393) {
            $MumTotalAmountCOD +=$CODRES[$i]['Fees'] * $CODRES[$i]['Qty'];
            $MumTotalCOD +=1;
        }

        if ($CODRES[$i]['CityId'] == 77) {
            $PuneTotalAmountCOD +=$CODRES[$i]['Fees'] * $CODRES[$i]['Qty'];
            $PuneTotalCOD +=1;
        }

        if ($CODRES[$i]['CityId'] == 37) {
            $BangTotalAmountCOD +=$CODRES[$i]['Fees'] * $CODRES[$i]['Qty'];
            $BangTotalCOD +=1;
        }

        if ($CODRES[$i]['CityId'] == 39) {
            $ChenTotalAmountCOD +=$CODRES[$i]['Fees'] * $CODRES[$i]['Qty'];
            $ChenTotalCOD +=1;
        }


        if ($CODRES[$i]['StateId'] == 53 || $CODRES[$i]['CityId'] == 330 || $CODRES[$i]['CityId'] == 331 || $CODRES[$i]['CityId'] == 383 || $CODRES[$i]['CityId'] == 408) {
            $DelTotalAmountCOD +=$CODRES[$i]['Fees'] * $CODRES[$i]['Qty'];
            $DelTotalCOD +=1;
        }

        if ($CODRES[$i]['CityId'] != 47 && $CODRES[$i]['CityId'] != 448 && $CODRES[$i]['CityId'] != 14 && $CODRES[$i]['CityId'] != 393 && $CODRES[$i]['CityId'] != 77 && $CODRES[$i]['CityId'] != 37 && $CODRES[$i]['CityId'] != 39 && $CODRES[$i]['StateId'] != 53 && $CODRES[$i]['CityId'] != 330 && $CODRES[$i]['CityId'] != 331 && $CODRES[$i]['CityId'] != 383 && $CODRES[$i]['CityId'] != 408) {
            $OthTotalAmountCOD +=$CODRES[$i]['Fees'] * $CODRES[$i]['Qty'];
            $OthTotalCOD +=1;
        }

        $TotalAmountCOD += $CODRES[$i]['Fees'] * $CODRES[$i]['Qty'];
        $TotalCOD +=1; // $TransactionRES[$i]['Qty'];
    }
    for ($i = 0; $i < count($ChqTranRES); $i++) {


        if ($ChqTranRES[$i]['CityId'] == 47 || $ChqTranRES[$i]['CityId'] == 448) {
            $HydTotalAmountchk +=$ChqTranRES[$i]['Fees'] * $ChqTranRES[$i]['Qty'];
            $HydTotalchk +=1;
        }

        if ($ChqTranRES[$i]['CityId'] == 14 || $ChqTranRES[$i]['CityId'] == 393) {
            $MumTotalAmountchk +=$ChqTranRES[$i]['Fees'] * $ChqTranRES[$i]['Qty'];
            $MumTotalchk +=1;
        }

        if ($ChqTranRES[$i]['CityId'] == 77) {
            $PuneTotalAmountchk +=$ChqTranRES[$i]['Fees'] * $ChqTranRES[$i]['Qty'];
            $PuneTotalchk +=1;
        }

        if ($ChqTranRES[$i]['CityId'] == 37) {
            $BangTotalAmountchk +=$ChqTranRES[$i]['Fees'] * $ChqTranRES[$i]['Qty'];
            $BangTotalchk +=1;
        }

        if ($ChqTranRES[$i]['CityId'] == 39) {
            $ChenTotalAmountchk +=$ChqTranRES[$i]['Fees'] * $ChqTranRES[$i]['Qty'];
            $ChenTotalchk +=1;
        }


        if ($ChqTranRES[$i]['StateId'] == 53 || $ChqTranRES[$i]['CityId'] == 330 || $ChqTranRES[$i]['CityId'] == 331 || $ChqTranRES[$i]['CityId'] == 383 || $ChqTranRES[$i]['CityId'] == 408) {
            $DelTotalAmountchk +=$ChqTranRES[$i]['Fees'] * $ChqTranRES[$i]['Qty'];
            $DelTotalchk +=1;
        }

        if ($ChqTranRES[$i]['CityId'] != 47 && $ChqTranRES[$i]['CityId'] != 448 && $ChqTranRES[$i]['CityId'] != 14 && $ChqTranRES[$i]['CityId'] != 393 && $ChqTranRES[$i]['CityId'] != 77 && $ChqTranRES[$i]['CityId'] != 37 && $ChqTranRES[$i]['CityId'] != 39 && $ChqTranRES[$i]['StateId'] != 53 && $ChqTranRES[$i]['CityId'] != 330 && $ChqTranRES[$i]['CityId'] != 331 && $ChqTranRES[$i]['CityId'] != 383 && $ChqTranRES[$i]['CityId'] != 408) {
            $OthTotalAmountchk +=$ChqTranRES[$i]['Fees'] * $ChqTranRES[$i]['Qty'];
            $OthTotalchk +=1;
        }

        $TotalAmountchk += $ChqTranRES[$i]['Fees'] * $ChqTranRES[$i]['Qty'];
        $Totalchk +=1; // $ChqTranRES[$i]['Qty'];
    }

    $TotalAmountcard = round($TotalAmountcard);
    $HydTotalAmountcard = round($HydTotalAmountcard);
    $MumTotalAmountcard = round($MumTotalAmountcard);
    $PuneTotalAmountcard = round($PuneTotalAmountcard);
    $BangTotalAmountcard = round($BangTotalAmountcard);
    $ChenTotalAmountcard = round($ChenTotalAmountcard);
    $DelTotalAmountcard = round($DelTotalAmountcard);
    $OthTotalAmountcard = round($OthTotalAmountcard);
    $TotalAmountPayatCounter = round($TotalAmountPayatCounter);
    $HydTotalAmountPayatCounter = round($HydTotalAmountPayatCounter);
    $MumTotalAmountPayatCounter = round($MumTotalAmountPayatCounter);
    $PuneTotalAmountPayatCounter = round($PuneTotalAmountPayatCounter);
    $BangTotalAmountPayatCounter = round($BangTotalAmountPayatCounter);
    $ChenTotalAmountPayatCounter = round($ChenTotalAmountPayatCounter);
    $DelTotalAmountPayatCounter = round($DelTotalAmountPayatCounter);
    $OthTotalAmountPayatCounter = round($OthTotalAmountPayatCounter);
    $TotalAmountchk = round($TotalAmountchk);
    $HydTotalAmountchk = round($HydTotalAmountchk);
    $MumTotalAmountchk = round($MumTotalAmountchk);
    $PuneTotalAmountchk = round($PuneTotalAmountchk);
    $BangTotalAmountchk = round($BangTotalAmountchk);
    $ChenTotalAmountchk = round($ChenTotalAmountchk);
    $DelTotalAmountchk = round($DelTotalAmountchk);
    $OthTotalAmountchk = round($OthTotalAmountchk);
    $TotalAmountCOD = round($TotalAmountCOD);
    $HydTotalAmountCOD = round($HydTotalAmountCOD);
    $MumTotalAmountCOD = round($MumTotalAmountCOD);
    $PuneTotalAmountCOD = round($PuneTotalAmountCOD);
    $BangTotalAmountCOD = round($BangTotalAmountCOD);
    $ChenTotalAmountCOD = round($ChenTotalAmountCOD);
    $DelTotalAmountCOD = round($DelTotalAmountCOD);
    $OthTotalAmountCOD = round($OthTotalAmountCOD);

    for ($i = 0; $i < count($TotalUsersRES); $i++) {

        if ($TotalUsersRES[$i]['CityId'] == 47 || $TotalUsersRES[$i]['CityId'] == 448) {

            $HydTotalSigned +=1;
        }

        if ($TotalUsersRES[$i]['CityId'] == 14 || $TotalUsersRES[$i]['CityId'] == 393) {

            $MumTotalSigned +=1;
        }

        if ($TotalUsersRES[$i]['CityId'] == 77) {

            $PuneTotalSigned +=1;
        }

        if ($TotalUsersRES[$i]['CityId'] == 37) {
            $BangTotalSigned +=1;
        }

        if ($TotalUsersRES[$i]['CityId'] == 39) {
            $ChenTotalSigned +=1;
        }

        if ($TotalUsersRES[$i]['StateId'] == 53 || $TotalUsersRES[$i]['CityId'] == 330 || $TotalUsersRES[$i]['CityId'] == 331 || $TotalUsersRES[$i]['CityId'] == 383 || $TotalUsersRES[$i]['CityId'] == 408) {

            $DelTotalSigned +=1;
        }

        if ($TotalUsersRES[$i]['CityId'] != 47 && $TotalUsersRES[$i]['CityId'] != 448 && $TotalUsersRES[$i]['CityId'] != 14 && $TotalUsersRES[$i]['CityId'] != 393 && $TotalUsersRES[$i]['CityId'] != 77 && $TotalUsersRES[$i]['CityId'] != 37 && $TotalUsersRES[$i]['CityId'] != 39 && $TotalUsersRES[$i]['StateId'] != 53 && $TotalUsersRES[$i]['CityId'] != 330 && $TotalUsersRES[$i]['CityId'] != 331 && $TotalUsersRES[$i]['CityId'] != 383 && $TotalUsersRES[$i]['CityId'] != 408) {

            $OthTotalSigned +=1;
        }


        $TotalSigned +=1;
    }

    for ($i = 0; $i < count($TotalUsersFreeRES); $i++) {
        if ($TotalUsersFreeRES[$i]['CityId'] == 47 || $TotalUsersFreeRES[$i]['CityId'] == 448) {

            $HydTotalFreeSigned +=1;
        }

        if ($TotalUsersFreeRES[$i]['CityId'] == 14 || $TotalUsersFreeRES[$i]['CityId'] == 393) {

            $MumTotalFreeSigned +=1;
        }

        if ($TotalUsersFreeRES[$i]['CityId'] == 77) {

            $PuneTotalFreeSigned +=1;
        }

        if ($TotalUsersFreeRES[$i]['CityId'] == 37) {
            $BangTotalFreeSigned +=1;
        }

        if ($TotalUsersFreeRES[$i]['CityId'] == 39) {
            $ChenTotalFreeSigned +=1;
        }

        if ($TotalUsersFreeRES[$i]['StateId'] == 53 || $TotalUsersFreeRES[$i]['CityId'] == 330 || $TotalUsersFreeRES[$i]['CityId'] == 331 || $TotalUsersFreeRES[$i]['CityId'] == 383 || $TotalUsersFreeRES[$i]['CityId'] == 408) {

            $DelTotalFreeSigned +=1;
        }

        if ($TotalUsersFreeRES[$i]['CityId'] != 47 && $TotalUsersFreeRES[$i]['CityId'] != 448 && $TotalUsersFreeRES[$i]['CityId'] != 14 && $TotalUsersFreeRES[$i]['CityId'] != 393 && $TotalUsersFreeRES[$i]['CityId'] != 77 && $TotalUsersFreeRES[$i]['CityId'] != 37 && $TotalUsersFreeRES[$i]['CityId'] != 39 && $TotalUsersFreeRES[$i]['StateId'] != 53 && $TotalUsersFreeRES[$i]['CityId'] != 330 && $TotalUsersFreeRES[$i]['CityId'] != 331 && $TotalUsersFreeRES[$i]['CityId'] != 383 && $TotalUsersFreeRES[$i]['CityId'] != 408) {

            $OthTotalFreeSigned +=1;
        }


        $TotalFreeSigned +=1;
    }

    for ($i = 0; $i < count($TotalUsersPaidRES); $i++) {
        if ($TotalUsersPaidRES[$i]['CityId'] == 47 || $TotalUsersPaidRES[$i]['CityId'] == 448) {

            $HydTotalPaidSigned +=1;
        }

        if ($TotalUsersPaidRES[$i]['CityId'] == 14 || $TotalUsersPaidRES[$i]['CityId'] == 393) {

            $MumTotalPaidSigned +=1;
        }

        if ($TotalUsersPaidRES[$i]['CityId'] == 77) {

            $PuneTotalPaidSigned +=1;
        }

        if ($TotalUsersPaidRES[$i]['CityId'] == 37) {
            $BangTotalPaidSigned +=1;
        }

        if ($TotalUsersPaidRES[$i]['CityId'] == 39) {
            $ChenTotalPaidSigned +=1;
        }

        if ($TotalUsersPaidRES[$i]['StateId'] == 53 || $TotalUsersPaidRES[$i]['CityId'] == 330 || $TotalUsersPaidRES[$i]['CityId'] == 331 || $TotalUsersPaidRES[$i]['CityId'] == 383 || $TotalUsersPaidRES[$i]['CityId'] == 408) {

            $DelTotalPaidSigned +=1;
        }

        if ($TotalUsersPaidRES[$i]['CityId'] != 47 && $TotalUsersPaidRES[$i]['CityId'] != 448 && $TotalUsersPaidRES[$i]['CityId'] != 14 && $TotalUsersPaidRES[$i]['CityId'] != 393 && $TotalUsersPaidRES[$i]['CityId'] != 77 && $TotalUsersPaidRES[$i]['CityId'] != 37 && $TotalUsersPaidRES[$i]['CityId'] != 39 && $TotalUsersPaidRES[$i]['StateId'] != 53 && $TotalUsersPaidRES[$i]['CityId'] != 330 && $TotalUsersPaidRES[$i]['CityId'] != 331 && $TotalUsersPaidRES[$i]['CityId'] != 383 && $TotalUsersPaidRES[$i]['CityId'] != 408) {

            $OthTotalPaidSigned +=1;
        }


        $TotalPaidSigned +=1;
    }
    for ($i = 0; $i < count($TotalURES); $i++) {
        if ($TotalURES[$i]['CityId'] == 47 || $TotalURES[$i]['CityId'] == 448) {

            $HydTotalUsr +=1;
        }

        if ($TotalURES[$i]['CityId'] == 14 || $TotalURES[$i]['CityId'] == 393) {

            $MumTotalUsr +=1;
        }

        if ($TotalURES[$i]['CityId'] == 77) {

            $PuneTotalUsr +=1;
        }

        if ($TotalURES[$i]['CityId'] == 37) {
            $BangTotalUsr +=1;
        }

        if ($TotalURES[$i]['CityId'] == 39) {
            $ChenTotalUsr +=1;
        }

        if ($TotalURES[$i]['StateId'] == 53 || $TotalURES[$i]['CityId'] == 330 && $TotalURES[$i]['CityId'] == 331 && $TotalURES[$i]['CityId'] == 383 && $TotalURES[$i]['CityId'] == 408) {

            $DelTotalUsr +=1;
        }

        if ($TotalURES[$i]['CityId'] != 47 && $TotalURES[$i]['CityId'] != 448 && $TotalURES[$i]['CityId'] != 14 && $TotalURES[$i]['CityId'] != 393 && $TotalURES[$i]['CityId'] != 77 && $TotalURES[$i]['CityId'] != 37 && $TotalURES[$i]['CityId'] != 39 && $TotalURES[$i]['StateId'] != 53 && $TotalURES[$i]['CityId'] != 330 && $TotalURES[$i]['CityId'] != 331 && $TotalURES[$i]['CityId'] != 383 && $TotalURES[$i]['CityId'] != 408) {

            $OthTotalUsr +=1;
        }


        $TotalUsr +=1;
    }
    for ($i = 0; $i < count($TotalOrgRES); $i++) {
        if ($TotalOrgRES[$i]['CityId'] == 47 || $TotalOrgRES[$i]['CityId'] == 448) {

            $HydTotalOrg +=1;
        }

        if ($TotalOrgRES[$i]['CityId'] == 14 || $TotalOrgRES[$i]['CityId'] == 393) {

            $MumTotalOrg +=1;
        }

        if ($TotalOrgRES[$i]['CityId'] == 77) {

            $PuneTotalOrg +=1;
        }

        if ($TotalOrgRES[$i]['CityId'] == 37) {
            $BangTotalOrg +=1;
        }

        if ($TotalOrgRES[$i]['CityId'] == 39) {
            $ChenTotalOrg +=1;
        }

        if ($TotalOrgRES[$i]['StateId'] == 53 || $TotalOrgRES[$i]['CityId'] == 330 || $TotalOrgRES[$i]['CityId'] == 331 || $TotalOrgRES[$i]['CityId'] == 383 || $TotalOrgRES[$i]['CityId'] == 408) {

            $DelTotalOrg +=1;
        }

        if ($TotalOrgRES[$i]['CityId'] != 47 && $TotalOrgRES[$i]['CityId'] != 448 && $TotalOrgRES[$i]['CityId'] != 14 && $TotalOrgRES[$i]['CityId'] != 393 && $TotalOrgRES[$i]['CityId'] != 77 && $TotalOrgRES[$i]['CityId'] != 37 && $TotalOrgRES[$i]['CityId'] != 39 && $TotalOrgRES[$i]['StateId'] != 53 && $TotalOrgRES[$i]['CityId'] != 330 && $TotalOrgRES[$i]['CityId'] != 331 && $TotalOrgRES[$i]['CityId'] != 383 && $TotalOrgRES[$i]['CityId'] != 408) {

            $OthTotalOrg +=1;
        }


        $TotalOrg +=1;
    }

    for ($i = 0; $i < count($TotalEventsRES); $i++) {
        if ($TotalEventsRES[$i]['CityId'] == 47 || $TotalEventsRES[$i]['CityId'] == 448) {

            $HydTotalEvents +=1;
        }

        if ($TotalEventsRES[$i]['CityId'] == 14 || $TotalEventsRES[$i]['CityId'] == 393) {

            $MumTotalEvents +=1;
        }

        if ($TotalEventsRES[$i]['CityId'] == 77) {

            $PuneTotalEvents +=1;
        }

        if ($TotalEventsRES[$i]['CityId'] == 37) {
            $BangTotalEvents +=1;
        }

        if ($TotalEventsRES[$i]['CityId'] == 39) {
            $ChenTotalEvents +=1;
        }

        if ($TotalEventsRES[$i]['StateId'] == 53 || $TotalEventsRES[$i]['CityId'] == 330 || $TotalEventsRES[$i]['CityId'] == 331 || $TotalEventsRES[$i]['CityId'] == 383 || $TotalEventsRES[$i]['CityId'] == 408) {

            $DelTotalEvents +=1;
        }

        if ($TotalEventsRES[$i]['CityId'] != 47 && $TotalEventsRES[$i]['CityId'] != 448 && $TotalEventsRES[$i]['CityId'] != 14 && $TotalEventsRES[$i]['CityId'] != 393 && $TotalEventsRES[$i]['CityId'] != 77 && $TotalEventsRES[$i]['CityId'] != 37 && $TotalEventsRES[$i]['CityId'] != 39 && $TotalEventsRES[$i]['StateId'] != 53 && $TotalEventsRES[$i]['CityId'] != 330 && $TotalEventsRES[$i]['CityId'] != 331 && $TotalEventsRES[$i]['CityId'] != 383 && $TotalEventsRES[$i]['CityId'] != 408) {

            $OthTotalEvents +=1;
        }


        $TotalEvents +=1;
    }
    for ($i = 0; $i < count($TotalEventsfreeRES); $i++) {
        if ($TotalEventsfreeRES[$i]['CityId'] == 47 || $TotalEventsfreeRES[$i]['CityId'] == 448) {

            $HydTotalFreeEvents +=1;
        }

        if ($TotalEventsfreeRES[$i]['CityId'] == 14 || $TotalEventsfreeRES[$i]['CityId'] == 393) {

            $MumTotalFreeEvents +=1;
        }

        if ($TotalEventsfreeRES[$i]['CityId'] == 77) {

            $PuneTotalFreeEvents +=1;
        }

        if ($TotalEventsfreeRES[$i]['CityId'] == 37) {
            $BangTotalFreeEvents +=1;
        }

        if ($TotalEventsfreeRES[$i]['CityId'] == 39) {
            $ChenTotalFreeEvents +=1;
        }

        if ($TotalEventsfreeRES[$i]['StateId'] == 53 || $TotalEventsfreeRES[$i]['CityId'] == 330 || $TotalEventsfreeRES[$i]['CityId'] == 331 || $TotalEventsfreeRES[$i]['CityId'] == 383 || $TotalEventsfreeRES[$i]['CityId'] == 408) {

            $DelTotalFreeEvents +=1;
        }

        if ($TotalEventsfreeRES[$i]['CityId'] != 47 && $TotalEventsfreeRES[$i]['CityId'] != 448 && $TotalEventsfreeRES[$i]['CityId'] != 14 && $TotalEventsfreeRES[$i]['CityId'] != 393 && $TotalEventsfreeRES[$i]['CityId'] != 77 && $TotalEventsfreeRES[$i]['CityId'] != 37 && $TotalEventsfreeRES[$i]['CityId'] != 39 && $TotalEventsfreeRES[$i]['StateId'] != 53 && $TotalEventsfreeRES[$i]['CityId'] != 330 && $TotalEventsfreeRES[$i]['CityId'] != 331 && $TotalEventsfreeRES[$i]['CityId'] != 383 && $TotalEventsfreeRES[$i]['CityId'] != 408) {

            $OthTotalFreeEvents +=1;
        }


        $TotalFreeEvents +=1;
    }
    for ($i = 0; $i < count($TotalEventspaidRES); $i++) {
        if ($TotalEventspaidRES[$i]['CityId'] == 47 || $TotalEventspaidRES[$i]['CityId'] == 448) {

            $HydTotalPaidEvents +=1;
        }

        if ($TotalEventspaidRES[$i]['CityId'] == 14 || $TotalEventspaidRES[$i]['CityId'] == 393) {

            $MumTotalPaidEvents +=1;
        }

        if ($TotalEventspaidRES[$i]['CityId'] == 77) {

            $PuneTotalPaidEvents +=1;
        }

        if ($TotalEventspaidRES[$i]['CityId'] == 37) {
            $BangTotalPaidEvents +=1;
        }

        if ($TotalEventspaidRES[$i]['CityId'] == 39) {
            $ChenTotalPaidEvents +=1;
        }

        if ($TotalEventspaidRES[$i]['StateId'] == 53 || $TotalEventspaidRES[$i]['CityId'] == 330 || $TotalEventspaidRES[$i]['CityId'] == 331 || $TotalEventspaidRES[$i]['CityId'] == 383 || $TotalEventspaidRES[$i]['CityId'] == 408) {

            $DelTotalPaidEvents +=1;
        }

        if ($TotalEventspaidRES[$i]['CityId'] != 47 && $TotalEventspaidRES[$i]['CityId'] != 448 && $TotalEventspaidRES[$i]['CityId'] != 14 && $TotalEventspaidRES[$i]['CityId'] != 393 && $TotalEventspaidRES[$i]['CityId'] != 77 && $TotalEventspaidRES[$i]['CityId'] != 37 && $TotalEventspaidRES[$i]['CityId'] != 39 && $TotalEventspaidRES[$i]['StateId'] != 53 && $TotalEventspaidRES[$i]['CityId'] != 330 && $TotalEventspaidRES[$i]['CityId'] != 331 && $TotalEventspaidRES[$i]['CityId'] != 383 && $TotalEventspaidRES[$i]['CityId'] != 408) {

            $OthTotalPaidEvents +=1;
        }


        $TotalPaidEvents +=1;
    }
    for ($i = 0; $i < count($TotalEventsnoregRES); $i++) {
        if ($TotalEventsnoregRES[$i]['CityId'] == 47 || $TotalEventsnoregRES[$i]['CityId'] == 448) {

            $HydTotalNoregEvents +=1;
        }

        if ($TotalEventsnoregRES[$i]['CityId'] == 14 || $TotalEventsnoregRES[$i]['CityId'] == 393) {

            $MumTotalNoregEvents +=1;
        }

        if ($TotalEventsnoregRES[$i]['CityId'] == 77) {

            $PuneTotalNoregEvents +=1;
        }

        if ($TotalEventsnoregRES[$i]['CityId'] == 37) {
            $BangTotalNoregEvents +=1;
        }

        if ($TotalEventsnoregRES[$i]['CityId'] == 39) {
            $ChenTotalNoregEvents +=1;
        }

        if ($TotalEventsnoregRES[$i]['StateId'] == 53 || $TotalEventsnoregRES[$i]['CityId'] == 330 || $TotalEventsnoregRES[$i]['CityId'] == 331 || $TotalEventsnoregRES[$i]['CityId'] == 383 || $TotalEventsnoregRES[$i]['CityId'] == 408) {

            $DelTotalNoregEvents +=1;
        }

        if ($TotalEventsnoregRES[$i]['CityId'] != 47 && $TotalEventsnoregRES[$i]['CityId'] != 448 && $TotalEventsnoregRES[$i]['CityId'] != 14 && $TotalEventsnoregRES[$i]['CityId'] != 393 && $TotalEventsnoregRES[$i]['CityId'] != 77 && $TotalEventsnoregRES[$i]['CityId'] != 37 && $TotalEventsnoregRES[$i]['CityId'] != 39 && $TotalEventsnoregRES[$i]['StateId'] != 53 && $TotalEventsnoregRES[$i]['CityId'] != 330 && $TotalEventsnoregRES[$i]['CityId'] != 331 && $TotalEventsnoregRES[$i]['CityId'] != 383 && $TotalEventsnoregRES[$i]['CityId'] != 408) {

            $OthTotalNoregEvents +=1;
        }


        $TotalNoregEvents +=1;
    }
    for ($i = 0; $i < count($TotalunqEvents); $i++) {
        if ($TotalunqEvents[$i]['CityId'] == 47 || $TotalunqEvents[$i]['CityId'] == 448) {

            $HydTotalunEvents +=1;
        }

        if ($TotalunqEvents[$i]['CityId'] == 14 || $TotalunqEvents[$i]['CityId'] == 393) {

            $MumTotalunEvents +=1;
        }

        if ($TotalunqEvents[$i]['CityId'] == 77) {

            $PuneTotalunEvents +=1;
        }

        if ($TotalunqEvents[$i]['CityId'] == 37) {
            $BangTotalunEvents +=1;
        }

        if ($TotalunqEvents[$i]['CityId'] == 39) {
            $ChenTotalunEvents +=1;
        }

        if ($TotalunqEvents[$i]['StateId'] == 53 || $TotalunqEvents[$i]['CityId'] == 330 || $TotalunqEvents[$i]['CityId'] == 331 || $TotalunqEvents[$i]['CityId'] == 383 || $TotalunqEvents[$i]['CityId'] == 408) {

            $DelTotalunEvents +=1;
        }

        if ($TotalunqEvents[$i]['CityId'] != 47 && $TotalunqEvents[$i]['CityId'] != 448 && $TotalunqEvents[$i]['CityId'] != 14 && $TotalunqEvents[$i]['CityId'] != 393 && $TotalunqEvents[$i]['CityId'] != 77 && $TotalunqEvents[$i]['CityId'] != 37 && $TotalunqEvents[$i]['CityId'] != 39 && $TotalunqEvents[$i]['StateId'] != 53 && $TotalunqEvents[$i]['CityId'] != 330 && $TotalunqEvents[$i]['CityId'] != 331 && $TotalunqEvents[$i]['CityId'] != 383 && $TotalunqEvents[$i]['CityId'] != 408) {

            $OthTotalunEvents +=1;
        }


        $TotalunEvents +=1;
    }
    for ($i = 0; $i < count($TotalunqUserID); $i++) {
        if ($TotalunqUserID[$i]['CityId'] == 47 || $TotalunqUserID[$i]['CityId'] == 448) {

            $HydTotalunUserID +=1;
        }

        if ($TotalunqUserID[$i]['CityId'] == 14 || $TotalunqUserID[$i]['CityId'] == 393) {

            $MumTotalunUserID +=1;
        }

        if ($TotalunqUserID[$i]['CityId'] == 77) {

            $PuneTotalunUserID +=1;
        }

        if ($TotalunqUserID[$i]['CityId'] == 37) {
            $BangTotalunUserID +=1;
        }

        if ($TotalunqUserID[$i]['CityId'] == 39) {
            $ChenTotalunUserID +=1;
        }

        if ($TotalunqUserID[$i]['StateId'] == 53 || $TotalunqUserID[$i]['CityId'] == 330 || $TotalunqUserID[$i]['CityId'] == 331 || $TotalunqUserID[$i]['CityId'] == 383 || $TotalunqUserID[$i]['CityId'] == 408) {

            $DelTotalunUserID +=1;
        }

        if ($TotalunqUserID[$i]['CityId'] != 47 && $TotalunqUserID[$i]['CityId'] != 448 && $TotalunqUserID[$i]['CityId'] != 14 && $TotalunqUserID[$i]['CityId'] != 393 && $TotalunqUserID[$i]['CityId'] != 77 && $TotalunqUserID[$i]['CityId'] != 37 && $TotalunqUserID[$i]['CityId'] != 39 && $TotalunqUserID[$i]['StateId'] != 53 && $TotalunqUserID[$i]['CityId'] != 330 && $TotalunqUserID[$i]['CityId'] != 331 && $TotalunqUserID[$i]['CityId'] != 383 && $TotalunqUserID[$i]['CityId'] != 408) {

            $OthTotalunUserID +=1;
        }


        $TotalunUserID +=1;
    }



    /* Processing End here */


    //echo $Msg;




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
			<table width='100%' border='1' cellpadding='0' cellspacing='0' >
			<thead>
            <tr bgcolor='#94D2F3'>
		  	<td class='tblinner' valign='middle' align='center'>&nbsp;</td>
			<td class='tblinner' valign='middle'  align='center'>All</td>
            <td class='tblinner' valign='middle'  align='center'>Hyderabad</td>
            <td class='tblinner' valign='middle'  align='center'>Mumbai</td>
            <td class='tblinner' valign='middle'  align='center'>Pune</td>
            <td class='tblinner' valign='middle'  align='center'>Bangalore</td>
            <td class='tblinner' valign='middle'  align='center'>Chennai</td>
            <td class='tblinner' valign='middle'  align='center'>Delhi/NCR</td>
            <td class='tblinner' valign='middle'  align='center'>Others</td>
          </tr>
        
       
<tr><td  style='line-height:30px;'><strong>Total Transaction Amount:</strong></td>
  <td  align='center'><font color='#000000'>" . ($TotalAmountcard + $TotalAmountPayatCounter + $TotalAmountCOD + $TotalAmountchk) . "</font></td>
  <td  align='center'><font color='#000000'>" . ($HydTotalAmountcard + $HydTotalAmountPayatCounter + $HydTotalAmountCOD + $HydTotalAmountchk) . "</font></td>
  <td  align='center'><font color='#000000'>" . ($MumTotalAmountcard + $MumTotalAmountPayatCounter + $MumTotalAmountCOD + $MumTotalAmountchk) . "</font></td>
  <td  align='center'><font color='#000000'>" . ($PuneTotalAmountcard + $PuneTotalAmountPayatCounter + $PuneTotalAmountCOD + $PuneTotalAmountchk) . "</font></td>
  <td  align='center'><font color='#000000'>" . ($BangTotalAmountcard + $BangTotalAmountPayatCounter + $BangTotalAmountCOD + $BangTotalAmountchk) . "</font></td>
  <td  align='center'><font color='#000000'>" . ($ChenTotalAmountcard + $ChenTotalAmountPayatCounter + $ChenTotalAmountCOD + $ChenTotalAmountchk) . "</font></td>
  <td  align='center'><font color='#000000'>" . ($DelTotalAmountcard + $DelTotalAmountPayatCounter + $DelTotalAmountCOD + $DelTotalAmountchk) . "</font></td>
<td  align='center'><font color='#000000'>" . ($OthTotalAmountcard + $OthTotalAmountPayatCounter + $OthTotalAmountCOD + $OthTotalAmountchk) . "</font></td>
</tr>
<tr>
  <td>Total Card Transactions Amount :</td>
  <td  align='center'><font color='#000000'>" . $TotalAmountcard . "</font></td>
<td  align='center'><font color='#000000'> " . $HydTotalAmountcard . "</font></td>
  <td  align='center'><font color='#000000'> " . $MumTotalAmountcard . "</font></td>
  <td  align='center'><font color='#000000'> " . $PuneTotalAmountcard . "</font></td>
  <td  align='center'><font color='#000000'> " . $BangTotalAmountcard . "</font></td>
  <td  align='center'><font color='#000000'> " . $ChenTotalAmountcard . "</font></td>
  <td  align='center'><font color='#000000'> " . $DelTotalAmountcard . "</font></td>
  <td  align='center'><font color='#000000'> " . $OthTotalAmountcard . "</font></td>
  
  </tr>
  <tr>
  <tr>
  <td >Total Cheque Transactions Amount :</td>
  <td  align='center'><font color='#000000'>" . $TotalAmountchk . "</font></td>
<td  align='center'><font color='#000000'> " . $HydTotalAmountchk . "</font></td>
  <td  align='center'><font color='#000000'> " . $MumTotalAmountchk . "</font></td>
  <td  align='center'><font color='#000000'> " . $PuneTotalAmountchk . "</font></td>
  <td  align='center'><font color='#000000'> " . $BangTotalAmountchk . "</font></td>
  <td  align='center'><font color='#000000'> " . $ChenTotalAmountchk . "</font></td>
  <td  align='center'><font color='#000000'> " . $DelTotalAmountchk . "</font></td>
  <td  align='center'><font color='#000000'> " . $OthTotalAmountchk . "</font></td>
  </tr>
  <tr>
  <tr>
  <td >Total PayatCounter Amount :</td>
  <td  align='center'><font color='#000000'>" . $TotalAmountPayatCounter . "</font></td>
  <td  align='center'><font color='#000000'>" . $HydTotalAmountPayatCounter . "</font></td>
  <td  align='center'><font color='#000000'>" . $MumTotalAmountPayatCounter . "</font></td>
  <td  align='center'><font color='#000000'>" . $PuneTotalAmountPayatCounter . "</font></td>
  <td  align='center'><font color='#000000'>" . $BangTotalAmountPayatCounter . "</font></td>
  <td  align='center'><font color='#000000'>" . $ChenTotalAmountPayatCounter . "</font></td>
  <td  align='center'><font color='#000000'>" . $DelTotalAmountPayatCounter . "</font></td>
  <td  align='center'><font color='#000000'>" . $OthTotalAmountPayatCounter . "</font></td>
  </tr>
  <tr>
  <td >Total CashonDelivery Amount :</td>
 <td  align='center'><font color='#000000'>" . $TotalAmountCOD . "</font></td>
 <td  align='center'><font color='#000000'>" . $HydTotalAmountCOD . "</font></td>
 <td  align='center'><font color='#000000'>" . $MumTotalAmountCOD . "</font></td>
 <td  align='center'><font color='#000000'>" . $PuneTotalAmountCOD . "</font></td>
 <td  align='center'><font color='#000000'>" . $BangTotalAmountCOD . "</font></td>
 <td  align='center'><font color='#000000'>" . $ChenTotalAmountCOD . "</font></td>
 <td  align='center'><font color='#000000'>" . $DelTotalAmountCOD . "</font></td>
 <td  align='center'><font color='#000000'>" . $OthTotalAmountCOD . "</font></td>
  </tr>
  
  <tr>
<tr>
  <td >Total Card Transactions :</td>
 <td  align='center'><font color='#000000'>" . $Totalcard . "</font></td>
<td  align='center'><font color='#000000'> " . $HydTotalcard . "</font></td>
  <td  align='center'><font color='#000000'> " . $MumTotalcard . "</font></td>
  <td  align='center'><font color='#000000'> " . $PuneTotalcard . "</font></td>
  <td  align='center'><font color='#000000'> " . $BangTotalcard . "</font></td>
  <td  align='center'><font color='#000000'> " . $ChenTotalcard . "</font></td>
  <td  align='center'><font color='#000000'> " . $DelTotalcard . "</font></td>
  <td  align='center'><font color='#000000'> " . $OthTotalcard . "</font></td>
  </tr>
  <tr>
  <td >Total Pay at Counter :</td>
 <td  align='center'><font color='#000000'>" . $TotalPayatCounter . "</font></td>
<td  align='center'><font color='#000000'>" . $HydTotalPayatCounter . "</font></td>
 <td  align='center'><font color='#000000'>" . $MumTotalPayatCounter . "</font></td>
  <td  align='center'><font color='#000000'>" . $PuneTotalPayatCounter . "</font></td>
  <td  align='center'><font color='#000000'>" . $BangTotalPayatCounter . "</font></td>
  <td  align='center'><font color='#000000'>" . $ChenTotalPayatCounter . "</font></td>
  <td  align='center'><font color='#000000'>" . $DelTotalPayatCounter . "</font></td>
  <td  align='center'><font color='#000000'>" . $OthTotalPayatCounter . "</font></td>
  </tr>
  <tr>
  <td >Total COD Transactions :</td>
  <td  align='center'><font color='#000000'>" . $TotalCOD . "</font></td>
  <td  align='center'><font color='#000000'>" . $HydTotalCOD . "</font></td>
    <td  align='center'><font color='#000000'>" . $MumTotalCOD . "</font></td>
      <td  align='center'><font color='#000000'>" . $PuneTotalCOD . "</font></td>
        <td  align='center'><font color='#000000'>" . $BangTotalCOD . "</font></td>
          <td  align='center'><font color='#000000'>" . $ChenTotalCOD . "</font></td>
            <td  align='center'><font color='#000000'>" . $DelTotalCOD . "</font></td>
              <td  align='center'><font color='#000000'>" . $OthTotalCOD . "</font></td>
  </tr>
<tr><td >Total Cheque Transaction :</td>
<td  align='center'><font color='#000000'>" . $Totalchk . "</font></td>
<td  align='center'><font color='#000000'>" . $HydTotalchk . "</font></td>
<td  align='center'><font color='#000000'>" . $MumTotalchk . "</font></td>
<td  align='center'><font color='#000000'>" . $PuneTotalchk . "</font></td>
<td  align='center'><font color='#000000'>" . $BangTotalchk . "</font></td>
<td  align='center'><font color='#000000'>" . $ChenTotalchk . "</font></td>
<td  align='center'><font color='#000000'>" . $DelTotalchk . "</font></td>
<td  align='center'><font color='#000000'>" . $OthTotalchk . "</font></td>
  </tr>
<tr>
  <td >Total Delegates Signed up for Tickets :</td>
  <td  align='center'><font color='#000000'>" . $TotalSigned . "</font></td>
<td  align='center'><font color='#000000'> " . $HydTotalSigned . "</font></td>
  <td  align='center'><font color='#000000'> " . $MumTotalSigned . "</font></td>
 <td  align='center'><font color='#000000'> " . $PuneTotalSigned . "</font></td>
 <td  align='center'><font color='#000000'> " . $BangTotalSigned . "</font></td>
 <td  align='center'><font color='#000000'> " . $ChenTotalSigned . "</font></td>
 <td  align='center'><font color='#000000'> " . $DelTotalSigned . "</font></td>
 <td  align='center'><font color='#000000'> " . $OthTotalSigned . "</font></td>
  </tr>
  
<tr>
  <td >Delegates Signed up for Free Tickets :</td>
 <td  align='center'><font color='#000000'> " . $TotalFreeSigned . "</font></td>
 <td  align='center'><font color='#000000'> " . $HydTotalFreeSigned . "</font></td>
 <td  align='center'><font color='#000000'> " . $MumTotalFreeSigned . "</font></td>
 <td  align='center'><font color='#000000'> " . $PuneTotalFreeSigned . "</font></td>
 <td  align='center'><font color='#000000'> " . $BangTotalFreeSigned . "</font></td>
 <td  align='center'><font color='#000000'> " . $ChenTotalFreeSigned . "</font></td>
 <td  align='center'><font color='#000000'> " . $DelTotalFreeSigned . "</font></td>
 <td  align='center'><font color='#000000'> " . $OthTotalFreeSigned . "</font></td>
  </tr>
<tr>
  <td >Delegates Signed up for Paid Tickets :</td>
 <td  align='center'><font color='#000000'>" . $TotalPaidSigned . "</font></td>
 <td  align='center'><font color='#000000'>" . $HydTotalPaidSigned . "</font></td>
 <td  align='center'><font color='#000000'>" . $MumTotalPaidSigned . "</font></td>
 <td  align='center'><font color='#000000'>" . $PuneTotalPaidSigned . "</font></td>
 <td  align='center'><font color='#000000'>" . $BangTotalPaidSigned . "</font></td>
 <td  align='center'><font color='#000000'>" . $ChenTotalPaidSigned . "</font></td>
 <td  align='center'><font color='#000000'>" . $DelTotalPaidSigned . "</font></td>
 <td  align='center'><font color='#000000'>" . $OthTotalPaidSigned . "</font></td>
 
 
  </tr>
  <tr><td >Total Users Signed up :</td>
  <td  align='center'><font color='#000000'>" . $TotalUsr . "</font></td>
  <td  align='center'><font color='#000000'>" . $HydTotalUsr . "</font></td>
  <td  align='center'><font color='#000000'>" . $MumTotalUsr . "</font></td>
  <td  align='center'><font color='#000000'>" . $PuneTotalUsr . "</font></td>
  <td  align='center'><font color='#000000'>" . $BangTotalUsr . "</font></td>
  <td  align='center'><font color='#000000'>" . $ChenTotalUsr . "</font></td>
  <td  align='center'><font color='#000000'>" . $DelTotalUsr . "</font></td>
  <td  align='center'><font color='#000000'>" . $OthTotalUsr . "</font></td>
  
  
  </tr>

<tr><td >Total Organizers Registred :</td>
<td  align='center'><font color='#000000'>" . $TotalOrg . "</font></td>
<td  align='center'><font color='#000000'>" . $HydTotalOrg . "</font></td>
<td  align='center'><font color='#000000'>" . $MumTotalOrg . "</font></td>
<td  align='center'><font color='#000000'>" . $PuneTotalOrg . "</font></td>
<td  align='center'><font color='#000000'>" . $BangTotalOrg . "</font></td>
<td  align='center'><font color='#000000'>" . $ChenTotalOrg . "</font></td>
<td  align='center'><font color='#000000'>" . $DelTotalOrg . "</font></td>
<td  align='center'><font color='#000000'>" . $OthTotalOrg . "</font></td>
  </tr>
<tr><td >Total Events Added :</td>
<td  align='center'><font color='#000000'>" . $TotalEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $HydTotalEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $MumTotalEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $PuneTotalEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $BangTotalEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $ChenTotalEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $DelTotalEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $OthTotalEvents . "</font></td>
  </tr>
<tr><td >Paid Events :</td>
<td  align='center'><font color='#000000'>" . $TotalPaidEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $HydTotalPaidEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $MumTotalPaidEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $PuneTotalPaidEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $BangTotalPaidEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $ChenTotalPaidEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $DelTotalPaidEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $OthTotalPaidEvents . "</font></td>

  </tr>
<tr><td >Free Events :</td>
<td  align='center'><font color='#000000'>" . $TotalFreeEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $HydTotalFreeEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $MumTotalFreeEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $PuneTotalFreeEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $BangTotalFreeEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $ChenTotalFreeEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $DelTotalFreeEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $OthTotalFreeEvents . "</font></td>
  </tr>
<tr><td >No Registration Events :</td>
<td  align='center'><font color='#000000'>" . $TotalNoregEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $HydTotalNoregEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $MumTotalNoregEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $PuneTotalNoregEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $BangTotalNoregEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $ChenTotalNoregEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $DelTotalNoregEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $OthTotalNoregEvents . "</font></td>
  </tr>
<tr><td >No Unique Events :</td>
<td  align='center'><font color='#000000'>" . $TotalunEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $HydTotalunEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $MumTotalunEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $PuneTotalunEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $BangTotalunEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $ChenTotalunEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $DelTotalunEvents . "</font></td>
<td  align='center'><font color='#000000'>" . $OthTotalunEvents . "</font></td>
  </tr>
<tr><td >No Unique Organizers :</td>
<td  align='center'><font color='#000000'>" . $TotalunUserID . "</font></td>
<td  align='center'><font color='#000000'>" . $HydTotalunUserID . "</font></td>
<td  align='center'><font color='#000000'>" . $MumTotalunUserID . "</font></td>
<td  align='center'><font color='#000000'>" . $PuneTotalunUserID . "</font></td>
<td  align='center'><font color='#000000'>" . $BangTotalunUserID . "</font></td>
<td  align='center'><font color='#000000'>" . $ChenTotalunUserID . "</font></td>
<td  align='center'><font color='#000000'>" . $DelTotalunUserID . "</font></td>
<td  align='center'><font color='#000000'>" . $OthTotalunUserID . "</font></td>
  </tr>


</table></body></html>";

    //SEND EMAIL
    //$to = 'team@meraevents.com,kumard@meraevents.com';
    $subject = '[MeraEvents] Sales Report Details by City From: ' . $yesterdaySDate . ' To: ' . $yesterdayEDate;
    $message = 'Dear Team,<br /><br />Sales Report Details by City From: ' . $yesterdaySDate . ' To: ' . $yesterdayEDate . '<br /><br /><br />' . $Msg . '<br /><br />Regards,<br>Meraevents Team';

    //echo $message;

    $cc = $content = $filename = $bcc = $replyto = NULL;
    $to = 'sales@meraevents.com';
    //$cc='sreekanthp@meraevents.com,naidu@meraevents.com,Amitgupta@meraevents.com';
    $bcc = 'qison@meraevents.com';
    //$to = 'sudhera99@gmail.com,shashi.enjapuri@gmail.com,durgeshmishra2525@gmail.com';
    //$cc='shashidhar.enjapuri@qison.com,sudhera.bagineni@qison.com,durgesh.mishra@qison.com';
    $from = 'MeraEvents<admin@meraevents.com>';
    $commonFunctions->sendEmail($to, $cc, $bcc, $from, $replyto, $subject, $message, $content, $filename);

    //mail($to, $subject, $message, $headers);
    //mail('durgeshmishra2525@gmail.com', $subject, $message, $headers);
    //mail('kumard@meraevents.com', $subject, $message, $headers);
    //EMAIL SENT
    mysql_close();
}
?>
