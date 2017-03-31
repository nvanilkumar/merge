<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Add the city against the state.
 *	It checkes the city name against the state already exist or not if it shows the error message.
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 22nd Aug 2009
******************************************************************************************************************************************/
	
	session_start();
	
	include_once("MT/cGlobali.php");
	include_once("MT/cCities.php");
	
	$Globali = new cGlobali();
        include 'loginchk.php';
	
	$msgStateCityExist = '';
	    $countryid=$_POST['countryid'];
		$stateid=$_POST['stateid'];
		$city_name = $_POST['city'];
		$status = $_POST['status'];
		$featured = $_POST['featured'];
		if(isset($_POST['specialcity']) && $_POST['specialcity']==1){
			$specialcity = $_POST['stateid'];
		}
		
		$order = $_POST['order'];
		
	
	if(isset($city_name) && $city_name != "")
	{		
		
		
		$city_name = trim($city_name);
		$city_name = strtolower($city_name);
		
		// MAKE ALL FIRST LETTERS OF EACH WORD CAPITAL
		$names = explode(" ",$city_name);
		foreach($names as $key => $val)
		{
			$words[] = ucfirst($val);
		}
		$city_name = implode(" ",$words);
		
		//print_r($_POST); exit;

		
		$CityQuery = "SELECT c.id FROM city as c, statecity as sc WHERE c.id = sc.cityid and c.name = '".$city_name."' and c.countryid = '".$countryid."' and sc.stateid = '".$stateid."' and c.deleted = 0";
		$CityId = $Globali->SelectQuery($CityQuery);

		if(count($CityId) > 0)
		{
			$msgStateCityExist = 'City Name for this State already exist!';
		}
		else
		{
			try
			{	
				   $CityQ = "INSERT into city (`name`,`status`,`featured`,`order`,`countryid`,`splcitystateid`,`cts`,`createdby`,`modifiedby`) values ('".$city_name."','".$status."','".$featured."','".$order."','".$countryid."','".$specialcity."',now(),'".$_SESSION["uid"]."','".$_SESSION["uid"]."')";
				 $id = $Globali->ExecuteQueryId($CityQ); 
				
				if($id!="")
				{
					 $insQuery = "INSERT into statecity (`stateid`,`cityid`,`cts`,`createdby`,`modifiedby`) values ('".$stateid."','".$id."',now(),'".$_SESSION["uid"]."','".$_SESSION["uid"]."')"; 
					$Globali->ExecuteQuery($insQuery);
					header("location:editcities.php?countryid=".$countryid."&stateid=".$stateid);
				}
			}
			catch (Exception $Ex)
			{
				echo $Ex->getMessage();
			}
		}	
	
	
	}	// END IF Add
	//Query For State List
	//Query For All Cities
	$CountryQuery = "SELECT c.id, c.name from country as c where c.status = 1 and c.deleted = 0 order by name";
	$CountryList = $Globali->SelectQuery($CountryQuery);
	if(isset($countryid) && $countryid !=0){
	$StateQuery = "SELECT s.id, s.name from state as s where s.countryid=".$countryid." and s.status = 1 and s.deleted = 0 order by name";
	$StateList = $Globali->SelectQuery($StateQuery);
	}
	include 'templates/addcity.tpl.php';
?>