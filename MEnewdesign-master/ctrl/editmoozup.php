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
	
	if($_POST['Submit'] == "Delete")
	{
		include_once("MT/cCities.php");		//include the cCities MT
		$delJujamaIds = $_POST['jujama'];			//get the list of cities in array of delCityIds
		
		foreach($delJujamaIds as $JId)			//get the city id one by one to delete
		{
			$Id = $JId;
		
			try
			{	
				$delsql="delete from jujama where Id=".$Id;
				$done=$Global->ExecuteQuery($delsql);
			}
			catch (Exception $Ex)
			{
				echo $Ex->getMessage();
			}
		}
	}// END IF delete
		
	//Query For All Cities
//	$JQuery = "SELECT * FROM jujama";
        $JQuery = "SELECT `Id`, `MEventId`, `JEventId`, `DPassword` FROM jujama";
	$JList = $Global->SelectQuery($JQuery);

	include 'templates/editmoozup.tpl.php';
?>