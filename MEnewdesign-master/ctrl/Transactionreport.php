<?php
	session_start();
	
	include_once("MT/cGlobali.php");
	include 'loginchk.php';
	include_once("includes/common_functions.php");
	$Global = new cGlobali();
	$common=new functions();
	$MsgCountryExist = '';
	
	$sqlCities = "SELECT DISTINCT c.name,c.id FROM city AS c where c.status=1 ORDER BY c.name ASC";
	$dtlCities = $Global->SelectQuery($sqlCities);

	
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
        
        $offline_Sql = " AND s.paymenttransactionid != 'Offline' ";
        if($_REQUEST['Offline'] != '') {
            $offline_Sql = "";
        }
	
	$TotalAmount = 0;
	$cntTransactionRES = 1;	
	
	if($_REQUEST['selCity']!="")
	{
	$cityId=" AND e.cityid=".$_REQUEST['selCity'];
       $cityId1=" AND u.cityid=".$_REQUEST['selCity'];
     
	}
	
        
        
        if($_REQUEST['submit'] == 'Show Report')
	{
	//Display list of Successful Transactions
	$TransactionQuery = "SELECT s.eventid, s.id, s.signupdate, s.quantity, s.totalamount, ROUND(totalamount,0) 'Amount', s.paymenttransactionid, e.title,s.paymentstatus  FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE e.deleted = 0 AND s.signupdate >= '".$yesterdaySDate."' AND s.signupdate <= '".$yesterdayEDate."' AND (s.totalamount != 0 AND (s.paymenttransactionid != 'A1'))  $SearchQuery3 $offline_Sql $cityId and s.paymentstatus!='Canceled' ORDER BY s.eventid, s.signupdate DESC"; 
	$TransactionRES=$Global->justSelectQuery($TransactionQuery);
	 //echo $TransactionRES->num_rows;
	$PayatCounterQuery = "SELECT s.eventid, s.id, s.signupdate, s.quantity, s.totalamount,ROUND(totalamount,0) 'Amount',  s.paymenttransactionid, e.title FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE e.deleted = 0 AND s.signupdate >= '".$yesterdaySDate."' AND s.signupdate <= '".$yesterdayEDate."' AND (s.totalamount != 0 AND (s.discount = 'Y' or s.discount ='PayatCounter' ))  $SearchQuery3 $offline_Sql $cityId and s.paymentstatus!='Canceled' ORDER BY s.eventid, s.signupdate DESC"; 
	$PayatCounterRES=$Global->justSelectQuery($PayatCounterQuery);
	 
//	 $CODQuery = "SELECT s.EventId, s.Id, s.SignupDt, s.Qty, s.Fees, s.PaymentTransId, e.Title 
//             FROM EventSignup AS s INNER JOIN events AS e ON s.EventId = e.Id WHERE s.SignupDt >= '".$yesterdaySDate."'
//                 AND s.SignupDt <= '".$yesterdayEDate."'
//                     AND (s.Fees != 0 AND (s.PaymentGateway = 'CashonDelivery' ))  $SearchQuery3 $cityId and s.eChecked!='Canceled' ORDER BY s.EventId, s.SignupDt DESC"; 
	 $CODQuery= "SELECT s.eventid, s.id, s.signupdate, e.title ,s.paymenttransactionid, s.quantity, s.totalamount, ROUND(totalamount,0) 'Amount', cs.status 'status'
            FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id 
                                Inner join CODstatus cs on cs.eventsignupid=s.id
								Inner join paymentgateway pg on pg.id=s.paymentgatewayid
                WHERE e.deleted = 0 AND s.signupdate >= '".$yesterdaySDate."' 
                    AND s.signupdate <= '".$yesterdayEDate."' 
                    AND (s.totalamount != 0 AND (pg.name = 'cod' ))".$SearchQuery3.$offline_Sql.$cityId." 
                    and s.paymentstatus!='Canceled' 
                ORDER BY s.eventid, s.signupdate DESC";
         $CODRES=$Global->justSelectQuery($CODQuery);
		
		
      /* $ChqTranQuery = "SELECT s.eventid, s.id, s.signupdate, s.quantity, s.totalamount, ROUND(totalamount,0) 'Amount',s.paymenttransactionid, e.title, cq.ChqNo, cq.ChqDt, cq.ChqBank, cq.Cleared, cq.Id AS chequeId FROM ChqPmnts AS cq, EventSignup AS s, events AS e WHERE s.Id = cq.EventSignupId AND s.PaymentModeId = 2 AND s.EventId = e.Id AND s.SignupDt >= '".$yesterdaySDate."' AND s.SignupDt <= '".$yesterdayEDate."' AND s.Fees != 0  $SearchQuery3 $offline_Sql $cityId and s.eChecked!='Canceled' ORDER BY s.EventId, s.SignupDt DESC"; 
	$ChqTranRES = $Global->justSelectQuery($ChqTranQuery);	
	*/
	$TotalUsers = "SELECT sum(s.quantity) as totusers  FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE e.deleted = 0 AND s.signupdate >= '".$yesterdaySDate."' AND s.signupdate <= '".$yesterdayEDate."' AND (s.totalamount=0 or (s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2 or s.discount !='X') $cityId ORDER BY s.eventid, s.signupdate DESC"; 
	$TotalUsersRES = $Global->SelectQuery($TotalUsers);
	
	$TotalUsersFree = "SELECT sum(s.quantity) as totusersfree  FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE e.deleted = 0 AND s.signupdate >= '".$yesterdaySDate."' AND s.signupdate <= '".$yesterdayEDate."' AND (e.registrationtype=1 or s.totalamount=0) $cityId ORDER BY s.eventid, s.signupdate DESC"; 

	$TotalUsersFreeRES = $Global->SelectQuery($TotalUsersFree);	
	
	$TotalUsersPaid = "SELECT sum(s.quantity) as totuserspaid  FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE e.deleted = 0 AND s.signupdate >= '".$yesterdaySDate."' AND s.signupdate <= '".$yesterdayEDate."' AND ((s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2 or s.discount !='X') and s.totalamount!=0  $cityId ORDER BY s.eventid, s.signupdate DESC"; 
	$TotalUsersPaidRES = $Global->SelectQuery($TotalUsersPaid);		
	$TotalUser = "SELECT count(u.id) as ucount FROM `user` u WHERE 1 and u.signupdate between '".$yesterdaySDate."' and '".$yesterdayEDate."'"; 
	$TotalURES = $Global->SelectQuery($TotalUser);	
	
	$TotalOrg = "SELECT count(u.id) as orgcount FROM `user` u, organizer o WHERE u.id=o.userid and u.signupdate between '".$yesterdaySDate."' and '".$yesterdayEDate."' $cityId1"; 
	$TotalOrgRES = $Global->SelectQuery($TotalOrg);	
	
	$TotalEvents = "SELECT count(id) as eventcount FROM event as e where e.deleted=0 and (categoryid !=0 or (categoryid =0 and status=1)) and e.registrationdate between '".$yesterdaySDate."' and '".$yesterdayEDate."' $cityId"; 
	$TotalEventsRES = $Global->SelectQuery($TotalEvents);	
	
	$TotalEventsfree = "SELECT count(id) as freecount FROM event as e where  e.deleted=0 and (categoryid !=0 or (categoryid=0 and status=1)) and e.registrationdate between '".$yesterdaySDate."' and '".$yesterdayEDate."' and e.registrationtype = 1 $cityId"; 
	$TotalEventsfreeRES = $Global->SelectQuery($TotalEventsfree);	
	
	$TotalEventspaid = "SELECT count(id) as paidcount FROM event as e where e.deleted=0 and (categoryid !=0 or (categoryid=0 and status=1)) and e.registrationdate between '".$yesterdaySDate."' and '".$yesterdayEDate."' and e.registrationtype=2  $cityId"; 
	$TotalEventspaidRES = $Global->SelectQuery($TotalEventspaid);
	
	$TotalEventsnoreg = "SELECT count(id) as noregcount FROM event as e where  e.deleted=0 and (categoryid !=0 or (categoryid=0 and status=1)) and e.registrationdate between '".$yesterdaySDate."' and '".$yesterdayEDate."' and e.registrationtype=3 $cityId"; 
	$TotalEventsnoregRES = $Global->SelectQuery($TotalEventsnoreg);	
	
	$unqEvents=" select count(distinct(e.id)) as unqEventCount FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE e.deleted = 0 AND s.signupdate >= '".$yesterdaySDate."' AND s.signupdate <= '".$yesterdayEDate."' AND ((s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2 or s.discount !='X') and s.totalamount!=0   $SearchQuery3 $offline_Sql $cityId and s.paymentstatus!='Canceled'";	
	$TotalunqEvents = $Global->SelectQuery($unqEvents);		
	
	$unqUserID=" select count(distinct(e.ownerid)) as unqUserCount FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE e.deleted = 0 AND s.signupdate >= '".$yesterdaySDate."' AND s.signupdate <= '".$yesterdayEDate."' AND ((s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2 or s.discount !='X') and s.totalamount!=0   $SearchQuery3 $offline_Sql $cityId and s.paymentstatus!='Canceled'";	
	$TotalunqUserID = $Global->SelectQuery($unqUserID);	
	
	
	$unqCityId=" select count(distinct(e.cityid)) as unqCityCount FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id WHERE e.deleted = 0 AND s.signupdate >= '".$yesterdaySDate."' AND s.signupdate <= '".$yesterdayEDate."' AND ((s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2 or s.discount !='X') and s.totalamount!=0   $SearchQuery3 $offline_Sql $cityId and s.paymentstatus!='Canceled'";	
	$TotalunqCityId = $Global->SelectQuery($unqCityId);			
	
	
        }
      // mysql_close();
	include 'templates/Transactionreport.tpl.php';
?>