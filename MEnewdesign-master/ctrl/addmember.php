<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Display the all Associations Membership Names.
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 24th Aug 2009
 *	2.	Updateed on : 01 Oct 2009 - for WYPWYG commented the strtolower, for loop for ucfirst and implode  
******************************************************************************************************************************************/
	
	session_start();
	include 'loginchk.php';
	include_once("MT/cGlobal.php");
	include_once("MT/cAssociations.php");
	
	$Global = new cGlobal();
	
	$msgAssoMemberExist = ' ';
	
	if($_POST['Submit'] == "Add")
	{
		$member = $_POST['member'];
		$member = trim($member); 
//		$member = strtoupper($member);//strtolower($member);
		
		// MAKE ALL FIRST LETTERS OF EACH WORD CAPITAL
//		$names = explode(" ",$member);
//		foreach($names as $key => $val)
//		{
//			$words[] = ucfirst($val);
//		}
//		$member = implode(" ",$words);

		$member = str_replace("&","AND",$member);
		
		$AssoMemberQuery = "SELECT Id FROM associations WHERE Associations = '".$member."'";
		$Id = $Global->SelectQuery($AssoMemberQuery);

		if(count($Id) > 0)
		{
			$msgAssoMemberExist = 'Association Membership Name already exist!';	
		}
		else
		{
			try
			{	
				$objAssociations = new cAssociations(0, $member);
				
				if($objAssociations->Save())
				{
					header('location:editmember.php');
				}
			}
			catch (Exception $Ex)
			{
				echo $Ex->getMessage();
			}
		}		
	}// END IF Add
	
	include 'templates/addmember.tpl.php';	
?>