<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Update Events of the Month
 *	
 *	Created / Updated on:
 *		It takes the values EventId and newStatus. Using these fields update the Event Id which reflects on front end.
 *		The newStatus fields use to update IsFamous value.
******************************************************************************************************************************************/

//	include_once("MT/cGlobal.php");
//	include_once("MT/cEvents.php");	
	
//	$Global = new cGlobal();


include('includes/commondbdetails.php');

	mysql_connect($DBServerName,$DBUserName,$DBPassword) or die("cannot connect to db : ".mysql_error());
	mysql_select_db("meraeven_dmeraevent") or die("DB not found :".mysql_error());

	$EventId = $_GET['EventId'];
	$IsFamous = $_GET['newStatus'];
	
	$update_query="UPDATE events SET IsFamous='".$IsFamous."' WHERE Id='".$EventId."'";
	mysql_query($update_query);
	
	$newIsFamousStatus = 0;
?>	
	<td align="left" valign="middle" class="helpBod" id="EventsOfMonth">
			<input type="checkbox" name="chkIsFamous" <?php if($IsFamous == 1) { $newIsFamousStatus = 0; ?> checked="checked" value="1" <?php } else { $newIsFamousStatus = 1; ?> value="0" <?php } ?> onclick="updateFamousEvent.php?EventId=<?php echo $EventId; ?>&newStatus=<?=$newIsFamousStatus?>"	/>
	</td>