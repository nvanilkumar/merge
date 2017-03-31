<?php
	session_start();
	
	include_once("MT/cGlobali.php");
	include 'loginchk.php';
	
	$Global = new cGlobali();
	$MsgCountryExist = '';
        include_once("includes/common_functions.php");
        $common=new functions();
	
	
	$SalesQuery = "SELECT `id` AS SalesId,`name` AS SalesName from  salesperson   ORDER BY `name`" ; 
		$SalesQueryRES = $Global->SelectQuery($SalesQuery);

	
	if($_REQUEST['submit'] == 'Show Report')
	{
		if($_REQUEST['txtSDt']!=""  && $_REQUEST['txtEDt']!="")
		{
			$SDt = $_REQUEST['txtSDt'];
			$SDtExplode = explode("/", $SDt);
			$yesterdaySDate = $SDtExplode[2].'-'.$SDtExplode[1].'-'.$SDtExplode[0].' 00:00:00';
                        $yesterdaySDate =$common->convertTime($yesterdaySDate, DEFAULT_TIMEZONE);
			
			$EDt = $_REQUEST['txtEDt'];
			$EDtExplode = explode("/", $EDt);
			$yesterdayEDate = $EDtExplode[2].'-'.$EDtExplode[1].'-'.$EDtExplode[0].' 23:59:59';
                        $yesterdayEDate =$common->convertTime($yesterdayEDate, DEFAULT_TIMEZONE);
			
			$dates = "  and e.registrationdate between  '".$yesterdaySDate."' and '".$yesterdayEDate."' ";
		}
		elseif($_REQUEST['txtSDt']!="" && $_REQUEST['txtEDt']==""){
			$SDt = $_REQUEST['txtSDt'];
			$SDtExplode = explode("/", $SDt);
			$yesterdaySDate = $SDtExplode[2].'-'.$SDtExplode[1].'-'.$SDtExplode[0].' 00:00:00';
                         $yesterdaySDate =$common->convertTime($yesterdaySDate, DEFAULT_TIMEZONE);
			$yesterdayEDate = date ("Y-m-d", mktime (0,0,0,date("m"),(date("d")),date("Y"))).' 23:59:59';
                        $yesterdayEDate =$common->convertTime($yesterdayEDate, DEFAULT_TIMEZONE);
			$dates = "  and e.registrationdate between  '".$yesterdaySDate."' and '".$yesterdayEDate."' ";
		}
		elseif($_REQUEST['txtSDt']=="" && $_REQUEST['txtEDt']!="" ){
			$yesterdaySDate = date ("Y-m-d", mktime (0,0,0,date("m"),(date("d")-1),date("Y"))).' 00:00:00';
			$EDt = $_REQUEST['txtEDt'];
			$EDtExplode = explode("/", $EDt);
			$yesterdayEDate = $EDtExplode[2].'-'.$EDtExplode[1].'-'.$EDtExplode[0].' 23:59:59';	
                        $yesterdayEDate =$common->convertTime($yesterdayEDate, DEFAULT_TIMEZONE);
			$dates = "  and e.registrationdate between  '".$yesterdaySDate."' and '".$yesterdayEDate."' ";
		}
		
		
	}
	else
	{
	   $SDt = date ("d/m/Y", mktime (0,0,0,date("m"),(date("d")-1),date("Y")));	
	   $EDt = date ("d/m/Y", mktime (0,0,0,date("m"),(date("d")),date("Y"))); 
		$yesterdaySDate = date ("Y-m-d", mktime (0,0,0,date("m"),(date("d")-1),date("Y"))).' 00:00:00';
		$yesterdayEDate = date ("Y-m-d", mktime (0,0,0,date("m"),(date("d")),date("Y"))).' 23:59:59';
                $yesterdaySDate =$common->convertTime($yesterdaySDate, DEFAULT_TIMEZONE);
                $yesterdayEDate =$common->convertTime($yesterdayEDate, DEFAULT_TIMEZONE);
		$dates = "  and e.registrationdate between  '".$yesterdaySDate."' and '".$yesterdayEDate."' ";
	}
	    
	
	$TotalAmount = 0;
	$cntTransactionRES = 1;	
	
	if($_REQUEST['selCity']!="")
	{
		if($_REQUEST['selCity']=="NewDelhi")
		$cityId=" AND e.cityid in (38,55,71,146,147,148,149,150,151,152,256,321,326,327,408,330,331,383,484)";
		else 
		if($_REQUEST['selCity']=="Other")
	    $cityId=" AND e.cityid not in (47,14,77,37,39,38,55,71,146,147,148,149,150,151,152,256,321,326,327,408,330,331,383,484)";
		else
		$cityId=" AND e.cityid=".$_REQUEST['selCity'];
          
	}
	
	if($_REQUEST['SalesId']!="")
	{
		$SalesId=" AND ed.salespersonid=".$_REQUEST['SalesId'];
	}
	
	//Display list of Successful Transactions
	
	
 $TransactionQuery = "SELECT count(e.id) as ecount,c.name AS CatName FROM eventdetail AS ed, event as e, category as c WHERE e.id = ed.eventid AND c.id = e.categoryid $dates $cityId $SalesId group by c.name";
	 $TransactionRESDB=$Global->SelectQuery($TransactionQuery);
	 
	 $TransactionRES=array();
	 foreach($TransactionRESDB as $k1=>$v1)
	 {
		 $TransactionRES[$v1['CatName']]=$v1['ecount'];
	 }
	 
	 
	 $CategoriesQuery = "SELECT id AS Id, `name` AS CatName FROM category ORDER BY CatName ASC";
     $CategoriesRESDB = $Global->SelectQuery($CategoriesQuery);
	 
	 $CategoriesRES=array();
	 foreach($CategoriesRESDB as $k=>$v)
	 {
		 $CategoriesRES[$v['Id']]=$v['CatName'];
	 }
 $OtherCategoriesCount=$Global->GetSingleFieldValue("SELECT  count(e.Id) FROM event as e, eventdetail AS ed WHERE e.id = ed.eventid AND(e.categoryid=0 and e.deleted=0 and  e.status=1) $dates $cityId $SalesId						
");


	
       mysql_close();
	include 'templates/report_cat_cre_city.tpl.php';
?>