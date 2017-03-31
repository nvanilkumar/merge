<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Edit the Associations Membership Names.
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
		
	if($_POST['Submit'] == "Update")
	{
		$Id = $_POST['id'];
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
		try
		{
			$objAssociations = new cAssociations($Id, $member);

			$Id = $objAssociations->Save();
	
			if($Id > 0)
			{
				header("location:editmember.php");
			}
		}
		catch (Exception $Ex)
		{
			echo $Ex->getMessage();
		}
	}// END IF update
	else
	{
		$Id = $_GET['id'];
		
		//Query For Associated Membership
		//$AssocMemberQuery = "SELECT * FROM associations WHERE Id='".$Id."'";
                $AssocMemberQuery = "SELECT `Associations` FROM associations WHERE Id='".$Id."'";
		$AssocMemberList = $Global->SelectQuery($AssocMemberQuery);
		
		for($i = 0; $i <  count($AssocMemberList); $i++)
		{
			$AssoMembershipName = $AssocMemberList[$i]['Associations'];
		}
	}
	
	include 'templates/member_edit.tpl.php';	
?>
