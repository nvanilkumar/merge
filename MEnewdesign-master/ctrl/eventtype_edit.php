<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Edit the Event Type name
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 24th Aug 2009
 *	2.	Updateed on : 01 Oct 2009 - for WYPWYG commented the strtolower, for loop for ucfirst and implode 
******************************************************************************************************************************************/

	session_start();
	
	include_once("MT/cGlobal.php");
	include_once("MT/cEventTypes.php");
	 include 'loginchk.php';

	$Global = new cGlobal();

	if($_POST['Submit'] == "Update")
	{
		$eventtype = $_POST['txtEventTypes'];
		$eventtype = trim($eventtype); 
//		$eventtype = strtolower($eventtype);
               $DispOrder=$_POST['DispOrder'];
		
		// MAKE ALL FIRST LETTERS OF EACH WORD CAPITAL
//		$names = explode(" ",$eventtype);
//		foreach($names as $key => $val)
//		{
//			$words[] = ucfirst($val);
//		}
//		$eventtype = implode(" ",$words);
		
	 	$Id = $_POST['type_id'];

		try
		{
			$objEventType = new cEventTypes($Id, $eventtype, $DispOrder);
			$Id = $objEventType->Save();
	
		//	if($Id > 0)
		//	{
				header("location:editeventtype.php");
		//	}
		}
		catch (Exception $Ex)
		{
			echo $Ex->getMessage();
		}
	}
	else
	{
		$Id = $_GET['id'];
		
		//Query For Event Types Details
		$EventTypeQuery = "SELECT * FROM eventtypes WHERE Id = '".$Id."'";
		$EventTypeList = $Global->SelectQuery($EventTypeQuery);
		
		for($i = 0; $i < count($EventTypeList); $i++)
		{
			$eventtype = $EventTypeList[$i]['EventType'];
                     $DispOrder= $EventTypeList[$i]['DispOrder'];
			$Id = $EventTypeList[$i]['Id'];
		}
	}// END Get Function Details
          $CategoriesQuery = "SELECT Id, Category FROM categories";
          $CategoriesRES = $Global->SelectQuery($CategoriesQuery);

	include 'templates/eventtype_edit.tpl.php';	
?>