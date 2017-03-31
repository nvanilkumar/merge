<?php
	session_start();
	
	include_once("MT/cGlobal.php");
	include 'loginchk.php';
	
	$Global = new cGlobal();
	$MsgCountryExist = '';
	if($_REQUEST[value]!="")
	{
	 $UpQuery="update EventSignup set eChecked='".$_REQUEST[value]."' where Id=".$_REQUEST['sId'];
	
    $ResUp= $Global->ExecuteQuery($UpQuery);
	}

	if($_REQUEST['txtSDt']!="" && $_REQUEST['txtEDt'] != "")
	{
		$SDt = $_REQUEST['txtSDt'];
		$SDtExplode = explode("/", $SDt);
		$yesterdaySDate = $SDtExplode[2].'-'.$SDtExplode[1].'-'.$SDtExplode[0].' 00:00:00';
		
		$EDt = $_REQUEST['txtEDt'];
		$EDtExplode = explode("/", $EDt);
		$yesterdayEDate = $EDtExplode[2].'-'.$EDtExplode[1].'-'.$EDtExplode[0].' 23:59:59';
		$dates=" and e.StartDt >= '".$yesterdaySDate."' AND e.EndDt <= '".$yesterdayEDate."' ";
		
	}
	
	if(isset($_REQUEST[compeve]) && $_REQUEST[compeve]==1)
	{
	$compeve=" AND e.EndDt < now() ";
	}
    	if(isset($_REQUEST[EventId]) && $_REQUEST[EventId]!=""){
	$EventId=" and s.EventId=".$_REQUEST[EventId];
	}
	if($_REQUEST['SerEventName']!="")
	{
	 $sqlid = "SELECT Id,UserId FROM orgdispnameid where OrgId=".$_REQUEST['SerEventName'] ;
                  $dtsqlid1 = $Global->SelectQuery($sqlid);
                for($i=0;$i<count($dtsqlid1);$i++)
                    {
                    $orgid1.=$dtsqlid1[$i][UserId].","; 
                    }
                  
                   $orgid=substr($orgid1,0,-1);
                   $SearchQuery =" AND e.UserID in (".$orgid.") " ;  

	}
	$TotalAmount = 0;
	$cntTransactionRES = 1;	
	if($_REQUEST[Status]!="")
	{
	if($_REQUEST[Status]=="Pending")
	{
	$TransactionQuery = "SELECT distinct(s.EventId),e.UserID,e.Title,e.StartDt,e.EndDt FROM EventSignup AS s,Paymentinfo AS p,  events AS e where 1 and  s.EventId = e.Id   and s.EventId not in (select EventId from Paymentinfo) AND (s.Fees != 0 AND (s.PaymentTransId != 'A1'))  $dates $compeve $EventId $SearchQuery order by e.StartDt DESC"; 
	}else{
	 $TransactionQuery = "SELECT distinct(s.EventId),e.UserID,e.Title,e.StartDt,e.EndDt FROM EventSignup AS s,Paymentinfo AS p,  events AS e where 1 and s.EventId = e.Id   and s.EventId = p.EventId AND (s.Fees != 0 AND (s.PaymentTransId != 'A1'))  $dates $compeve $EventId $SearchQuery order by e.StartDt DESC"; 
	}
	
	}else{
	
		//Display list of Successful Transactions
   	 $TransactionQuery = "SELECT distinct(s.EventId),e.UserID,e.Title,e.StartDt,e.EndDt FROM EventSignup AS s INNER JOIN events AS e ON s.EventId = e.Id  where 1 AND (s.Fees != 0 AND (s.PaymentTransId != 'A1')) $compeve $dates $EventId $SearchQuery order by e.StartDt DESC"; 
	 }
	 $TransactionRES=$Global->SelectQuery($TransactionQuery); 
		
		
		$EventQuery = "SELECT distinct(s.EventId), e.Title AS Details FROM EventSignup AS s INNER JOIN events AS e ON s.EventId = e.Id where 1 AND (s.Fees != 0 AND (s.PaymentTransId != 'A1'))   ORDER BY e.Title  DESC"; 
	$EventQueryRES = $Global->SelectQuery($EventQuery);
			
	
	
	

	include 'templates/TransbyEvent.tpl.php';
?>