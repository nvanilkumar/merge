<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Edit the Event Name
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 22nd Aug 2009
 *	2.	commented for strtolower and ucfirst on 29t Sep 2009
******************************************************************************************************************************************/

	session_start();
	
	include_once("MT/cGlobal.php");
	include_once("MT/cEventNames.php");
	 include 'loginchk.php';

	$Global = new cGlobal();
	
	if($_POST['Submit'] == "Update")
	{
		$eventname = $_POST['eventname'];
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
		
		$Id = $_POST['eventname_id'];
		
		try
		{
			$objEventN = new cEventNames($Id, $eventname);
			$Id = $objEventN->Save();
	
			if($Id > 0)
			{
				header("location:eventsearch.php");
			}
		}
		catch (Exception $Ex)
		{
			echo $Ex->getMessage();
		}
	}
	else
	{
		$Id = $_GET['id'];
		
		//Query For State List
		$EventNameQuery = "SELECT * FROM eventnames WHERE Id='".$Id."'"; //using all -pH
		$EventNameList = $Global->SelectQuery($EventNameQuery);
		
		for($i = 0; $i <  count($EventNameList); $i++)
		{
			$eventname = $EventNameList[$i]['EventName'];
			$eventname_id = $EventNameList[$i]['Id'];
		}
	}
	
	include 'templates/eventname_edit.tpl.php';
?>