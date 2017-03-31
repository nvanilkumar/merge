<?php
	include_once("includes/application_top.php");
	include('includes/functions.php');
	include('includes/logincheck.php');
/**************************commented on 17082009 need to remove afterwords**************************	
	if($_REQUEST['action'] != '')
	{
			if($_REQUEST['action'] == 'popular')
			{
					$arr_uid = array('uid' => array(), 'name' => array(), 'mail' => array(), 'nid' => array(), 'title' => array(),'tst' => array());
					$chkbx = $_REQUEST['chkbox'];
					
					$update_popular_event = " TRUNCATE TABLE popular_events";
					mysql_query($update_popular_event);
					
					for($cn=0;$cn<count($chkbx);$cn++)
					{
							$var = explode('_',htmlentities($chkbx[$cn]));
							$nidd = $var[0];
							$titl = $var[2];
							//$uidd = $var[3];
							$sel_pop = "SELECT * FROM popular_events WHERE nid = '".$nidd."' ";
							$qry_pop = mysql_fetch_array(mysql_query($sel_pop));
							$e_nid = $qry_pop['nid'];
							
							$insert_popular_event = "INSERT INTO popular_events (nid,eventname,logo,status) values('".$nidd."','".$titl."','".$nidd.".jpg','1') ";
							mysql_query($insert_popular_event);
					}
					
			}
			
	}

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
		
	}
****************************************************/
	//print_r($arr_event);
	$current_page_content	=	'popular_events.tpl.php';
	include_once(_CURRENT_TEMPLATE_DIR.'main.tpl.php');
?>