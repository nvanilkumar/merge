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
        $cGlobali=new cGlobali();
    $commonFunctions=new functions();
    $_GET=$commonFunctions->stripData($_GET,1);
    $_POST=$commonFunctions->stripData($_POST,1);
    $_REQUEST=$commonFunctions->stripData($_REQUEST,1);
		

if($_GET['runNow']==1) 
{
	
 


	//CRETAE THE TOMMORROW START / END DATE
	$tomorrowSDate = date ("Y-m-d", mktime (0,0,0,date("m"),(date("d")),date("Y"))).' 18:30:01';
	$tomorrowEDate = date ("Y-m-d", mktime (0,0,0,date("m"),(date("d")+1),date("Y"))).' 18:29:59';
	
	 //$tomorrowSDate=$commonFunctions->convertTime($tomorrowSDate1,DEFAULT_TIMEZONE,TRUE);
    // $tomorrowEDate=$commonFunctions->convertTime($tomorrowEDate1,DEFAULT_TIMEZONE,TRUE);
	//Sms function
	
	 
		

	//>> GET THE LIST OF ONE DAYS LATER EVENTs
  $SelectEvents = "SELECT e.startdatetime,e.enddatetime,e.venuename,e.url, e.title,e.cityid,e.stateid,e.countryid,e.pincode, u.id, u.name,u.email, u.mobile, es.id as ESUpId,e.id as EId FROM event AS e, eventsignup AS es, user AS u WHERE e.status='1' AND e.id=es.eventid AND es.userid=u.id AND u.status='1' AND (e.startdatetime>='".$tomorrowSDate."' AND e.startdatetime <= '".$tomorrowEDate."') AND ((es.paymentmodeid=1 and es.paymenttransactionid!='A1') or es.paymentmodeid=2 or  es.discount !='X' or e.registrationtype=1)";
         
//	$rsEvents=mysql_query($SelectEvents);
        $rsEvents=$cGlobali->justSelectQuery($SelectEvents);
	$cntEvents = 0;
 
	while($row=$rsEvents->fetch_assoc())
	{ 
		$EventsResult[$cntEvents]=$row;
		$cntEvents++;
	}
        

	for($i = 0; $i < count($EventsResult); $i++)
	{
		$MobileNo = preg_replace("/[^0-9]/i", "", $EventsResult[$i]['mobile']);
		$Id = $EventsResult[$i]['id'];
		$ESUpId = $EventsResult[$i]['ESUpId'];	
		
//		 $State = mysql_fetch_array(mysql_query("select `name` from state where id='".$EventsResult[$i]['stateid']."'"));
//         $City = mysql_fetch_array(mysql_query("select `name` from city where id='".$EventsResult[$i]['cityid']."'"));
//		 $Country = mysql_fetch_array(mysql_query("select `name` from country where id='".$EventsResult[$i]['countryid']."'"));
//			
                $State =$cGlobali->GetSingleFieldValue("select `name` from state where id='".$EventsResult[$i]['stateid']."'");
                $City =$cGlobali->GetSingleFieldValue("select `name` from city where id='".$EventsResult[$i]['cityid']."'");
                $Country =$cGlobali->GetSingleFieldValue("select `name` from country where id='".$EventsResult[$i]['countryid']."'");
                
		$time='';
	$StartDtt = $commonFunctions->convertTime($EventsResult[$i][startdatetime],DEFAULT_TIMEZONE,TRUE);
   $EndDtt = $commonFunctions->convertTime($EventsResult[$i][enddatetime],DEFAULT_TIMEZONE,TRUE);	
$StartDt = date('F j, Y',strtotime($StartDtt));
$EndDt = date('F j, Y',strtotime($EndDtt));
$time.= $StartDt;
if($StartDt!=$EndDt)
{
$time.= ' - ';
$time.= $EndDt; 
}  
$time.= ' Time : ';
$time.= date('g:i a',strtotime($StartDtt)).'-'.date('g:i a',strtotime($EndDtt));
 
//$url="http://www.meraevents.com/printpass?UserType=Delegate&uid=".$EventsResult[$i][Id]."&auth_code=".$EventsResult[$i][auth_code]."&EMail=".$EventsResult[$i][Email]."&EventSignupId=".$EventsResult[$i][ESUpId]; 
$url="http://www.meraevents.com/printpass/".$EventsResult[$i][ESUpId]; 
$eurl="http://www.meraevents.com/event/".$EventsResult[$i][url]; 

$u='http://www.facebook.com/share.php?u='.$eurl.'&title=Meraevents -'.$EventsResult[$i][title]; 
 $c='onclick="javascript: cGA(/event-share-facebook); return fbs_click('.$eurl.', Meraevents - '.$EventsResult[$i][title].')'  ;
$T='http://twitter.com/home?status='.$EventsResult[$i][title].'...+'.$eurl ;

$msg='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body style="padding:0px; margin:0px;"><div style="background:#FFFFFF; padding:">
		<table width="100%" cellspacing="0" style="background-color: rgb(238, 237, 231);" class="backgroundTable"><tbody><tr><td valign="top" align="center">
<table width="550" cellspacing="0" cellpadding="0" style="border-bottom: 1px solid rgb(238, 237, 231);">
<tbody><tr><td height="40" align="center" class="headerTop" style="border-top: 0px solid rgb(0, 0, 0); border-bottom: 0px none rgb(255, 255, 255); padding: 0px; background-color: rgb(238, 237, 231); text-align: center;"><div style="font-size: 10px; color: rgb(153, 153, 153); line-height: 300%; font-family: Verdana; text-decoration: none;" class="adminText"></div></td></tr><tr><td style="background-color: rgb(255, 255, 255);" class="headerBar"><table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td width="120"><div style="padding: 10px;" class="divpad"><a href="http://www.meraevents.com" target="_blank" rel="nofollow"><span style="line-height: 0px;">
<img src="http://static.meraevents.com/images/static/me-logo.svg" alt="MeraEvents.com" title="MeraEvents.com"   border="0" style="display: block;" /></span></a></div></td>
                        <td width="430" valign="top" align="right">&nbsp;</td>
		</tr></tbody></table></td></tr></tbody></table><table width="550" cellspacing="0" cellpadding="0" style="background-color: rgb(255, 255, 255);" class="bodyTable"><tbody><tr><td valign="top" align="left" style="padding: 20px 20px 0pt; background-color: rgb(255, 255, 255);" class="defaultText"><table width="95%" cellpadding="0" cellspacing="0" style="font-family:Arial, Helvetica, sans-serif; font-size:14px;">
          <tr>
            <td colspan="2" height="20">Hi '.$EventsResult[$i][name].',</td>
          </tr>
          <tr>
            <td colspan="2" height="35">Your event is almost here! Check out the details below.</td>
          </tr>
          <tr>
            <td colspan="2" height="30"><a href="'.$eurl.'" target="_blank" style="color: #1C5B9C; font-size: 20px; font-weight: bold; font-family:Arial, Helvetica, sans-serif; text-decoration:none;"> '.stripslashes($EventsResult[$i]['title']).'! </a><br />
            </td>
          </tr>
          <tr>
            <td colspan="2" height="25">'.$time.'</td>
          </tr>
          <tr>
            <td width="350"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tbody>
                   <tr>
                    <td height="20"></td>
                  </tr>
                    <tr>
                    <td height="20">RegNo:'.$EventsResult[$i][ESUpId].' </td>
                  </tr>
                   <tr>
                    <td height="20"></td>
                  </tr>

                  <tr>
                    <td width="335"><b>Venue :</b></td><tr>
                    <tr>
                    <td height="25">'.nl2br($EventsResult[$i]['venuename']).'<br/>
                 '.$City[name].','.$State[name].','.$Country[name].'  '.$EventsResult[$i]['pincode'].' </p></td>
                  </tr>
                  
                  <tr>
                    <td height="20"></td>
                  </tr>
                  <tr>
                    <td height="30" align="left"><a target="_blank" href="'.$url.'" alt="Print Tickets" style="background-color:#13b5f9;color:#FFFFFF;display:inline-block;font-size:12pt;font-weight:bold;font-family:Helvetica, Arial, sans-serif;padding:4px 12px;margin:0px;text-decoration:none; border:none;">Print Tickets</a>&nbsp;<a target="_blank" href="http://www.meraevents.com/ctrl/EmailSMSFun.php?EventSignupId='.$EventsResult[$i][ESUpId].'" alt="Go Green - SMS Me" style="background-color:#9aaa12;color:#FFFFFF;display:inline-block;font-size:12pt;font-weight:bold;font-family:Helvetica, Arial, sans-serif;padding:4px 12px;margin:0px;text-decoration:none; border:none;">Go Green - SMS Me</a> </td>
                  </tr>
                </tbody>
            </table></td>
            <td valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" height="20"></td>
          </tr>
          <tr>
            <td colspan="2" height="20">Let your friends know. Share this event on <a href="'.$u.'" '.$c.' target="_blank" style="color:#1C5B9C; text-decoration:none">Facebook</a> and <a href="'.$T.'" style="color:#1C5B9C; text-decoration:none" target="_blank">Twitter</a>. </td>
          </tr>
          <tr>
            <td colspan="2" height="40">Thanks for using MeraEvents.com. Have a great time!</td>
          </tr>
        </table></td>
		</tr>
		            <tr></tr>
		            <tr><td align="left" style="padding: 20px;"><table width="510" cellspacing="0" cellpadding="0" border="0">	<tbody><tr>		<td style="font-family: Tahoma,Verdana,Geneva; font-size: 10px; color: rgb(98, 98, 98);">Questions, Comments, Critics? Email us at <a style="color: rgb(6, 117, 181); text-decoration: none;" href="/mc/compose?to=info@hereitself.com" target="_blank" ymailto="mailto:support@meraevents.com" rel="nofollow"><span id="lw_1266263330_4" class="yshortcuts">support@meraevents.com</span></a></td>		
		          <td width="100" align="right" style="font-family: Tahoma,Verdana,Geneva; font-size: 10px; color: rgb(98, 98, 98);">Friend us on:</td>		<td width="60" align="right"><a href="http://www.facebook.com/meraevents" target="_blank" rel="nofollow"><img width="23" height="22" border="0" alt="Facebook" src="http://static.meraevents.com/images/static/facebook.png"></a> &nbsp;<a href="https://twitter.com/meraeventsindia" target="_blank" rel="nofollow"><img width="23" height="22" border="0" alt="Twitter" src="http://static.meraevents.com/images/static/twitter.png"></a></td>	
		            </tr></tbody></table></td></tr><tr><td valign="top" align="left" style="border-top: 10px solid rgb(255, 255, 255); padding: 20px 5px; background-color: rgb(238, 237, 231);" class="footerRow"><div style="font-size: 10px; color: rgb(153, 153, 153); line-height: 100%; font-family: Tahoma;" class="footerText">		            Copyright (C) '.date("Y").' itself. All rights reserved.<br>
		</div></td></tr></tbody></table></td></tr></tbody></table>
	</div></body>
</html>';	
			
	
	
	
	$subject = '[MeraEvents] Reminder for "'.stripslashes($EventsResult[$i]['title']).' !"';
	
	 
	
	$cc=$content=$filename=$replyto=NULL;
	$from='MeraEvents<admin@meraevents.com>';
	$bcc='qison@meraevents.com';
	$commonFunctions->sendEmail($EventsResult[$i]['email'],$cc,$bcc,$from,$replyto,$subject,$msg,$content,$filename); 
	
       // mail($EventsResult[$i]['Email'], $subject, $msg, $headers);
	}
	
 
}
?>