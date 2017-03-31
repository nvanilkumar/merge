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
$Globali = new cGlobali();
 function getattendeeinfo($attendeid, $commonfieldid)
 {
	 global $Globali; 
	return $Globali->GetSingleFieldValue("select value from attendeedetail where attendeeid=".$Globali->dbconn->real_escape_string($attendeid)." and commonfieldid =".$Globali->dbconn->real_escape_string($commonfieldid));
	 
 }
 function run ($eventId, $lastRegNo )
 {
	// echo "__Run_start______".time();
	 global $Globali;
	 
	 
	 $sqlCust="select id,fieldname from customfield where eventid=".$Globali->dbconn->real_escape_string($eventId)." and `order` !='-1'";
	$ResCust = $Globali->SelectQuery($sqlCust);
	$CustName=" ";
	for ($cus = 0; $cus < count($ResCust); $cus++) 
		{
		$CustName.=$ResCust[$cus]['fieldname'].",";
	
		}
		$CustName=substr($CustName,0,-1);
		
		
		
		//$seat=$Globali->GetSingleFieldValue("select Id from VenueSeats where EventId=".$Globali->dbconn->real_escape_string($eventId));
			
	
		 $out = 'Receipt No.,Signup Date,TicketType,Transaction/Cheque No.,PromotionCode,PaymentStatus,Name,Email,City,State,Company,Phone No.,Amount,Paid';
		$out .=",".$CustName;
		if($seat!=""){
		$out .=",Seatno";
		}
		$out .="\n";
		$columns = 4;
		$ssc=0;
		//$selEvntSUpTktDtls = "SELECT * FROM eventsignupticketdetails WHERE EventSignupId = '".$TransactionRES[$i]['Id']."'";
	
	 
	 
	 
	 
	 $QString="";
	 if(strlen($lastRegNo)>0)
	 {
		 $QString=" and es.id=".$Globali->dbconn->real_escape_string($lastRegNo);
	 }
	 

 
 
  $freesql="select e.registrationtype,ed.extrareportingemails,e.title from event e Inner Join eventdetail ed on e.id = ed.eventid where  e.deleted = 0 and  id=".$Globali->dbconn->real_escape_string($_REQUEST['EventId']);
		$free = $Globali->SelectQuery($freesql);
		$OEmails=$free[0]['extrareportingemails'];

if($free[0]['registrationtype']==1)			
	$isfree=2;
	else
	$isfree=1;
 
  $myQuery = "";
	
	if($isfree==1) //for paid events
	{
	/*  a.name 'Name' ,
	   a.id 'attendeeId',
	   
es.CityId,es.StateId,
       a.field1,
       a.field2,
       a.field3,
       a.field4,
       a.Email 'Email', es.Fees,
       a.company 'Company',
       a.Phone 'Phone', '' Mobile,*/	
		
	  $myQuery.=" select es.id 'EventSIgnupId',                          
       es.signupdate 'Signup Date',
              CASE WHEN paymentgatewayid=2 THEN 'Cash on Delivery'
                   WHEN paymentmodeid=2 and paymenttransactionid='A1' THEN ch.chequenumber
          ELSE es.paymenttransactionid 
          END 'paymenttransactionid', 
       es.discount 'PromotionCode',
	   es.totalamount 'Fees',
	   a.id 'attendeeId',
       CASE es.paymentstatus WHEN 'Verified' THEN 'Successful' 
          ELSE 'Pending' 
          End 'PaymentStatus',
     
       ((es.totalamount/es.quantity) - (es.eventextrachargeamount/es.quantity)) 'Paid' ,
t.name,(estd.amount/estd.ticketquantity) 'Amount'      
FROM attendee as a 
            Inner Join eventsignup as es on a.eventsignupid=es.id
            Inner Join eventsignupticketdetail estd on a.eventsignupid=estd.eventsignupid
            Inner Join ticket t on t.id=estd.ticketid 
            Left Outer Join chequepayments ch on es.id=ch.eventsignupid
            
    WHERE  es.eventid= ".$eventId.$QString."
        and ((es.paymentgatewayid=2) or es.paymenttransactionid!='A1' or (es. paymentmodeid=3 and paymenttransactionid='A1' and es.paymentstatus='Verified') )
        and es.paymentstatus!='Canceled'
        and es.paymentstatus!='Refunded'
        and a.ticketid=estd.ticketid
 
     order by es.id desc;

       ";}
		else if($isfree==2)
		{
		 $myQuery.="select es.id 'EventSIgnupId',                          
       es.signupdate 'Signup Date', es.paymenttransactionid,es.paymentgatewayid,
      
       es.discount 'PromotionCode', es.totalamount 'Fees',a.id 'attendeeId',
       CASE es.paymentstatus WHEN 'Verified' THEN 'Successful' 
          ELSE 'Pending' 
          End 'PaymentStatus',
        t.name,
       ((es.totalamount/es.quantity) - (es.eventextrachargeamount/es.quantity)) 'Paid' FROM attendee  a,eventsignup es ,ticket t, eventsignupticketdetail estd   WHERE a.eventsignupid=es.id and t.id=estd.ticketid 
and a.ticketid=estd.ticketid AND estd.eventsignupid = es.id and es.eventid=".$eventId.$QString."  order by es.id desc"; 
		}
		
	
		
$atten_res=$Globali->SelectQuery($myQuery);


  $onequery="select es.id,escf.customfieldid,ecf.fieldname,escf.attendeeid,escf.value 
from attendeedetail escf, 
customfield ecf ,eventsignup es, attendee a 
where escf.customfieldid=ecf.id and a.id = escf.attendeeid and a.eventsignupid=es.id and es.eventid=".$Globali->dbconn->real_escape_string($eventId);	

	if($isfree==1)
	{	 $onequery="select  es.id,escf.customfieldid,          
  ecf.fieldname,
  escf.value,
  escf.attendeeid  
from  attendeedetail escf 
Inner Join customfield ecf on escf.customfieldid=ecf.id 
Inner Join attendee a on escf.attendeeid = a.id
Inner Join eventsignup es on a.eventsignupid=es.id 
Left Outer Join chequepayments ch on ch.eventsignupid=es.id 
Left Outer Join codstatus cs on cs.eventsignupid=es.id 
                                    
where   es.paymentstatus not in ('Refunded','Canceled')
     AND (((es.paymenttransactionid='A1' and es.paymentgatewayid=2 AND es.paymentmodeid=2) or 
     (es.paymenttransactionid<>'A1' and es.paymentmodeid=1) or (es.paymentmodeid=2 and es.paymenttransactionid='A1')) or (es.paymentmodeid=3))
     and es.eventid=".$Globali->dbconn->real_escape_string($eventId)
	;
	}

//	echo "@@@". $onequery; exit;
	
	$onequeryrow=$Globali->SelectQuery($onequery); 
	
$singlearray=NULL;
	foreach($onequeryrow as $key=>$value)
	{
		//$singlearray[$value['id']][$value['attendeeId']][$value['eventcustomfieldsid']]=$value['EventSignupFieldValue'];
		
		$newindex=$value['id'];
		if(!empty($value['attendeeid']) && $value['attendeeid']!=0)
		$newindex=$value['id'].'-'.$value['attendeeid'];
		
		$singlearray[$newindex][$value['customfieldid']]=$value['value'];
		
	}
//print_r($singlearray); exit;
 
 $finalArray=NULL;// contains complete data combining both $ResultArray1 and $ResultArray2

$cou=0;
              $signupId='';
			  $am=0;
			  $tickc=1;
                        $totamt=0;
//creating finalarray with every parameters including the CUSTOM FIELDS.......########################
		foreach($atten_res as $key=>$list_row)// iterating through the 1st array containing Main fields 
		{	
			$PaymentTransId = '';
			$PromotionCode = '';
			
		    $PaymentTransId = $list_row['paymenttransactionid'];
			
			if(($list_row['PromotionCode'] != 'X'  && $list_row['Fees']==0) || $list_row['PromotionCode'] == 'PayatCounter' || $list_row['PromotionCode'] == 'CashonDelivery')  
			{
				$PaymentTransId = $list_row['PromotionCode'];
			}
			if($PaymentTransId != '' )
			{
				  $ExportAttendee[$cou]['EventSIgnupId'] = $list_row['EventSIgnupId']; 
				//get the event signup date				
				 $ExportAttendee[$cou]['SignupDt'] = $list_row['Signup Date']; 
				//ends get signup date
				$ExportAttendee[$cou]['TicketType']=$list_row['name']; 
				$ExportAttendee[$cou]['PaymentTransId'] = $PaymentTransId;
				$ExportAttendee[$cou]['PromotionCode'] = $list_row['PromotionCode'];
				$ExportAttendee[$cou]['Name'] = getattendeeinfo($list_row['attendeeId'],1);  
				$ExportAttendee[$cou]['attendeeId'] = $list_row['attendeeId'];
				$ExportAttendee[$cou]['Email'] = getattendeeinfo($list_row['attendeeId'],2);
				$ExportAttendee[$cou]['Company'] = getattendeeinfo($list_row['attendeeId'],8);
				
				$ExportAttendee[$cou]['City'] =  getattendeeinfo($list_row['attendeeId'],6);
				$ExportAttendee[$cou]['State'] =  getattendeeinfo($list_row['attendeeId'],5);
				             	
				/* $ExportAttendee[$cou]['field1'] = $list_row['field1'];
				$ExportAttendee[$cou]['field2'] = $list_row['field2'];
				$ExportAttendee[$cou]['field3'] = $list_row['field3'];
				$ExportAttendee[$cou]['field4'] = $list_row['field4']; */

                $ExportAttendee[$cou]['Phone'] = getattendeeinfo($list_row['attendeeId'],3);;
                $ExportAttendee[$cou]['aId'] = $list_row['Id'];
				$ExportAttendee[$cou]['Amount'] = $list_row['Amount'];
				$ExportAttendee[$cou]['Paid'] =$list_row['Paid'];  //($dtlES[0]['Fees']*$dtlES[0]['Qty'])-$dtlES[0]['Ccharge'];
		
				
				$ExportAttendee[$cou]['eChecked'] = $list_row['PaymentStatus'];
				$totamt+=$list_row['Paid'];
				
				if(isset($_REQUEST['export']))
				{
					//	echo "m sshere";
					$out .='"'.$ExportAttendee[$cou]['EventSIgnupId'].'",';
					$out .='"'.$ExportAttendee[$cou]['SignupDt'].'",';
					$out .='"'.$ExportAttendee[$cou]['TicketType'].'",';
					$out .='"'.$ExportAttendee[$cou]['PaymentTransId'].'",';
					$out .='"'.$ExportAttendee[$cou]['PromotionCode'].'",';
					$out .='"'.$ExportAttendee[$cou]['eChecked'].'",'; 
					$out .='"'.$ExportAttendee[$cou]['Name'].'",';
					$out .='"'.$ExportAttendee[$cou]['Email'].$ExportAttendee[$cou]['field1'].$ExportAttendee[$cou]['field2'].$ExportAttendee[$cou]['field3'].$ExportAttendee[$cou]['field4'].'",';
			
						$out.= '"'.$ExportAttendee[$cou]['City'].'",';
						$out.= '"'.$ExportAttendee[$cou]['State'].'",';
						
		
			
			
					$out .='"'.$ExportAttendee[$cou]['Company'].'",';
					$out .='"'.$ExportAttendee[$cou]['Phone'].'",';
					$out .='"'.$ExportAttendee[$cou]['Amount'].'",';
					$out .='"'.$ExportAttendee[$cou]['Paid'].'",';
						$CustNameval=" ";
			
		
			
		
					
		
		//getting customfield value #########################################################
		
			$countres=count($ResCust);
			//$countres=1;
				for ($cus = 0; $cus < $countres; $cus++) 
				{
					//echo $cus;
					$changed=0;
   
					$newindex1=$ExportAttendee[$cou]['EventSIgnupId'];
					if(!empty($value['attendeeId']) &&  $value['attendeeId']!=0)
					$newindex1=$ExportAttendee[$cou]['EventSIgnupId'].'-'.$ExportAttendee[$cou]['attendeeId'];
	   
					if(isset($singlearray[$newindex1][$ResCust[$cus]['Id']]))
					{
						$changed=1;
						$CustNameval.='"'.$singlearray[$newindex1][$ResCust[$cus]['Id']].'",';
					}
		
						
		
					if($changed==0)
					{
						$CustNameval.=',';
					}
		
				}
					$out .=$CustNameval;
					
					//end of custome field value ##############################################################3
		
		
		
		
		
					
				if($seat!=""){
					$sqlSeatnos="select GridPosition,Seatno from VenueSeats where EventSIgnupId=".$Globali->dbconn->real_escape_string($ExportAttendee[$cou]['EventSIgnupId']);
			$ResSeatno = $Globali->SelectQuery($sqlSeatnos);
			$countseat=count($ResSeatno);
			   
				if($countseat>$ssc){
				
				$out .=substr($ResSeatno[$ssc]['GridPosition'],0,1).$ResSeatno[$ssc]['Seatno']." ";
				
				$ssc++;
				}else{
				$ssc=0;
				$out .=substr($ResSeatno[$ssc]['GridPosition'],0,1).$ResSeatno[$ssc]['Seatno']." ";
				$ssc++;
				}
				}	
					$out .="\n";
		
				
				
	
				
				
					
				}
				
				$cou++;
			}
		
			
			
			
	}
	
	if(isset($_REQUEST['export']))
	{
		header("Content-type: text/x-csv");
					header("Content-Disposition: attachment; filename=CardTransaction_export.csv");
					echo $out;
					exit;
	}
	
	
	//echo "FInal Array: ".count($finalArray);
	return $ExportAttendee;
	
 }
   //print_r($_POST);
	if(isset($_REQUEST['submit'])|| $_REQUEST['export']|| $_REQUEST['EventId']!="")
	{
            	if(!empty($_REQUEST['EventId'])){
           $query="SELECT id FROM event WHERE id=".$_REQUEST['EventId']." and deleted=1";
           $outputEvent=$Global->SelectQuery($query);
           if(!$outputEvent){
		$eventId= $_REQUEST['EventId'];
		$lastRegNo=NULL;
		if(isset($_REQUEST['regno']))
		$lastRegNo= $_REQUEST['regno'];
		/*if(isset($_REQUEST['submit']) || $_REQUEST['EventId']!="")
		{
		 $sqlCust="select Id,EventCustomFieldName from eventcustomfields where EventCustomFieldSeqNo!='-1' and EventId=".$Globali->dbconn->real_escape_string($eventId);
		$ResCust = $Globali->SelectQuery($sqlCust);
		}*/
		$finalArray=run($eventId, $lastRegNo);
		//print_r($finalArray);
	}
        }}
	include 'templates/custom_field_event.tpl.php';	
	?>
