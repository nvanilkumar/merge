<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Display Cancel Transactions
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 26th Aug 2009
 *	2.	Added the new filed IsFamous in db which is used to display the Famous Events on the front end.
 * 		The check box property checked shows the event is famous, visible on front end and vice versa.
******************************************************************************************************************************************/
	
	session_start();
	$uid =	$_SESSION['uid'];
	
 include 'loginchk.php';
	
	
	
	include_once("MT/cGlobali.php");
        include_once("includes/common_functions.php");
	
	$Global = new cGlobali();
	
        //$exclude_events=array();
        $exclude_events=array(70608);
        $exclude_events_title='paytm';
        $common=new functions();
        	if(!empty($_REQUEST['eventIdSrch'])){
           $query="SELECT id FROM event WHERE id=".$_REQUEST['eventIdSrch']." and deleted=1";
           $output=$Global->SelectQuery($query);
           if($output){
               $EventId=$_POST['eventIdSrch'];   
               include 'templates/OnlyCancelTrans.tpl.php';
               exit;
        }}
//	$EventQuery = "SELECT distinct(s.eventid), e.title AS Details FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id AND s.totalamount !=0  $type ORDER BY e.title  DESC"; 
//	$EventQueryRES = $Global->SelectQuery($EventQuery);
        if($_REQUEST[AddComment]=="Add Comment")
	{
	 $sqlcheck="select id from comment where eventsIgnupid='".$_REQUEST[TransId]."' and comment='".$_REQUEST[comment]."' and type = 'incomplete'";
	 $CheckRES = $Global->SelectQuery($sqlcheck);
	 if(count($CheckRES)>0){
}else{	$InsTransComments="insert into comment(eventsIgnupid,comment,createdby,type)values('".$_REQUEST[TransId]."','".$_REQUEST[comment]."','".$_SESSION['uid']."','incomplete')"; 
	$indId = $Global->ExecuteQuery($InsTransComments); 
	}
	}
	
	
	
	/*if($_REQUEST[DeleTrans]=="Delete")
	{
	$DelQuery="delete from EventSignup where Id=".$_REQUEST[transid];
    $ResDel= $Global->ExecuteQuery($DelQuery);
	}*/
	
        $exevents = count($exclude_events);
        $ex_event_ids='';
        if($exevents>0){
		for($ex = 0; $ex < $exevents; $ex++)
                {
                   $ex_event_ids.=$exclude_events[$ex].',';
                }
                $ex_event_ids=rtrim($ex_event_ids, ',');
                $excludeevents=" AND s.eventid NOT IN($ex_event_ids)";
        }
        $excludeeventstitle=" AND e.title NOT LIKE  '%".$exclude_events_title."%'";
        
	if($_REQUEST[Status]!="")
	{
        $eStatus=" AND s.supportstatus='".$_REQUEST[Status]."'"; 
	} 
     
	 $EventId=NULL;
	 $EventTransId=NULL;   
	if($_REQUEST[EventId]!="" || !empty($_REQUEST['eventIdSrch']))
	{
		if(!empty($_REQUEST['EventId']))
            $eventIdSrch = $_REQUEST['EventId'];
        else if(!empty($_REQUEST['eventIdSrch'])){
            $eventIdSrch = $_REQUEST['eventIdSrch'];
        }
		
		$EventId=$eventIdSrch;
        //$EventTransId=" AND EventId=".$_REQUEST[EventId];
        $EventTransId=" AND s.eventid='".$eventIdSrch."'";
	$_REQUEST['settleDt']="";
	$_REQUEST['endDt']="";
	} else if($_REQUEST['settleDt']!="")
	  {
	$SetDt = $_REQUEST['settleDt'];
	$EndDt = $_REQUEST['endDt'];
		$SetDtExplode = explode("/", $SetDt);
		$EndDtExplode = explode("/", $EndDt);
		$SETDATE = $SetDtExplode[2].'-'.$SetDtExplode[1].'-'.$SetDtExplode[0].' 00:00:00';
                $SETDATE =$common->convertTime($SETDATE, DEFAULT_TIMEZONE);
		$EndDATE = $EndDtExplode[2].'-'.$EndDtExplode[1].'-'.$EndDtExplode[0].' 23:59:59';
                $EndDATE =$common->convertTime($EndDATE, DEFAULT_TIMEZONE);
		$signup=" and s.signupdate between '".$SETDATE."' and '".$EndDATE."'";
               //$_REQUEST[EventId]="";
               
		}else{
              if($_REQUEST[EventId]=="" && $_REQUEST['eventIdSrch']=='')
                 {
                 $datet=date('d/m/Y');
                  $SetDtExplode = explode("/", $datet);
		$SETDATE = $SetDtExplode[2].'-'.$SetDtExplode[1].'-'.$SetDtExplode[0].' 00:00:00';
                $SETDATE =$common->convertTime($SETDATE, DEFAULT_TIMEZONE);
		$SETEDATE = $SetDtExplode[2].'-'.$SetDtExplode[1].'-'.$SetDtExplode[0].' 23:59:59';
                $SETEDATE =$common->convertTime($SETEDATE, DEFAULT_TIMEZONE);
		$signup=" and s.signupdate between '".$SETDATE."' and '".$SETEDATE."'";
                  $_REQUEST['settleDt']=$datet;
				  $_REQUEST['endDt']=$datet;
                 }
               
            }
	
        $nowDate= date('Y-m-d h:i:s');
        $nowDate =$common->convertTime($nowDate, DEFAULT_TIMEZONE);
	if($_REQUEST[eventtype]=="Past"){
	$st=" and e.enddatetime < '".$nowDate."'";
	}else{
	$st=" and e.enddatetime > '".$nowDate."'";
	}
      /*
          if($_REQUEST['SerEventName']!="")
	{
	 $sqlid = "SELECT id,UserId FROM orgdispnameid where OrgId=".$_REQUEST['SerEventName'] ;
                  $dtsqlid1 = $Global->SelectQuery($sqlid);
                for($i=0;$i<count($dtsqlid1);$i++)
                    {
                    $orgid1.=$dtsqlid1[$i][UserId].","; 
                    }
                  
                   $orgid=substr($orgid1,0,-1);
                   $SearchQuery =" AND e.UserID in (".$orgid.") " ;  

	}*/
	
	if($_REQUEST[value]!="")
	{
	 $UpQuery="update eventsignup set supportstatus='".$_REQUEST[value]."' where id=".$_REQUEST['sId'];
	
    $ResUp= $Global->ExecuteQuery($UpQuery);
	}
	
	
	if(strlen($EventId)>0)
	{
		
		 $CanTranQuery ="SELECT count( s.id ) AS fCount,sum( s.quantity ) AS tktCount,sum( totalamount ) AS totalAmount, s.id,s.`eventid`,s.`signupdate` ,s.`quantity` ,s.`totalamount` ,u.`name` ,u.`email` ,u.`mobile` ,u.`cityid` ,s.`supportstatus` ,s.`paymentstatus` ,ed.salespersonid 
FROM eventsignup As s
	Inner Join event As e on s.eventid=e.id
	Inner Join eventdetail As ed on ed.eventid=s.eventid
	Inner Join user As u on s.userid=u.id
	
WHERE   ((s.paymentmodeid=1 and s.paymenttransactionid='A1') or s.paymentmodeid not in (2,4,5) ) and s.totalamount!=0 
and s.eventid='".$EventId."' AND e.status = 1 
and s.userid not in (SELECT DISTINCT userid FROM eventsignup WHERE 1 and ((paymentmodeid=1 and paymenttransactionid!='A1') or paymentmodeid in (2,4) ) AND eventid='".$EventId."') 
 $signup $eStatus $st $excludeevents $excludeeventstitle GROUP BY s.userid, s.eventid ORDER BY s.id DESC";
		
	
	}
	else
	{
		 $CanTranQuery ="SELECT count( s.id ) AS fCount,sum( s.quantity ) AS tktCount,sum( totalamount ) AS totalAmount, s.id,s.`eventid`,s.`signupdate` ,s.`quantity` ,s.`totalamount` ,u.`name` ,u.`email` ,u.`mobile` ,u.`cityid` ,s.`supportstatus` ,s.`paymentstatus` ,ed.salespersonid 
FROM eventsignup As s
	Inner Join event As e on s.eventid=e.id
	Inner Join eventdetail As ed on ed.eventid=s.eventid
	Inner Join user As u on s.userid=u.id
	
WHERE   ((s.paymentmodeid=1 and s.paymenttransactionid='A1') or s.paymentmodeid not in (2,4,5)) and s.totalamount!=0 AND e.status = 1 and e.deleted = 0
and s.userid not in (SELECT DISTINCT userid FROM eventsignup WHERE 1 and ((paymentmodeid=1 and paymenttransactionid!='A1') or paymentmodeid=2 ) )
 $signup $eStatus $st $excludeevents $excludeeventstitle GROUP BY s.userid,s.eventid ORDER BY s.id DESC";
		
		
		
	}
	//echo $CanTranQuery;
	 
