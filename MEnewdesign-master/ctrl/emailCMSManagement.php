<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Manage EMail CMS Management
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is created on 05th Oct 2009
******************************************************************************************************************************************/
	
	session_start();
		
	include 'loginchk.php';

	$uid =	$_SESSION['uid'];
	
	include_once("MT/cGlobali.php");
	include_once("MT/cEMailMsgs.php");

	$Global = new cGlobali();

	$msgActionStatus = '';
	
	//Delete the banner
	if(isset($_REQUEST['delete'])	)
	{
		$Id = $_REQUEST['delete'];
		
		try
		{	
			$delQuery = "UPDATE messagetemplate set deleted = 1, modifiedby = ".$_SESSION['uid']." where id =".$Id ;
			$delres = $Global->ExecuteQuery($delQuery);
			if($delres > 0)
			{	
				//delete successful statement
				$msgActionStatus = "EMail Message Content Deleted Successfully.";
			}
		}
		catch (Exception $Ex)
		{
			echo $Ex->getMessage();
		}
	}
	
	//Add new EMail Content
	if(isset($_POST['Submit']) == 'Submit' && empty($_POST['Id']))
	{
		$Msg = addslashes(trim($_POST['txtMsg']));
		$MsgType = $_POST['txtMsgType'];
		$SendThruEMailId = $_POST['txtSendThruEMailId'];
		
		//$objEMailMsgs = new cEMailMsgs(0, $Msg, $MsgType, $SendThruEMailId);
		
		 $insQuery = "INSERT into messagetemplate (template,type,mode,fromemailid,createdby) values ('".$Msg."','".$MsgType."','email','".$SendThruEMailId."','".$_SESSION['uid']."') ";
			$insres = $Global->ExecuteQuery($insQuery);
		
		if(insres > 0)
		{
		
			$msgActionStatus = "New EMail Message Content Added Successfully.";
		}
	}
	
	//Update EMail Msgs Information
	if(isset($_POST['Submit']) == 'Submit' && !empty($_POST['Id']))
	{
		$Id = $_REQUEST['Id'];
		$Msg = trim($_POST['txtMsg']);
		$MsgType = trim($_POST['txtMsgType']);
		$SendThruEMailId = trim($_POST['txtSendThruEMailId']);
		
		//$UpEMailMsgs = new cEMailMsgs($Id, $Msg, $MsgType, $SendThruEMailId);
					
		 $updateQuery = "UPDATE messagetemplate set template = '".$Msg."',type='".$MsgType."',fromemailid='".$SendThruEMailId."', modifiedby = ".$_SESSION['uid']." where id =".$Id ;
			$updateres = $Global->ExecuteQuery($updateQuery);
					
		if($updateres > 0)
		{
			$msgActionStatus = "EMail Message Content Updated Successfully.";
		}
	}
	else if(isset($_REQUEST['Edit'])!='')//Get the content of Id
	{
		$Id = $_REQUEST['Edit'];
		
		$selEMailMsgs = "SELECT * FROM messagetemplate WHERE id ='".$Id."' and mode = 'email'"; //using 3/4 -pH
		$EditEMailMsgs = $Global->SelectQuery($selEMailMsgs);
		
		$txtMsg = stripslashes($EditEMailMsgs[0]['template']);
		$txtMsgType = $EditEMailMsgs[0]['type'];
		$txtSendThruEMailId = $EditEMailMsgs[0]['fromemailid'];
	}
	
	//Query For All EMail Msgs List
	$EMailMsgsQuery = "SELECT * FROM messagetemplate where mode = 'email' and deleted = 0"; //using 3/4 -pH
	$EMailMsgsList = $Global->SelectQuery($EMailMsgsQuery);
	
	include 'templates/emailCMSManagement.tpl.php';
?>