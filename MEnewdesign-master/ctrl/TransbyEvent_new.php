<?php
	session_start();
	
	
	include_once("MT/cGlobali.php");
	include 'loginchk.php';
	
	$Global = new cGlobali();
	$MsgCountryExist = '';
        include_once("includes/common_functions.php");
        $common=new functions();
	

	$compeve=NULL;
		//if(isset($_REQUEST['exportReports'])){
	 ini_set('memory_limit', '3000M');
	 ini_set('max_execution_time', -1);	
// } 

	if(!empty($_REQUEST['eventIdSrch'])){
           $query="SELECT id FROM event WHERE id=".$_REQUEST['eventIdSrch']." and deleted=1";
           $output=$Global->SelectQuery($query);
           if($output){
               $EventId=$_POST['eventIdSrch'];   
               include 'templates/TransbyEvent_new.tpl.php';
               exit;
        }}
               if($_REQUEST['value']!="")
	{
	 $UpQuery="update eventsignup set paymentstatus='".$_REQUEST[value]."' where id=".$_REQUEST['sId'];
	
    $ResUp= $Global->ExecuteQuery($UpQuery);
	}

      
	if($_REQUEST['txtSDt'] != "")
	{
		$SDt = $_REQUEST['txtSDt'];
		$SDtExplode = explode("/", $SDt);
		$yesterdaySDate = $SDtExplode[2].'-'.$SDtExplode[1].'-'.$SDtExplode[0].' 00:00:00';
		$yesterdaySDate =$common->convertTime($yesterdaySDate, DEFAULT_TIMEZONE);
		$dates=" and e.startdatetime >= '".$yesterdaySDate."' ";
	}
	if($_REQUEST['txtEDt'] != "")
	{
		$EDt = $_REQUEST['txtEDt'];
		$EDtExplode = explode("/", $EDt);
		$yesterdayEDate = $EDtExplode[2].'-'.$EDtExplode[1].'-'.$EDtExplode[0].' 23:59:59';
		$yesterdayEDate =$common->convertTime($yesterdayEDate, DEFAULT_TIMEZONE);
		$dates=" and e.enddatetime <= '".$yesterdayEDate."' ";
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
		$dates=" and e.startdatetime >= '".$yesterdaySDate."' AND e.enddatetime <= '".$yesterdayEDate."' ";
		
	}
	
	

        
        $offline_Sql=" AND  s.paymenttransactionid not in ('A1','Offline') ";
 if(isset($_REQUEST['offline']))
 {
  $offline_Sql=" AND s.paymenttransactionid != 'A1' ";
 }
	
	if(isset($_REQUEST['compeve']) && $_REQUEST['compeve']==1)
	{
	$compeve=" AND e.enddatetime < now() ";
	}
        $amountType='paid';
        if(isset($_REQUEST['amountsType'])){
            $amountType=$_REQUEST['amountsType'];    
        }
	switch ($amountType) {
            case 'paid':$amountsFilter=" having TotalTransAmount>0 ";
                break;
            case 'free':$amountsFilter=" having TotalTransAmount=0 ";
            default:
            break;
}
        
	
	$EventId=$EventIdSql=NULL;
	$PublishedSql=" and e.status=1 ";
	if(!empty($_REQUEST['EventId']) || !empty($_REQUEST['eventIdSrch'])){
            if(!empty($_REQUEST['EventId']))
			{
				$EventId=$_REQUEST['EventId'];
                $EventIdSql=" and s.eventid='".$_REQUEST['EventId']."'";
				$PublishedSql=NULL;
			}
            else if(!empty($_REQUEST['eventIdSrch']))
			{
				$EventId=$_REQUEST['eventIdSrch'];
            	$EventIdSql=" and s.eventid='".$_REQUEST['eventIdSrch']."'";
				$PublishedSql=NULL;
			}
	}
	
	
	/*if($_REQUEST['SerEventName']!="")
	{
	 $sqlid = "SELECT Id,UserId FROM orgdispnameid where OrgId=".$_REQUEST['SerEventName'] ;
                  $dtsqlid1 = $Global->SelectQuery($sqlid);
                for($i=0;$i<count($dtsqlid1);$i++)
                    {
                    $orgid1.=$dtsqlid1[$i]['UserId'].","; 
                    }
                  
                   $orgid=substr($orgid1,0,-1);
                   $SearchQuery =" AND e.UserID in (".$orgid.") " ;  

	} */
	$TotalAmount = 0;
	$cntTransactionRES = 1;	
	if($_REQUEST['Status']!="")
	{
	if($_REQUEST['Status']=="Pending")
	{
/*	$TransactionQuery = "SELECT distinct(s.EventId),e.UserID,e.Title,e.StartDt,e.EndDt FROM EventSignup AS s,  events AS e where     s.EventId = e.Id   and s.EventId not in (select EventId from Paymentinfo)  AND ((s.PaymentModeId=1 and s.PaymentTransId != 'A1') or (s.PaymentModeId=2))  $dates $compeve $EventId $SearchQuery and e.Published=1 and  s.eChecked !='Canceled' order by e.StartDt DESC";  */
	
	 $TransactionQuery=" SELECT distinct(s.eventid),e.status,
                e.ownerid,
                e.title,
				e.url,
                e.startdatetime,
                e.enddatetime,
                 u.company 'OrgName',
                count(s.id) 'RegNo',
                sum(s.quantity) 'Qty',
                 ROUND(SUM(CASE WHEN  (totalamount!=0 and fromcurrencyid != 1 and ( paymenttransactionid != 'A1') or (paymentmodeid=2 or discount='CashonDelivery' or discount='PayatCounter'))then (s.totalamount  * s.conversionrate)
				       WHEN  (totalamount!=0 and fromcurrencyid = 1 and ( paymenttransactionid != 'A1') or (paymentmodeid=2 or discount='CashonDelivery' or discount='PayatCounter'))then (s.totalamount)
                     else 0 
                end),0) 'TotalTransAmount',
                ROUND(SUM(CASE WHEN s.paymentstatus = 'Refunded' AND totalamount != 0 and s.fromcurrencyid != 1  Then (s.totalamount  * s.conversionrate)
				               WHEN s.paymentstatus = 'Refunded' AND totalamount != 0 and s.fromcurrencyid = 1  Then (s.totalamount)
                     else 0
                end),0) 'RefundedAmount',
                ROUND(SUM(CASE WHEN s.paymentstatus = 'Verified' AND s.totalamount != 0 and s.fromcurrencyid != 1  Then (s.totalamount  * s.conversionrate)
				               WHEN s.paymentstatus = 'Verified' AND s.totalamount != 0 and s.fromcurrencyid = 1 Then (s.totalamount)
                     else 0
                end),0) 'VerifiedAmount'
        FROM eventsignup AS s   Inner Join event AS e on s.eventid = e.id
                                Left Outer Join user u on u.id=e.ownerid
                                 LEFT OUTER Join (select max(id) as id,eventid from settlement as s where paymenttype='Partial Payment' and 1 $EventIdSql group by s.eventid) as pi ON s.eventid = pi.eventid   
        where s.eventid not in (select eventid from settlement where paymenttype in ('Done','EventCanceled')and status=1) AND ((s.paymenttransactionid != 'A1') or (s.paymentmodeid=2)) 
                $dates $offline_Sql $compeve $EventIdSql $SearchQuery 
                  AND s.paymentstatus != 'Canceled'
				$PublishedSql
     group by s.eventid $amountsFilter
    order by e.startdatetime DESC;";
	
	
	}elseif($_REQUEST['Status']=="Done"){

            $TransactionQuery="SELECT distinct(s.eventid),e.status,
                e.ownerid,
                e.title,
				e.url,
                e.startdatetime,
                e.enddatetime,
                 u.company 'OrgName',
                count(s.id) 'RegNo',
                sum(s.quantity) 'Qty',
                ROUND(SUM(CASE WHEN (totalamount!=0 and fromcurrencyid != 1 and ( paymenttransactionid != 'A1') or (paymentmodeid=2 or discount='CashonDelivery' or discount='PayatCounter'))then (s.totalamount  * s.conversionrate) 
				 WHEN (totalamount!=0 and fromcurrencyid = 1 and ( paymenttransactionid != 'A1') or (paymentmodeid=2 or discount='CashonDelivery' or discount='PayatCounter'))then (s.totalamount ) 
                else 0 end),0) 'TotalTransAmount',
                ROUND(SUM(CASE WHEN s.paymentstatus = 'Refunded' AND totalamount != 0 and s.fromcurrencyid != 1  Then (s.totalamount  * s.conversionrate) 
				               WHEN s.paymentstatus = 'Refunded' AND totalamount != 0 and s.fromcurrencyid = 1  Then (s.totalamount)
				     else 0 end),0) 'RefundedAmount', 
                ROUND(SUM(CASE WHEN s.paymentstatus = 'Verified' AND s.totalamount != 0 and s.fromcurrencyid != 1  Then (s.totalamount  * s.conversionrate) 
				               WHEN s.paymentstatus = 'Verified' AND s.totalamount != 0 and s.fromcurrencyid = 1  Then (s.totalamount)
				 else 0 end),0) 'VerifiedAmount'               
                    FROM eventsignup AS s Inner Join event AS e on s.eventid = e.id    
                    Left Outer Join user u on u.id=e.ownerid 
                                        INNER Join (select max(id) as id,eventid from settlement as s where paymenttype='Done' and status=1 and 1 $EventIdSql group by s.eventid) as pi ON s.eventid = pi.eventid        
                                    where s.eventid not in (select eventid from settlement where paymenttype in ('Partial Payment','EventCanceled')) AND (( s.paymenttransactionid != 'A1') or (s.paymentmodeid=2)) 
										$dates $offline_Sql $compeve $EventIdSql $SearchQuery
  AND s.paymentstatus != 'Canceled'
											$PublishedSql
                                        group by s.eventid $amountsFilter
                                    order by e.startdatetime DESC;";
									


//echo $TransactionQuery;
	}else{
	
	
	 $TransactionQuery="SELECT distinct(s.eventid),e.status,
                e.ownerid, 
                e.title,
				e.url,
                e.startdatetime, 
                e.enddatetime,
                u.company 'OrgName' , 
                count(s.id) 'RegNo', 
                sum(s.quantity) 'Qty',
                ROUND(SUM(CASE WHEN (totalamount!=0 and fromcurrencyid != 1 and (paymenttransactionid != 'A1') or (paymentmodeid=2 or discount='CashonDelivery' or discount='PayatCounter'))then (s.totalamount  * s.conversionrate) 
				WHEN (totalamount!=0 and fromcurrencyid = 1 and (paymenttransactionid != 'A1') or (paymentmodeid=2 or discount='CashonDelivery' or discount='PayatCounter'))then (s.totalamount)
                else 0 end),0) 'TotalTransAmount',
                ROUND(SUM(CASE WHEN s.paymentstatus = 'Refunded' AND totalamount != 0 and s.fromcurrencyid != 1 Then (s.totalamount  * s.conversionrate) 
				               WHEN s.paymentstatus = 'Refunded' AND totalamount = 0 and s.fromcurrencyid != 1 Then (s.totalamount)
				else 0 end),0) 'RefundedAmount', 
                ROUND(SUM(CASE WHEN s.paymentstatus = 'Verified' AND s.totalamount != 0 and s.fromcurrencyid != 1 Then (s.totalamount  * s.conversionrate) 
				               WHEN s.paymentstatus = 'Verified' AND s.totalamount != 0 and s.fromcurrencyid = 1 Then (s.totalamount)
				else 0 end),0) 'VerifiedAmount'
                FROM eventsignup AS s Inner Join event AS e on s.eventid = e.id 
                                            Left Outer Join user u on u.id=e.ownerid 
                                      INNER Join (select max(id) as id,eventid from settlement as s where paymenttype='EventCanceled' and 1 $EventIdSql group by s.eventid) as pi ON s.eventid = pi.eventid     
                                    where 1 
										$dates $offline_Sql $compeve $EventIdSql $SearchQuery
  AND s.paymentstatus != 'Canceled'
											$PublishedSql
                                        group by s.eventid $amountsFilter
                                    order by e.startdatetime DESC;";
									


//echo $TransactionQuery;
	}
	
	}else{
	
		//Display list of Successful Transactions  
/*   	$TransactionQuery = "SELECT distinct(s.EventId),e.UserID,e.Title,e.StartDt,e.EndDt FROM EventSignup AS s INNER JOIN events AS e ON s.EventId = e.Id  where  ((s.PaymentModeId=1 and s.PaymentTransId != 'A1') or (s.PaymentModeId=2)) $compeve $dates $EventId $SearchQuery and e.Published=1 and s.eChecked !='Canceled' order by e.StartDt DESC"; */
	
	//STATUS WITHOUT DATES AND completed checkbox not checked
          $TransactionQuery="SELECT DISTINCT(s.eventid),e.status,
e.ownerid,
e.title, e.url,
e.startdatetime,
e.enddatetime,
u.company 'OrgName',
COUNT(s.id) 'RegNo',
SUM(s.quantity) 'Qty',
ROUND(SUM(CASE
WHEN (totalamount != 0 and fromcurrencyid != 1 AND
(paymenttransactionid != 'A1') OR
(paymentmodeid = 2 OR
discount = 'CashonDelivery' OR
discount = 'PayatCounter')) THEN (s.totalamount  * s.conversionrate)
WHEN (totalamount != 0 and fromcurrencyid = 1 AND
(paymenttransactionid != 'A1') OR
(paymentmodeid = 2 OR
discount = 'CashonDelivery' OR
discount = 'PayatCounter')) THEN (s.totalamount)
ELSE 0
END), 0) 'TotalTransAmount',
ROUND(SUM(CASE
WHEN s.paymentstatus = 'Refunded' and s.fromcurrencyid != 1 AND
totalamount != 0 THEN (s.totalamount  * s.conversionrate)
WHEN s.paymentstatus = 'Refunded' and s.fromcurrencyid = 1 AND
totalamount != 0 THEN (s.totalamount)
ELSE 0
END),0) 'RefundedAmount',
ROUND(SUM(CASE
WHEN s.paymentstatus = 'Verified' and s.fromcurrencyid != 1 AND
totalamount != 0 THEN (s.totalamount  * s.conversionrate)
WHEN s.paymentstatus = 'Verified' and s.fromcurrencyid = 1 AND
totalamount != 0 THEN (s.totalamount)
ELSE 0
END),0) 'VerifiedAmount'
FROM eventsignup AS s
INNER JOIN event AS e
ON s.eventid = e.id
LEFT OUTER JOIN user u
ON u.id = e.ownerid
LEFT OUTER JOIN (select max(id) as id,eventid from settlement as s where 1 $EventIdSql group by s.eventid) as pi ON s.eventid = pi.eventid
WHERE ((s.paymenttransactionid != 'A1') OR (s.paymentmodeid = 2))
 $dates $offline_Sql $compeve $EventIdSql $SearchQuery
  AND s.paymentstatus != 'Canceled'
$PublishedSql
group by s.eventid $amountsFilter
ORDER BY e.startdatetime DESC"; 
	
	 }
	 //echo $TransactionQuery;exit;
	 $TransactionRES=$Global->SelectQuery($TransactionQuery); 
		
         
		$EventQuery = "SELECT distinct(s.eventid), e.title AS Details FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id where 1  AND ((s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or (s.paymentmodeid=2))   ORDER BY e.Title  DESC"; 
