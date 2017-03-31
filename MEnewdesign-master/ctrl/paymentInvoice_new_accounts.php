<?php

	
	session_start();
	$uid =	$_SESSION['uid'];
	
	include 'loginchk.php';	
	
	include_once("MT/cGlobali.php");
	
	$Global = new cGlobali();
	
	include 'includes/common_functions.php';
        include 'includes/PaymentAdviceFunctions.php';
	$commonFunctions=new functions();
	$advice=new PaymentAdviceFunctions();
        
        $event_id_custom = $_REQUEST['event_id_custom'];
        if($event_id_custom != '') {
            $_REQUEST['EventId'] = $event_id_custom;
        } 
        	if(!empty($_REQUEST['EventId'])){
           $query="SELECT id FROM event WHERE id=".$_REQUEST['EventId']." and deleted=1";
           $outputPaymentInvoice=$Global->SelectQuery($query);
           if(!$outputPaymentInvoice){

	$eventOverAllPerc = $perc = $Global->GetSingleFieldValue("select percentage from eventsetting where eventid='" . $_REQUEST[EventId] . "'");
	if($_REQUEST['export']=="ExportPaymentAdvice" || $_REQUEST['download']=="EmailPaymentAdvice")
	{
	 if($_REQUEST['EventId']!=""){
	 $sql="select e.title AS Title,u.company AS Company,u.email AS Email "
                 . "FROM event e, user u where e.deleted=0 and e.ownerid=u.id and e.id=".$_REQUEST['EventId'];
	 $QueryRES = $Global->SelectQuery($sql);
	  $Organiser=$QueryRES[0][Company];
	  $Title=$QueryRES[0][Title];
	  $Email=$QueryRES[0][Email];
	  
	  $EventLevelEbsComm = $EventLevelPaypalComm = $EventLevelMobikwikComm = $EventLevelCodComm = $EventLevelCounterComm = $EventLevelMEeffortComm = $EventLevelPaytmComm = 0;  // event level default values 

        $sqlComm1 = "select `type`,`value` from `commission`  where eventid='" . $_REQUEST['EventId'] . "'  AND deleted = 0 AND global = 0";
        $ELCommmCard = $Global->SelectQuery($sqlComm1);
		     for ($i = 0; $i < count($ELCommmCard); $i++) {
						if ($ELCommmCard[$i]['type'] == 1) {
                            $EventLevelEbsComm = $ELCommmCard[$i]['value'];
                        }

                        if ($ELCommmCard[$i]['type'] == 3) {
                            $EventLevelSpotRegistrationCounter = $ELCommmCard[$i]['value'];
                        }
                        if ($ELCommmCard[$i]['type'] == 4) {
                            $EventLevelPaypalComm = $ELCommmCard[$i]['value'];
                        }
						if ($ELCommmCard[$i]['type'] == 11) {
                            $EventLevelMEeffortComm = $ELCommmCard[$i]['value'];
                        }
						if($recComm[$i]['type'] == 12){
	                    $ServiceTaxComm=$recComm[$i]['value'];
	                    }
                    }
	  
 $Commm =  $EventLevelEbsComm;
	  if($Commm =="" || $Commm ==0){
	  $Commm=$_REQUEST[Commm];
	  }
	  $Chqno=$_REQUEST[Chqno];
	  $Chqdt=$_REQUEST[Chqdt];
	  $AccName=$_REQUEST[AccName];
	  $Accno=$_REQUEST[Accno];
	  $BnkName=$_REQUEST[BnkName];
	  $BnkBranch=$_REQUEST[BnkBranch];
	  $Acctype=$_REQUEST[Acctype];
	  $IFCS=$_REQUEST[IFCS];
	  
	   /* if($_REQUEST['EventId']!=""){
	      $UserID =  $Global->GetSingleFieldValue("select ownerid AS UserID from event where id='".$_REQUEST['EventId']."'");
		  $BankQuery="select * from organizerbankdetail  where userid=".$UserID; //using 6/8
		  $BankQueryRES = $Global->SelectQuery($BankQuery);
		  if(count($BankQueryRES)>0){
		  $bankupdate="update organizerbankdetail set accountname='".$AccName."',bankname='".$BnkName."', "
                          . "accountnumber='".$Accno."', accounttype='".$Acctype."', branch='". $BnkBranch."', "
                          . "ifsccode='".$IFCS."' where userid=".$UserID;
		 $Global->ExecuteQuery($bankupdate);
		  }else{
		   $bankupdate="insert into organizerbankdetail (userid,accountname,bankname,accountnumber,accounttype,branch,ifsccode) values('".$UserID."','".$AccName."','".$BnkName."','".$Accno."','".$Acctype."','". $BnkBranch."','".$IFCS."')";
		 $Global->ExecuteQuery($bankupdate);
		  }
		  } */
	  
	  
	  if($_REQUEST['txtSDt']!="" && $_REQUEST['txtEDt']!=""){
	  $SDt = $_REQUEST['txtSDt'];
		$SDtExplode = explode("/", $SDt);
		$yesterdaySDate = $SDtExplode[2].'-'.$SDtExplode[1].'-'.$SDtExplode[0].' 00:00:00';
	
		
		$EDt = $_REQUEST['txtEDt'];
		$EDtExplode = explode("/", $EDt);
		$yesterdayEDate = $EDtExplode[2].'-'.$EDtExplode[1].'-'.$EDtExplode[0].' 23:59:59';
		$SqDate=" and s.signupdate between '".$yesterdaySDate."' and '".$yesterdayEDate."'";
		}else{
		$SqDate="";
		}
		
	
		
	   $transql="SELECT estd.ticketquantity AS NumOfTickets, estd.amount AS TicketAmt,s.eventid AS EventId, s.id AS Id,"
                   . "s.signupdate AS SignupDt, e.title AS Details, "
                   . "s.quantity AS Qty, (s.totalamount/s.quantity) AS Fees, s.paymenttransactionid AS PaymentTransId, "
                   . "s.discount AS PromotionCode,s.paymentmodeid AS PaymentModeId,s.paymentstatus AS eChecked, "
                   . "s.eventextrachargeamount AS Ccharge,p.`name` AS PaymentGateway, "
                   . "c.code currencyCPaymentTransIdode "
                   . "FROM eventsignup AS s "
                   . "INNER JOIN eventsignupticketdetail estd ON estd.eventsignupid=s.Id "
                   . "INNER JOIN event AS e ON s.eventid = e.id "
                   . "INNER JOIN currency c ON c.id=s.fromcurrencyid "
                   . "INNER JOIN paymentgateway p on s.paymentgatewayid=p.id "
                   . "WHERE 1 AND (s.paymentmodeid=1 and s.paymenttransactionid != 'A1') "
                   . "AND s.eventid=". $_REQUEST['EventId'].  $SqDate ." "
                   . "AND s.paymentstatus!='Refunded' "
                   . "AND s.paymentstatus!='Canceled' "
                   . "ORDER BY s.signupdate"; 
	   $TranRES = $Global->SelectQuery($transql);
	   $transByGateway=  $advice->getCardTransByGateway($TranRES);
           
	    $transqlr="SELECT  estd.ticketquantity AS NumOfTickets, estd.amount AS TicketAmt,s.eventid AS EventId, s.id AS Id, "
                    . "s.signupdate AS SignupDt, e.title AS Details, "
                    . "s.quantity AS Qty, (s.totalamount/s.quantity) AS Fees, s.paymenttransactionid AS PaymentTransId,"
                    . "s.discount AS PromotionCode,s.paymentmodeid AS PaymentModeId,s.paymentstatus AS eChecked,"
                    . "s.eventextrachargeamount AS Ccharge,p.`name` AS PaymentGateway,"
                    . "c.code currencyCode "
                    . "FROM eventsignup AS s "
                    . "INNER JOIN eventsignupticketdetail estd ON estd.eventsignupid=s.Id "
                    . "INNER JOIN event AS e ON s.eventid = e.id "
                    . "INNER JOIN currency c on s.fromcurrencyid=c.id "
                    . "INNER JOIN paymentgateway p on s.paymentgatewayid=p.id "
                    . "WHERE 1 AND (s.paymentmodeid=1 and s.paymenttransactionid != 'A1') "
                    . "and s.eventid=".$_REQUEST['EventId'] . $SqDate ." "
                    . "and s.paymentstatus='Refunded'  "
                    . "order by s.signupdate"; 
	   $TranRESr = $Global->SelectQuery($transqlr);
	   $transrByGateway=  $advice->getCardTransByGateway($TranRESr);
	   
           
	   $paycomm =  $Global->GetSingleFieldValue("select value AS Paypal from commission where eventid='".$_REQUEST['EventId']."' AND type = 4 AND deleted = 0 AND global = 0");
	   if($paycomm==0 || $paycomm=="")
	   {
	   $paycomm=8;
	   }
			include("includes/mpdf/mpdf.php");
         $mpdf=new mPDF();
     
         $data='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>MeraEvents - Signup Events</title>
<link href="'._HTTP_CF_ROOT.'"/ctrl/css/style.min.css.gz" rel="stylesheet" type="text/css" />
</head>
<body>
<table width="850" align="center" border="10" cellpadding="5" cellspacing="0" bordercolor="#CCCCCC" style="font-family:Arial, Helvetica, sans-serif; font-size:18px;">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>

        <td width="21%"><img  src="http://content.meraevents.com/images/VOS-logo.jpg" alt="Versent Logo" /></td>
        <td width="63%" align="center"><div align="center">PAYMENT    ADVISE<br />
                <strong>Mera Events Collections    &amp; Payment details</strong></div></td>
        <td width="16%"><img width="144" height="91" src="http://content.meraevents.com/images/online-events-portal.jpg" alt="Meraevents Logo" /></td>
      </tr>
    </table></td>
  </tr>
  <tr></tr>
  <tr>
    <td><table width="100%" border="1" cellpadding="2" cellspacing="0">
      <tr height="21">

        <td width="500" height="30">Versant Online Solutions Private Limited</td>
        <td width="152" height="30"><strong>Name of the Organiser:</strong></td>
        <td width="274" height="30" colspan="2">'.$Organiser.'</td>
      </tr>
      <tr height="20">
        <td height="30">3Cube    Towers, 2nd Floor, White Field Road, Kondapur, Hyderabad-500084</td>
        <td height="30"><strong>Name of the Event&nbsp; :</strong></td>
        <td height="30" colspan="2">'.$Title.'</td>
      </tr>
      <tr height="20">
        <td height="30" colspan="4"><strong>Service    Tax No# : </strong> AABCV8766GST002</td>
      </tr>
 <tr height="20">
        <td height="30" colspan="4"><strong>EventId# : </strong>'.$_REQUEST['EventId'].'</td>
      </tr>

    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>';
         $grandTotalamt = NULL;
        foreach ($transByGateway as $Gatewayk => $Gatewayv) {
			 $cardCommisionType = 0;
            if (strtolower($Gatewayk) == 'ebs' || strtolower($Gatewayk) == 'mobikwik' || strtolower($Gatewayk) == 'paytm') {
                $cardCommisionType = 1;
            }
        $TranRES = $Gatewayv;
        $comm = strcmp($Gatewayk, 'paypal') == 0 ? $paycomm : $Commm;
        $currencyType = '';
        if (strcmp($Gatewayk, 'paypal') != 0) {
            $currencyType = '(INR)';
        }
            $data.='<tr>
    <td><b>Card Transactions('.$Gatewayk.')</b></td>
  </tr>
  <tr>
    <td><table width="100%" border="1" cellspacing="0" cellpadding="2">
      <tr height="35">
        <td height="25" width="112" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Receipt No.</strong></td>
        <td height="25" width="150" align="left" nowrap="nowrap" bordercolor="#000000"><strong>PGIRef. No.</strong></td>
        <td height="25" width="150" align="left" nowrap="nowrap" bordercolor="#000000"><strong>DateofTxn</strong></td>
        <td height="25" width="80" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Qty</strong></td>
        <td height="25" width="92" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Amount'.$currencyType.'</strong></td>
        <td height="25" width="90" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Commission<br/>('.$comm.'%)'.$currencyType.'</strong></td>
        <td height="25" width="90" align="left" nowrap="nowrap" bordercolor="#000000"><strong>ServiceTax'.$currencyType.'<br/>@14%</strong></td>
        <td height="25" width="90" align="left" nowrap="nowrap" bordercolor="#000000"><strong>NetAmount'.$currencyType.'</strong></td>
		 </tr>';
		 
        $calComm=NULL;
        $serTax = NULL;
        $netAmt = NULL;
        $totqty = NULL;
        $totamt = NULL;
        $totcom = NULL;
        $totst = NULL;
        $totna = NULL;
        $netToPay = NULL;
        $coTran = count($TranRES);
        for ($i = 0; $i < $coTran; $i++) {
            $calComm[$TranRES[$i]['currencyCode']] = (($TranRES[$i][Qty] * $TranRES[$i][Fees]) + $TranRES[$i][DAmount] - $TranRES[$i][Ccharge]) * ($comm / 100);


            if ($TranRES[$i][SignupDt] > '2012-03-31 23:59:59') {
                $serTax[$TranRES[$i]['currencyCode']] = $calComm[$TranRES[$i]['currencyCode']] * (14 / 100);
            } else {
                $serTax[$TranRES[$i]['currencyCode']] = $calComm[$TranRES[$i]['currencyCode']] * (10.3 / 100);
            }
            $netAmt[$TranRES[$i]['currencyCode']] = ($TranRES[$i][Qty] * $TranRES[$i][Fees]) - $calComm[$TranRES[$i]['currencyCode']] - $serTax[$TranRES[$i]['currencyCode']];


            $transid = "TR:" . $TranRES[$i][PaymentTransId];
			
	$data.=' <tr>
        <td height="20" align="left" bordercolor="#000000">'.$TranRES[$i][Id].'</td>
        <td align="left" bordercolor="#000000">'.$transid.'</td>
        <td align="left" bordercolor="#000000">'.date("d/m/Y H:i",strtotime($TranRES[$i][SignupDt])).'</td>
        <td align="left" bordercolor="#000000">'.$TranRES[$i]['qty_paid'].'</td>
        <td align="left" bordercolor="#000000">';
        $cType='';
        if(strcmp($Gatewayk, 'PayPal')==0){
            $cType=$TranRES[$i]['currencyCode'].' ';
            $data.=$TranRES[$i]['currencyCode'].' '.(($TranRES[$i][Qty]*$TranRES[$i][Fees])-$TranRES[$i][Ccharge]);
        }else{
            $data.=(($TranRES[$i][Qty]*$TranRES[$i][Fees])-$TranRES[$i][Ccharge]);
        };
        $data.='</td>
        <td align="left" bordercolor="#000000">'.  $cType.round($calComm[$TranRES[$i]['currencyCode']],2).'</td>
        <td align="left" bordercolor="#000000">'.  $cType.round($serTax[$TranRES[$i]['currencyCode']],2).'</td>
        <td align="left" bordercolor="#000000">'.  $cType.round($netAmt[$TranRES[$i]['currencyCode']],2).'</td>
		
      </tr>';
        $totqty=$totqty+$TranRES[$i]['qty_paid'];
        $totamt[$TranRES[$i]['currencyCode']]+=($TranRES[$i][Qty]*$TranRES[$i][Fees]);
        $totcom[$TranRES[$i]['currencyCode']]+=$calComm[$TranRES[$i]['currencyCode']];
        $totst[$TranRES[$i]['currencyCode']]+=$serTax[$TranRES[$i]['currencyCode']];
        $totna[$TranRES[$i]['currencyCode']]+=$netAmt[$TranRES[$i]['currencyCode']];
        $netToPay[$TranRES[$i]['currencyCode']]+=$netAmt[$TranRES[$i]['currencyCode']];
        $grandTotalamt[$TranRES[$i]['currencyCode']]+=$netAmt[$TranRES[$i]['currencyCode']];
     }
		 
      $data.=' 
      <tr height="20">
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">'.$totqty.'</td>
        <td height="25" bordercolor="#000000">'.$advice->totalStrWithCurrencies($totamt).'</td>
        <td height="25" bordercolor="#000000">'.$advice->totalStrWithCurrencies($totcom).'</td>
        <td height="25" bordercolor="#000000">'.$advice->totalStrWithCurrencies($totst).'</td>
        <td height="25" bordercolor="#000000">'.$advice->totalStrWithCurrencies($totna).'</td>
      </tr>';
	  
      if(strcmp($Gatewayk,'ebs')==0){
              $TranRESr=$transrByGateway['ebs'];
          }else if(strcmp($Gatewayk,'paypal')==0){
              $TranRESr=$transrByGateway['paypal'];
          }else if(strcmp($Gatewayk,'mobikwik')==0){
              $TranRESr=$transrByGateway['mobikwik'];
          }else if(strcmp($Gatewayk,'paytm')==0){
              $TranRESr=$transrByGateway['paytm'];
          }
                        $calCommr=NULL;
			$serTaxr=NULL;
			$netAmtr=NULL;
			$totqtyr=NULL;
			$totamtr=NULL;
			$totcomr=NULL;
			$totstr=NULL;
			$totnar=NULL;
			$netToPayr=NULL;
			 $coTranr=count($TranRESr);
			for($i=0; $i < $coTranr; $i++)
			{
            if($TranRESr[$i][PaymentTransId]=="PayPalPayment"){ 
			$calCommr[$TranRESr[$i]['currencyCode']]=($TranRESr[$i][Qty]*$TranRESr[$i][Fees])*($paycomm/100);
			}else{          
			$calCommr[$TranRESr[$i]['currencyCode']]=($TranRESr[$i][Qty]*$TranRESr[$i][Fees])*($Commm/100);
			}
			if($TranRESr[$i][SignupDt] > '2012-03-31 23:59:59')
			{
			$serTaxr[$TranRESr[$i]['currencyCode']]=$calCommr[$TranRESr[$i]['currencyCode']]*(14/100);
			}else{
			$serTaxr[$TranRESr[$i]['currencyCode']]=$calCommr[$TranRESr[$i]['currencyCode']]*(10.3/100);
			}
			
			$netAmtr[$TranRESr[$i]['currencyCode']]=($TranRESr[$i][Qty]*$TranRESr[$i][Fees])-$calCommr[$TranRESr[$i]['currencyCode']]-$serTaxr[$TranRESr[$i]['currencyCode']];
			 $totqtyr=$totqtyr+$TranRESr[$i]['qty_paid'];
			 $totamtr[$TranRESr[$i]['currencyCode']]+=($TranRESr[$i][Qty]*$TranRESr[$i][Fees]);
	  $totcomr[$TranRESr[$i]['currencyCode']]+=$calCommr[$TranRESr[$i]['currencyCode']];
	  $totstr[$TranRESr[$i]['currencyCode']]+=$serTaxr[$TranRESr[$i]['currencyCode']];
	  $totnar[$TranRESr[$i]['currencyCode']]+=$netAmtr[$TranRESr[$i]['currencyCode']];
          $netToPay[$TranRESr[$i]['currencyCode']]-=($calCommr[$TranRESr[$i]['currencyCode']]+$serTaxr[$TranRESr[$i]['currencyCode']]);
          $grandTotalamt[$TranRESr[$i]['currencyCode']]-=($calCommr[$TranRESr[$i]['currencyCode']]+$serTaxr[$TranRESr[$i]['currencyCode']]);
	  }
	  
	  $data.=' <tr height="20">
        <td height="25" bordercolor="#000000" colspan=2>Refunds</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
       <td height="25" bordercolor="#000000">'.$totqtyr.'</td>
        <td height="25" bordercolor="#000000">'.$advice->totalStrWithCurrencies($totamtr).'</td>
        <td height="25" bordercolor="#000000">'.  $advice->totalStrWithCurrencies($totcomr).'</td>
        <td height="25" bordercolor="#000000">'.  $advice->totalStrWithCurrencies($totstr).'</td>
        <td height="25" bordercolor="#000000">'.  $advice->totalStrWithCurrencies($totnar).'</td>
      </tr>
	    <tr height="20">
        <td height="25" bordercolor="#000000" colspan=3>Net to Pay (Net Amount-Refund Charges)</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">'.  $advice->totalStrWithCurrencies($netToPayr).'</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>';
         // $grandTotalamt+=round($totna-$totcomr-$totstr,2);
         } 
  if($_REQUEST[COD]==1){
  $Commm3 =  $Global->GetSingleFieldValue("select value AS Cod from commission where eventid='".$_REQUEST['EventId']."' AND type = 2 AND deleted = 0 AND global = 0");
	  if($Commm3 =="" || $Commm3 ==0){
	  $Commm3=$_REQUEST[Commm];
	  }
 
            $COD="SELECT estd.ticketquantity AS NumOfTickets, estd.amount AS TicketAmt,s.eventid AS EventId, s.id AS Id, "
                    . "s.signupdate AS SignupDt, e.title AS Details, "
                    . "s.quantity AS Qty, (s.totalamount/s.quantity) AS Fees, s.paymenttransactionid AS PaymentTransId,"
                    . "s.discount AS PromotionCode, s.paymentmodeid AS PaymentModeId, s.paymentstatus AS eChecked,"
                    . "s.eventextrachargeamount AS Ccharge "
                    . "FROM eventsignup AS s "
                    . "INNER JOIN eventsignupticketdetail estd ON estd.eventsignupid=s.id "
                    . "INNER JOIN event AS e ON s.eventid = e.id "
                    . "WHERE s.paymentgatewayid='2' "
                    . "and s.eventid=".$_REQUEST['EventId'] . $SqDate ." "
                    . "and s.paymentstatus ='Verified' "
                    . "order by s.signupdate"; 
	   $TranCODRESt = $Global->SelectQuery($COD);
           $TranCODRES=$advice->getCODPaidTrans($TranCODRESt);
	   
	   $CODrefund="SELECT estd.ticketquantity AS NumOfTickets, estd.amount AS TicketAmt,s.eventid AS EventId, s.id AS Id, "
                    . "s.signupdate AS SignupDt, e.title AS Details, "
                    . "s.quantity AS Qty, (s.totalamount/s.quantity) AS Fees, s.paymenttransactionid AS PaymentTransId,"
                    . "s.discount AS PromotionCode, s.paymentmodeid AS PaymentModeId, s.paymentstatus AS eChecked,"
                    . "s.eventextrachargeamount AS Ccharge "
                   . "FROM eventsignup AS s "
                   . "INNER JOIN eventsignupticketdetail estd ON estd.eventsignupid=s.Id "
                   . "INNER JOIN event AS e ON s.eventid = e.Id "
                   . "WHERE s.paymentgatewayid='2' "
                   . "and s.eventid=".$_REQUEST['EventId'] . $SqDate ." "
                   . "and s.paymentstatus='Refunded' "
                   . "order by s.signupdate"; 
	   $TranCODrefundRESt = $Global->SelectQuery($CODrefund);
           $TranCODrefundRES=$advice->getCODPaidTrans($TranCODrefundRESt);
 
 $data.=' <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><b>COD Transactions</b></td>
  </tr>
  <tr>
    <td><table width="100%" border="1" cellspacing="0" cellpadding="2">
      <tr height="35">
        <td height="25" width="112" align="left" nowrap="nowrap" bordercolor="#000000"><strong>ReceiptNo.</strong></td>
        <td height="25" width="150" align="left" nowrap="nowrap" bordercolor="#000000"><strong>PGIRef.No.</strong></td>
        <td height="25" width="150" align="left" nowrap="nowrap" bordercolor="#000000"><strong>DateofTxn</strong></td>
        <td height="25" width="80" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Qty</strong></td>
        <td height="25" width="112" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Amount<br/>(Rs)</strong></td>
        <td height="25" width="120" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Commission<br/>('.$Commm3.'%)</strong></td>
        <td height="25" width="120" align="left" nowrap="nowrap" bordercolor="#000000"><strong>ServiceTax<br/>@14%</strong></td>
        <td height="25" width="112" align="left" nowrap="nowrap" bordercolor="#000000"><strong>NetAmount</strong></td>
		</tr>';
	        $calComm3=0;
			$serTax3=0;
			$netAmt3=0;
			$totqty3=0;
			$totamt3=0;
			$totcom3=0;
			$totst3=0;


			$totna3=0;
			$coTran3=count($TranCODRES);
			for($i=0; $i < $coTran3; $i++)
			{
                      
			$calComm3=($TranCODRES[$i][Qty]*$TranCODRES[$i][Fees])*($Commm3/100);
			if($TranCODRES[$i][SignupDt] > '2012-03-31 23:59:59')
			{
			$serTax3=$calComm3*(14/100);
			}else{
			$serTax3=$calComm3*(10.3/100);
			}
			
			
			$netAmt3=($TranCODRES[$i][Qty]*$TranCODRES[$i][Fees])-$calComm3-$serTax3;
			
			
			
			 $transid3="CashonDelivery";
			
			 $data.=' <tr>
        <td height="20" align="left" bordercolor="#000000">'.$TranCODRES[$i][Id].'</td>
        <td align="left" bordercolor="#000000">'.$transid3.'</td>
        <td align="left" bordercolor="#000000">'.date("d/m/Y H:i",strtotime($TranCODRES[$i][SignupDt])).'</td>
        <td align="left" bordercolor="#000000">'.$TranCODRES[$i]['qty_paid'].'</td>
        <td align="left" bordercolor="#000000">'.($TranCODRES[$i][Qty]*$TranCODRES[$i][Fees]).'</td>
        <td align="left" bordercolor="#000000">'.round($calComm3,2).'</td>
        <td align="left" bordercolor="#000000">'.round($serTax3,2).'</td>
        <td align="left" bordercolor="#000000">'.round($netAmt3,2).'</td>
		
      </tr>';
	  $totqty3=$totqty3+$TranCODRES[$i]['qty_paid'];
	  $totamt3=$totamt3+($TranCODRES[$i][Qty]*$TranCODRES[$i][Fees]);
	  $totcom3=$totcom3+$calComm3;
	  $totst3=$totst3+$serTax3;
	  $totna3=$totna3+$netAmt3;
			}
		
	  
      $data.=' 
      <tr height="20">
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">'.$totqty3.'</td>
        <td height="25" bordercolor="#000000">'.$totamt3.'</td>
        <td height="25" bordercolor="#000000">'.round($totcom3,2).'</td>
        <td height="25" bordercolor="#000000">'.round($totst3,2).'</td>
        <td height="25" bordercolor="#000000">'.round($totna3,2).'</td>
      </tr>';
	  $calrComm3=0;
			$serrTax3=0;
			$netrAmt3=0;
			$totrqty3=0;
			$totramt3=0;
			$totrcom3=0;
			$totrst3=0;
			$totrna3=0;
			$corTran3=count($TranCODrefundRES);
			for($i=0; $i < $corTran3; $i++)
			{
                      
			$calrComm3=($TranCODrefundRES[$i][Qty]*$TranCODrefundRES[$i][Fees])*($Commm3/100);
			if($TranCODrefundRES[$i][SignupDt] > '2012-03-31 23:59:59')
			{
			$serrTax3=$calrComm3*(14/100);
			}else{
			$serrTax3=$calrComm3*(10.3/100);
			}
			
			
	  $netrAmt3=($TranCODrefundRES[$i][Qty]*$TranCODrefundRES[$i][Fees])-$calrComm3-$serrTax3;
	  $totrqty3=$totrqty3+$TranCODrefundRES[$i]['qty_paid'];
	  $totramt3=$totramt3+($TranCODrefundRES[$i][Qty]*$TranCODrefundRES[$i][Fees]);
	  $totrcom3=$totrcom3+$calrComm3;
	  $totrst3=$totrst3+$serrTax3;
	  $totrna3=$totrna3+$netrAmt3;
			}
			
	  
     $data.=' <tr height="20">
        <td height="25" bordercolor="#000000" colspan=2>Refunds</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
         <td height="25" bordercolor="#000000">'.$totrqty3.'</td>
        <td height="25" bordercolor="#000000">'.$totramt3.'</td>
        <td height="25" bordercolor="#000000">'.round($totrcom3,2).'</td>
        <td height="25" bordercolor="#000000">'.round($totrst3,2).'</td>
        <td height="25" bordercolor="#000000">'.round($totrna3,2).'</td>
      </tr>
	   <tr height="20">
        <td height="25" bordercolor="#000000" colspan=3>Net to Pay (Net Amount-Refund Charges)</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">'.round(($totna3-($corTran3*40)-($totrcom3+$totrst3)),2).'</td>
      </tr>

	  </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>';
  
 }
   if($_REQUEST[Cheque]==1){
 $Commm1=$_REQUEST[Commm];

 
 $Cheque="SELECT s.eventid AS EventId, s.id AS Id, s.signupdate AS SignupDt, e.title AS Details, "
         . "s.quantity AS Qty, (s.totalamount/s.quantity) AS Fees, s.paymenttransactionid AS PaymentTransId, " 
         . "s.discount AS PromotionCode, s.paymentmodeid AS PaymentModeId, s.paymentstatus AS eChecked, "
         . "s.eventextrachargeamount AS Ccharge"
         . " FROM eventsignup AS s,chequepayments as ch, "
         . "event AS e "
         . "where s.eventid = e.id and s.id=ch.eventsignupid "
         . "AND s.PaymentModeId=3 "
         . "and s.eventid=".$_REQUEST['EventId'] . $SqDate ." "
         . "and s.paymentstatus!='Refunded' "
         . "order by s.signupdate"; 
$TranChequeRES = $Global->SelectQuery($Cheque);
 
 
 $data.=' <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><b>Cheque Transactions</b></td>
  </tr>
  <tr>
    <td><table width="100%" border="1" cellspacing="0" cellpadding="2">
      <tr height="35">
        <td height="25" width="112" align="left" nowrap="nowrap" bordercolor="#000000"><strong>ReceiptNo.</strong></td>
        <td height="25" width="150" align="left" nowrap="nowrap" bordercolor="#000000"><strong>PGIRef.No.</strong></td>
        <td height="25" width="150" align="left" nowrap="nowrap" bordercolor="#000000"><strong>DateofTxn</strong></td>
        <td height="25" width="80" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Qty</strong></td>
        <td height="25" width="112" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Amount<br/>(Rs)</strong></td>
        <td height="25" width="120" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Commission<br/>('.$Commm1.'%)</strong></td>
        <td height="25" width="120" align="left" nowrap="nowrap" bordercolor="#000000"><strong>ServiceTax<br/>@14%</strong></td>
        <td height="25" width="112" align="left" nowrap="nowrap" bordercolor="#000000"><strong>NetAmount</strong></td>
		
      </tr>';
	        $calComm1=0;
			$serTax1=0;
			$netAmt1=0;
			$totqty1=0;
			$totamt1=0;
			$totcom1=0;
			$totst1=0;
			$totna1=0;
			$coTran1=count($TranChequeRES);
			for($i=0; $i < $coTran1; $i++)
			{
                      
			$calComm1=($TranChequeRES[$i][Qty]*$TranChequeRES[$i][Fees])*($Commm1/100);
			if($TranChequeRES[$i][SignupDt] > '2012-03-31 23:59:59')
			{
			$serTax1=$calComm1*(14/100);
			}else{
			$serTax1=$calComm1*(10.3/100);
			}
			
			
			$netAmt1=($TranChequeRES[$i][Qty]*$TranChequeRES[$i][Fees])-$calComm1-$serTax1;
			
			
			 $sql="select chequenumber from chequepayments where eventsignupid=".$TranChequeRES[$i][Id]; 
			 $chkno = $Global->SelectQuery($sql);
			 $transid1=$chkno[0][ChqNo];
			
			  $data.='<tr>
        <td height="20" align="left" bordercolor="#000000">'.$TranChequeRES[$i][Id].'</td>
        <td align="left" bordercolor="#000000">'.$transid1.'</td>
        <td align="left" bordercolor="#000000">'.date("d/m/Y H:i",strtotime($TranChequeRES[$i][SignupDt])).'</td>
        <td align="left" bordercolor="#000000">'.$TranChequeRES[$i][Qty].'</td>
        <td align="left" bordercolor="#000000">'.($TranChequeRES[$i][Qty]*$TranChequeRES[$i][Fees]).'</td>
        <td align="left" bordercolor="#000000">'.round($calComm1,2).'</td>
        <td align="left" bordercolor="#000000">'.round($serTax1,2).'</td>
        <td align="left" bordercolor="#000000">'.round($netAmt1,2).'</td>
		
      </tr>';
	  $totqty1=$totqty1+$TranChequeRES[$i][Qty];
	  $totamt1=$totamt1+($TranChequeRES[$i][Qty]*$TranChequeRES[$i][Fees]);
	  $totcom1=$totcom1+$calComm1;
	  $totst1=$totst1+$serTax1;
	  $totna1=$totna1+$netAmt1;
			}
		
	  
      $data.=' 
      <tr height="20">
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">'.$totqty1.'</td>
        <td height="25" bordercolor="#000000">'.$totamt1.'</td>
        <td height="25" bordercolor="#000000">'.round($totcom1,2).'</td>
        <td height="25" bordercolor="#000000">'.round($totst1,2).'</td>
        <td height="25" bordercolor="#000000">'.round($totna1,2).'</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>';
  
 } 
 
 
 if($_REQUEST[payatcounter]==1){
  $Commm2 =  $Global->GetSingleFieldValue("select value AS Counter  from commission where eventid='".$_REQUEST['EventId']."' AND type = 2 AND deleted = 0 AND global = 0 AND deleted = 0 AND global = 0");
	  if($Commm2 =="" || $Commm2 ==0){
	  $Commm2=$_REQUEST[Commm];
	  }
 
$Counter="SELECT s.eventid AS EventId, s.id AS Id, s.signupdate AS SignupDt, e.title AS Details,"
        . " s.quantity AS Qty, (s.totalamount/s.quantity) AS Fees, s.paymenttransactionid AS PaymentTransId,"
        . "s.discount AS PromotionCode, s.paymentmodeid AS PaymentModeId, s.paymentstatus AS eChecked,"
        . "s.eventextrachargeamount AS Ccharge "
        . "FROM eventsignup AS s  "
        . "JOIN event AS e ON e.id=s.eventid "
        . "where s.discount='PayatCounter' "
        . "and s.eventid=".$_REQUEST['EventId'] . $SqDate ." "
        . "and s.paymentstatus!='Refunded' "
        . "and s.paymentstatus!='Canceled' "
        . "order by s.signupdate"; 
$TranCounterRES = $Global->SelectQuery($Counter);
 
 
 $data.=' <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><b>Counter Transactions</b></td>
  </tr>
  <tr>
    <td><table width="100%" border="1" cellspacing="0" cellpadding="2">
      <tr height="35">
        <td height="25" width="112" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Receipt No.</strong></td>
        <td height="25" width="150" align="left" nowrap="nowrap" bordercolor="#000000"><strong>PGIRef. No.</strong></td>
        <td height="25" width="150" align="left" nowrap="nowrap" bordercolor="#000000"><strong>DateofTxn</strong></td>
        <td height="25" width="80" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Qty</strong></td>
        <td height="25" width="92" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Amount<br/>(Rs)</strong></td>
        <td height="25" width="90" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Commission<br/>('.$Commm2.'%)</strong></td>
        <td height="25" width="90" align="left" nowrap="nowrap" bordercolor="#000000"><strong>ServiceTax<br/>@14%</strong></td>
        <td height="25" width="90" align="left" nowrap="nowrap" bordercolor="#000000"><strong>NetAmount</strong></td>
		
      </tr>';
	        $calComm2=0;
			$serTax2=0;
			$netAmt2=0;
			$totqty2=0;
			$totamt2=0;
			$totcom2=0;
			$totst2=0;
			$totna2=0;
			$coTran2=count($TranCounterRES);
			for($i=0; $i < $coTran2; $i++)
			{
                      
			$calComm2=($TranCounterRES[$i][Qty]*$TranCounterRES[$i][Fees])*($Commm2/100);
			
			if($TranCounterRES[$i][SignupDt] > '2012-03-31 23:59:59')
			{
			$serTax2=$calComm2*(14/100);
			}else{
			$serTax2=$calComm2*(10.3/100);
			}
			
			$netAmt2=($TranCounterRES[$i][Qty]*$TranCounterRES[$i][Fees])-$calComm2-$serTax2;
			
			
			
			 $transid2="PayatCounter";
			
			 $data.=' <tr>
        <td height="20" align="left" bordercolor="#000000">'.$TranCounterRES[$i][Id].'</td>
        <td align="left" bordercolor="#000000">'.$transid2.'</td>
        <td align="left" bordercolor="#000000">'.date("d/m/Y H:i",strtotime($TranCounterRES[$i][SignupDt])).'</td>
        <td align="left" bordercolor="#000000">'.$TranCounterRES[$i][Qty].'</td>
        <td align="left" bordercolor="#000000">'.($TranCounterRES[$i][Qty]*$TranCounterRES[$i][Fees]).'</td>
        <td align="left" bordercolor="#000000">'.round($calComm2,2).'</td>
        <td align="left" bordercolor="#000000">'.round($serTax2,2).'</td>
        <td align="left" bordercolor="#000000">'.round($netAmt2,2).'</td>
		 
      </tr>';
	  $totqty2=$totqty2+$TranCounterRES[$i][Qty];
	  $totamt2=$totamt2+($TranCounterRES[$i][Qty]*$TranCounterRES[$i][Fees]);
	  $totcom2=$totcom2+$calComm2;
	  $totst2=$totst2+$serTax2;
	  $totna2=$totna2+$netAmt2;
			}
		
	  
      $data.=' 
      <tr height="20">
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>

        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">'.$totqty2.'</td>
        <td height="25" bordercolor="#000000">'.$totamt2.'</td>
        <td height="25" bordercolor="#000000">'.round($totcom2,2).'</td>
        <td height="25" bordercolor="#000000">'.round($totst2,2).'</td>
        <td height="25" bordercolor="#000000">'.round($totna2,2).'</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>';
  
 }
 
 //print_r($grandTotalamt);
  $grandTotalamt['INR']+=round($totna2+$totna1+($totna3-($corTran3*40)-($totrcom3+$totrst3)),2);
 $data.='
  <tr>
    <td ><table width="100%" border="1" cellspacing="0" cellpadding="2">
      <tr height="35">
        <td height="25" width="112" colspan=3 align="left" nowrap="nowrap" bordercolor="#000000"><strong>Total Net Amount to Pay</strong></td>
        
        <td height="25" width="80" align="right" colspan=3 nowrap="nowrap" bordercolor="#000000"><strong>'.  $advice->totalStrWithCurrencies($grandTotalamt).'</strong></td>
       </tr></table></td>
      </tr>
   <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td><table width="100%" border="1" cellpadding="2" cellspacing="0">
            <col width="79" />
            <col width="128" />
            <tr height="20">
              <td height="25" colspan="2"><strong>Payment    Details:</strong></td>
              </tr>
            <tr height="20">
              <td width="116" height="25"><strong>Cheque No&nbsp;&nbsp;</strong></td>
              <td width="200" height="25">'.$Chqno.'</td>
            </tr>
            <tr height="20">
              <td height="25"><strong>Cheque Date</strong></td>
              <td height="25">'.$Chqdt.'</td>
            </tr>
            <tr height="20">
              <td height="25"><strong>Bank Name</strong></td>
              <td height="25">ICICI</td>
            </tr>
            <tr height="20">
              <td height="25"><strong>Branch</strong></td>
              <td height="25">Madhapur</td>
            </tr>
        </table></td>
       <td width="65%" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td align="right"><strong>Verified and Confirmed By</strong></td>
          </tr>
		   <tr>
            <td width="71%" height="50" align="right">&nbsp;</td>
            <td width="29%" align="right">&nbsp;</td>
          </tr>
		    <tr>
            <td width="71%" height="19" align="right"><strong>Signatory</strong></td>
            <td width="29%" align="right">&nbsp;</td>
          </tr>
		    <tr>
            <td width="90%" height="19" align="right"><strong>Please Process the Payment</strong></td>
            <td width="10%" align="right">&nbsp;</td>
          </tr>
        </table></td>      </tr>
     
      <tr>
        <td height="45" colspan="2" ><table width="100%" border="1" cellpadding="2" cellspacing="0">
        
          <tr height="20">
            <td height="25" colspan="2"><strong>Organizer Account   Details:</strong></td>
            </tr>
          <tr height="20">
            <td height="25" width="200"><strong>Beneficiary  Name</strong></td>
            <td width="900" height="25">'.$AccName.'</td>
            </tr>
          <tr height="20">
            <td height="25" width="200"><strong>Beneficiary Ac/No</strong></td>
            <td height="25" width="900">'.$Accno.'</td>
            </tr>
			<tr height="20">
            <td height="25" width="200"><strong>Account Type(SB/CA)</strong></td>
            <td height="25" width="900">'.$Acctype.'</td>
            </tr>
          <tr height="20" >
            <td width="200" height="25"><strong>Bank Name&nbsp;</strong></td>
            <td height="25" width="900">'.$BnkName.'</td>
            </tr>
          <tr height="20" >
            <td height="25" width="200"><strong>Bank Branch & Address</strong></td>
            <td height="25" width="900">'.$BnkBranch.'</td>
            </tr>
			 <tr height="20" >
            <td height="25" width="200"><strong>IFSC Code</strong></td>
            <td height="25" width="900">'.$IFCS.'</td>
            </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr></tr>
</table>
</body>
</html>
';
  $msg=$data; 
  $data = utf8_encode($data); 
 
   if($_REQUEST['export']=="ExportPaymentAdvice"){
   echo $data;
exit;
   }else{
//$mpdf->SetDisplayMode('fullpage');
//$mpdf->useOnlyCoreFonts = true; 
 //$mpdf->WriteHTML($data);
  //$pdf=$mpdf->Output();
   //$mpdf->Output($Title.'Advice.pdf','D');
   $subject="Payment Advice for the event : ".stripslashes($Title);
   
		 
		  $cc=$content=$filename=$bcc=$replyto=NULL;
		 
		 $from = 'MeraEvents<admin@meraevents.com>';
		$folder='ctrl';
		$commonFunctions->sendEmail('sales@meraevents.com',$cc,$bcc,$from,$replyto,$subject,$msg,$content,$filename,$folder);
		//mail('sales@meraevents.com', $subject, $msg, $headers);         
   }
 

   
}
	}
	
        $perc = $Global->GetSingleFieldValue("select percentage AS perc from eventsetting where eventid='" . $_REQUEST['EventId'] . "'");

        if ($_REQUEST['EventId'] != "") {
            $BankQuery = "select b.* from organizerbankdetail as b, event as e where e.ownerid=b.userid and e.id=" . $_REQUEST['EventId'];
            $BankQueryRES = $Global->SelectQuery($BankQuery);
        }

        $EventQuery = "SELECT distinct(s.eventid), e.title AS Details "
                . "FROM eventsignup AS s "
                . "INNER JOIN event AS e ON s.eventid = e.id "
                . "WHERE 1  AND ((s.paymentmodeid=1 and s.paymenttransactionid!='A1') or s.paymentmodeid=2) AND e.status=1 AND e.deleted =0 "
                . "ORDER BY e.id DESC";
        //$EventQueryRES = $Global->SelectQuery($EventQuery);

	//mysql_close();	
           }
    }
				 
	include 'templates/paymentInvoice_new_accounts.tpl.php';	
?>

