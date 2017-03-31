<?php

session_start();
unset($_SESSION['status']);
include 'loginchk.php';

include_once("MT/cGlobali.php");
include_once("includes/common_functions.php");
include_once 'MT/cEventSignup.php';
$Globali = new cGlobali();
$commonFunctions = new functions();
$esid = isset($_POST['esid']) ? $_POST['esid'] : '';
$delEmail =$_POST['delEmail'];
$eventsignupDtls =$Title=$Vh1GOATktQty=$Vh1GOASelTkt=null;
if (!empty($esid) && $esid > 0) {
    $eventsignupDtls = new cEventSignup($esid);
    $eventsignupDtls->Load();
}
if ($_REQUEST['sendRegMails'] == 'Submit') {
   if ($eventsignupDtls->EventId == $Vh1GOAEventID) {
        $selESTD = "SELECT estd.ticketid,e.title AS Title,estd.ticketquantity AS NumOfTickets "
                . "FROM eventsignupticketdetail estd "
                . "INNER JOIN eventsignup es ON es.id=estd.eventsignupid "
                . "INNER JOIN event e ON e.id=es.eventid "
                . "WHERE es.id=" . $esid;
        $resESTD = $Globali->SelectQuery($selESTD, MYSQLI_ASSOC);
        
        if (count($resESTD) > 0) {
            $Vh1GOASelTkt = $resESTD[0]['ticketid'];
            $Title=stripslashes($resESTD[0]['Title']);
            $Vh1GOATktQty = $resESTD[0]['NumOfTickets'];
        }
        if (in_array($Vh1GOASelTkt, $Vh1GOATktIds)) {
           $filename = $data =NULL;
            $attendName= "";
            //$Globali->GetSingleFieldValue("select Name from Attendees where EventSIgnupId='".$Globali->dbconn->real_escape_string($esid)."'");
            $message3 = Vh1GoaMailContent();
            $message3 = str_replace("Vh1GOARegId", $esid, $message3);
            $message3 = str_replace("Vh1GOARegName", $attendName, $message3);
            //$message3=  str_replace('DEL_FIRST_NAME', $attendName, $message3);
            $Vh1GOATktName = strip_tags($Globali->GetSingleFieldValue("SELECT Name FROM tickets WHERE Id=" . $Globali->dbconn->real_escape_string($Vh1GOASelTkt)));
            $message3 = str_replace("Vh1GOATktName", $Vh1GOATktName, $message3);
            $message3 = str_replace("Vh1GOATktQty", $Vh1GOATktQty, $message3);
            $from = 'MeraEvents<admin@meraevents.com>';
            $bcc = 'support.2@meraevents.com';$replyto=$content=$filename=NULL;
            $subject = 'You have successfully registered for '.stripslashes($Title).' - '.$esid;
            if ($eventsignupDtls->EventId == $Vh1GOAEventID && in_array($Vh1GOASelTkt, $Vh1GOATktIds)) {
                $bcc = ",durgeshmishra2525@gmail.com";
            }
            //print_r($message3);
            $stat= $commonFunctions->sendEmail($delEmail, $cc, $bcc, $from, $replyto, $subject, $message3, $content, $filename);
            //var_dump($stat);
            $_SESSION['status']="Email sent successfully to ".$delEmail." !!!";
            unset($esid);
            unset($delEmail);
        } else {
            $_SESSION['status'] = "Sorry,this registration number is not of particular ticket of vh1 supersonic event";
        }
    } else{
        $_SESSION['status'] = "Sorry,this registration number not related to vh1 supersonic event";
    }
}

