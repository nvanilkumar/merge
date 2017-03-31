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
	include 'loginchk.php';
	
	include_once("MT/cGlobal.php");
	include_once("MT/cTargetAud.php");
	
	$Global = new cGlobal();

	if($_POST['Submit'] == "Update")
	{
		$TargetAud = $_POST['TargetAud'];
		$TargetAud = trim($TargetAud); 
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
			$objEventType = new cTargetAud($Id, $TargetAud, $DispOrder);
			$Id = $objEventType->Save();
	
		//	if($Id > 0)
		//	{
				header("location:edittargetaud.php");
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
//		$EventTypeQuery = "SELECT * FROM targetaud WHERE Id = '".$Id."'";
                $EventTypeQuery = "SELECT `TargetAud`,`DispOrder`,`Id` FROM targetaud WHERE Id = '".$Id."'";
		$EventTypeList = $Global->SelectQuery($EventTypeQuery);
		
		for($i = 0; $i < count($EventTypeList); $i++)
		{
			$TargetAud = $EventTypeList[$i]['TargetAud'];
                     $DispOrder= $EventTypeList[$i]['DispOrder'];
			$Id = $EventTypeList[$i]['Id'];
		}
	}// END Get Function Details
          

	include 'templates/targetaud_edit.tpl.php';	
?>