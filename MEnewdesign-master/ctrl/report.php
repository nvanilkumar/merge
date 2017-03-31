<?php
	session_start();
	include 'loginchk.php';
	include_once("MT/cGlobal.php");
	
	
	$Global = new cGlobal();

      




	$MsgCountryExist = '';
	$msg="";
	$std="";
	    $incpay="";
	
		if($_REQUEST['txtSDt']!="" && $_REQUEST['txtEDt']!=""){
		$SDt = $_REQUEST['txtSDt'];
		$SDtExplode = explode("/", $SDt);
		$SDtYMD = $SDtExplode[2].'-'.$SDtExplode[1].'-'.$SDtExplode[0].' 00:00:00';
		
		$EDt = $_REQUEST['txtEDt'];
		$EDtExplode = explode("/", $EDt);
		$EDtYMD = $EDtExplode[2].'-'.$EDtExplode[1].'-'.$EDtExplode[0].' 23:59:59';
		$incpay.=" AND e.RegnDt between '".$SDtYMD."' and '".$EDtYMD."'";
		 
		
			 
		
		
		$targetpage="report.php?txtSDt=".$_REQUEST[txtSDt]."&txtEDt=".$_REQUEST[txtEDt];
		$pagenum=$_REQUEST[pagenum];
 if (!(isset($pagenum))) 
 { 
 $pagenum = 1; 
 } 
$page_rows = 20; 

$sqlc="SELECT count(e.Id) as tot FROM events as e, EventSignup as s    where e.Id=s.EventId and e.Title != '' and ((s.PaymentModeId=1 and s.PaymentTransId!='A1') or s.PaymentModeId=2) $incpay  ";
$r=$Global->SelectQuery($sqlc);
$rows = $r[0][tot]; 

		$last = ceil($rows/$page_rows); 
	 if ($pagenum < 1) 
 { 
$pagenum = 1; 
 } 
 elseif ($pagenum > $last) 
 { 
$pagenum = $last; 
 } 
 
 //This sets the range to display in our query 
if($pagenum > 0){
 $max = ' limit ' .($pagenum - 1) * $page_rows .',' .$page_rows; 
}else{
$max = ' limit 0,' .$page_rows; 
}


		
		
$EventsQuery = "SELECT distinct(e.Id),e.UserID,e.Title,e.QPid,e.URL,e.eChecked,e.QDate,e.OEmails,e.Tckwdz,e.PaidBit FROM events as e, EventSignup as s  where e.Id=s.EventId and e.Title != '' and ((s.PaymentModeId=1 and s.PaymentTransId!='A1') or s.PaymentModeId=2) $incpay   ORDER BY e.StartDt ASC $max";  
$EventsOfMonth = $Global->SelectQuery($EventsQuery);
		
		$pagination = "";


if ($pagenum == 1) 
 {
 } 
 else 
 {
 $pagination .= " <a href='$targetpage&pagenum=1'> <<-First</a> ";
 $previous = $pagenum-1;
$pagination .=  " <a href='$targetpage&pagenum=$previous'> <-Previous</a> ";
 } 
 //just a spacer
//$pagination .=  " ---- ";
 //This does the same as above, only checking if we are on the last page, and then generating the Next and Last links
 if ($pagenum == $last) 
 {

 } 
 else {
 $next = $pagenum+1;
 $pagination .=  " <a href='$targetpage&pagenum=$next'>Next -></a> ";
 $pagination .=  " ";
 $pagination .=  " <a href='$targetpage&pagenum=$last'>Last ->></a> ";
 } 

	
}

	include 'templates/report.tpl.php';
?>