<?php
	session_start();
		
	include_once("MT/cGlobal.php");
	
	$Global = new cGlobal();	
	 include 'loginchk.php';
	if(isset($_GET['delmember']))
	{	
		$delmember = $_GET['delmember'];
		
		$sqlDel="delete from pmichennaimembers where Id='".$delmember."'";
		if($Global->ExecuteQuery($sqlDel))
		{
			$_SESSION['pmiDeleted']=true;
			header("Location: pmimembers.php"); exit;
		}
	}// END IF delete
	
	
	if(isset($_POST['addMember']))
	{
		$memberid=trim($_POST['memberid']);
		$memname=trim($_POST['memname']);
		
		$pmiChk = "SELECT * FROM `pmichennaimembers` where `MemberShipId`='".$memberid."'"; //using all -pH
		if($Global->SelectQuery($pmiChk)>0)
		{
			$_SESSION['duplicatePmi']=true;
		}
		else
		{
			$sqlInsert="insert into `pmichennaimembers` (`MemberShipId`,`Name`) values('".$memberid."','".$memname."')";
			if($Global->ExecuteQuery($sqlInsert))
			{
				$_SESSION['pmiAdded']=true;
			}
		}
	}
		
	////////////////////////////////Query For All Countries////////////////////////////////
	$pmiQuery = "SELECT * FROM `pmichennaimembers` order by Id desc"; //using all -pH
	$pmiList = $Global->SelectQuery($pmiQuery);
	////////////////////////////////

	include 'templates/pmimembers.tpl.php';
?>