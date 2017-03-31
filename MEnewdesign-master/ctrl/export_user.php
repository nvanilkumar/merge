<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Export User List - By Event, By City, By Organizer.
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 26th Aug 2009
******************************************************************************************************************************************/
	
	session_start();
	include 'loginchk.php';
	
	include_once("MT/cGlobal.php");

	$Global = new cGlobal();
	
	//$flag = 0;
	//$e = 0;
	//$c= 0;
	if($_POST['Submit'] == "Export To CSV")
	{
		$event = $_POST['event'];
		$city = $_POST['city'];
		$org = $_POST['organiser'];
		
		$OrganizerQuery = "SELECT dg.DisplayName, org.CEMail, c.City, en.EventName, u.FirstName, u.LastName, u.Email FROM delegate AS dg, organizer AS org, user AS u, Cities AS c, eventnames AS en, eventnamepref AS enp WHERE u.CityId=c.Id AND u.Id=dg.UserId AND enp.UserId=u.Id AND enp.EventName=en.Id AND org.UserId=enp.Id";

		if($event != '0')
		{
			$OrganizerQuery.= " AND en.Id = ".$event;
		}	
		
		if($city != '0')
		{
			$OrganizerQuery.= " AND c.Id = '".$city."' ";
		}
		
		if($org != '0')
		{
			$OrganizerQuery.= " AND org.UserId = '".$org."' ";
		}
		
		$OrganizerQuery .= " ORDER BY u.FirstName, u.LastName ASC";
		
		///// GENERATE CSV	
		$ExportUser = $Global->SelectQuery($OrganizerQuery);
		
		$out = 'Delegate Name,Organizer Email,Deligate City,Event Title,Organiser Name,Deligate Email';
		$out .="\n";
		$columns = 6;
		for ($i = 0; $i < count($ExportUser); $i++) 
		{
			$out .='"'.$ExportUser[$i]['DisplayName'].'",';
			$out .='"'.$ExportUser[$i]['CEMail'].'",';
			$out .='"'.$ExportUser[$i]['City'].'",';
			$out .='"'.$ExportUser[$i]['EventName'].'",';
			$out .='"'.$ExportUser[$i]['FirstName'].' '.$ExportUser[$i]['LastName'].'",';
			$out .='"'.$ExportUser[$i]['Email'].'",';
			$out .="\n";
		}
		// Output to browser with appropriate mime type, you choose ;)
		header("Content-type: text/x-csv");
		//header("Content-type: text/csv");
		//header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=user_export.csv");
		echo $out;
		exit;
	
	} // End Of IF EXPORT


	//Query For Event List
	$EventQuery = "SELECT Id, EventName FROM eventnames";
	$EventList = $Global->SelectQuery($EventQuery);

	//Query For Cities List
	$CityQuery = "SELECT Id, City FROM Cities";
	$CityList = $Global->SelectQuery($CityQuery);
	
	//Query For Organizer List
	$OrganizerQuery = "SELECT org.UserId, u.FirstName, u.LastName FROM user AS u, organizer AS org WHERE org.UserId = u.Id ORDER BY u.FirstName";
	$OrganizerList = $Global->SelectQuery($OrganizerQuery);

	include 'templates/export_user.tpl.php';		
?>