function Vh1GoaMailContent() {
    $emailContent = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MeraEvents.com</title>
</head>

<body>
<table width="650" border="0" cellspacing="0" cellpadding="0" align="center" style="background:#fef7ed; border:5px solid #dbdbdb;">
  <tr>
    <td>
    	
        	<table width="560" border="0" cellspacing="0" cellpadding="0" align="center" style="background:#f5826d; padding:5px 0; margin:40px auto 0; box-shadow:0px 2px 0px #999; ">
              <tr>
                <td>
               	  <p style="background:#f2e3ac; letter-spacing:0.03em; text-align:center; font-size:22px; font-weight:bold; font-family:Georgia, \'Times New Roman\', Times, serif; color:#333; padding:10px 0; margin:20px 0;"> Vh1 Supersonic Goa 2015</p>
                </td> 
              </tr>
            </table>

    
    
    </td>
  </tr>
  <tr>
    <td><table width="560" border="0" cellspacing="0" cellpadding="0" align="center">
              <tr>
                <td>
               	  <p style="font-size:16px; letter-spacing:0.03em; font-weight:normal; line-height:24px; font-family:Georgia, \'Times New Roman\', Times, serif; color:#000; padding:10px 0; margin:20px 0 5px 0;">
                   <span style="width:100%; float:left; margin:0 0 15px 0;">Hi,</span>
                   <span style="width:100%; float:left; margin:0 0 24px 0;">Congratulations on pre-blocking your ticket  for Vh1Supersonic Goa 2015.</span>
				   <span style="width:100%; float:left; margin:0 0 24px 0;">You can pay the remainder of the balance (80 percent) on or before September 15, 2015 using the unique link that will be sent to your email.  Failure to comply and pay the remaining 80 percent on or before September 15, 2015 will not result in a refund. </span>
					<span style="width:100%; float:left; margin:0 0 20px 0;"><span style="color:#f60; font-weight:bold;">Please note, </span> this is not an  e-ticket. It is just a confirmation of the advance payment that we have received to pre-block your ticket. The e-ticket will be sent to you upon payment of the final amount.  The ticket is neither transferable nor refundable.</span>
                   </p>
                </td> 
              </tr>
            </table></td>
  </tr>
  <tr>
    <td>
	
	<table width="560" border="0" cellspacing="0" cellpadding="0" align="center">
              <tr>
                <td>
               	 <p style="font-size:16px; letter-spacing:0.03em; font-weight:normal; line-height:24px; font-family:Georgia, \'Times New Roman\', Times, serif; color:#000; padding:15px 0 10px 0; margin:0px 0 5px 0; border-bottom:1px solid #333; border-top:1px solid #333; float:left; width:100%;">
                
                   <span style="width:100%; float:left; margin:0 0 14px 0; font-weight:bold; color:#f60;">Registration No : Vh1GOARegId</span>
				   <span style="width:100%; float:left; margin:0 0 14px 0; font-weight:bold; color:#f60;">Name : Vh1GOARegName</span>
                   <span style="width:50%; float:left; margin:0 0 14px 0; font-weight:bold; color:#f60;">Ticket Type : Vh1GOATktName</span>
				   <span style="width:50%; float:left; margin:0 0 14px 0; font-weight:bold; color:#f60;">Qty : Vh1GOATktQty</span>
                   </p>
                </td> 
              </tr>
            </table>
	
    
    	
    
    </td>
  </tr>
  
  
    <tr>
    <td>
    
    	<table width="560" border="0" cellspacing="0" cellpadding="0" align="center">
              <tr>
                <td>
               	 <p style="font-size:18px; letter-spacing:0.03em; font-weight:normal; line-height:24px; font-family:Georgia, \'Times New Roman\', Times, serif; color:#000; padding:10px 0 10px 0; margin:0px 0 5px 0;">
                
                   <span style="width:100%; float:left; margin:0 0 14px 0; font-weight:normal; color:#000;">For any Queries Call us on : +91-9396 555 888 <br />email us @ <a href="mailto:support@meraevents.com" style="color:#f60;">support@meraevents.com</a></span>
                   </p>
                </td> 
              </tr>
            </table>
    
    </td>
  </tr>
  
  
  
  
  
  <tr>
    <td style="border-top:4px solid #cbc6be; margin: 20px 0 0 0; float: left; width: 100%;">&nbsp;</td>
  </tr>


  <tr>
  	<td>
    	<table width="560" border="0" cellspacing="0" cellpadding="0" align="center">
		  <tr>
		    <td align="left" width="70%" style="font-size:14px; font-style:italic; color:#333; line-height:20px;">&copy; 2015, Versant Online Solutions Pvt.Ltd, All Rights Reserved.<br />
            2nd Floor, 3 Cube Towers, Whitefield Road, Kondapur, Hyderabad, Andhra Pradesh - 500084</td>
             <td align="right" width="30%"><a href="http://www.meraevents.com" target="_blank"><img src="http://www.meraevents.com/download/MeaEvents_Logo.png" border="0" width="112" height="71" /></a></td>
    	  </tr>
        </table>
    </td>
  </tr>

  <tr>
    <td style="height:20px;">&nbsp;</td>
  </tr>

  
  
</table>

</body>
</html>
';

    return $emailContent;
}

include_once './templates/send_vh1_regemail_tpl.php';
