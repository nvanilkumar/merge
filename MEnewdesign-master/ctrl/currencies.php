<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	It displays the city list as per the states list.
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 22nd Aug 2009
******************************************************************************************************************************************/
	session_start();
	//echo "<pre>";	
	include_once("MT/cGlobali.php");
	
	$Global = new cGlobali();
        include 'loginchk.php';	
		
		
	//initializing variables
	$currId='';
	$currName=$currCode=NULL;
	$uId =$_SESSION["uid"];
	if(isset($_GET['id']))
	{
		$currId=$_GET['id'];
		
		$CurrQueryEdit = "SELECT id,name,code,symbol FROM currency where id='".$currId."'";
		$CurrDataEdit = $Global->SelectQuery($CurrQueryEdit);
		$currName=$CurrDataEdit[0]['name'];
		$currCode=strtoupper($CurrDataEdit[0]['code']);
                $currSymbol = $CurrDataEdit[0]['symbol'];
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
	if(isset($_POST['currFrmSub']))
	{
            //print_r($_POST);
		$currName=$_POST['currName'];
		$currCode=strtoupper(str_replace(' ', '',$_POST['currCode']));
		$currId=$_POST['currId'];
		$currSymbol=$_POST['currSymbol'];
		
		
		 $sqlUp="insert into `currency` (`id`,`name`,`code`, `symbol`,`createdby`,`modifiedby`) values('".$currId."','".$currName."','".$currCode."', '".mysqli_real_escape_string($Global->dbconn,$currSymbol)."','".$uId."','".$uId."') ON DUPLICATE KEY UPDATE `name`='".$currName."', `code`='".$currCode."', `symbol`='".mysqli_real_escape_string($Global->dbconn,$currSymbol)."',`createdby`='".$uId."',`modifiedby`='".$uId."'";
		if($Global->ExecuteQuery($sqlUp))
		{
			$_SESSION['currencyUp']=true;
			header("Location: currencies.php");
			exit;
		}
		
	}
	
		
	//Query For All Cities
	$CurrQuery = "SELECT id,name,code,symbol FROM currency where name!='free' order by id DESC";
	$CurrData = $Global->SelectQuery($CurrQuery);
        //print_r($CurrData);
        
	//echo "</pre>";	
	include 'templates/currencies.tpl.php';
?>