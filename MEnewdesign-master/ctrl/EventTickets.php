<?php
	session_start();
	$uid =	$_SESSION['uid'];
        include 'loginchk.php';
	include_once("MT/cGlobali.php");
	$Global = new cGlobali();
	
	include 'includes/common_functions.php';
	$commonFunctions=new functions();
        if(!empty($_REQUEST['EventId'])){
           $query="SELECT id FROM event WHERE id=".$_REQUEST['EventId']." and deleted=1";
           $outputTicket=$Global->SelectQuery($query);
        if(!$outputTicket){
	if((isset($_REQUEST['EventId']) && $_REQUEST['EventId']!="") || !empty($_REQUEST['eventIdSrch'])){
	 //$sqlUser="select * from events where Id=".$_REQUEST['EventId'];
         if(!empty($_REQUEST['EventId']))
            $eventIdSrch = $_REQUEST['EventId'];
         else if(!empty($_REQUEST['eventIdSrch']))
             $eventIdSrch = $_REQUEST['eventIdSrch'];
         //echo $eventIdSrch;
        $sqlUser="select e.ownerid,e.title,ed.extrareportingemails from event as e inner join eventdetail as ed on ed.eventid=e.id where e.deleted = 0 and e.id='".$eventIdSrch."'";
	$desUser=$Global->SelectQuery($sqlUser);
	if(count($desUser)>0){
	$usersql= "SELECT u.email, u.name, u.phone, u.mobile, u.cityid FROM user AS u where u.id=".$desUser[0]['ownerid'];
	$dtuser = $Global->SelectQuery($usersql);

	if($_REQUEST['Exportcard']=="Exportcard")
	{
		$out = 'Receipt No.,Signup Date,Transaction,PromotionCode,Name,Email,Phone No.,Amount';
		$out .="\n";
		$columns = 4;
		$signupId='';
			  $am=0;
			  $tickc=1;
		$sql2="select s.id,a.eventsignupid,u.name,u.email,u.phone,s.signupdate,s.paymenttransactionid,s.discount from  user as u,eventsignup as s where s.userid=u.id and s.eventid='".$eventIdSrch."' and s.paymentmodeid=1 and s.paymenttransactionid!='A1' order by s.id DESC";
		$sql="select es.id 'RecieptNo',                          
       es.signupdate 'Signup Date',
              CASE WHEN paymentgatewayid=2 THEN 'Cash on Delivery'
          ELSE es.paymenttransactionid 
          END 'Transaction/Cheque No.', 
       es.discount 'PromotionCode',
       CASE es.paymentstatus WHEN 'Verified' THEN 'Successful' 
          ELSE 'Pending' 
          End 'PaymentStatus',
       u.name 'Name' ,
       u.email 'Email',
       u.Phone 'Phone No.', '' Mobile,
       (es.totalamount - (es.eventextrachargeamount/es.quantity)) 'Paid' ,
t.name,(estd.amount/estd.ticketquantity) 'Amount' ,   es.paymentstatus   
FROM user as u,eventsignup as es , ticket t,eventsignupticketdetail estd
    WHERE es.userid=u.id 
                        and es.eventid='".$eventIdSrch."'
                        and es.paymentmodeid=1 
                        and es.paymenttransactionid!='A1' 
                        and t.id=estd.ticketid 
                        order by es.id desc";
		$ExportAttendeecard=$Global->SelectQuery($sql);
		$countAttendeeCard=count($ExportAttendeecard);
		for ($i = 0; $i < $countAttendeeCard; $i++) 
		{
/*		    $selEAQuery= "SELECT * FROM eventsignupticketdetails WHERE EventSignupId='".$ExportAttendeecard[$i]['EventSIgnupId']."'";
             $dtlEA = $Global->SelectQuery($selEAQuery);
                        if(count($dtlEA)>1)
			          {
                        if($dtlEA[$am][NumOfTickets] < $tickc ) 
                        { 
			    $am++; 
			   $tickc=1;
			 }else{
			        if($signupId != $ExportAttendeecard[$i]['EventSIgnupId'])
					{
					$am=0;  
					$signupId =$ExportAttendeecard[$i]['EventSIgnupId'];
					} 
			    $tickc++;
                     }
                     }else{
                      $am=0;
                       }*/
		     
			$out .='"'.$ExportAttendeecard[$i]['RecieptNo'].'",';
			$out .='"'.$ExportAttendeecard[$i]['Signup Date'].'",';
			$out .='"'.$ExportAttendeecard[$i]['Transaction/Cheque No.'].'",';
			$out .='"'.$ExportAttendeecard[$i]['PromotionCode'].'",';
			$out .='"'.$ExportAttendeecard[$i]['Name'].'",';
			$out .='"'.$ExportAttendeecard[$i]['Email'].'",';
			$out .='"'.$ExportAttendeecard[$i]['Phone No.'].'",';
			$out .='"'.$ExportAttendeecard[$i]['Amount'].'",';
			$out .="\n";
		}
	
	
		header("Content-type: text/x-csv");
		header("Content-Disposition: attachment; filename=CardTransaction.csv");
		echo $out;
		exit;			
	}
	

	if($_REQUEST['ExportChq']=="ExportChq")
	{ 
		$out = 'Receipt No.,Signup Date,Chequeno,PromotionCode,Name,Email,Phone No.,Amount';
		$out .="\n";
		$columns = 4;
		$signupId='';
			  $am=0;
			  $tickc=1;
		$sql="select a.EventSIgnupId,a.Name,a.Company,a.Email,a.Phone,s.SignupDt,s.PaymentTransId,s.PromotionCode from  Attendees as a,EventSignup as s where s.Id=a.EventSIgnupId and s.EventId=".$_REQUEST[EventId]." and s.PaymentModeId=2  order by a.EventSIgnupId DESC";
		$ExportAttendeecard=$Global->SelectQuery($sql);
		for ($i = 0; $i < count($ExportAttendeecard); $i++) 
		{
//		    $selEAQuery= "SELECT * FROM eventsignupticketdetails WHERE EventSignupId='".$ExportAttendeecard[$i]['EventSIgnupId']."'";
                    $selEAQuery= "SELECT `TicketAmt`,`NumOfTickets` FROM eventsignupticketdetails WHERE EventSignupId='".$ExportAttendeecard[$i]['EventSIgnupId']."'";
             $dtlEA = $Global->SelectQuery($selEAQuery);
                        if(count($dtlEA)>1)
			          {
                        if($dtlEA[$am][NumOfTickets] < $tickc ) 
                        { 
			    $am++; 
			   $tickc=1;
			 }else{
			        if($signupId != $list_row['EventSIgnupId'])
					{
					$am=0;  
					$signupId = $list_row['EventSIgnupId'];
					} 
			    $tickc++;
                     }
                     }else{
                      $am=0;
                       }
		    $chqno=$Global->GetSingleFieldValue("SELECT ChqNo FROM ChqPmnts WHERE EventSignupId='".$ExportAttendeecard[$i]['EventSIgnupId']."'");
			$out .='"'.$ExportAttendeecard[$i]['EventSIgnupId'].'",';
			$out .='"'.$ExportAttendeecard[$i]['SignupDt'].'",';
			$out .='"'.$chqno.'",';
			$out .='"'.$ExportAttendeecard[$i]['PromotionCode'].'",';
			$out .='"'.$ExportAttendeecard[$i]['Name'].'",';
			$out .='"'.$ExportAttendeecard[$i]['Email'].'",';
			$out .='"'.$ExportAttendeecard[$i]['Company'].'",';
            $out .='"'.$ExportAttendeecard[$i]['Phone'].'",';
			$out .='"'.($dtlEA[$am]['TicketAmt']/$dtlEA[$am]['NumOfTickets']).'",';
			$out .="\n";
		}
	
		
		header("Content-type: text/x-csv");
		
		header("Content-Disposition: attachment; filename=ChequeTransaction.csv");
		echo $out;
		exit;			
	}

     if($_REQUEST['ExportFree']=="ExportFree")
	{
		$out = 'Receipt No.,Signup Date,Transaction,PromotionCode,Name,Email,Company,Phone No.,Amount';
		$out .="\n";
		$columns = 4;
		$signupId='';
			  $am=0;
			  $tickc=1;
		$sql="select a.EventSIgnupId,a.Name,a.Company,a.Email,a.Phone,s.SignupDt,s.PaymentTransId,s.PromotionCode from  Attendees as a,EventSignup as s where s.Id=a.EventSIgnupId and s.EventId=".$_REQUEST[EventId]." and s.Fees=0    order by a.EventSIgnupId DESC";
		$ExportAttendeecard=$Global->SelectQuery($sql);
		for ($i = 0; $i < count($ExportAttendeecard); $i++) 
		{
//		    $selEAQuery= "SELECT * FROM eventsignupticketdetails WHERE EventSignupId='".$ExportAttendeecard[$i]['EventSIgnupId']."'";
                    $selEAQuery= "SELECT `amount`,`ticketquantity` FROM eventsignupticketdetail WHERE eventsignupid='".$ExportAttendeecard[$i]['EventSIgnupId']."'";
             $dtlEA = $Global->SelectQuery($selEAQuery);
                        if(count($dtlEA)>1)
			          {
                        if($dtlEA[$am][NumOfTickets] < $tickc ) 
                        { 
			    $am++; 
			   $tickc=1;
			 }else{
			        if($signupId != $list_row['EventSIgnupId'])
					{
					$am=0;  
					$signupId = $list_row['EventSIgnupId'];
					} 
			    $tickc++;
                     }
                     }else{
                      $am=0;
                       }
                     
			$out .='"'.$ExportAttendeecard[$i]['EventSIgnupId'].'",';
			$out .='"'.$ExportAttendeecard[$i]['SignupDt'].'",';
			$out .='"'.$ExportAttendeecard[$i]['PaymentTransId'].'",';
			$out .='"'.$ExportAttendeecard[$i]['PromotionCode'].'",';
			$out .='"'.$ExportAttendeecard[$i]['Name'].'",';
			$out .='"'.$ExportAttendeecard[$i]['Email'].'",';
			$out .='"'.$ExportAttendeecard[$i]['Company'].'",';
            $out .='"'.$ExportAttendeecard[$i]['Phone'].'",';
			$out .='"'.($dtlEA[$am]['TicketAmt']/$dtlEA[$am]['NumOfTickets']).'",';
			$out .="\n";
		}
	
		// Output to browser with appropriate mime type, you choose ;)
		header("Content-type: text/x-csv");
		//header("Content-type: text/csv");
		//header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=FreeRegistration.csv");
		echo $out;
		exit;			
	}
if($_REQUEST['ExportAll']=="ExportAll")
	{
		$out = 'Receipt No.,Signup Date,Transaction/chqno,PromotionCode,Name,Email,Company,Phone No.,Amount';
		$out .="\n";
		$columns = 4;
		$signupId='';
			  $am=0;
			  $tickc=1;
		//$sql2="select a.EventSIgnupId,a.Name,a.Company,a.Email,a.Phone,s.SignupDt,s.PaymentTransId,s.PromotionCode from  Attendees as a,EventSignup as s where s.Id=a.EventSIgnupId and s.EventId=".$_REQUEST[EventId]." and ((PaymentModeId=1 and PaymentTransId!='A1') or PaymentModeId=2 or  PromotionCode !='X' or s.Fees=0 )   order by a.EventSIgnupId DESC";
		$sql="select es.id 'RecieptNo',                          
       es.SignupDt 'Signup Date',
              CASE WHEN paymentgateway='CashonDelivery' THEN 'Cash on Delivery'
          ELSE es.PaymentTransId 
          END 'Transaction/Cheque No.', 
       es.PromotionCode 'PromotionCode',
       CASE es.eChecked WHEN 'Verified' THEN 'Successful' 
          ELSE 'Pending' 
          End 'PaymentStatus',
       a.name 'Name' ,
       a.Email 'Email',
       a.company 'Company',
       a.Phone 'Phone No.', '' Mobile,
       (es.fees - (es.Ccharge/es.Qty)) 'Paid' ,
t.name,(estd.TicketAmt/estd.NumOfTickets) 'Amount'      
FROM Attendees as a,EventSignup as es , tickets t,eventsignupticketdetails estd
    WHERE es.Id=a.EventSIgnupId and es.EventId=".$_REQUEST[EventId]."
    and t.id=estd.ticketid 
 and a.ticketid=estd.ticketid
 and a.eventsignupid=estd.eventsignupid
   and ((PaymentModeId=1 and PaymentTransId!='A1') or PaymentModeId=2 or  PromotionCode !='X' or es.Fees=0 )   
   order by a.EventSIgnupId DESC ";
   //echo $sql;
		$ExportAttendeecard=$Global->SelectQuery($sql);
		$countOfAttendees= count($ExportAttendeecard);
		for ($i = 0; $i < $countOfAttendees; $i++) 
		{
/*		    $selEAQuery= "SELECT * FROM eventsignupticketdetails WHERE EventSignupId='".$ExportAttendeecard[$i]['EventSIgnupId']."'";
             $dtlEA = $Global->SelectQuery($selEAQuery);
                        if(count($dtlEA)>1)
			          {
                        if($dtlEA[$am][NumOfTickets] < $tickc ) 
                        { 
			    $am++; 
			   $tickc=1;
			 }else{
			        if($signupId != $list_row['EventSIgnupId'])
					{
					$am=0;  
					$signupId = $list_row['EventSIgnupId'];
					} 
			    $tickc++;
                     }
                     }else{
                      $am=0;
                       }
		          if($ExportAttendeecard[$i]['PaymentTransId']!='A1')
				  {
				  $chqno=$ExportAttendeecard[$i]['PaymentTransId'];
				  }else{
				   $chqno=$Global->GetSingleFieldValue("SELECT ChqNo FROM ChqPmnts WHERE EventSignupId='".$ExportAttendeecard[$i]['EventSIgnupId']."'");
				  }*/
			$out .='"'.$ExportAttendeecard[$i]['RecieptNo'].'",';
			$out .='"'.$ExportAttendeecard[$i]['Signup Date'].'",';
			$out .='"'.$ExportAttendeecard[$i]['Transaction/Cheque No.'].'",';
			$out .='"'.$ExportAttendeecard[$i]['PromotionCode'].'",';
			$out .='"'.$ExportAttendeecard[$i]['Name'].'",';
			$out .='"'.$ExportAttendeecard[$i]['Email'].'",';
			$out .='"'.$ExportAttendeecard[$i]['Company'].'",';
            $out .='"'.$ExportAttendeecard[$i]['Phone No.'].'",';
			$out .='"'.$ExportAttendeecard[$i]['Amount'].'",';
			$out .="\n";
		}
	
		// Output to browser with appropriate mime type, you choose ;)
		header("Content-type: text/x-csv");
		//header("Content-type: text/csv");
		//header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=Attendeelist.csv");
		echo $out;
		exit;			
	}
        
        // taken out this query out from the below if condition so that the tpl also can use this .  -pH
//        $SelTickets = "SELECT * FROM tickets WHERE EventId='".$_REQUEST[EventId]."'";
        $SelTickets = "SELECT t.`name`,t.`price`,t.`startdatetime`,t.`enddatetime`,t.`status`, t.`totalsoldtickets`, tt.id  as Tax  FROM ticket as t
            left join tickettax as tt on tt.ticketid=t.id
                WHERE eventid='".$eventIdSrch."'";
	$ResTickets = $Global->SelectQuery($SelTickets);
	 
      if($_REQUEST['TicketEmail']=="SendEmail")
	{
	     $subject="Ticket Details of  ".$desUser[0]['title'];
		 
	//$SelTickets = "SELECT * FROM tickets WHERE EventId='".$_REQUEST[EventId]."'";
	//$ResTickets = $Global->SelectQuery($SelTickets);

$TickMsg='<p>Dear '.$dtuser[0]['FirstName'].',<br/><br/>Ticket Details of '.$desUser[0]['Title'].' are as below<br/><br/><table width="100%" border="0" cellspacing="0" cellpadding="0">
  			<tr>
			<td align="left" colspan="2"  id="showtickets">
            <div id="allTickets" style="padding:0px; margin:0px;">';
			if(count($ResTickets)>0) { 
		 $TickMsg.='<table width="100%" border="0" cellspacing="2" cellpadding="2" style="border:solid 1px #CCCCCC;">
                    <tr bgcolor="#F0F0F0">
					 <tr bgcolor="#ebeae9">
                     <td align="left" class="graybox"><strong>Name</strong></td>
					<td align="left" class="graybox"><strong>Price Rs/-</strong></td>
					<td align="left" class="graybox"><strong>Dates</strong></td>
					<td align="left" class="graybox"><strong>Inclusive of ST</strong></td>
					<td align="left" class="graybox"><strong>Tickets Sold</strong></td>
                    <td align="left" class="graybox"><strong>Status</strong></td>
					</tr>';
	 for($cntTkts = 0; $cntTkts < count($ResTickets); $cntTkts++)
	{
	$TickMsg.='<tr><td align="left" valign="middle" bgcolor="#F3F3F3" class="td_pad" style="font-size:small;">'.$ResTickets[$cntTkts]['name'].'</td>
           <td align="left" valign="middle" bgcolor="#F3F3F3" class="td_pad" style="font-size:small;"> '.$ResTickets[$cntTkts]['price'].'</td>
           <td align="left" valign="middle" bgcolor="#F3F3F3" class="td_pad" style="font-size:small;">Start :'.date('d-m-Y', strtotime($ResTickets[$cntTkts]['startdatetime'])).'<br />End :'.date('d-m-Y', strtotime($ResTickets[$cntTkts]['enddatetime'])).'</td><td align="left" valign="middle" bgcolor="#F3F3F3" class="td_pad" style="font-size:small;">';
if($ResTickets[$cntTkts]['Tax'] > 0) { 
$TickMsg.='Yes'; 
} else { 
$TickMsg.='No'; 
}
if($ResTickets[$cntTkts]['status']==0)
{
$status='Inactive';
}
if($ResTickets[$cntTkts]['status']==1)
{
$status='Active';
}
if($ResTickets[$cntTkts]['status']==2)
{
$status='Sold Out';
}
$TickMsg.='</td><td align="left" valign="middle" bgcolor="#F3F3F3" class="td_pad" style="font-size:small;">'.$ResTickets[$cntTkts]['totalsoldtickets'].'</td><td align="left" valign="middle" bgcolor="#F3F3F3" class="td_pad" style="font-size:small;">'.$status.'</td></tr>';
}
$TickMsg.='</table>';
$TickMsg.='<table widthe="100%"> <tr><td ><p>You are most welcome to contact us for any query, 
            comments and suggestions at <br /><a href="mailto:support@meraevents.com" target="_blank">
            support@meraevents.com</a>please call us at  +91-40-40404160</p> <br/>         </td>
      </tr>
      <tr>
        <td align="left" ><p><span class="style2">With Best Wishes,</span><br />
		    <a href="http://www.meraevents.com"><img src="http://www.meraevents.com/images/logo.jpg" border="0"></a><br />
            <strong><div class="imgbuttons"><div class="socialmediabg"><div style="float:left; margin-right:10px; line-height:30px;"><strong>Connect us</strong></div> 
               <div style="padding:5px 10px 5px 10px; line-height:20px; height:25px;" align="left"><a href="http://www.facebook.com/meraevents" target="_blank"><img src="http://www.meraevents.com/images/facebook.jpg" border="0" alt="Face Book" /></a>&nbsp;<a href="http://twitter.com/meraevents_com" target="_blank"><img src="http://www.meraevents.com/images/twiter.jpg" border="0" alt="Twitter" /></a>&nbsp;<a href="http://www.linkedin.com/company/www.meraevents.com" target="_blank"> <img src="http://www.meraevents.com/images/linkdin.jpg" border="0" alt="Linked In" /></a>&nbsp;<a href="http://labs.google.co.in/smschannels/subscribe/MeraEvents" target="_blank" ><img src="http://www.meraevents.com/images/Sms.png" border="0" height=21 width=21 alt="Google Sms Channels"/></a>&nbsp;<a href="http://www.meraevents.com/rss_events.php" target="_blank" ><img src="http://www.meraevents.com/images/Rss.png" height=21 width=21 border="0" alt="RSS"/></a>&nbsp;
         
     
        </div></div> 
    </div></strong></p>          </td>
      </tr>
      
    </table>    </td>
  </tr>
  
  <tr>';
$TickMsg.='</table>';
} 
		$TickMsg.='</div></td></tr></table><p>&nbsp;</p>';
   
		
		
	  
	   $to = $dtuser[0]['Email'];
	   $OEmails=$desUser[0]['OEmails'];
	  if($OEmails!="")
	  {
	  $to.=",".$OEmails;
	  }
	  
	  $cc=$content=$filename=$replyto=NULL;
		 
		 $from = 'MeraEvents<admin@meraevents.com>';
		$bcc='qison@meraevents.com';
		$folder='ctrl';
		$commonFunctions->sendEmail($to,$cc,$bcc,$from,$replyto,$subject,$TickMsg,$content,$filename,$folder);
	  
		//mail($to, $subject, $TickMsg, $headers);
	}
	}
	}
	if(isset($_REQUEST['past']) && $_REQUEST['past']=="Closed"){
	$past=" and e.enddatetime < now()"; 
	}else{
	$past=" and e.enddatetime > now()";
	}
//	$EventQuery = "SELECT distinct(s.eventid), e.title AS Details FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id $past  ORDER BY e.title "; 
//		$EventQueryRES = $Global->SelectQuery($EventQuery);
		//echo "Check";
		//count($EventQueryRES);
		//print_r($EventQueryRES);
        }}
        $eventId=$_REQUEST[EventId];   
	include 'templates/EventTickets.tpl.php';	
?>
