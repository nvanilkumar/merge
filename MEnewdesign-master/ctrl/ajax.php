<?php

@session_start();
include 'loginchk.php';

include_once("MT/cGlobali.php");
include_once 'includes/common_functions.php';
$Global = new cGlobali();
$commonFunctions = new functions();
// checking for event ID, whether exist or not 
if (isset($_REQUEST['eventIDChk'])) {
    $eventid = trim($_REQUEST['eventid']);

    $sql = 'select `Id` from `events` where `Id`=' . $eventid;
    //echo $sql;
    $data = $Global->GetSingleFieldValue($sql);

    if (strlen($data) > 0) {
        $sql2 = 'select `Id` from `seotypes` where `eventId`=' . $eventid;
        $data2 = $Global->GetSingleFieldValue($sql2);
        if (strlen($data2) > 0) {
            echo "addSEO.php?eventid=" . $eventid . "&edit=" . $data2['Id'];
        } else {
            echo "addSEO.php?eventid=" . $eventid;
        }
    } else {
        echo "error";
    }
}
// deleting a SEO entry
elseif (isset($_POST['delSEO'])) {
    $delid = $_POST['delid'];
    $sql = "delete from `seotypes` where `Id`='" . $delid . "'";
    $Global->ExecuteQuery($sql);
}
if (isset($_POST['call'])) {
    switch (trim($_POST['call'])) {
        case 'getStates':
            $countryId = $_POST['countryId'];
            $res = $Global->SelectQuery("SELECT `id`,`name` "
                    . "FROM state "
                    . "WHERE `countryid` = '" . $countryId . "' "
                    . "AND `status` = 1 AND `deleted` = 0 "
                    . "AND `name` NOT LIKE '%Not from%' "
                    . "ORDER BY `name`");
            if (count($res) > 0) {
                $data = "";
                $TotalStateQueryRES = count($res);
                $data .= '<option value="0" >-Select state-</option>';
                for ($i = 0; $i < $TotalStateQueryRES; $i++) {
                    $data .= '<option value="' . $res[$i]['id'] . '" >' . $res[$i]['name'] . '</option>';
                }
                echo $data;
            } else
                echo "ERROR!";
            break;
        case 'getCities' :
            $stateId = $_POST['stateId'];
            $res = $Global->SelectQuery("SELECT c.id, c.name "
                    . "FROM city c JOIN statecity sc ON c.id = sc.cityid "
                    . "WHERE sc.stateid = '" . $stateId . "' "
                    . "AND c.name NOT LIKE '%Other%' "
                    . "AND c.name NOT LIKE '%Not from%' "
                    . "AND c.status = 1 AND c.deleted = 0 "
                    . "ORDER BY `name`");
            if (count($res) > 0) {
                $data = "";
                $TotalCityQueryRES = count($res);
                $data .= '<option value="0" >-Select city-</option>';
                for ($i = 0; $i < $TotalCityQueryRES; $i++) {
                    $data .= '<option value="' . $res[$i]['id'] . '" >' . $res[$i]['name'] . '</option>';
                }
                echo $data;
            } else {
                echo "ERROR!";
            }
            break;
        case 'getCountyCities' :
            $countryId = $_POST['countryId'];
            $res = $Global->SelectQuery("SELECT id, name FROM city WHERE countryid = '" . $countryId . "' AND featured=1 AND (`name` NOT LIKE '%Other%' AND `name` NOT LIKE '%Not From%')AND status = 1 AND deleted = 0 ORDER BY `name`");
            if (count($res) > 0) {
                $data = "";
                $TotalCityQueryRES = count($res);
                $data .= '<option value="">Select City</option>';
                $data .= '<option value="All">All cities</option>';
                for ($i = 0; $i < $TotalCityQueryRES; $i++) {
                    $data .= '<option value="' . $res[$i]['id'] . '" >' . $res[$i]['name'] . '</option>';
                }
                $data .= '<option value="Other">Other cities</option>';
                echo $data;
            } else {
                echo "ERROR!";
            }
            break;
        case 'isEventIdExists':
            $eventId = $_POST['event_id'];
            $res = $Global->SelectQuery("SELECT id FROM event WHERE deleted=0 and id='" . $eventId . "'");
            $data['status'] = false;
            if (count($res) > 0) {
                $data['status'] = true;
            }
            echo json_encode($data);
            break;
        case 'emailOrg_Promotions':
            $eventId = $_POST['eventId'];
            $selQry = "SELECT e.OEmails,u.EMail,p.promotionMedium,p.promotionURL,e.Title,u.FirstName as Name,e.Id EventId,e.URL,u.Id as UserId,p.status,p.comments FROM promotions p INNER JOIN events e ON e.Id=p.EventId INNER JOIN user u ON u.Id=e.UserID WHERE EventId='" . $eventId . "' order by p.promotionMedium";
            $promotions = $Global->SelectQuery($selQry);
            $cntPromos = count($promotions);
            if ($cntPromos > 0) {
                $resEmailPromo = $Global->SelectQuery("SELECT * FROM EMailMsgs WHERE MsgType='emailPromotions'");
                $message = $resEmailPromo[0]['Msg'];
                $to = $promotions[0]['EMail'];
                $cc = $promotions[0]['OEmails'];
                $from = $resEmailPromo[0]['SendThruEMailId'];
                $data = '     <table cellspacing="0" cellpadding="0" border="1" class="stdtable">
            <colgroup>
                <col class="con0" width="50px" align="center" />
                <col class="con0" width="200px" align="center"/>
                <col class="con0" width="310px" align="center"/>
            </colgroup>
            <thead>
                <tr>
                    <th class="head1" style="padding: 5px;">S.No</th>
                    <th class="head1" style="padding: 5px;">Promotion Medium</th>
                    <th class="head1" style="padding: 5px;">Promotion Link</th>
                    <th class="head1" style="padding: 5px;">Remarks</th>
               </tr>
            </thead>
            <tbody>';
                $cnt = 1;
                $detailsArray = array();
                $sameMed = array();
                $comments = array();
                for ($i = 0; $i < $cntPromos; $i++) {
                    array_push($sameMed, $promotions[$i]['promotionURL']);
                    array_push($comments, $promotions[$i]['comments']);

                    if ($promotions[$i]['promotionMedium'] != $promotions[$i + 1]['promotionMedium']) {
                        $detailsArray[$promotions[$i]['promotionMedium']]['link'] = $sameMed;

                        $detailsArray[$promotions[$i]['promotionMedium']]['comments'] = $comments;
                        $sameMed = array();
                        $comments = array();
                    }
                }
                foreach ($detailsArray as $medium => $links) {
                    $data.='<tr>
                    
                    <td style="text-align: center;padding: 5px;" ><strong>' . $cnt++ . '</strong></td>
                    <td style="text-align: center;padding: 5px;"><strong>' . $medium . '</strong></td>
                    <td style="text-align: center;padding: 5px;">
                    
                    
                    ';
                    foreach ($links['link'] as $k => $link) {
                        $url_data.='<a href="' . $link . '" target="_blank" style="text-decoration:none; color:#f60; font-size:13px">' . $link . '</a><br/>';
                        $comments_data.= $links["comments"][$k] . '<br/>';
                    }
                    $data.=$url_data . '</td><td>' . $comments_data . '</td>
                </tr>';
                }

                $data.='</tbody>
        </table>';
                $eventLink = '<a href="' . _HTTP_SITE_ROOT . '/event/' . $promotions[0]['URL'] . '" target="_blank" style="color:#000;text-decoration:none;font-weight:600">' . $promotions[0]['Title'] . '</a>';
                $text = 'The following are the Activities done by Meraevents for your event:';
                $message = str_replace('ORG_NAME', $promotions[0]['Name'], $message);
                $message = str_replace('PROMOTIONS_CONTENT', $text, $message);
                $message = str_replace('EVENT_NAME', $eventLink, $message);
                $message = str_replace('EVENT_ID', $promotions[0]['EventId'], $message);
                $message = str_replace('PROMOTIONS_LIST', $data, $message);
                $link = _HTTP_SITE_ROOT . '/Login?refurl=event-promotions?EventId=' . $promotions[0]['EventId'];
                $message = str_replace('VIEW_PROMOTIONS', $link, $message);
                $replyto = $bcc == NULL;
                $title = $promotions[0]['Title'];
                $subject = 'Activities done by Meraevents to ' . $title;
                $status = $commonFunctions->sendEmail($to, $cc, $bcc, $from, $replyto, $subject, $message);
                if ($status) {
                    $date = date('Y-m-d H:i:S', strtotime('now'));
                    $insSent = "INSERT EMailSent(UserId,EMailMsgId,SentDt) VALUES('" . $promotions[0]['UserId'] . "','" . $resEmailPromo[0]['Id'] . "','" . $date . "')";
                    $Global->ExecuteQuery($insSent);
                    echo 'Mail Sent Successfully';
                } else {
                    echo 'Mail sending failed';
                }
            } else {
                echo 'No promotions.Email not sent.';
            }
            ;
            break;
        case 'unpublishEvent':
            $data = array();
            $data['status'] = 'Invalid';
            if (isset($_POST['EventId'])) {
                $eventId = $_POST['EventId'];
                $selEve = "SELECT id,status,startdatetime FROM event WHERE deleted=0 and id='" . $eventId . "'";
                $resEve = $Global->SelectQuery($selEve);
                if (count($resEve) > 0) {
                    $val = $resEve[0]['status'] ^ 1;
                    /* $check_valid=true;
                      if($val==1){
                      $curr_date=date('Y-m-d H:i:s');
                      $check_valid=$resEve[0]['StartDt']>$curr_date?true:false;
                      } */
                    $solrData = array();
                    $solrData['eventId'] = $eventId;
                    $solrData['status'] = $val;
                    $solrData['keyValue'] = $_SESSION['uid'];
                    $solrData['updatetype'] = 'eventstatus';
                    $solrUrl = "/api/event/solrEventStatus";


                    $solrStatus = $commonFunctions->makeSolrCall($solrData, $solrUrl);
                    $solrStatusResponse = json_decode($solrStatus, true);
                    //echo 'output is   <pre>'; print_r($solrStatusResponse);
                    if ($solrStatusResponse['response']['statusUpdated'] == 'Success') {
                        $updateQry = "UPDATE event SET status=" . $val . ",modifiedby=" . $_POST['adminId'] . " WHERE id='" . $eventId . "'";
                        $Global->ExecuteQuery($updateQry);
                        $data['status'] = "Successfully unpublished event";
                        if ($val == 1) {
                            $data['status'] = "Successfully published event";
                        }
                    } else {
                        $data['status'] = 'Something went wrong, please try again';
                    }
                } else {
                    $data['status'] = 'Already event is unpublished';
                }
            }
            echo json_encode($data);
            break;
        case 'updateNoTck':
            $data = array();
            $data['status'] = 'Invalid';
            if (isset($_POST['EventId'])) {
                $eventId = $_POST['EventId'];
                $selEve = "SELECT id,ticketsoldout FROM event WHERE deleted=0 and id='" . $eventId . "'";
                $resEve = $Global->SelectQuery($selEve);

                if (count($resEve) > 0) {
                    $val = 0;
                    if ($resEve[0]['ticketsoldout'] == 0) {
                        $val = 1;
                    }
                    $solrData = array();
                    $solrData['eventId'] = $eventId;
                    $solrData['ticketSoldout'] = $val;
                    $solrData['keyValue'] = $_SESSION['uid'];
                    $solrData['updatetype'] = 'ticketstatus';
                    $solrUrl = "/api/event/solrEventStatus";

                    $solrStatus = $commonFunctions->makeSolrCall($solrData, $solrUrl);
                    $solrStatusResponse = json_decode($solrStatus, true);
                    if ($solrStatusResponse['response']['updatedTicketSoldout'] == 'Success') {
                        $updateQry = "UPDATE event SET ticketsoldout=" . $val . ",modifiedby=" . $_POST['adminId'] . " WHERE id='" . $_POST['EventId'] . "'";
                        $Global->ExecuteQuery($updateQry);
                        $data['status'] = "Successfully updated no ticket value";
                    } else {
                        $data['status'] = 'Something went wrong, please try again';
                    }
                } else {
                    $data['status'] = 'Invalid EventId';
                }
            }
            echo json_encode($data);
            break;
        case 'taxonbaseprice':
            $data = array();
            $data['status'] = 'false';
            if (isset($_POST['EventId'])) {
                $eventId = $_POST['EventId'];
                $adminId = $_POST['adminId'];
                $selEve = "SELECT calculationmode FROM eventsetting WHERE eventid='" . $eventId . "'";
                $resEve = $Global->SelectQuery($selEve);

                if (count($resEve) > 0) {
                    $val = 'onbaseprice';
                    if ($resEve[0]['calculationmode'] == 'onbaseprice') {
                        $val = 'ontaxedprice';
                    }
                    $updateQry = "UPDATE eventsetting SET calculationmode='" . $val . "',modifiedby=" . $adminId . " WHERE eventid='" . $eventId . "'";
                    $Global->ExecuteQuery($updateQry);
                    $data['status'] = 'true';
                    $data['response']['total'] = 1;
                    $data['response']['messages'][] = "Successfully updated your setting!!!";
                } else {
                    $data['status'] = 'false';
                    $data['response']['total'] = 0;
                    $data['response']['messages'][] = 'Invalid EventId';
                }
            }
            echo json_encode($data);
            break;
        case 'discountaftertax':
            $data = array();
            $data['status'] = 'false';
            if (isset($_POST['EventId'])) {
                $eventId = $_POST['EventId'];
                $adminId = $_POST['adminId'];
                $selEve = "SELECT discountaftertax FROM eventdetail WHERE eventid='" . $eventId . "'";
                $resEve = $Global->SelectQuery($selEve);

                if (count($resEve) > 0) {
                    $val = '1';
                    if ($resEve[0]['discountaftertax']) {
                        $val = '0';
                    }
                    $updateQry = "UPDATE eventdetail SET discountaftertax='" . $val . "',modifiedby=" . $adminId . " WHERE eventid='" . $eventId . "'";
                    $Global->ExecuteQuery($updateQry);
                    $data['status'] = 'true';
                    $data['response']['total'] = 1;
                    $data['response']['messages'][] = "Successfully updated your setting!!!";
                } else {
                    $data['status'] = 'false';
                    $data['response']['total'] = 0;
                    $data['response']['messages'][] = 'Invalid EventId';
                }
            }
            echo json_encode($data);
            break;
        case 'updateExtraChargeStatus':
            $data = array();
            $data['status'] = 'false';
            $id = $_POST['id'];
            $status = $_POST['status'];
            $updateQry = "UPDATE eventextracharge SET status='" . $status . "',modifiedby=" . $_SESSION['uid'] . " WHERE id='" . $id . "'";
            $updStatus = $Global->ExecuteQuery($updateQry);
            if ($updStatus) {
                $data['status'] = 'true';
                $data['response']['total'] = 1;
                $data['response']['messages'][] = "Status updated successfully!!!";
            } else {
                $data['status'] = 'false';
                $data['response']['total'] = 0;
                $data['response']['messages'][] = "Updation failed!!!";
            }
            echo json_encode($data);
            break;
        case 'updateSendFeedback':
            $data = array();
            $data['status'] = 'Invalid';
            if (isset($_POST['EventId'])) {
                $eventId = $_POST['EventId'];
                $selEve = "SELECT eventid,sendfeedbackemails FROM eventsetting WHERE eventid='" . $eventId . "'";
                $resEve = $Global->SelectQuery($selEve);
                if (count($resEve) > 0) {
                    $val = $resEve[0]['sendfeedbackemails'] ^ 1;
                    $updateQry = "UPDATE eventsetting SET sendfeedbackemails=" . $val . ",modifiedby=" . $_POST['adminId'] . " WHERE eventid='" . $eventId . "'";
                    $Global->ExecuteQuery($updateQry);
                    $data['status'] = "Successfully de-activated send feedback emails";
                    if ($val == 1) {
                        $data['status'] = "Successfully activated send feedback emails";
                    }
                } else {
                    $data['status'] = 'Invalid EventId';
                }
            }
            echo json_encode($data);
            break;
        case 'addOrUpdateCommission':
            $data = array();
            $data['status'] = false;
            $PEventId = $_REQUEST['EventId'];
            $cardperc = $_REQUEST['cardperc'];
            $codperc = $_REQUEST['codperc'];
            $counterperc = $_REQUEST['counterperc'];
            $paypalperc = $_REQUEST['paypalperc'];
            //$mobikwikperc=$_REQUEST['mobikwikperc'];
            //$paytmperc=$_REQUEST['paytmperc'];
            $meeffortperc = $_REQUEST['meeffortperc'];
            $overall = $_REQUEST['overall'];
            $oStatus = $addOrUpSt = false;
            if ($overall != "") {
                $update_query1 = "UPDATE eventsetting SET percentage='" . $overall . "',modifiedby=" . $_POST['adminId'] . " WHERE eventid='" . $PEventId . "'";
                $oStatus = $Global->ExecuteQuery($update_query1);
            }
            if ($_REQUEST['Save'] == "Save") {
                //$update_query="UPDATE commsion SET Card='".$cardperc."',Cod='".$codperc."',Counter='".$counterperc."',Paypal='".$paypalperc."',`Mobikwik`='".$mobikwikperc."',MEeffort='".$meeffortperc."',`Paytm`='".$paytmperc."' WHERE EventId='".$PEventId."'"; 
                $updateQuery = "UPDATE commission
          SET value = 
           CASE 
           WHEN type =1 THEN '" . $cardperc .
                        "' WHEN type =2 THEN '" . $codperc .
                        "' WHEN type =3 THEN '" . $counterperc .
                        "' WHEN type =4 THEN '" . $paypalperc .
                        "' WHEN type =5 THEN '" . $cardperc .
                        "' WHEN type =6 THEN '" . $cardperc .
                        "' WHEN type =11 THEN '" . $meeffortperc . "'" .
                        " END
        " . ",modifiedby=" . $_POST['adminId'] . " where eventid=" . $PEventId;
                $addOrUpCard = $Global->ExecuteQuery($updateQuery);
            } else if ($_REQUEST['Save'] == "Add") {
                $updateqry = "insert into commission (`global`,`type`,`value`,countryid,eventid,createdby,modifiedby) values (0,1,'" . $cardperc . "',14,'" . $PEventId . "',1,1),(0,5,'" . $cardperc . "',14,'" . $PEventId . "',1,1),(0,6,'" . $cardperc . "',14,'" . $PEventId . "',1,1),(0,2,'" . $codperc . "',14,'" . $PEventId . "',1,1),(0,3,'" . $counterperc . "',14,'" . $PEventId . "',1,1),(0,4,'" . $paypalperc . "',14,'" . $PEventId . "',1,1),(0,11,'" . $meeffortperc . "',14,'" . $PEventId . "',1,1)";
                $addOrUpCard = $Global->ExecuteQuery($updateqry);

                $event_data = "Overall - " . $overall . "%<br>Card - " . $cardperc . "%<br>COD - " . $codperc . "%<br>"
                        . "Counter - " . $counterperc . "%<br>Paypal - " . $paypalperc . "%<br>ME Sales - " . $meeffortperc . "%";

                $EventsQuery = "SELECT 
                          e.title as Title, sal.email
                          FROM
                          event e
                          LEFT JOIN
                          eventsalespersonmapping ae ON ae.eventid = e.id
                          LEFT JOIN
                          salesperson sal ON sal.id = ae.salesid
                          WHERE
                          e.`id` ='" . $PEventId . "' ";
                //echo $EventsQuery;
                $EventsQueryRes = $Global->SelectQuery($EventsQuery);
                $sales_email = $EventsQueryRes[0]['email'];
                $event_title = $EventsQueryRes[0]['Title'];


                $to = $sales_email;
                $cc = 'support@meraevents.com';
                $subject = "Event '" . $event_title . "(" . $PEventId . ")' has assigned to you";
                $message = "Hi ,<br>This is to inform you that an event that named '" . $event_title . "' has been  assigned to you with the following details <br>" . $event_data;
                $from = 'MeraEvents<admin@meraevents.com>';
                $bcc = $replyto = $content = $filename = NULL;
                $commonFunctions->sendEmail($to, $cc, $bcc, $from, $replyto, $subject, $message, $content, $filename);
            }
            if ($oStatus || $addOrUpCard)
                $data['status'] = true;
            echo json_encode($data);
            break;
        case 'checkVerifiedAmt':
            $EventId = $_POST['EventId'];
            $AmountP = $_POST['AmountP'];
            $selVerAmt = "SELECT SUM(totalamount) as vAmt FROM eventsignup  where (totalamount != 0 AND (paymentmodeid=1 AND paymenttransactionid != 'A1')) and paymentstatus = 'Verified' and eventid='" . $EventId . "'";
            $ResVerified = $Global->SelectQuery($selVerAmt);
            $selPayments = "SELECT SUM(amountpaid) as aPaid FROM settlement WHERE eventid='" . $EventId . "'";
            $ResPayments = $Global->SelectQuery($selPayments);
            if (count($ResPayments) > 0 && !is_null($ResPayments[0]['aPaid'])) {
                $AmountP+=$ResPayments[0]['aPaid'];
            }
            $data = array('status' => true);
            $data['a'] = $AmountP;
            $data['ae'] = $ResVerified[0]['vAmt'];
            if (count($ResVerified) > 0 && (!is_null($ResVerified[0]['vAmt']) && $ResVerified[0]['vAmt'] > $AmountP)) {
                $data['status'] = false;
            }
            echo json_encode($data);
            break;
        case 'cancelRequest':
            $data['status'] = FALSE;
            $eventId = $_POST['EventId'];
            $cancelQuery="update `event` set `deleterequest`=0 where id=".$eventId;
            $cancelData=$Global->ExecuteQuery($cancelQuery);
            if($cancelData>0)
            {
                $data['status'] = TRUE;
            }
            echo json_encode($data);
            break;
        case 'deleteEvent':
            $EventId = $_POST['EventId'];
            $deleteRequest = $_POST['deleteRequest'];
            $modifiedBy = $_SESSION['uid'];
            $solrData = array();
            $solrData['eventId'] = $EventId;
            $query="SELECT `id` as `id`, `totalamount` as `totalamount` FROM (`eventsignup`) 
        WHERE `eventid` = ".$EventId." AND `deleted` = 0 AND `transactionstatus` = 'success' AND `paymentstatus` NOT IN ('Canceled', 'Refunded')";
            $TransactionEvents=$Global->SelectQuery($query);
            if(count($TransactionEvents)>0){
                $data['status'] = FALSE;
                $data['transCheck'] = 'error';
                echo json_encode($data);
                break;
            }
            $solrUrl = "/api/event/solrEventStatus";
            $solrData['keyValue'] = $_SESSION['uid'];
            $solrData['updatetype'] = 'deleteEvent';
            $solrStatus = $commonFunctions->makeSolrCall($solrData, $solrUrl);
            $solrStatusResponse = json_decode($solrStatus, true);
            $data['status'] = FALSE;
            if ($solrStatusResponse['response']['deleteEvent'] == 'Success') {
            if (isset($deleteRequest)) {
                $eventQuery = "select title,url,ownerid from event where deleted=0 and id=" . $EventId;
                $eventResponse = $Global->SelectQuery($eventQuery);
                $query  ="update `event` set `deleted` = '1',`deleterequest`=0 ,`modifiedby`=".$modifiedBy. " where id=".$EventId;
            } else {
                $query = "update `event` set `deleted`=1 ,`modifiedby`=" . $modifiedBy . " where id = " . $EventId;
            }
            $resDelQry = $Global->ExecuteQuery($query);
            $data['status'] = $resDelQry;
            //Sending mail to the organizer regarding confirmation
            if (isset($deleteRequest)) {
                $templateQuery = "select * from messagetemplate where type='deleteRequestConfirmation'";
                $templateResponse = $Global->SelectQuery($templateQuery);
                $userQuery = "select name,email from user where id=" . $eventResponse[0]['ownerid'];
                $userResponse = $Global->SelectQuery($userQuery);
                $to = $userResponse['0']['email'];
                $from = 'MeraEvents<admin@meraevents.com>';
                $subject = 'Your event' . $title . ' has been deleted successfully';
                //print_r($templateResponse);
                $message = $templateResponse['0']['template'];
                $message = str_replace('{organizerName}', $userResponse['0']['name'], $message);
                $message = str_replace('{eventtitle}', $eventResponse['0']['title'], $message);
                $message = str_replace('{eventid}', $EventId, $message);
                $message = str_replace('{currentYear}', date('Y'), $message);
                $message = str_replace('{supportLink}', _HTTP_SITE_ROOT."/support" , $message);
                $is_sent = $commonFunctions->sendEmail($to, $cc = '', $bcc = '', $from, $replyto = '', $subject, $message, $content = NULL, $filename = NULL, $folder = NULL);
                if ($is_sent) {
                    $data['status'] = 'Mail sent successfully';
                } else {
                    $data['status'] = 'Mail sent failed';
                }
            }
            }
            echo json_encode($data);
            break;
        case 'apiEnabling':
            $data = array();
            $data['status'] = 'Invalid';
            if (isset($_POST['EventId'])) {
                $eventId = $_POST['EventId'];
                $selEve = "SELECT id,status FROM event WHERE deleted=0 and id='" . $eventId . "'";
                $resEve = $Global->SelectQuery($selEve);

                if (count($resEve) > 0) {
                    $solrData = array();

                    $solrData['type'] = $_POST['Type'];
                    $solrData['status'] = $_POST['Status'];
                    $solrData['eventId'] = $eventId;
                    $solrData['keyValue'] = $_SESSION['uid'];
                    $solrUrl = "/api/event/solrAPIStatus";

                    $solrStatus = $commonFunctions->makeSolrCall($solrData, $solrUrl);
                    $solrStatusResponse = json_decode($solrStatus, true);
                    if ($solrStatusResponse['response']['statusUpdated'] == 'Success') {
                        if ($solrData['type'] == 1) {
                            $type = 'standardapi';
                            $status = $solrData['status'];
                        }
                        if ($solrData['type'] == 2) {
                            $type = 'mobileapi';
                            $status = $solrData['status'];
                        }
                        $updateQry = "UPDATE eventsetting SET " . $type . " = $status ,modifiedby=" . $_POST['adminId'] . " where eventid = $eventId";
                        $Global->ExecuteQuery($updateQry);
                        $data['status'] = "Successfully updated";
                    } else {
                        $data['status'] = 'Something went wrong, please try again';
                    }
                } else {
                    $data['status'] = 'No event found';
                }
            }
            echo json_encode($data);
            break;
        default:echo 'invalid';
            break;
    }
}




