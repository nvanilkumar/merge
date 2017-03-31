<?php
session_start();
ini_set('max_execution_time', 2000);
// error_reporting(-1);
// ini_set('display_errors',1); 
 
include_once("MT/cGlobali.php");
include_once('includes/SMSfunction.php');
include 'loginchk.php';
include_once 'eventsignup.php';
include_once("includes/common_functions.php");
$common=new functions();

$Global = new cGlobali();
$eventSignup = new eventsignup();

$MsgCountryExist = '';
include_once('includes/functions.php');
if ($_REQUEST['AddComment'] == "Add Comment") {
    $sqlcheck = "select id as CanTransId from comment where eventsignupid='" . $_REQUEST[TransId] . "' and comment='" . $_REQUEST[comment] . "'";
    $CheckRES = $Global->SelectQuery($sqlcheck);
    if (count($CheckRES) > 0) {
        
    } else if ($_REQUEST['comment'] != "") {
         $InsTransComments = "insert into comment(eventsignupid,comment,cts,createdby)values('" . $_REQUEST[TransId] . "','" . $_REQUEST[comment] . "','" . date('Y-m-d') . "','Admin')";
        $indId = $Global->ExecuteQuery($InsTransComments);
    }
}

if ($_REQUEST['SendSms'] == "Send Sms") {
//    $sqlsms = "select Phone from eventsignup where Id='" . $_REQUEST[TransId] . "' ";
//    $smsRES = $Global->SelectQuery($sqlsms);

     $sqlsms = $eventSignup->getPrimaryAttendeDetails($_REQUEST[TransId]);
     $smsRES = $eventSignup->formatEventSignupDetails($sqlsms);
    $MobileNo = $smsRES[0]['Phone'];
    $Message = $_REQUEST['message'];
    $RtrnMsg = 0;
    functionSendSMS($MobileNo, $Message, $RtrnMsg);
}

if ($_REQUEST['SendEmail'] == "Send Email") {
//    $sqlusr = "select Name,EMail from eventsignup where Id='" . $_REQUEST[TransId] . "' ";
//    $usrRES = $Global->SelectQuery($sqlusr);
    $sqlusr = $eventSignup->getPrimaryAttendeDetails($_REQUEST[TransId]);
    $usrRES = $eventSignup->formatEventSignupDetails($sqlusr);
    
    $name = $usrRES[0]['Name'];
    $EMail = $usrRES[0]['EMail'];
    $subject = $_REQUEST['subject'];
    $emsg = $_REQUEST['emsg'];
    $salesp = $_REQUEST['salesp'];
    $sqlsign = "select signature from salesperson where id='" . $salesp . "' ";
    $signRES = $Global->SelectQuery($sqlsign);

    $selEMailMsgs = "SELECT id as Id,template as Msg, type as MsgType, fromemailid as SendThruEMailId "
            . "FROM messagetemplate WHERE type ='emTrans' and mode = 'email'";
    $dtlEMailMsgs = $Global->SelectQuery($selEMailMsgs);

    $EMailMsgId = $dtlEMailMsgs[0]['Id'];
    $message = str_replace("FirstName", $name, $dtlEMailMsgs[0]['Msg']);
    $message = str_replace("MSGTXT", stripslashes($emsg), $message);
    $message = str_replace("EmailSignature", $signRES[0]['signature'], $message);

    $headers = "From:" . $dtlEMailMsgs[0]['SendThruEMailId'] . "\r\n";

    $headers.='Bcc: sunila@meraevents.com,support@meraevents.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion() . "\r\n" .
            "MIME-Version: 1.0\r\n" .
            "Content-Type: text/html; charset=utf-8\r\n" .
            "Content-Transfer-Encoding: 8bit\r\n\r\n";

    mail($EMail, $subject, $message, $headers);
}


