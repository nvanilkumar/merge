<?php
// ini_set('display_errors',1);
include_once("commondbdetails.php");
include_once("../ctrl/MT/cGlobali.php");
$cGlobali = new cGlobali();
include_once '../ctrl/includes/common_functions.php';
require_once("../application/libraries/paytm/config_paytm.php"); 
require_once("../application/helpers/paytm_helper.php");
$commonFunctions = new functions();
$_GET = $commonFunctions->stripData($_GET);
$_POST = $commonFunctions->stripData($_POST);
$_REQUEST = $commonFunctions->stripData($_REQUEST);
if ($_GET['runNow'] == 1) {
    $db_conn = mysqli_connect($DBServerName, $DBUserName, $DBPassword, $DBIniCatalog);

    $transQry = "SELECT es.id as Id,es.signupdate as SignupDt, og.orderid FROM eventsignup as es 
                INNER join orderlog as og on og.eventsignup=es.id 
                WHERE es.paymentstatus='NotVerified' and es.totalamount!=0 and es.paymenttransactionid!='A1' and es.paymentgatewayid=6";
//        $resQry= mysqli_query($db_conn,$transQry);
    $resQry = $cGlobali->justSelectQuery($transQry);
    $date = date('Y-m-d H:i:s');
    $failedIds = array();
    $requestParamList = array();
    $responseParamList = array();
    
    ///paytm details
    $paytmq="select merchantid from paymentgateway where name='paytm'";
    $paytmqRes = $cGlobali->SelectQuery($paytmq, MYSQLI_ASSOC);
    $merchantid=$paytmqRes[0]['merchantid'];
    
    while ($record = mysqli_fetch_array($resQry, MYSQLI_ASSOC)) {
        if (!is_null($record['Id']) && $record['Id'] > 0) {

            // Create an array having all required parameters for status query.
            $requestParamList = array("MID" => $merchantid, "ORDERID" => $record['orderid']);

            // Call the PG's getTxnStatus() function for verifying the transaction status.
            $responseParamList = getTxnStatus($requestParamList);
            // print_r($responseParamList);
        }
        if ($responseParamList ['STATUS'] == 'TXN_SUCCESS') {
//                $updQry="UPDATE EventSignup set eChecked='Captured',SettlementDate='".$record['SignupDt']."' WHERE Id='".$record['Id']."'";
            $updQry = "update eventsignup set paymentstatus='Captured',settlementdate='" . $record['SignupDt'] . "' where id=" . $record['Id'];
//                $updStatus= mysqli_query($db_conn,$updQry);
            $updStatus = $cGlobali->ExecuteQuery($updQry);
            if (!$updStatus) {
                array_push($failedIds, $record['Id']);
            }
        } else {
            array_push($failedIds, $record['Id']);
        }
    }
    if (count($failedIds) > 0) {
        $ids = '';
        foreach ($failedIds as $k => $v) {
            echo $k . " " . $v;
            $ids.=$v . ",";
        }
        $ids = substr($ids, 0, -1);
        $message = 'Dear Sir/Madam,<br>Reports of Paytm capture transactions done at ' . $date . '<br/>';
        $message.='The following transactions did not get captured.<br>' . $ids . '<br>';
        $to = 'srilakshmis@meraevents.com';
        //$to='ms.jagadish1@gmail.com';
        $bcc = $replyto = $cc = $content = $filename = NULL;
        $cc = "sreekanthp@meraevents.com,durgesh.mishra@qison.com";
        $subject = 'Paytm - [MeraEvents] Reports of Paytm capture transactions done at ' . $date;
        $from = 'MeraEvents<admin@meraevents.com>';
        $commonFunctions->sendEmail($to, $cc, $bcc, $from, $replyto, $subject, $message, $content, $filename);
    }

//    mysqli_close($db_conn);
}
?>
