<?php
@session_start();


include_once("../MT/cGlobali.php");
$Globali = new cGlobali();
include_once 'common_functions.php';

$commonFunctions = new functions();
$_GET = $commonFunctions->stripData($_GET);
$_POST = $commonFunctions->stripData($_POST);
$_REQUEST = $commonFunctions->stripData($_REQUEST);
 

//checking for event ID, whether exist or not sset($_REQUEST['eventIDChk'])
if (isset($_REQUEST['eventIDChk']) && $_REQUEST['eventIDChk'] == "0") {
    $eventid = trim($_REQUEST['eventid']);

     $sql = "select `id` from `event` where deleted=0 and `id`='" . $Globali->dbconn->real_escape_string($eventid) . "'";
    //echo $sql;
    $data = $Globali->GetSingleFieldValue($sql);

    if (strlen($data) > 0) {
        if(isset($_REQUEST['devChk']) && $_REQUEST['devChk'] == 1){
            echo "mappingSEO.php?eventid=" . $eventid . "&edit=" . $data2;
        }else{
            echo "addSEO.php?eventid=" . $eventid . "&edit=" . $data2;
        }
    } else {
        echo "error";
    }
}elseif(isset ($_REQUEST['eventIDChk'])&& $_REQUEST['eventIDChk']=="1")
{
    $URL=trim($_REQUEST['url']);
	
	$sql="select `id` from `seodata` where `url`='".$Globali->dbconn->real_escape_string($URL)."'";
	//echo $sql;
        
	$data=$Globali->GetSingleFieldValue($sql);
	//print_r($data);exit;
	if(strlen($data)>0)
	{	
            if(isset($_REQUEST['devChk']) && $_REQUEST['devChk'] == 1){
                echo "mappingSEO.php?url=".$URL."&edit=editing";
            }else{
                echo "addSEO.php?url=".$URL."&edit=editing";	
            }
	}
	else{
            if(isset($_REQUEST['devChk']) && $_REQUEST['devChk'] == 1){
                echo "mappingSEO.php?url=".$URL;
            }else{
                echo "addSEO.php?url=".$URL;
            }
	}
}
// deleting a SEO entry
elseif (isset($_POST['delSEO'])) {
    $delid = $_POST['delid'];
    //$sql="delete from `seoTypes` where `Id`=?";
    $sql = "update `eventdetail` set `seoTitle` = '',`seoKeywords`='', `seoDescription`='', URL='' where eventid = ?";

    $seoTypesStmt = $Globali->dbconn->prepare($sql);
    $seoTypesStmt->bind_param("d", $delid);
    $seoTypesStmt->execute();
    $seoTypesStmt->close();
}
?>