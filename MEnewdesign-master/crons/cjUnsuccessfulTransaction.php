<?php
include("commondbdetails.php");
/************************************************************************************************ 
 *	Page Details : Cron Job Transactions happen yesterday and Send Mail
 *	Created / Last Updation Details : 
 *	1.	Created on 30th Oct 2009 : Created by nileshp
 *		Run Time :	8 AM 
 *		Details  :	Send mail which shows the list of transactions happen yesterday.
 * 					Transactions by payment gateway, cheque, promotion code
 *	2.	Updated on 03rd Nov 2009 : nileshp - Removed the MT and make it running. 
 *	3.	Updated on 22nd Jan 2010 : nileshp - added CC : rajeshb@quicsolv.com
************************************************************************************************/	

include("../ctrl/MT/cGlobali.php");
$global=new cGlobali();

       include_once '../ctrl/includes/common_functions.php';
    $commonFunctions=new functions();
    $_GET=$commonFunctions->stripData($_GET,1);
    $_POST=$commonFunctions->stripData($_POST,1);
    $_REQUEST=$commonFunctions->stripData($_REQUEST,1);
if(isset($_GET['failedTransaction'])) {
		
	/*$DBServerName = "localhost";
	$DBUserName = "meraeven_admin";
	$DBPassword = "Admin@123";
	$DBIniCatalog = "meraeven_dmeraevent";*/




//	$db_conn = mysql_connect($DBServerName,$DBUserName,$DBPassword);
//	mysql_select_db($DBIniCatalog);

//echo "in here";
$date=date('Y-m-d');

$starttime=date("Y-m-d H:i:00", strtotime("-1 hour"));
//$starttime=date("2013-11-26 11:30:05");
$endtime=date("Y-m-d H:i:59", strtotime("-30 minutes"));
//$endtime=date("2013-11-26 12:50:05");

//$endtime=date("Y-m-d H:i:59");
//$starttime=date("Y-m-d H:i:00", strtotime("-30 minutes"));


$subjectstart=date("h:i A", strtotime("-1 hour"));
$subjectend=date("h:i A", strtotime("-30 minutes"));


$dateformat=date('jS M, Y');

$messagetemplate='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
            <td colspan="2" height="20">Hi FULL_NAME,</td>
          </tr>
          <tr>
            <td colspan="2" height="35">Thank you for booking a ticket through Meraevents.</td>
          </tr>
          <tr>
            <td colspan="2" height="30">We request you to share your feedback for the event - <strong>EVENT_NAME</strong> that you attended on DATE_FORMAT.<br />
            </td>
          </tr>
          
          <tr>
            <td width="350"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tbody>
                   <tr>
                    <td height="20"></td>
                  </tr>
                    <tr>
                    <td height="20"><a href="SITE_LINK" title="Submit your feedback">Submit your feedback here.</a> </td>
                  </tr>
                   


                  
                  <tr>
                    <td height="20"></td>
                  </tr>
                  <tr>
                    <td height="30" align="left">We look forward to serving you many more times in the future. </td>
                  </tr>
                </tbody>
            </table></td>
            <td valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" height="20"></td>
          </tr>
          
          <tr>
            <td colspan="2" height="40">Thanks for using MeraEvents.com. Have a great time!</td>
          </tr>
        </table></td>
		</tr>
		            <tr></tr>
		            <tr><td align="left" style="padding: 20px;"><table width="510" cellspacing="0" cellpadding="0" border="0">	<tbody><tr>		<td style="font-family: Tahoma,Verdana,Geneva; font-size: 10px; color: rgb(98, 98, 98);">Questions, Comments, Critics? Email us at <a style="color: rgb(6, 117, 181); text-decoration: none;" href="/mc/compose?to=info@hereitself.com" target="_blank" ymailto="mailto:support@meraevents.com" rel="nofollow"><span id="lw_1266263330_4" class="yshortcuts">support@meraevents.com</span></a></td>		
		          <td width="100" align="right" style="font-family: Tahoma,Verdana,Geneva; font-size: 10px; color: rgb(98, 98, 98);">Friend us on:</td>		<td width="60" align="right"><a href="http://www.facebook.com/meraevents" target="_blank" rel="nofollow"><img width="23" height="22" border="0" alt="Facebook" src="http://static.meraevents.com/images/static/facebook.png"></a> &nbsp;<a href="http://twitter.com/meraevents_com" target="_blank" rel="nofollow"><img width="23" height="22" border="0" alt="Twitter" src="http://static.meraevents.com/images/static/twitter.png"></a></td>	
		            </tr></tbody></table></td></tr><tr><td valign="top" align="left" style="border-top: 10px solid rgb(255, 255, 255); padding: 20px 5px; background-color: rgb(238, 237, 231);" class="footerRow"><div style="font-size: 10px; color: rgb(153, 153, 153); line-height: 100%; font-family: Tahoma;" class="footerText">		            Copyright (C) '.date("Y").' itself. All rights reserved.<br>
		</div></td></tr></tbody></table></td></tr></tbody></table>
	</div></body>
</html>';

$originaltemplate=$messagetemplate;




$fromemail=NULL;
$messagetemplate=NULL;
// $query="SELECT s.Id  as 'Reg.no',
//       s.SignupDt 'Date',
//       t.name 'Ticket Type',
//       s.name,
//       u.Email, 
//       u.FirstName, 
//       u.MiddleName, 
//       u.LastName, 
//       u.Phone, 
//       u.Mobile, 
//       
//       s.phone,
//    e.ContactDetails,e.UserID,e.Title,e.Id,e.URL,
//       estd.ticketamt 'Amount',
//       s.qty
//FROM  EventSignup AS s  Inner Join events AS e on s.EventId = e.Id
//                        Inner Join eventsignupticketdetails estd on estd.eventsignupid=s.id
//                        Inner Join tickets t on estd.ticketid=t.id
//                        Inner Join user as u on s.userid=u.id
//                      
//          WHERE 
//             ((s.PaymentModeId=1 and s.PaymentTransId='A1') or s.PaymentModeId!=2 or  s.PromotionCode ='X' ) 
//             and s.Fees!=0  
//             and  s.id not in (SELECT id FROM EventSignup WHERE 
//                                    ((PaymentModeId=1 and PaymentTransId!='A1') or PaymentModeId=2 or  PromotionCode !='X')   
//                                 )    
//            AND s.eStatus='Open' AND s.signupdt BETWEEN '$starttime' AND '$endtime' 
//        ORDER BY s.Id DESC";
//		
//	//echo $query ;	
//       // exit();
//$sqlemail=$global->SelectQuery($query); 


$incompleteTransQuery="select * from `incompletetrans` where `TimeStamp` BETWEEN '".$starttime."' AND '".$endtime."'";
$incompleteTransRes=$global->SelectQuery($incompleteTransQuery);
//if(count($incompleteTransRes)>0)
//{
//
// foreach ($incompleteTransRes as $value) {
//     $ticketDetails=  unserialize(json_decode($value['TicketType']));
//     foreach ($ticketDetails as $ticketInfo) {
//         $tickettype.=$ticketInfo->Name."-(".$ticketInfo->count."),";
//         
//     }
//     
// $eventInfoQuery="select `ContactDetails`,`UserID`,`Title`,`URL`  from `events` where `Id`='".$value['EventId']."'" ;
// $eventInfoRes=$global->SelectQuery($eventInfoQuery);
//        
// $signupdate=$value['TimeSTamp']; 
////$tickettype=$ticketDetails[''];//shd be commented -pH.
//$useremail=$value['Email']; 
//$name=$value['Name'];
//$contactdetails=$eventInfoRes[0]['ContactDetails']; //event related.
//$mobile=$value['Mobile'];//may Loose;
////$quantity=$value['qty'];//will be commented
//$creatorid=$eventInfoRes[0]['UserID']; //event Related -pH
//$eventname=$eventInfoRes[0]['Title']; //event Related -pH
//$eventid=$value['EventId'];
////$amount=$value['Amount']; not in USE -pH
////$eventsignup=$value['Reg.no'];
//$eventsignup=$eventInfoRes[0]['Title'];
//
//
//$eurl=$value['URL']; //eventRelated -pH
// }
//}
if(count($incompleteTransRes)>0)
{
    //print_r($sqlemail);
 

//    print_r($incompleteTransRes);
//    exit();

 foreach ($incompleteTransRes as $value){

	$template=$messagetemplate;
	$sendemailto=NULL;
       //  print_r(($value['TicketType']));
      //   echo "------------";
    ///   print_r(json_decode($value['TicketType']));
//  $ticketDetails= json_decode(unserialize($value['TicketType']));
          $ticketDetails= json_decode($value['TicketType']);
        
 // print_r($ticketDetails);
 // exit();
          $tickettype="";
     foreach ($ticketDetails as $ticketInfo) {
         $tickettype.=$ticketInfo->Name." - (".$ticketInfo->Count."), ";
         
     }
     $tickettype=substr($tickettype, 0, -2);
   //  echo $tickettype."yes";
   //  exit();
     
 $eventInfoQuery="select ed.`contactdetails` 'ContactDetails,e.`ownerid` 'UserID',e.`title` 'Title',e.`url` 'URL'  from `event` e inner join eventdetail ed on ed.eventid = eid where e.`id`='".$value['eventid']."'" ;
 $eventInfoRes=$global->SelectQuery($eventInfoQuery);
        
 $signupdate=$value['TimeStamp']; 
//$tickettype=$ticketDetails[''];//shd be commented -pH.
$useremail=$value['Email']; 
$name=$value['Name'];
$contactdetails=$eventInfoRes[0]['ContactDetails']; //event related.
$mobile=$value['Mobile'];//may Loose;
//$quantity=$value['qty'];//will be commented
$creatorid=$eventInfoRes[0]['UserID']; //event Related -pH
$eventname=$eventInfoRes[0]['Title']; //event Related -pH
$eventid=$value['EventId'];
//$amount=$value['Amount']; not in USE -pH
//$eventsignup=$value['Reg.no'];
$incompleteTransId=$value['Id'];


$eurl=$eventInfoRes[0]['URL']; //eventRelated -pH

//end of if condition to check if the alerts are set

  $sql="select `type`,`status` from `alert` where `userid`='".$creatorid."' ";
		
		

		
		$resultset=$global->SelectQuery($sql);
                $alertsOpted=false;
		if(count($resultset)>0)
		{			
			if($resultset[0]['type']=='incomplete'){
			if($resultset[0]['status']==1)
			$alertsOpted=true;
			}
		}
               
//                if($alertsOpted)
//                {
                    
$organizer=$global->SelectQuery("select `email`,`name` from `user` where `id`='".$creatorid."'");
$organizername=$organizer[0]['name'];
$organizeremail=$organizer[0]['email'];
//echo $organizeremail.":".$alertsOpted."-----";
if($alertsOpted)
{
  $match=$functions->extract_emails($contactdetails);

if(is_array($match))
{
	$sendemailto=implode(',',$match);
}
$sendemailto.=','.$organizeremail;//needs to be done on some condition of (alerts opted) -pH
}


//checking.
//if(isset($failed[$sendemailto][$incompleteTransId]))
//{
////$failed[$sendemailto][$eventsignup]['tickettype'].=','.$tickettype;
//continue;	
//}


$failed[$sendemailto][$incompleteTransId]['organizername']=$organizername;
$failed[$sendemailto][$incompleteTransId]['time']=$signupdate;
$failed[$sendemailto][$incompleteTransId]['name']=$name;
$failed[$sendemailto][$incompleteTransId]['email']=$useremail;
$failed[$sendemailto][$incompleteTransId]['mobile']=$mobile;
$failed[$sendemailto][$incompleteTransId]['tickettype']=$tickettype;
$failed[$sendemailto][$incompleteTransId]['eventname']=$eventname;
$failed[$sendemailto][$incompleteTransId]['eventid']=$eventid;
//$failed[$sendemailto][$incompleteTransId]['quantity']=$quantity; //not required -pH, displaying it in the ticketType column only
$failed[$sendemailto][$incompleteTransId]['url']=$eurl;
	   
/*$unsuccessful[$eventsignup][]="<table align='center'>
<tr><td><b>Registration No. :</b></td><td> $eventsignup</td></tr>

<tr><td><b>Name :</b></td><td> $name</td></tr>
<tr><td><b>Email :</b></td><td> $useremail</td></tr>
<tr><td><b>Mobile :</b></td><td> $mobile</td></tr>
<tr><td><b>Event Details :</b></td><td> $eventname ($eventid)</td></tr>
<tr><td><b>Ticket Type :</b></td><td> $tickettype (Rs. $amount)</td></tr>
<tr><td><b>Amount :</b></td><td> Rs. $amount /-</td></tr>
<tr><td><b>Quantity :</b></td><td> $quantity</td></tr>
</table><br />
<br />
";*/

//}//endOd alerts Opted check

	
}



}

//print_r($failed);



$fromemail=NULL;
$messagetemplate=NULL;

$sqlemail=$global->SelectQuery("select `id`,`template` as msgtemplate,`fromemailid` as fromemail from messagetemplate where `type`='failedTran' and `mode`='email'");
if(count($sqlemail)>0)
{
$messagetemplate=stripslashes($sqlemail[0]['msgtemplate']);
$fromemailtemplate=$sqlemail[0]['fromemail'];	
$messageid=$sqlemail[0]['id'];
}

    
//print_r($failed);
//exit();
if(strlen($messagetemplate)>0 && is_array($failed))
{
	
foreach($failed as $keys=>$values)
{
	$template=$messagetemplate;
	
	$sno=1;$count=1;
	
	$tab="<table   style='font-size:13px;text-align:center;border-width: 0 0 1px 1px;border-spacing: 0;border-collapse: collapse;border-style: solid;border-color:#CCC;'>
<tr><th style='text-align:center;margin: 0;padding: 4px;border-width: 1px 1px 0 0;border-style: solid;border-color:#CCC;'>Sno.</th>
<th style='text-align:center;margin: 0;padding: 4px;border-width: 1px 1px 0 0;border-style: solid;border-color:#CCC;' > Name </th>
<th style='text-align:center;margin: 0;padding: 4px;border-width: 1px 1px 0 0;border-style: solid;border-color:#CCC;' > Email </th>
<th style='text-align:center;margin: 0;padding: 4px;border-width: 1px 1px 0 0;border-style: solid;border-color:#CCC;' >Mobile </th>
<th style='text-align:center;margin: 0;padding: 4px;border-width: 1px 1px 0 0;border-style: solid;border-color:#CCC;' > Ticket types - (Qty) </th>
<th style='text-align:center;margin: 0;padding: 4px;border-width: 1px 1px 0 0;border-style: solid;border-color:#CCC;' >Tried Booking At </th></tr>
";
$tomails=explode(',',$keys);

$eventnamevalue=NULL;$eventurl=NULL;
	foreach($values as $keyin=>$valuein)
	{
		

if($sno==1)
{$eventnamevalue=$valuein['eventname'];
$template=str_replace('OrgName',$valuein['organizername'],$template);	
$orgname=$valuein['organizername'];
$eventurl=$valuein['eurl'];
}

		$tab.="<tr><td style='text-align:center;margin: 0;padding: 4px;border-width: 1px 1px 0 0;border-style: solid;border-color:#CCC;' >$sno</td>
		<td style='text-align:center;margin: 0;padding: 4px;border-width: 1px 1px 0 0;border-style: solid;border-color:#CCC;' >".$valuein['name']."</td><td style='text-align:center;margin: 0;padding: 4px;border-width: 1px 1px 0 0;border-style: solid;border-color:#CCC;' >".$valuein['email']."</td>
<td style='text-align:center;margin: 0;padding: 4px;border-width: 1px 1px 0 0;border-style: solid;border-color:#CCC;' >".$valuein['mobile']."</td>
<td style='text-align:center;margin: 0;padding: 4px;border-width: 1px 1px 0 0;border-style: solid;border-color:#CCC;' >".$valuein['tickettype']."</td>
<td style='text-align:center;margin: 0;padding: 4px;border-width: 1px 1px 0 0;border-style: solid;border-color:#CCC;' >".$valuein['time']."</td></tr>";	
	
		
		$sno++;$count++;
	}
	
	
	$tab.='</table> ';
	
	
	
	$eurl="http://www.meraevents.com/event/".$eventurl; 

$u='http://www.facebook.com/share.php?u='.$eurl.'&title=Meraevents -'.$eventnamevalue; 
 $c='onclick="javascript: cGA(/event-share-facebook); return fbs_click('.$eurl.', Meraevents - '.$eventnamevalue.')'  ;
$T='http://twitter.com/home?status='.$eventnamevalue.'...+'.$eurl ;
	
	$template='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>

</head>

<body style="padding:0px; margin:0px;"><div style="background:#FFFFFF; padding:">
		<table width="100%" cellspacing="0" style="background-color: rgb(238, 237, 231);" class="backgroundTable"><tbody><tr><td valign="top" align="center">
<table width="700" cellspacing="0" cellpadding="0" style="border-bottom: 1px solid rgb(238, 237, 231);">
<tbody><tr><td height="40" align="center" class="headerTop" style="border-top: 0px solid rgb(0, 0, 0); border-bottom: 0px none rgb(255, 255, 255); padding: 0px; background-color: rgb(238, 237, 231); text-align: center;"><div style="font-size: 10px; color: rgb(153, 153, 153); line-height: 300%; font-family: Verdana; text-decoration: none;" class="adminText"></div></td></tr><tr><td style="background-color: rgb(255, 255, 255);" class="headerBar"><table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td width="120"><div style="padding: 10px;" class="divpad"><a href="http://www.meraevents.com" target="_blank" rel="nofollow"><span style="line-height: 0px;">
<img src="http://static.meraevents.com/images/static/me-logo.svg" alt="MeraEvents.com" title="MeraEvents.com"   border="0" style="display: block;" /></span></a></div></td>
                        <td width="430" valign="top" align="right">&nbsp;</td>
		</tr></tbody></table></td></tr></tbody></table><table width="700" cellspacing="0" cellpadding="0" style="background-color: rgb(255, 255, 255);" class="bodyTable"><tbody><tr><td valign="top" align="left" style="padding: 20px 20px 0pt; background-color: rgb(255, 255, 255);" class="defaultText"><table width="95%" cellpadding="0" cellspacing="0" style="font-family:Arial, Helvetica, sans-serif; font-size:14px;">
          <tr>
            <td colspan="2" height="20">Hi '.$orgname.',</td>
          </tr>
          <tr>
            <td colspan="2" height="50">The details of the Incomplete Transaction for the event <b>'.ucfirst($eventnamevalue).'</b> are as follows<br />
</td>
          </tr>
         
          <tr>
            <td colspan="2" >'.$tab.'</td>
          </tr>
          <tr>
        
            <td valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" height="20"></td>
          </tr>
          
          <tr>
            <td colspan="2" height="40">Thanks for using MeraEvents.com. Have a great time!</td>
          </tr>
        </table></td>
		</tr>
		            <tr></tr>
		            <tr><td align="left" style="padding: 20px;"><table width="660" cellspacing="0" cellpadding="0" border="0">	<tbody><tr>		<td style="font-family: Tahoma,Verdana,Geneva; font-size: 10px; color: rgb(98, 98, 98);">Questions, Comments, Critics? Email us at <a style="color: rgb(6, 117, 181); text-decoration: none;" href="/mc/compose?to=info@hereitself.com" target="_blank" ymailto="mailto:support@meraevents.com" rel="nofollow"><span id="lw_1266263330_4" class="yshortcuts">support@meraevents.com</span></a></td>		
		          <td width="100" align="right" style="font-family: Tahoma,Verdana,Geneva; font-size: 10px; color: rgb(98, 98, 98);">Friend us on:</td>		<td width="60" align="right"><a href="http://www.facebook.com/meraevents" target="_blank" rel="nofollow"><img width="23" height="22" border="0" alt="Facebook" src="http://static.meraevents.com/images/static/facebook.png"></a> &nbsp;<a href="http://twitter.com/meraevents_com" target="_blank" rel="nofollow"><img width="23" height="22" border="0" alt="Twitter" src="http://static.meraevents.com/images/static/twitter.png"></a></td>	
		            </tr></tbody></table></td></tr><tr><td valign="top" align="left" style="border-top: 10px solid rgb(255, 255, 255); padding: 20px 5px; background-color: rgb(238, 237, 231);" class="footerRow"><div style="font-size: 10px; color: rgb(153, 153, 153); line-height: 100%; font-family: Tahoma;" class="footerText">		            Copyright (C) '.date("Y").' itself. All rights reserved.<br>
		</div></td></tr></tbody></table></td></tr></tbody></table>
	</div></body>
</html>';
	
	//$template="Hello $orgname,<br /> <br />  The details of the Unsuccessful Transaction are as follows<br /> <br /> $tab <br /><br />  <p>You are most welcome to contact us for any query,              comments and suggestions at <br />             <a href=\"mailto:support@meraevents.com\" target=\"_blank\">             support@meraevents.com</a> please call us at  +91-9396555888</p>           <br/>        <p><span class=\"style2\">With Best Wishes,</span><br /> 		    <a href='http://www.meraevents.com'><img src='http://www.meraevents.com/images/logo.jpg' border='0'></a><br />             <strong><div class='imgbuttons'><div class='socialmediabg'><div style='float:left; margin-right:10px; line-height:30px;'><strong>Connect us</strong></div>                 <div style='padding:5px 10px 5px 10px; line-height:20px; height:25px;' align='left'><a href='http://www.facebook.com/pages/wwwmeraeventscom/125923692046' target='_blank'><img src='http://www.meraevents.com/images/facebook.jpg' border='0' alt='Face Book' /></a>&nbsp;<a href='http://twitter.com/meraevents_com' target='_blank'><img src='http://www.meraevents.com/images/twiter.jpg' border='0' alt='Twitter' /></a>&nbsp;<a href='http://www.linkedin.com/company/www.meraevents.com' target='_blank'> <img src='http://www.meraevents.com/images/linkdin.jpg' border='0' alt='Linked In' /></a>&nbsp;<a href='http://labs.google.co.in/smschannels/subscribe/MeraEvents' target='_blank' ><img src='http://www.meraevents.com/images/Sms.png' border='0' height=21 width=21 alt='Google Sms Channels'/></a>&nbsp;<a href='http://www.meraevents.com/rss_events.php' target='_blank' ><img src='http://www.meraevents.com/images/Rss.png' height=21 width=21 border='0' alt='RSS'/></a>&nbsp;        </div></div>      </div></strong></p>   "; 
	
	//$template=str_replace('UNSUCCESSFUL_TRANSACTION',$tab,$template);
	
	$headers = "From:MeraEvents<".$fromemailtemplate.">\r\n" .
	"X-Mailer: PHP/" . phpversion() . "\r\n" .
	"MIME-Version: 1.0\r\n" .
	"Content-Type: text/html; charset=utf-8\r\n" .
	"Content-Transfer-Encoding: 8bit\r\n\r\n";
	
	$subject="Incomplete Transaction between $subjectstart - $subjectend on $date for  $eventnamevalue";
	
	$alreadysent=NULL;
	
	foreach($tomails as $tokey=>$tovalue)
	{
		if(!isset($alreadysent[$tovalue]))
		{
			// mail($tovalue,$subject,$template,$headers);
			
			$cc=$content=$filename=$replyto=NULL;
			$bcc='qison@meraevents.com,support@meraevents.com';
                        //$bcc='samuelphinny@gmail.com';
			$from='MeraEvents<info@meraevents.com>';
                      //  echo "toValue:".$tovalue;
                        if(empty($tovalue))
                        {
                            $tovalue=$bcc;
                            $bcc=Null;
                        }
                     //   exit();
			$functions->sendEmail($tovalue,$cc,$bcc,$from,$replyto,$subject,$template,$content,$filename);
	
	
	
	
	
	
	//mail('durgeshmishra2525@gmail.com',$subject,$template,$headers);
/*	echo "<br />
mail sent to $tovalue ;";*/
//echo $template;
		
		$alreadysent[$tovalue]=1;
	}
	}
	
//	echo $template;
}


}//end of messagetemplate check condition




	
	
mysql_close();	
	
}


 ?>