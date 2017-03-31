<?php
	session_start();
	include 'loginchk.php';
	
	include_once("MT/cGlobali.php");
	
	
	$Global = new cGlobali();
	include_once("includes/common_functions.php");
        $common=new functions();

	//$base_path = '/home/meraeven/public_html/content';		
	
	$base_path = "../"._HTTP_Content;
	$EventId=$_REQUEST['EventId'];
	
	if(isset($_POST['chnageStatus']))
	{
		$recUp="";
		$recId = $_POST['recid'];
		$recStatus = $_POST['recStatus'];
		
		try
		{	
			$sqlUp="update `settlement` set `status`='".$recStatus."' where `id`='".$recId."'";
			if($Global->ExecuteQuery($sqlUp))
			{
				$recUp="Status changed successfully";
			}
			
		}
		catch (Exception $Ex)
		{
			echo $Ex->getMessage();
		}
	}		
	
	
	
	
	
	
	function getFileName($filePath)
	{
		$filePathEx=explode("/",$filePath);
		return end($filePathEx);
	}
	
	
	
	
	
    $PaymentinfoSql = "SELECT * FROM settlement where eventid='".$EventId."'"; //using 6/7
	$PaymentinfoRes = $Global->SelectQuery($PaymentinfoSql);
	//print_r($PaymentinfoRes);
	
	include 'templates/paymentdocs.tpl.php';
?>