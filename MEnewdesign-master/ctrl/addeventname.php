<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Add the Event Names.
 *	It checkes the event name already exist or not if it shows the error message.
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 22nd Aug 2009
 *	2.	commented for strtolower and ucfirst on 29th Sep 2009
******************************************************************************************************************************************/
	
	session_start();
	include 'loginchk.php';
	
	include_once("MT/cGlobal.php");
	include_once("MT/cEventNames.php");
	
	$Global = new cGlobal();	

	$msgEventNameExist = '';

	if($_POST['Submit'])
	{
		$eventname = $_POST['event_name'];
		$eventname = trim($eventname); 
//		$eventname = strtolower($eventname);
		
		// MAKE ALL FIRST LETTERS OF EACH WORD CAPITAL
//		$names = explode(" ",$eventname);
//		foreach($names as $key => $val)
//		{
//			$words[] = ucfirst($val);
//		}
//		$eventname = implode(" ",$words);
		
		$eventname = str_replace("&","and",$eventname);
		
		$ENameQuery = "SELECT Id FROM eventnames WHERE EventName = '".$eventname."'";
		$Id = $Global->SelectQuery($ENameQuery);

		if(count($Id) > 0)
		{
			$msgEventNameExist = 'Event name already exist!';
		}
		else
		{
			try
			{	
				$objEName = new cEventNames(0, $eventname);
				
				if($objEName->Save())
				{
					header("location:eventsearch.php");
				}
			}
			catch (Exception $Ex)
			{
				echo $Ex->getMessage();
			}
		}
	}		

	include 'templates/addeventname.tpl.php';	
?>