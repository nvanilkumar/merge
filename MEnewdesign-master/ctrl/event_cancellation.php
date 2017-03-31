<?php
		include_once("includes/application_top.php");
		include('includes/functions.php');
		 include 'loginchk.php';

/**************************commented on 17082009 need to remove afterwords**************************		
		if($_REQUEST['action'] != '')
		{
				if($_REQUEST['action'] == 'approve')
				{
						$nid = $_REQUEST['nodeid'];
						$uid = $_REQUEST['usrid'];
						$event_title = $_REQUEST['title'];
						
						$updt_evnt_cancl = 'UPDATE event_cancellation SET cancellation = "1" WHERE nid = "'.$nid.'"';
						mysql_query($updt_evnt_cancl);
						
						$del_node = 'DELETE FROM node WHERE nid = "'.$nid.'"';
						mysql_query();
						
						$del_event = 'DELETE FROM event WHERE nid  = "'.$nid.'"';
						mysql_query($del_event);
						
						$del_node_rev = 'DELETE FROM node_revisions WHERE nid = "'.$nid.'"';
						mysql_query($del_node_rev);
						
						$del_node_acc = 'DELETE FROM node_access WHERE nid = "'.$nid.'"';
						mysql_query($del_node_acc);
						
						$del_node_content = 'DELETE FROM content_type_add_event WHERE nid = "'.$nid.'"';
						mysql_query($del_node_content);
						
						//-----------SELECT ORG EMAIL----------------------------------------------------------------------------------------------------
						$org_email = 'SELECT mail,name FROM users WHERE uid = "'.$uid.'"';
						$sql_org_email = mysql_fetch_array(mysql_query($org_email));
						
							$to = $sql_org_email['mail'];
							$subject = 'Approval from Event Cancellation';
							$message ='<table width="50%" bgcolor="#CEEFFF" cellpadding="0" cellspacing="0">
							<tr><td>&nbsp;</td></tr>
							<tr>
							<td align="left" style="padding-left:25px;">
							<font style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px; color:#333333; font-weight:bold;">Hi &nbsp; '.$sql_org_email['name'].',</font>
							</td>
							</tr>
							<tr><td>&nbsp;</td></tr>
							<tr>
							<td align="left" style="padding-left:25px;"><font style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px; color:#333333;">Congratulation, .. MeraEvents Admin has approved your following Event Cancellation for  -- <br/><strong>';
										$message .= $event_title;
							$message .='</strong></font></td>
							</tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr>
							<td align="left" style="padding-left:25px;">
							<font style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px; color:#333333;">Regards,<br />Webmaster<br />
							<a href="http://www.meraevents.com">www.meraevents.com</a><br />
							</font>
							</td>
							</tr>
							<tr><td>&nbsp;</td></tr>
							</table>';
							$from = 'info@meraevents.com';
							$headers .= "From: $from"."\r\n".'MIME-Version: 1.0' . "\r\n";
							$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
							mail($to,$subject,$message,$headers);
						//-------------------------------------------------------------------------------------------------------------------------------
						
						//----------SEND EMAIL TO REGISTERED USERS---------------------------------------------------------------------------------------
						$user_email = 'SELECT re.uid,u.name,u.mail FROM registeredevents AS re,users AS u WHERE nid = "'.$nid.'" AND re.uid = u.uid';
						$sql_usr_email = mysql_query($user_email);
						while($row_usr_eml = mysql_fetch_array($sql_usr_email))
						{
								$email = $row_usr_eml['mail'];
								$name = $row_usr_eml['name'];
								
								$to = $email;
								$subject = 'Alert for Event Cancellation';
								$message ='<table width="50%" bgcolor="#CEEFFF" cellpadding="0" cellspacing="0">
								<tr><td>&nbsp;</td></tr>
								<tr>
								<td align="left" style="padding-left:25px;">
								<font style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px; color:#333333; font-weight:bold;">Hi &nbsp; '.$sql_org_email['name'].',</font>
								</td>
								</tr>
								<tr><td>&nbsp;</td></tr>
								<tr>
								<td align="left" style="padding-left:25px;"><font style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px; color:#333333;">As per the cancellation request from '.$sql_org_email['name'].',<br> MeraEvents Admin has approved Event Cancellation for  -- <br/><strong>';
											$message .= $event_title;
								$message .='</strong></font></td>
								</tr>
								<tr><td>There will not exist Event - '.$event_title.' any more from MeraEvents..</td></tr>
								<tr><td>&nbsp;</td></tr>
								<tr>
								<td align="left" style="padding-left:25px;">
								<font style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px; color:#333333;">Regards,<br />Webmaster<br />
								<a href="http://www.meraevents.com">www.meraevents.com</a><br />
								</font>
								</td>
								</tr>
								<tr><td>&nbsp;</td></tr>
								</table>';
								$from = 'info@meraevents.com';
								$headers .= "From: $from"."\r\n".'MIME-Version: 1.0' . "\r\n";
								$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
								mail($to,$subject,$message,$headers);
						}
						//-------------------------------------------------------------------------------------------------------------------------------
				}
				
				if($_REQUEST['action'] == 'decline')
				{
						$nid = $_REQUEST['nodeid'];
						$uid = $_REQUEST['usrid'];
						$event_title = $_REQUEST['title'];
						
						$del_cancel = 'DELETE FROM event_cancellation WHERE nid = "'.$nid.'"';
						mysql_query($del_cancel);
						
						$org_email = 'SELECT mail,name FROM users WHERE uid = "'.$uid.'"';
						$sql_org_email = mysql_fetch_array(mysql_query($org_email));
						
							$to = $sql_org_email['mail'];
							$subject = 'Request Decline from MeraEvent Admin for Event Cancellation';
							$message ='<table width="50%" bgcolor="#CEEFFF" cellpadding="0" cellspacing="0">
							<tr><td>&nbsp;</td></tr>
							<tr>
							<td align="left" style="padding-left:25px;">
							<font style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px; color:#333333; font-weight:bold;">Hi &nbsp; '.$sql_org_email['name'].',</font>
							</td>
							</tr>
							<tr><td>&nbsp;</td></tr>
							<tr>
							<td align="left" style="padding-left:25px;"><font style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px; color:#333333;">Sorry, .. MeraEvents Admin has declined yout request for Event Cancellation for  -- <br/><strong>';
										$message .= $event_title;
							$message .='</strong></font></td>
							</tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr>
							<td align="left" style="padding-left:25px;">
							<font style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px; color:#333333;">Regards,<br />Webmaster<br />
							<a href="http://www.meraevents.com">www.meraevents.com</a><br />
							</font>
							</td>
							</tr>
							<tr><td>&nbsp;</td></tr>
							</table>';
							$from = 'info@meraevents.com';
							$headers .= "From: $from"."\r\n".'MIME-Version: 1.0' . "\r\n";
							$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
							mail($to,$subject,$message,$headers);
				}
		}
		
****************************************************/		
		$arr_eve_cancl = array('uid' => array(),'nid' => array(),'org_name' => array(),'event_title' => array(),'total-signup' => array(),'tst' => array());
