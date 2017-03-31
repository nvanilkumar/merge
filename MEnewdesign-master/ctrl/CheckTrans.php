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
	if($_REQUEST[value]!="")
	{
	if($_REQUEST['setDt']!="")
	{
	$SetDt = $_REQUEST['setDt'];
		$SetDtExplode = explode("/", $SetDt);
		$SETDATE = $SetDtExplode[2].'-'.$SetDtExplode[1].'-'.$SetDtExplode[0].' 00:00:00';
                $SETDATE =$common->convertTime($SETDATE, DEFAULT_TIMEZONE);
		}
		if($_REQUEST['depDt']!="")
	{
	$DepDt = $_REQUEST['depDt'];
		$DepDtExplode = explode("/", $DepDt);
		$DEPDATE = $DepDtExplode[2].'-'.$DepDtExplode[1].'-'.$DepDtExplode[0].' 00:00:00';
                $DEPDATE =$common->convertTime($DEPDATE, DEFAULT_TIMEZONE);
                
		}
                $paymentStatusQuery = "select paymentstatus,bookingtype from eventsignup where id=".$_REQUEST['sId'];
                $Respay = $Global->SelectQuery($paymentStatusQuery);
                //Not able to change refund/cancel to other payment status
                if (in_array($Respay['0']['paymentstatus'], $deductCount) && !in_array($_REQUEST['value'], $deductCount)) {
                    $msg = "<font color=red>Couldn't change the payment status</font>";
                } else {
                    $UpQuery = "update eventsignup set paymentstatus='" . $_REQUEST[value] . "',settlementdate='" . $SETDATE . "',depositdate='" . $DEPDATE . "' where id=" . $_REQUEST['sId'];
                    $ResUp = $Global->ExecuteQuery($UpQuery);
                }
            //descrease sold count when transaction is cancelled or refunded
            if($ResUp && in_array($_REQUEST['value'], $deductCount) && (strtolower($Respay['0']['paymentstatus']) != strtolower($_REQUEST['value'])) && !in_array($Respay['0']['paymentstatus'],$deductCount)){
                $UpQuery1="update ticket t INNER JOIN eventsignupticketdetail estd ON t.id=estd.ticketid INNER JOIN eventsignup es ON es.id=estd.eventsignupid set t.totalsoldtickets=t.totalsoldtickets-estd.ticketquantity where es.id=".$_REQUEST['sId'];
                $ResUp1= $Global->ExecuteQuery($UpQuery1);
            }
            if($ResUp && in_array($_REQUEST['value'], $deductCount) && (strtolower($Respay['0']['paymentstatus']) != strtolower($_REQUEST['value'])) && !in_array($Respay['0']['paymentstatus'],$deductCount)){
                if($Respay['0']['bookingtype']=='global'){
                    $getESPoints="select points,userid,eventsignupid from userpoint where type='affiliate' and points>0 and eventsignupid=".$_REQUEST['sId']." limit 1";
                    $resESPoints=$Global->SelectQuery($getESPoints);
                    if(count($resESPoints)>0){
                        $deductPoints="insert into userpoint(points,userid,type,eventsignupid) values('-".$resESPoints[0]['points']."','".$resESPoints[0]['userid']."','affiliate','".$resESPoints[0]['eventsignupid']."')";
                        $ResUp = $Global->ExecuteQueryId($deductPoints);
                    }
                }
                $UpQuery1="update ticket t INNER JOIN eventsignupticketdetail estd ON t.id=estd.ticketid INNER JOIN eventsignup es ON es.id=estd.eventsignupid set t.totalsoldtickets=t.totalsoldtickets-estd.ticketquantity where es.id=".$_REQUEST['sId'];
                $ResUp1= $Global->ExecuteQuery($UpQuery1);
            }
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
	else if($_REQUEST['settleDt']!="")
	{
		$settleDt1 = $_REQUEST['settleDt'];
		$settleDt1Explode = explode("/", $settleDt1);
		$settleDt = $settleDt1Explode[2].'-'.$settleDt1Explode[1].'-'.$settleDt1Explode[0].' 00:00:00';
                $settleDt =$common->convertTime($settleDt, DEFAULT_TIMEZONE);
		$stdate=" and s.settlementdate = '".$settleDt."' ";
		
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
	$status="";
	if(isset($_REQUEST['Status']) && $_REQUEST['Status']!="")
	{
	 if($_REQUEST['Status']=="All"){
	 $status="";
	 }else{
	$status=" AND s.paymentstatus ='".$_REQUEST[Status]."'";
	}
	}else{
         $status=" AND s.paymentstatus ='NotVerified'";
         $_REQUEST['Status']='NotVerified';
        }
		
		$Payment="";
	if(isset($_REQUEST[Payment]) && $_REQUEST[Payment]!="")
	{
	 if($_REQUEST[Payment]=="All"){
	 $Payment="";
	 }else{
	$Payment=" AND s.paymentgatewayid ='".$_REQUEST[Payment]."'";
	}
	}/*else{
         $Payment=" AND s.PaymentGateway ='EBS'";
         $_REQUEST[Payment]='EBS';
        }*/
 
	
	if(isset($_REQUEST[recptno]) && $_REQUEST[recptno]!=""){
	$signid=" and s.id=".$_REQUEST[recptno];
	}
	
	if(!empty($_REQUEST['EventId']) || !empty($_REQUEST['eventIdSrch'])){
            if(!empty($_REQUEST['EventId']))
                $EventId=" and s.eventid='".$_REQUEST['EventId']."'";
            else if(!empty($_REQUEST['eventIdSrch']))
            $EventId=" and s.eventid='".$_REQUEST['eventIdSrch']."'";
	}
	
	$freeTransSql='';
	if(isset($_REQUEST['freeTrans']))
	{
		$freeTransSql=' or s.totalamount=0 ';
	}
	
	
	$offTransSql=" AND  s.paymenttransactionid not in ('A1','Offline') ";
	if(isset($_REQUEST['offTrans']))
	{
		$offTransSql=" AND s.paymenttransactionid != 'A1' ";
	}
	
	 /*if($_REQUEST['SerEventName']!="")
	{
	 $sqlid = "SELECT Id,UserId FROM orgdispnameid where OrgId=".$_REQUEST['SerEventName'] ;
                  $dtsqlid1 = $Global->SelectQuery($sqlid);
                for($i=0;$i<count($dtsqlid1);$i++)
                    {
                    $orgid1.=$dtsqlid1[$i][UserId].","; 
                    }
                  
                   $orgid=substr($orgid1,0,-1);
                   $SearchQuery =" AND e.UserID in (".$orgid.") " ;  

	} */

	
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
	 ini_set('max_execution_time', 180);
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

$countQuery="SELECT count(s.id) as count FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id INNER JOIN paymentgateway AS pg ON pg.id = s.paymentgatewayid WHERE 1 AND e.deleted = 0  $dates AND (s.totalamount != 0 $freeTransSql $offTransSql) $status  $Payment $signid $stdate $EventId  $SearchQuery ORDER BY  s.signupdate DESC";
$countQueryRes=$Global->SelectQuery($countQuery); 
$totalItems= $countQueryRes[0]['count'];

 $totalAmountQuery="SELECT s.quantity,s.totalamount as 'Amount',c.code 'currencyCode',fc.code as fromCurrency,s.conversionrate as conversionRate,s.convertedamount  FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id 
LEFT JOIN currency c ON c.id=s.tocurrencyid 
LEFT JOIN currency fc ON fc.id=s.fromcurrencyid
WHERE 1 AND e.deleted = 0 $dates AND (s.totalamount != 0 $freeTransSql $offTransSql) $status  $Payment $signid $stdate $EventId  $SearchQuery ORDER BY  s.signupdate DESC";
$totalAmountRes=$Global->SelectQuery($totalAmountQuery);
//print_r($totalAmountRes);
//echo "_______________".$totalAmountRes[0]['Total_Amount']."________________";
//$AmountTillPage1="SELECT s.id 'id'  FROM EventSignup AS s INNER JOIN events AS e ON s.EventId = e.Id WHERE 1 $dates AND (s.Fees != 0 AND (s.PaymentTransId != 'A1')) $status  $Payment $signid $stdate $EventId  $SearchQuery ORDER BY  s.SignupDt limit 0,".$perPage*$page;        
//$AmountTillRes1=$Global->SelectQuery($AmountTillPage1);
//$lastId=$AmountTillRes1[0]['id'];
 //   echo "___________________".$AmountTillRes1[0]['id'],"______________";
  

	//Display list of Successful Transactions IF(s.gateway_commission>0,s.gateway_commission,0) as gateway_commission 
  	 $TransactionQuery = "SELECT s.eventid, s.id, s.signupdate, s.quantity,s.totalamount,(s.totalamount*s.conversionrate) 'AMOUNT', s.convertedamount, s.conversionrate as conversionRate, s.paymenttransactionid,  e.title,s.paymentstatus,s.settlementdate,s.depositdate,c.code 'currencyCode',pg.name
	FROM eventsignup AS s 
	INNER JOIN event AS e ON s.eventid = e.id 
	INNER JOIN paymentgateway AS pg ON pg.id = s.paymentgatewayid 
	LEFT JOIN currency c ON s.fromcurrencyid =c.id
	WHERE 1 AND e.deleted = 0 $dates AND (s.totalamount != 0 $freeTransSql $offTransSql) $status  $Payment $signid $stdate $EventId  $SearchQuery ORDER BY  s.signupdate DESC limit ". $start.",". $perPage;  
  
       $TransactionRES=$Global->SelectQuery($TransactionQuery); 
        $currCode=sameCurrencyCode($TransactionRES);
		
		//print_r($TransactionRES);
        
       
        
        
  
  
//  $lastId=$TransactionRES[$countTransactionRES-1]['Id'];
//  echo "___________".$lastId."________";
   // $AmountTillPage2="SELECT sum((s.qty*s.fees)) 'Amount_till_page'  FROM EventSignup AS s INNER JOIN events AS e ON s.EventId = e.Id WHERE 1 and s.id>=$lastId $dates AND (s.Fees != 0 AND (s.PaymentTransId != 'A1')) $status  $Payment $signid $stdate $EventId  $SearchQuery ORDER BY  s.SignupDt DESC";
     //   $AmountTillRes2=$Global->SelectQuery($AmountTillPage2);
   //  echo  "________________".$AmountTillRes2[0]['Amount_till_page']."______________";
   
   
   
    $totSum=$totConvertedSum=$totPaypalConvertedSum=array();
   foreach ($totalAmountRes as $k=>$v){
       $currencyCode=$v['fromCurrency'];
       /*if($v['conversionRate']!=1 && $v['currencyCode']!="INR"){
            $currencyCode="INR";
       }*/
       $totSum[$currencyCode]+=$v['Amount'];
   }
   //print_r($totSum);
   

	//print_r($totalAmountRes);
   foreach ($totalAmountRes as $k=>$v){
       $currencyCode=$v['currencyCode'];
       /*if($v['conversionRate']!=1 && $v['currencyCode']!="INR"){
            $currencyCode="INR";
       }*/
	   if($v['conversionRate']>1)
	   { 
	   	    if($v['convertedamount']>0){
				$totConvertedSum['INR']+=round($v['convertedamount']*$v['quantity']*$v['conversionRate'],2);
			}
			else{
				$totConvertedSum[$currencyCode]+=$v['Amount']*$v['conversionRate'];
			}

	   }
	   else
	   {  
	   		if($v['convertedamount']>0){
			  $totConvertedSum['USD']+=round($v['convertedamount']*$v['Qty'],2);
		   }
		   else{
			    $totConvertedSum[$currencyCode]+=$v['Amount'];
		   }
	   }
	   
	   
//	   print_r($totConvertedSum);exit;
    
    
     
    
   }
   //print_r($totConvertedSum);
   
   foreach ($totalAmountRes as $k=>$v){
       $currencyCode=$v['currencyCode'];
       /*if($v['conversionRate']!=1 && $v['currencyCode']!="INR"){
            $currencyCode="INR";
       }*/
	   
		if($v['convertedamount']>0){
				$totPaypalConvertedSum['USD']+=round($v['convertedamount']*$v['quantity'],2);
		}
		
	   
	   
	   
	   /*if($v['paypal_converted_amount']>0){ $totPaypalConvertedSum['USD']+=($v['paypal_converted_amount']*$v['Qty']);  }
	   else{ $totPaypalConvertedSum[$currencyCode]+=($v['Amount']);}*/
       
   }
   //print_r($totPaypalConvertedSum);
   
   
   
     
		$EventQuery = "SELECT s.eventid, e.title AS Details FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id 
		AND s.totalamount > 0 group by s.eventid where e.deleted = 0 ORDER BY e.title  DESC"; 
	//$EventQueryRES = $Global->SelectQuery($EventQuery);
			
	
 if(isset($_REQUEST['exportReports'])){
            
            $totalQuanity=0;
             $countTransactionRES=count($TransactionRES);
            if( $countTransactionRES > 0){
                
            $file='userfiles/'.$common->appendTimeStamp('transactionreports').'csv';
            $handle = fopen($file, 'wa+');
            $header=array('S.No','Receipt NO','Date','Event Details','Transaction No','Payment Gateway','Qty','paid','Paypal Converted Amt', 'Converted Amt','Transaction Status','SettelementDate','DepoositedDate');
            
            fwrite($handle, implode(',', $header).PHP_EOL);
               
            for($i = 0; $i < $countTransactionRES; $i++){
                
                if($TransactionRES[$i]['convertedamount']>0){ $convertedAmount= "USD ".round($TransactionRES[$i]['convertedamount']*$TransactionRES[$i]['quantity'],2); }else{ $convertedAmount= "NA";  }
                
                
                if($TransactionRES[$i]['conversionRate']>1){ 
				if($TransactionRES[$i]['convertedamount']>0){
					$conversionRate= "INR ".round($TransactionRES[$i]['convertedamount']*$TransactionRES[$i]['quantity']*$TransactionRES[$i]['conversionRate'],2); 
					$conversionRateAmount= round($TransactionRES[$i]['convertedamount']*$TransactionRES[$i]['quantity']*$TransactionRES[$i]['conversionRate'],2); 
				}
				else{ $conversionRate= "INR ".round($TransactionRES[$i]['AMOUNT'],2);
                                $conversionRateAmount= round($TransactionRES[$i]['AMOUNT'],2);
                                
                                }
				
			}
			else{ 
				if($TransactionRES[$i]['convertedamount']>0){
					$conversionRate= "USD ".round($TransactionRES[$i]['convertedamount']*$TransactionRES[$i]['quantity'],2); 
					$conversionRateAmount= round($TransactionRES[$i]['convertedamount']*$TransactionRES[$i]['quantity'],2); 
				}
				else{ 
                                    $conversionRate= $TransactionRES[$i]['currencyCode']." ".$TransactionRES[$i]['AMOUNT'];   
                                    $conversionRateAmount= $TransactionRES[$i]['AMOUNT'];   
                                    
                                }
			
				 
			} 
                        
                $sdate=$common->convertTime($TransactionRES[$i]['settlementdate'],DEFAULT_TIMEZONE,TRUE);
		$sdate=date('d/m/Y',strtotime($sdate));
                                                
                        $depdate=$common->convertTime($TransactionRES[$i]['depositdate'],DEFAULT_TIMEZONE,TRUE);
                        $depdate=date('d/m/Y',strtotime($depdate));
                
              $rowData.='"'.($i+1).'","'.$TransactionRES[$i]['id'].'","'.$common->convertTime($TransactionRES[$i]['signupdate'],DEFAULT_TIMEZONE,TRUE).'","'.stripslashes($TransactionRES[$i]['title']).'","'.$TransactionRES[$i]['paymenttransactionid'].'","'.
                      $TransactionRES[$i]['name'].'","'.$TransactionRES[$i]['quantity'].'","'.$TransactionRES[$i]['currencyCode']." ".$TransactionRES[$i]['totalamount'].'","'.$convertedAmount.'","'.$conversionRate.'","'.$TransactionRES[$i]['paymentstatus']
                  .'","'.$sdate.'","'.$depdate.'"'.PHP_EOL; 
              
               
                $totalExcelAmount+=$TransactionRES[$i]['totalamount'];
				$totalConvertedAmount+=$conversionRateAmount;
            }

			$rowData.='"Total Transaction Amount :","","","","","","",'. $totalExcelAmount.',"",'. $totalConvertedAmount;
            fwrite($handle, $rowData);

            header('Content-Description: File Transfer');
            header("Content-Type:'application/force-download'");
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            echo 111;
            exit;
            } 
        }
        
        

	include 'templates/CheckTrans.tpl.php';
?>
