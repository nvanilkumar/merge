<?php
	session_start();
	include 'loginchk.php';
	
	include_once("MT/cGlobal.php");
	include_once("MT/cLocation.php");
	
	$Global = new cGlobal();
		
	if($_POST['Submit'] == "Update")
	{
		$location_name = $_POST['location_name'];
		$location_name = trim($location_name);
		
		
		$Id = $_POST['location_id'];
		$StateId = $_POST['StateId'];
		$CityId = $_POST['CityId'];
		
		// UPDATE country master WITH UPDATED VALUE
		$UpdateLocation = new cLocation($Id, $location_name, $StateId, $CityId);
		$Id = $UpdateLocation->Save();
		if($Id > 0)
		{
			header("location:editsplocation.php");
		}
	}
	
	$LocationId = $_GET['id'];
	
	 $LocationQuery = "SELECT * FROM Location WHERE Id = '".$LocationId."'"; //using 3/4 -pH
	$EditLocation = $Global->SelectQuery($LocationQuery);
	
//	$citySql = "SELECT * FROM Cities where StateId=".$EditLocation[0][StateId]; 
        $citySql = "SELECT `Id`,`City` FROM Cities where StateId=".$EditLocation[0][StateId]; 
	$editCity = $Global->selectQuery($citySql);
	
	//$stateSql = "SELECT * FROM States";
        $stateSql = "SELECT `Id`,`State` FROM States";
	$editState = $Global->selectQuery($stateSql);

	include 'templates/location_edit.tpl.php';
?>