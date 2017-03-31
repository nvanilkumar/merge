<?php
	session_start();
	include 'loginchk.php';
	include_once("MT/cGlobali.php");
	include_once("MT/cStates.php");
	
	$Global = new cGlobali();
	
	$msgCountryStateExist = '';
	
	if($_POST['Submit'] == "Add")
	{
		$country_id = $_POST['country'];
		$state_name = $_POST['state'];
		$state_name = trim($state_name);
		$state_name = strtolower($state_name);
		$state_name = ucfirst($state_name);
		
		$StateQuery = "SELECT Id FROM state WHERE countryid = '".$country_id."' AND name = '".$state_name."' AND deleted = 0";
		$StateId = $Global->SelectQuery($StateQuery);


		if(count($StateId) > 0)
		{
			$msgCountryStateExist = 'State Name for this Country already exist!';
		}
		else
		{
			try
			{	
				$State = new cStates(0, $state_name, $country_id);
				
				if($State->Save())
				{
					header("location:editstate.php");
				}
			}
			catch (Exception $Ex)
			{
				echo $Ex->getMessage();
			}
		}	
	
	}// END IF Add
		
	////////////////////////////////Query For Country List////////////////////////////////
	$CountryQuery = "SELECT * FROM country WHERE deleted = 0 AND status =1 ORDER BY name ASC"; //using all -pH
	$CountryList = $Global->SelectQuery($CountryQuery);
	////////////////////////////////

	include 'templates/addstate.tpl.php';
?>
