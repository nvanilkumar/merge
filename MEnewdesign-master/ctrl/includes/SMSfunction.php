<?php

include_once("MT/cGlobali.php");

$Globali = new cGlobali();

function functionSendSMS($Mobile, $Message, $RtrnMsg) {
    if ($_SERVER['HTTP_HOST'] == "www.meraevents.com") {
        $ch = curl_init();
        $user = "srinivasrp@cbizsoftindia.com:Mera123";
        $receipientno = $Mobile;
        $senderID = "MERAEN";
        $msgtxt = $Message;
        curl_setopt($ch, CURLOPT_URL, "http://api.mVaayoo.com/mvaayooapi/MessageCompose");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "user=$user&senderID=$senderID&receipientno=$receipientno&msgtxt=$msgtxt");
        $buffer = curl_exec($ch);
        if (empty($buffer)) { //echo " buffer is empty "; 
        } else { //echo $buffer; 
        }
        curl_close($ch);

        /* $url = "http://sms.bulkalerts.info/sendurlcomma.asp?user=20014038&pwd=gi64au&senderid=meraevnt&mobileno=".$Mobile."&msgtext=".$Message;
          $url = "http://api.mvaayoo.com/mvaayooapi/MessageCompose?user=srinivasrp@cbizsoftindia.com:Mera123&senderID=MeraEvnt&receipientno=".$Mobile."&msgtxt=".$Message;

          echo '<iframe src="'.$url.'" width="1" height="1" style="display:none;"></iframe>';
          if($RtrnMsg==1)
          {
          echo '<font color="#006600">SMS Sent</font>';
          } */
    }
}

//function to send SMS & Email when user Register
function sendSignupSMSandEmail($UserDetails) {
    $Globali = new cGlobali();
    //SEND SMS
    $Mobile_No = preg_replace("/[^0-9]/i", "", $UserDetails['Mobile_new']);
    $Mobile_No = (strlen(trim($UserDetails['Mobile_new'])) < 1) ? "--" : $UserDetails['Mobile_new'];

    $selSMSMsgs = "SELECT Id, Msg FROM SMSMsgs WHERE MsgType ='smRegn'";
    $dtlSMSMsgs = $Globali->SelectQuery($selSMSMsgs);

    $SMSMsgId = $dtlSMSMsgs[0]['Id'];


    $Message = str_replace("uname", $UserDetails['UserName'], $dtlSMSMsgs[0]['Msg']);
    $Message = str_replace("pwd", $UserDetails['Password'], $Message);

    $RtrnMsg = 0;
    functionSendSMS($Mobile_new, $Message, $RtrnMsg);
    $sUserId = $_SESSION['uid'];
    $sSMSMsgId = $SMSMsgId;
    $sSentDt = date('Y-m-d h:i:s');

    $sqlInsertSMSSent = "INSERT INTO SMSSent (UserId, SMSMsgId, SentDt) VALUES (?,?,?)";

    $SMSSentStmt = $Globali->dbconn->prepare($sqlInsertSMSSent);
    $SMSSentStmt->bind_param("dis", $sUserId, $sSMSMsgId, $sSentDt);
    $SMSSentStmt->execute();
    $SMSSentStmt->close();
    //End of SMS Sent
    //SEND MAIL
    $to = $UserDetails['Email'];
    $subject = 'Account details for ' . $UserDetails['fullname'] . ' as Delegate at meraevents.com';
    //$window_url ="<form action='http://www.meraevents.com/ChangePassword.php' method='post'><input type=hidden value='Delegate' name='UserType' /><input type=hidden value='".$UserIdi."' name='uid' /><input type=submit name=submit value='Click here to change your default password' /></form>";
    $window_url = "<a href=" . _HTTP_SITE_ROOT . "/dashboard-changepassword?UserType=Delegate&auth_code=" . $UserDetails['auth_code'] . "&uid=" . $sUserId . ">Click here to change your default password</a>";
    $selEMailMsgs = "SELECT Id, Msg, MsgType, SendThruEMailId FROM EMailMsgs WHERE MsgType ='emRegn'";
    $dtlEMailMsgs = $Globali->SelectQuery($selEMailMsgs);

    $EMailMsgId = $dtlEMailMsgs[0]['Id'];
    $LastName = '';
    $Message = str_replace("FirstName", $UserDetails['fullname'], $dtlEMailMsgs[0]['Msg']);
    $Message = str_replace("LastName", $LastName, $Message);
    $Message = str_replace("UserNane", $UserDetails['Email'], $Message);
    $Message = str_replace("RandomPassword1", $window_url, $Message);
    $Message = str_replace("EmailID", $UserDetails['Email'], $Message);


    $cc = $replyto = $conent = $filename = NULL;

    $bcc = 'sunila@meraevents.com';
    $from = $dtlEMailMsgs[0]['SendThruEMailId'];

    $commonFunctions = new functions();
    $commonFunctions->sendEmail($to, $cc, $bcc, $from, $replyto, $subject, $Message, $conent, $filename);



    //$sUserId = $_SESSION['uid'];
    $sEMailMsgId = $EMailMsgId;
    $sSentDt = date('Y-m-d h:i:s');

    $sqlInsEMailSent = "INSERT INTO EMailSent (UserId, EMailMsgId, SentDt) VALUES (?,?,?)";

    $EMailSentStmt = $Globali->dbconn->prepare($sqlInsEMailSent);
    $EMailSentStmt->bind_param("dis", $sUserId, $sEMailMsgId, $sSentDt);
    $EMailSentStmt->execute();
    $EMailSentStmt->close();

    //MAIL SENT
}

?>