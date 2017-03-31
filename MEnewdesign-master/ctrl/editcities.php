<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	It displays the city list as per the states list.
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 22nd Aug 2009
******************************************************************************************************************************************/
	session_start();
		
	include_once("MT/cGlobali.php");
	
	$Globali = new cGlobali();
        include 'loginchk.php';	
	   $countryid=$_REQUEST['countryid'];
	   $countryid = (isset($countryid) && $countryid !=0)?$countryid:14;
		$stateid=$_REQUEST['stateid'];
	
	if($_POST['Submit'] == "Delete")
	{
		include_once("MT/cCities.php");		//include the cCities MT
		$delCityIds = $_POST['city'];			//get the list of cities in array of delCityIds
		
		foreach($delCityIds as $CId)			//get the city id one by one to delete
		{
			$Id = $CId;
		
			try
			{	
				$City = new cCities($Id);
				$Id = $City->Delete();
				if($Id > 0)
				{	
					//delete successful statement
				}
			}
			catch (Exception $Ex)
			{
				echo $Ex->getMessage();
			}
		}
	}// END IF delete
		
	//Query For All Cities
	$CountryQuery = "SELECT c.id, c.name from country as c where c.status = 1 and c.deleted = 0 order by name";
	$CountryList = $Globali->SelectQuery($CountryQuery);
	if(isset($countryid) && $countryid !=0){
	$StateQuery = "SELECT s.id, s.name from state as s where s.countryid=".$countryid." and s.status = 1 and s.deleted = 0 order by name";
	$StateList = $Globali->SelectQuery($StateQuery);
	}
    if((isset($countryid) && $countryid !=0) && (isset($stateid) && $stateid !=0))
	{
		//print_r($_POST); exit;
		$CityIdquery = "SELECT cityid from statecity where stateid =".$stateid;
		$CityidList = $Globali->SelectQuery($CityIdquery);
		$cityarray = "";
		if(count($CityidList) > 0){
		for($i=0;$i<count($CityidList);$i++)
		{
			 $cityarray.= $CityidList[$i]['cityid'].",";
		}	
	    $cityarray=substr($cityarray,0,-1);
		$CityQuery = "SELECT c.*  FROM city AS c WHERE c.id in(".$cityarray.") and c.status in (0,1)  and c.deleted = 0"; 
	    $CityList = $Globali->SelectQuery($CityQuery);
		}
	}		
	

	include 'templates/editcities.tpl.php';
?>