<?php
		include_once("includes/application_top.php");
		include('includes/functions.php');
		include('includes/logincheck.php');
		include('includes/paginator.php');
		
		if($_POST['Search'] == 'Search')
		{
			$t_name = $_POST['t_name'];
			$o_name = $_POST['o_name'];
			$e_name = $_POST['e_name'];
			/**************************commented on 17082009 need to remove afterwords**************************
			$sql_team = "SELECT team_member.*,users.name AS orgname FROM team_member,users WHERE team_member.uid=users.uid AND";
			
			if($_POST['t_name'] != '')
			{
				$sql_team.=" team_member.email LIKE '%".$t_name."%' "; 
				
				if($o_name != '' || $e_name  != '')
				{
					$sql_team.=" AND";
				}
			}
			
			if($_POST['o_name'] != '')
			{
				$sql_team.=" users.name LIkE '%".$o_name."%' ";
				if($_POST['e_name'] != '') 
				{
					$sql_team.=" AND";
				}
			}
			
			if($_POST['e_name'] != '')
			{
				$sql_team.=" team_member.event LIkE '%".$e_name."%'  "; 
			}
			
			$sql_team_res = mysql_query($sql_team) or die("Error in team member :".mysql_error());
			****************************************************/
		}
		else
		{
			/**************************commented on 17082009 need to remove afterwords**************************
			$sql_team = "SELECT team_member.*,users.name AS orgname,users.mail FROM team_member,users WHERE team_member.uid=users.uid";
			$sql_team_res = mysql_query($sql_team) or die("Error in team member :".mysql_error());
			//$sql_team_row = mysql_fetch_array($sql_team_res);
			****************************************************/
			
			$project_numbers = 1;//mysql_num_rows($sql_team_res);
			///////////// Code For Paging//////////////////////////
			$projectpage =& new Paginator($_GET['page'],$project_numbers);
			$projectpage->set_Limit(5); 
			$projectpage->set_Links(3);
			$limit1 = $projectpage->getRange1(); 
			$limit2 = $projectpage->getRange2();
			/**************************commented on 17082009 need to remove afterwords**************************  
			$sql_team = "SELECT team_member.*,users.name AS orgname,users.mail FROM team_member,users WHERE team_member.uid=users.uid LIMIT ".$limit1.",".$limit2;
			$sql_team_res = mysql_query($sql_team) or die("Error in team member :".mysql_error());
			****************************************************/
		////////////////////////////////
		
		}
		
		
		$current_page_content	=	'view_teammembers.tpl.php';
		include_once(_CURRENT_TEMPLATE_DIR.'main.tpl.php');
?>	   