/**************************commented on 17082009 need to remove afterwords**************************
		$cancel_event = 'SELECT * FROM event_cancellation WHERE cancellation = "0"';
		$sql_cancel_event = mysql_query($cancel_event);
		
		while($row_cancel = mysql_fetch_array($sql_cancel_event))
		{
				if(!in_array($row_cancel['uid'],$arr_eve_cancl['uid']))
				{
						array_push($arr_eve_cancl['uid'],$row_cancel['uid']);
						
						$sel_uname = 'SELECT name FROM users WHERE uid = "'.$row_cancel['uid'].'"';
						$sql_uname = mysql_fetch_array(mysql_query($sel_uname));
						
						array_push($arr_eve_cancl['org_name'],$sql_uname['name']);
				}
				array_push($arr_eve_cancl['nid'],$row_cancel['nid']);	
				array_push($arr_eve_cancl['event_title'],$row_cancel['event_title']);	
				array_push($arr_eve_cancl['tst'],$row_cancel['uid']);
				
				$regcnt = 'SELECT count(nid) AS cnt FROM registeredevents WHERE nid = "'.$row_cancel['nid'].'"';
				$sql_regcnt = mysql_fetch_array(mysql_query($regcnt));
				
				array_push($arr_eve_cancl['total-signup'],$sql_regcnt['cnt']);				
		}
****************************************************/		
		//print_r($arr_eve_cancl);
		$current_page_content	=	'event_cancellation.tpl.php';
		include_once(_CURRENT_TEMPLATE_DIR.'main.tpl.php');
?>