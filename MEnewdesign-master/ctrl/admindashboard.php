<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Display Cancel Transactions
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 26th Aug 2009
 *	2.	Added the new filed IsFamous in db which is used to display the Famous Events on the front end.
 * 		The check box property checked shows the event is famous, visible on front end and vice versa.
******************************************************************************************************************************************/
	
	session_start();
	include 'loginchk.php';
	$uid =	$_SESSION['uid'];
	

	
	include_once("MT/cGlobal.php");
       include_once('graphs.inc.php'); 
       include "chart.class.php";  
       include "piechart.class.php";
   $Global=new cGlobal();
    if($_REQUEST[profile_pstate]!="")
	{
	$st=" AND StateId=".$_REQUEST[profile_pstate];
	}else{
	$st=" ";
	}
	 if($_REQUEST[profile_pcity]!="")
	{
	$ct=" AND CityId=".$_REQUEST[profile_pcity];
	}else{
	$ct=" ";
	}
	 if($_REQUEST['SerEventName']!="")
	{
	 $sqlid = "SELECT Id,UserId FROM orgdispnameid where OrgId=".$_REQUEST['SerEventName'] ;
                  $dtsqlid1 = $Global->SelectQuery($sqlid);
                for($i=0;$i<count($dtsqlid1);$i++)
                    {
                    $orgid1.=$dtsqlid1[$i][UserId].","; 
                    }
                  
                   $orgid=substr($orgid1,0,-1);
                   $SearchQuery .=" AND UserID in (".$orgid.") " ;  

	}else{
	$SearchQuery=" ";
	}
	if($_REQUEST[startdate]!="") 
	{
	$Stdate=" and StartDt between '".date('Y-m-d H:i:s',strtotime($_REQUEST[startdate]))."' and '".date('Y-m-d H:i:s',strtotime($_REQUEST[enddate]))."'";
	}else{
	$Stdate="";
	}
 $sqltotalevents="Select * from events where 1 and Title!='' $st $ct $Stdate $SearchQuery";
   $Restotalevents = $Global->SelectQuery($sqltotalevents);
    $sqltotalpevents="Select * from events where EndDt < now()  and Published=1 $st $ct $Stdate $SearchQuery";
   $Restotalpevents = $Global->SelectQuery($sqltotalpevents);
  $sqltotalfevents="Select * from events where StartDt > now()  and Published=1 $st $ct $Stdate $SearchQuery";
   $Restotalfevents = $Global->SelectQuery($sqltotalfevents);
   $sqltotalpaidevents="Select * from events where Free=0  and Published=1 $st $ct $Stdate $SearchQuery";
   $Restotalpaidevents = $Global->SelectQuery($sqltotalpaidevents);
    if($_REQUEST[profile_pstate]!="" || $_REQUEST[profile_pcity]!="" || $_REQUEST[startdate]!="" || $_REQUEST['SerEventName']!="")
	{
     $freesql="Select * from events where 1 and Free=1  and Published=1 $st $ct $Stdate $SearchQuery";
     $Restotalfreeevents = $Global->SelectQuery($freesql); 
     }else{
      $freesql="Select * from events where 1 and Free=1  and Published=1 ";
      $Restotalfreeevents = $Global->SelectQuery($freesql);
       }
 
   
   if($_REQUEST[profile_pstate]!="" || $_REQUEST[profile_pcity]!="" || $_REQUEST[startdate]!="" || $_REQUEST['SerEventName']!="")
	{
   $sqlregevents="SELECT DISTINCT (EventId) FROM EventSignup where  EventId in(select Id from events where 1  and Published=1 $st $ct $Stdate $SearchQuery) ";
   $ResRegevents = $Global->SelectQuery($sqlregevents);
   }else {
    $sqlregevents="SELECT DISTINCT (EventId) FROM EventSignup";
   $ResRegevents = $Global->SelectQuery($sqlregevents);
   }
 
   
        if($_REQUEST[profile_pstate]!="" || $_REQUEST[profile_pcity]!="" || $_REQUEST[startdate]!="" || $_REQUEST['SerEventName']!="")
	{
   $sqlregusers="SELECT DISTINCT (UserId) FROM EventSignup where (Fees=0 or (PaymentModeId=1 and PaymentTransId!='A1') or  PaymentModeId=2 or PromotionCode='Y') and EventId in(select Id from events where 1 $st $ct $Stdate $SearchQuery) ";
   $ResRegusers = $Global->SelectQuery($sqlregusers);
   }else {
    $sqlregusers="SELECT DISTINCT (UserId) FROM EventSignup where (Fees=0 or (PaymentModeId=1 and PaymentTransId!='A1') or  PaymentModeId=2 or PromotionCode='Y')";
    $ResRegusers = $Global->SelectQuery($sqlregusers);
	}
   
   
      if($_REQUEST[profile_pstate]!="" || $_REQUEST[profile_pcity]!="" || $_REQUEST[startdate]!="" || $_REQUEST['SerEventName']!="")
	{
   $sqlcardamount="SELECT Fees,Qty FROM EventSignup where (PaymentModeId=1 and PaymentTransId!='A1')  and EventId in(select Id from events where 1 $st $ct $Stdate $SearchQuery) ";
   $ResCardAmount = $Global->SelectQuery($sqlcardamount);
   }else {
    $sqlcardamount="SELECT Fees,Qty FROM EventSignup where (PaymentModeId=1 and PaymentTransId!='A1') ";
    $ResCardAmount = $Global->SelectQuery($sqlcardamount);
	}
   
   $TotalCardAmount=0;
   for($i = 0; $i < count($ResCardAmount); $i++){
   $TotalCardAmount +=$ResCardAmount[$i]['Fees']*$ResCardAmount[$i]['Qty'];
   }
     if($_REQUEST[profile_pstate]!="" || $_REQUEST[profile_pcity]!="" || $_REQUEST[startdate]!="" || $_REQUEST['SerEventName']!="")
	{
   $sqlcounteramount="SELECT Fees,Qty  FROM EventSignup where PromotionCode='Y'  and EventId in(select Id from events where 1 $st $ct $Stdate $SearchQuery) ";
   $ResCounterAmount = $Global->SelectQuery($sqlcounteramount);
   }else {
    $sqlcounteramount="SELECT Fees,Qty  FROM EventSignup where PromotionCode='Y' ";
    $ResCounterAmount = $Global->SelectQuery($sqlcounteramount);
	}
   
    $TotalCounterAmount=0;
   for($i = 0; $i < count($ResCounterAmount); $i++){
   $TotalCounterAmount +=$ResCounterAmount[$i]['Fees']*$ResCounterAmount[$i]['Qty'];
   }
    if($_REQUEST[profile_pstate]!="" || $_REQUEST[profile_pcity]!="" || $_REQUEST[startdate]!="" || $_REQUEST['SerEventName']!="")
	{
   $sqlchequeamount="SELECT s.Fees,s.Qty FROM EventSignup AS s,ChqPmnts AS cq Where s.Id=cq.EventSignupId and s.PaymentModeId =2 and s.EventId in(select Id from events where 1 $st $ct $Stdate $SearchQuery) ";
   $ResChequeamount = $Global->SelectQuery($sqlchequeamount);
   }else {
    $sqlchequeamount="SELECT s.Fees,s.Qty FROM EventSignup AS s,ChqPmnts AS cq Where s.Id=cq.EventSignupId and s.PaymentModeId =2 ";
    $ResChequeamount = $Global->SelectQuery($sqlchequeamount);
	}
   
    $TotalChequeAmount=0;
   for($i = 0; $i < count($ResChequeamount); $i++){
   $TotalChequeAmount +=$ResChequeamount[$i]['Fees']*$ResChequeamount[$i]['Qty'];
   }

    if($_REQUEST[profile_pstate]!="" || $_REQUEST[profile_pcity]!="" || $_REQUEST[startdate]!="" || $_REQUEST['SerEventName']!="")
	{
	$sqlToalat="select Id from Attendees where EventSIgnupId in (select Id from EventSignup where (Fees=0 or (PaymentModeId=1 and PaymentTransId!='A1') or  PaymentModeId=2 or PromotionCode='Y') and EventId in(select Id from events where 1 $st $ct $Stdate $SearchQuery))";
       $ResToalat = $Global->SelectQuery($sqlToalat); 
      
	}else{
     $sqlToalat="select Id from Attendees where EventSIgnupId in (select Id from EventSignup where (Fees=0 or (PaymentModeId=1 and PaymentTransId!='A1') or  PaymentModeId=2 or PromotionCode='Y') )";
     $ResToalat = $Global->SelectQuery($sqlToalat); 
   }
 
  


 
   
	
  include 'templates/admindashboard.tpl.php';	
	
	
?>

