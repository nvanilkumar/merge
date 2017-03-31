<?php
include_once("cGlobali.php");
include_once("../includes/functions.php");

class cAffiliate
{
	public $Globali = '';
	public $commonFunctions='';
        public $hostname='';
//----------------------------------------------------------------------------------------------------

	public function __construct()
	{
		$this->Globali=new cGlobali();
		$this->commonFunctions=new functions();
                $hostname=strtolower($_SERVER['HTTP_HOST']);
                if(strcmp($hostname,'www.meraevents.com')==0 || strcmp($hostname,'meraevents.com')==0)
                {
                    $this->supersonicEvents = array(71769,71772);  
                }elseif(strcmp($hostname,'stage.meraevents.com')==0)
                {
                    $this->supersonicEvents = array(64954,55471);
                }
	}
	
//----------------------------------------------------------------------------------------------------
	
	/**** 
         ** function to calculate Referral Discount for a given Ticket
	 ***/
         
	public function calculateReferralDiscountByTicket($ticketId,$TicketPrice,$ticketQty,$Globali)
	{
                $referralDiscTotal=0;$refereeDiscTotal=0;
                $retArray = array();
                $refLinkTicktsQry= "SELECT Id,commissionvalue,commissiontype,refereeCommValue FROM aff_event_tickets WHERE ticketid='".$this->Globali->dbconn->real_escape_string($ticketId)."' AND commissionvalue>0 AND status=1"; 
                $refLinkTicktsRes = $this->Globali->SelectQuery($refLinkTicktsQry);
                $refLinkIndexKey = array();
                $refDisAmt = $refLinkTicktsRes[0]['commissionvalue'];
                $refereeDisAmt= $refLinkTicktsRes[0]['refereeCommValue'];
                  if(count($refLinkTicktsRes)>0){
                      if($refLinkTicktsRes[0]['commissiontype']==1){
                      $refDisAmt = round(($TicketPrice * $refDisAmt/100),2);
                      $refereeDisAmt = round(($TicketPrice * $refereeDisAmt/100),2);
                  }
                  $referralDiscTotal = $refDisAmt*$ticketQty;
                  $refereeDiscTotal = $refereeDisAmt*$ticketQty;
                  }
                  $retArray['referralDiscTotal'] = $referralDiscTotal;
                  $retArray['refereeDiscTotal'] = $refereeDiscTotal;
                 return $retArray;
	}
	
//---------------------------------------------------------------------------------------------------------------
        /**** 
         ** function to insert records in AFF_SALES and AFF_USER_POINTS tables after successfull transaction done through referral link
	 ***/
        
            
        /**** 
         ** function to insert records in AFF_SALES and AFF_USER_POINTS tables after successfull transaction done through referral link
	 ***/
        
