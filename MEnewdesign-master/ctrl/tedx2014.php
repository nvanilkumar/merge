<?php
	session_start();
		
	include_once("MT/cGlobal.php");
	
	$Global = new cGlobal();	
	 include 'loginchk.php';
	if(isset($_GET['delmember']))
	{	
		$delmember = $_GET['delmember'];
		
		$sqlDel="delete from onlyfor_tes where Id='".$delmember."'";
		if($Global->ExecuteQuery($sqlDel))
		{
			$_SESSION['tedDeleted']=true;
			header("Location: tedx2014.php"); 
			exit;
		}
	}// END IF delete
	
	
	if(isset($_POST['addMember']))
	{
		$email=trim($_POST['email']);
		
		$tedChk = "SELECT Id FROM `onlyfor_tes` where `MembershipType`='TEDx2014' and `Email`='".$email."'"; //using all -pH
		if($Global->SelectQuery($tedChk)>0)
		{
			$_SESSION['duplicateTED']=true;
		}
		else
		{
			$sqlInsert="insert into `onlyfor_tes` (`MembershipType`,`Email`) values('TEDx2014','".$email."')";
			if($Global->ExecuteQuery($sqlInsert))
			{
				$_SESSION['tedAdded']=true;
			}
		}
	}
		
	////////////////////////////////Query For All Countries////////////////////////////////
	$tedQuery = "SELECT * FROM `onlyfor_tes` where `MembershipType`='TEDx2014' order by Id desc"; //using all -pH
	$tedList = $Global->SelectQuery($tedQuery);
	////////////////////////////////

	include 'templates/tedx2014.tpl.php';
?>