<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	It displays the city list as per the states list.
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 22nd Aug 2009
******************************************************************************************************************************************/
	session_start();
		
	include_once("MT/cGlobal.php");
	
	$Global = new cGlobal();
        include 'loginchk.php';	
		
		
	//initializing variables
	$currId='';
	$currName=$currCode=NULL;
	
	if(isset($_GET['id']))
	{
		$currId=$_GET['id'];
		
		$CurrQueryEdit = "SELECT id,name,code FROM currency where id='".$currId."'";
		$CurrDataEdit = $Global->SelectQuery($CurrQueryEdit);
		$currName=$CurrDataEdit[0]['Currency'];
		$currCode=strtoupper($CurrDataEdit[0]['code']);
	}
	
	
	
	/*if(isset($_POST['delCurrency']))
	{
		//print_r($_POST); exit;
		$delCurrencyId = $_POST['delCurrency'];			//get the list of cities in array of delCityIds
		
		$sqlDel="delete from `currencies` where `Id`='".$delCurrencyId."'";
		if($Global->ExecuteQuery($sqlDel))
		{
			$_SESSION['currencyDel']=true;
			header("Location: currencies.php");
			exit;
		}
	}*/
	// END IF delete
	
	
	
	
	//inserting/updating currencies
	$msg==NULL;
	if(isset($_POST['TaxFormDefault']))
	{
		$TaxType=$_POST['TaxType'];
		$rid=$_POST['rid'];
		$isdefault=$_POST['isdefault'];
		
		if($TaxType=="STax"){$type="ServiceTax"; $msg="Service Tax"; }else{$type="EntertainmentTax"; $msg="Entertainment Tax";}
		$Global->ExecuteQuery("update `taxes` set `isdefault`=0 where isdefault=1 and `type`='".$type."'");
		
		$sqlUp="update `taxes` set `isdefault`='".$isdefault."' where `id`='".$rid."'";
		
		if($Global->ExecuteQuery($sqlUp))
		{
			$_SESSION['successMsg']=$msg." default value updated successfully.";
			header("Location: taxes.php");
			exit;
		}
		
	}
	
	if(isset($_POST['addTaxForm']))
	{
		$taxVal=$_POST['taxVal'];
		$taxType=$_POST['taxType'];
		if($TaxType=="ServiceTax"){ $msg="Service Tax"; }else{ $msg="Entertainment Tax";}
		
		$sql="insert into `taxes` (`value`,`type`) values ('".$taxVal."','".$taxType."')";
		if($Global->ExecuteQuery($sql))
		{
			$_SESSION['successMsg']="New ".$msg." value added successfully.";
			header("Location: taxes.php");
			exit;
		}
	}
	
	if(isset($_POST['delTax']))
	{
		$rid=$_POST['rid'];
		$sqlDel="delete from `taxes` where `id`='".$rid."'";
		if($Global->ExecuteQuery($sqlDel))
		{
			$_SESSION['successMsg']="Record deleted successfully.";
			header("Location: taxes.php");
			exit;
		}
		
	}
	
		
	//Query For ServiceTax
	$STQuery = "SELECT id,value,isdefault FROM taxes where type='ServiceTax' order by value DESC";
	$STData = $Global->SelectQuery($STQuery);
	$STDataCount=count($STData);
	
	
	//Query For EntertainmentTax
	$ETQuery = "SELECT id,value,isdefault FROM taxes where type='EntertainmentTax' order by value DESC";
	$ETData = $Global->SelectQuery($ETQuery);
	$ETDataCount=count($ETData);

	include 'templates/taxes.tpl.php';
?>