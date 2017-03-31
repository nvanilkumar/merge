<?php
	session_start();
	include 'loginchk.php';
	
	include_once("MT/cGlobali.php");
        include_once("includes/common_functions.php");
        $common=new functions();
	
	
	$Global = new cGlobali();

      

function ticketsold($id)
{
 $TickQuery = "SELECT totalsoldtickets AS ticketLevel FROM ticket WHERE eventid=".$id;
 $ResTickQuery = $Global->SelectQuery($TickQuery);
 $TotalResTickQuery = count($ResTickQuery);
        $tickcount=0;
		for($j=0; $j < $TotalResTickQuery; $j++)
		{
		 $tickcount=$tickcount+$ResTickQuery[$j][ticketLevel];
		}
		echo $tickcount;
}function ticketamount($id)
{
$TickPQuery = "SELECT price AS Price,totalsoldtickets AS ticketLevel FROM ticket WHERE eventid=".$id;
$ResTickPQuery = $Global->SelectQuery($TickPQuery);
$TotalResTickPQuery = count($ResTickPQuery);
        $tickamt=0;
		for($j=0; $j < $TotalResTickPQuery; $j++)
		{
		 $tickamt=$tickamt+($ResTickPQuery[$j][Price]*$ResTickPQuery[$j][ticketLevel]);
		}
		echo $tickamt;
}

	$MsgCountryExist = '';
	$msg="";
	$std="";
	if($_REQUEST['submit'] == 'Show report')
	{
		$SalesId = $_REQUEST['SalesId'];
		if($_REQUEST['txtSDt']!="" && $_REQUEST['txtEDt']!=""){
		$SDt = $_REQUEST['txtSDt'];
		$SDtExplode = explode("/", $SDt);
		$SDtYMD = $SDtExplode[2].'-'.$SDtExplode[1].'-'.$SDtExplode[0].' 00:00:00';
		$SDtYMD =$common->convertTime($SDtYMD, DEFAULT_TIMEZONE);
		$EDt = $_REQUEST['txtEDt'];
		$EDtExplode = explode("/", $EDt);
		$EDtYMD = $EDtExplode[2].'-'.$EDtExplode[1].'-'.$EDtExplode[0].' 23:59:59';
                $EDtYMD =$common->convertTime($EDtYMD, DEFAULT_TIMEZONE);
		$stdt=" AND s.signupdate between '".$SDtYMD."' and '".$EDtYMD."'";
		 $incpay="";
		}
			 if($_REQUEST['card']==1 && $_REQUEST['chq']==1 && $_REQUEST['free']==1)
		 {
		   $incpay.=" AND ((s.paymentmodeid=1 and s.paymenttransactionid!='A1') or s.paymentmodeid=2 or e.registrationtype=1 or s.discount !='X')"; 
		 }      
                           
		 if($_REQUEST['card']==1 && $_REQUEST['chq']==1 && $_REQUEST['free']!=1)
		  {
		  $incpay.=" AND ((s.paymentmodeid=1 and s.paymenttransactionid!='A1') or s.paymentmodeid=2 or s.discount !='X')"; 
		  }
		  if($_REQUEST['card']==1 && $_REQUEST['chq']!=1 && $_REQUEST['free']==1)
		  {
		   $incpay.=" AND ((s.paymentmodeid=1 and s.paymenttransactionid!='A1')  or e.registrationtype=1 or s.discount !='X')"; 
		  }
		    if($_REQUEST['card']==1 && $_REQUEST['chq']!=1 && $_REQUEST['free']!=1)
		  {
		   $incpay.=" AND ((s.paymentmodeid=1 and s.paymenttransactionid!='A1') or s.discount !='X')"; 
		  }
		    if($_REQUEST['card']!=1 && $_REQUEST['chq']!=1 && $_REQUEST['free']!=1)
		  {
		   $incpay.=" AND ((s.paymentmodeid=1 and s.paymenttransactionid!='A1') or s.discount !='X')";
		   $_REQUEST['card']=1; 
		  }
          if($_REQUEST['card']!=1 && $_REQUEST['chq']==1 && $_REQUEST['free']==1)
		 {
		   $incpay.=" AND ( s.paymentmodeid=2 or e.registrationtype=1 or s.discount !='X')"; 
		 }       
          if($_REQUEST['card']!=1 && $_REQUEST['chq']==1 && $_REQUEST['free']!=1)
		 {
		   $incpay.=" AND ( s.paymentmodeid=2 or s.discount !='X')"; 
		 }   
		 	 if($_REQUEST['card']!=1 && $_REQUEST['chq']!=1 && $_REQUEST['free']==1)
		 {
		   $incpay.=" AND (e.registrationtype=1 or s.discount !='X')"; 
		 } 
		
		
		//$EventsQuery = "SELECT distinct(e.Id),e.UserID,e.Title FROM events e,EventSignup s WHERE e.Id=s.EventId and e.SalesId=".$SalesId." $stdt  ORDER BY e.Title ASC";
		//((sum(estd.amount))+ROUND(((sum(estd.amount))*(txm.value/100)),0)) as Amount 
		//    taxmapping txm,    txm.value,                      AND etx.taxmappingid = txm.id 
                $EventsQuery = "SELECT t.eventid, u.company,e.title,sum(ticketquantity) as Tickets_Sold , 
                                sum(estd.amount) as Amount,
                                sum(etx.taxamount)+sum(estd.amount) as taxAmount 
                                FROM eventsignupticketdetail estd
                                join ticket t on estd.ticketid = t.id
                                join event e on e.id = t.eventid
                                join user u on u.id = e.ownerid
                                join (select s.id AS Id FROM eventsignupticketdetail st, eventsignup AS s , 
                                eventsalespersonmapping AS es, event e
                                WHERE e.id=es.eventid AND s.eventid = es.eventid AND s.id = st.eventsignupid ".  
                                $incpay. " AND  es.salesid=".$SalesId.$stdt." ) as x on x.id =estd.eventsignupid
                                left join eventsignuptax etx on etx.eventsignupid = estd.eventsignupid    
                                                                       
                                GROUP BY t.eventid 
                                ORDER BY e.title";
                
   		$EventsOfMonth = $Global->SelectQuery($EventsQuery);
                  // print_r($EventsOfMonth);
	}
		
		 $SalesQuery = "SELECT id AS SalesId,`name` AS SalesName from  salesperson   ORDER BY `name`" ; 
		$SalesQueryRES = $Global->SelectQuery($SalesQuery);

	include 'templates/monthlytrans.tpl.php';
?>