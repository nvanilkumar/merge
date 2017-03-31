<?php
include("commondbdetails.php");
/* * ********************************************************************************************** 
 * 	Page Details : Cron Job Transactions happen yesterday and Send Mail
 * Cron file to update the event ticket sold out status related to ticket avilable qty
 * 	Created / Last Updation Details : 
 * 	1.	Created on 31st Dec 2015 : Created by MAK
 * ********************************************************************************************** */
include("../ctrl/MT/cGlobali.php");
$cGlobali = new cGlobali();
include_once '../ctrl/includes/common_functions.php';
$commonFunctions = new functions();
$_GET = $commonFunctions->stripData($_GET, 1);
$_POST = $commonFunctions->stripData($_POST, 1);
$_REQUEST = $commonFunctions->stripData($_REQUEST, 1);



if ($_GET['runNow'] == 1) {
    $allActiveEvents = "select 
                            id
                        from
                            event
                        where
                        enddatetime > now() and status = 1
                            and deleted = 0
                            and ticketsoldout = 0
                            and private = 0";
    $resAllActiveEvents = $cGlobali->justSelectQuery($allActiveEvents);
    $countActiveEvents = mysqli_num_rows($resAllActiveEvents);
    $activeEventIds = array();
    if ($countActiveEvents > 0) {
        while ($row = $resAllActiveEvents->fetch_assoc()) {
            $activeEventIds[] = $row['id'];
        }
    }
    //Brings the all the events which have added new ticktes or increased ticket qty values by organizer
    $activeTicketEvents = "select 
                                count(t.id), t.eventid
                            from
                                ticket t
                                    INNER JOIN
                                event e ON e.id = t.eventid
                            where
                                t.enddatetime > now()
                                    and e.enddatetime > now()
                                    and e.private = 0
                                    and t.displaystatus = 1
                                    and t.soldout = 0
                                    and t.totalsoldtickets < t.quantity
                                    and t.status = 1
                                    and t.deleted = 0
                                    and e.status = 1
                                    and e.deleted = 0
                            group by t.eventid";
    $resActiveTicketEvents = $cGlobali->justSelectQuery($activeTicketEvents);
    $countActiveTicketEvents = mysqli_num_rows($resActiveTicketEvents);
    $activeTicketEventIds = array();
    if ($countActiveTicketEvents > 0) {
        while ($row = $resActiveTicketEvents->fetch_assoc()) {
            $activeTicketEventIds[] = $row['eventid'];
        }
    }
    $soldOutEvents = array_diff($activeEventIds, $activeTicketEventIds);
    if (count($soldOutEvents) > 0) {
        foreach ($soldOutEvents as $eventId) {
            $solrUpdateData = array();
            $solrUpdateData['eventId'] = $eventId;
            $solrUpdateData['ticketSoldout'] = 1;

            $output = $commonFunctions->changeEventTicketSoldStatus($solrUpdateData);

            $jsonOutput = json_decode($output, true);
            if ($jsonOutput['response']['updatedTicketSoldout'] == 'Success') {
                $UpQuery = "update event set ticketsoldout=1 where id=" . $eventId;
                $resUp = $cGlobali->ExecuteQuery($UpQuery);
            } else {
                $failedIds['soldout'][] = $eventId;
            }
        }
    }
    $unSoldEvents = array_diff($activeTicketEventIds, $activeEventIds);
    if (count($unSoldEvents) > 0) {
        foreach ($unSoldEvents as $soldoutEventrow) {
            $solrUpdateData = array();
            $solrUpdateData['eventId'] = $eventId;
            $solrUpdateData['ticketSoldout'] = 0;

            $output = $commonFunctions->changeEventTicketSoldStatus($solrUpdateData);

            $jsonOutput = json_decode($output, true);
            if ($jsonOutput['response']['updatedTicketSoldout'] == 'Success') {
                $UpQuery = "update event set ticketsoldout=0 where id=" . $eventId;
                $resUp = $cGlobali->ExecuteQuery($UpQuery);
            } else {
                $failedIds['unsoldout'][] = $eventId;
            }
        }
    }
    if (count($failedIds) > 0) {
        $message = 'Hi,<br>Updating event ticketsoldout at ' . $commonFunctions->convertTime($date, DEFAULT_TIME_ZONE, true) . '<br/>';
        if (count($failedIds['soldout']) > 0) {
            $message.='The following event ids did not get updated as soldout.<br>' . implode(',', $failedIds['soldout']) . '<br>';
        }
        if (count($failedIds['unsoldout']) > 0) {
            $message.='<br>The following event ids did not get updated as unsoldout.<br>' . implode(',', $failedIds['unsoldout']) . '<br>';
        }
        $to = 'sunila@meraevents.com,sridevi.gara@qison.com,jagadish.ms@qison.com';
        $bcc = $replyto = $cc = $content = $filename = NULL;
        $subject = 'Failed to update event ticketsoldout' . $commonFunctions->convertTime($date, DEFAULT_TIME_ZONE, true);
        $from = 'MeraEvents<admin@meraevents.com>';
        $commonFunctions->sendEmail($to, $cc, $bcc, $from, $replyto, $subject, $message, $content, $filename);
    }
}
?>