if (isset($_REQUEST['recptno']) && $_REQUEST['recptno'] != "") {
    $signid = " and s.id=" . $_REQUEST['recptno'];
}
if (isset($_REQUEST['transid']) && $_REQUEST['transid'] != "") {
    $transid = " and s.paymenttransactionid='" . $_REQUEST['transid'] . "' ";
}
if (isset($_REQUEST['email']) && $_REQUEST['email'] != "") {

    $checkev = NULL;
    $merge_query == NULL;
//    $checkeventsignup = $Global->SelectQuery("SELECT id as Id from eventsignup WHERE EMail='" . $_REQUEST['email'] . "'");
    $eventSingupListRes = $eventSignup->getPrimaryEmailEventSignupIds($_REQUEST['email']);
     
         

    if (count($eventSingupListRes)> 0) {
        $singupIdList=$eventSignup->formatEventSignupids($eventSingupListRes);
            $checkev = "s.id in (" . $singupIdList . ")";
    }


    $checkmerge = "SELECT id AS Id, facebookid FacebookId,twitterid twitter_id FROM user WHERE email='" . $_REQUEST['email'] . "'";
    $resultaccounts = $Global->SelectQuery($checkmerge);
    if ($resultaccounts) {
        $fbId = $resultaccounts[0][1];
        $twId = $resultaccounts[0][2];
        $merge_ids = $resultaccounts[0][0] . ",";
        if (!empty($fbId)) {
            $checkfbids = "SELECT id AS Id FROM user WHERE facebookid='" . $fbId . "'";
            $resultfbids = $Global->SelectQuery($checkfbids);
            foreach ($resultfbids as $resultfbid) {
                $merge_ids.=$resultfbid['Id'] . ',';
            }
        }
        if (!empty($twId)) {
            $checktwids = "SELECT id AS Id FROM user WHERE twitterid='" . $twId . "'";
            $resulttwids = $Global->SelectQuery($checktwids);
            foreach ($resulttwids as $resulttwid) {
                $merge_ids.=$resulttwid['Id'] . ',';
            }
        }
        if (strlen($merge_ids) > 0) {
            $merge_query = "s.userid IN (" . substr($merge_ids, 0, -1) . ")";
        }
    }

    $merge_query2 = NULL;
    if (strlen($merge_query) > 0 && strlen($checkev) > 0) {
        $merge_query2 = " and ($merge_query OR $checkev) ";
    } else
    if (strlen($merge_query) > 0 || strlen($checkev) > 0) {
        $merge_query2 = " and $merge_query $checkev ";
    }

    if (strlen($merge_query2) == 0) {
        // echo "reching";
        //echo "Location: "._HTTP_SITE_ROOT."/CheckReg.php";
        //  header("Location:"._HTTP_SITE_ROOT."/ctrl/CheckReg.tpl.php" );
        ?><Script>
                           alert('No matching email');
                           // $("#ErrorText").text("Email not found");

                           location.href = "CheckReg.php?ErrorText='IncorrectEmail'";
                           //  document.getElementById("ErrorText").value="yes not found";

                           die;
        </script> <?php
    }
}

