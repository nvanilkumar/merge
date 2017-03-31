<?php
@session_start();    
include 'loginchk.php';

$uid =    $_SESSION['uid'];
    
include_once("MT/cGlobali.php");
include_once("includes/common_functions.php");
	
$Global = new cGlobali();
$functions=new functions();

$eventid=NULL;
$eventdata=array();
$edidSEOdata=array();
$URL=$_REQUEST['url'];
if(isset($_REQUEST['eventid']) && $_REQUEST['eventid']>0)
{
   
  
	$eventid=$_REQUEST['eventid'];
	$eventsql="select startdatetime AS StartDt, enddatetime AS EndDt,title AS `Title`, url AS `URL` from `event` WHERE deleted = 0 AND id='".$eventid."'";

	$eventdata=$Global->SelectQuery($eventsql);
	
	if(count($eventdata)<1)
	{
		header("Location: addSEO.php");
	}
       
}

if(isset($_REQUEST['url']) && isset($_REQUEST['edit']))
{  
    $sqlEditSEO="select id,seotitle,seokeywords, seodescription,pageDescription,url,canonicalurl from seodata where binary url='".$URL."'";	
    $edidSEOdata=$Global->SelectQuery($sqlEditSEO);   
    $URL=$_REQUEST['url'];    
}

if((isset($_REQUEST['edit'])&& isset($_REQUEST['eventid']))|| (isset($_REQUEST['eventid']) && $_REQUEST['eventid']>0))
{
    $eventId=(isset($_REQUEST['edit']) && $_REQUEST['edit']!="")?$_REQUEST['edit']:$_REQUEST['eventid'];
    $sqlEditSEO="select `eventid`, `seotitle`,`seokeywords`, `seodescription`,`conanicalurl` from `eventdetail` where `eventid`='".$eventId."'";
    $edidSEOdata=$Global->SelectQuery($sqlEditSEO);
       // print_r($edidSEOdata);
}
if(isset($_REQUEST['delid']))
{
	
$delid=$_REQUEST['delid'];
	//$sql="delete from `seoTypes` where `Id`=?";
	$delsql="update `eventdetail` set `seotitle` = '',`seokeywords`='', `seodescription`='', conanicalurl='' where eventid =".$delid;
 $delres=$Global->ExecuteQuery($delsql);	
 if($delres>0)
 {
	 $msg = "Deleted successfully";
 }else{
	 $msg = "Unable to Delete";
	 
 }	 
	
}
if(isset($_POST['addSEO']))
{
    $editid=$_POST['editid'];
    $mtitle=$_POST['mtitle'];
    $mkeywords=$_POST['mkeywords'];
    $mdescription=$_POST['mdescription'];
    $id = $_POST['seoid'];
    //print_r($_POST);
    $datatime=date('Y-m-d H:i:s',time());
    if(isset($_POST['addType']) && $_POST['addType']=="eventid")
    {
        $URL=$_POST['sURL'];
        $eventid=$_POST['addTypeValue'];
        $eventaddsql="update `eventdetail` set `seotitle` = '".$Global->dbconn->real_escape_string($mtitle)."',`seokeywords`='".$Global->dbconn->real_escape_string($mkeywords)."', `seodescription`='".$Global->dbconn->real_escape_string($mdescription)."' , conanicalurl='".$Global->dbconn->real_escape_string($URL)."' where eventid = ".$eventid ;
            

    }elseif(isset($_POST['addType']) && $_POST['addType']=="url" && !empty($_POST['editid'])){
        $sURL=$_POST['sURL'];      
        $eventaddsql="update `seodata` set `seotitle` = '".$Global->dbconn->real_escape_string($mtitle)."',`seokeywords`='".$Global->dbconn->real_escape_string($mkeywords)."', `seodescription`='".$Global->dbconn->real_escape_string($mdescription)."' , canonicalurl='".$Global->dbconn->real_escape_string($sURL)."' where id = '".$id."'" ;
    }
    elseif(isset($_POST['addType']) && $_POST['addType']=="url"  && empty($_POST['editid'])){
        $sURL=$_POST['sURL'];
        $pDescription=$_POST['pdescription'];
        $eventaddsql="insert into `seodata` (`seotitle`,`seokeywords`, `seodescription`,`url`,`canonicalurl`) values('".$Global->dbconn->real_escape_string($mtitle)."', '".$Global->dbconn->real_escape_string($mkeywords)."', '".$Global->dbconn->real_escape_string($mdescription)."', '".$Global->dbconn->real_escape_string($URL)."',  '".$Global->dbconn->real_escape_string($sURL)."')";
        //exit;
    }
    //echo $eventaddsql;
    	if($Global->ExecuteQuery($eventaddsql))
	{
		$msg = "Added successfully";
		header("Location: addSEO.php");
	}
}






// pagination script starts here
$page=1;//Default page
$limit=20;//Records per page
$start=0;//starts displaying records from 0
if(isset($_GET['page']) && $_GET['page']!=''){
$page=$_GET['page'];
}
$start=($page-1)*$limit;



//Get total records (you can also use MySQL COUNT function to count records)
$query=$Global->SelectQuery("select s.eventid, s.seotitle,s.seokeywords, s.seodescription, s.conanicalurl as sURL, s.seodescription AS pageDescription, e.url AS URL 
 from eventdetail s,event e where e.deleted = 0 and s.eventid=e.id and s.seotitle!=''
union
select s.eventid AS eventId, s.seotitle AS seoTitle,s.seokeywords AS seoKeywords, s.seodescription AS seoDescription, s.conanicalurl as sURL, s.seodescription AS pageDescription, '' as URL 
 from eventdetail s where s.eventid=0 and s.seotitle!='' ");
$rows=count($query);

//echo pagination($limit,$page,'index.php?page=',$rows); //call function to show pagination

// code for fetching all SEO types

//$sqlseotypes="select s.Id, s.eventId, s.seoTitle,s.seoKeywords, s.seoDescription, s.timestamp, s.URL as sURL, s.pageDescription, e.URL
//    from seoTypes s, events e 
//    where (s.eventId=e.Id) or s.eventId=0 LIMIT $start, $limit";
  $sqlseotypes="select s.eventid AS eventId, s.seotitle AS seoTitle,s.seokeywords AS seoKeywords, s.seodescription AS seoDescription, s.conanicalurl as sURL, s.seodescription AS pageDescription, e.url AS URL
 from eventdetail s,event e where e.deleted = 0 and  s.eventid=e.id and s.seotitle!=''
union
select s.eventid AS eventId, s.seotitle AS seoTitle,s.seokeywords AS seoKeywords, s.seodescription AS seoDescription, s.conanicalurl as sURL, s.seodescription AS pageDescription, '' as URL 
 from eventdetail s where s.eventid=0 and s.seotitle!=''  LIMIT $start, $limit";
//echo $sqlseotypes;
//echo $sqlseotypes;
$seotypesdata=$Global->SelectQuery($sqlseotypes);

//print_r($seotypesdata);


	
	
include 'templates/addSEO.tpl.php';
?>