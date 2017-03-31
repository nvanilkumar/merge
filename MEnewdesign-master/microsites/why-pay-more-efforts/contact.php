<?php
include_once("../../ctrl/MT/cGlobali.php");  
$Globali = new cGlobali();
include_once '../../ctrl/includes/common_functions.php';
$commonFunctions=new functions();

$_GET=$commonFunctions->stripData($_GET);
$_POST=$commonFunctions->stripData($_POST);
$_REQUEST=$commonFunctions->stripData($_REQUEST);



$cc=$bcc=$from=$replyto=$content=$filename=NULL;


$EFFORTSFullName=$EFFORTSEmailId=$EFFORTSMobileNo=$EFFORTSPercentage=$comments=$captchatext=NULL;

$captchatextErr=NULL;

if(isset($_POST['effortsform']))
{	
	$EFFORTSFullName=ucfirst($_POST['EFFORTSFullName']);
	$replyto=$from=$EFFORTSEmailId=$_POST['EFFORTSEmailId'];
	$EFFORTSMobileNo=$_POST['EFFORTSMobileNo'];
	$EFFORTSPercentage=$_POST['percentage'];
	$comments=$_POST['message'];
	//$captchatext=$_POST['captchatext'];
	
	
	
	
		$subject="Comment through Why Pay More Efforts - ".$EFFORTSFullName;
		$message='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body style="padding:0px; margin:0px;"><div style="background:#FFFFFF; padding:">
		<table width="100%" cellspacing="0" style="background-color: rgb(238, 237, 231);" class="backgroundTable"><tbody><tr><td valign="top" align="center">
<table width="550" cellspacing="0" cellpadding="0" style="border-bottom: 1px solid rgb(238, 237, 231);">
<tbody><tr><td height="40" align="center" class="headerTop" style="border-top: 0px solid rgb(0, 0, 0); border-bottom: 0px none rgb(255, 255, 255); padding: 0px; background-color: rgb(238, 237, 231); text-align: center;"><div style="font-size: 10px; color: rgb(153, 153, 153); line-height: 300%; font-family: Verdana; text-decoration: none;" class="adminText"></div></td></tr><tr><td style="background-color: rgb(255, 255, 255);" class="headerBar"><table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td width="120"><div style="padding: 10px;" class="divpad"><a href="http://www.meraevents.com" target="_blank" rel="nofollow"><span style="line-height: 0px;">
<img src="http://content.meraevents.com/images/onlineportalemailer.jpg" alt="MeraEvents.com" title="MeraEvents.com"   border="0" style="display: block;" /></span></a></div></td>
                        <td width="430" valign="top" align="right">&nbsp;</td>
		</tr></tbody></table></td></tr></tbody></table><table width="550" cellspacing="0" cellpadding="0" style="background-color: rgb(255, 255, 255);" class="bodyTable"><tbody><tr><td valign="top" align="left" style="padding: 20px 20px 0pt; background-color: rgb(255, 255, 255);" class="defaultText"><table width="95%" cellpadding="0" cellspacing="0" style="font-family:Arial, Helvetica, sans-serif; font-size:14px;">
          <tr>
            <td colspan="2" height="20">Hello Admin,</td>
          </tr>
          <tr>
            <td width="350"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tbody>
                   <tr>
                    <td height="20"></td>
                  </tr>
                  <tr>
                    <td height="20"> <b>Name :</b> '.$EFFORTSFullName.'</td>
                  </tr>
                    <tr>
                    <td height="20"><b> Email ID :</b> '.$EFFORTSEmailId.'</td>
                  </tr>
                   <tr>
                    <td height="20"><b> Mobile Number :</b> '.$EFFORTSMobileNo.'</td>
                  </tr>

                  <tr>
                    <td height="20"><b>Percentage  :</b></td>
				  </tr>
                    <tr>
                    <td height="20">'.$EFFORTSPercentage.'</td>
                  </tr>
				  
				  <tr>
                    <td width="335"><b>Comments  :</b></td><tr>
                    <tr>
                    <td height="25">'.$comments.'</td>
                  </tr>
                  
                           
                  <tr>
                    <td height="20"></td>
                  </tr>
                  
                </tbody>
            </table></td>
            <td valign="top">&nbsp;</td>
          </tr>
 
         
          <tr>
            <td colspan="2" height="40">Thanks for using MeraEvents.com. Have a great time!</td>
          </tr>
		   <tr>
               <td height="20"></td>
           </tr>
        </table></td>
		</tr>
		            </tbody></table></td></tr></tbody></table>
	</div></body>
</html>';
		//$cc='sreekanthp@meraevents.com,kumard@meraevents.com';
		echo "before";
		$bcc='qison@meraevents.com';
		$commonFunctions->sendEmail ('shashi.enjapuri@gmail.com,sreekanthp@meraevents.com' , $cc, $bcc,$from,$replyto,$subject,$message,$content,$filename );
		//$commonFunctions->sendEmail('shashi.enjapuri@gmail.com',$cc,$bcc,$from,$replyto,$subject,$message,$content,$filename,'');
		echo "after";
		echo "success"; 
		exit;
	
	
}
                       
                                              
                        
?>