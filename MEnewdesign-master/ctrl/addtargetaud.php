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
		$TargetAud = $_POST['TargetAud'];
		$TargetAud = trim($TargetAud); 
		$TargetAud = strtolower($TargetAud);
		$DispOrder= $_POST['DispOrder'];
		
		// MAKE ALL FIRST LETTERS OF EACH WORD CAPITAL
		$names = explode(" ",$TargetAud);
		foreach($names as $key => $val)
		{
			$words[] = ucfirst($val);
		}
		$TargetAud = implode(" ",$words);
		
		include_once("MT/cTargetAud.php");
		
		$eventtypeQuery = "SELECT Id FROM targetaud WHERE TargetAud = '".$TargetAud."'";
		$etypeId = $Global->SelectQuery($eventtypeQuery); //Returns the Event Type Id if exist
		
		if(count($etypeId) > 0)
		{
			$msgEventTypeExist = 'Target Audience Name already exist!';
		}
		else
		{
			try
			{
				$objEventType = new cTargetAud(0, $TargetAud, $DispOrder);
				
				if($objEventType->Save())
				{
					header("location:edittargetaud.php");
				}
			}
			catch (Exception $Ex)
			{
				echo $Ex->getMessage();
			}	
		}
	}//ENDS Add New Event Type
    
	include 'templates/addtargetaud.tpl.php';	
?>