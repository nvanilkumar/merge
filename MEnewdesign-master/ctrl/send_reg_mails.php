<?php
session_start();
	
	 include 'loginchk.php';
	
	include_once("MT/cGlobali.php");
        include_once("includes/common_functions.php");
	$Globali = new cGlobali();
        
        $commonFunctions=new functions();
       
	
	
	
if($_REQUEST['sendRegMails'] == 'Submit')
{
		include("../delegatepass.php");
	$delegatePass=new delegatePass();
		
		 include("../Extras/mpdf/mpdf.php");
        $EventId=$_POST['EventId'];
        $multipleTrans=array();
         $qSelEventDtls = "SELECT Id,Banner,Title,StartDt,EndDt,Venue,ContactDetails,OEmails,URL, Description,UserID,ticketMsg,Logo,EmailTxt,isWebinar,`StateId`,`CityId`,`PinCode`,`web_hook_url`,`display_amount` FROM events WHERE Id = '".$Globali->dbconn->real_escape_string($EventId)."'";
	$aEventDtls = $Globali->SelectQuery($qSelEventDtls);
         $Title = stripslashes($aEventDtls[0]['Title']);
	$URL=_HTTP_SITE_ROOT."/event/".$aEventDtls[0]['URL'];
	$EmailTxt=stripslashes($aEventDtls[0]['EmailTxt']);
    $HighLights = $aEventDtls[0]['Description'];
    $StartDt = $aEventDtls[0]['StartDt'];
    $EndDt = $aEventDtls[0]['EndDt'];
    $Venue = $aEventDtls[0]['Venue'];
    $Contact = $aEventDtls[0]['ContactDetails'];
	$orgId=$aEventDtls[0]['UserID'];
	$ticketMsg=$aEventDtls[0]['ticketMsg'];
		
		
		
            $_SESSION['not_sent']="";
            $transQry="SELECT es.Id,es.Fees,es.Qty,u.Email,u.FirstName,es.EntTax,es.PaymentTransId,u.Id uid FROM EventSignup as es INNER JOIN events as e ON e.Id=es.EventId LEFT JOIN user u ON es.UserID=u.Id WHERE es.Fees!=0 and es.PaymentTransId!='A1' and es.Id<229842 and es.eChecked not in ('Refunded','Canceled') and e.Id='".$EventId."' and es.Id NOT IN (224158,224172,224214,224249,224322,224367,224464,224499,224512,224573)";
            $resTrans=$Globali->SelectQuery($transQry);
            $from='MeraEvents<admin@meraevents.com>';
	    $bcc='srilakshmis@meraevents.com';
            $cc=$bcc=$replyto=NULL;
           
            $selEMailMsgs = "SELECT Id, Msg, MsgType, SendThruEMailId FROM EMailMsgs WHERE MsgType ='emInvoice'";
            $dtlEMailMsgs = $Globali->SelectQuery($selEMailMsgs);
            $EMailMsgId = $dtlEMailMsgs[0]['Id'];
            $filename = 'E-ticket.pdf';
            foreach ($resTrans as $k=>$v){
                 $mpdf=new mPDF();
                $Id=$v['Id'];
                $subject = 'You have successfully registered for '.stripslashes($Title).' - '.$Id;
                $delFirstName = $v['FirstName'];
                $delEmail=$v['Email'];
                $Qty=$v['Qty'];$Fees=$v['Fees'];
                //$EmailTxt=$v['EntTax'];
                $PT_Id=$v['PaymentTransId'];
               
            $message3  = str_replace("FirstName", $delFirstName, $dtlEMailMsgs[0]['Msg']);
            $message3  = str_replace("Title", $Title, $message3);
            if($EmailTxt !=""){
			$message3  = str_replace("EmTxt", $EmailTxt, $message3);
			}else{
			$message3  = str_replace("EmTxt", " ", $message3);
			}
                        $message3  = str_replace("RandomInvoiceNo", $Id, $message3);
                        $message3  = str_replace("Fees", $Fees*$Qty, $message3);
		$message3  = str_replace("delqty",$Qty, $message3);
		$message3  = str_replace("PaymentTransId", $PT_Id, $message3);
		$message3  = str_replace("StartDt", $StartDt, $message3);
		$message3  = str_replace("EndDt", $EndDt, $message3);
		$message3  = str_replace("EventVenue", nl2br($Venue), $message3);
		$message3  = str_replace("ContactDetails", nl2br($Contact), $message3);
		$message3  = str_replace("EventHighLights", nl2br($HighLights), $message3);
		$message3  = str_replace("EventURL", $URL, $message3);
		
		$FBSharelink='http://www.facebook.com/share.php?u='.$URL.'&title=Meraevents -'.stripslashes($Title);
		$message3  = str_replace("FBSharelink", $FBSharelink, $message3);
			
		$TwitterSharelink='http://twitter.com/home?status=Meraevents -'.stripslashes($Title).'...+'.$URL;
		$message3  = str_replace("TwitterSharelink", $TwitterSharelink, $message3);
                $data=$delegatePass->getCustomSunburnPDF($EventId,$Id);
                //print_r($data);
                $data = utf8_encode($data);
                $mpdf->WriteHTML($data);
                $content = $mpdf->Output('', 'S');
                unset($mpdf);
               $filename = 'E-ticket.pdf';
                //print_r($delEmail."-".$cc."-".$bcc."-".$from."-".$replyto."-".$subject."-".$message3."-".$content."-".$filename);
                $status=$commonFunctions->sendEmail($delEmail,$cc,$bcc,$from,$replyto,$subject,$message3,$content,$filename);
                
                        if(!$status){
                             $_SESSION['not_sent'].=$delEmail+",";
                        }else{
                            $sUserId = $v['uid'];
			$sEMailMsgId = $EMailMsgId;
			$sSentDt = date('Y-m-d h:i:s');
			
			$sqlInsEMailSent="INSERT INTO EMailSent (UserId, EMailMsgId,EventSignupId, SentDt) VALUES (?,?,?,?)";
			
			$EMailSentStmt=$Globali->dbconn->prepare($sqlInsEMailSent);
			$EMailSentStmt->bind_param("dids",$sUserId,$sEMailMsgId,$Id,$sSentDt);
			$EMailSentStmt->execute();
			$EMailSentStmt->close(); 
               // print_r($status);
                        }
            }
           
	}
include 'templates/send_reg_mail_tpl.php';
?>