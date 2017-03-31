
<?php
/************************************************************************************************ 
 *	Page Details : Cron Job Transactions happen yesterday and Send Mail
 *	Created / Last Updation Details : 
 *	1.	Created on 12th Nov 2010 : Created by SUNIL
************************************************************************************************/	
include_once("commondbdetails.php");
    include_once '../ctrl/includes/common_functions.php';
    $commonFunctions=new functions();
    include("../ctrl/MT/cGlobali.php");
    $cGlobali=new cGlobali();
    $_GET=$commonFunctions->stripData($_GET,1);
    $_POST=$commonFunctions->stripData($_POST,1);
    $_REQUEST=$commonFunctions->stripData($_REQUEST,1);

if($_GET['runNow']==1) {
	
    
	$db_conn = mysql_connect($DBServerName,$DBUserName,$DBPassword);
	mysql_select_db($DBIniCatalog);


	//CRETAE THE YESTERDAYS START / END DATE
	
	 $HourSDateIst = date ("Y-m-d ", mktime (0,0,0,date("m"),(date("d")-1),date("Y"))).'00:00:01';
	 $HourEDateIst = date ("Y-m-d ", mktime (0,0,0,date("m"),(date("d")-1),date("Y"))).'23:59:59';
         $HourSDate =$commonFunctions->convertTime($HourSDateIst, DEFAULT_TIMEZONE);
         $HourEDate =$commonFunctions->convertTime($HourEDateIst, DEFAULT_TIMEZONE);
//        
//        $HourSDate=date('Y-m-d H:i:s', strtotime('-1 hour'));
//        $HourEDate=date('Y-m-d H:i:s');
		
	$TotalAmount = 0;
	$cntTransactionRES = 1;	
	$cntTransactionSMRES = 1;	
	$TotalQty=0;
	
	
	$currentEventsSql="SELECT e.id 'EventId',e.ownerid 'UserID',e.title 'Title',ed.extrareportingemails 'OEmails', ed.viewcount 
	FROM event e 
	INNER JOIN eventdetail ed ON ed.eventid=e.id 
	WHERE e.eventmode = 0 and  e.enddatetime > now()  AND e.status = 1 and e.deleted=0";
//	$currentEventsData=mysql_query($currentEventsSql);
        $currentEventsData=$cGlobali->justSelectQuery($currentEventsSql);
	
   $key=0;
	$TransactionSMRES=array();
	while($row=$currentEventsData->fetch_assoc())
	{
           	$TransactionSMRES[$key]=$row;
		$key++;
	}

	
//	print_r($TransactionSMRES); exit;

      
	for($i = 0; $i < count($TransactionSMRES); $i++)
	{
		//echo $i."<br>";
			
			  $Msg = "<html xmlns='http://www.w3.org/1999/xhtml'>
			<head>
			<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
			<title>Untitled Document</title>
			<style>
			body {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 12px;
				font-weight: normal;
				color: #000000;
			}
			.style1 {font-size: 11px}
			</style>
			</head>
			<body>";
			
				
			 $Msg .= "<table width='100%' border='1' cellpadding='0' cellspacing='0' bordercolor='#F5F4EF'>
			<thead>
			 <tr bgcolor='#94D2F3'>
		  	<td class='tblinner' valign='middle' width='39%' align='left'>Event Details</td>
			<td class='tblinner' valign='middle' width='39%' align='left'>Page Views</td>
			<td class='tblinner' valign='middle' width='10%' align='center'>Reg Yesterday</td>
			<td class='tblinner' valign='middle' width='10%' align='center'>Qty</td>
			<td class='tblinner' valign='middle' width='35%' align='left'>TicketDetails   -->Sold</td>
           
            </tr>
        </thead>";
		$Msg .= "<tr>
			<td class='tblinner' valign='middle' width='39%' align='left'><font color='#000000'>".$TransactionSMRES[$i]['Title']."</font></td>
			<td class='tblinner' valign='middle' width='39%' align='left'><font color='#000000'>".$TransactionSMRES[$i]['viewcount']."</font></td>";
			
			
			$sqlhr="select sum(s.quantity) as Totqtyhr FROM eventsignup AS s , event AS e  WHERE s.eventid = e.id and  s.signupdate >= '".$HourSDate."' AND s.signupdate <= '".$HourEDate."' AND ((s.paymenttransactionid!='A1') or  (s.discount !='X' and s.totalamount=0) or e.registrationtype=1) and s.paymentstatus Not In ('Canceled','Refunded') and s.eventid='".$TransactionSMRES[$i]['EventId']."'";
//			$qtysqlhr=mysql_fetch_array(mysql_query($sqlhr));
                         $qtysqlhr =$cGlobali->GetSingleFieldValue($sqlhr);
			 
			
			 $sql="select sum(s.quantity) as Totqty FROM eventsignup AS s , event AS e  WHERE s.eventid = e.id and   ((s.paymenttransactionid!='A1') or  (s.discount !='X' and s.totalamount=0) or e.registrationtype=1) and s.paymentstatus Not In ('Canceled','Refunded') and s.eventid='".$TransactionSMRES[$i]['EventId']."'";
//			$qtysql=mysql_fetch_array(mysql_query($sql));
                        $qtysql =$cGlobali->GetSingleFieldValue($sql);
		       
			$Msg .= "<td class='tblinner' valign='middle' width='10%' align='center'><font color='#000000'>".$qtysqlhr."</font></td>
			
			<td class='tblinner' valign='middle' width='10%' align='center' ><font color='#000000'>".$qtysql."</font></td><td class='tblinner' valign='middle' width='30%'><table>";
		//	 $ticket="SELECT * FROM tickets WHERE EventId='".$TransactionSMRES[$i]['EventId']."'"; 
            //$ticket="SELECT `Id`, `Name`, `ticketLevel`  FROM tickets WHERE EventId='".$TransactionSMRES[$i]['EventId']."'"; 
			
	      $SelTicketsDB = "select t.id 'Id', t.`name` 'Name',
        t.totalsoldtickets 'ticketLevel',
        count(res.id) as soldTickets ,
        (count(res.id)  * t.price) as soldTicketPrice from ticket t left outer join (
              select a.id as Id,a.ticketid as ticketId FROM attendee as a
            Inner Join eventsignup as es on a.eventsignupid=es.id  WHERE   ((((es.paymentgatewayid=2) or es.paymenttransactionid!='A1' or
        (es. paymentmodeid=2 and paymenttransactionid='A1' and es.paymentstatus='Verified') )
        and es.paymentstatus NOT IN('Canceled','Refunded'))  or es.totalamount=0 or es.discount='FreeTicket' ) and es.eventid='".$TransactionSMRES[$i]['EventId']."'  
) as res on t.id=res.ticketid INNER JOIN currency c ON c.id=t.currencyid  where t.eventid='".$TransactionSMRES[$i]['EventId']."'  group by t.id" ;
			
//	         $resticket=mysql_query($SelTicketsDB);
                 $resticket=$cGlobali->justSelectQuery($SelTicketsDB);
$sumThr=0; 
			while($rowticket=$resticket->fetch_assoc())
			{
				$sqlthour="select sum(ticketquantity) as nomt from eventsignupticketdetail where ticketid='".$rowticket['Id']."' and eventsignupid in(select s.id FROM eventsignup AS s , event AS e  WHERE   s.eventid = e.id and  s.signupdate >= '".$HourSDate."' AND s.signupdate <= '".$HourEDate."' AND ((s.paymenttransactionid!='A1') or  (s.discount !='X' and s.totalamount=0) or e.registrationtype=1) and s.eventid='".$TransactionSMRES[$i]['EventId']."' AND s.paymentstatus NOT IN('Canceled','Refunded'))";
//				$tickqtyhr=mysql_fetch_array(mysql_query($sqlthour));
                                $tickqtyhr = $cGlobali->SelectQuery($sqlthour, MYSQLI_ASSOC);
                                
				if($tickqtyhr[0]['nomt']=="")
				{
				$thr=0;
				}else{
				$sumThr+=$tickqtyhr[0]['nomt'];
				$thr=$tickqtyhr[0]['nomt'];
				}
				$Msg .="<tr><td align='left'>".$rowticket['Name']."</td><td>Yesterday-->&nbsp;".$thr."&nbsp;</td><td >Total-->".$rowticket['soldTickets']."</td></tr>";
			}
			
			$Msg .="</table></td><tr></table></body></html>";
			
			
//	         $resUser=mysql_fetch_array(mysql_query("SELECT name 'FirstName',email 'Email' FROM user WHERE id='".$TransactionSMRES[$i]['UserID']."'"));
                 $resUserQuery="SELECT name 'FirstName',email 'Email' FROM user WHERE id='".$TransactionSMRES[$i]['UserID']."'";
                 $resUser = $cGlobali->SelectQuery($resUserQuery, MYSQLI_ASSOC);
                 
//                 $userOpted=mysql_query("select `type` , `status` from `alert` where `userid`='".$TransactionSMRES[$i]['UserID']."'");
                 $userOptedQuery= "select `type` , `status` from `alert` where `userid`='".$TransactionSMRES[$i]['UserID']."'";
                 $userOpted = $cGlobali->justSelectQuery($userOptedQuery);
                //print_r($userOpted)."<br>";
                //var_dump($userOpted);
               // echo "<hr>";
                 
            
                 
                  $flag=false; 
                  //if(count($userOpted)==0)
                 // if(empty($userOpted))
			 while($rowType=$userOpted->fetch_assoc())
			{
                  if($rowType['type'] == "dailytransaction")
				  {
					 if($rowType['status']==1){
                             $flag=true;
                         }else{
							 $flag=false;
						 } 
				  }
                                 
                  if($rowType['type'] == "dailysuccesstransaction")
				  {
			if($rowType['status']==1){
                            if($qtysqlhr > 0){
                             $flag=true;
                        }else{
			$flag=false;
			} 
				  }	  				  
                                  }	  
				
			} 
                  if($flag)
                  {
		 $to = $resUser[0]['Email'];


		if($TransactionSMRES[$i]['OEmails']!=""){
			//$to = "sunila@meraevents.com";
			$to.=",".$TransactionSMRES[$i]['OEmails'];
		 }
	$subject = '[MeraEvents] Organizer  Details From: '.$HourSDateIst.' To: '.$HourEDateIst;
	$message = 'Dear '.$resUser['FirstName'].',<br /><br />Transaction Details From: '.$HourSDate.' To: '.$HourEDate.'<br /><br />It contains  Registration of yesterday.<br /><br />'.$Msg.'<br /><br />to Export Attendees Information Please login to <a href="http://www.meraevents.com">http://www.meraevents.com</a><br/><br/> Regards,<br>Meraevents Team';
	
	$cc=$content=$filename=$replyto=NULL;
	$bcc='qison@meraevents.com';
	$from='MeraEvents<admin@meraevents.com>';
	$commonFunctions->sendEmail($to,$cc,$bcc,$from,$replyto,$subject,$message,$content,$filename);
	
	

        

                }//end if
	//mail($to, $subject, $message, $headers);
	}
	
	
	
	
	
	
	
	
//mysql_close($db_conn);
	
}
?>
