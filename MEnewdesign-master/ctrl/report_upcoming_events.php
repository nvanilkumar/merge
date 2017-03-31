<?php
	
	include_once("MT/cGlobali.php");
	include 'loginchk.php';
        include_once("includes/common_functions.php");
        $common=new functions();
	
	$Global = new cGlobali();
	$MsgCountryExist = '';
	
	
	$SalesQuery = "SELECT id,name from  salesperson ORDER BY name" ; 
	$SalesQueryRES = $Global->SelectQuery($SalesQuery);
		
	$sqlCat = "SELECT id,name from `category`";
	$dtlCat = $Global->SelectQuery($sqlCat);

	
	if($_REQUEST['submit'] == 'Show Report')
	{
		if(strlen($_REQUEST['txtSDt'])>0)
		{
			$SDt = $_REQUEST['txtSDt'];
			$yesterdaySDate = date("Y-m-d",strtotime($_REQUEST['txtSDt'])).' 00:00:00';
                        $yesterdaySDate =$common->convertTime($yesterdaySDate, DEFAULT_TIMEZONE);
		}
		else
		{
			$SDt = $yesterdaySDate = NULL;
		}
		
		
		if(strlen($_REQUEST['txtEDt'])>0)
		{
			$EDt = $_REQUEST['txtEDt'];
			$yesterdayEDate = date("Y-m-d",strtotime($_REQUEST['txtEDt'])).' 23:59:59';
                        $yesterdayEDate =$common->convertTime($yesterdayEDate, DEFAULT_TIMEZONE);
		}
		else
		{
			$EDt = $yesterdayEDate = NULL;
			
		}
		
	}
//	else
//	{
//	   $SDt = ""; //date ("d-M-Y", strtotime("-10 days"));
//	   $yesterdaySDate = date ("Y-m-d", strtotime("-10 days")).' 00:00:01';
//	   
//	   
//	   $EDt = ""; //date ("d-M-Y", strtotime("+1 month"));
//	   $yesterdayEDate = date ("Y-m-d", strtotime("+1 month")).' 23:59:59';
//	}
	

	
	$TotalAmount = 0;
	$cntTransactionRES = 1;	
	
	if($_REQUEST['selCity']!="")
	{
		if($_REQUEST['selCity']=="NewDelhi")
		$cityId=" AND (e.stateid=53 or e.cityid in (408,330,331,383,408)) ";
		else if($_REQUEST['selCity']=="Hyderabad")
	    $cityId=" AND e.cityid  in (47,448) ";
		else if($_REQUEST['selCity']=="Goa")
	    $cityId=" AND e.stateid=53 ";
		else if($_REQUEST['selCity']=="Other")
	    $cityId=" AND (e.stateid!=53 or e.cityid not in (47,14,77,37,39,408,330,331,383,443,448))";
		
		else
		$cityId=" AND e.cityid=".$_REQUEST['selCity'];
          
	}
	
	$catId=NULL;
	if($_REQUEST['selCategory']!="")
	{
		$catId=" AND e.categoryid=".$_REQUEST['selCategory']." ";
	}
	
	
	
	if($_REQUEST['SalesId']!="")
	{
		$SalesId=" AND ed.salespersonid=".$_REQUEST['SalesId'];
	}
	
	//Display list of Successful Transactions
	if(strlen($yesterdaySDate)=="" && strlen($yesterdayEDate)==""){$dates="   ";}
	elseif(strlen($yesterdaySDate)>0 && strlen($yesterdayEDate)==""){$dates="  and e.enddatetime > '".$yesterdaySDate."'  ";}
	elseif(strlen($yesterdaySDate)=="" && strlen($yesterdayEDate)>0)
	{ 
		
	    
		$dates=" and e.enddatetime < '".$yesterdayEDate."'  ";
	}
	else{  $dates="       and e.enddatetime between '".$yesterdaySDate."' AND '".$yesterdayEDate."' ";}
	//echo $dates."<br>";
	
	 $TransactionQuery = "SELECT s.eventid, s.signupdate, sum(s.quantity) 'Qty', sum(s.totalamount) 'totalAmt', e.title  
	FROM eventsignup AS s 
	INNER JOIN event AS e ON s.eventid = e.id
	INNER JOIN eventdetail AS ed ON ed.eventid = e.id
	WHERE 1 and (e.startdatetime > CONVERT_TZ(ADDDATE(now(), INTERVAL -10 DAY),'ASIA/CALCUTTA','GMT'))  and e.enddatetime > 
        CONVERT_TZ(now(),'ASIA/CALCUTTA','GMT')  $dates
	AND ((s.paymentmodeid=1 and s.paymenttransactionid!='A1') or s.paymentmodeid=2 or  (s.discount !='X' and s.totalamount=0) or e.registrationtype=1) and s.paymentstatus!='Canceled' and s.paymentstatus!='Refunded'  $SearchQuery3 $catId $cityId $SalesId group by s.eventid ORDER BY s.eventid DESC"; 
	
	//echo $TransactionQuery."<br>";
	
	$TransactionRES=$Global->SelectQuery($TransactionQuery);
	

	

	

	
       mysql_close();
	include 'templates/report_upcoming_events.tpl.php';
?>
