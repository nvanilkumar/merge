<?php
/************************************************************************************************ 
 *	Page Details : Cron Job SMS Feature to Send EVENT Reminder to ORGANIZER
 *	Created / Last Updation Details : 
 *	1.	Created on 3rd Sep 2009 : Alogorithm to send sms to eventsignup users those alerts are
 *		enable for Two Weeks, One Week, Two Days, one day before.
 *	2.	Updated mobile number format remove the special characters
 *	3.	Removed the MT and make it running. by nileshp on 03112009
 *	4.	Updated EMail CMS by nileshp on 19122009
 *	5.	Updated Todays alerts (on place of tomorrows alerts) by nileshp on 01022010
************************************************************************************************/

 include("commondbdetails.php");
        include_once '../ctrl/includes/common_functions.php';
        include("../ctrl/MT/cGlobali.php");
    $commonFunctions=new functions();
    $_GET=$commonFunctions->stripData($_GET,1);
    $_POST=$commonFunctions->stripData($_POST,1);
    $_REQUEST=$commonFunctions->stripData($_REQUEST,1);
	
if($_GET['runNow']==1) 
{
	$db_conn = mysql_connect($DBServerName,$DBUserName,$DBPassword);
	mysql_select_db($DBIniCatalog);


	//CRETAE THE TODAYS START / END DATE
	$tomorrowSDate = date ("Y-m-d", mktime (0,0,0,date("m"),(date("d")-1),date("Y"))).' 18:30:01';
	$tomorrowEDate = date ("Y-m-d", mktime (0,0,0,date("m"),(date("d")),date("Y"))).' 18:29:59';
	
	$SMSCount = 0;	
	
	//>> GET THE LIST OF TWO WEEKS LATER EVENTs
	echo $SelectEvents = "SELECT e.startdatetime, e.title, u.id, u.name, u.mobile, es.id as ESUpId FROM event AS e, eventsignup AS es, user AS u WHERE e.status='1' AND e.id=es.eventid 
	  AND es.userid=u.id AND u.status='1' AND (e.startdatetime>='".$tomorrowSDate."' AND e.startdatetime <= '".$tomorrowEDate."') AND ((es.paymentmodeid=1 and es.paymenttransactionid!='A1') or es.paymentmodeid=2 or  es.discount !='X' or e.registrationtype=1)";
	    
	//$EventsResult = $Global->SelectQuery($SelectEvents);
	
	$rsEvents=mysql_query($SelectEvents);
	$cntEvents = 0;
	while($row=mysql_fetch_array($rsEvents))
	{
		$EventsResult[$cntEvents]=$row;
		$cntEvents++;
	}
	
	for($i = 0; $i < count($EventsResult); $i++)
	{
		$MobileNo = preg_replace("/[^0-9]/i", "", $EventsResult[$i]['mobile']);
		$Id = $EventsResult[$i]['id'];
		$ESUpId = $EventsResult[$i]['ESUpId']; 
		
		if(count($MobileNo) > 9)
		{
			
			
			$selSMSMsgs = "SELECT id as Id, template as Msg FROM messagetemplate WHERE type ='smCJEventSMS' and deleted = 0";
			$dtlSMSMsgs=mysql_fetch_array(mysql_query($selSMSMsgs));

			$SMSMsgId = $dtlSMSMsgs[0]['Id'];
			
			$Message  = str_replace("EventTitle", substr($EventsResult[$i]['title'],0,100), $dtlSMSMsgs['Msg']);
			$Message  = str_replace("afterDaysEDate", $EventsResult[$i]['startdatetime'], $Message);
			$Message  = str_replace("RegNo", $ESUpId, $Message);
			
			<?php 
$ch = curl_init();
$user="srinivasrp@cbizsoftindia.com:Mera@2015";
$receipientno=$MobileNo; 
$senderID="MERAEN"; 
$msgtxt=$Message; 
curl_setopt($ch,CURLOPT_URL,  "http://api.mVaayoo.com/mvaayooapi/MessageCompose");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "user=$user&senderID=$senderID&receipientno=$receipientno&msgtxt=$msgtxt");
$buffer = curl_exec($ch);
if(empty ($buffer))
{ echo " buffer is empty "; }
else
{ echo $buffer; } 
curl_close($ch);

		
		}
	}
	
	
	
	//echo "Total SMS Sent ".$SMSCount;
mysql_close($db_conn);
}
?>