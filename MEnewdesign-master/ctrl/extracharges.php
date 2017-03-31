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
	 include 'loginchk.php';

	$Global = new cGlobali();	
	
	if($_POST['Submit'] == "Delete")
	{
		include_once("MT/cCities.php");		//include the cCities MT
		$delCityIds = $_POST['city'];			//get the list of cities in array of delCityIds
		
		foreach($delCityIds as $CId)			//get the city id one by one to delete
		{
			$Id = $CId;
		
			try
			{	
				//$delsql="delete from eventextracharge where id=".$Id;
				$delsql="update eventextracharge set deleted =1 where id=".$Id;
				$Global->ExecuteQuery($delsql);
			}
			catch (Exception $Ex)
			{
				echo $Ex->getMessage();
			}
		}
	}// END IF delete
		
	//Query For All Cities
	 $CityQuery = "SELECT c . *, cu.code, e.id as eventid, e.title,c.status"
                      ." FROM eventextracharge AS c"
                        ." inner join event AS e on c.eventid = e.id"
                       ." left join   currency as cu on c.currencyid = cu.id"
                       ." WHERE    c.deleted = 0 and e.deleted = 0"
                       ." ORDER BY e.startdatetime desc";
	$CityList = $Global->SelectQuery($CityQuery);

	include 'templates/extracharges.tpl.php';
?>