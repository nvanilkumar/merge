<?php
	include_once("includes/application_top.php");
	include('includes/functions.php');
	include('includes/logincheck.php');

/**************************commented on 17082009 need to remove afterwords**************************	
	if($_REQUEST['action'] != '')
	{
	
			if($_REQUEST['action'] == 'Delete')
			{
				$id = $_REQUEST['act_id'];
				

				 // query to delete corresponding node FROM content_type_add_event
				  $sql_del_content = "DELETE FROM content_type_add_event WHERE nid=".$id;
				 mysql_query($sql_del_content);   
				 
				 // query to delete corresponding node from NODE 
				  $sql_del_node = "DELETE FROM node WHERE nid=".$id;
				 mysql_query($sql_del_node);
				 
				 // query to delete corresponding node from NODE REVISION
				  $sql_del_node_revision = "DELETE FROM node_revisions WHERE nid=".$id;
				 mysql_query($sql_del_node_revision);
				 
				 // query to delete corresponding node from EVENT
				  $sql_del_event = "DELETE FROM event WHERE nid=".$id;
				 mysql_query($sql_del_event);
				 
				 // query to delete node from team member///
				  $sql_team = "DELETE FROM team_member WHERE nid=".$id;
				 mysql_query($sql_team);
			}
			if($_REQUEST['action'] == 'activate')
			{
					$arr_uid = array('uid' => array(), 'name' => array(), 'mail' => array(), 'nid' => array(), 'title' => array(),'tst' => array());
					$chkbx = $_REQUEST['chkbox'];
					for($cn=0;$cn<count($chkbx);$cn++)
					{
							$var = explode('_',htmlentities($chkbx[$cn]));
							
							$nidd = $var[0];
							$apprv = '1';
							$titl = $var[2];
							$uidd = $var[3];
							if(!in_array($uidd,$arr_uid['uid']))
							{
									array_push($arr_uid['uid'],$uidd);
									$sel_mail = 'SELECT name,mail FROM users WHERE uid = "'.$uidd.'"';
									$sql_mail = mysql_fetch_array(mysql_query($sel_mail));
									array_push($arr_uid['mail'],$sql_mail['mail']);
									array_push($arr_uid['name'],$sql_mail['name']);
							}
							array_push($arr_uid['nid'],$nidd);
							array_push($arr_uid['title'],$titl);
							array_push($arr_uid['tst'],$uidd);
																
							$up_stat = 'UPDATE node SET approve = "1",status = "1" WHERE nid = "'.$nidd.'"';
							mysql_query($up_stat);
																
					}
					///USER EMAIL FOR APPROVAL OF EVENT-----------------------------------------
					
					for($act=0;$act<count($arr_uid['uid']);$act++)
					{
							$to = $arr_uid['mail'][$act];
							$name = $arr_uid['name'][$act];
							$subject = 'Approval from EVENT from ADMINISTRATION';
							$message ='<table width="50%" bgcolor="#CEEFFF" cellpadding="0" cellspacing="0">
							<tr><td>&nbsp;</td></tr>
							<tr>
							<td align="left" style="padding-left:25px;">
							<font style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px; color:#333333; font-weight:bold;">Hi &nbsp; '.$name.',</font>
							</td>
							</tr>
							<tr><td>&nbsp;</td></tr>
							<tr>
							<td align="left" style="padding-left:25px;"><font style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px; color:#333333;">Congratulation, .. MeraEvents Admin has approved your following EVENTs -- <br/><strong><ul>';
							for($mm=0;$mm<count($arr_uid['nid']);$mm++)
							{
									if($arr_uid['tst'][$mm] == $arr_uid['uid'][$act])
									{
										$message .= '<li>'.$arr_uid['title'][$mm].'</li>';
									}
							}
							$message .='</ul></strong></font></td>
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
					///-------------------------------------------------------------------------	
					//print_r($arr_uid);						
			}
			if($_REQUEST['action'] == 'deactivate')
			{
					$arr_uid = array('uid' => array(), 'name' => array(), 'mail' => array(), 'nid' => array(), 'title' => array(),'tst' => array());
					$chkbx = $_REQUEST['chkbox'];
					for($cn=0;$cn<count($chkbx);$cn++)
					{
							$var = explode('_',htmlentities($chkbx[$cn]));
							
							$nidd = $var[0];
							$apprv = '1';
							$titl = $var[2];
							$uidd = $var[3];
							if(!in_array($uidd,$arr_uid['uid']))
							{
									array_push($arr_uid['uid'],$uidd);
									$sel_mail = 'SELECT name,mail FROM users WHERE uid = "'.$uidd.'"';
									$sql_mail = mysql_fetch_array(mysql_query($sel_mail));
									array_push($arr_uid['mail'],$sql_mail['mail']);
									array_push($arr_uid['name'],$sql_mail['name']);
							}
							array_push($arr_uid['nid'],$nidd);
							array_push($arr_uid['title'],$titl);
							array_push($arr_uid['tst'],$uidd);
																
							$up_stat = 'UPDATE node SET approve = "0",status = "0" WHERE nid = "'.$nidd.'"';
							mysql_query($up_stat);
																
					}
					///USER EMAIL FOR Denial OF EVENT-----------------------------------------
					
					for($act=0;$act<count($arr_uid['uid']);$act++)
					{
							$to = $arr_uid['mail'][$act];
							$name = $arr_uid['name'][$act];
							$subject = 'Deny Event alert';
							$message ='<table width="50%" bgcolor="#CEEFFF" cellpadding="0" cellspacing="0">
							<tr><td>&nbsp;</td></tr>
							<tr>
							<td align="left" style="padding-left:25px;">
							<font style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px; color:#333333; font-weight:bold;">Hi &nbsp; '.$name.',</font>
							</td>
							</tr>
							<tr><td>&nbsp;</td></tr>
							<tr>
							<td align="left" style="padding-left:25px;"><font style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px; color:#333333;">Sorry, Admin has denied your following EVENTs -- <br/><strong><ul>';
							for($mm=0;$mm<count($arr_uid['nid']);$mm++)
							{
									if($arr_uid['tst'][$mm] == $arr_uid['uid'][$act])
									{
										$message .= '<li>'.$arr_uid['title'][$mm].'</li>';
									}
							}
							$message .='</ul></strong></font></td>
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
					///-------------------------------------------------------------------------	
				//	print_r($arr_uid);							
			}
	}
****************************************************/

