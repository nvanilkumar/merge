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
	
	if($_REQUEST[DeleTrans]=="Delete")
	{
	$DelQuery="delete from EventSignup where Id=".$_REQUEST[transid];
    $ResDel= $Global->ExecuteQuery($DelQuery);
	}
	
	if($_REQUEST[EventId]!="")
	{
	$EventTransId=" AND EventId=".$_REQUEST[EventId];
	}
	
	if($_REQUEST[value]!="")
	{
	 $UpQuery="update EventSignup set eStatus='".$_REQUEST[value]."' where Id=".$_REQUEST['sId'];
	
    $ResUp= $Global->ExecuteQuery($UpQuery);
	}
	
	$sqluserId="SELECT distinct(UserId) FROM EventSignup WHERE  PaymentTransId='A1' $EventTransId  and `UserId` not in (select `UserId` FROM `EventSignup` WHERE `PaymentTransId`!='A1' $EventTransId)";
	$UsrTranRES = $Global->SelectQuery($sqluserId);
	 $userId=" ";
	for($i=0;$i<count($UsrTranRES);$i++)
	{
	$userId.=$UsrTranRES[$i][UserId].",";
	}
	$userId=substr($userId,0, -1);
$CanTranQuery = "SELECT `FirstName`,`LastName`,`Mobile`,`Email` FROM `user` WHERE `Id` in ($userId) ";
$CanTranRES = $Global->SelectQuery($CanTranQuery);
	
	 $EventQuery = "SELECT distinct(s.EventId), e.Title AS Details FROM EventSignup AS s INNER JOIN events AS e ON s.EventId = e.Id  ORDER BY e.Id DESC"; 
		$EventQueryRES = $Global->SelectQuery($EventQuery);
	include 'templates/CancelUnqTrans.tpl.php';	
?>
