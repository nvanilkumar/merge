<?php
	session_start();
	
	include 'loginchk.php';
	
	include_once("MT/cGlobal.php");
	
	$Global = new cGlobal();		
	if($_REQUEST['action'] == "delete")
	{
		include_once("MT/cMenuContent.php");

		$Id = $_REQUEST['Id'];
		
		try
		{	
			$MenuContent = new cMenuContent($Id);//, $Title, $MenuDesc, $ParentMenuId, $Content
			$Id = $MenuContent->Delete();
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
	
	////////////////////////////////Query For Menu Contents////////////////////////////////
//	$MenuQuery = "SELECT * FROM menus";
        $MenuQuery = "SELECT `Title`,`ParentMenuId`,`Id` FROM menus";
	$MenuList = $Global->SelectQuery($MenuQuery);
	////////////////////////////////

	include 'templates/content.tpl.php';	
?>	   