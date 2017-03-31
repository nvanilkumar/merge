<?php
	session_start();
		
	include_once("MT/cGlobali.php");
	
	$db = new cGlobali();	
	// include 'loginchk.php';
	if($_POST['Submit'] == "Delete")
	{
		include_once("MT/cCountries.php");		
		$delCountryIds = $_POST['country'];
		
		foreach($delCountryIds as $CId)
		{
			$Id = $CId;
		
			try
			{	
				$Country = new cCountries($Id);
				$Id = $Country->Delete();
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
		
	////////////////////////////////Query For All Countries////////////////////////////////
	 $CountryQuery = "SELECT * FROM country WHERE deleted = 0 and status in (0,1) ORDER BY `default` DESC,featured DESC,`order` ASC"; //using all -pH
	$CountryList = $db->SelectQuery($CountryQuery);
	////////////////////////////////

	include 'templates/editcountry.tpl.php';
?>