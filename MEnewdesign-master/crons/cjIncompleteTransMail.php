<?php
include_once("commondbdetails.php");
include_once("../ctrl/MT/cGlobali.php");
$cGlobali = new cGlobali();
include_once '../ctrl/includes/common_functions.php';
$commonFunctions = new functions();
$_GET = $commonFunctions->stripData($_GET);
$_POST = $commonFunctions->stripData($_POST);
$_REQUEST = $commonFunctions->stripData($_REQUEST);
if ($_GET['runNow'] == 1) {
    //$db_conn = mysqli_connect($DBServerName, $DBUserName, $DBPassword, $DBIniCatalog);

    $transQry = "SELECT 
                    es.id,
                    ol.orderid,
                    CONVERT_TZ(es.signupdate, 'GMT', 'Asia/Kolkata'),
                    u.name,
                    u.email,
                    e.title,
                    e.url
                FROM
                    eventsignup as es
                        INNER JOIN
                    orderlog as ol ON ol.eventsignup = es.id
                        INNER JOIN
                    user u ON u.id = es.userid
                        INNER JOIN
                    event e ON e.id = es.eventid
                WHERE
                    es.signupdate BETWEEN DATE_SUB(NOW(), INTERVAL 2 HOUR) and DATE_SUB(NOW(), INTERVAL 1 HOUR)
                        and es.paymentstatus = 'NotVerified'
                        and es.totalamount != 0
                        and es.transactionstatus = 'pending'
                group by es.eventid , es.userid
                order by es.id desc;";
    $resQry = $cGlobali->justSelectQuery($transQry);
    //print_r($resQry);exit;
    $totalRecords = mysqli_num_rows($resQry);
    if ($totalRecords > 0) {
        $selectTemplate = "SELECT template,fromemailid FROM  messagetemplate where mode='email' and type='incompletetomakesuccess' order by id desc limit 1";
        $resSelectTemplate = $cGlobali->SelectQuery($selectTemplate);
    }
    $bcc = $replyto = $cc = $content = $filename = NULL;
    if (count($resSelectTemplate) > 0) {
        $fromEmailId = $resSelectTemplate[0]['fromemailid'];
        $parentTemplate = $resSelectTemplate[0]['template'];
        //print_r($template);
        while ($record = mysqli_fetch_array($resQry, MYSQLI_ASSOC)) {
            $template=$parentTemplate;
            $firstName = $record['name'];
            $email = $record['email'];
			echo $email."<br>";
            $esId = $record['id'];
            $orderId = $record['orderid'];
            $eventTitle = $record['title'];
            $eventURL = _HTTP_SITE_ROOT . '/event/' . $record['url'];
            $subject = 'You tried to book tickets for ' . $eventTitle . ' Reg. no:' . $esId;
            $link = _HTTP_SITE_ROOT . '/login?redirect_url=payment?orderid=' . $orderId . '&incomplete=true';
            $supportLink = _HTTP_SITE_ROOT.'/support';
            $template = str_ireplace('{USER_FIRST_NAME}', $firstName, $template);
            //{EVENT_URL}
            $template = str_ireplace('{EVENT_TITLE}', $eventTitle, $template);
            $template = str_ireplace('{EVENT_URL}', $eventURL, $template);
            $template = str_ireplace('{REGISTRATION_LINK}', $link, $template);
            $template = str_ireplace('{SUPPORT_LINK}', $supportLink, $template);
            //print_r($template);
            $status = $commonFunctions->sendEmail($email, $cc, $bcc, $fromEmailId, $replyto, $subject, $template, '', $filename);
            print_r($status);
        }
    }
}
?>