//	$EventQueryRES = $Global->SelectQuery($EventQuery);
			

	 
	 
	 if(isset($_REQUEST['exportReports'])){
            
            $totalQuanity=0;
             $countTransactionRES=count($TransactionRES);
            if( $countTransactionRES > 0){
                
            $file='userfiles/'.$common->appendTimeStamp('GatewayTransactions').'csv';
            $handle = fopen($file, 'wa+');
            $header=array('S.No','Date','Event Details','OrgName','Reg No','Qty','TotalTransAmount','RefundedAmount', 'VerifiedAmount','Partial Payment','Total Amt to be Paid','UploadDocs','Comments');
            
            fwrite($handle, implode(',', $header).PHP_EOL);
               
            for($i = 0; $i < $countTransactionRES; $i++){
				
				$TotaltAmountcard=0;
		$TotalAmountcard=0;
		$TotalrAmountcard=0;
		$TotalAmountverified=0;
		$TotalNetverified=0;
		$Totalreg=0;
		$Totalqty=0;
        $netamt=0;
		$totrev=0;
		$tobepaid=0;
		$totPaidAmt=0;
                $dataHTML='';
				$par='';
                $selAllPayments="SELECT DATE(paymentdate) as date,amountpaid,paymenttype FROM settlement WHERE eventid='".$TransactionRES[$i]['eventid']."' and status='1' ORDER BY paymentdate ASC";
                    $resAllPayments=$Global->SelectQuery($selAllPayments);
                    if(count($resAllPayments)>0){ 
                        foreach ($resAllPayments as $k=>$v){
                               // if(strcmp($v['PType'], 'Partial Payment')==0){
                                    $dataHTML.=$v['amountpaid'].'('.$v['date'].')';
                               // }
                                    $totPaidAmt+=$v['amountpaid'];
                        }
                        if(strlen($dataHTML)>0){
                           $par= $dataHTML;
                        }else{
							$par='';
						}
                    }
					
					$totAmtToBePaid = $TransactionRES[$i]['VerifiedAmount']-$totPaidAmt;
					$finalPartailAmt+=$totPaidAmt;
					$finalTotAmtToBePaid+=$totAmtToBePaid;
					
					$selQry="SELECT paymenttype from settlement where eventid='".$TransactionRES[$i]['eventid']."' order by id desc limit 1";
              $resQry=$Global->SelectQuery($selQry);
              $status='PENDING';
              if(count($resQry)>0){
                  if(strcmp($resQry[0]['paymenttype'],'Done')==0){
                      $status='DONE'; 
                  }elseif(strcmp($resQry[0]['paymenttype'], 'EventCanceled')==0){
                      $status='CANCELED';
                  }
              }
			  $comment=" ";
			  $comment=$Global->GetSingleFieldValue("SELECT comment FROM comment where eventid=".$TransactionRES[$i]['eventid']);
			   $TotalAmount=$TotalAmount+$TransactionRES[$i]['TotalTransAmount'];;
		$TotalAmount1=$TotalAmount1+$TransactionRES[$i]['RefundedAmount'];;
		$TotalAmount2=$TotalAmount2+$TotalAmountcard;
		$TotalAmount3=$TotalAmount3+$TransactionRES[$i]['VerifiedAmount'];;
		$TotalAmount4=$TotalAmount4+$TotalNetverified;
		 $TotalNetAmount=$TotalNetAmount+$netamt;
		 $Totaltobepaid=$Totaltobepaid+$tobepaid;
		 $TotalRevenue=$TotalRevenue+$totrev;
		 $TotalAmount5=round($TotalAmount5+$tobepaid-$netamt,2);
                 $tot_commission+=$TransactionRES[$i]['gateway_commission'];
		
                
                   
              $rowData.='"'.($i+1).'","'.date("F j, Y, g:i a",strtotime($TransactionRES[$i]['startdatetime'])). " to ".date("F j, Y, g:i a",strtotime($TransactionRES[$i]['enddatetime'])).'","'.stripslashes($TransactionRES[$i]['title']).'","'.$TransactionRES[$i]['OrgName'].'","'.
                      $TransactionRES[$i]['RegNo'].'","'.$TransactionRES[$i]['Qty'].'","'.$TransactionRES[$i]['TotalTransAmount'].'","'.$TransactionRES[$i]['RefundedAmount'].'","'.$TransactionRES[$i]['VerifiedAmount'].'","'.$par.'","'.$totAmtToBePaid
                  .'","'.$status.'","'.$comment.'"'.PHP_EOL; 
              
               
                $totalExcelAmount+=$TransactionRES[$i]['totalamount'];
				$totalConvertedAmount+=$conversionRateAmount;
            }

			$rowData.='"Total Transaction Amount :","","","","","",'.$TotalAmount.','. $TotalAmount1.','.$TotalAmount3.','.$finalPartailAmt.','.$finalTotAmtToBePaid;
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

	//echo "aaaaaaaaaaaaaaaa"; exit;   
        
        if($_REQUEST['AddComment']){
            $data=array();
            $comments=addslashes($_POST['comments']);
            $EventId=$_POST['eventId'];
            $data['status']=false;
            $insQry="INSERT INTO comment(comment,eventid,createdby,type) VALUES('".$comments."','".$EventId."','".$_SESSION['uid']."','accounts')";
            $id=$Global->ExecuteQueryId($insQry);
            if($id>0){
              $data['status']=true;  
              $data['id']=$id;
            }
        }

        $EventId=$_POST['eventIdSrch'];   
	
        
	include 'templates/TransbyEvent_new.tpl.php';
?>
