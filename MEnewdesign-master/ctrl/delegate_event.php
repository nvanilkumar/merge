<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	MIS Report - Delegate Report Generation - Display the Event/s Report. Generate the Event/s Report in CSV File.
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 26th Aug 2009
******************************************************************************************************************************************/
	
	session_start();
	include 'loginchk.php';
	
	include_once("MT/cGlobal.php");

	$Global = new cGlobal();
	
	$nid=0;
	if($_REQUEST['show'] == 'Generate Report')
	{
		$nid = $_REQUEST['event'];
	}
	if($nid > 0)
	{ 
		$EventQuery = "SELECT en.EventName, dg.DisplayName, c.City, u.Company FROM eventnames AS en, eventnamepref AS enp, user AS u, delegate AS dg, events AS e, Cities AS c WHERE en.Id='".$nid."' AND en.Id=enp.EventName AND enp.UserId=u.Id AND u.Id=dg.UserId AND e.UserId=u.Id AND e.CityId=c.Id ORDER BY enp.EventName ASC";
		$DelegateEvent = $Global->SelectQuery($EventQuery);
		
		if($_POST['Submit'] == "Export As CSV")
		{				
			$out = 'Delegate Name,City,Company';
			$out .="\n";
			$columns = 3;
			for($i = 0; $i < count($DelegateEvent); $i++)
			{
				$out .='"'.$DelegateEvent[$i]['DisplayName'].'",';
				$out .='"'.$DelegateEvent[$i]['City'].'",';
				$out .='"'.$DelegateEvent[$i]['Company'].'",';
				$out .="\n";
			}
			// Output to browser with appropriate mime type, you choose ;)
			header("Content-type: text/x-csv");
			//header("Content-type: text/csv");
			//header("Content-type: application/csv");
			header("Content-Disposition: attachment; filename=delegate_event.csv");
			echo $out;
			exit;
		}
	}
	else
	{
		$EventQuery = "SELECT en.EventName, dg.DisplayName, c.City, u.Company FROM eventnames AS en, eventnamepref AS enp, user AS u, delegate AS dg, events AS e, Cities AS c WHERE enp.EventName=en.Id AND enp.UserId=u.Id AND u.Id=dg.UserId AND e.UserId=u.Id AND e.CityId=c.Id ORDER BY enp.EventName ASC";
		$AllEventsDelegates = $Global->SelectQuery($EventQuery);
		
		if($_POST['Save'] == "Export As CSV")
		{
			$out = 'Event Name,Delegate Name,City,Company';
			$out .="\n";
			$columns = 4;
			for ($i = 0; $i < count($AllEventsDelegates); $i++)
			{
				$out .='"'.$AllEventsDelegates[$i]['EventName'].'",';
				$out .='"'.$AllEventsDelegates[$i]['DisplayName'].'",';
				$out .='"'.$AllEventsDelegates[$i]['City'].'",';
				$out .='"'.$AllEventsDelegates[$i]['Company'].'",';
				$out .="\n";
			}
			// Output to browser with appropriate mime type, you choose ;)
			header("Content-type: text/x-csv");
			//header("Content-type: text/csv");
			//header("Content-type: application/csv");
			header("Content-Disposition: attachment; filename=delegate_event.csv");
			echo $out;
			exit;
		}
	}	
			

//Query For Event Names List
//	$EventQuery = "SELECT Id, EventName FROM eventnames";
//	$EventList = $Global->SelectQuery($EventQuery);
	
	include 'templates/delegate_event.tpl.php';		
?>	  