	<?php
	session_start();
	include_once("MT/cGlobali.php");
	 include 'loginchk.php';
	 
       //  print_r($_GET);
	$Global = new cGlobali();
        include_once("includes/functions.php");
        include_once("includes/common_functions.php");
        $common=new functions();
	$MsgCountryExist = '';
        $msg = '';
	$sessData=array();
        $deductCount=array('Refunded','Canceled');
	if($_REQUEST[value]!="" && $_REQUEST['sId']!="")
	{       
      $UpQuery1="update eventsignup set promotercode = '".$_REQUEST[value]."' where id=".$_REQUEST['sId'];
      $ResUp1= $Global->ExecuteQuery($UpQuery1);
           
	}else if($_REQUEST[value]!="" && $_REQUEST['sId']=="" && $_REQUEST['EventId']!=""){
		$UpQuery1="update eventsignup set promotercode = '".$_REQUEST[value]."' where eventid=".$_REQUEST['EventId'];
      $ResUp1= $Global->ExecuteQuery($UpQuery1);
	}
	
    if($_REQUEST['txtSDt']!="" && $_REQUEST['txtEDt'] != "")
	{
		$SDt = $_REQUEST['txtSDt'];
		$SDtExplode = explode("/", $SDt);
		$yesterdaySDate = $SDtExplode[2].'-'.$SDtExplode[1].'-'.$SDtExplode[0].' 00:00:00';
                $yesterdaySDate =$common->convertTime($yesterdaySDate, DEFAULT_TIMEZONE);
		
		$EDt = $_REQUEST['txtEDt'];
		$EDtExplode = explode("/", $EDt);
		$yesterdayEDate = $EDtExplode[2].'-'.$EDtExplode[1].'-'.$EDtExplode[0].' 23:59:59';
                $yesterdayEDate =$common->convertTime($yesterdayEDate, DEFAULT_TIMEZONE);
		$dates=" and s.signupdate >= '".$yesterdaySDate."' AND s.signupdate <= '".$yesterdayEDate."' ";
		
	}
	
	else if($_REQUEST['txtSDt']=="" && $_REQUEST['txtEDt'] == ""){
                $SDt = $_REQUEST['txtSDt'];
                $EDt = $_REQUEST['txtEDt'];
               }
	 else if($_REQUEST[recptno]=="")
	{
	   $SDt = date ("d/m/Y", mktime (0,0,0,date("m"),(date("d")-1),date("Y")));
	   $EDt =date ("d/m/Y", mktime (0,0,0,date("m"),(date("d")-1),date("Y")));
		$yesterdaySDate = date ("Y-m-d", mktime (0,0,0,date("m"),(date("d")-1),date("Y"))).' 00:00:01';
	$yesterdayEDate = date ("Y-m-d", mktime (0,0,0,date("m"),(date("d")-1),date("Y"))).' 23:59:59';
	$dates=" and s.signupdate >= '".$yesterdaySDate."' AND s.signupdate <= '".$yesterdayEDate."' ";
	} 

	if(isset($_REQUEST[recptno]) && $_REQUEST[recptno]!=""){
	$signid=" and s.id=".$_REQUEST[recptno];
	}
	
	if(!empty($_REQUEST['EventId']) || !empty($_REQUEST['eventIdSrch'])){
            if(!empty($_REQUEST['EventId']))
                $EventId=" and s.eventid='".$_REQUEST['EventId']."'";
            else if(!empty($_REQUEST['eventIdSrch']))
            $EventId=" and s.eventid='".$_REQUEST['eventIdSrch']."'";
	}
	
	

	
	$TotalAmount = 0;
	//$cntTransactionRES = 1;	
        
        
        
        function pagination($per_page = 10, $page = 1, $url = '', $total){

$adjacents = "2";

$url='http://'.$_SERVER['HTTP_HOST'].$url;

$page = ($page == 0 ? 1 : $page);
$start = ($page - 1) * $per_page;

$prev = $page - 1;
$next = $page + 1;
$lastpage = ceil($total/$per_page);
$lpm1 = $lastpage - 1;

$pagination = "";
if($lastpage > 1)
{
$pagination .= "<ul class='pagination'>";
$pagination .= "<li class='details'>Page $page of $lastpage</li>";
if ($lastpage < 7 + ($adjacents * 2))
{
for ($counter = 1; $counter <= $lastpage; $counter++)
{
if ($counter == $page)
$pagination.= "<li><a class='current'>$counter</a></li>";
else
$pagination.= "<li><a href='{$url}$counter'>$counter</a></li>";
}
}
elseif($lastpage > 5 + ($adjacents * 2))
{
if($page < 1 + ($adjacents * 2))
{
for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
{
if ($counter == $page)
$pagination.= "<li><a class='current'>$counter</a></li>";
else
$pagination.= "<li><a href='{$url}$counter'>$counter</a></li>";
}
$pagination.= "<li class='dot'>...</li>";
$pagination.= "<li><a href='{$url}$lpm1'>$lpm1</a></li>";
$pagination.= "<li><a href='{$url}$lastpage'>$lastpage</a></li>";
}
elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
{
$pagination.= "<li><a href='{$url}1'>1</a></li>";
$pagination.= "<li><a href='{$url}2'>2</a></li>";
$pagination.= "<li class='dot'>...</li>";
for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
{
if ($counter == $page)
$pagination.= "<li><a class='current'>$counter</a></li>";
else
$pagination.= "<li><a href='{$url}$counter'>$counter</a></li>";
}
$pagination.= "<li class='dot'>..</li>";
$pagination.= "<li><a href='{$url}$lpm1'>$lpm1</a></li>";
$pagination.= "<li><a href='{$url}$lastpage'>$lastpage</a></li>";
}
else
{
$pagination.= "<li><a href='{$url}1'>1</a></li>";
$pagination.= "<li><a href='{$url}2'>2</a></li>";
$pagination.= "<li class='dot'>..</li>";
for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
{
if ($counter == $page)
$pagination.= "<li><a class='current'>$counter</a></li>";
else
$pagination.= "<li><a href='{$url}$counter'>$counter</a></li>";
}
}
}

if ($page < $counter - 1){
$pagination.= "<li><a href='{$url}$next'>Next</a></li>";
// $pagination.= "<li><a href='{$url}$lastpage'>Last</a></li>";
}else{
//$pagination.= "<li><a class='current'>Next</a></li>";
// $pagination.= "<li><a class='current'>Last</a></li>";
}
$pagination.= "</ul>\n";
}
return $pagination;
} 
        
