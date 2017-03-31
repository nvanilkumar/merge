<?php
include("commondbdetails.php");

/*file to delete older (before 20 days) orderlogs from DB - 28th-Feb-2014*/


include("../ctrl/MT/cGlobali.php");
$cGlobali=new cGlobali();

include_once '../ctrl/includes/common_functions.php';
$commonFunctions=new functions();
$_GET=$commonFunctions->stripData($_GET,1);

//error_reporting(-1);

if($_GET['runNow']==1) 
{

	$sTd=date("Y-m-d 23:59:59",strtotime("-1 month"));
	
	$sql="select userid,messageid,eventsignupid,cts,mts,createdby,modifiedby,status from `sentmessage` where `cts` < '".$sTd."' ";
	//echo $sql."<br>";
	$data = $cGlobali->justSelectQuery($sql);
	
	
	if($data->num_rows > 0)
	{
		while($rec = $data->fetch_assoc())
		{			
			$insert = "insert into `sentmessagearchive` (userid,messageid,eventsignupid,cts,mts,createdby,modifiedby,status) values ('".$rec['userid']."','".$rec['messageid']."','".$rec['eventsignupid']."','".$rec['cts']."','".$rec['mts']."','".$rec['createdby']."','".$rec['modifiedby']."','".$rec['status']."') ";
			echo $insert."<br>";
			$cGlobali->ExecuteQuery($insert);
		}
	}
	
	
	//removing records from orderlogs
	$sqlDel="delete from `sentmessage` where `cts`<'".$sTd."'";
	$cGlobali->ExecuteQuery($sqlDel);
	
}
?>