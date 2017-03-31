<?php

	
	session_start();
	$uid =	$_SESSION['uid'];
	
	include 'loginchk.php';	
	
	include_once("MT/cGlobal.php");
	
	$Global = new cGlobal();

	
	if($_REQUEST['export']=="ExportPaymentAdvice" || $_REQUEST['download']=="DownloadPaymentAdvice")
	{
	 if($_REQUEST[EventId]!=""){
	 $sql="select e.Title,u.Company from events e,user u where e.UserID=u.Id and e.Id=$_REQUEST[EventId]";
	 $QueryRES = $Global->SelectQuery($sql);
	  $Organiser=$QueryRES[0][Company];
	  $Title=$QueryRES[0][Title];
	  $Commm=$_REQUEST[Commm];
	  $Chqno=$_REQUEST[Chqno];
	  $Chqdt=$_REQUEST[Chqdt];
	  $AccName=$_REQUEST[AccName];
	  $Accno=$_REQUEST[Accno];
	  $BnkName=$_REQUEST[BnkName];
	  $BnkBranch=$_REQUEST[BnkBranch];
	  $Acctype=$_REQUEST[Acctype];
	  $IFCS=$_REQUEST[IFCS];
	  
	   if($_REQUEST[EventId]!=""){
	      $UserID =  $Global->GetSingleFieldValue("select UserID from events where Id='".$_REQUEST[EventId]."'");
		  $BankQuery="select * from orgbank  where UserID=".$UserID;
		  $BankQueryRES = $Global->SelectQuery($BankQuery);
		  if(count($BankQueryRES)>0){
		  $bankupdate="update orgbank set AccName='".$AccName."',BankName='".$BnkName."',AccNo='".$Accno."',AccType='".$Acctype."',Branch='". $BnkBranch."',IFCSCode='".$IFCS."' where UserID=".$UserID;
		 $Global->ExecuteQuery($bankupdate);
		  }else{
		   $bankupdate="insert into orgbank (UserID,AccName,BankName,AccNo,AccType,Branch,IFCSCode) values('".$UserID."','".$AccName."','".$BnkName."','".$Accno."','".$Acctype."','". $BnkBranch."','".$IFCS."')";
		 $Global->ExecuteQuery($bankupdate);
		  }
		  }
	  
	  
	  if($_REQUEST['txtSDt']!="" && $_REQUEST['txtEDt']!=""){
	  $SDt = $_REQUEST['txtSDt'];
		$SDtExplode = explode("/", $SDt);
		$yesterdaySDate = $SDtExplode[2].'-'.$SDtExplode[1].'-'.$SDtExplode[0].' 00:00:00';
	
		
		$EDt = $_REQUEST['txtEDt'];
		$EDtExplode = explode("/", $EDt);
		$yesterdayEDate = $EDtExplode[2].'-'.$EDtExplode[1].'-'.$EDtExplode[0].' 23:59:59';
		$SqDate=" and s.SignupDt between '".$yesterdaySDate."' and '".$yesterdayEDate."'";
		}else{
		$SqDate="";
		}
		
		if($_REQUEST[payatcounter]!=1 && $_REQUEST[Cheque]!=1 && $_REQUEST[COD]!=1){
		 $payatc="AND (s.PaymentModeId=1 and s.PaymentTransId != 'A1') ";
		}
		if($_REQUEST[payatcounter]==1 && $_REQUEST[Cheque]!=1 && $_REQUEST[COD]!=1){
		 $payatc=" AND ((s.PaymentModeId=1 and s.PaymentTransId != 'A1') or s.PromotionCode='PayatCounter')";
		}else
		if($_REQUEST[payatcounter]!=1 && $_REQUEST[Cheque]==1 && $_REQUEST[COD]!=1){
		 $payatc=" AND ((s.PaymentModeId=1 and s.PaymentTransId != 'A1') or (s.PaymentModeId=2 and s.PromotionCode!='PayatCounter' and s.PromotionCode!='CashonDelivery'))";
		}else
		if($_REQUEST[payatcounter]!=1 && $_REQUEST[Cheque]!=1 && $_REQUEST[COD]==1){
		 $payatc=" AND ((s.PaymentModeId=1 and s.PaymentTransId != 'A1') or (s.PromotionCode='CashonDelivery'))";
		}else
		if($_REQUEST[Cheque]==1 && $_REQUEST[payatcounter]==1 && $_REQUEST[COD]!=1){
		 $payatc=" AND ((s.PaymentModeId=1 and s.PaymentTransId != 'A1') or (s.PaymentModeId=2 and s.PromotionCode!='CashonDelivery'))";
		}
		else
		if($_REQUEST[Cheque]!=1 && $_REQUEST[payatcounter]==1 && $_REQUEST[COD]==1){
		 $payatc=" AND ((s.PaymentModeId=1 and s.PaymentTransId != 'A1') or (PromotionCode='PayatCounter' or s.PromotionCode='CashonDelivery'))";
		}else
		if($_REQUEST[Cheque]==1 && $_REQUEST[payatcounter]!=1 && $_REQUEST[COD]==1){
		 $payatc=" AND ((s.PaymentModeId=1 and s.PaymentTransId != 'A1') or (s.PaymentModeId=2  and s.PromotionCode!='PayatCounter'))";
		}else
		if($_REQUEST[Cheque]==1 && $_REQUEST[payatcounter]==1 && $_REQUEST[COD]==1){
		 $payatc=" AND ((s.PaymentModeId=1 and s.PaymentTransId != 'A1') or (s.PaymentModeId=2 or s.PromotionCode='CashonDelivery' or s.PromotionCode='PayatCounter'))";
		}
		 $chkcod="";
		if($_REQUEST[COD]==1)
		{
		$sqlcod="select s.Id from EventSignup AS s,CODstatus as cs where s.Id=cs.EventSIgnupId and s.EventId=$_REQUEST[EventId] and cs.Status!='Delivered'";
		$codRES = $Global->SelectQuery($sqlcod);
		$csids="";
              $chkcod="";
              if(count($codRES)>0){
		for($i=0; $i < count($codRES); $i++)
		{
		$csids.=$codRES[$i]['Id'].",";
		}
		$csid=substr($csids,0,-1);
		 $chkcod=" and s.Id not in(".$csid.")";
               }
		}
		
	   $transql="SELECT s.EventId, s.Id, s.SignupDt, e.Title AS Details, s.Qty, s.Fees, s.PaymentTransId,s.PromotionCode,s.PaymentModeId FROM EventSignup AS s INNER JOIN events AS e ON s.EventId = e.Id WHERE 1 $payatc $chkcod and s.EventId=$_REQUEST[EventId] $SqDate and s.eChecked!='Refunded' order by s.SignupDt"; 
	   $TranRES = $Global->SelectQuery($transql);
			include("Extras/mpdf/mpdf.php");
         $mpdf=new mPDF();
     
         $data='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>MeraEvents - Signup Events</title>
<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.min.css.gz" rel="stylesheet" type="text/css" />
</head>
<body>
<table width="850" border="10" cellpadding="5" cellspacing="0" bordercolor="#CCCCCC" style="font-family:Arial, Helvetica, sans-serif; font-size:18px;">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td width="21%"><img  ="<?=_HTTP_CF_ROOT;?>/images/versant-logo.jpg" alt="Versent Logo" /></td>
        <td width="63%" align="center"><div align="center">PAYMENT    ADVISE<br />
                <strong>Mera Events Collections    &amp; Payment details</strong></div></td>
        <td width="16%"><img width="144" height="91" ="<?=_HTTP_CF_ROOT;?>/images/online-events-portal.jpg" alt="Meraevents Logo" /></td>
      </tr>
    </table></td>
  </tr>
  <tr></tr>
  <tr>
    <td><table width="100%" border="1" cellpadding="2" cellspacing="0">
      <tr height="21">
        <td width="500" height="30">Versant    Technologies Private Limited</td>
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
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table width="100%" border="1" cellspacing="0" cellpadding="2">
      <tr height="35">
        <td height="25" width="112" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Receipt No.</strong></td>
        <td height="25" width="150" align="left" nowrap="nowrap" bordercolor="#000000"><strong>PGIRef.No.</strong></td>
        <td height="25" width="150" align="left" nowrap="nowrap" bordercolor="#000000"><strong>DateofTxn</strong></td>
        <td height="25" width="60" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Qty</strong></td>
        <td height="25" width="90" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Amount<br/>(Rs)</strong></td>
        <td height="25" width="90" align="left" nowrap="nowrap" bordercolor="#000000"><strong>Commission<br/>('.$Commm.'%)</strong></td>
        <td height="25" width="90" align="left" nowrap="nowrap" bordercolor="#000000"><strong>ServiceTax<br/>@10.3%</strong></td>
        <td height="25" width="90" align="left" nowrap="nowrap" bordercolor="#000000"><strong>NetAmount</strong></td>
      </tr>';
	        $calComm=0;
			$serTax=0;
			$netAmt=0;
			$totqty=0;
			$totamt=0;
			$totcom=0;
			$totst=0;
			$totna=0;
			$coTran=count($TranRES);
			for($i=0; $i < $coTran; $i++)
			{
                      
			$calComm=($TranRES[$i][Qty]*$TranRES[$i][Fees])*($Commm/100);
			$serTax=$calComm*(10.3/100);
			$netAmt=($TranRES[$i][Qty]*$TranRES[$i][Fees])-$calComm-$serTax;
			
			if($TranRES[$i][PaymentTransId]!='A1'){
			$transid="TR:".$TranRES[$i][PaymentTransId];
			}else if($TranRES[$i][PaymentModeId]=2 && $TranRES[$i][PromotionCode] !='PayatCounter' && $TranRES[$i][PromotionCode] !='CashonDelivery'){
			 $sql="select ChqNo from ChqPmnts where EventSignupId=".$TranRES[$i][Id]; 
			 $chkno = $Global->SelectQuery($sql);
			 $transid=$chkno[0][ChqNo];
			} else if($TranRES[$i][PromotionCode] =='PayatCounter')
                     {
			$transid="PayatCounter";
			} else if($TranRES[$i][PromotionCode] =='CashonDelivery')
                     {
			$transid="CashonDelivery";
			}
			 $data.=' <tr>
        <td height="20" align="left" bordercolor="#000000">'.$TranRES[$i][Id].'</td>
        <td align="left" bordercolor="#000000">'.$transid.'</td>
        <td align="left" bordercolor="#000000">'.date("d/m/Y H:i",strtotime($TranRES[$i][SignupDt])).'</td>
        <td align="left" bordercolor="#000000">'.$TranRES[$i][Qty].'</td>
        <td align="left" bordercolor="#000000">'.$TranRES[$i][Qty]*$TranRES[$i][Fees].'</td>
        <td align="left" bordercolor="#000000">'.round($calComm,2).'</td>
        <td align="left" bordercolor="#000000">'.round($serTax,2).'</td>
        <td align="left" bordercolor="#000000">'.round($netAmt,2).'</td>
      </tr>';
	  $totqty=$totqty+$TranRES[$i][Qty];
	  $totamt=$totamt+($TranRES[$i][Qty]*$TranRES[$i][Fees]);
	  $totcom=$totcom+$calComm;
	  $totst=$totst+$serTax;
	  $totna=$totna+$netAmt;
			}
		
	  
      $data.=' 
      <tr height="20">
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">&nbsp;</td>
        <td height="25" bordercolor="#000000">'.$totqty.'</td>
        <td height="25" bordercolor="#000000">'.$totamt.'</td>
        <td height="25" bordercolor="#000000">'.round($totcom,2).'</td>
        <td height="25" bordercolor="#000000">'.round($totst,2).'</td>
        <td height="25" bordercolor="#000000">'.round($totna,2).'</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
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
              <td height="25">RBS</td>
            </tr>
            <tr height="20">
              <td height="25"><strong>Branch</strong></td>
              <td height="25">Pune</td>
            </tr>
        </table></td>
       <td width="65%" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td align="right"><strong>Verifed and Confirmed By</strong></td>
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
   $data = utf8_encode($data); 
   if($_REQUEST['export']=="ExportPaymentAdvice"){
   echo $data;
exit;
   }else{
//$mpdf->SetDisplayMode('fullpage');
//$mpdf->useOnlyCoreFonts = true; 
 $mpdf->WriteHTML($data);
  //$pdf=$mpdf->Output();
   $mpdf->Output($Title.'Advice.pdf','D');
   }
 

   
}
	}
	
          $perc =  $Global->GetSingleFieldValue("select perc from events where Id='".$_REQUEST[EventId]."'");
		  
		  if($_REQUEST[EventId]!=""){
		  $BankQuery="select b.* from orgbank as b,events as e where e.UserID=b.UserID and e.Id=".$_REQUEST[EventId];
		  $BankQueryRES = $Global->SelectQuery($BankQuery);
		  }
		  
  	  $EventQuery = "SELECT distinct(s.EventId), e.Title AS Details FROM EventSignup AS s INNER JOIN events AS e ON s.EventId = e.Id  where 1  and ((s.PaymentModeId=1 and s.PaymentTransId!='A1') or s.PaymentModeId=2)  ORDER BY e.Id DESC"; 
		$EventQueryRES = $Global->SelectQuery($EventQuery);
		
	//mysql_close();	
		
	include 'templates/paymentInvoice.tpl.php';	
?>

