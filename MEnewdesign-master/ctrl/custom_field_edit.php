<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Display Events of the Month
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 26th Aug 2009
 *	2.	Added the new filed IsFamous in db which is used to display the Famous Events on the front end.
 * 		The check box property checked shows the event is famous, visible on front end and vice versa.
******************************************************************************************************************************************/
	
	session_start();
	$uid =	$_SESSION['uid'];
	
	include 'loginchk.php';
	
	include_once("MT/cGlobali.php");
	
	$Global = new cGlobali();
	
$EventSignupId=$_REQUEST['EventSignupId'];
$eventId=$_REQUEST['EventId'];
$regno=$_REQUEST['regno'];
		
	//print_r($_REQUEST);
		//echo "-----".$regno."value";


		if($_REQUEST['submit']=="Update")
	{
	   	//print_r($_REQUEST); exit;
		
		$sqlCustval="select ad.attendeeid,ad.customfieldid,ad.value from attendeedetail ad
		 Inner Join attendee a on ad.attendeeid = a.id 
		where a.eventsignupid='".$EventSignupId."'";
		$ResCust = $Global->SelectQuery($sqlCustval);
		
		
		for ($cus = 0; $cus < count($ResCust); $cus++) 
		{
		 $sqlupdate="update attendeedetail set value='".$_REQUEST[$ResCust[$cus]['customfieldid']."-".$ResCust[$cus]['attendeeid']]."' where attendeeid='".$ResCust[$cus]['attendeeid']."' and customfieldid=".$ResCust[$cus]['customfieldid']; 
		$res=$Global->ExecuteQuery($sqlupdate);
		}
             if($res){
             ?>
             <script>
            window.location="custom_field_event.php?EventId=<?=$eventId;?>&regno=<?=$regno;?>";
            </script>
		<?php }
		
	}
	
if($_REQUEST['Cancel']=="Cancel")
{
 ?>
             <script>
            window.location="custom_field_event.php?EventId=<?=$eventId;?>&regno=<?=$regno;?>";
            </script>
		<?php 
}
              $sqlCustval1="select ad.attendeeid,ad.customfieldid,ad.value from attendeedetail ad
		 Inner Join attendee a on ad.attendeeid = a.id 
		where a.eventsignupid='".$EventSignupId."'";
		$ResCustval1 = $Global->SelectQuery($sqlCustval1);
		
		
		$totalCustFileds=$Global->GetSingleFieldValue("select count(ad.attendeeid) from attendeedetail ad
		 Inner Join attendee a on ad.attendeeid = a.id 
		where a.eventsignupid='".$EventSignupId."' group by ad.attendeeid");
		              
	
	include 'templates/custom_field_edit.tpl.php';	
?>
