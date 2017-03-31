<?php
	session_start();
	include 'loginchk.php';	
	include_once("MT/cGlobal.php");
	
	$Global = new cGlobal();	
	
	if($_POST['Submit'] == "Delete")
	{
		include_once("MT/cLocation.php");		
		$dellocationIds = $_POST['location'];
		
		foreach($dellocationIds as $CId)
		{
			$Id = $CId;
		
			try
			{	
				$location = new cLocation($Id);
				$Id = $location->Delete();
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
	
	$locationquery = "SELECT a.Id as Id, a.StateId as StateId, a.CityId as CityId,a.Loc as Loc, b.State as State,c.City as City FROM Location a, States b, Cities c WHERE b.Id = a.StateId and c.Id = a.CityId and a.status=1 order by  b.State";
	$locationList = $Global->SelectQuery($locationquery);
	////////////////////////////////

	include 'templates/editsplocation.tpl.php';
?>