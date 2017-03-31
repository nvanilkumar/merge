<?php

session_start();
include 'loginchk.php';

include_once('includes/common_functions.php');

$commonFunctions = new functions();
include_once("MT/cGlobali.php");
$Globali = new cGlobali();
$MsgCountryExist = '';
$msg = "";
$from = 'MeraEvents<admin@meraevents.com>';
if(!empty($_REQUEST['EventId'])){
           $query="SELECT id FROM event WHERE id=".$_REQUEST['EventId']." and deleted=1";
           $outputEvent=$Global->SelectQuery($query);
           
           if(!$outputEvent){
if ($_REQUEST['aSalesId'] != "") {
    $sqls = "SELECT eventtype AS EType, ticketwidget AS Tckwz, description AS Description, paymentinterval AS iDays, paymenttype AS Payment from eventsalespersonmapping WHERE salesid=" . $_REQUEST['aSalesId'] . " AND eventid=" . $_REQUEST['EventId'];
    $ressqls = $Globali->SelectQuery($sqls);
	//echo "<pre>"; print_r( $ressqls); exit;
    $EventType = $ressqls[0]['EType'];
    $Tckwdz = $ressqls[0]['Tckwz'];
    $iDays = $ressqls[0]['iDays'];
    $Description = $ressqls[0]['Description'];
    $Payment = $ressqls[0]['Payment'];
    $msg = "<font color=green>Event is already assigned to below sales person!!!</font>";
}
if (isset($_POST['Submit']) && $_POST['Submit'] == "Add" || $_POST['Submit'] == "Update") {

    //echo "<pre>"; print_r($_REQUEST); exit;
    $SalesId = $_POST['SalesId'];
    $EventId = $_REQUEST['EventId'];
    $EventType = $_POST['EventType'];
    $Tckwdz = $_POST['Tckwdz'];
    $iDays = $_POST['iDays'];
    $Description = stripslashes($_POST['Description']);
    $Payment = $_POST['Payment'];
    if ($SalesId != "") {
        $seup = "UPDATE eventdetail SET salespersonid=" . $SalesId . " where eventid=" . $EventId;
        $Globali->ExecuteQuery($seup);
        $sqld = 'SELECT `id` FROM `eventsalespersonmapping` where eventid=' . $EventId;
        $AssigneventId = $Globali->GetSingleFieldValue($sqld);
        if ($AssigneventId > 0) {
            $sqlup = "UPDATE eventsalespersonmapping set `eventid` = " . $EventId . ", `salesid`=" . $SalesId . ", `eventtype`='" . $EventType . "', `ticketwidget`='" . $Tckwdz . "', `paymentinterval` = '" . $iDays . "', `description`='" . $Description . "', `paymenttype`='" . $Payment . "' where `id`=" . $AssigneventId;
            $Globali->ExecuteQuery($sqlup);
        } else {
            $sqlins = "INSERT INTO eventsalespersonmapping (`eventid`, `salesid`, `eventtype`, `ticketwidget`, `paymentinterval`, `description`, `paymenttype`) VALUES (" . $EventId . "," . $SalesId . ",'" . $EventType . "','" . $Tckwdz . "','" . $iDays . "','" . $Description . "','" . $Payment . "')";
            $Globali->ExecuteQuery($sqlins);
        }
        $msg = "<font color=green>Updated Successfully</font>";
    } else {
        $Globali->ExecuteQuery("UPDATE event SET salespersonid=0 WHERE id=" . $EventId);
        $msg = "<font color=green>Updated Successfully</font>";
    }

    $EventsQuery = "SELECT e.title AS Title, es.percentage AS perc FROM event e JOIN eventsetting es ON es.eventid = e.id WHERE e.deleted=0 and e.id='" . $Globali->dbconn->real_escape_string($EventId) . "'";
    $EventsQueryRes = $Globali->SelectQuery($EventsQuery);
    $event_title = $EventsQueryRes[0]['Title'];

    $SalesEmailQuery = "SELECT email FROM salesperson WHERE id = " . $SalesId;
    $SalesEmailQueryRES = $Globali->SelectQuery($SalesEmailQuery);
    $sales_email = $SalesEmailQueryRES[0]['Email'];


    /*if ($_POST['Submit'] == "Add" || $_POST['Submit'] == "Update") {

        $commQuery = "SELECT * FROM commission WHERE eventid=" . $EventId;
        $RescommQuery = $Globali->SelectQuery($commQuery);
        $comcount = count($RescommQuery);

        if ($comcount > 0) {
            $commId = $RescommQuery[0]['id'];
            $cardperc = $RescommQuery[0]['Card'];
            $codperc = $RescommQuery[0]['Cod'];
            $counterperc = $RescommQuery[0]['Counter'];
            $paypalperc = $RescommQuery[0]['Paypal'];
            $mobikwikperc = $RescommQuery[0]['Mobikwik'];
            $paytmperc = $RescommQuery[0]['Paytm'];
            $meeffortperc = $RescommQuery[0]['MEeffort'];
        } else {
            $sqlComm = "SELECT * FROM commission WHERE `global` = 1 AND `eventid` = ''";
            $recComm = $Globali->SelectQuery($sqlComm);

            $Globalicardperc = $recComm[0]['Ebs'];
            $Globalicodperc = $recComm[0]['Cod'];
            $Globalicounterperc = $recComm[0]['Counter'];
            $Globalipaypalperc = $recComm[0]['Paypal'];
            $Globalimobikwikperc = $recComm[0]['Mobikwik'];
            $Globalipaytmperc = $recComm[0]['Paytm'];
            $Globalimeeffortperc = $recComm[0]['MEeffort'];

            $cardperc = $Globalicardperc;
            $codperc = $Globalicodperc;
            $counterperc = $Globalicounterperc;
            $paypalperc = $Globalipaypalperc;
            $mobikwikperc = $Globalimobikwikperc;
            $paytmperc = $Globalipaytmperc;
            $meeffortperc = $Globalimeeffortperc;
        }
        
        $event_data = "Overall% - " . $EventsQueryRes[0]['perc'] . "<br>Card% - " . $cardperc . "<br>COD% - " . $codperc . "<br>"
                . "Counter% - " . $counterperc . "<br>Paypal% - " . $paypalperc ."<br>ME Sales - " . $meeffortperc;
        
        if($Payment == 'Full') {
            $event_data .= "<br/>Payment Details:<br>
                 Full Payment after Event Completion";
         } elseif($Payment == 'Partial') {
             $event_data .= "<br/>Payment Details:<br>
                 Partial Payment - Payment Goes in - ".$iDays." days";
         }
        //$to = $sales_email;
        //$cc = 'support@meraevents.com';
        //$subject = "Event '" . $event_title . "(".$EventId.")' has assigned to you";
        //$message = "Hi ,<br>This is to inform you that an event that named '" . $event_title . "' has been assigned to you with the following details <br>" . $event_data;
      //  $commonFunctions->sendEmail($to, $cc, $bcc, $from, $replyto, $subject, $message, $content, $filename, $folder);
    }*/

    //header("Location: assignevent.php?msg=".$msg);
        header("Location: events_commision.php?EventId=" . $EventId);
}

$action = '';
if ($_POST['Submit'] == "Edit") {
    $action = 'Update';
}
$EventQuery = "SELECT e.title AS Title,e.id AS Id,ed.salespersonid AS SalesId from event AS e JOIN eventdetail AS ed ON e.id = ed.eventid WHERE e.deleted=0 and e.startdatetime > now()  ORDER BY e.title DESC";
$EventQueryRES = $Globali->SelectQuery($EventQuery);

$SalesQuery = "SELECT id AS SalesId,`name` AS SalesName FROM salesperson WHERE `deleted`=0 ORDER BY SalesName";
$SalesQueryRES = $Globali->SelectQuery($SalesQuery);
}}
include 'templates/assignevent.tpl.php';
?>