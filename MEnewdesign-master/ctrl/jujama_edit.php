<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Edit the city name aginst the state
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 22nd Aug 2009
******************************************************************************************************************************************/

	session_start();
	
	include_once("MT/cGlobal.php");
	include_once("MT/cCities.php");
	 include 'loginchk.php';

	$Global = new cGlobal();

	if($_POST['Submit'] == "Update")
	{
		$Id = $_POST['Id'];
		$MEventId = $_POST['MEventId'];
		
		$JEventId = $_POST['JEventId'];
              $DPassword = $_POST['DPassword'];
              $ParTy=$_POST['ParTy'];
              $SendMail=$_POST['SendMail'];
              
          	$EmailTxt = $_POST['Description'];
		try
		{
			$UPsql="update jujama set MEventId='".$MEventId."',JEventId='".$JEventId."',DPassword='".$DPassword."',ParTy='".$ParTy."',SendMail='".$SendMail."',EmailTxt='".$EmailTxt."'  where Id=".$Id;
				$done=$Global->ExecuteQuery($UPsql);
				if($done > 0)
			{
				header("location:editmoozup.php");
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
		
		//Query For City Details
		$JujQuery = "SELECT * FROM jujama WHERE Id = '".$Id."'"; //using 6/7 -pH
		$JujList = $Global->SelectQuery($JujQuery);
		
	
		
		for($i = 0; $i <  count($JujList); $i++)
		{
			$MEventId = $JujList[$i]['MEventId'];
			$JEventId = $JujList[$i]['JEventId'];
                     $DPassword = $JujList[$i]['DPassword'];
                     $ParTy = $JujList[$i]['ParTy'];
                     $SendMail = $JujList[$i]['SendMail'];
			$EmailTxt = stripslashes($JujList[$i]['EmailTxt']);
		}
	}
	
	include 'templates/jujama_edit.tpl.php';
?>