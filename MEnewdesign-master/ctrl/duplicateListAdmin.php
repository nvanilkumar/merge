<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Display Cancel Transactions
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 26th Aug 2009
 *	2.	Added the new filed IsFamous in db which is used to display the Famous Events on the front end.
 * 		The check box property checked shows the event is famous, visible on front end and vice versa.
******************************************************************************************************************************************/
	
	session_start();
	$uid =	$_SESSION['uid'];
	
	include 'loginchk.php';
	
	include_once("MT/cGlobal.php");
	
	$Global = new cGlobal();
               if($_REQUEST[edit]=="Save")
		{
		$updateAttendes="update EventSignup set Name='".$_REQUEST[aName]."',EMail='".$_REQUEST[aEmail]."',Phone='".$_REQUEST[aPhone]."' where Id=".$_REQUEST[attend];
		$ResUpAttend = $Global->ExecuteQuery($updateAttendes);
	
		}
		
		  if(isset($_REQUEST[del]))
		{
//		 $sqlreg="select * from EventSignup where Id=".$_REQUEST[del];
                      $sqlreg="select `Id` from EventSignup where Id=".$_REQUEST[del];
		 $ressqlreg=$Global->SelectQuery($sqlreg);
		 if(count($ressqlreg)>0){
		// $sqlselticksigup="select * from eventsignupticketdetails where EventSignupId=".$_REQUEST['del'];
                      $sqlselticksigup="select `NumOfTickets`,`TicketId` from eventsignupticketdetails where EventSignupId=".$_REQUEST['del'];
		 $ressqlticksign=$Global->SelectQuery($sqlselticksigup);
		 for($i = 0; $i < count($ressqlticksign); $i++)
		{ 
		$Global->ExecuteQuery("update tickets set ticketLevel=ticketLevel-".$ressqlticksign[$i]['NumOfTickets']." where Id=".$ressqlticksign[$i]['TicketId']); 
		}
		$sqldelsign="delete from eventsignupticketdetails where EventSignupId=".$_REQUEST[del];
		$Resdelsign = $Global->ExecuteQuery($sqldelsign);
		$sqldelattend="delete from Attendees where EventSignupId=".$_REQUEST[del];
		$Resdelattend = $Global->ExecuteQuery($sqldelattend);
		$sqldeldup="delete from EventSignup where Id=".$_REQUEST[del];
		$Resdeldup = $Global->ExecuteQuery($sqldeldup);
		}
		}
	
	
	if(isset($_REQUEST['EventId']))
	{ 
	//$sqlduplist="select * from EventSignup  where EventId=".$_REQUEST['EventId']." and ((PaymentModeId=1 and PaymentTransId!='A1') or PaymentModeId=2 or  PromotionCode !='X' or Fees=0) and EMail in( select EMail from EventSignup where EventId=".$_REQUEST['EventId']."  and ((`PaymentModeId`=1 and PaymentTransId!='A1') or PaymentModeId=2 or  PromotionCode !='X' or Fees=0) group by EMail having count(EMail)>1) order by EMail";
            $sqlduplist="select `PaymentModeId`,`Id`,`PaymentTransId`,`SignupDt`,`PromotionCode`,`Name`,`Email`,`Phone`,`Qty`, `Fees` from EventSignup  where EventId=".$_REQUEST['EventId']." and ((PaymentModeId=1 and PaymentTransId!='A1') or PaymentModeId=2 or  PromotionCode !='X' or Fees=0) and EMail in( select EMail from EventSignup where EventId=".$_REQUEST['EventId']."  and ((`PaymentModeId`=1 and PaymentTransId!='A1') or PaymentModeId=2 or  PromotionCode !='X' or Fees=0) group by EMail having count(EMail)>1) order by EMail";
	$Resduplist = $Global->SelectQuery($sqlduplist);
	$cou=count($Resduplist);
	}
	
	
	
       $EventQuery = "SELECT distinct(e.Id),e.Title FROM events e,EventSignup s  WHERE e.Id=s.EventId  and e.StartDt > now() order by e.Title ";
	$EventQueryRES = $Global->SelectQuery($EventQuery);
	
	include 'templates/duplicateListAdmin.tpl.php';
?>