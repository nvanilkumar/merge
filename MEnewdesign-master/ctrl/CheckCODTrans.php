<?php
	session_start();
	
	include_once("MT/cGlobali.php");
	include_once("MT/cEvents.php");
	include_once("includes/common_functions.php");
	//for affiliate module to send mails
	include_once("MT/cAffiliate.php"); 
       include 'loginchk.php';


	$Global = new cGlobali();
	
	$common=new functions();
	
	$Affiliate=new cAffiliate();
	$MsgCountryExist = '';
	
	
	
	include("delegatepass.php");
	$delegatePass=new delegatePass();
	
	
	
        if ($_REQUEST[AddComment] == "Add Comment") {
    $sqlcheck = "select id from comment where eventsignupid='" . $_REQUEST[TransId] . "' and comment='" . $_REQUEST[comment] . "'";
    $CheckRES = $Global->SelectQuery($sqlcheck);
    if (count($CheckRES) > 0) {
        
    } else {
        $InsTransComments = "insert into comment(eventsignupid,comment,createdby,type)values('" . $_REQUEST[TransId] . "','" . $_REQUEST[comment] . "','".$_SESSION['uid']."'),'incomplete'";
        $indId = $Global->ExecuteQuery($InsTransComments);
    }
}
	 if(($_REQUEST[value]=="change" && $_REQUEST['depDt']=="") || $_REQUEST['selCodStatus']=="Refunded"){             
		$sqlup="update CODstatus set Status='".$_REQUEST['selCodStatus']."',tStatus='".$_REQUEST['selCodtStatus']."' where EventSIgnupId=".$_REQUEST['sId'];
		$Global->ExecuteQuery($sqlup); 
		$sqlsup="update EventSignup set delStatus='".$_REQUEST['selCodtStatus']."' where Id=".$_REQUEST['sId'];
		$Global->ExecuteQuery($sqlsup);   
		$sqlEve="select `STax`,`Fees`,`DAmount`,`Qty`,`Ccharge`,`EventId`, `UserId`,`referralDAmount`,c.code 'currencyCode' from EventSignup es INNER JOIN currencies c ON c.Id=es.CurrencyId where es.Id=".$_REQUEST['sId'];
		$resEve = $Global->SelectQuery($sqlEve);
		$selEMailMsgs = "SELECT Id, Msg, MsgType, SendThruEMailId FROM EMailMsgs WHERE MsgType ='emCodDelPass'";
		$dtlEMailMsgs = $Global->SelectQuery($selEMailMsgs);
		$Events = @new cEvents($resEve[0]['EventId']);
                $Success=$Events->Load();
		$EMailMsgId=$dtlEMailMsgs[0]['Id'];
		$selEMailSent = "SELECT UserId,EventSignupId FROM EMailSent WHERE UserId ='".$resEve[0]['UserId']."' and EventSignupId='".$_REQUEST['sId']."' AND EMailMsgId='".$EMailMsgId."'";
		$dtlEMailSent = $Global->SelectQuery($selEMailSent);              
 if($_REQUEST['selCodStatus']=="Delivered")
 {
	 if($dtlEMailSent[0]['UserId']=="" && $dtlEMailSent[0]['EventSignupId']=="")
	 {
             $sqlv="update EventSignup set eChecked='Verified' where Id=".$_REQUEST['sId'];
             $Global->ExecuteQuery($sqlv); 
             executeWizrocketCode($_REQUEST['sId'],$Events,$Global);

 
/*--------------Start Sending Delegate Pass----------------*/ 

//$sqlEve="select * from EventSignup where Id=".$_REQUEST['sId'];
  $EventId=$resEve[0]['EventId'];
//print_r($resEve);exit;



//updating PMI2 EventSignup->field2 value
if($EventId==56832){
	$sqlField="select MAX(field2) 'field2' from `EventSignup` where `EventId`='".$EventId."'";
	$dataField=$Global->SelectQuery($sqlField);
	if(count($dataField)>0){$filed2maxValue=$dataField[0]['field2']; if($filed2maxValue>500){$newfiled2maxValue=$filed2maxValue+1;}else{$newfiled2maxValue=501;}}else{$newfiled2maxValue=501;}
								   
								   
	$updateFieldStatusQ="update `EventSignup` set `field2`='".$newfiled2maxValue."' where `EventId`='".$EventId."' and `Id`='".$_REQUEST['sId']."'";
	$Global->ExecuteQuery($updateFieldStatusQ);
}
//updating PMI2 EventSignup->field2 value


if($EventId==59560) //TEDx2014 event
{
	$sqlTed="select `Email` from `Attendees` where `EventSIgnupId`='".$_REQUEST['sId']."'";
	$tedEmail=$Global->GetSingleFieldValue($sqlTed);
	
	$MembershipType='TEDx2014';
    $updateTESStatusQuery="update `onlyfor_tes` set `Status`=1 where `Email`='".$tedEmail."'  and `MembershipType`='TEDx2014' ";
	$Global->ExecuteQuery($updateTESStatusQuery);
}	







$TSeats=$Global->GetSingleFieldValue("SELECT EventId from VenueSeats WHERE EventId='".$resEve[0]['EventId']."'");
		if($TSeats>0){
		$seatQuery= "SELECT GridPosition,Seatno FROM VenueSeats WHERE EventSIgnupId='".$_REQUEST['sId']."'";
		$seatRes = $Global->SelectQuery($seatQuery);
               $SNo="";
		for($s=0;$s<count($seatRes);$s++)
		{
		$SNo.= substr($seatRes[$s]['GridPosition'],0,1).$seatRes[$s]['Seatno'].", ";
		}
		$SeatsNo=" <b>Seat Nos-</b> ".substr($SNo,0,-1);
		} else {
		$SeatsNo="";
		}
 	
include("../Extras/mpdf/mpdf.php");
$mpdf=new mPDF();
	if(empty($Events->Logo) || ($Events->Logo=='eventlogo/'))
	{
	 $sql_logo=$Global->GetSingleFieldValue("select CLogo from organizer where UserId=".$Events->UserID); 
	 if(empty($sql_logo) || $sql_logo =="logo/"){
	 $eventlogo=_HTTP_CF_ROOT."/images/mera_logo1.jpg";
	 }else{
	 $eventlogo=_HTTP_CDN_ROOT."/".$sql_logo;
	 }
	 }else{
	 $eventlogo=$common->getResizedImagepath(_HTTP_CDN_ROOT."/".$Events->Logo);
	 }
	 $sqluser="select FirstName,Email,Company from user where Id=".$resEve[0]['UserId'];
        $resuser = $Global->SelectQuery($sqluser);
		
   $display_print_pass_amount=$Global->GetSingleFieldValue("SELECT display_amount  FROM events WHERE Id = '".$Events->Id."'");  
	$data=$delegatePass->getPrintPassPDF($Events->Id,$_REQUEST['sId'],$display_print_pass_amount);
  
						  
    $data = utf8_encode($data);
	$mpdf->WriteHTML($data);
	
	$content = $mpdf->Output('', 'S');
	$qSelEventDtls = "SELECT Title,ContactDetails,OEmails,URL,isWebinar,CityId FROM events WHERE Id = '".$resEve[0]['EventId']."'";
	$aEventDtls = $Global->SelectQuery($qSelEventDtls);
$userPreviewLink=_HTTP_SITE_ROOT."/event/".$aEventDtls[0]['URL'];
//$content = chunk_split(base64_encode($content));

$from_name = 'Meraevents.com';
$from_mail = 'info@meraevents.com';
$replyto = 'sunila@meraevents.com';
$uid = md5(uniqid(time()));
$subject = 'Delegate Pass for the event '.$Events->Title;
//$DisplayName = $Global->GetSingleFieldValue("SELECT DisplayName FROM delegate WHERE UserId = '".$_SESSION['uid']."'"); 
 
  $DisplayName=$resuser[0][FirstName];
  $mailto=$resuser[0][Email];
//$mailto = 'sunila@meraevents.com';
$EMailMsgId = $dtlEMailMsgs[0]['Id'];
$message = str_replace("FirstName",$DisplayName,$dtlEMailMsgs[0]['Msg']);
$message = str_replace("EventTitle",$Events->Title,$message);
$message = str_replace("EventIdNumber",$resEve[0]['EventId'],$message);
$message = str_replace("EventContact",$aEventDtls[0]['ContactDetails'],$message);
$message = str_replace("userPreviewLink",$userPreviewLink,$message);
$FBSharelink = 'http://www.facebook.com/share.php?u=' . $userPreviewLink . '&title=Meraevents -' . stripslashes($Title);
$message = str_replace("FBSharelink", $FBSharelink, $message);
$TwitterSharelink = 'http://twitter.com/home?status=Meraevents -' . substr(stripslashes($Title),0,90) . '...+' . $common->get_tiny_url($userPreviewLink);
$message = str_replace("TwitterSharelink", $TwitterSharelink, $message);
$filename = 'Delegate.pdf';

/*$header = "From: ".$from_name." <".$from_mail.">\r\n";
$header .= "Bcc: sunila@meraevents.com,amareshwarilinga@meraevents.com \r\n";
$header .= "Reply-To: ".$replyto."\r\n";
$header .= "MIME-Version: 1.0\r\n";
$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
$header .= "This is a multi-part message in MIME format.\r\n";
$header .= "--".$uid."\r\n";
$header .= "Content-type:text/html; charset=iso-8859-1\r\n";
$header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
$header .= $message."\r\n\r\n";
$header .= "--".$uid."\r\n";
$header .= "Content-Type: application/pdf; name=\"".$filename."\"\r\n";
$header .= "Content-Transfer-Encoding: base64\r\n";
$header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
$header .= $content."\r\n\r\n";
$header .= "--".$uid."--";*/
$is_sent = $common->sendEmail( $mailto,'','sunila@meraevents.com,amareshwarilinga@meraevents.com',$from_mail,$replyto,$subject,$message,$content,$filename,'ctrl');
        /*
         *  START ---code related to sending UBER emails
         */
       
        if($aEventDtls[0]['isWebinar']!=1 && $common->isUberAvailable($aEventDtls[0]['CityId'],$resEve[0]['EventId'],$Global))
        {
        $common->sendUberMail($mailto,$Global);
        }
        /*
         *  END ---code related to sending UBER emails
         */

$uid=$resEve[0]['UserId'];
$sSentDt = date('Y-m-d h:i:s');
$sqlQry="INSERT INTO EMailSent (UserId, EMailMsgId,EventSignupId, SentDt) VALUES ('".$uid."','".$EMailMsgId."','".$_REQUEST['sId']."','".$sSentDt."')";
$EMailSentStmt=$Global->ExecuteQueryId($sqlQry);

 /******
 **code for Affiliate Module Referral link **
 *******/
        $aff_status=1; 
        //$inQuery = "select Id from eventsignupticketdetails where EventSignupId='".$_REQUEST['sId']."'";
	//$affSalesQry="update `aff_sales` set `status`=1 where `eventSignupTicketDetailsId` in (select Id from eventsignupticketdetails where EventSignupId='".$_REQUEST['sId']."')";
	
	//$affSalesQry="update `aff_user_points` set `status`=1 where `id`=(select `senderUserPointsId` from `aff_sales` where `eventSignupTicketDetailsId` = (select Id from eventsignupticketdetails where EventSignupId='".$_REQUEST['sId']."' limit 1))";
	
	
	//getting aff_user_points table `id`, to update `status` column 
	$sqlUserPointsId=$Global->GetSingleFieldValue("SELECT afs.senderUserPointsId FROM aff_sales as afs inner join eventsignupticketdetails as estd on
afs.eventSignupTicketDetailsId= estd.Id where estd.EventSignupId='".$_REQUEST['sId']."' limit 1");
//var_dump($sqlUserPointsId);
	if($sqlUserPointsId>0) //found (atleast) one record in aff_sales table
	{
		
		//updating aff_user_points table status
		$affUserPointsQry="update `aff_user_points` set `status`=1 where `id`='".$sqlUserPointsId."'";
		$Global->ExecuteQuery($affUserPointsQry);
	}
	
	
	//echo $affSalesQry2; exit;
	
		
								
       
 /******
  **End of code for Affiliate Module Referral link **
  *******/ 
	//code related to sending referral link email 
	 //$selAffEveTck="SELECT aef.status,MAX(IF(aef.commissiontype=1,((aef.refereeCommValue*t.Price)/100),(aef.refereeCommValue))) AS refereeAmount,MAX(IF(aef.commissiontype=1,(aef.commissionvalue*t.Price)/100,aef.commissionvalue)) AS bookeeAmount,SUM(estd.NumOfTickets) FROM aff_event_tickets aef, tickets t,eventsignupticketdetails estd where aef.ticketid=t.Id and t.Id=estd.TicketId and aef.eventid='".$resEve[0]['EventId']."' AND aef.status=1";
	$selAffEveTck="SELECT aef.status,ifnull(SUM(IF(aef.commissiontype=1,(((aef.refereeCommValue*estd.TicketAmt)/100)),(aef.refereeCommValue*estd.NumOfTickets))),0) AS totalRefereeAmt,sum(estd.NumOfTickets) FROM aff_event_tickets aef INNER JOIN eventsignupticketdetails estd ON aef.ticketid=estd.TicketId where  aef.eventid='".$resEve[0]['EventId']."' and estd.EventSignupId='".$_REQUEST['sId']."'  AND aef.status=1";
        //echo $selAffEveTck."<br>";
	$selAffEveTckRes=$Global->SelectQuery($selAffEveTck);
	
	$discountMessage='You get up to Rs.REFFERAL_AMOUNT reward and up to Rs.BOOKEE_AMOUNT discount for your friends.';
			   $multipleCurrencyTkts=$Global->SelectQuery("SELECT currencyId FROM tickets t WHERE EventId='".$EventId."' and t.Price>0 GROUP BY t.currencyId");
			   if(count($multipleCurrencyTkts)>1){
			   		$showDiscountMsg=false;
					$discountMessage='';
			   }
	 $selComm="SELECT aet.status,MAX(IF(aet.commissiontype=1,((aet.refereeCommValue*t.Price)/100),(aet.refereeCommValue))) AS refereeAmount,MAX(IF(aet.commissiontype=1,(aet.commissionvalue*t.Price)/100,aet.commissionvalue)) AS bookeeAmount FROM aff_event_tickets aet INNER JOIN tickets t ON t.Id=aet.ticketId where aet.eventid='".$resEve[0]['EventId']."' and aet.status=1";
	$selCommRes=$Global->SelectQuery($selComm);
	//CHECK SHARE MAIL SENT OR NOT
	$msgId=$Global->GetSingleFieldValue("SELECT Id FROM EMailMsgs WHERE MsgType='emAffShare'");
	$emailSent=$Global->GetSingleFieldValue("SELECT Id FROM EMailSent WHERE EMailMsgId='".$msgId."' AND EventSignupId='".$_REQUEST['sId']."'");
	//send mail only if commission exits
	$refAmount=$bookAmount=$title=$event_url=NULL;
	if($selCommRes[0]['status']==1 && count($emailSent)==0){
				$from='MeraEvents<admin@meraevents.com>';
				$folder='ctrl';
				$title=$aEventDtls[0]['Title'];
				$refAmount=$selCommRes[0]['refereeAmount'];
				$bookAmount=$selCommRes[0]['bookeeAmount'];
				
				$referralCode=$common->generateRandomString(8);
				$event_url=$aEventDtls[0]['URL'];
				$Affiliate->sendShareMail($EventId,$mailto,$from,$replyto,$event_url,$title,$referralCode,$DisplayName,$refAmount,$bookAmount,$discountMessage,$folder);
				$uid=$resEve[0]['UserId'];
				$sSentDt = date('Y-m-d h:i:s');
				$sqlQry="INSERT INTO EMailSent (UserId, EMailMsgId,EventSignupId, SentDt) VALUES ('".$uid."','".$msgId."','".$_REQUEST['sId']."','".$sSentDt."')";
				$EMailSentStmt=$Global->ExecuteQueryId($sqlQry);
				$UpEventSignupStmt=$Global->ExecuteQuery("UPDATE EventSignup SET referralcode='".$referralCode."' WHERE Id='".$_REQUEST['sId']."'");
				
	}
	//send mail only when its not send already(refresh issue) 
	$msgId=$Global->GetSingleFieldValue("SELECT Id FROM EMailMsgs WHERE MsgType='emAffCongratz'");
	$emailSent=$Global->GetSingleFieldValue("SELECT Id FROM EMailSent WHERE EMailMsgId='".$msgId."' AND EventSignupId='".$_REQUEST['sId']."'");
	if(count($emailSent)==0){
		$dtlQry="SELECT u.FirstName,a.referralCode,u.Id,u.EMail as email,IF(aef.commissiontype=0,aef.refereeCommValue,estd.TicketAmt*(aef.refereeCommValue/100)) as amount FROM EventSignup es INNER JOIN eventsignupticketdetails estd ON estd.EventSignupId=es.Id AND es.Id='".$_REQUEST['sId']."' 
					INNER JOIN aff_sales a ON  estd.Id=a.eventSignupTicketDetailsId 
					INNER JOIN aff_event_tickets aef ON a.affiliateEventTicketId=aef.Id
					INNER JOIN user u ON u.Id=a.senderUserId";
		$res=$Global->SelectQuery($dtlQry);
		if(count($res)>0){
			
			$senderUserId=$res[0]['Id'];
			$sSentDt = date('Y-m-d h:i:s');
			$eventSignupId=$_REQUEST['sId'];
			$disAmount=$res[0]['amount'];
			$email=$res[0]['email'];
			$refName=$res[0]['FirstName'];
			$ownerReffCode=$res[0]['referralCode'];
			$shareLink=_HTTP_SITE_ROOT."/event/".$event_url."&reffCode=".$ownerReffCode;
			/*
			* update ME Credits in User table                    
			*/
		   $userMeCreditsQry = "SELECT mecredits FROM user WHERE Id='".$senderUserId."'";
		   $userCredits = $Global->SelectQuery($userMeCreditsQry);
		   $userCredits = $userCredits[0]['mecredits'] + $disAmount;
		   
		   $UpdateuserMeCreditsQry = "UPDATE user SET mecredits=$userCredits WHERE Id='".$senderUserId."'";
		   $Global->ExecuteQuery($UpdateuserMeCreditsQry);
		   
			
		   //End of updating users table
		   //$Affiliate->sendCongratzMail($EventId,$email,$amount,$DisplayName);
		   $Affiliate->sendCongratzMail($EventId,$email,$selAffEveTckRes[0]['totalRefereeAmt'],$refAmount,$bookAmount,$DisplayName,$refName,$shareLink,$title,$discountMessage);
		   $EMailSentStmt=$Global->ExecuteQuery("INSERT INTO EMailSent (UserId, EMailMsgId,EventSignupId, SentDt) VALUES ('".$senderUserId."','".$msgId."','".$eventSignupId."','".$sSentDt."')");
		   /*$EMailSentStmt->bind_param("dids",$senderUserId,$msgId,$eventSignupId,$sSentDt);
		   $EMailSentStmt->execute();
		   $EMailSentStmt->close();*/
		}
	}
	//update credits
	if($dbSessData['redeemPointsUsed']>0){
		$points=$dbSessData['redeemPointsUsed'];
		$type=0;
		$uid=$resEve[0]['UserId'];
		$Affiliate->updateMeCredits($points,$type,$uid);
	}
	
			/*$qSelectRegDtls = "SELECT FirstName,LastName,Company,DesignationId,CityId, UserName, Email, Mobile FROM user WHERE Id = '".$resEve[0]['UserId']."'";

			
	//getting first attendee details
   		$firstAttendeeId=$Global->GetSingleFieldValue("SELECT Id from Attendees WHERE EventSIgnupId='".$_REQUEST['sId']."' order by Id ASC limit 1");
		
		$getCustomfields="select `Id`,`EventCustomFieldName` from `eventcustomfields` where `EventCustomFieldName` in ('Full Name','Email Id','Company Name','Designation','Mobile No','City')  and `EventId`='".$EventId."'";
		$customfieldsDataDB=$Global->SelectQuery($getCustomfields);
		
		
		/*$customfieldsData=array();
		foreach($customfieldsDataDB as $cindex=>$cval)
		{
			$customfieldsData[$cval['EventCustomFieldName']]=$cval['Id'];
		}
		
		
		for($c=0;$c<count($customfieldsDataDB);$c++)
		{
			if($customfieldsDataDB[$c]['EventCustomFieldName']=="Full Name"){$custAttName=$Global->GetSingleFieldValue("SELECT `EventSignupFieldValue` from `eventsignupcustomfields` WHERE `EventCustomFieldsId`='".$customfieldsDataDB[$c]['Id']."' and `attendeeId`='".$firstAttendeeId."' order by Id desc limit 1");}
			elseif($customfieldsDataDB[$c]['EventCustomFieldName']=="Email Id"){$custAttEmail=$Global->GetSingleFieldValue("SELECT `EventSignupFieldValue` from `eventsignupcustomfields` WHERE `EventCustomFieldsId`='".$customfieldsDataDB[$c]['Id']."' and `attendeeId`='".$firstAttendeeId."'  and `attendeeId`='".$firstAttendeeId."'  order by Id desc limit 1");}
			elseif($customfieldsDataDB[$c]['EventCustomFieldName']=="Company Name"){$custAttComp=$Global->GetSingleFieldValue("SELECT `EventSignupFieldValue` from `eventsignupcustomfields` WHERE `EventCustomFieldsId`='".$customfieldsDataDB[$c]['Id']."' and `attendeeId`='".$firstAttendeeId."'  order by Id desc limit 1");}
			elseif($customfieldsDataDB[$c]['EventCustomFieldName']=="Designation"){$custAttDesig=$Global->GetSingleFieldValue("SELECT `EventSignupFieldValue` from `eventsignupcustomfields` WHERE `EventCustomFieldsId`='".$customfieldsDataDB[$c]['Id']."' and `attendeeId`='".$firstAttendeeId."'  order by Id desc limit 1");}
			elseif($customfieldsDataDB[$c]['EventCustomFieldName']=="Mobile No"){$custAttMobile=$Global->GetSingleFieldValue("SELECT `EventSignupFieldValue` from `eventsignupcustomfields` WHERE `EventCustomFieldsId`='".$customfieldsDataDB[$c]['Id']."'  and `attendeeId`='".$firstAttendeeId."' order by Id desc limit 1");}
			elseif($customfieldsDataDB[$c]['EventCustomFieldName']=="City"){$custAttCity=$Global->GetSingleFieldValue("SELECT `EventSignupFieldValue` from `eventsignupcustomfields` WHERE `EventCustomFieldsId`='".$customfieldsDataDB[$c]['Id']."'  and `attendeeId`='".$firstAttendeeId."' order by Id desc limit 1");}
		}
 
	
	
$qSelectRegDtls = "SELECT FirstName,LastName,Company,DesignationId,CityId, UserName, Email, Mobile FROM user WHERE Id = '".$resEve[0]['UserId']."'";

			$aRegDtls = $Global->SelectQuery($qSelectRegDtls);
		
			$FirstName = $aRegDtls[0]['FirstName'];
			$LastName = $aRegDtls[0]['LastName'];
			$Company = $aRegDtls[0]['Company'];
			$DelCity =$Global->GetSingleFieldValue("select City from Cities where Id=".$aRegDtls[0]['CityId']); 

			if($aRegDtls[0]['DesignationId']!=0)
			{
				$sql_designation="select Designation from Designations where Id='".$aRegDtls[0]['DesignationId']."'";
				$sql_des=$Global->SelectQuery($sql_designation);
				$Designation=$sql_des[0][Designation];
			}
			else
			{
				$Designation=NULL;
			}
			
			$Email = $aRegDtls[0]['Email'];
			$MobileNo = preg_replace("/[^0-9]/i", "", $aRegDtls[0]['Mobile']);
			
			
			if(strlen(trim($custAttComp))==0){$custAttComp = NULL;}
		if(strlen(trim($custAttCity))==0){$custAttCity = NULL;}
		if(strlen(trim($custAttDesig))==0){$custAttDesig = NULL;}
		if(strlen(trim($custAttEmail))==0){$custAttEmail = NULL;}
		if(strlen(trim($custAttMobile))==0){$custAttMobile = NULL;}
		
			
			$sql_org = "SELECT  FirstName, Email FROM user WHERE Id = '".$Events->UserID."'";
			$res_org = $Global->SelectQuery($sql_org);
				
			$to = $res_org[0]['Email'];
			$subject = 'You have received a successful registration for '.stripslashes($Title)." ".$promo;

			$selESQuery= "SELECT PaymentTransId,Fees,Qty,Id FROM EventSignup WHERE Id='".$_REQUEST['sId']."'";
			$dtlES = $Global->SelectQuery($selESQuery);
			
			$PaymentTransId = $dtlES[0]['PaymentTransId'];
			$Fees = $dtlES[0]['Fees']*$dtlES[0]['Qty'];
			$delqty=$dtlES[0]['Qty'];
			$InvoiceNo = $dtlES[0]['Id'];
			
			$selEMailMsgs = "SELECT Id, Msg, MsgType, SendThruEMailId FROM EMailMsgs WHERE MsgType ='emOrgCod'";
			$dtlEMailMsgs = $Global->SelectQuery($selEMailMsgs);
			
			$EMailMsgId = $dtlEMailMsgs[0]['Id'];
			
			$orgMailBody='<tr>
                    <td height="20">Name : <b>&nbsp;'.$custAttName.'</b> </td>
                  </tr>';
			if(strlen($custAttComp)>0)
			{
				$orgMailBody.='<tr>
                    <td height="20">Company :  <b>'.$custAttComp.'</b></td>
                  </tr>';
			}
			if(strlen($custAttDesig)>0)
			{
				$orgMailBody.='<tr>
                    <td height="20">Designation :  <b>'.$custAttDesig.'</b></td>
                  </tr> ';
			}
			if(strlen($custAttCity)>0)
			{
				$orgMailBody.='<tr>
                    <td height="20">City:  <b>'.$custAttCity.'</b></td>
                  </tr>';
			}
			if(strlen($custAttEmail)>0)
			{
				$orgMailBody.='<tr>
                    <td height="20">Email Id:  <b>'.$custAttEmail.'</b></td>
                  </tr>';
			}if(strlen($custAttMobile)>0)
			{
				$orgMailBody.='<tr>
                    <td height="20">Mobile :  <b>'.$custAttMobile.'</b></td>
                  </tr>';
			}
			
			
			$message  = str_replace("OrgFirstName", $res_org[0]['FirstName'], $dtlEMailMsgs[0]['Msg']);
			$message  = str_replace("EventName", $aEventDtls[0]['Title'], $message);
			$message  = str_replace("FirstName", $FirstName, $message);		
			$message  = str_replace("LastName", $LastName, $message);
			$message  = str_replace("CompanyName", $Company, $message);
			$message  = str_replace("DesignationName", $Designation, $message);
			$message  = str_replace("delcty", $DelCity, $message);
			$message  = str_replace("DelEMail", $Email, $message);
			$message  = str_replace("orgMailBody", $orgMailBody, $message);
			$message  = str_replace("MobileNo", $MobileNo, $message);
			$message  = str_replace("InvoiceNo", $_REQUEST['sId'], $message);
			$message  = str_replace("chkamt", $Fees, $message);
			$message  = str_replace("delqty", $delqty, $message);
			$message  = str_replace("EventIdNumber", $EventId, $message);
			$message  = str_replace("userPreviewLink", $userPreviewLink, $message3);
					
			$selEMailSentorg = "SELECT UserId,EventSignupId FROM EMailSent WHERE UserId ='".$resEve[0]['UserId']."' and EventSignupId='".$_REQUEST['sId']."'";
			$dtlEMailSentorg = $Global->SelectQuery($selEMailSentorg);
			if($dtlEMailSentorg[0][UserId]=="" && $dtlEMailSentorg[0][EventSignupId]=="")
			{
				$headers = "From:".$dtlEMailMsgs[0]['SendThruEMailId']."\r\n" ;
				
				$headers.='Bcc: amareshwarilinga@meraevents.com,kumard@meraevents.com, sunila@meraevents.com' . "\r\n".           
				'X-Mailer: PHP/' . phpversion() . "\r\n" .
				"MIME-Version: 1.0\r\n" .
				"Content-Type: text/html; charset=utf-8\r\n" .
				"Content-Transfer-Encoding: 8bit\r\n\r\n";
				 if($OEmails!=""){
				$to.=",".$OEmails;
				}
				//mail($to, $subject, $message, $headers);
				//$common->sendEmail($to,'','sunila@meraevents.com,amareshwarilinga@meraevents.com',$from_mail,$replyto,$subject,$message,$content,$filename,'ctrl');
				$sqlQry="INSERT INTO EMailSent (UserId, EMailMsgId,EventSignupId, SentDt) VALUES ('".$uid."','".$selEMailMsgs[0]['Id']."','".$_REQUEST['sId']."','".$sSentDt."')";
				$EMailSentStmt=$Global->ExecuteQueryId($sqlQry);
			} */
	 
			}
}
/*--------------End Sending Delegate Pass----------------*/ 
 else  if($_REQUEST['selCodStatus']=="Canceled")
 {
   $cancelUserDetails=$Global->SelectQuery("select eChecked,Mobile,u.Email,es.Id,u.Id as userId from EventSignup es INNER JOIN user u ON es.UserID=u.Id WHERE es.Id='".$_REQUEST['sId']."'");
if($cancelUserDetails[0]['eChaecked']!="Canceled"){
    $user_mobile_no=$cancelUserDetails[0]['Mobile'];
    $event_signup_id=$cancelUserDetails[0]['Id'];
    $user_id=$cancelUserDetails[0]['userId'];
    $email=$cancelUserDetails[0]['Email'];
    send_cancel_cod_sms($Global, $user_mobile_no, $user_id, $event_signup_id,'smscodcanceled',$email);
$sqlv="update EventSignup set eChecked='Canceled' where Id=".$_REQUEST['sId'];
 $Global->ExecuteQuery($sqlv); 
 $sqla="update Attendees set PaidBit=0 where EventSIgnupId=".$_REQUEST['sId'];
 $Global->ExecuteQuery($sqla); 
 $update_query1="UPDATE VenueSeats SET Status='Available',EventSIgnupId=0,BDate='0000-00-00 00:00:00' WHERE EventSIgnupId='".$_REQUEST['sId']."'";
 $Global->ExecuteQuery($update_query1); 
  $sqlqty= "SELECT TicketId,NumOfTickets FROM eventsignupticketdetails where EventSignupId=".$_REQUEST['sId']; 
   $dtqty = $Global->SelectQuery($sqlqty);
 	for($i=0; $i < count($dtqty); $i++)
		{
		$tQty=$Global->GetSingleFieldValue("select ticketLevel from tickets where Id='".$dtqty[$i]['TicketId']."'");
		$pqty=$tQty-$dtqty[$i]['NumOfTickets'];
		$sqlqt="update tickets set ticketLevel=$pqty where Id=".$dtqty[$i]['TicketId'];
        $Global->ExecuteQuery($sqlqt);
		} 
	}
        header("Location:CheckCODTrans.php?cancelSMS=sent");
 }
 else  if($_REQUEST['selCodStatus']=="Refunded")
 {
 $sqlv="update EventSignup set eChecked='Refunded' where Id=".$_REQUEST['sId'];
 $Global->ExecuteQuery($sqlv); 
 }else {
	$sqlv="update EventSignup set eChecked='NotVerified' where Id=".$_REQUEST['sId'];
 	$Global->ExecuteQuery($sqlv);
} 	
}	
  if($_REQUEST['depDt']!="")
	{
	    $DepDt = $_REQUEST['depDt'];
		$DepDtExplode = explode("/", $DepDt);
		$DEPDATE = $DepDtExplode[2].'-'.$DepDtExplode[1].'-'.$DepDtExplode[0].' 00:00:00';
		$sqld="update EventSignup set DepositedDate='".$DEPDATE."' where Id=".$_REQUEST['sId'];
 $Global->ExecuteQuery($sqld);
		}
	
	
    if($_REQUEST['txtSDt']!="" && $_REQUEST['txtEDt'] != "")
	{
		$SDt = $_REQUEST['txtSDt'];
		$SDtExplode = explode("/", $SDt);
		$yesterdaySDate = $SDtExplode[2].'-'.$SDtExplode[1].'-'.$SDtExplode[0].' 00:00:00';
		
		$EDt = $_REQUEST['txtEDt'];
		$EDtExplode = explode("/", $EDt);
		$yesterdayEDate = $EDtExplode[2].'-'.$EDtExplode[1].'-'.$EDtExplode[0].' 23:59:59';
		$dates=" and s.SignupDt >= '".$yesterdaySDate."' AND s.SignupDt <= '".$yesterdayEDate."' ";
		
	}else if($_REQUEST['txtSDt']=="" && $_REQUEST['txtEDt'] == ""){
               /* $SDt = date ("d/m/Y", mktime (0,0,0,date("m"),(date("d")-1),date("Y")));
	         $EDt =date ("d/m/Y", mktime (0,0,0,date("m"),(date("d")-1),date("Y")));
                 $yesterdaySDate = date ("Y-m-d", mktime (0,0,0,date("m"),(date("d")-1),date("Y"))).' 00:00:01';
	$yesterdayEDate = date ("Y-m-d", mktime (0,0,0,date("m"),(date("d")-1),date("Y"))).' 23:59:59';
	$dates=" and s.SignupDt >= '".$yesterdaySDate."' AND s.SignupDt <= '".$yesterdayEDate."' ";
*/
               }
	
	 else if($_REQUEST[recptno]=="")
	{
	  $SDt = date ("d/m/Y", mktime (0,0,0,date("m"),(date("d")-1),date("Y")));
	  $EDt =date ("d/m/Y", mktime (0,0,0,date("m"),(date("d")-1),date("Y")));
		$yesterdaySDate = date ("Y-m-d", mktime (0,0,0,date("m"),(date("d")-1),date("Y"))).' 00:00:01';
	$yesterdayEDate = date ("Y-m-d", mktime (0,0,0,date("m"),(date("d")-1),date("Y"))).' 23:59:59';
	$dates=" and s.SignupDt >= '".$yesterdaySDate."' AND s.SignupDt <= '".$yesterdayEDate."' ";
	} 
	
	$status="";
	if(isset($_REQUEST[Status]) && $_REQUEST[Status]!="")
	{
	 if($_REQUEST[Status]=="All"){
	 $status="";
	 }else{
	$status=" AND s.eChecked ='".$_REQUEST[Status]."'";
	}
	}else{
         $status=" AND s.eChecked ='NotVerified'";
         $_REQUEST[Status]='NotVerified';
        }
	
		
	
	if(isset($_REQUEST[recptno]) && $_REQUEST[recptno]!=""){
	$signid=" and s.Id=".$_REQUEST[recptno];
	}
	
	$EventId=NULL;
	if(!empty($_REQUEST['EventId']) || !empty($_REQUEST['eventIdSrch'])){
            if(!empty($_REQUEST['EventId']))
			{
				$EventId=$_REQUEST['EventId'];
                $EventIdSql=" and s.EventId='".$_REQUEST['EventId']."'";
			}
            else if(!empty($_REQUEST['eventIdSrch']))
			{
				$EventId=$_REQUEST['eventIdSrch'];
            	$EventIdSql=" and s.EventId='".$_REQUEST['eventIdSrch']."'";
			}
	}
	

 if($_REQUEST['SerEventName']!="")
	{
	 $sqlid = "SELECT Id,UserId FROM orgdispnameid where OrgId=".$_REQUEST['SerEventName'] ;
                  $dtsqlid1 = $Global->SelectQuery($sqlid);
                for($i=0;$i<count($dtsqlid1);$i++)
                    {
                    $orgid1.=$dtsqlid1[$i][UserId].","; 
                    }
                  
                   $orgid=substr($orgid1,0,-1);
                   $SearchQuery =" AND e.UserID in (".$orgid.") " ;  

	}

	
	$TotalAmount = 0;
	$cntTransactionRES = 1;	
	
	//Display list of Successful Transactions
   	$TransactionQuery = "SELECT s.EventId, s.Id, s.SignupDt, s.Qty, s.Fees,  e.Title,s.eChecked,s.DepositedDate, s.Phone,s.Email,s.UserId as user_id,e.URL,u.Email as OrgEmail FROM EventSignup AS s INNER JOIN events AS e ON s.EventId = e.Id LEFT OUTER JOIN user u ON u.Id=e.UserID
      WHERE 1 $dates AND (s.Fees != 0 AND (s.PaymentGateway = 'CashonDelivery' ))  $signid $status  $EventIdSql $SearchQuery ORDER BY  s.SignupDt DESC";  
	 $TransactionRES=$Global->SelectQuery($TransactionQuery); 
		
		
		$EventQuery = "SELECT distinct(s.EventId), e.Title AS Details FROM EventSignup AS s INNER JOIN events AS e ON s.EventId = e.Id AND s.Fees !=0   ORDER BY e.Title  DESC"; 
	$EventQueryRES = $Global->SelectQuery($EventQuery);
			
    //cod email msg id
    $selEMailMsgs = "SELECT Id,Msg,MsgType,SendThruEMailId
                     FROM EMailMsgs WHERE MsgType ='cod_warning_mail'";
    $message_details = $Global->SelectQuery($selEMailMsgs);
    $cod_message_id = $message_details[0]['Id'];
 
   //T0 send cod email
    if($_POST['submit']==="send email"){
        
        $email_message=$_POST['email_message'];
        $to_email=$_POST['user_email_id'];
        $user_id=$_POST['user_id'];
        $event_signup_id=$_POST['event_signup_id'];
        $event_url=$_POST['event_url'];
        $user_mobile_no=$_POST['user_mobile'];
        $event_org_email=$_POST['event_org_email'];
        
        send_cancel_cod_email($Global,$to_email,$email_message,$event_signup_id,$user_id,$common,$message_details, $event_url,$event_org_email);
        
        send_cancel_cod_sms($Global,$user_mobile_no,$user_id,$event_signup_id,'smscodwarning');
    }
    
    //To check the email sended to the user 
    function check_event_signup_email_status($Globali,$event_signup_id,$user_id,$message_id){
       $email_send_query = "SELECT Id FROM EMailSent 
             WHERE EventSignupId =".$event_signup_id." and EMailMsgId=".$message_id." and  UserId=".$user_id; 
        $email_send = $Globali->SelectQuery($email_send_query);
        
        if(count($email_send) > 0)
            return 'true';
        return 'false';
    
    }
    //To send the cancel cod email to the user
    function send_cancel_cod_email($Globali, $to_email, $email_message, $event_signup_id, $user_id,$common,$message_details, $event_url,$event_org_email) {
        $to = $to_email;
        $subject = 'Your COD Warning Mail  ';

        //PLACEHOLDER
 
        $EMailMsgId = $message_details[0]['Id'];
        $Message = $message_details[0]['Msg'];
        $event_link="Event Url : <a href='"._HTTP_SITE_ROOT."/event/".$event_url."'>".$event_url."</a>";
        $email_message=str_replace("Event Url", $event_link, $email_message);
        $Message  = str_replace("cod_message", $email_message, $Message);
        $cc=NULL;
        $replyto = NULL;
        $conent = NULL;
        $filename = NULL;
        $bcc='support@meraevents.com';
        if(!empty(trim($event_org_email))){
            $bcc.=",".trim($event_org_email);
        }
//        $bcc = 'sunila@meraevents.com,sreekanthp@meraevents.com,hirishj@meraevents.com';
        $from = "MeraEvents<admin@meraevents.com>";

        include_once 'includes/common_functions.php';
         
        $common->sendEmail($to, $cc, $bcc, $from, $replyto, $subject, $Message, $conent, $filename);

        //SEND EMAIL
        $sEMailMsgId = $EMailMsgId;
        $sSentDt = date('Y-m-d h:i:s');

        $sqlInsEMailSent = "INSERT INTO EMailSent (UserId, EMailMsgId,EventSignupId, SentDt) VALUES (".$user_id.",".$sEMailMsgId.",".$event_signup_id.",'".$sSentDt."')";

        $Globali->ExecuteQuery($sqlInsEMailSent); 
        
    }
    
    //Send cod sms sent to the user
    function send_cancel_cod_sms($Globali,$user_mobile_no,$user_id,$event_signup_id,$type,$email=NULL){
        //SEND SMS                        
        $MobileNo = preg_replace("/[^0-9]/i", "", $user_mobile_no);
        $selSMSMsgs = "SELECT Id, Msg FROM SMSMsgs WHERE MsgType ='".$type."'";
        $dtlSMSMsgs = $Globali->SelectQuery($selSMSMsgs);
        $SMSMsgId = $dtlSMSMsgs[0]['Id'];
        $Message  = $dtlSMSMsgs[0]['Msg'];
        $Message  = str_replace("EVENTSIGNUP_ID", $event_signup_id, $Message);
        if(strcmp($type,'smscodcanceled')==0){
             $Message  = str_replace("MOBILE_NO", $user_mobile_no, $Message);
             $Message  = str_replace("EMAIL_ID", $email, $Message);
        }

        $RtrnMsg = 0;
        include_once('../SMSfunction.php');
        functionSendSMS($MobileNo, $Message, $RtrnMsg); 
                 
 
        $sSMSMsgId = ($SMSMsgId > 0)?$SMSMsgId:0 ;
        $sSentDt = date('Y-m-d h:i:s');
        $sqlInsertSMSSent="INSERT INTO SMSSent (UserId, SMSMsgId, SentDt,EventSignupId) VALUES (".$user_id.",".$sSMSMsgId.",'".$sSentDt."','".$event_signup_id."')";
        $Globali->ExecuteQuery($sqlInsertSMSSent); 
         
                      
    }
    
//wizrocket code
function executeWizrocketCode($eventSignupId,$Events,$Global){ 
    include_once 'includes/wizrocketHeader.php';?>
<script>
    <?php
    $ticketsArray=$Global->SelectQuery("SELECT estd.Discount,estd.BulkDiscount,estd.ReferralDiscount,estd.NumOfTickets,estd.TicketAmt,estd.ServiceTax,t.Name FROM eventsignupticketdetails estd INNER JOIN tickets t ON estd.TicketId=t.Id WHERE EventSignupid='".$eventSignupId."'");
    $EventCity =$Global->GetSingleFieldValue("select City from Cities where Id='".$Events->CityId."'"); 
    $EventCategory =$Global->GetSingleFieldValue("SELECT CatName FROM category WHERE Id='".$Events->CategoryId."'");
    $transDis=$transticksTot=0;
      ?>
wizrocket.event.push("Charged",
    {
        "Payment Mode": "COD", 
        "Event Category":"<?=$EventCategory;?>",
        <?php if(!empty($EventCity)){?> 
        "Event City":"<?=$EventCity;?>",
        <?php } ?>
        "Registration Number":<?=$eventSignupId;?>,
        "Items": [
            <?php foreach ($ticketsArray as $k=>$value){
                    $value['tDiscount']=$value['Discount']+$value['BulkDiscount']+$value['ReferralDiscount'];
                    $value['tPaid']=$value['TicketAmt']-$value['tDiscount']+$value['ServiceTax'];
                    $transDis+=$value['tDiscount'];
                    $transticksTot+=$value['TicketAmt'];
                ?>
            {
            "Event Name": "<?php echo strip_tags(stripslashes($Events->Title)); ?>",
            "Ticket Type": "<?=$value['Name']?>",
            "Discount Amount": <?=$value['tDiscount'];?>,
            "Quantity": <?=$value['NumOfTickets'];?>,
            "Amount": <?=$value['tPaid'];?>
            },
            <?php }  ?>
        ],
        "Discounted Amount":<?=$transDis;?>,
        "Amount": <?=$transticksTot;?>,
        "Currency Type":"INR"
    }
);


</script>
<?php
}
    include 'templates/CheckCODTrans.tpl.php';
?>