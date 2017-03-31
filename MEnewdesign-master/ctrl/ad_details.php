<?php
include_once("includes/application_top.php");
include_once("includes/functions.php");
include('includes/logincheck.php');
 include 'loginchk.php';

$cid = $_GET['cid'];


// *--------------------- GET CLICK HISTORY FOR THE ADVERTISEMENT ----------------------------------------
/**************************commented on 17082009 need to remove afterwords**************************
$sql_history = "SELECT ad_clicks.*,files.filepath FROM ad_clicks,files,ad_image WHERE ad_image.aid = ad_clicks.aid AND ad_image.fid = files.fid AND ad_clicks.cid=".$cid." ";

$sql_history_res = mysql_query($sql_history);
$sql_row = mysql_fetch_array($sql_history_res);
// *------------------------------------------------------------------------------------------------------
****************************************************/
$current_page_content = 'ad_details.tpl.php';
include_once(_CURRENT_TEMPLATE_DIR.'main.tpl.php');	
?>