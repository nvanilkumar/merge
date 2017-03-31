<?php
	session_start();
	include 'loginchk.php';
	include_once("MT/cGlobali.php");
	
	
	$Global = new cGlobali();
	$MsgCountryExist = '';
	$EventId=$_REQUEST[EventId];
	if($EventId!=""){
	//not in use --msJ
	/*$TransactionBilldeskQuery = "SELECT distinct(s.EventId),e.UserID,e.Title,e.StartDt,e.EndDt FROM EventSignup AS s INNER JOIN events AS e ON s.EventId = e.Id  where 1 AND (s.Fees != 0 AND (s.PaymentModeId=1 AND s.PaymentTransId != 'A1' AND s.PaymentTransId !='PayPalPayment')) AND s.PaymentGateway='Billdesk' and EventId= $EventId "; 
	$EventQueryBilldeskRES = $Global->SelectQuery($TransactionBilldeskQuery);
	
	$TransactionEBSQuery = "SELECT distinct(s.EventId),e.UserID,e.Title,e.StartDt,e.EndDt FROM EventSignup AS s INNER JOIN events AS e ON s.EventId = e.Id  where 1 AND (s.Fees != 0 AND (s.PaymentModeId=1 AND s.PaymentTransId != 'A1' AND s.PaymentTransId !='PayPalPayment')) AND s.PaymentGateway='EBS' and s.eChecked!='Canceled' and EventId= $EventId "; 
	$EventQueryEBSRES = $Global->SelectQuery($TransactionEBSQuery);
	
        $TransactionPaypalQuery = "SELECT distinct(s.EventId),e.UserID,e.Title,e.StartDt,e.EndDt FROM EventSignup AS s INNER JOIN events AS e ON s.EventId = e.Id  where 1 AND (s.Fees != 0 AND (s.PaymentModeId=1 AND (s.PaymentTransId ='PayPalPayment' or s.PaymentGateway='PayPal')))  and EventId= $EventId "; 
	$EventQueryPaypalRES = $Global->SelectQuery($TransactionPaypalQuery);		
	
	$TransactionCODQuery = "SELECT distinct(s.EventId),e.UserID,e.Title,e.StartDt,e.EndDt FROM CODstatus as co, EventSignup AS s , events AS e where  s.EventId = e.Id and s.Id=co.EventSIgnupId  AND (s.Fees != 0 AND (s.PaymentModeId=2 AND s.PaymentGateway = 'CashonDelivery' )) and co.Status='Delivered' and co.tStatus='Delivered' and EventId= $EventId "; 
	$EventQueryCODRES = $Global->SelectQuery($TransactionCODQuery);
	
	
	$TransactioncounterQuery = "SELECT distinct(s.EventId),e.UserID,e.Title,e.StartDt,e.EndDt FROM EventSignup AS s INNER JOIN events AS e ON s.EventId = e.Id  where 1 AND (s.Fees != 0 AND (s.PaymentModeId=2 AND (s.PromotionCode = 'PayatCounter' or s.PromotionCode = 'Y') )) and s.delStatus='Delivered'  and EventId= $EventId "; 
	$EventQuerycounterRES = $Global->SelectQuery($TransactioncounterQuery);
	
	$TransactionchequeQuery = "SELECT distinct(s.EventId),e.UserID,e.Title,e.StartDt,e.EndDt FROM ChqPmnts AS cq, EventSignup AS s, events AS e where  s.EventId = e.Id and s.Id=cq.EventSignupId and s.PaymentModeId =2 and cq.Cleared=4     and EventId= $EventId "; 
	$EventQuerychequeRES = $Global->SelectQuery($TransactionchequeQuery);*/ 
	
	
	
	
	
	}
	
$EventQuery = "SELECT distinct(s.eventid), e.title AS Details FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id where 1  AND (s.totalamount!=0 and (s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or (s.paymentmodeid=2 or s.paymentgatewayid= 2 or s.discount='PayatCounter'))   ORDER BY e.title  DESC"; 
	$EventQueryRES = $Global->SelectQuery($EventQuery);


	include 'templates/detailreport.tpl.php';
?>