       public function insertAffReferralInfo($dbSessData,$eventSignupId,$affSalesStatus,$shareLink,$eventName,$discountMessage=NULL)
        {
			$senderUserId = $this->Globali->GetSingleFieldValue("SELECT UserId FROM EventSignup WHERE referralCode='".$this->Globali->dbconn->real_escape_string($dbSessData['referralCode'])."'");
				
				 /*
				 * Insert AFF_USER_POINTS table after successfull completion of booking through referral link *
				 */
				   $pointsType = '1';
				   $mts=date('Y-m-d h:i:s');
				   $sqlaffPointsQry="insert into `aff_user_points` (`userid`,`points`,`type`,`status`,`mts`) values(?,?,?,?,?)";
				   $sqlaffPointsres=$this->Globali->dbconn->prepare($sqlaffPointsQry);
				   $sqlaffPointsres->bind_param("ddsis",$senderUserId,$dbSessData['refereeDiscTotal'],$pointsType,$affSalesStatus, $mts);
				   $sqlaffPointsres->execute();
				   $senderPointsId = $sqlaffPointsres->insert_id;
				   $sqlaffPointsres->close();
				 //End of Updating of AFF_USER_POINTS table
                 /*
                 * Insert AFF_SALES table after successfull completion of booking through referral link *
                 */
				 
                 if(count($dbSessData['referralTicketIds'])>0){
					   foreach($dbSessData['referralTicketIds'] as $tktId)
					   {
						    
                           $eventSignupTicktDetailId = $this->Globali->GetSingleFieldValue("SELECT Id from eventsignupticketdetails WHERE EventSignupId='".$this->Globali->dbconn->real_escape_string($eventSignupId)."' AND TicketId='".$this->Globali->dbconn->real_escape_string($tktId)."'");
                           $affiliateEventTicketId = $this->Globali->GetSingleFieldValue("SELECT Id from aff_event_tickets WHERE eventid='".$this->Globali->dbconn->real_escape_string($dbSessData['EventId'])."' AND ticketid='".$this->Globali->dbconn->real_escape_string($tktId)."'");
                           $salesType=1;
                           $sqlaffSalesQry="insert into `aff_sales` (`senderUserid`,`eventSignupTicketDetailsId`,`affiliateEventTicketId`,`senderUserPointsId`,`salesType`,`referralCode`,`mts`) values(?,?,?,?,?,?,?)";
                           $sqlaffSalesres=$this->Globali->dbconn->prepare($sqlaffSalesQry);
						   $mtsDate=date('Y-m-d h:i:s');
                           $sqlaffSalesres->bind_param("ddddiss",$senderUserId,$eventSignupTicktDetailId,$affiliateEventTicketId,$senderPointsId,$salesType,$dbSessData['referralCode'],$mtsDate);
                           $sqlaffSalesres->execute();
                           $sqlaffSalesres->close();
					   }
					   if($affSalesStatus=='1'){
							/*
							* update ME Credits in User table                    
							*/
						   $userMeCreditsQry = "SELECT mecredits FROM user WHERE Id='".$senderUserId."'";
						   $userCredits = $this->Globali->SelectQuery($userMeCreditsQry);
						   $userCredits = $userCredits[0]['mecredits'] + $dbSessData['refereeDiscTotal'];
						   
						   $UpdateuserMeCreditsQry = "UPDATE user SET mecredits=$userCredits WHERE Id='".$senderUserId."'";
						   $this->Globali->ExecuteQuery($UpdateuserMeCreditsQry);
						   
						   //End of updating users table
						   
						   
						   if($dbSessData['EventId']!=Mumbai_Marathon_EventId)
						   {
							   $msgId=$this->Globali->GetSingleFieldValue("SELECT Id FROM EMailMsgs WHERE MsgType='emAffCongratz'");
							   $emailSent=$this->Globali->GetSingleFieldValue("SELECT Id FROM EMailSent WHERE EMailMsgId='".$msgId."' AND EventSignupId='".$eventSignupId."'");
	
							   //send mail to the user who got points
								$selOwner=$this->Globali->SelectQuery("SELECT EMail,FirstName as name FROM user WHERE Id='".$senderUserId."'");
								//CHECK SHARE MAIL SENT OR NOT
								$msgId=$this->Globali->GetSingleFieldValue("SELECT Id FROM EMailMsgs WHERE MsgType='emAffCongratz'");
								//$emailSent=$this->Globali->GetSingleFieldValue("SELECT Id FROM EMailSent WHERE EMailMsgId='".$msgId."' AND EventSignupId='".$senderUserESId."'");
								if(count($selOwner)>0 and count($emailSent)==0){
									$dtlRes=$this->Globali->SelectQuery("SELECT CONCAT(IF(ISNULL(FirstName),'',FirstName),' ',IF(ISNULL(MiddleName),'',MiddleName),' ',IF(ISNULL(LastName),'',LastName)) as name,EMail FROM user WHERE Id='".$_SESSION['uid']."'");
									$bookeeName=empty($dtlRes[0]['name'])?$dtlRes[0]['EMail']:$dtlRes[0]['name'];
									$selAffEveTck="SELECT aef.status,MAX(IF(commissiontype=1,(refereeCommValue*Price)/100,refereeCommValue)) AS refereeAmount,MAX(IF(commissiontype=1,(commissionvalue*Price)/100,commissionvalue)) AS bookeeAmount FROM aff_event_tickets aef INNER JOIN tickets t ON aef.ticketid=t.Id WHERE aef.eventid='".$_REQUEST['EventId']."' AND aef.status=1";
									$selAffEveTckRes=$this->Globali->SelectQuery($selAffEveTck);
									
									$this->sendCongratzMail($dbSessData['EventId'],$selOwner[0]['EMail'],$dbSessData['refereeDiscTotal'],$selAffEveTckRes[0]['refereeAmount'],$selAffEveTckRes[0]['bookeeAmount'],$bookeeName,$selOwner[0]['name'],$shareLink,$eventName,$discountMessage,$dbSessData['currencyCode']);
									$EMailSentStmt=$this->Globali->dbconn->prepare("INSERT INTO EMailSent (UserId, EMailMsgId,EventSignupId, SentDt) VALUES (?,?,?,?)");
									$sSentDt = date('Y-m-d h:i:s');
									$EMailSentStmt->bind_param("dids",$senderUserId,$msgId,$eventSignupId,$sSentDt);
									$EMailSentStmt->execute();
									$EMailSentStmt->close();
								}
							}
					   }
                 }
        }
		public function sendShareMail($EventId,$delEmail,$from,$replyto,$event_url,$title,$referralCode,$name,$refAmount,$bookAmount,$discountMessage,$folder=NULL,$filename=NULL)
		{
			$bookeeDiscTotal=$this->Globali->GetSingleFieldValue("SELECT aef.status, MAX(IF(commissiontype=1,(commissionvalue*Price)/100,commissionvalue)) AS bookeeAmount FROM aff_event_tickets aef INNER JOIN tickets t ON aef.ticketid=t.Id WHERE aef.eventid='".$EventId."' AND aef.status=1");
			//echo $EventId;
                        if(in_array($EventId,$this->supersonicEvents)) {
                           $selShareTpl = $this->get_supersonic_referal_mail_content();
                        }else {
                            $selShareTpl=$this->Globali->GetSingleFieldValue("SELECT Msg FROM EMailMsgs WHERE MsgType='emAffShare'");
                        }
			$link_to_share= _HTTP_SITE_ROOT."/event/".$event_url."&reffCode=".$referralCode;
			$subject=urlencode('Share and Get Discount');
			//$body=urlencode("I am attending this event, Join me and  you would get up to Rs. ".ceil($bookeeDiscTotal)." discount.<br><br>  ".$link_to_share);
			//$body = '';
                        //$maillink="mailto:?subject=".$subject."&amp;body=".$body;
                        $maillink= _HTTP_SITE_ROOT."/shareWithMail.php?reffCode=".$referralCode;
			$fblink='http://www.facebook.com/share.php?u='.urlencode($link_to_share);
			$twiterlink='http://twitter.com/home?status='.substr($title,0,100).'...'.$this->commonFunctions->getBitlyURL($link_to_share);
			$googlelink='https://plus.google.com/share?url='.urlencode($link_to_share);
			$linkedinlink='http://www.linkedin.com/shareArticle?mini=true&amp;url='.urlencode($link_to_share).'&amp;title='.$title.'&amp;source=Meraevents';
			$messageAff=str_replace('EventTitle',$title,$selShareTpl);
			$messageAff=str_replace('eventurl',$event_url,$messageAff);
			$messageAff=str_replace('Name',$name,$messageAff);
			$messageAff=str_replace('YOU_GET_UPTO_MESSAGE',$discountMessage,$messageAff);
			$messageAff=str_replace('REFFERAL_AMOUNT',$refAmount,$messageAff);
			$messageAff=str_replace('BOOKEE_AMOUNT',$bookAmount,$messageAff);
                        $messageAff=str_replace('refAmt',$refAmount,$messageAff);
			$messageAff=str_replace('bookAmt',$bookAmount,$messageAff);
			$messageAff=str_replace('sharelink',$link_to_share,$messageAff);
			$messageAff=str_replace('maillink',$maillink,$messageAff);
			$messageAff=str_replace('fblink',$fblink,$messageAff);
			$messageAff=str_replace('twiterlink',$twiterlink,$messageAff);
			$messageAff=str_replace('googlelink',$googlelink,$messageAff);
			$messageAff=str_replace('linkedinlink',$linkedinlink,$messageAff);
			$this->commonFunctions->sendEmail($delEmail,'','',$from,$replyto,'Share and get discount',$messageAff,'',$filename,$folder);
		}
                
