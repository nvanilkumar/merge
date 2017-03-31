<?php
include_once("includes/application_top.php");
include_once("includes/functions.php");
include('includes/logincheck.php');

$aid = $_GET['aid'];


// *--------------------- GET CLICK HISTORY FOR THE ADVERTISEMENT ----------------------------------------

$sql_history = "SELECT ad_clicks.*,files.filepath FROM ad_clicks,files,ad_image WHERE ad_image.aid = ad_clicks.aid AND ad_image.fid = files.fid AND ad_clicks.aid=".$aid." ";

$sql_history_res = mysql_query($sql_history);

// *------------------------------------------------------------------------------------------------------

$current_page_content = 'stat_detail.tpl.php';
include_once(_CURRENT_TEMPLATE_DIR.'main.tpl.php');	
?>