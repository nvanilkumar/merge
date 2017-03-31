<?php

//include("commondbdetails.php");
include('../ctrl/MT/cGlobali.php');
include_once '../ctrl/includes/common_functions.php';
$Global = new cGlobali();
$commonFunctions = new functions();

$_GET = $commonFunctions->stripData($_GET, 1);
$_POST = $commonFunctions->stripData($_POST, 1);
$_REQUEST = $commonFunctions->stripData($_REQUEST, 1);

if ($_GET['runNow'] == 1) {
    $fetchCount = 100;
    $cronUserId = $commonFunctions->getCronUserDetails();
    $modifiedUser = ",modifiedby=" . $cronUserId;
    $getTotalEvents = "select count(id) as cnt from event where enddatetime>now() and deleted=0";
    $resGetTotalEvents = $Global->SelectQuery($getTotalEvents, MYSQLI_ASSOC);
    $loopCount = 0;
    if (count($resGetTotalEvents) > 0) {
        $loopCount = (int) ceil($resGetTotalEvents[0]['cnt'] / $fetchCount);
    }
    $loop = 0;
    while ($loop < $loopCount) {
        $eventIds = array();
        $selectEvents = "select id from event where enddatetime>now() and deleted=0 limit " . ($loop * $fetchCount) . "," . $fetchCount;
        $resSelectEvents = $Global->SelectQuery($selectEvents, MYSQLI_ASSOC);
        foreach ($resSelectEvents as $row) {
            $eventIds[] = $row['id'];
        }
        if (count($eventIds) > 0) {
            $getTktCount = "select sum(totalsoldtickets) as totalsoldtickets,eventid from ticket where eventid IN(" . implode(",", $eventIds) . ") group by eventid";
            $resGetTktCount = $Global->SelectQuery($getTktCount, MYSQLI_ASSOC);
        }
        if (isset($resGetTktCount) && count($resGetTktCount) > 0) {
            foreach ($resGetTktCount as $ticketData) {
                $inputSolr['eventId'] = $ticketData['eventid'];
                $inputSolr['keyValue'] = $cronUserId;
                $inputSolr['totalsoldtickets'] = $ticketData['totalsoldtickets'];
                $inputSolr['updatetype'] = 'updatetotalsoldtickets';
                $solrResponse = $commonFunctions->makeSolrCall($inputSolr, '/api/event/solrEventStatus');
                if ($solrResponse['status']) {
                    //print_r($solrResponse);exit;
                    $updateQry = "UPDATE event set totalsoldtickets=" . $ticketData['totalsoldtickets'] . "$modifiedUser where id=" . $ticketData['eventid'];
                    $status = $Global->ExecuteQuery($updateQry);
                    //var_dump($Global->dbconn->affected_rows);
                    var_dump($status);
                }
            }
        }
        $loop++;
        //echo $updateQry . "\r\n";
    }
}
?>
