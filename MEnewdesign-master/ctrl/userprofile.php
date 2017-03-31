<?php
	include_once("includes/application_top.php");
	include('includes/functions.php');
	include('includes/logincheck.php');
	
	$uid = $_REQUEST['uid'];
	
	$arr_user = array('username' => array(),'email' => array(),'comp_name' => array(),'comp_addr' => array(),'comp_country' => array(),'comp_city' => array(),'comp_ph' => array(),'comp_fax' => array(),'comp_email' => array(),'comp_type' => array(),'comp_url' => array(),'salutation' => array(),'fname' => array(),'mname' => array(),'lname' => array(),'addr' => array(),'country' => array(),'city' => array(),'pin' => array(),'ph' => array(),'mob' => array(),'designation' => array());
	
	$selprof = 'SELECT ur.rid FROM users AS u, users_roles AS ur WHERE u.uid = "'.$uid.'" AND u.uid = ur.uid';
	$sql_selprof = mysql_fetch_array(mysql_query($selprof));
	
	//--IF ORGANISER---------------------------------------------------------------------------------------------------------------------------
	if($sql_selprof['rid'] == 3)
	{
			$usrprof = 'SELECT u.uid,u.name,u.mail,pv.value,pv.fid FROM users AS u, profile_fields AS pf, profile_values AS pv WHERE u.uid = "'.$uid.'" AND pf.fid IN (4,2,3,42,8,9,10,11,12,13,14,15,16,17,18,43,21,22,23) AND pf.fid = pv.fid AND pv.uid = u.uid';
			$sql_usrprof = mysql_query($usrprof);
			while($row_usrprof = mysql_fetch_array($sql_usrprof))
			{
					if(!in_array($row_usrprof['name'],$arr_user['username']))
					{
							array_push($arr_user['username'],$row_usrprof['name']);
							array_push($arr_user['email'],$row_usrprof['mail']);
					}
					if($row_usrprof['fid'] == 2)
					{
							array_push($arr_user['comp_name'],$row_usrprof['value']);		
					}
					if($row_usrprof['fid'] == 3)
					{
							array_push($arr_user['comp_addr'],$row_usrprof['value']);		
					}
					if($row_usrprof['fid'] == 4)
					{
							array_push($arr_user['comp_country'],$row_usrprof['value']);		
					}
					if($row_usrprof['fid'] == 42)
					{
							array_push($arr_user['comp_city'],$row_usrprof['value']);		
					}
					if($row_usrprof['fid'] == 8)
					{
							array_push($arr_user['comp_ph'],$row_usrprof['value']);		
					}
					if($row_usrprof['fid'] == 9)
					{
							array_push($arr_user['comp_fax'],$row_usrprof['value']);		
					}
					if($row_usrprof['fid'] == 10)
					{
							array_push($arr_user['comp_email'],$row_usrprof['value']);		
					}
					if($row_usrprof['fid'] == 11)
					{
							array_push($arr_user['comp_type'],$row_usrprof['value']);		
					}
					if($row_usrprof['fid'] == 12)
					{
							array_push($arr_user['comp_url'],$row_usrprof['value']);		
					}
					if($row_usrprof['fid'] == 13)
					{
							array_push($arr_user['salutation'],$row_usrprof['value']);		
					}
					if($row_usrprof['fid'] == 14)
					{
							array_push($arr_user['fname'],$row_usrprof['value']);		
					}
					if($row_usrprof['fid'] == 15)
					{
							array_push($arr_user['mname'],$row_usrprof['value']);		
					}
					if($row_usrprof['fid'] == 16)
					{
							array_push($arr_user['lname'],$row_usrprof['value']);		
					}
					if($row_usrprof['fid'] == 17)
					{
							array_push($arr_user['addr'],$row_usrprof['value']);		
					}
					if($row_usrprof['fid'] == 18)
					{
							array_push($arr_user['country'],$row_usrprof['value']);		
					}
					if($row_usrprof['fid'] == 43)
					{
							array_push($arr_user['city'],$row_usrprof['value']);		
					}
					if($row_usrprof['fid'] == 21)
					{
							array_push($arr_user['pin'],$row_usrprof['value']);		
					}
					if($row_usrprof['fid'] == 22)
					{
							array_push($arr_user['ph'],$row_usrprof['value']);		
					}
					if($row_usrprof['fid'] == 23)
					{
							array_push($arr_user['mob'],$row_usrprof['value']);		
					}
			}			
	}
	
	//--IF DELEGATE---------------------------------------------------------------------------------------------------------------------------
	if($sql_selprof['rid'] == 4)
	{
			$usrprof = 'SELECT u.uid,u.name,u.mail,pv.value,pv.fid FROM users AS u, profile_fields AS pf, profile_values AS pv WHERE u.uid = "'.$uid.'" AND pf.fid IN (25,26,27,28,29,30,31,32,33,34) AND pf.fid = pv.fid AND pv.uid = u.uid';
			$sql_usrprof = mysql_query($usrprof);
			while($row_usrprof = mysql_fetch_array($sql_usrprof))
			{
					if(!in_array($row_usrprof['name'],$arr_user['username']))
					{
							array_push($arr_user['username'],$row_usrprof['name']);
							array_push($arr_user['email'],$row_usrprof['mail']);
					}
					if($row_usrprof['fid'] == 25)
					{
							array_push($arr_user['fname'],$row_usrprof['value']);		
					}
					if($row_usrprof['fid'] == 26)
					{
							array_push($arr_user['salutation'],$row_usrprof['value']);		
					}
					if($row_usrprof['fid'] == 27)
					{
							array_push($arr_user['lname'],$row_usrprof['value']);		
					}
					if($row_usrprof['fid'] == 29)
					{
							array_push($arr_user['comp_name'],$row_usrprof['value']);		
					}
					if($row_usrprof['fid'] == 30)
					{
							array_push($arr_user['designation'],$row_usrprof['value']);		
					}
					if($row_usrprof['fid'] == 31)
					{
							array_push($arr_user['mob'],$row_usrprof['value']);		
					}
					if($row_usrprof['fid'] == 32)
					{
							array_push($arr_user['addr'],$row_usrprof['value']);		
					}
					if($row_usrprof['fid'] == 33)
					{
							array_push($arr_user['pin'],$row_usrprof['value']);		
					}
					if($row_usrprof['fid'] == 34)
					{
							array_push($arr_user['country'],$row_usrprof['value']);		
					}
			}			
	}
	//-----------------------------------------------------------------------------------------------------------------------------------------
	
	$current_page_content	=	'userprofile.tpl.php';
	include_once(_CURRENT_TEMPLATE_DIR.'main.tpl.php');
?>