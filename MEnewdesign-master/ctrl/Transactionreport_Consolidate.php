<?php
	session_start();
	
	include_once("MT/cGlobali.php");
	include 'loginchk.php';
	include_once("includes/common_functions.php");
	$Global = new cGlobali();
	$common=new functions();
	$MsgCountryExist = '';
	
	$sqlCities = "SELECT DISTINCT c.name,c.id FROM city AS c ORDER BY c.name ASC";
	$dtlCities = $Global->SelectQuery($sqlCities);
	
	$SalesQuery = "SELECT id as SalesId,name as SalesName from  salesperson ORDER BY name" ; 
		$SalesQueryRES = $Global->SelectQuery($SalesQuery);

	
	if($_REQUEST['submit'] == 'Show Report')
	{
		$SDt = $_REQUEST['txtSDt'];
		$SDtExplode = explode("/", $SDt);
		$yesterdaySDate = $SDtExplode[2].'-'.$SDtExplode[1].'-'.$SDtExplode[0].' 00:00:00';
		
		$EDt = $_REQUEST['txtEDt'];
		$EDtExplode = explode("/", $EDt);
		$yesterdayEDate = $EDtExplode[2].'-'.$EDtExplode[1].'-'.$EDtExplode[0].' 23:59:59';
		
		$yesterdaySDate = $common->convertTime($yesterdaySDate, DEFAULT_TIMEZONE);
		$yesterdayEDate = $common->convertTime($yesterdayEDate, DEFAULT_TIMEZONE);
		
		
	}
	else
	{
	   $SDt = date ("d/m/Y", mktime (0,0,0,date("m"),(date("d")-1),date("Y")));
	   $EDt =date ("d/m/Y", mktime (0,0,0,date("m"),(date("d")-1),date("Y")));
		$yesterdaySDate = date ("Y-m-d", mktime (0,0,0,date("m"),(date("d")-1),date("Y"))).' 00:00:01';
	$yesterdayEDate = date ("Y-m-d", mktime (0,0,0,date("m"),(date("d")-1),date("Y"))).' 23:59:59';
	$yesterdaySDate = $common->convertTime($yesterdaySDate, DEFAULT_TIMEZONE);
		$yesterdayEDate = $common->convertTime($yesterdayEDate, DEFAULT_TIMEZONE);
	}
	    if(isset($_REQUEST[NewYear])){
	    $sqlcat = "SELECT id FROM category where name='NewYear'"; 
                   $dtsqlcat = $Global->GetSingleFieldValue($sqlcat);            
                 $SearchQuery3 .=" AND e.categoryid = ".$dtsqlcat ;                

	}
	
	$TotalAmount = 0;
	$cntTransactionRES = 1;	
	
	if($_REQUEST['selCity']!="")
	{
		if($_REQUEST['selCity']=="NewDelhi"){
		$cityId=" AND (e.stateid=53 or e.cityid in (408,330,331,383,484)) ";
		$cityId1=" AND (u.stateid=53 or u.cityid in (408,330,331,383,484))";
		}
		else if($_REQUEST['selCity']=="Hyderabad"){
	    $cityId=" AND e.cityid in (47, 448) ";
		$cityId1=" AND u.cityid in (47, 448) ";
		}
		else if($_REQUEST['selCity']=="Mumbai"){
	    $cityId=" AND e.cityid in (14, 393) ";
		$cityId1=" AND u.cityid in (14, 393) ";
		}
		elseif($_REQUEST['selCity']=="Other"){
	    $cityId=" AND (e.stateid=53 or e.cityid not in (47,14,77,37,39,408,330,331,383,484))";
		$cityId1=" AND (u.stateid!=53 or u.cityid not in (47,14,77,37,39,408,330,331,383,484))";
		}
		else{
		$cityId=" AND e.cityid=".$_REQUEST['selCity'];		
	   $cityId1=" AND u.cityid=".$_REQUEST['selCity'];
		}     
	}
	
	if($_REQUEST['SalesId']!="")
	{
		$SalesId=" AND ed.salespersonid=".$_REQUEST['SalesId'];
	}
	
	//Display list of Successful Transactions
	  $TransactionQuery = "SELECT s.eventid, s.id, s.signupdate,e.stateid, s.quantity as Qty, (s.totalamount/s.quantity) as Fees, s.paymenttransactionid,s.eventextrachargeamount as Ccharge,e.title as Title,s.paymentstatus, e.cityid  FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id INNER JOIN eventdetail as ed on ed.eventid = e.id WHERE e.deleted = 0 AND s.signupdate >= '".$yesterdaySDate."' AND s.signupdate <= '".$yesterdayEDate."' AND (s.totalamount != 0 AND (s.paymenttransactionid != 'A1'))  $SearchQuery3 $cityId $SalesId and s.paymentstatus!='Canceled' ORDER BY s.eventid, s.signupdate DESC"; 
	
	 $TransactionRES=$Global->justSelectQuery($TransactionQuery);
	 
	 $PayatCounterQuery = "SELECT s.eventid, s.id, s.signupdate,e.stateid, s.quantity as Qty, (s.totalamount/s.quantity) as Fees, s.paymenttransactionid,s.eventextrachargeamount as Ccharge,e.title as Title,e.cityid FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id INNER JOIN eventdetail as ed on ed.eventid = e.id WHERE e.deleted = 0 AND s.signupdate >= '".$yesterdaySDate."' AND s.signupdate <= '".$yesterdayEDate."' 
	AND ((s.totalamount != 0 AND s.paymenttransactionid = 'A1') AND (s.discount in ('PayatCounter','Y') ))  $SearchQuery3 $cityId $SalesId and s.paymentstatus!='Canceled' ORDER BY s.eventid, s.signupdate DESC";  
	 $PayatCounterRES=$Global->justSelectQuery($PayatCounterQuery);
	 
	 $CODQuery = "SELECT s.eventid, s.id, s.signupdate, s.quantity as Qty, (s.totalamount/s.quantity) as Fees,s.eventextrachargeamount as Ccharge,e.title as Title,e.stateid, s.paymenttransactionid, e.cityid FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id INNER JOIN eventdetail as ed on ed.eventid = e.id WHERE e.deleted = 0 AND s.signupdate >= '".$yesterdaySDate."' AND s.signupdate <= '".$yesterdayEDate."' AND (s.totalamount != 0 AND (s.paymentgatewayid = 2 ))  $SearchQuery3 $cityId $SalesId and s.paymentstatus!='Canceled' ORDER BY s.eventid, s.signupdate DESC"; 
	 $CODRES=$Global->justSelectQuery($CODQuery);
		
		
       $ChqTranQuery = "SELECT s.eventid, s.id, s.signupdate, s.quantity as Qty, (s.totalamount/s.quantity) as Fees,s.eventextrachargeamount as Ccharge,e.title as Title,s.paymenttransactionid, cq.chequenumber, cq.chequedate, cq.chequebank, cq.status, cq.id AS chequeId FROM chequepayments AS cq, eventsignup AS s, event AS e WHERE e.deleted = 0 AND s.id = cq.eventsignupid AND s.paymentmodeid = 3 AND s.eventid = e.id AND s.signupdate >= '".$yesterdaySDate."' AND s.signupdate <= '".$yesterdayEDate."' AND s.totalamount != 0  $SearchQuery3 $cityId $SalesId and s.paymentstatus!='Canceled' ORDER BY s.eventid, s.signupdate DESC"; 
	$ChqTranRES = $Global->justSelectQuery($ChqTranQuery);	
	
	
	$TotalUsers = "SELECT sum(s.quantity) as totusers  FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id INNER JOIN eventdetail as ed on ed.eventid = e.id WHERE e.deleted = 0 AND s.signupdate >= '".$yesterdaySDate."' AND s.signupdate <= '".$yesterdayEDate."' AND (s.totalamount=0 or (s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2 or s.discount !='X') $cityId $SalesId ORDER BY s.eventid, s.signupdate DESC"; 
	$TotalUsersRES = $Global->SelectQuery($TotalUsers);
	
	$TotalUsersFree = "SELECT sum(s.quantity) as totusersfree  FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id INNER JOIN eventdetail as ed on ed.eventid = e.id WHERE e.deleted = 0 AND s.signupdate >= '".$yesterdaySDate."' AND s.signupdate <= '".$yesterdayEDate."' AND (e.registrationtype=1 or s.totalamount=0) $cityId $SalesId ORDER BY s.eventid, s.signupdate DESC"; 

	$TotalUsersFreeRES = $Global->SelectQuery($TotalUsersFree);	
	
	$TotalUsersPaid = "SELECT sum(s.quantity) as totuserspaid  FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id INNER JOIN eventdetail as ed on ed.eventid = e.id WHERE e.deleted = 0 AND s.signupdate >= '".$yesterdaySDate."' AND s.signupdate <= '".$yesterdayEDate."' AND ((s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2 or s.discount !='X') and s.totalamount!=0  $cityId $SalesId ORDER BY s.eventid, s.signupdate DESC"; 
	$TotalUsersPaidRES = $Global->SelectQuery($TotalUsersPaid);		
	$TotalUser = "SELECT count(u.id) as ucount FROM `user` u WHERE 1 and u.signupdate between '".$yesterdaySDate."' and '".$yesterdayEDate."' $cityId1"; 
	$TotalURES = $Global->SelectQuery($TotalUser);	
	
	$TotalOrg = "SELECT count(u.id) as orgcount FROM `user` u, organizer o WHERE u.id=o.userid and u.signupdate between '".$yesterdaySDate."' and '".$yesterdayEDate."' $cityId1"; 
	$TotalOrgRES = $Global->SelectQuery($TotalOrg);	
	
	$TotalEvents = "SELECT count(id) as eventcount FROM event as e INNER JOIN eventdetail as ed on ed.eventid = e.id where e.deleted=0 and e.registrationdate between '".$yesterdaySDate."' and '".$yesterdayEDate."' $cityId $SalesId"; 
	$TotalEventsRES = $Global->SelectQuery($TotalEvents);	
	
	$TotalEventsfree = "SELECT count(id) as freecount FROM event as e INNER JOIN eventdetail as ed on ed.eventid = e.id where e.deleted=0 and e.registrationdate between '".$yesterdaySDate."' and '".$yesterdayEDate."' and e.registrationtype = 1 $cityId $SalesId"; 
	$TotalEventsfreeRES = $Global->SelectQuery($TotalEventsfree);	
	
	$TotalEventspaid = "SELECT count(id) as paidcount FROM event as e INNER JOIN eventdetail as ed on ed.eventid = e.id where e.deleted=0 and e.registrationdate between '".$yesterdaySDate."' and '".$yesterdayEDate."' and e.registrationtype = 2 $cityId $SalesId"; 
	$TotalEventspaidRES = $Global->SelectQuery($TotalEventspaid);
	
	$TotalEventsnoreg = "SELECT count(id) as noregcount FROM event as e INNER JOIN eventdetail as ed on ed.eventid = e.id where e.deleted=0 and e.registrationdate between '".$yesterdaySDate."' and '".$yesterdayEDate."' and e.registrationtype = 3 $cityId $SalesId"; 
	$TotalEventsnoregRES = $Global->SelectQuery($TotalEventsnoreg);	
	
	$unqEvents=" select count(distinct(e.id)) as unqEventCount FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id INNER JOIN eventdetail as ed on ed.eventid = e.id WHERE  e.deleted=0 and s.signupdate >= '".$yesterdaySDate."' AND s.signupdate <= '".$yesterdayEDate."' AND ((s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2 or s.discount !='X') and s.totalamount!=0   $SearchQuery3 $cityId and s.paymentstatus!='Canceled'";	
	$TotalunqEvents = $Global->SelectQuery($unqEvents);		

	$unqUserID=" select count(distinct(e.ownerid)) as unqUserCount FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id INNER JOIN eventdetail as ed on ed.eventid = e.id WHERE  e.deleted=0 and s.signupdate >= '".$yesterdaySDate."' AND s.signupdate <= '".$yesterdayEDate."' AND ((s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2 or s.discount !='X') and s.totalamount!=0   $SearchQuery3 $cityId and s.paymentstatus!='Canceled'";	
	$TotalunqUserID = $Global->SelectQuery($unqUserID);	


	$unqCityId=" select count(distinct(e.cityid)) as unqCityCount FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE  e.deleted=0 and s.signupdate >= '".$yesterdaySDate."' AND s.signupdate <= '".$yesterdayEDate."' AND ((s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2 or s.discount !='X') and s.totalamount!=0   $SearchQuery3 $cityId and s.paymentstatus!='Canceled'";	
	$TotalunqCityId = $Global->SelectQuery($unqCityId);		
	

	
       mysql_close();
	include 'templates/Transactionreport_Consolidate.tpl.php';
?>