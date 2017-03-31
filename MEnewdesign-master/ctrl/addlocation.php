<?php
	session_start();
	include 'loginchk.php';
	include_once("MT/cGlobal.php");
	include_once("MT/cLocation.php");
	
	$Global = new cGlobal();
	$MsgCountryExist = '';
	
	if($_POST['Submit'] == "Add")
	{	
		$location_name = $_POST['location_name'];
	    $location_name = trim($location_name);
		$stateId=$_POST[StateId];
		$cityId=$_POST[CityId];
		
		$LocationQuery = "SELECT Id FROM Location WHERE Loc='".$location_name."' and StateId=$stateId and CityId=$cityId";
		$LocationId = $Global->SelectQuery($LocationQuery);
		if(count($LocationId) > 0)
		{
			$MsgLocationExist = 'Location Name already exist!';
		}
		else
		{
			try
			{	
				$Location = new cLocation(0, $location_name, $stateId, $cityId);
				
				if($Location->Save())
				{
					header("location:editsplocation.php");
				}
			}
			catch (Exception $Ex)
			{
				echo $Ex->getMessage();
			}
		}
	}
//     $citySql = "SELECT * FROM Cities";
        $citySql = "SELECT `Id`, `City` FROM Cities";
	$editCity = $Global->selectQuery($citySql);
	
//	$stateSql = "SELECT * FROM States";
        $stateSql = "SELECT `Id`, `State` FROM States";
	$editState = $Global->selectQuery($stateSql);
	
	include 'templates/addlocation.tpl.php';
?>