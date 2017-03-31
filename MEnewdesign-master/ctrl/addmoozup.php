<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Add the city against the state.
 *	It checkes the city name against the state already exist or not if it shows the error message.
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 22nd Aug 2009
******************************************************************************************************************************************/
	
	session_start();
	
	include_once("MT/cGlobal.php");
	include_once("MT/cCities.php");
	
	$Global = new cGlobal();
        include 'loginchk.php';
	
	$msgStateCityExist = '';
$Description='<p><span style="font-family: arial, helvetica, sans-serif; font-size: small;">Dear Firstname,</span></p>
<p><span style="font-family: arial, helvetica, sans-serif; font-size: small;">&nbsp;</span><br /><span style="font-family: arial, helvetica, sans-serif; font-size: small;">Welcome to Moozup, that allows you to network with other participants of the event.</span><br /><span style="font-family: arial, helvetica, sans-serif; font-size: small;">Following are the details for you to connect to other delegates of Title.</span></p>
<p><br /><span style="font-family: arial, helvetica, sans-serif; font-size: small;">UserName</span></p>
<p><span style="font-family: arial, helvetica, sans-serif; font-size: small;">PassWord</span></p>
<p><span style="font-family: arial, helvetica, sans-serif; font-size: small;">Link to login http://www.moozup.com/&nbsp;</span></p>
<p><span style="font-family: arial, helvetica, sans-serif; font-size: small;"><br /></span></p>
<p><span style="font-family: arial, helvetica, sans-serif; font-size: small;">Thank You,<br />MeraEvents Team</span></p>';
	
	if($_POST['Submit'] == "Add")
	{
		$MEventId = $_POST['MEventId'];
		
		$JEventId = $_POST['JEventId'];
              $DPassword = $_POST['DPassword'];
		$EmailTxt = stripslashes($_POST['Description']);
              $ParTy=$_POST['ParTy'];
              $SendMail=$_POST['SendMail'];
              
		
		
		$JujamQuery = "SELECT Id FROM jujama WHERE MEventId = '".$MEventId."'";
		$JujamId = $Global->SelectQuery($JujamQuery);

		if(count($JujamId) > 0)
		{
			$msgStateCityExist = 'Moozup Id already exist!';
		}
		else
		{
			try
			{	
			$sqlIns="insert into jujama(MEventId,JEventId,DPassword,ParTy,SendMail,EmailTxt) values('".$MEventId."','".$JEventId."','".$DPassword."','".$ParTy."','".$SendMail."','".$EmailTxt."')";
		       $InsId = $Global->ExecuteQuery($sqlIns);
				
				if($InsId)
				{
					header("location:editmoozup.php");
				}
			}
			catch (Exception $Ex)
			{
				echo $Ex->getMessage();
			}
		}	
	
	}// END IF Add
		
	//Query For State List
        // commenting, becaus enot being used anywhere pH
	$JQuery = "SELECT * FROM jujama";
	//$JList = $Global->SelectQuery($JQuery);

	include 'templates/addmoozup.tpl.php';
?>