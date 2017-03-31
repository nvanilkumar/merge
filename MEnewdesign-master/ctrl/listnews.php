<?php
		include_once("includes/application_top.php");
		include('includes/functions.php');
		include('includes/logincheck.php');
		
		if($_REQUEST['action'] != '')
		{
				if($_REQUEST['action'] == 'delete')
				{
						$nid = $_REQUEST['id'];
						
						$del_news_node = 'DELETE FROM node WHERE nid = "'.$nid.'"';
						mysql_query($del_news_node);
						
						$del_news_nod_rev = 'DELETE FROM node_revisions WHERE nid = "'.$nid.'"';
						mysql_query($del_news_nod_rev);
						
						$del_news_nod_acs = 'DELETE FROM node_access WHERE nid = "'.$nid.'"';
						mysql_query($del_news_nod_acs);
				}
		}
		$select_news = 'SELECT * FROM node AS n,node_revisions AS nr WHERE n.type = "news" AND n.nid = nr.nid';
		$sql_news = mysql_query($select_news);
		
		$current_page_content	=	'listnews.tpl.php';
		include_once(_CURRENT_TEMPLATE_DIR.'main.tpl.php');
?>	   