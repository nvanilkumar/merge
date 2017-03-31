<?php
	session_start();
	
	include 'loginchk.php';
	
	include_once("MT/cGlobal.php");
        include_once('../SMSfunction.php');

	
	$Global = new cGlobal();
	include 'includes/common_functions.php';
	$commonFunctions=new functions();
	
	
	$MsgCountryExist = '';
	function createPassword($length) 
	{
		$chars = "234567890abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$i = 0;
		$password = "";
		while ($i <= $length)
		{
			$password .= $chars{mt_rand(0,strlen($chars))};
			$i++;
		}
		return $password;
	} 
	if($_REQUEST[edel]=="y" && $_REQUEST[edid]!="")
	{
	        $sqldeled="delete from user where Id=".$_REQUEST[edid];  
			$Global->ExecuteQuery($sqldeled);
			$sqldel="delete from editor where UserId=".$_REQUEST[edid];  
			$Global->ExecuteQuery($sqldel);
	}
	
	if($_POST['Submit'] == "Create")
	{	
		$name=trim($_REQUEST['profile_fname']);
		
		$mail=$_REQUEST['mail'];
			$mail = trim($mail);
		
			$profile_pstate=$_REQUEST['profile_pstate'];
			if($profile_pstate=="---Select---")
			{
			$profile_pstate=0;
			}			
                    $profile_contact=$_REQUEST['profile_contact'];
			$profile_pcity=$_REQUEST['profile_pcity'];
			if($profile_pcity=="---Select---")
			{
			$profile_pcity=0;
			}		
			$profile_newsletter=1;
		
			$errMessage = '';
			
			
			
				$username=$mail;
				$password = createPassword(8);
				//echo "Your 8 character password is: $password<br>";
				
				$checkuser = "SELECT Id FROM user WHERE  Email='".$mail."'";
				$CheckId = $Global->GetSingleFieldValue($checkuser);
				//$count1=$count['count'];

				if($CheckId > 0)
				{
				  $msgUNEmailError = "Email not available.";
				}
				else
				{
				
        
              $auth_token=createPassword(12);
            $user_entry="INSERT INTO user(UserName,Password,Email,CountryId,StateId,CityId,Salutation,FirstName,Mobile,NewsletterSub,DesignationId,Active,RegnDt,auth_code ) VALUES('$username','".md5($password)."', '$mail',14, '$profile_pstate','$profile_pcity', 1, '$name', '$profile_contact', 1,0, 1, '".date('Y-m-d H:i:s')."','".$auth_token."')";
              
                
              $UserIdi =$Global->ExecuteQueryId($user_entry);
                
                // entry in deligate table
               $sqldelins="INSERT INTO editor (UserId,DisplayName,UserTypeId)VALUES ($UserIdi,'$name',6)"; 
               $Global->ExecuteQuery($sqldelins);    

	}
	     if($UserIdi){
					
					//SEND SMS						
				 $MobileNo = preg_replace("/[^0-9]/i", "",$profile_contact);
                
                $selSMSMsgs = "SELECT Id, Msg FROM SMSMsgs WHERE MsgType ='smRegn'";
                $dtlSMSMsgs = $Global->SelectQuery($selSMSMsgs);
                
                $SMSMsgId = $dtlSMSMsgs[0]['Id'];
                
                $Message  = str_replace("uname", $username, $dtlSMSMsgs[0]['Msg']);
                $Message  = str_replace("pwd", $password, $Message);
                
                $RtrnMsg = 0;
                functionSendSMS($MobileNo , $Message, $RtrnMsg);
                      //SMS SENT
				
			$sUserId = $UserIdi;
			$sSMSMsgId = $SMSMsgId;
			$sSentDt = date('Y-m-d h:i:s');
			
			$sqlInsertSMSSent="INSERT INTO SMSSent (UserId, SMSMsgId, SentDt) VALUES ('".$sUserId."','".$sSMSMsgId."','".$sSentDt."')";  
			$Global->ExecuteQuery($sqlInsertSMSSent);
			//SMS SENT
			
			//SEND MAIL
			$to = $mail;
			$subject = 'Account details for '.$name.' as Delegate at meraevents.com';

			$selEMailMsgs = "SELECT Id, Msg, MsgType, SendThruEMailId FROM EMailMsgs WHERE MsgType ='emRegn'";
			$dtlEMailMsgs = $Global->SelectQuery($selEMailMsgs);
			
			$EMailMsgId = $dtlEMailMsgs[0]['Id'];
			$window_url = "<a href="._HTTP_SITE_ROOT."/change-password.php?UserType=Delegate&auth_code=".$auth_token."&uid=".$UserIdi.">Click here to change your default password</a>";

			
			//PLACEHOLDER
			$Message  = str_replace("FirstName", $name, $dtlEMailMsgs[0]['Msg']);
			$Message  = str_replace("LastName", $LastName, $Message);
			$Message  = str_replace("UserNane", $username, $Message);

			$Message  = str_replace("RandomPassword1", $window_url, $Message);
			$Message  = str_replace("EmailID", $mail, $Message);
			
			
			
			 $cc=$content=$filename=$replyto=NULL;
		 
		    $from = $dtlEMailMsgs[0]['SendThruEMailId'];
			$bcc='qison@meraevents.com';
			$bcc=NULL;
			$folder='ctrl';
			$commonFunctions->sendEmail($to,$cc,$bcc,$from,$replyto,$subject,$Message,$content,$filename,$folder);
			
			
			//mail($to, $subject, $Message, $headers);
			
			$sUserId = $UserIdi;
			$sEMailMsgId = $EMailMsgId;
			$sSentDt = date('Y-m-d h:i:s');
			
			$sqlInsEMailSent="INSERT INTO EMailSent (UserId, EMailMsgId, SentDt) VALUES ('".$sUserId."','".$sEMailMsgId."','".$sSentDt."')";
			$Global->ExecuteQuery($sqlInsEMailSent);
					//>>EMAIL SENT 
			}
	}
		$seledt = "SELECT u.Id, e.DisplayName,u.Email, u.Mobile,u.StateId,u.CityId FROM  user as u,editor as e WHERE u.Id=e.UserId";
		$dtlseledt = $Global->SelectQuery($seledt);

	include 'templates/addeditor.tpl.php';
?>