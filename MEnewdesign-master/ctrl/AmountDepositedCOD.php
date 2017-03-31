<?php
	session_start();
	include 'loginchk.php';
	ini_set('max_execution_time', 2000);
	include_once("MT/cGlobali.php");
	
	
	$Global = new cGlobali();
	$MsgCountryExist = '';
	
	if($_REQUEST['txtSDt']!="" )
	{
		$SDt = $_REQUEST['txtSDt'];
		$SDtExplode = explode("/", $SDt);
		$yesterdaySDate = $SDtExplode[2].'-'.$SDtExplode[1].'-'.$SDtExplode[0].' 00:00:00';
		
		$dates=" and s.depositdate = '".$yesterdaySDate."' ";
		
	}
	
	
   $EventId=NULL;
	if(!empty($_REQUEST['EventId']) || !empty($_REQUEST['eventIdSrch'])){
            if(!empty($_REQUEST['EventId']))
			{
				$EventId=$_REQUEST['EventId'];
                $EventIdSql=" and s.eventid='".$_REQUEST['EventId']."'";
			}
            else if(!empty($_REQUEST['eventIdSrch']))
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
	$TotalAmount = 0;
	$cntTransactionRES = 1;	
	if($_REQUEST['txtSDt']!="" || $_REQUEST[EventId]!="" || $_REQUEST['SerEventName']!=""  || $_REQUEST['eventIdSrch']!="")
	{
	
		//Display list of Successful Transactions
   	$TransactionQuery = "SELECT distinct(s.eventid) FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id  where 1 and s.depositdate!='0000-00-00 00:00:00'  AND s.paymentgatewayid=2  $dates $EventIdSql $SearchQuery  AND e.deleted = 0 AND e.status=1 ORDER BY e.startdatetime DESC"; 
        $TransactionRES=$Global->SelectQuery($TransactionQuery); 
	 }
	
		
		
       $EventQuery = "SELECT distinct(s.eventid), e.title AS Details FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id where 1  AND e.deleted=0 and (s.totalamount!=0 and (s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or ( s.paymentgatewayid=2 or s.discount='PayatCounter'))   ORDER BY e.title  DESC"; 
	//$EventQueryRES = $Global->SelectQuery($EventQuery);
			
	
	
	

	include 'templates/AmountDepositedCOD.tpl.php';
?>