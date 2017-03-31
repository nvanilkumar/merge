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
        if($_REQUEST[AddComment]=="Add Comment")
	{
	$InsTransComments="insert into CancelTransComments(EventSIgnupId,Comment,PostedDate,PostedBy)values('".$_REQUEST[TransId]."','".$_REQUEST[comment]."','".date('Y-m-d')."','Admin')"; 
	$indId = $Global->ExecuteQuery($InsTransComments); 
	
	}

	
	/*if($_REQUEST[DeleTrans]=="Delete")
	{
	$DelQuery="delete from EventSignup where Id=".$_REQUEST[transid];
    $ResDel= $Global->ExecuteQuery($DelQuery);
	}*/
	
	if($_REQUEST[EventId]!="")
	{
	$EventTransId=" AND EventId=".$_REQUEST[EventId];
	}else {
          $EventTransId="";
         }
	if($_REQUEST[Status]!="")
	{
	$eStatus=" AND eStatus='".$_REQUEST[Status]."'";
	}
	
	if($_REQUEST[value]!="")
	{
	 $UpQuery="update EventSignup set eStatus='".$_REQUEST[value]."' where Id=".$_REQUEST['sId'];
	
    $ResUp= $Global->ExecuteQuery($UpQuery);
	}
	
 $CanTranQuery = "SELECT DISTINCT(s.Id), s.EventId,s.Name,s.EMail,s.Phone,s.CityId, s.SignupDt,s.field1,s.eStatus, e.Title, s.Qty, s.Fees, s.PaymentTransId FROM ChqPmnts AS cq, EventSignup AS s, events AS e WHERE s.EventId = e.Id  AND s.Fees != 0 AND s.PaymentTransId = 'A1' AND s.PaymentModeId = 1 AND s.Id != cq.EventSignupId $EventTransId $eStatus ORDER BY s.SignupDt DESC";
$CanTranRES = $Global->SelectQuery($CanTranQuery);

	if($_REQUEST['export']=="ExportCancelTrans")
	{
		$out = 'Receipt No.,Signup Date,Event Details,Name,Email,Phone,City,Qty,Amount';
		$out .="\n";
		$columns = 4;
		$TotalCanTranRES = count($CanTranRES);
		$cntCanTranRES = 1;
		
		for($i = 0; $i < $TotalCanTranRES; $i++)
		{
		
		
			
		$out .='"'.$CanTranRES[$i]['Id'].'",';
			$out .='"'.$CanTranRES[$i]['SignupDt'].'",';
			$out .='"'.$Global->GetSingleFieldValue("select Title from events where Id='".$CanTranRES[$i]['EventId']."'").'",';
			$out .= '"'.$CanTranRES[$i]['Name'].'",';
			$out .= '"'.$CanTranRES[$i]['EMail'].'",';
			$out .='"'.$CanTranRES[$i]['Phone'].'",';		
			$out .='"'.$Global->GetSingleFieldValue("select City from Cities where Id='".$CanTranRES[$i]['CityId']."'").'",';
			$out .='"'.$CanTranRES[$i]['Qty'].'",';
            $out .='"'.$CanTranRES[$i]['Fees'] * $CanTranRES[$i]['Qty'].'",';
			$out .="\n";
		
		}
	$filename="CancelTransactionlist.csv";
		// Output to browser with appropriate mime type, you choose ;)
		header("Content-type: text/x-csv");
		//header("Content-type: text/csv");
		//header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=CancelTransaction_export.csv");
		echo $out;
		
		exit;			
	}
	
	
	$EventQuery = "SELECT distinct(s.EventId), e.Title AS Details FROM EventSignup AS s INNER JOIN events AS e ON s.EventId = e.Id  ORDER BY e.Id DESC"; 
		$EventQueryRES = $Global->SelectQuery($EventQuery);
	include 'templates/CancelTrans.tpl.php';	
?>
