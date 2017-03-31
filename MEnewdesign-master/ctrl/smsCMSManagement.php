<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Manage SMS CMS Management
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is created on 05th Oct 2009
******************************************************************************************************************************************/
	
	session_start();
		
	include 'loginchk.php';

	$uid =	$_SESSION['uid'];
	
	include_once("MT/cGlobali.php");
	//include_once("MT/cSMSMsgs.php");

	$Global = new cGlobali();

	$msgActionStatus = '';

	//Delete the SMS
	if(isset($_REQUEST['delete']))
	{
		$Id = $_REQUEST['delete'];
		
		try
		{	
			//$objSMSMsgs = new cSMSMsgs($Id);
			$delQuery = "UPDATE messagetemplate set deleted = 1, modifiedby = ".$_SESSION['uid']." where id =".$Id ;
			$delres = $Global->ExecuteQuery($delQuery);
			if($delres > 0)
			{	
				//delete successful statement
				$msgActionStatus = "SMS Message Content Deleted Successfully.";
			}
		}
		catch (Exception $Ex)
		{
			echo $Ex->getMessage();
		}
	}
	
	//Add new SMS Content
	if(isset($_POST['Submit']) == 'Submit' && empty($_POST['Id']))
	{
		$Msg = trim($_POST['txtMsg']);
		$MsgType = trim($_POST['txtMsgType']);
		
		//$objSMSMsgs = new cSMSMsgs(0, $Msg, $MsgType);
		 $insQuery = "INSERT into messagetemplate (template,type,mode,createdby) values ('".$Msg."','".$MsgType."','sms','".$_SESSION['uid']."') ";
			$insres = $Global->ExecuteQuery($insQuery);
		
		if(insres > 0)
		{
			$msgActionStatus = "New SMS Message Content Added Successfully.";
		}
		
	}
	
	//Update SMS Msgs Information
	if(isset($_POST['Submit']) == 'Submit' && !empty($_POST['Id']))
	{
		 $Id = $_REQUEST['Id'];
		$Msg = trim($_POST['txtMsg']);
		$MsgType = trim($_POST['txtMsgType']);
		
		//$UpSMSMsgs = new cSMSMsgs($Id, $Msg, $MsgType);
		 $updateQuery = "UPDATE messagetemplate set template = '".$Msg."',type='".$MsgType."', modifiedby = ".$_SESSION['uid']." where id =".$Id ;
			$updateres = $Global->ExecuteQuery($updateQuery);
					
		if($updateres > 0)
		{
			$msgActionStatus = "SMS Message Content Updated Successfully.";
		}
	}
	else if(isset($_REQUEST['Edit'])!='')//Get the content of Id
	{
		$Id = $_REQUEST['Edit'];

//		$selSMSMsgs = "SELECT * FROM SMSMsgs WHERE Id ='".$Id."'";
        $selSMSMsgs = "SELECT `template`,`type` FROM messagetemplate WHERE id ='".$Id."' and mode = 'sms'";
		$EditSMSMsg = $Global->SelectQuery($selSMSMsgs);
		
		$txtMsg = stripslashes($EditSMSMsg[0]['template']);
		$txtMsgType = $EditSMSMsg[0]['type'];
	}
	
	//Query For All SMS Msgs List
	//$SMSMsgsQuery = "SELECT * FROM SMSMsgs";
        $SMSMsgsQuery = "SELECT `id`,`type` FROM messagetemplate where mode = 'sms' and deleted = 0";
	$SMSMsgsList = $Global->SelectQuery($SMSMsgsQuery);
	
	include 'templates/smsCMSManagement.tpl.php';
?>