<?php

session_start();
include_once("MT/cGlobali.php");
include_once("includes/common_functions.php");
$Globali = new cGlobali();
$common = new functions();

//CRETAE THE YESTERDAYS START / END DATE
$fromDt = '2009-01-01 00:00:01';
$yesterdayDate = date($common->convertTime($fromDt, DEFAULT_TIMEZONE));
$todayDate = date("d-m-Y H:i:s", strtotime("+5 year"));
$eventType = 'all';
$userData = '';
$SDt = '01/01/2009';
//print_r($common->convertTime($todayDate, DEFAULT_TIMEZONE,true));exit;
$EDt = date('d/m/Y', strtotime($common->convertTime($todayDate, DEFAULT_TIMEZONE)));
if (isset($_POST['formSubmit'])) {
    //print_r($_POST); exit;
    //dates
    $SDt = str_replace('/', '-', $_POST['txtSDt']);
    $EDt = str_replace('/', '-', $_POST['txtEDt']);
    if (strlen($SDt) > 0) {
        $fromDt = date("Y-m-d", strtotime($SDt . ' 00:00:01'));
        $yesterdayDate = date("Y-m-d H:i:s", strtotime($common->convertTime($fromDt, DEFAULT_TIMEZONE)));
    }

    if (strlen($EDt) > 0) {
        //$toDt = date("Y-m-d", strtotime($EDt)) . ' 23:59:59';
        $toDt = $common->convertTime($EDt . ' 23:59:59', DEFAULT_TIMEZONE);
        $todayDate = date("Y-m-d H:i:s", strtotime($toDt));
    }
    $eventType = $_POST['eventType'];
    $userData = $_POST['userData'];
    $SDt = str_replace('-', '/', $SDt);
    $EDt = str_replace('-', '/', $EDt);
}
$userQry = '';
$finalArr = array();
if ($userData != '') {
    $userQry = " and (u.email='" . $userData . "' or u.id='" . $userData . "')";
    $from = " and date(e.startdatetime)>='" . $yesterdayDate . "'";
    if ($eventType == 'all') {
        $to = " and (e.enddatetime)<='" . $todayDate . "'";
    } elseif ($eventType == 'current') {
        //$to = " and (e.enddatetime)>='" . $common->convertTime(date('Y-m-d H:i:s'), DEFAULT_TIMEZONE) . "' and date(e.enddatetime)<='" . $todayDate . "'";
        $to = " and (e.enddatetime)>='" . $common->convertTime(date('Y-m-d H:i:s'), DEFAULT_TIMEZONE) . "'";
    } else {
        $to = " and (e.enddatetime)<'" . $common->convertTime(date('Y-m-d H:i:s'), DEFAULT_TIMEZONE) . "'";
    }
    $selectEvents = "SELECT e.id 'eventid',e.ownerid,e.title,u.id 'userid',u.company,u.email,e.url,c.name 'cityname',cg.name 'categoryname',u.cityid FROM event e LEFT JOIN user u ON u.id=e.ownerid LEFT JOIN city c ON c.id=e.cityid LEFT JOIN category cg ON cg.id=e.categoryid where 1 and e.deleted=0 " . $from . $to . $userQry;
    $resEvents = $Globali->SelectQuery($selectEvents);
    $eventIds = array();
    $cityId = 0;
    $userId = 0;
    if (count($resEvents) > 0) {
        for ($i = 0; $i < count($resEvents); $i++) {
            $userId = $resEvents[$i]['ownerid'];
            $finalArr[$userId]['eventData'][$resEvents[$i]['eventid']]['title'] = $resEvents[$i]['title'];
            $finalArr[$userId]['eventData'][$resEvents[$i]['eventid']]['url'] = $resEvents[$i]['url'];
            if (!empty($resEvents[$i]['cityname']) && !in_array($resEvents[$i]['cityname'], $finalArr[$userId]['eventcities'])) {
                $finalArr[$userId]['eventcities'][] = $resEvents[$i]['cityname'];
            }
            if (!in_array($resEvents[$i]['categoryname'], $finalArr[$userId]['eventcategories'])) {
                $finalArr[$userId]['eventcategories'][] = $resEvents[$i]['categoryname'];
            }
            $finalArr[$userId]['userData']['company'] = $resEvents[$i]['company'];
            $finalArr[$userId]['userData']['email'] = $resEvents[$i]['email'];

            $eventIds[] = $resEvents[$i]['eventid'];
            $cityId = $resEvents[$i]['cityid'];
        }
    } else {
        $_SESSION['nodata'] = true;
    }
    //echo "<pre>";
    //var_dump($cityId);exit;
    if ($cityId > 0) {
        $cityQry = "select name from city where id=" . $cityId . " Limit 1";
        $resCity = $Globali->SelectQuery($cityQry);
    }
    if (count($resCity) > 0) {
        $finalArr[$userId]['userData']['cityname'] = $resCity[0]['name'];
    }
    if (count($eventIds) > 0) {
        $selectQry = "SELECT cy.code,SUM(es.quantity) 'totalqty',SUM(es.totalamount) 'totalamount' FROM eventsignup es INNER JOIN currency cy ON cy.id=es.fromcurrencyid WHERE es.transactionstatus='success' and es.paymentstatus NOT IN ('Refunded','Canceled') and es.eventid IN(" . implode(',', $eventIds) . ") GROUP BY es.eventid,cy.id";
        $resQry = $Globali->SelectQuery($selectQry);

        for ($j = 0; $j < count($resQry); $j++) {
            $finalArr[$userId]['totalqty']+=$resQry[$j]['totalqty'];
            if (!empty($resQry[$j]['code'])) {
                $finalArr[$userId]['totalamount'][$resQry[$j]['code']]+=$resQry[$j]['totalamount'];
            }
        }
    }
//    echo "<pre>";
//    print_r($finalArr);
//    exit;
}


include 'templates/search_by_organizer_tpl.php';
?>