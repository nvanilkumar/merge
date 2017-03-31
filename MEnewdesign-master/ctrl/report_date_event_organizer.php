<?php
	
	include_once("MT/cGlobali.php");
	include 'loginchk.php';
	
	$Global = new cGlobali();
	include("MT/cCtrl.php");
$cCtrl = new cCtrl();	
include_once("includes/common_functions.php");
$common=new functions();

		$MsgCountryExist = '';
		
		//CRETAE THE YESTERDAYS START / END DATE
$yesterdayDate=date ("Y-m-d");
$SDt=$EDt=date ("d-M-Y");
$fromDt = $yesterdayDate.' 00:00:01';
$fromDt =$common->convertTime($fromDt, DEFAULT_TIMEZONE);
$toDt = $yesterdayDate.' 23:59:59';
$toDt =$common->convertTime($toDt, DEFAULT_TIMEZONE);

	if(isset($_POST['submit']))
{
	//print_r($_POST); exit;
	 //dates
	 $SDt = str_replace('/', '-', $_POST['txtSDt']);
	 $EDt= str_replace('/', '-', $_POST['txtEDt']);
	if(strlen($SDt)>0)
	{
		$fromDt = date ("Y-m-d", strtotime($SDt)).' 00:00:01';
                $fromDt =$common->convertTime($fromDt, DEFAULT_TIMEZONE);
	}else{
            $fromDt=date('Y-m-d').' 00:00:01';
        }
	
	if(strlen($EDt)>0)
	{
		$toDt = date ("Y-m-d", strtotime($EDt)).' 23:59:59';
                
	}
	else
	{
		$EDt=date ("d-M-Y", strtotime("now"));
		$toDt = date ("Y-m-d h:i:s", strtotime("now"));
                
	}
        $toDt =$common->convertTime($toDt, DEFAULT_TIMEZONE);
	//dates
	
	
}

  $sqlorg = "select e.id as `EventId`,e.title as `EventName`,e.status,e.ownerid,u.`name` as `Organizer`,u.email as `OrgEmail`,c.`name` as `City`,uc.`name` as `UserCity`,ca.`name` as `Category` from event e 
inner join user u on u.id = e.ownerid 
INNER JOIN city c on c.id=e.cityid 
left outer JOIN city uc on uc.id=u.cityid
INNER JOIN category ca on ca.id=e.categoryid
where e.registrationdate >= '".$fromDt."' AND e.registrationdate <= '".$toDt."'  and e.deleted = 0; 
   ";
$ResOrg = $Global->SelectQuery($sqlorg);


function get_org_status($ownerid,$StartDt){
include_once("MT/cGlobali.php");
	$Global = new cGlobali();	
	 $sqlq="select count(id) as tot from event where registrationdate < '$StartDt' and ownerid = $ownerid and status = 1 and deleted = 0";
$countevents = $Global->GetSingleFieldValue($sqlq);	
if($countevents > 0){
	echo "Existing";
}else{
	echo "New";
}    
}
   
    

include 'templates/report_date_event_organizer.tpl.php';
?>