<?php
include_once("MT/cGlobali.php");
include_once("includes/common_functions.php");
include_once 'eventsignup.php';
$commonFunctions = new functions();
$_GET = $commonFunctions->stripData($_GET);
$_POST = $commonFunctions->stripData($_POST);
$_REQUEST = $commonFunctions->stripData($_REQUEST);

$Global = new cGlobali();
if ($_REQUEST['EventSignupId'] != "") {

    $SelectEvents = "SELECT e.title as Title,e.url as URL,es.quantity as Qty,es.totalamount as Fees,u.Mobile 
FROM event AS e, eventsignup AS es, user AS u WHERE e.deleted = 0 and es.id=" . $_REQUEST['EventSignupId'] . " and  e.id=es.eventid AND e.ownerid=u.id and e.enddatetime > now() ";
    $respass = $Global->SelectQuery($SelectEvents);
    $Mess = $respass[0]['Title'] . " Regno: " . $_REQUEST['EventSignupId'] . " amt: " . $respass[0]['Fees'] * $respass[0]['Qty'] . " Qty: " . $respass[0]['Qty'] . " Contact: " . $respass[0]['Mobile'] . " MeraEvents.com";
    
    $eventSignup = new eventsignup();
    $esDetails=$eventSignup->getPrimaryAttendeDetails($_REQUEST['EventSignupId']);
    $Mob = $esDetails[0]['Mobile No'];

    $ch = curl_init();
    $user = "srinivasrp@cbizsoftindia.com:Mera@2015";
    $receipientno = $Mob;
  
    $senderID = "MERAEN";
    $msgtxt = urlencode($Mess);
    curl_setopt($ch, CURLOPT_URL, "http://api.mVaayoo.com/mvaayooapi/MessageCompose");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "user=$user&senderID=$senderID&receipientno=$receipientno&msgtxt=$msgtxt");
    $buffer = curl_exec($ch);
    if (empty($buffer)) { //echo " buffer is empty "; 
    } else { //echo $buffer; 
    }
    curl_close($ch);


    
    echo '<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td height="200" align="center" valign="middle">&nbsp;</td>
  </tr>
  <tr>
  <td align="center" valign="middle"><img src="http://content.meraevents.com/images/ajax-new-loader.gif" /></td></tr></table>';
    ?>
    <script>
        setTimeout(window.location = "http://www.meraevents.com/event/<?= $respass[0]['URL']; ?>", 5000);

    </script>
    <?php
}
?> 