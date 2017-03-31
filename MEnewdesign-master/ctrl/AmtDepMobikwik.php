<?php 
session_start();
ini_set('max_execution_time', 2000);
    include 'loginchk.php';
    include_once("MT/cGlobali.php");
    include_once("includes/common_functions.php");
    $common=new functions();
    
    /*echo "<pre>";
    print_r($_REQUEST);
    echo "</pre>";*/
    
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

//            $dates=" and s.depositdate = '".$yesterdaySDate."' ";
            $dates = " and s.depositdate between '".$yesterdaySDate."' and '".$yesterdayEDate."' ";

    }

    $EventId="";
    if(isset ($_REQUEST['EventId']) || isset($_REQUEST['eventIdSrch'])){
        if(isset ($_REQUEST['EventId']) && $_REQUEST['EventId'] != "") {
            //echo "here1";
            $EventId=$_REQUEST['EventId'];
            $EventIdSql=" AND s.eventid='".$_REQUEST['EventId']."' ";
        }
        if( !empty($_REQUEST['eventIdSrch'])) {
            $EventId=$_REQUEST['eventIdSrch'];
            $EventIdSql=" and s.eventid='".$_REQUEST['eventIdSrch']."' ";
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
    $TotalAmount = 0;
    $cntTransactionRES = 1;	
    if($_REQUEST['txtSDt']!="" || $_REQUEST["eventIdSrch"]!="" || $_REQUEST['SerEventName']!="")
    {
        //Display list of Successful Transactions
    $TransactionQuery = "SELECT distinct(s.eventid) as EventId FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id  where 1 and e.deleted=0 and s.depositdate!='0000-00-00 00:00:00'  AND  s.paymenttransactionid != 'A1' and s.paymentgatewayid='5' ". $dates . $EventIdSql ."  order by e.startdatetime DESC"; 
        $TransactionRES=$Global->SelectQuery($TransactionQuery); 
     }
	
     $EventQuery = "SELECT distinct(s.eventid), e.title AS Details FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id  where 1  AND (s.totalamount!=0 and (s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or ( s.discount='CashonDelivery' or s.discount='PayatCounter')) AND e.deleted = 0 AND e.status=1  ORDER BY e.title  DESC";
     //$EventQueryRES = $Global->SelectQuery($EventQuery);
     
     include 'templates/AmtDepMobikwik.tpl.php';
?>