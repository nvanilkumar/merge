<?php 
        session_start();
        
        $uid =	$_SESSION['uid'];
	
        include_once("MT/cGlobali.php");
	
        $Global = new cGlobali();
	
        include 'loginchk.php';
	
        $msg = '';
        
	if($_REQUEST['getEveDtls'] == 'Submit' || isset($_REQUEST['EventId'])){
                $EventId=$_REQUEST['EventId'];
                $query="SELECT id FROM event WHERE id=".$EventId." and deleted=1";
                $outputEvent=$Global->SelectQuery($query);
                if(!$outputEvent){
		$OrgQuery = "SELECT e.eventid, e.mobileapi, e.standardapi FROM eventsetting e WHERE e.eventid=".$EventId;
   		$ResOrgQuery = $Global->SelectQuery($OrgQuery);
                    if(!$ResOrgQuery){
                        $msg = '<font color=red>No records found</font>';
                    }
                }
	}        
        
include 'templates/api_event_edit.php';	
?>
