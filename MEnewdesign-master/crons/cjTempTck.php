<?php
include("commondbdetails.php");

/* * ********************************************************************************************** 
 * 	Page Details : Cron Job Transactions happen yesterday and Send Mail
 * 	Created / Last Updation Details : 
 * 	1.	Created on 12th Nov 2010 : Created by SUNIL
 * ********************************************************************************************** */
include('../ctrl/MT/cGlobali.php');
include_once '../ctrl/includes/common_functions.php';
$Global = new cGlobali();
$commonFunctions = new functions();

$_GET = $commonFunctions->stripData($_GET, 1);
$_POST = $commonFunctions->stripData($_POST, 1);
$_REQUEST = $commonFunctions->stripData($_REQUEST, 1);

if ($_GET['runNow'] == 1) {

    $modifiedUser=",modifiedby=".$commonFunctions->getCronUserDetails();


    /* $db_conn = mysql_connect($DBServerName,$DBUserName,$DBPassword);
      mysql_select_db($DBIniCatalog); */

    $b = time() - 1200;
    $datetime = date("Y-m-d H:i:s", $b);

    $datetime1 = date("Y-m-d H:i:s");

    //$sqlTck="delete from sessionlock where endtime <= '". $datetime1."' ";
    $sqlTck = "update sessionlock set deleted=1 $modifiedUser where endtime <= '" . $datetime1 . "' ";

    //mysql_query($sqlTck);
    $Global->ExecuteQuery($sqlTck);

    $sqlseats = "update venueseat set Status='Available',EventSIgnupId=0,BDate='0000-00-00 00:00:00'".$modifiedUser." where Status='InProcess' and BDate < '" . $datetime . "'";
    // mysql_query($sqlseats);
    $Global->ExecuteQuery($sqlseats);

//mysql_close($db_conn);
}
?>