// function to change the status (Active/Inactive) of New Year discounts
if (isset($_POST['changeNYEDiscountStatus'])) {
    $rid = $_POST['rid'];
    $status = $_POST['status'];

    if ($status == 1) {
        $newStatus = 0;
        $newTitle = "Activate this discount";
        $anchorText = "Inactive";
        $checked = '';
    } else {
        $newStatus = 1;
        $newTitle = "Inactivate this discount";
        $anchorText = "Active";
        $checked = ' checked="checked" ';
    }

    $sqlPCode = "Update `specialdiscount`  set `status`='" . $newStatus . "', modifiedby=" . $_SESSION['uid'] . " where id=" . $rid;

    $Global->ExecuteQuery($sqlPCode);

    echo '<input type="checkbox" onclick="changeStatus(' . $rid . ',' . $newStatus . ')" title="' . $newTitle . '" ' . $checked . ' />&nbsp;(<b  style="color:#09C; font-weight:bold;" >' . $anchorText . '</b>)';
}


if (isset($_POST['delNYEdiscount'])) {
    $delid = $_POST['delid'];

    $sql = "Update `specialdiscount`  set `deleted`= 1, modifiedby=" . $_SESSION['uid'] . " where id=" . $delid;
    $Global->ExecuteQuery($sql);
}
?>