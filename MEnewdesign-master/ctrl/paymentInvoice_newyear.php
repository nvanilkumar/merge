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
	$eventOverAllPerc = $perc = $Global->GetSingleFieldValue("select percentage from eventsetting where eventid='" . $_REQUEST[EventId] . "'");
	if($_REQUEST['export']=="ExportPaymentAdvice" || $_REQUEST['download']=="EmailPaymentAdvice")
	{
	 if($_REQUEST[EventId]!=""){
       $sql="select e.title AS Title,u.company AS Company,u.email AS Email "
                 . "FROM event e, user u where e.ownerid=u.id and e.id=".$_REQUEST['EventId'];
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
       // if(count($ELCommCard)==0){
            $sqlComm1 = "select `type`,`value` from `commission`  where deleted = 0 AND global = 1";
        $ELCommmCard = $Global->SelectQuery($sqlComm1);
		     for ($i = 0; $i < count($ELCommmCard); $i++) {
//						if ($ELCommmCard[$i]['type'] == 1) {
//                            $EventLevelEbsComm = $ELCommmCard[$i]['value'];
//                        }
//
//                        if ($ELCommmCard[$i]['type'] == 3) {
//                            $EventLevelSpotRegistrationCounter = $ELCommmCard[$i]['value'];
//                        }
//                        if ($ELCommmCard[$i]['type'] == 4) {
//                            $EventLevelPaypalComm = $ELCommmCard[$i]['value'];
//                        }
//						if ($ELCommmCard[$i]['type'] == 11) {
//                            $EventLevelMEeffortComm = $ELCommmCard[$i]['value'];
//                        }
						if($ELCommmCard[$i]['type'] == 12){
	                    $ServiceTaxComm=$ELCommmCard[$i]['value'];
	                    }
                    }
       // }         
	  $Chqno=$_REQUEST[Chqno];
	  $Chqdt=$_REQUEST[Chqdt];
	  $AccName=$_REQUEST[AccName];
	  $Accno=$_REQUEST[Accno];
	  $BnkName=$_REQUEST[BnkName];
	  $BnkBranch=$_REQUEST[BnkBranch];
	  $Acctype=$_REQUEST[Acctype];
	  $IFCS=$_REQUEST[IFCS];
	  
	   /* if($_REQUEST[EventId]!=""){
	    $UserID =  $Global->GetSingleFieldValue("select UserID from events where Id='".$_REQUEST[EventId]."'");
		  $BankQuery="select * from orgbank  where UserID=".$UserID; //using 6/8 -pH
		  $BankQueryRES = $Global->SelectQuery($BankQuery);
		  if(count($BankQueryRES)>0){
		  $bankupdate="update orgbank set AccName='".$AccName."',BankName='".$BnkName."',AccNo='".$Accno."',AccType='".$Acctype."',Branch='". $BnkBranch."',IFCSCode='".$IFCS."' where UserID=".$UserID;
		 $Global->ExecuteQuery($bankupdate);
		  }else{
		   $bankupdate="insert into orgbank (UserID,AccName,BankName,AccNo,AccType,Branch,IFCSCode) values('".$UserID."','".$AccName."','".$BnkName."','".$Accno."','".$Acctype."','". $BnkBranch."','".$IFCS."')";
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
		
	
		
	   $transql="SELECT estd.ticketquantity As NumOfTickets,estd.amount As TicketAmt,s.eventid As EventId, s.id as Id, s.signupdate As SignupDt, e.title AS Details, s.quantity As Qty, (s.totalamount/s.quantity) As Fees, s.paymenttransactionid As PaymentTransId,s.discount As PromotionCode,s.paymentmodeid As PaymentModeId,s.paymentstatus As eChecked,s.eventextrachargeamount As Ccharge,s.discountamount As DAmount,c.code 'currencyCode',p.name As PaymentGateway FROM eventsignup AS s INNER JOIN eventsignupticketdetail estd ON estd.eventsignupid=s.id INNER JOIN event AS e ON s.eventid = e.id INNER JOIN currency c on s.fromcurrencyid=c.id INNER JOIN paymentgateway p on s.paymentgatewayid=p.id WHERE 1 AND (s.paymentmodeid=1 and s.paymenttransactionid != 'A1') and s.eventid=$_REQUEST[EventId] $SqDate and s.paymentstatus!='Refunded' and s.paymentstatus!='Canceled' order by s.id,s.signupdate"; 
	   $TranRES = $Global->SelectQuery($transql);
           $transByGateway= $advice->getCardTransByGateway($TranRES);
	   
      $transqlr="SELECT estd.ticketquantity As NumOfTickets,estd.amount As TicketAmt,s.eventid As EventId, s.id as Id, s.signupdate As SignupDt, e.title AS Details, s.quantity As Qty, (s.totalamount/s.quantity) As Fees, s.paymenttransactionid As PaymentTransId,s.discount As PromotionCode,s.paymentmodeid As PaymentModeId,s.paymentstatus As eChecked,s.eventextrachargeamount As Ccharge,s.discountamount As DAmount,c.code 'currencyCode',p.name As PaymentGateway FROM eventsignup AS s INNER JOIN eventsignupticketdetail estd ON estd.eventsignupid=s.id INNER JOIN event AS e ON s.eventid = e.id INNER JOIN currency c on s.fromcurrencyid=c.id INNER JOIN paymentgateway p on s.paymentgatewayid=p.id WHERE 1 AND (s.paymentmodeid=1 and s.paymenttransactionid != 'A1') and s.eventid=$_REQUEST[EventId] $SqDate and s.paymentstatus='Refunded'  order by s.id,s.signupdate"; 
	   $TranRESr = $Global->SelectQuery($transqlr);
	   $transrByGateway=$advice->getCardTransByGateway($TranRESr);
	   
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

        <td width="21%"><!--img  src="http://content.meraevents.com/images/VOS-logo.jpg" alt="Versant Online Solutions Private Limited" /--></td>
        <td width="63%" align="center"><div align="center">PAYMENT    ADVISE<br />
                <strong>MeraEvents Collections    &amp; Payment details</strong></div></td>
        <td width="16%"><img width="144" height="60" src="images/garland_dropmenu_logo (1).png" alt="Meraevents Logo" /></td>
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
        <td height="30" colspan="4"><strong>EventId# : </strong>'.$_REQUEST[EventId].'</td>
      </tr>

    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>';
         $grandTotalamt=NULL;
		 $section_total_qty = $refund_total_qty = 0;
        $section_totamt = $section_totcom = $section_totst = $section_totna = NULL;

         foreach($transByGateway as $Gatewayk=>$Gatewayv){
         $cardCommisionType = 0;
            if (strtolower($Gatewayk) == 'ebs' || strtolower($Gatewayk) == 'mobikwik' || strtolower($Gatewayk) == 'paytm') {
                $cardCommisionType = 1;
            }
     $TranRES=$Gatewayv;
     $comm=strcmp($Gatewayk,'paypal')==0?$paycomm:$Commm;
     $currencyType='';
  if(strcmp($Gatewayk, 'paypal')!=0){
      $currencyType='(INR)';
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
        <td height="25" width="90" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Commission<br/>('.$comm.'%)</strong></td>';
		 if($_REQUEST[incst]!=1){ 
       $data.=' <td height="25" width="90" align="left" nowrap="nowrap" bordercolor="#000000"><strong>ServiceTax<br/>@'.$ServiceTaxComm.'%</strong></td>';
		 }
		
        $data.='<td height="25" width="90" align="left" nowrap="nowrap" bordercolor="#000000"><strong>NetAmount</strong></td>
		 </tr>';
		 
		    $calComm=NULL;
			$serTax=NULL;
			$netAmt=NULL;
			$totqty=0;
			$totamt=NULL;
			$totcom=NULL;
			$totst=NULL;
			$totna=NULL;
                        $netToPay=NULL;
			$coTran=count($TranRES);
			for($i=0; $i < $coTran; $i++)
			{
                            $calComm[$TranRES[$i]['currencyCode']]=(($TranRES[$i][Qty]*$TranRES[$i][Fees])+$TranRES[$i][DAmount]-$TranRES[$i][Ccharge])*($comm/100);
             /*if($TranRES[$i][PaymentTransId]=="PayPalPayment"){  
			 $calComm[$TranRES[$i]['currencyCode']]=(($TranRES[$i][Qty]*$TranRES[$i][Fees])+$TranRES[$i][DAmount]-$TranRES[$i][Ccharge])*($paycomm/100);
			 }else{       
			$calComm[$TranRES[$i]['currencyCode']]=(($TranRES[$i][Qty]*$TranRES[$i][Fees])+$TranRES[$i][DAmount]-$TranRES[$i][Ccharge])*($comm/100);
			}*/
                        if($_REQUEST[incst]==1){
				$netAmt[$TranRES[$i]['currencyCode']]=(($TranRES[$i][Qty]*$TranRES[$i][Fees])+$TranRES[$i][DAmount]-$TranRES[$i][Ccharge])-$calComm[$TranRES[$i]['currencyCode']];
				$serTax[$TranRES[$i]['currencyCode']]=0;
			}else{
			/* if($TranRES[$i][SignupDt] > '2012-03-31 23:59:59')
			{
			$serTax[$TranRES[$i]['currencyCode']]=$calComm[$TranRES[$i]['currencyCode']]*($ServiceTaxComm/100);
			}else{
			$serTax[$TranRES[$i]['currencyCode']]=$calComm[$TranRES[$i]['currencyCode']]*(10.3/100);
			} */
			 $serTax[$TranRES[$i]['currencyCode']] = $calComm[$TranRES[$i]['currencyCode']] * ($commonFunctions->getServiceTax($Global,$TranRES[$i][SignupDt]) / 100);
			$netAmt[$TranRES[$i]['currencyCode']]=(($TranRES[$i][Qty]*$TranRES[$i][Fees])+$TranRES[$i][DAmount]-$TranRES[$i][Ccharge])-$calComm[$TranRES[$i]['currencyCode']]-$serTax[$TranRES[$i]['currencyCode']];
			}
			$transid="TR:".$TranRES[$i][PaymentTransId];
			
			 $data.=' <tr>
        <td height="20" align="left" bordercolor="#000000">'.$TranRES[$i][Id].'</td>
        <td align="left" bordercolor="#000000">'.$transid.'</td>
        <td align="left" bordercolor="#000000">'.date("d/m/Y H:i",strtotime($TranRES[$i][SignupDt])).'</td>
        <td align="left" bordercolor="#000000">'.$TranRES[$i]['qty_paid'].'</td>
        <td align="left" bordercolor="#000000">';
                         if(strcmp($Gatewayk, 'paypal')==0){
                             $data.=$TranRES[$i]['currencyCode'].' '.(($TranRES[$i][Qty]*$TranRES[$i][Fees])+$TranRES[$i][DAmount]-$TranRES[$i][Ccharge]);
                             }else{
                             
                                 $data.=(($TranRES[$i][Qty]*$TranRES[$i][Fees])+$TranRES[$i][DAmount]-$TranRES[$i][Ccharge]);
                             };
                         $data.='</td>
        <td align="left" bordercolor="#000000">'.round($calComm[$TranRES[$i]['currencyCode']],2).'</td>';
		if($_REQUEST[incst]!=1){
        $data.='<td align="left" bordercolor="#000000">'.round($serTax[$TranRES[$i]['currencyCode']],2).'</td>';
		}
       $data.=' <td align="left" bordercolor="#000000">'.round($netAmt[$TranRES[$i]['currencyCode']],2).'</td>
		
      </tr>';
	  $totqty=$totqty+$TranRES[$i]['qty_paid'];
	  $totamt[$TranRES[$i]['currencyCode']]+=(($TranRES[$i][Qty]*$TranRES[$i][Fees])+$TranRES[$i][DAmount]-$TranRES[$i][Ccharge]);
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
        <td height="25" bordercolor="#000000">'.  $advice->totalStrWithCurrencies($totcom).'</td>';
		if($_REQUEST[incst]!=1){
        $data.=' <td height="25" bordercolor="#000000">'.  $advice->totalStrWithCurrencies($totst).'</td>';
		}
       $data.='<td height="25" bordercolor="#000000">'.  $advice->totalStrWithCurrencies($totna).'</td>
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
		  else if(strcmp($Gatewayk,'spotcash')==0){
              $TranRESr=$transrByGateway['spotcash'];
          }
		  else if(strcmp($Gatewayk,'spotcard')==0){
              $TranRESr=$transrByGateway['spotcard'];
          }
	   $calCommr=NULL;
			$serTaxr=NULL;
			$netAmtr=NULL;
			$totqtyr=0;
			$totamtr=NULL;
			$totcomr=NULL;
			$totstr=NULL;
			$totnar=NULL;
                        $coTranr=count($TranRESr);
			for($i=0; $i < $coTranr; $i++)
			{
            if($TranRESr[$i][PaymentTransId]=="PayPalPayment"){ 
			$calCommr[$TranRESr[$i]['currencyCode']]=(($TranRESr[$i][Qty]*$TranRESr[$i][Fees])+$TranRESr[$i][DAmount]-$TranRESr[$i][Ccharge])*($paycomm/100);
			}else{          
			$calCommr[$TranRESr[$i]['currencyCode']]=(($TranRESr[$i][Qty]*$TranRESr[$i][Fees])+$TranRESr[$i][DAmount]-$TranRESr[$i][Ccharge])*($Commm/100);
			}
			if($_REQUEST[incst]==1){
				$serTaxr[$TranRESr[$i]['currencyCode']]=0;
				$netAmtr[$TranRESr[$i]['currencyCode']]=(($TranRESr[$i][Qty]*$TranRESr[$i][Fees])+$TranRESr[$i][DAmount]-$TranRESr[$i][Ccharge])-$calCommr[$TranRESr[$i]['currencyCode']]-$serTaxr[$TranRESr[$i]['currencyCode']];
				
			}else{
			/* if($TranRESr[$i][SignupDt] > '2012-03-31 23:59:59')
			{
			$serTaxr[$TranRESr[$i]['currencyCode']]=$calCommr[$TranRESr[$i]['currencyCode']]*($ServiceTaxComm/100);
			}else{
			$serTaxr[$TranRESr[$i]['currencyCode']]=$calCommr[$TranRESr[$i]['currencyCode']]*(10.3/100);
			} */
			 $serTaxr[$TranRESr[$i]['currencyCode']] = $calCommr[$TranRESr[$i]['currencyCode']] * ($commonFunctions->getServiceTax($Global,$TranRESr[$i][SignupDt]) / 100);
			$netAmtr[$TranRESr[$i]['currencyCode']]=(($TranRESr[$i][Qty]*$TranRESr[$i][Fees])+$TranRESr[$i][DAmount]-$TranRESr[$i][Ccharge])-$calCommr[$TranRESr[$i]['currencyCode']]-$serTaxr[$TranRESr[$i]['currencyCode']];
			}
			 $totqtyr=$totqtyr+$TranRESr[$i]['qty_paid'];
	  $totamtr[$TranRESr[$i]['currencyCode']]+=(($TranRESr[$i][Qty]*$TranRESr[$i][Fees])+$TranRESr[$i][DAmount]-$TranRESr[$i][Ccharge]);
	  $totcomr[$TranRESr[$i]['currencyCode']]+=$calCommr[$TranRESr[$i]['currencyCode']];
	  $totstr[$TranRESr[$i]['currencyCode']]+=$serTaxr[$TranRESr[$i]['currencyCode']];
	  $totnar[$TranRESr[$i]['currencyCode']]+=$netAmtr[$TranRESr[$i]['currencyCode']];
          $netToPay[$TranRESr[$i]['currencyCode']]-=($calCommr[$TranRESr[$i]['currencyCode']]+$serTaxr[$TranRESr[$i]['currencyCode']]);
          //$netToPay[$TranRESr[$i]['currencyCode']]-=$serTaxr[$TranRESr[$i]['currencyCode']];
          $grandTotalamt[$TranRESr[$i]['currencyCode']]-=($calCommr[$TranRESr[$i]['currencyCode']]+$serTaxr[$TranRESr[$i]['currencyCode']]);
	  }
	  
	  $data.=' <tr height="20">
        <td height="25" bordercolor="#000000" colspan=2>Refunds</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
       <td height="25" bordercolor="#000000">'.($totqtyr).'</td>
        <td height="25" bordercolor="#000000">'.$advice->totalStrWithCurrencies($totamtr).'</td>
        <td height="25" bordercolor="#000000">'.  $advice->totalStrWithCurrencies($totcomr).'</td>';
		if($_REQUEST[incst]!=1){
        $data.='<td height="25" bordercolor="#000000">'.  $advice->totalStrWithCurrencies($totstr).'</td>';
		}
         $data.='<td height="25" bordercolor="#000000">'.  $advice->totalStrWithCurrencies($totnar).'</td>
      </tr>
	    <tr height="20">
        <td height="25" bordercolor="#000000" colspan=3>Net to Pay (Net Amount-Refund Charges)</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>';
		if($_REQUEST[incst]!=1){
        $data.='<td height="25" bordercolor="#000000">&nbsp;</td>';
		}
         $data.='<td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">'.  $advice->totalStrWithCurrencies($netToPay).'</td>
      </tr>
    </table></td>
  </tr>';
         //$grandTotalamt+=round($totna-$totcomr-$totstr,2);
         //}//count>0
    }
       //  $grandTotalamt['INR']+=round($totna2+$totna1+($totna3-($corTran3*40)-($totrcom3+$totrst3)),2);
       //print_r($netToPay);
  $data.='<tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td ><table width="100%" border="1" cellspacing="0" cellpadding="2">
      <tr height="35">
        <td height="25" width="112" colspan=3 align="left" nowrap="nowrap" bordercolor="#000000"><strong>Total Net Amount to Pay</strong></td>
        
        <td height="25" width="80" align="right" colspan=3 nowrap="nowrap" bordercolor="#000000"><strong>'.$advice->totalStrWithCurrencies($grandTotalamt).'</strong></td>
       </tr></table></td>
      </tr>';
         
   $data.='<tr>
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
    
		 
		 $cc=$content=$filename=$replyto=NULL;
		 
		 $from = 'MeraEvents<admin@meraevents.com>';
		$bcc='qison@meraevents.com';
		$folder='ctrl';
		$commonFunctions->sendEmail('sales@meraevents.com',$cc,$bcc,$from,$replyto,$subject,$msg,$content,$filename,$folder);
		$_SESSION['PaymentAdviceMailSent']=true;
		 
		//mail('sales@meraevents.com', $subject, $msg, $headers);         
   }
 

   
}
	}
	
          //$perc =  $Global->GetSingleFieldValue("select perc from events where Id='".$_REQUEST[EventId]."'");
		  
		 if ($_REQUEST[EventId] != "") {
     $BankQuery = "select     b . *, e.title
                from organizerbankdetail as b,event as e
                where  e.ownerid = b.userid and e.id =" . $_REQUEST[EventId];
    $BankQueryRES = $Global->SelectQuery($BankQuery);
}
  	  /* $EventQuery = "SELECT distinct(s.EventId), e.Title FROM EventSignup AS s INNER JOIN events AS e ON s.EventId = e.Id  where 1  and ((s.PaymentModeId=1 and s.PaymentTransId!='A1') or s.PaymentModeId=2)  ORDER BY e.Id DESC"; 
		$EventQueryRES = $Global->SelectQuery($EventQuery); */
		
	mysql_close();	
	/*function getCardTransByGateway($TranRES){
           $countRes=  count($TranRES);
           $incEBS=$incPayPal=$incMobi=$incPaytm=$inc=0;
           $TranRESEBS=$TranRESPayPal=$TranRESMobikwik=$TranRESPaytm=array();
           for($k=0;$k<$countRes;$k++){
               switch ($TranRES[$k]['PaymentGateway']){
                   case 'EBS':$resArray='TranRESEBS';$inc=$incEBS++;break;
                   case 'PayPal':$resArray='TranRESPayPal';$inc=$incPayPal++;break;
                   case 'Mobikwik':$resArray='TranRESMobikwik';$inc=$incMobi++;break;
                   case 'Paytm':$resArray='TranRESPaytm';$inc=$incPaytm++;break;
default : echo 'no gateway';
        break;
               }
               ${$resArray}[$inc]['EventId']=$TranRES[$k]['EventId'];
               ${$resArray}[$inc]['Id']=$TranRES[$k]['Id'];
               ${$resArray}[$inc]['SignupDt']=$TranRES[$k]['SignupDt'];
               ${$resArray}[$inc]['Details']=$TranRES[$k]['Details'];
               ${$resArray}[$inc]['Qty']=$TranRES[$k]['Qty'];
               ${$resArray}[$inc]['Fees']=$TranRES[$k]['Fees'];
               ${$resArray}[$inc]['PaymentTransId']=$TranRES[$k]['PaymentTransId'];
               ${$resArray}[$inc]['PromotionCode']=$TranRES[$k]['PromotionCode'];
               ${$resArray}[$inc]['PaymentModeId']=$TranRES[$k]['PaymentModeId'];
               ${$resArray}[$inc]['eChecked']=$TranRES[$k]['eChecked'];
               ${$resArray}[$inc]['Ccharge']=$TranRES[$k]['Ccharge'];
               ${$resArray}[$inc]['currencyCode']=$TranRES[$k]['currencyCode'];
               //${$resArray}[$inc]['conversionRate']=$TranRES[$k]['conversionRate'];
               ${$resArray}[$inc]['PaymentGateway']=$TranRES[$k]['PaymentGateway']; 
               ${$resArray}[$inc]['DAmount']=$TranRES[$k]['DAmount']; 
               }
               $transByGateway=array('EBS'=>$TranRESEBS,'Paytm'=>$TranRESPaytm,'Mobikwik'=>$TranRESMobikwik,'PayPal'=>$TranRESPayPal);
               return $transByGateway;
      }	
      
      function $advice->totalStrWithCurrencies($totalArray){
                     $totalArrayStr='';
                     if(count($totalArray)>0){
                        ksort($totalArray);
                        $tot=NULL;
                        foreach($totalArray as $k=>$v){
                            if($v!=0){
                                $tot.=$k." ".round($v,2)."<br>";
                            }
                                
                        }
                            $totalArrayStr=$tot;
                     }
                     return strlen($totalArrayStr)>0?$totalArrayStr:0;
                 }*/
	include 'templates/paymentInvoice_newyear.tpl.php';	
?>

