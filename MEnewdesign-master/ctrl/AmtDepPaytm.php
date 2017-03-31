<?php session_start();
    include 'loginchk.php';
    include_once("MT/cGlobali.php");
    ini_set('memory_limit','700M');
    include_once("includes/common_functions.php");
    $common=new functions();

    $Global = new cGlobali();
    include_once("includes/functions.php");
    if($_REQUEST['txtSDt']!="" )
    {
        $SDt = $_REQUEST['txtSDt'];
        $SDtExplode = explode("/", $SDt);
        $yesterdaySDate = $SDtExplode[2].'-'.$SDtExplode[1].'-'.$SDtExplode[0].' 00:00:00';
        $yesterdaySDate =$common->convertTime($yesterdaySDate, DEFAULT_TIMEZONE);
        
        
        $yesterdayEDate = $SDtExplode[2].'-'.$SDtExplode[1].'-'.$SDtExplode[0].' 23:59:59';
        $yesterdayEDate =$common->convertTime($yesterdayEDate, DEFAULT_TIMEZONE);
        
        $settdates=" and s.settlementdate = '".$yesterdaySDate."'  ";
        $dates=" and s.depositdate = '".$yesterdaySDate."' ";
        $event_dates=" and ((s.depositdate between '".$yesterdaySDate."' and '".$yesterdayEDate."')  "
                . "or (s.settlementdate between '".$yesterdaySDate."' and '".$yesterdayEDate."')) ";
    }

    $EventId=NULL;
    if(!empty($_REQUEST['EventId']) || !empty($_REQUEST['eventIdSrch'])){
        if(!empty($_REQUEST['EventId']))
        {
            $EventId=$_REQUEST['EventId'];
            $EventIdSql=" and s.eventid='".$_REQUEST['EventId']."'";
        }else if(!empty($_REQUEST['eventIdSrch']))
        {
            $EventId=$_REQUEST['eventIdSrch'];
            $EventIdSql=" and s.eventid='".$_REQUEST['eventIdSrch']."'";
        }
    }
	
	
    if($_REQUEST['SerEventName']!="")
    {
         $sqlid = "SELECT id 'Id',userid 'UserId' FROM organizationuser where organizationid=".$_REQUEST['SerEventName'] ;
        $dtsqlid1 = $Global->SelectQuery($sqlid);
        for($i=0;$i<count($dtsqlid1);$i++)
        {
            $orgid1.=$dtsqlid1[$i][UserId].","; 
        }
        $orgid=substr($orgid1,0,-1);
        $SearchQuery =" AND e.ownerid in (".$orgid.") " ;  

    }
    if($_REQUEST['txtSDt']!="" || $_REQUEST["eventIdSrch"]!="" || $_REQUEST['SerEventName']!="")
    {
        //Display list of Successful Transactions
         $TransactionQuery = "SELECT distinct(s.eventid) FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id  where 1 and s.depositdate!='0000-00-00 00:00:00'  AND  s.paymenttransactionid != 'A1' and s.paymentgatewayid='6' ". $event_dates .$EventIdSql . $SearchQuery ." AND e.deleted = 0  order by e.startdatetime DESC"; 
        $TransactionRES=$Global->SelectQuery($TransactionQuery); 
     }
	
       $EventQuery = "SELECT distinct(s.eventid), e.title AS Details FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id  WHERE 1  AND (s.totalamount!=0 and (s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or (s.discount='CashonDelivery' or s.discount='PayatCounter')) AND e.deleted = 0 AND e.status=1 ORDER BY e.title  DESC";
     //$EventQueryRES = $Global->SelectQuery($EventQuery);
    include 'templates/AmtDepPaytm_tpl.php';
?>