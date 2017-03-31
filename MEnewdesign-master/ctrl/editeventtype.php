<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	It displays the Event Types list.
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 24th Aug 2009
******************************************************************************************************************************************/
	session_start();
		
	include_once("MT/cGlobal.php");
	
	$Global = new cGlobal();	
	 include 'loginchk.php';

	if($_POST['Submit'] == "Delete")
	{
		$selected = $_POST['eventtype'];
		
		include_once("MT/cEventTypes.php");	//include the cEventTypes MT
		$delEventTypeIds = $_POST['eventtype'];		//get the list of event types in array of delEventTypeIds
		
		foreach($delEventTypeIds as $Id)			//get the event type id one by one to delete
		{
			try
			{	
				$objEventTypes = new cEventTypes($Id);
				$Id = $objEventTypes->Delete();
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
	
	//Query For All Event Types
	$EventTypesQuery = "SELECT * FROM eventtypes ORDER BY EventType ASC"; //using 3/4
	$EventTypeList = $Global->SelectQuery($EventTypesQuery);

	include 'templates/editeventtype.tpl.php';	
?>