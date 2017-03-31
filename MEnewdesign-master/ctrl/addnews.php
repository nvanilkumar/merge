<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Add / Edit of News
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 25th Aug 2009
******************************************************************************************************************************************/
	
	session_start();
	
	include 'loginchk.php';
	
	include_once("MT/cGlobal.php");
	
	$Global = new cGlobal();
		
	if($_REQUEST['newsid'] != '')
	{
		$Id = $_REQUEST['newsid'];
		
		if($_POST['save'] == 'SUBMIT')
		{
			include_once("MT/cNews.php");
			$Title = trim($_POST['txttitle']);
			$ShortDesc = $_POST['shortdesc'];
			$Content = $_POST['news_content'];
			
			try
			{	
				$objNews = new cNews(0, $Title, $ShortDesc, $Content, $Dt, $Active);
				
				if($objNews->Save())
				{
					header("location:news.php");
				}
			}
			catch (Exception $Ex)
			{
				echo $Ex->getMessage();
			}
		}
		else
		{
			//Query For News Details
			$NewsQuery = "SELECT * FROM News";
			$NewsList = $Global->SelectQuery($NewsQuery);
			for($i = 0; $i < count($NewsList); $i++)
			{
				$title = $NewsList[$i]['Title'];
				$teaser = $NewsList[$i]['ShortDesc']; 
				$body = $NewsList[$i]['News'];
			}
		}
	}

	include 'templates/addnews.tpl.php';	
?>