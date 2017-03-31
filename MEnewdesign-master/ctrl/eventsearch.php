<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	It displays the event names
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 22nd Aug 2009
******************************************************************************************************************************************/
	session_start();
		
	include_once("MT/cGlobal.php");
	 include 'loginchk.php';

	$Global = new cGlobal();	
	if($_REQUEST['action'] == 'Delete')
	{
		$Id = $_REQUEST['act_id'];
		include_once("MT/cEventNames.php");
		
		try
		{	
			$objEventNames = new cEventNames($Id);
			$Id = $objEventNames->Delete();
			if($Id > 0)
			{	
				//delete successful statement
			}
		}
		catch (Exception $Ex)
		{
			echo $Ex->getMessage();
		}
	}// END IF delete

	//Query For All Events
	$EventQuery = "SELECT * FROM eventnames ORDER BY EventName ASC"; //using all -pH
	$EventList = $Global->SelectQuery($EventQuery);

	include 'templates/eventsearch.tpl.php';
?>