                public function get_supersonic_referal_mail_content() {
                    $content = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MeraEvents.com</title>
</head>

<body>
<table width="650" border="0" cellspacing="0" cellpadding="0" align="center" style="background:#fef7ed; border:5px solid #dbdbdb;">
<tr>
<td><table width="560" border="0" cellspacing="0" cellpadding="0" align="center" style="background:#f5826d; padding:5px 0; margin:40px auto 0; box-shadow:0px 2px 0px #999; ">
<tr>
<td>
<p style="background:#f2e3ac; letter-spacing:0.03em; text-align:center; font-size:22px; font-weight:bold; font-family:Georgia, Times, serif; color:#333; padding:10px 0; margin:20px 0;"> SHARE & EARN</p>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td><table width="560" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td>
<p style="font-size:18px; letter-spacing:0.03em; font-weight:normal; line-height:24px; font-family:Georgia, Times, serif; color:#000; padding:10px 0; margin:20px 0 5px 0;">
<span style="width:100%; float:left; margin:0 0 15px 0;">Hi Name,</span>
<span style="width:100%; float:left; margin:0 0 20px 0;">Share this unique URL with your friends and you get up to REFFERAL_AMOUNT MeraEvents points (Redeemable on other Supersonic Events listed on MeraEvents in the year 2015),each time a friend books their passes.This would also give your friends a flat discount of Rs.BOOKEE_AMOUNT on their tickets.</span>
</p>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td><table width="560" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td>
<p style="background:#333; text-align:center; border-radius:5px; -moz-border-radius:5px; -webkit-border-radius:5px; padding:15px 0; margin:10px 0 20px 0;"> <a target="_blank" href="sharelink" style="text-align:center; font-size:15px; font-weight:bold; font-family:Georgia, Times, serif; color:#FFF; text-decoration:none; ">sharelink</a></p>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td><table width="560" border="0" cellspacing="0" cellpadding="0" align="center" style="margin-top:20px;">
<tr>
<td align="center" valign="middle"><a href="maillink" target="_blank"  class="nounderline"> <img src="http://duys0ue8c6g11.cloudfront.net/images/misc/mail-icon-inner.jpg" alt="Facebook" class="IconRight" title="email" /></a></td>
<td align="center" valign="middle"><a href="fblink" target="_blank" class="nounderline"> <img src="http://duys0ue8c6g11.cloudfront.net/images/misc/facebook-icon-inner.jpg" alt="Facebook" class="IconRight" title="Facebook" /></a></td>
<td align="center" valign="middle"><a href="twiterlink" target="_blank" class="nounderline"><img src="http://duys0ue8c6g11.cloudfront.net/images/twitter-1-h.jpg" style="border:0;width:32px;height:32px;" /></a></td>
<td align="center" valign="middle"><a href="googlelink" target="_blank" class="nounderline"><img src="http://duys0ue8c6g11.cloudfront.net/images/googleplus-1-h.jpg" style="border:0;width:32px;height:32px;" /></a></td>
<td align="center" valign="middle"><a href="linkedinlink" target="_blank" class="nounderline"><img src="http://duys0ue8c6g11.cloudfront.net/images/misc/linkedin-icon-inner.jpg" style="border:0" /></a></td>
</tr>
<tr>
<td align="center" valign="middle"><a href="maillink" target="_blank" style="font-family:Georgia, Times, serif; color:#333; text-decoration:none; padding:10px 0; line-height:25px;">Email your friends </a></td>
<td align="center" valign="middle"><a href="fblink" target="_blank" style="font-family:Georgia, Times, serif; color:#333; text-decoration:none; padding:10px 0; line-height:25px;">Share with Facebook</a></td>
<td align="center" valign="middle"><a href="twiterlink" target="_blank" style="font-family:Georgia, Times, serif; color:#333; text-decoration:none; padding:10px 0; line-height:25px;">Share with Twitter</a></td>
<td align="center" valign="middle"><a href="googlelink" target="_blank" style="font-family:Georgia, Times, serif; color:#333; text-decoration:none; padding:10px 0; line-height:25px;">Share with Google Plus</a></td>
<td align="center" valign="middle"><a href="linkedinlink" target="_blank" style="font-family:Georgia, Times, serif; color:#333; text-decoration:none; padding:10px 0; line-height:25px;">Share with Linkedin</a></td>
</tr>
</table>
</td>
</tr>
<tr>
<td style="border-top:4px solid #cbc6be; margin: 20px 0 0 0; float: left; width: 100%;">&nbsp;</td>
</tr>
<tr>
<td><table width="560" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td align="left" width="70%" style="font-size:14px; font-style:italic; color:#333; line-height:20px;">&copy; 2014, Versant Online Solutions Pvt.Ltd, All Rights Reserved.<br />
2nd Floor, 3 Cube Towers, Whitefield Road, Kondapur, Hyderabad, Andhra Pradesh - 500084</td>
<td align="right" width="30%"><a href="http://www.meraevents.com" target="_blank"><img src="http://www.meraevents.com/download/MeaEvents_Logo.png" border="0" width="112" height="71" /></a></td>
</tr>
</table>
</td>
</tr>
<tr>
<td style="height:20px;">&nbsp;</td>
</tr>
</table></body></html>';
                    return $content;
                }
                
