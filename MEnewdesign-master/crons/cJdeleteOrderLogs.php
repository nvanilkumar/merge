<?php
include("commondbdetails.php");

/*file to delete older (before 20 days) orderlogs from DB - 28th-Feb-2014*/


include("../ctrl/MT/cGlobali.php");
$cGlobali=new cGlobali();

include_once '../ctrl/includes/common_functions.php';
$commonFunctions=new functions();
$_GET=$commonFunctions->stripData($_GET,1);


if($_GET['runNow']==1) 
{

	$sTd=date("Y-m-d 18:30:01",strtotime("-1 day"));
	//echo $sTd;
	//check if index is already available or not
	$selIndex="show indexes from orderlog where Column_name='orderid'";
	$show=$cGlobali->SelectQuery($selIndex);
	//drop if exists
	if(count($show)>0){
		$dropIndex="drop index orderlog_orderid_idx on orderlog";
		$cGlobali->ExecuteQuery($dropIndex);
	}
	//removing records from orderlogs
	$sql="delete from `orderlog` where `datetime`<'".$sTd."'";
	$cGlobali->ExecuteQuery($sql);
	
	//create index on orderid column
	$addIndex="create index orderlog_orderid_idx on orderlog(orderid)";
	$cGlobali->ExecuteQuery($addIndex);
}
?>