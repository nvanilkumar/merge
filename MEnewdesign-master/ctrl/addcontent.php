<?php
	session_start();
	
	include_once("MT/cGlobal.php");
	include_once("MT/cMenuContent.php");
	$Global = new cGlobal();	
        include 'loginchk.php';	
		
	//check if for edit the content
	if($_REQUEST['contentid']!='')
	{
		$Id = $_REQUEST['contentid'];
		
//		$MenuContent = new cMenuContent($Id);//, $Title, $MenuDesc, $ParentMenuId, $Content
//		$EditMenu = $MenuContent->Load();
		$MenuContentQuery = "SELECT * FROM menus WHERE Id = '".$Id."'"; //using 4/5 -pH
		$EditMenuContent = $Global->SelectQuery($MenuContentQuery);
	}

	if($_REQUEST['save'] == 'SUBMIT')
	{
		$menu_title = trim($_REQUEST['menu_title']);
		$shortdesc = trim($_REQUEST['menu_desc']);
		$ParentMenuId = ($_REQUEST['menu_parent'])?$_REQUEST['menu_parent']:'0';
		$longdesc = $_REQUEST['content'];
		
		if($_REQUEST['hidn_content']!='')//update the existing menu content
		{
			$Id = $_REQUEST['hidn_content'];

			$MenuContent = new cMenuContent($Id, $menu_title, $shortdesc, $ParentMenuId, $longdesc);
			$Id = $MenuContent->Save();
			if($Id > 0)
			{
				header("location:content.php");
			}
		}
		else	//add the new menu content 
		{			
			try
			{	
				//$Events = new cMenuContent(0, $Title, $MenuDesc, $ParentMenuId, $Content);
				$MenuContent = new cMenuContent(0, $menu_title, $shortdesc, $ParentMenuId, $longdesc);
				
				if($MenuContent->Save())
				{
					header("location:content.php");
				}
			}
			catch (Exception $Ex)
			{
				echo $Ex->getMessage();
			}
		}
		//redirect to menu cotent management page
		header('Location: content.php');
	}

	//$PMenuQuery = "SELECT * FROM menus WHERE ParentMenuId = '0'";
        $PMenuQuery = "SELECT `Id`,`Title` FROM menus WHERE ParentMenuId = '0'";
	$ParentMenu = $Global->SelectQuery($PMenuQuery);

	include 'templates/addcontent.tpl.php';
?>