                public function get_supersonic_referal_success_mail_content() {
                    $content = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<p style="background:#f2e3ac; letter-spacing:0.03em; text-align:center; font-size:20px; line-height:30px; font-weight:bold; font-family:Georgia, Times, serif; color:#333; padding:10px 0; margin:20px 0;"><strong><span align="center">CONGRATS!!! YOU HAVE EARNED</span><br/><span style="text-decoration:underline;"> Rs.Amount AS REFERRAL BONUS</span></strong></p>
</td>
</tr>
</table>



</td>
</tr>
<tr>
<td><table width="560" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td>
<p style="font-size:18px; letter-spacing:0.03em; font-weight:normal; line-height:24px; font-family:Georgia, Times, serif; color:#000; padding:10px 0; margin:20px 0 5px 0;">
<span style="width:100%; float:left; margin:0 0 15px 0;">Hi refereeName,</span>
<span style="width:100%; float:left; margin:0 0 24px 0;">Your Friend bookeeName just booked passes using your unique URL for "EventName".For inviting a friend you have just earned <b style="color:#ff6600;">Amount MeraEvents points </b> (Redeemable on other Supersonic Events listed on MeraEvents in the year 2015) in your account.</span>
<span style="width:100%; float:left; margin:0 0 20px 0;"><b style="color:#ff6600;">Note:</b></span>
<span style="width:100%; float:left; margin:0 0 20px 0;">The referral points would be redeemable on other Supersonic Events listed on MeraEvents in the year 2015 only.</span>
<span style="width:100%; float:left; margin:0 0 20px 0;">Click the below link to find the referral bonus you have earned</span>
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
<p style="background:#333; text-align:center; border-radius:5px; -moz-border-radius:5px; -webkit-border-radius:5px; padding:15px 0; margin:10px 0 20px 0;"> <a href="redeemLink" style="text-align:center; font-size:18px; font-weight:bold; font-family:Georgia, Times, serif; color:#FFF; text-decoration:none; ">CLICK HERE</a></p>
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
                <p style="font-size:18px; letter-spacing:0.03em; font-weight:normal; line-height:24px; font-family:Georgia, Times, serif; color:#000; padding:10px 0; margin:20px 0 5px 0;">
                <span style="width:100%; float:left; margin:0 0 15px 0;">Share this unique URL with your friends. You get up to Rs. REFFERAL_AMOUNT reward and up to Rs. BOOKEE_AMOUNT discount for your friends.</span>
                </p></td>
                </tr>
                </table>
        </td>
</tr>
<tr>
<td><table width="560" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td>
<p style="background:#333; text-align:center; border-radius:5px; -moz-border-radius:5px; -webkit-border-radius:5px; padding:15px 0; margin:10px 0 20px 0;"> <a target="_blank" href="sharelink" style="text-align:center; font-size:15px; font-weight:bold; font-family:Georgia, Times, serif; color:#FFF; text-decoration:none; ">sharelink</a></p>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td><table width="560" border="0" cellspacing="0" cellpadding="0" align="center" style="margin-top:20px;">
<tr>
<td align="center" valign="middle"><a href="maillink" target="_blank"  class="nounderline"> <img src="http://duys0ue8c6g11.cloudfront.net/images/misc/mail-icon-inner.jpg" alt="Facebook" class="IconRight" title="email" /></a></td>
<td align="center" valign="middle"><a href="fblink" target="_blank" class="nounderline"> <img src="http://duys0ue8c6g11.cloudfront.net/images/misc/facebook-icon-inner.jpg" alt="Facebook" class="IconRight" title="Facebook" /></a></td>
<td align="center" valign="middle"><a href="twiterlink" target="_blank" class="nounderline"><img src="http://duys0ue8c6g11.cloudfront.net/images/twitter-1-h.jpg" style="border:0;width:32px;height:32px;" /></a></td>
<td align="center" valign="middle"><a href="googlelink" target="_blank" class="nounderline"><img src="http://duys0ue8c6g11.cloudfront.net/images/googleplus-1-h.jpg" style="border:0;width:32px;height:32px;" /></a></td>
<td align="center" valign="middle"><a href="linkedinlink" target="_blank" class="nounderline"><img src="http://duys0ue8c6g11.cloudfront.net/images/misc/linkedin-icon-inner.jpg" style="border:0" /></a></td>
</tr>
<tr>
<td align="center" valign="middle"><a href="maillink" target="_blank" style="font-family:Georgia, Times, serif; color:#333; text-decoration:none; padding:10px 0; line-height:25px;">Email your friends </a></td>
<td align="center" valign="middle"><a href="fblink" target="_blank" style="font-family:Georgia, Times, serif; color:#333; text-decoration:none; padding:10px 0; line-height:25px;">Share with Facebook</a></td>
<td align="center" valign="middle"><a href="twiterlink" target="_blank" style="font-family:Georgia, Times, serif; color:#333; text-decoration:none; padding:10px 0; line-height:25px;">Share with Twitter</a></td>
<td align="center" valign="middle"><a href="googlelink" target="_blank" style="font-family:Georgia, Times, serif; color:#333; text-decoration:none; padding:10px 0; line-height:25px;">Share with Google Plus</a></td>
<td align="center" valign="middle"><a href="linkedinlink" target="_blank" style="font-family:Georgia, Times, serif; color:#333; text-decoration:none; padding:10px 0; line-height:25px;">Share with Linkedin</a></td>
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
<td align="left" width="70%" style="font-size:14px; font-style:italic; color:#333; line-height:20px;">&copy; 2014, Versant Online Solutions Pvt.Ltd, All Rights Reserved.<br />
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
</html>';
                    return $content;
                }
                
