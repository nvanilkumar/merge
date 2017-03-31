<?php
	session_start();
	
	include_once("MT/cGlobal.php");
	
	
	$Global = new cGlobal();
	$MsgCountryExist = '';
   $reg=$_REQUEST[regid];
   $email=$_REQUEST[email];
   $recptno=$_REQUEST[recptno];
	if($_REQUEST[submit]=="Save")
	{
	$updateEventSignup="update EventSignup set PaymentTransId='".$_REQUEST[PaymentTransId]."',PaymentStatus='".$_REQUEST[PaymentStatus]."' where Id=".$reg;
		$ResUpEventSignup = $Global->ExecuteQuery($updateEventSignup);
		if($_REQUEST[PaymentTransId]!='A1')
		{
		  $sqlreg="select PaidBit from Attendees where EventSIgnupId=".$reg;
		 $ressqlreg=$Global->SelectQuery($sqlreg);
		 if($ressqlreg[0][PaidBit]==0)
		 {
		 $updateAtt="update Attendees set PaidBit=1 where EventSIgnupId=".$reg;
		 $ResupdateAtt = $Global->ExecuteQuery($updateAtt);
		 $sqltck="select TicketId,NumOfTickets from eventsignupticketdetails where EventSIgnupId=".$reg;
		 $ressqlreg=$Global->SelectQuery($sqltck);
		 for($i = 0; $i < count($ressqlreg); $i++)
		 {
		 $updateAtt="update tickets set ticketLevel=ticketLevel+".$ressqlreg[$i][NumOfTickets]." where Id=".$ressqlreg[$i][TicketId];
		 $ResupdateAtt = $Global->ExecuteQuery($updateAtt);
		 }
		 }		
		}
		if($ResUpEventSignup)
		{?>
		<script>
		window.location="CheckReg.php?recptno=<?=$recptno;?>&email=<?=$email;?>&msg=Upated Successfully";
		</script>
		<? } else {
		?>
		<script>
		window.location="CheckReg.php?recptno=<?=$recptno;?>&email=<?=$email;?>&msg=Unable To Update";
		</script>
		<?  }
	}
	
	
	if(isset($_REQUEST[regid]) && $_REQUEST[regid]!=""){
	$signid=" and s.Id=".$_REQUEST[regid];
	
	//Display list of Successful Transactions
  	 $TransactionQuery =  "SELECT  s.Id, s.SignupDt, e.Title , s.Qty, s.Fees, s.PaymentTransId,s.PaymentStatus FROM EventSignup AS s INNER JOIN events AS e ON s.EventId = e.Id WHERE 1  $signid "; 
	 $TransactionRES=$Global->SelectQuery($TransactionQuery); 
	 
	 
	}	
		
	
			
	
	
	//mysql_close();

	include 'templates/CheckReg_edit.tpl.php';
?>