$sameCurrencyEBS = '';
$sameCurrencyCOD = '';
$sameCurrencyInc = '';
if ((isset($_REQUEST['recptno']) && $_REQUEST['recptno'] != "") || (isset($_REQUEST['email']) && $_REQUEST['email'] != "") || (isset($_REQUEST[transid]) && $_REQUEST[transid] != "")) {

    //Display list of Successful Transactions ,s.Name,s.EMail,s.Phone,
    //paymentmodeid 1-card,4-offline, 5-spot
    $TransactionQuery = "SELECT s.eventid as EventId,s.userid as UserId, s.id as Id, s.signupdate as SignupDt,"
                         ." e.title as Title , s.quantity as Qty, s.totalamount as Fees,"
                         ." s.conversionrate as conversionRate,s. paymenttransactionid as PaymentTransId,"
                         ." s.paymentstatus as eChecked,c.code 'currencyCode',pg.name as PaymentGateway,"
                         ." s. convertedamount as paypal_converted_amount"
            . " FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id "
            . "INNER JOIN currency c ON c.id=s.fromcurrencyid "
            . "INNER JOIN paymentgateway as pg ON pg.id=s.paymentgatewayid  "
            . "WHERE 1 AND e.deleted=0 AND s.paymentmodeid in (1,4,5) " . $merge_query2 . " ". $signid . $transid 
            . " AND (s.totalamount=0 OR (s.totalamount != 0 AND s.paymenttransactionid != 'A1')) "
            . "ORDER BY s.signupdate DESC";
    $TransactionRES = $Global->SelectQuery($TransactionQuery);

    $sameCurrencyEBS = sameCurrencyCode($TransactionRES);

    //,s.Name,s.EMail,s.Phone
    $FailTransactionQuery = "SELECT s.eventid AS EventId, s.userid AS UserId, s.id AS Id, s.signupdate AS SignupDt, "
            . "e.title AS Title , s.quantity AS Qty, (s.totalamount) AS Fees, "
            . "s.conversionrate as conversionRate,s.paymenttransactionid AS PaymentTransId,pg.name AS PaymentGateway, "
            . "c.code currencyCode "
            . "FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id "
            . "INNER JOIN currency c ON c.id=s.fromcurrencyid "
            . "INNER JOIN paymentgateway as pg ON pg.id=s.paymentgatewayid  "
            . "WHERE 1  AND e.deleted=0 " . $merge_query2 . " ".$signid . $transid 
            . " AND s.paymentmodeid=1 AND s.paymenttransactionid = 'A1' AND s.totalamount != 0 "
            . "ORDER BY s.signupdate DESC";
    $FailTransactionRES = $Global->SelectQuery($FailTransactionQuery);
    $sameCurrencyInc = sameCurrencyCode($FailTransactionRES);
}
if ($_REQUEST['gharpayid'] != "" || (isset($_REQUEST['recptno']) && $_REQUEST['recptno'] != "") || (isset($_REQUEST['email']) && $_REQUEST['email'] != "")) {
    if ($_REQUEST['gharpayid'] != "") {
        $garpay = " And c.gharpayid='" . $_REQUEST['gharpayid'] . "'";
    }
    //,s.Name,s.EMail,s.Phone
    $CODTransactionQuery = "SELECT s.eventid AS EventId, s.userid AS UserId, s.id AS Id, s.signupdate AS SignupDt,"
            . " e.title AS Title , s.quantity AS Qty, (s.totalamount) AS Fees, "
            . "c.gharpayid AS GharPayId, c.status,"
            . " cu.code currencyCode "
            . "FROM eventsignup AS s , event AS e , "
            . "codstatus as c,"
            . "currency cu "
            . "WHERE e.deleted=0 and s.eventid = e.id and cu.id=s.fromcurrencyid "
            . "AND s.id=c.eventsignupid  " 
            . $merge_query2 . " ". $signid . $transid . $garpay 
            . " AND s.paymentmodeid=2 AND s.paymentgatewayid = '2' AND s.totalamount != 0 "
            . " AND e.deleted = 0 AND e.status = 1 "
            . "ORDER BY s.signupdate DESC";
    $CODTransactionRES = $Global->SelectQuery($CODTransactionQuery);
    $sameCurrencyCOD = sameCurrencyCode($CODTransactionRES);
}

if($CODTransactionRES[0]['Id'] || $TransactionRES[0]['Id']||$FailTransactionRES[0]['Id']){
    $eventSignupId=($TransactionRES[0]['Id'])?$TransactionRES[0]['Id']:$CODTransactionRES[0]['Id'] ;
    $eventSignupId=($eventSignupId)?$eventSignupId:$FailTransactionRES[0]['Id'];
        $sqlusr = $eventSignup->getPrimaryAttendeDetails($eventSignupId);
        $usrRES = $eventSignup->formatEventSignupDetails($sqlusr);
         
    
}




//mysql_close();

include 'templates/CheckReg.tpl.php';
?>