		public function sendCongratzMail($EventId,$email,$disAmount,$refAmount,$bookAmount,$bookeeName,$refName,$shareLink,$eventName,$discountMessage,$currencyCode=NULL)
		{
			$bookeeDiscTotal=$this->Globali->GetSingleFieldValue("SELECT aef.status, MAX(IF(commissiontype=1,(commissionvalue*Price)/100,commissionvalue)) AS bookeeAmount FROM aff_event_tickets aef INNER JOIN tickets t ON aef.ticketid=t.Id WHERE aef.eventid='".$EventId."' AND aef.status=1");
			$maillinkStr = substr($shareLink,strrpos($shareLink,'&')+1);
                        
                        if(in_array($EventId,$this->supersonicEvents)) {
                            $selShareTpl = $this->get_supersonic_referal_success_mail_content();
                        } else {
                            $selShareTpl=$this->Globali->GetSingleFieldValue("SELECT Msg FROM EMailMsgs WHERE MsgType='emAffCongratz'");
                        }
			
			$redeemLink=_HTTP_SITE_ROOT.'/Login?refurl=aff-referral-details';
			//$subject=urlencode('Share and Get Discount');
			//$body=urlencode("I am attending this event, Join me and  you would get up to ".$bookeeDiscTotal." discount.<br><br>  ".$shareLink);
			//$maillink="mailto:?subject=".$subject."&amp;body=".$body;
            $maillink = _HTTP_SITE_ROOT."/shareWithMail.php?".$maillinkStr;
			$fblink='http://www.facebook.com/share.php?u='.urlencode($shareLink);
			$twiterlink='http://twitter.com/home?status='.substr($eventName,0,100).'...'.$this->commonFunctions->get_tiny_url($shareLink);
			$googlelink='https://plus.google.com/share?url='.urlencode($shareLink);
			$linkedinlink='http://www.linkedin.com/shareArticle?mini=true&amp;url='.urlencode($shareLink).'&amp;title='.$eventName.'&amp;source=Meraevents';
			$msg=str_replace('YOU_GET_UPTO_MESSAGE_CONGRATZ',$discountMessage,$selShareTpl);
			$msg=str_replace('bookeeName',$bookeeName,$msg);
			$msg=str_replace('refereeName',$refName,$msg);
			$msg=str_replace('EventName',$eventName,$msg);
			if(!empty($currencyCode)){
				$msg=str_replace('Rs.',$currencyCode.' ',$msg);
			}
			$msg=str_replace('Amount',$disAmount,$msg);
			$msg=str_replace('REFFERAL_AMOUNT',$refAmount,$msg);
			$msg=str_replace('BOOKEE_AMOUNT',$bookAmount,$msg);
                        $msg=str_replace('refAmt',$refAmount,$msg);
			$msg=str_replace('bookAmt',$bookAmount,$msg);
			$msg=str_replace('redeemLink',$redeemLink,$msg);
			$msg=str_replace('sharelink',$shareLink,$msg);
			$msg=str_replace('maillink',$maillink,$msg);
			$msg=str_replace('fblink',$fblink,$msg);
			$msg=str_replace('twiterlink',$twiterlink,$msg);
			$msg=str_replace('googlelink',$googlelink,$msg);
			$msg=str_replace('linkedinlink',$linkedinlink,$msg);
			
			$this->commonFunctions->sendEmail($email,'','','MeraEvents<admin@meraevents.com>','','Congratz Your viral code has been used',$msg);
		}
		