/**************************commented on 17082009 need to remove afterwords**************************
	$arr_event = array('uid' => array(), 'nid' => array(), 'approve' => array(), 'title' => array(),'test' => array(),'username' => array());
	$sel_events = 'SELECT u.uid,u.name,u.mail FROM users AS u, users_roles AS ur WHERE ur.rid = "3" AND status = "1" AND u.uid = ur.uid';
	$sql_eve = mysql_query($sel_events);
	while($row_eve = mysql_fetch_array($sql_eve))
	{
		$nm = $row_eve['name'];		
		$uid = $row_eve['uid'];
		
		$all_event = 'SELECT title,nid,approve FROM node WHERE uid ='.$uid.' AND published=1 AND type = "add_event"';
		$sql_event = mysql_query($all_event);
		
		if(mysql_num_rows($sql_event) > 0)
		{
			if(!in_array($uid,$arr_event['uid']) && !in_array($nm,$arr_event['username']))
			{
					array_push($arr_event['username'],$nm);
					array_push($arr_event['uid'],$uid);
			}
			while($ro_eve = mysql_fetch_array($sql_event))
			{
					array_push($arr_event['test'],$uid);
					array_push($arr_event['nid'],$ro_eve['nid']);
					array_push($arr_event['title'],$ro_eve['title']);
					array_push($arr_event['approve'],$ro_eve['approve']);
			}
		}	
****************************************************/		
		/*$sel_temp_usr = 'SELECT * FROM temp_events WHERE uid = "'.$uid.'"';
		$sql_temp_usr = mysql_query($sel_temp_usr);
		if(mysql_num_rows($sql_temp_usr) > 0)
		{
				if(!in_array($uid,$arr_event['uid']) && !in_array($nm,$arr_event['username']))
				{
						array_push($arr_event['username'],$nm);
						array_push($arr_event['uid'],$uid);
				}
				while($row_temp = mysql_fetch_array($sql_temp_usr))
				{
						array_push($arr_event['test'],$uid);
						array_push($arr_event['nid'],$row_temp['nid']);
						array_push($arr_event['title'],$row_temp['title']);
						array_push($arr_event['approve'],'0');
				}
		} */
/**************************commented on 17082009 need to remove afterwords**************************
	}
****************************************************/
	//print_r($arr_event);
	$current_page_content	=	'eventapproval.tpl.php';
	include_once(_CURRENT_TEMPLATE_DIR.'main.tpl.php');
?>