
<?php
	session_start();
		
	include_once("MT/cGlobal.php");
	include_once("includes/common_functions.php");
	
	$Global = new cGlobal();	
	$functions=new functions();
	 include 'loginchk.php';
	 
	$page=1;//Default page
	$limit=1000;//Records per page
	$start=0;//starts displaying records from 0
	if(isset($_GET['page']) && $_GET['page']!=''){
	$page=$_GET['page'];
	}
$start=($page-1)*$limit;

	if(isset($_GET['delmember']))
	{	
		$delmember = $_GET['delmember'];
		$currpage=$_GET['page'];
		$sqlDel="delete from pmiNationalConf where Id='".$delmember."'";
		if($Global->ExecuteQuery($sqlDel))
		{
			$_SESSION['pmiDeleted']=true;
			header("Location: pminationalconf.php?page=".$currpage); exit;
		}
	}// END IF delete
	
	
	if(isset($_POST['addMember']))
	{
		$memberid=trim($_POST['memberid']);
		$certificateid=trim($_POST['certificateid']);
		$memname=trim($_POST['memname']);
		$mememail=trim($_POST['mememail']);
		$memmobile=trim($_POST['memmobile']);
		$memidstatus=trim($_POST['memidstatus']);
		$memcertidstatus=trim($_POST['memcertidstatus']);
		$searchStr='';
		if(empty($memberid) && empty($certificateid) && empty($memname) && empty($mememail) && empty($memmobile) && empty($memidstatus) && empty($memcertidstatus)){
			$_SESSION['duplicatePmi']=true;
			$_SESSION['duplicateMsg']="Please enter atleast one field value with valid value";
			header("Location: pminationalconf.php"); exit;
		}
		else if(!empty($memberid) && !empty($certificateid)){
			$searchStr="where `memberId`='".$memberid."' and `certificateId`='".$certificateid."'";
			$_SESSION['duplicateMsg']="Duplicate combination of member id and certificate id..";
		}
		else if(!empty($memberid)){
			$searchStr="where `memberId`='".$memberid."'";
			$_SESSION['duplicateMsg']="Duplicate member id..";
		}
		else if(!empty($certificateid)){
			$searchStr="where `certificateId`='".$certificateid."'" ;
			$_SESSION['duplicateMsg']="Duplicate certificate id..";
		}
		if(!empty($searchStr)){
			$pmiChk = "SELECT * FROM `pmiNationalConf` ".$searchStr;
			if($Global->SelectQuery($pmiChk)>0)
			{
				$_SESSION['duplicatePmi']=true;
			}
			else
			{
				$sqlInsert="insert into `pmiNationalConf` (`memberId`,`certificateId`,`Name`,`Email`,`Mobile`,`statusM`,`statusC`) values('".$memberid."','".$certificateid."','".$memname."','".$mememail."','".$memmobile."','".$memidstatus."','".$memcertidstatus."')";
				if($Global->ExecuteQuery($sqlInsert))
				{
					$_SESSION['pmiAdded']=true;
					header("Location: pminationalconf.php"); exit;
				}
			}
		}
		else
			{
				$sqlInsert="insert into `pmiNationalConf` (`memberId`,`certificateId`,`Name`,`Email`,`Mobile`,`statusM`,`statusC`) values('".$memberid."','".$certificateid."','".$memname."','".$mememail."','".$memmobile."','".$memidstatus."','".$memcertidstatus."')";
				if($Global->ExecuteQuery($sqlInsert))
				{
					$_SESSION['pmiAdded']=true;
					header("Location: pminationalconf.php"); exit;
				}
			}
	}
	$rows=$Global->GetSingleFieldValue("select count(*) from pmiNationalConf");	
	$searchStr='';
	if(isset($_POST['searchForThisKeyword'])){
		$searchStr="WHERE memberId LIKE '%".$_POST['searchKeyword']."%' OR certificateId LIKE '%".$_POST['searchKeyword']."%' OR Email LIKE '%".$_POST['searchKeyword']."%'";
		$start=0;
	}
	////////////////////////////////Query For All records////////////////////////////////
	$pmiQuery = "SELECT * FROM `pmiNationalConf` $searchStr order by Id desc LIMIT $start, $limit"; 
	$pmiList = $Global->SelectQuery($pmiQuery);
	////////////////////////////////

	include 'templates/pminationalconf_tpl.php';
?>