		function updateMeCredits($points,$type,$uid)
		{
			$cts=$mts=date('Y-m-d H:i:s',strtotime("now"));
			$sqlIns="INSERT INTO aff_user_points(userid,points,type,cts,mts) VALUES(?,?,?,?,?)";
			$prepareStmt=$this->Globali->dbconn->prepare($sqlIns);
			$prepareStmt->bind_param("idiss",$uid,$points,$type,$cts,$mts);
			$dbQStatus=$prepareStmt->execute();
			$prepareStmt->close();
			$userMeCreditsQry = "SELECT mecredits FROM user WHERE Id='".$uid."'";
			$userMeCreditsQryRes = $this->Globali->SelectQuery($userMeCreditsQry);
			$userCredits = $userMeCreditsQryRes[0]['mecredits'] - (int)$points;
			$UpdateuserMeCreditsQry = "UPDATE user SET mecredits=$userCredits WHERE Id='".$uid."'";
			$this->Globali->ExecuteQuery($UpdateuserMeCreditsQry);
		}
}		//Class


/* >>>Useful notes>>>
 * >>Bind_Param Types (First Argument)
 � i: All INTEGER types
 � d: The DOUBLE and FLOAT types
 � b: The BLOB types
 � s: All other types (including strings)
 * <<Bind_Param Types<<
 *
 *
 */

?>