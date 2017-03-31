<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	It displays the city list as per the states list.
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 22nd Aug 2009
******************************************************************************************************************************************/
	session_start();
		
	include_once("MT/cGlobali.php");
	include 'loginchk.php';
	include 'includes/common_functions.php';
	
	$commonFunctions=new functions();

	$Global = new cGlobali();
	if (!$commonFunctions->isSuperAdmin() && !$commonFunctions->isSupportTeam())
            header("Location: admin.php");
	//updating attendee customfields data
	if(isset($_POST['AttendeeIdUp']))
	{
		//print_r($_POST); exit;
		$attId=$_POST['AttendeeIdUp'];
		$esid=$_POST['esID'];
		$eventid=$_POST['eventid'];
		
//		$sqlCF="select `EventCustomFieldName`,`EventCustomFieldType`,`Id` from `eventcustomfields` where `EventId`='".$eventid."' and `EventCustomFieldType`!=7";
//		$dataCF=$Global->SelectQuery($sqlCF);
//		
//		foreach($dataCF as $cfk=>$cfv)
//		{
//			$EventCustomFieldsId = $cfv['Id'];
//			$EventCustomFieldName = str_replace(" ","_",preg_replace("/[^A-Za-z0-9\s\s+]/","",$cfv['EventCustomFieldName']));
//			
//			if($cfv['EventCustomFieldType']==3 || $cfv['EventCustomFieldType']==4 )
//			{
//				$chk=$_POST[$EventCustomFieldName];
//				$EventSignupFieldValue=" ";
//				for($x=0; $x<count($chk); $x++){
//						$EventSignupFieldValue.=$chk[$x]. " ";
//				}
//			}
//			else
//			{
//				if(strcmp($EventCustomFieldName,'Designation')==0)
//				{
//					$EventSignupFieldValue =  $Global->GetSingleFieldValue("SELECT Designation from `Designations` WHERE Id = '".$_POST[$EventCustomFieldName]."'");           		
//				}
//				elseif(strcmp($EventCustomFieldName,'State')==0)
//				{
//					$EventSignupFieldValue =  $Global->GetSingleFieldValue("SELECT State from `States` WHERE Id = '".$_POST[$EventCustomFieldName]."'");           		
//				}
//				elseif(strcmp($EventCustomFieldName,'City'.$i)==0)
//				{
//					$EventSignupFieldValue =  $Global->GetSingleFieldValue("SELECT City from `Cities` WHERE Id = '".$_POST[$EventCustomFieldName]."'");           		
//				}
//				else
//				{
//					$EventSignupFieldValue = $_POST[$EventCustomFieldName];
//				}
//			}
//			
//			
//			//for updating eventcustomfield value
//			$sqlAttCustUp="update `eventsignupcustomfields` set `EventSignupFieldValue`='".$EventSignupFieldValue."' where `attendeeId`='".$attId."' and `EventSignupId`='".$esid."' and `EventCustomFieldsId`='".$EventCustomFieldsId."'";
//			$Global->ExecuteQuery($sqlAttCustUp);
//			//for updating eventcustomfield value
//			//for updating Attendee table record. (dont forget to update Attendees table)
//			if($EventCustomFieldName=='Full_Name' || $EventCustomFieldName=='Full Name')
//			{
//				$sqlAttUp="update `Attendees` set `Name`='".$EventSignupFieldValue."' where `Id`='".$attId."'";
//			}
//			elseif($EventCustomFieldName=='Email_Id')
//			{
//				$sqlAttUp="update `Attendees` set `Email`='".$EventSignupFieldValue."' where `Id`='".$attId."'";
//			}
//			elseif($EventCustomFieldName=='Company_Name')
//			{
//				$sqlAttUp="update `Attendees` set `Company`='".$EventSignupFieldValue."' where `Id`='".$attId."'";
//			}
//			
//			$Global->ExecuteQuery($sqlAttUp);
//			//for updating Attendee table record
//			
//		}
                
               $sqlCF="select ad.id as Id,ad.customfieldid,ad.value,cd.fieldname as EventCustomFieldName,cd.fieldtype as EventCustomFieldType".
                        " from attendeedetail as ad".
                        " inner join customfield as cd on cd.id=ad.customfieldid".
                        " where ad.attendeeid=".$attId;
                $ResCust=$Global->SelectQuery($sqlCF);
                for ($cus = 0; $cus < count($ResCust); $cus++) 
		{
                    echo $sqlupdate="update attendeedetail set value='".$_REQUEST[$ResCust[$cus]['Id']."-".$ResCust[$cus]['customfieldid']]."' "
                             . "where id=".$ResCust[$cus]['Id']; 
                    $res=$Global->ExecuteQuery($sqlupdate);
		}
		
		
		$_SESSION['attUpSucc']=$esid;
		header("Location: editeventsignup.php?evtsignupid=".$esid);
		exit;
	}
	
	
	$data=array();
	$esid=NULL;
        
	if(isset($_GET['aid']))
	{
		$aid=$_GET['aid'];
		$esid=$_GET['esid'];
		$eventid=$_GET['eventid'];
		
		//$sqlCF="select `EventCustomFieldName`,`EventCustomFieldType`,`Id` from `eventcustomfields` where `EventId`='".$eventid."' and `EventCustomFieldType`!=7";
		 $sqlCF="select ad.id as Id,ad.customfieldid,ad.value,cd.fieldname as EventCustomFieldName,cd.fieldtype as EventCustomFieldType".
                        " from attendeedetail as ad".
                        " inner join customfield as cd on cd.id=ad.customfieldid".
                        " where ad.attendeeid=".$aid;
                $dataCF=$Global->SelectQuery($sqlCF);

	}


	include 'templates/editAttendee.tpl.php';
?>