$perPage=500;
 if(isset($_REQUEST['exportReports'])){
	 ini_set('memory_limit', '300M');
	 ini_set('max_execution_time', -1);
	 $perPage=500000;
 }
$page=1;
if(isset($_GET['page']) && $_GET['page']!=''){
$page=$_GET['page'];
//echo "in here";
} 
//if($page!=1){
$start=($page-1)*$perPage;
$cntTransactionRES=$start+1;
//}else
//{
//    $start=1;
//}
//echo "__________pagenumer__".$page."___________";
if($_REQUEST['submit']=="Show Report"){
 $countQuery="SELECT count(s.id) as count FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id INNER JOIN paymentgateway AS pg ON pg.id = s.paymentgatewayid WHERE 1 
 AND e.deleted = 0 $dates AND (s.totalamount != 0 AND s.paymenttransactionid != 'A1' and s.paymentmodeid != 4) $signid $stdate $EventId  $SearchQuery ORDER BY  s.signupdate DESC";
$countQueryRes=$Global->SelectQuery($countQuery); 
$totalItems= $countQueryRes[0]['count'];

  

	//Display list of Successful Transactions IF(s.gateway_commission>0,s.gateway_commission,0) as gateway_commission 
  	 $TransactionQuery = "SELECT s.eventid, s.id, s.signupdate, s.promotercode, s.quantity,s.totalamount,(s.totalamount*s.conversionrate) 'AMOUNT', s.convertedamount, s.conversionrate as conversionRate, s.paymenttransactionid,  e.title,s.paymentstatus,s.settlementdate,s.depositdate,c.code 'currencyCode',pg.name
	FROM eventsignup AS s 
	INNER JOIN event AS e ON s.eventid = e.id 
	INNER JOIN paymentgateway AS pg ON pg.id = s.paymentgatewayid 
	LEFT JOIN currency c ON s.fromcurrencyid =c.id
	WHERE 1 AND e.deleted = 0 $dates AND (s.totalamount != 0 AND s.paymenttransactionid != 'A1' and s.paymentmodeid != 4) $signid $stdate $EventId  $SearchQuery ORDER BY  s.signupdate DESC limit ". $start.",". $perPage;  
  
       $TransactionRES=$Global->SelectQuery($TransactionQuery); 
        $currCode=sameCurrencyCode($TransactionRES);
		
		//print_r($TransactionRES);
        
       
	}      
        
  

   
   
   
    
   
			
	

        


include 'templates/metoorgefforts.tpl.php';
?>