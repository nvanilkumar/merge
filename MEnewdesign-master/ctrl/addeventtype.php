<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Add the New Event Type Name
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 24th Aug 2009
******************************************************************************************************************************************/
	session_start();
	include 'loginchk.php';
		
	include_once("MT/cGlobal.php");
	
	$Global = new cGlobal();	

	$msgEventTypeExist = '';

	if($_POST['Submit'] == "Add")
	{
		$etype = $_POST['txtEventType'];
		$etype = trim($etype); 
		$etype = strtolower($etype);
		$DispOrder= $_POST['DispOrder'];
		
		// MAKE ALL FIRST LETTERS OF EACH WORD CAPITAL
		$names = explode(" ",$etype);
		foreach($names as $key => $val)
		{
			$words[] = ucfirst($val);
		}
		$etype = implode(" ",$words);
		
		include_once("MT/cEventTypes.php");
		
		$eventtypeQuery = "SELECT Id FROM eventtypes WHERE EventType = '".$etype."'";
		$etypeId = $Global->SelectQuery($eventtypeQuery); //Returns the Event Type Id if exist
		
		if(count($etypeId) > 0)
		{
			$msgEventTypeExist = 'Event Type Name already exist!';
		}
		else
		{
			try
			{
				$objEventType = new cEventTypes(0, $etype, $DispOrder);
				
				if($objEventType->Save())
				{
					header("location:editeventtype.php");
				}
			}
			catch (Exception $Ex)
			{
				echo $Ex->getMessage();
			}	
		}
	}//ENDS Add New Event Type
    $CategoriesQuery = "SELECT Id, Category FROM categories";
	      $CategoriesRES = $Global->SelectQuery($CategoriesQuery);
	include 'templates/addeventtype.tpl.php';	
?>