$CanTranRES = $Global->SelectQuery($CanTranQuery);


//echo $CanTranQuery;

	if($_REQUEST['export']=="ExportIncompleteTrans")
	{
		
              $out = 'Receipt No.,Signup Date,Event Details,Name,Email,Phone,City,Failed Count,Qty,Amount';
		$out .="\n";
		$columns = 4;
		$TotalCanTranRES = count($CanTranRES);
		$cntCanTranRES = 1;
		
		for($i = 0; $i < $TotalCanTranRES; $i++)
		{
		
		
			
		$out .='"'.$CanTranRES[$i]['id'].'",';
			$out .='"'.$CanTranRES[$i]['signupdate'].'",';
			$out .='"'.$Global->GetSingleFieldValue("select title from event where deleted=0 and id='".$CanTranRES[$i]['eventid']."'").'",';
			$out .= '"'.$CanTranRES[$i]['name'].'",';
			$out .= '"'.$CanTranRES[$i]['email'].'",';
			$out .='"'.$CanTranRES[$i]['mobile'].'",';		
			$out .='"'.$Global->GetSingleFieldValue("select name from city where id='".$CanTranRES[$i]['cityid']."'").'",';
			$out .='"'.$CanTranRES[$i]['fCount'].'",';
			$out .='"'.$CanTranRES[$i]['quantity'].'",';
            $out .='"'.$CanTranRES[$i]['totalamount'].'",';
			$out .="\n";
		
		}
	$filename="CancelTransactionlist.csv";
		// Output to browser with appropriate mime type, you choose ;)
		header("Content-type: text/x-csv");
		//header("Content-type: text/csv");
		//header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=CancelTransaction_export.csv");
		echo $out;
		
		exit;			
	}
	$type="";
	if($_REQUEST[type]!="")
	{
	if($_REQUEST[type]=="Present"){
	$type=" AND startdatetime > now()";
	}
	if($_REQUEST[type]=="Past"){
	$type=" AND startdatetime < now()";
	}
	}
	
		
		
	//mysql_close();	
	include 'templates/OnlyCancelTrans.tpl.php';	
?>
