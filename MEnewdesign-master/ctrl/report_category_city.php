<?php
	session_start();
	
	include_once("MT/cGlobali.php");
	include 'loginchk.php';
        include_once("includes/common_functions.php");
        $common=new functions();
	/*error_reporting(-1);
        ini_set('display_errors',1);*/
	$Global = new cGlobali();
	$MsgCountryExist = '';
	
	$dates = $cityId = $SalesId = "";
	$SalesQuery = "SELECT `id` AS SalesId, `name` AS SalesName FROM  salesperson ORDER BY `name`" ; 
		$SalesQueryRES = $Global->SelectQuery($SalesQuery);

	//print_r($_REQUEST);
	if(isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'Show Report')
	{
		if($_REQUEST['txtSDt']!="" ){
		$SDt = $_REQUEST['txtSDt'];
		$SDtExplode = explode("/", $SDt);
		$yesterdaySDate = $SDtExplode[2].'-'.$SDtExplode[1].'-'.$SDtExplode[0].' 00:00:00';
                $yesterdaySDate =$common->convertTime($yesterdaySDate, DEFAULT_TIMEZONE);
		$dates = "  and e.startdatetime > '".$yesterdaySDate."'  ";
		}
		
	}
	else
	{
	   $SDt = date ("d/m/Y", mktime (0,0,0,date("m"),(date("d")-1),date("Y")));	 
		$yesterdaySDate = date ("Y-m-d", mktime (0,0,0,date("m"),(date("d")-1),date("Y"))).' 00:00:01';
                $yesterdaySDate =$common->convertTime($yesterdaySDate, DEFAULT_TIMEZONE);
		$dates = "  and e.startdatetime > '".$yesterdaySDate."'  ";
	}
	    
	
	$TotalAmount = 0;
	$cntTransactionRES = 1;	
	
	if(isset($_REQUEST['selCity']) && $_REQUEST['selCity']!="")
	{
		if($_REQUEST['selCity']=="NewDelhi")
		$cityId=" AND e.cityid in (330,331,383,408,484) or e.StateId=53";
		else 
		if($_REQUEST['selCity']=="Other")
	    $cityId=" AND e.cityid not in (47,14,77,37,39,330,331,383,408) or e.StateId!=53";
		else
		$cityId=" AND e.cityid=".$_REQUEST['selCity'];
          
	}
	
	if(isset($_REQUEST['SalesId']) && $_REQUEST['SalesId']!="")
	{
		$SalesId=" AND ed.salespersonid=".$_REQUEST['SalesId'];
	}
	
	//Display list of Successful Transactions
	
	
    $TransactionQuery = "SELECT count(e.id) as ecount, c.name AS CatName FROM eventdetail AS ed, event as e, category as c WHERE e.id = ed.eventid AND c.id = e.categoryid $dates $cityId $SalesId group by c.name";
    $TransactionRES=$Global->SelectQuery($TransactionQuery);	
    
    //echo "SELECT  count(e.id) FROM event as e WHERE (e.categoryid=0 AND e.status=1) $dates $cityId $SalesId";
	
  $OtherCategoriesCount=$Global->GetSingleFieldValue("SELECT  count(e.id) FROM event as e, eventdetail AS ed  WHERE e.id = ed.eventid AND (e.categoryid=0 AND e.deleted=0 AND e.status=1) $dates $cityId $SalesId");
	
       //mysql_close();
	include 'templates/report_category_city.tpl.php';
