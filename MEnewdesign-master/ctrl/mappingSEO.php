<?php
@session_start();    
include 'loginchk.php';

$uid =    $_SESSION['uid'];
    
include_once("MT/cGlobali.php");
include_once("includes/common_functions.php");
	
$Global = new cGlobali();
$functions=new functions();

$edidSEOdata=array();
$URL = trim($_REQUEST['url'],'/');

if(isset($_POST['addSEO']))
{
    $editid=$_POST['editid'];
    $mtitle=$_POST['mtitle'];
    $mkeywords=$_POST['mkeywords'];
    $mdescription=$_POST['mdescription'];
    $maptype=$_POST['maptype'];
    $pageType=$_POST['pageType'];
    $murl=$_POST['murl'];
    $params = $_POST['params'];
    $seomapid = $_POST['seomapid'];
        
    $datatime=date('Y-m-d H:i:s',time());
    if(!empty($_POST['editid'])){
        $sURL=$_POST['sURL'];      
        $eventaddsql="update `seodata` set `seotitle` = '".$Global->dbconn->real_escape_string($mtitle)."',`seokeywords`='".$Global->dbconn->real_escape_string($mkeywords)."', `seodescription`='".$Global->dbconn->real_escape_string($mdescription)."' , canonicalurl='".$Global->dbconn->real_escape_string($sURL)."',`mappingtype`= '".$Global->dbconn->real_escape_string($maptype)."', `pagetype` = '".$Global->dbconn->real_escape_string($pageType)."', `mappingurl` = '".$Global->dbconn->real_escape_string($murl)."', `params` = '".$Global->dbconn->real_escape_string($params)."',`modifiedby` =  '".$_SESSION['uid']."' where id = '".$seomapid."'" ;
        $updateRes = $Global->ExecuteQuery($eventaddsql);
        if($updateRes){
           $msg = "<font color=green>Successfully Updated</font>"; 
        }
    }
    elseif(empty($_POST['editid'])){
        $sURL=$_POST['sURL'];
        $pDescription=$_POST['pdescription'];
        $eventaddsql="insert into `seodata` (`seotitle`,`seokeywords`, `seodescription`,`url`,`canonicalurl`,`mappingtype`,`pagetype`, `mappingurl`,`params`,`createdby`,`modifiedby`) values('".$Global->dbconn->real_escape_string($mtitle)."', '".$Global->dbconn->real_escape_string($mkeywords)."', '".$Global->dbconn->real_escape_string($mdescription)."', '".$Global->dbconn->real_escape_string($URL)."', '".$Global->dbconn->real_escape_string($sURL)."' ,'".$Global->dbconn->real_escape_string($maptype)."', '".$Global->dbconn->real_escape_string($pageType)."', '".$Global->dbconn->real_escape_string($murl)."', '".$Global->dbconn->real_escape_string($params)."', '".$_SESSION['uid']."', '".$_SESSION['uid']."')";
        $insertRes = $Global->ExecuteQuery($eventaddsql);
        if($insertRes){
           $msg = "<font color=green>Added successfully</font>"; 
        }
    }

}
 
    $sqlEditSEO="select id,seotitle,seokeywords, seodescription,pageDescription,url,canonicalurl,mappingtype,pagetype,mappingurl,params from seodata where binary url='".$URL."' and deleted = 0";	
    $edidSEOdata=$Global->SelectQuery($sqlEditSEO);   
    //$URL=$_REQUEST['url'];  
    
include 'templates/mappingSEO.tpl.php';
?>