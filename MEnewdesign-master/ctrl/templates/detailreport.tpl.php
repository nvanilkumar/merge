<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<title>MeraEvents - Detail Report for an Event</title>
		<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
		<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.js"></script>	
        <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.js"></script>	
        <link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.css" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.js"></script>
<script language="javascript">
	function SEdt_validate()
	{
		var strtdt = document.frmEofMonth.txtSDt.value;
		var enddt = document.frmEofMonth.txtEDt.value;
       if(strtdt != '' && enddt != '')
		{   
			var startdate=strtdt.split('/');
			var startdatecon=startdate[2] + '/' + startdate[1]+ '/' + startdate[0];
			
			var enddate=enddt.split('/');
			var enddatecon=enddate[2] + '/' + enddate[1]+ '/' + enddate[0];
			
			if(Date.parse(enddatecon) < Date.parse(startdatecon))
			{
				alert('End Date must be greater then Start Date.');
				document.frmEofMonth.txtEDt.focus();
				return false;
			}
		}
	}
	function uploaddoc(eid)
	{
	var strtdt = document.frmEofMonth.txtSDt.value;
		var enddt = document.frmEofMonth.txtEDt.value;
		var status = document.frmEofMonth.Status.value;
		var SerEventName = document.frmEofMonth.SerEventName.value;
		window.location="uploaddocs.php?EventId="+eid+"&SerEventName="+SerEventName+"&Status="+status+"&txtSDt="+strtdt+"&txtEDt="+enddt;
	}

function reload(val)
{
window.location="detailreport.php?EventId="+val;
}
	</script>
	</head>	
<body style="background-image: url(images/background.gif); background-repeat:repeat-x; margin-top: 0px; margin-left: 0px; margin-right:0px; padding:0px">
	<?php include('templates/header.tpl.php'); ?>				
</div>
	<table width="103%" cellpadding="0" cellspacing="0" style="width:100%; height:495px;">
  <tr>
	<td width="150" style="width:150px; vertical-align:top; background-image:url(images/menugradient.jpg); background-repeat:repeat-x">
		<?php include('templates/left.tpl.php'); ?>	</td>
	<td width="848" style="vertical-align:top">
    
	<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td><div align="center" style="width:100%"><a href="TransbyEvent_new.php?EventId=<?=$EventId;?>">Back to Gateway Transactions</a> <br/><?=$EventQueryBilldeskRES[0]['title'];?></div>
</td>
  </tr>
<tr><td colspan="3">Select an Event <select name="EventId" id="EventId" onchange="reload(this.value)" >
        <option value="">Select Event</option>
        <?
		$TotalEventQueryRES = count($EventQueryRES);

		for($i=0; $i < $TotalEventQueryRES; $i++)
		{
		?>
         <option value="<?=$EventQueryRES[$i]['eventid'];?>" <? if($EventQueryRES[$i]['eventid']==$_REQUEST[EventId]){?> selected="selected" <? }?>><?=$EventQueryRES[$i]['Details'];?></option>
         <? }?>
      </select></td></tr>

  <tr>
    <td><div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
	
	
<!-------------------------------ADD CONTENT PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans4').style.display='block';
</script>

<?php
    $total_amount_temp = 0;
    //for net amount commissions
    $ebs_comm=$paypal_comm=$mobikwik_comm=$paytm_comm=$cod_comm=2.37;
//getting global commission values
	$sqlComm1="select value from `commission` where global = 1 and type =1";
	$recComm1=$Global->SelectQuery($sqlComm1);	
	$EbsComm=$recComm1[0]['value'];
	$sqlComm2="select value from `commission` where global = 1 and type =2";
	$recComm2=$Global->SelectQuery($sqlComm2);	
	$CodComm=$recComm2[0]['value'];
	$sqlComm3="select value from `commission` where global = 1 and type =3";
	$recComm3=$Global->SelectQuery($sqlComm3);	
	$CounterComm=$recComm3[0]['value'];
	$sqlComm4="select value from `commission` where global = 1 and type =4";
	$recComm4=$Global->SelectQuery($sqlComm4);	
	$PaypalComm=$recComm4[0]['value'];	
	$sqlComm5="select value from `commission` where global = 1 and type =5";
	$recComm5=$Global->SelectQuery($sqlComm5);	
	$MobikwikComm=$recComm5[0]['value'];	
	$sqlComm6="select value from `commission` where global = 1 and type =6";
	$recComm6=$Global->SelectQuery($sqlComm6);	
	$PaytmComm=$recComm6[0]['value'];
	
	/*$ServiceTaxComm=$recComm[0]['ServiceTax'];
	$MEeffortComm=$recComm[0]['MEeffort'];
	$PaytmComm=$recComm[0]['Paytm']; */
	//getting global commission values
	
	  //checking overall value
        $overallComm=$Global->GetSingleFieldValue("SELECT perc FROM events WHERE Id='".$_REQUEST['EventId']."'"); 
        if($overallComm>0){
              $EbsComm=$CodComm=$CounterComm=$PaypalComm=$MobikwikComm=$PaytmComm=$MEeffortComm=$overallComm;
        }else{
          // event level default values 
          $ELCommmCard =  $Global->SelectQuery("select Card,Paypal,Mobikwik,Paytm,Cod,Counter,MEeffort from commsion where EventId='".$EventId."'");
          if(count($ELCommmCard)>0){
              //ebs
              if(!empty($ELCommmCard[0]['Card']) && $ELCommmCard[0]['Card']>0)
                  $EbsComm=$ELCommmCard[0]['Card'];
              //cod
              if(!empty($ELCommmCard[0]['Cod']) && $ELCommmCard[0]['Cod']>0)
                  $CodComm=$ELCommmCard[0]['Cod'];
              //counter
              if(!empty($ELCommmCard[0]['Counter']) && $ELCommmCard[0]['Counter']>0)
                  $CounterComm=$ELCommmCard[0]['Counter'];
              //paypal
              if(!empty($ELCommmCard[0]['Paypal']) && $ELCommmCard[0]['Paypal']>0)
                  $PaypalComm=$ELCommmCard[0]['Paypal'];
              //mobikwik
              if(!empty($ELCommmCard[0]['Mobikwik']) && $ELCommmCard[0]['Mobikwik'])
                  $MobikwikComm=$ELCommmCard[0]['Mobikwik'];
              //paytm
              if(!empty($ELCommmCard[0]['Paytm']) && $ELCommmCard[0]['Paytm']>0)
                  $PaytmComm=$ELCommmCard[0]['Paytm'];
              //MEeffort effort
              if(!empty($ELCommmCard[0]['MEeffort']) && $ELCommmCard[0]['MEeffort']>0)
                  $MEeffortComm=$ELCommmCard[0]['MEeffort'];
          }
        }
?>

<!------------------------------------------BillDesk Payment--------------------------------------------------------------------->
<?php
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
		
			$CountQueryt = "SELECT  Id, SignupDt, Qty, Fees FROM EventSignup  where 1  AND (Fees != 0 AND (PaymentModeId=1 AND PaymentTransId != 'A1' AND PaymentTransId !='PayPalPayment')) AND PaymentGateway='Billdesk' and EventId= $EventId "; 
	 $CountQueryRESt=$Global->SelectQuery($CountQueryt); 
	 for($j = 0; $j < count($CountQueryRESt); $j++)
	{ 
	 $Totalreg=$Totalreg+1;
	$Totalqty=$Totalqty+$CountQueryRESt[$j][Qty];
	$TotaltAmountcard=$TotaltAmountcard+($CountQueryRESt[$j][Qty]*$CountQueryRESt[$j][Fees]);
	
	}		

	$CountQueryr = "SELECT  Id, SignupDt, Qty, Fees FROM EventSignup  where 1  AND (Fees != 0 AND (PaymentModeId=1 AND PaymentTransId != 'A1' AND PaymentTransId !='PayPalPayment')) AND PaymentGateway='Billdesk'  and eChecked = 'Refunded' and EventId=$EventId "; 
	 $CountQueryRESr=$Global->SelectQuery($CountQueryr); 
	 for($j = 0; $j < count($CountQueryRESr); $j++)
	{ 
	$TotalrAmountcard=$TotalrAmountcard+($CountQueryRESr[$j][Qty]*$CountQueryRESr[$j][Fees]);
	}
	
	$r=3.75+(3.75*0.1236);
       
    
	
	$CountQuery = "SELECT  Id, SignupDt, Qty, Fees FROM EventSignup  where 1  AND (Fees != 0 AND (PaymentModeId=1 AND PaymentTransId != 'A1' AND PaymentTransId !='PayPalPayment')) AND PaymentGateway='Billdesk'  and eChecked != 'Refunded' and EventId=$EventId"; 
	 $CountQueryRES=$Global->SelectQuery($CountQuery); 
	 for($j = 0; $j < count($CountQueryRES); $j++)
	{ 
	$TotalAmountcard=$TotalAmountcard+($CountQueryRES[$j][Qty]*$CountQueryRES[$j][Fees]);
	
	}
	
	 $CountQuery1 = "SELECT  Id, SignupDt, Qty, Fees FROM EventSignup  where 1  AND (Fees != 0 AND (PaymentModeId=1 AND PaymentTransId != 'A1' AND PaymentTransId !='PayPalPayment')) AND PaymentGateway='Billdesk' and eChecked = 'Verified' and EventId=$EventId"; 
	 $CountQueryRES1=$Global->SelectQuery($CountQuery1); 
	 for($j = 0; $j < count($CountQueryRES1); $j++)
	{ 
	
	$TotalAmountverified=round($TotalAmountverified+($CountQueryRES1[$j][Qty]*$CountQueryRES1[$j][Fees]),2);
	
	}
	
	    $TotalNetverified=round($TotalAmountverified-($TotalAmountverified*0.0353),2);
	   
		
		
	
     
		  
		
$perc=$Global->GetSingleFieldValue("select Card from commsion where EventId=$EventId");
if($perc==0 || $perc==""){
$perc=$Global->GetSingleFieldValue("select perc from events where Id=$EventId");
if($perc==0 || $perc==""){
             $perc=3.75;
		    $p=3.75+(3.75*0.1236);
            	 $tobepaid=round($TotalAmountcard-($TotalAmountcard*($p/100)),2);
				 } else{
				 $p=$perc+($perc*0.1236);
                $tobepaid=round($TotalAmountcard-($TotalAmountcard*($p/100)),2);
				 }
		}else{
		 $p=$perc+($perc*0.1236);
                $tobepaid=round($TotalAmountcard-($TotalAmountcard*($p/100)),2);
		}
		$totrev=$TotalNetverified-$tobepaid;
                
		 $refundchrg=round($TotalrAmountcard*($p/100),2);

?>
<div><h3>BillDesk Payments with <?=$perc;?>% commision</h3></div>
<table width='100%' border='1' cellpadding='0' cellspacing='0' >
			<thead>
            <tr bgcolor='#94D2F3'>
		  	
			
         
            <td class='tblinner' valign='middle' width='4%' align='center'>Reg No.</td>
            <td class='tblinner' valign='middle' width='5%' align='center'>Qty</td>
            <td class='tblinner' valign='middle' width='9%' align='center'>Total Trans Amount </td>
            <td class='tblinner' valign='middle' width='8%' align='center'>Refunded Amount </td>
             <td class='tblinner' valign='middle' width='6%' align='center'>Trans Amount </td>
             <td class='tblinner' valign='middle' width='6%' align='center'>Verified Amount </td>
             <td class='tblinner' valign='middle' width='6%' align='center'>Net Amount </td>
             <td class='tblinner' valign='middle' width='7%' align='center'>Refund Charges </td>
              <td class='tblinner' valign='middle' width='7%' align='center'>Revenue </td>
              <td class='tblinner' valign='middle' width='6%' align='center'>Amount </td> 
               <td class='tblinner' valign='middle' width='6%' align='center'>Amount to Pay </td>            
             
            
            
          </tr>
        </thead>
        
        
      
		<tr>
			
		
			<td class='tblinner' valign='middle' width='4%' align='center'><font color='#000000'><?=$Totalreg;?></font></td>     		
			<td class='tblinner' valign='middle' width='5%' align='center'><font color='#000000'><?=$Totalqty;?></font></td>
			<td class='tblinner' valign='middle' width='9%' align='center'><font color='#000000'> <?=$TotaltAmountcard;?></font></td>
            <td class='tblinner' valign='middle' width='8%' align='center'><font color='#000000'> <?=$TotalrAmountcard;?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$TotalAmountcard;?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$TotalAmountverified;?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$TotalNetverified;?></font></td>
              <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$refundchrg;?></font></td>
            <td class='tblinner' valign='middle' width='7%' align='center'><font color='#000000'> <?=$totrev;?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$tobepaid;?></font></td>
             <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$tobepaid-$refundchrg;?></font></td>
        
           
            
          </tr>
        
	
  

</table>

<!------------------------------------------End of BillDesk Payment--------------------------------------->

<br/><br/>



<!------------------------------------------EBS Payment--------------------------------------------------------------------->
<?php
$gateways=array('EBS','PayPal','Mobikwik','Paytm');
$cardTotal=array();
foreach($gateways as $gatewayk=>$gatewayv){	
	$cardTrans=array();
        //all trans
        $GCountQueryt = "SELECT  Id, SignupDt, Qty, Fees, ucode FROM EventSignup  where 1  AND (Fees != 0 AND (PaymentModeId=1 AND PaymentTransId != 'A1' AND PaymentTransId !='PayPalPayment')) AND PaymentGateway='".$gatewayv."' and eChecked!='Canceled' and EventId= $EventId "; 
        $GCountQueryRESt=$Global->SelectQuery($GCountQueryt); 
        for($j = 0; $j < count($GCountQueryRESt); $j++){ 
		if(empty($GCountQueryRESt[$j]['ucode'])){
                    $cardTrans['MEeffort']['GTotalreg']+=1;
                    $cardTrans['MEeffort']['GTotalqty']+=$GCountQueryRESt[$j]['Qty'];
                    $cardTrans['MEeffort']['GTotaltAmountcard']+=($GCountQueryRESt[$j][Qty]*$GCountQueryRESt[$j][Fees]);
                }else{
                    $cardTrans['Org Sales']['GTotalreg']+=1;
                    $cardTrans['Org Sales']['GTotalqty']+=$GCountQueryRESt[$j]['Qty'];
                    $cardTrans['Org Sales']['GTotaltAmountcard']+=($GCountQueryRESt[$j][Qty]*$GCountQueryRESt[$j][Fees]);
		}
	}		
        
        //refund trans
	$GCountQueryr = "SELECT  Id, SignupDt, Qty, Fees, ucode FROM EventSignup  where 1  AND (Fees != 0 AND (PaymentModeId=1 AND PaymentTransId != 'A1' AND PaymentTransId !='PayPalPayment')) AND PaymentGateway='".$gatewayv."'   and eChecked = 'Refunded' and EventId=$EventId "; 
        $GCountQueryRESr=$Global->SelectQuery($GCountQueryr); 
        for($j = 0; $j < count($GCountQueryRESr); $j++){ 
		if(empty($GCountQueryRESr[$j]['ucode'])){
                    $cardTrans['MEeffort']['GTotalrAmountcard']+=($GCountQueryRESr[$j][Qty]*$GCountQueryRESr[$j][Fees]);
		}else{
                    $cardTrans['Org Sales']['GTotalrAmountcard']+=($GCountQueryRESr[$j][Qty]*$GCountQueryRESr[$j][Fees]);
		}
	}
	
        //trans amt
	$GCountQuery = "SELECT  Id, SignupDt, Qty, Fees, ucode FROM EventSignup  where 1  AND (Fees != 0 AND (PaymentModeId=1 AND PaymentTransId != 'A1' AND PaymentTransId !='PayPalPayment')) AND PaymentGateway='".$gatewayv."' and eChecked!='Canceled'  and eChecked != 'Refunded' and EventId=$EventId"; 
        $GCountQueryRES=$Global->SelectQuery($GCountQuery); 
        for($j = 0; $j < count($GCountQueryRES); $j++){ 
		if(empty($GCountQueryRES[$j]['ucode'])){
                    $cardTrans['MEeffort']['GTotalAmountcard']+=($GCountQueryRES[$j][Qty]*$GCountQueryRES[$j][Fees]);
		}
		else{
                    $cardTrans['Org Sales']['GTotalAmountcard']+=($GCountQueryRES[$j][Qty]*$GCountQueryRES[$j][Fees]);
		}
	}
	
        //verified amt
        $GCountQuery1 = "SELECT  Id, SignupDt, Qty, Fees, ucode FROM EventSignup  where 1  AND (Fees != 0 AND (PaymentModeId=1 AND PaymentTransId != 'A1' AND PaymentTransId !='PayPalPayment')) AND PaymentGateway='".$gatewayv."' and eChecked = 'Verified' and EventId=$EventId"; 
        $GCountQueryRES1=$Global->SelectQuery($GCountQuery1); 
        for($j = 0; $j < count($GCountQueryRES1); $j++){ 
            if(empty($GCountQueryRES1[$j]['ucode'])){
                $cardTrans['MEeffort']['GTotalAmountverified']+=round($GCountQueryRES1[$j][Qty]*$GCountQueryRES1[$j][Fees],2);
            }else{
                $cardTrans['Org Sales']['GTotalAmountverified']+=round($GCountQueryRES1[$j][Qty]*$GCountQueryRES1[$j][Fees],2);
            }
	}
	
       $GpMESales=$MEeffortComm+($MEeffortComm*($ServiceTaxComm/100));
        switch (strtolower($gatewayv)){
            case 'ebs':$comm=$EbsComm;$G_comm=$ebs_comm;break;
            case 'paypal':$comm=$PaypalComm;$G_comm=$paypal_comm;break;
            case 'mobikwik':$comm=$MobikwikComm;$G_comm=$mobikwik_comm;break;
            case 'paytm':$comm=$PaytmComm;$G_comm=$paytm_comm;break;
            default :echo strtolower($gatewayv);
        break;
        }
        $cardTrans['MEeffort']['GTotalNetverified']=  round($cardTrans['MEeffort']['GTotalAmountverified']-($cardTrans['MEeffort']['GTotalAmountverified']*($G_comm/100)),2);
        $cardTrans['Org Sales']['GTotalNetverified']=  round($cardTrans['Org Sales']['GTotalAmountverified']-($cardTrans['Org Sales']['GTotalAmountverified']*($G_comm/100)),2);
$GpOrgSales=$comm+($comm*($ServiceTaxComm/100));	
$cardTrans['MEeffort']['Gtobepaid']=round($cardTrans['MEeffort']['GTotalAmountcard']-($cardTrans['MEeffort']['GTotalAmountcard']*($GpMESales/100)),2);	
$cardTrans['Org Sales']['Gtobepaid']=round($cardTrans['Org Sales']['GTotalAmountcard']-($cardTrans['Org Sales']['GTotalAmountcard']*($GpOrgSales/100)),2);	
//$Gtobepaid=round($GTotalAmountcard-($GTotalAmountcard*($Gp/100)),2);


		
        //$Gtotrev=$GTotalNetverified-$Gtobepaid;
		//$Grefundchrg=round($GTotalrAmountcard*($Gp/100),2);
$cardTrans['MEeffort']['Gtotrev']=round($cardTrans['MEeffort']['GTotalNetverified']-$cardTrans['MEeffort']['Gtobepaid'],2);	
$cardTrans['Org Sales']['Gtotrev']=round($cardTrans['Org Sales']['GTotalNetverified']-$cardTrans['Org Sales']['Gtobepaid'],2);
		
$cardTrans['MEeffort']['Grefundchrg']=round($cardTrans['MEeffort']['GTotalrAmountcard']*($GpMESales/100),2);	
$cardTrans['Org Sales']['Grefundchrg']=round($cardTrans['Org Sales']['GTotalrAmountcard']*($GpOrgSales/100),2);
			

?>
<div><h3><?=$gatewayv;?> Payments with <?=$MEeffortComm;?>% commission for MeraEvents sales,<?=$comm;?>% for Organizer sales</h3></div>
<table width='100%' border='1' cellpadding='0' cellspacing='0' >
			<thead>
            <tr bgcolor='#94D2F3'>
		  	
			
            <td class='tblinner' valign='middle' width='4%' align='center'>Sales Type</td>
            <td class='tblinner' valign='middle' width='4%' align='center'>Reg No.</td>
            <td class='tblinner' valign='middle' width='5%' align='center'>Qty</td>
            <td class='tblinner' valign='middle' width='9%' align='center'>Total Trans Amount </td>
            <td class='tblinner' valign='middle' width='8%' align='center'>Refunded Amount </td>
             <td class='tblinner' valign='middle' width='6%' align='center'>Trans Amount </td>
             <td class='tblinner' valign='middle' width='6%' align='center'>Verified Amount </td>
             <td class='tblinner' valign='middle' width='6%' align='center'>Net Amount </td>
             <td class='tblinner' valign='middle' width='7%' align='center'>Refund Charges </td>
              <td class='tblinner' valign='middle' width='7%' align='center'>Revenue </td>
              <td class='tblinner' valign='middle' width='6%' align='center'>Amount </td> 
               <td class='tblinner' valign='middle' width='6%' align='center'>Amount to Pay </td>            
             
            
            
          </tr>
        </thead>
        
        
      <?php 
ksort($cardTrans);
      foreach ($cardTrans as $sType=>$values){ ?>
		<tr>
                    <td class='tblinner' valign='middle' width='4%' align='center'><font color='#000000'><?=$sType;?></font></td> 
			<td class='tblinner' valign='middle' width='4%' align='center'><font color='#000000'><?php if(empty($values['GTotalreg'])) echo '0'; else echo $values['GTotalreg'];?></font></td>     		
			<td class='tblinner' valign='middle' width='5%' align='center'><font color='#000000'><?php if(empty($values['GTotalqty'])) echo '0'; else echo $values['GTotalqty'];?></font></td>
			<td class='tblinner' valign='middle' width='9%' align='center'><font color='#000000'> <?php if(empty($values['GTotaltAmountcard'])) echo '0'; else echo $values['GTotaltAmountcard'];?></font></td>
            <td class='tblinner' valign='middle' width='8%' align='center'><font color='#000000'> <?php if(empty($values['GTotalrAmountcard'])) echo '0'; else echo $values['GTotalrAmountcard'];?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?php if(empty($values['GTotalAmountcard'])) echo '0'; else echo $values['GTotalAmountcard'];?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?php if(empty($values['GTotalAmountverified'])) echo '0'; else echo $values['GTotalAmountverified'];?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?php if(empty($values['GTotalNetverified'])) echo '0'; else echo $values['GTotalNetverified'];?></font></td>
              <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?php if(empty($values['Grefundchrg'])){ $values['Grefundchrg']=0;echo '0';} else echo $values['Grefundchrg'];?></font></td>
            <td class='tblinner' valign='middle' width='7%' align='center'><font color='#000000'> <?php if(empty($values['Gtotrev'])) echo '0'; else echo $values['Gtotrev'];?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?php if(empty($values['Gtobepaid'])){ $values['Gtobepaid']=0;echo '0';} else echo $values['Gtobepaid'];?></font></td>
             <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$values['Gtobepaid']-$values['Grefundchrg'];?></font></td>          
          </tr>
        
    <?php 
             $cardTrans['org_me_Totalreg']+=$values['GTotalreg'];
             $cardTrans['org_me_Totalqty']+=$values['GTotalqty'];
             $cardTrans['org_me_TottAmtCard']+=$values['GTotaltAmountcard'];
             $cardTrans['org_me_TotrAmtCard']+=$values['GTotalrAmountcard'];
             $cardTrans['org_me_TotAmtCard']+=$values['GTotalAmountcard'];
             $cardTrans['org_me_TotVer']+=$values['GTotalAmountverified'];
             $cardTrans['org_me_TotalNVer']+=$values['GTotalNetverified'];
             $cardTrans['org_me_Totrc']+=$values['Grefundchrg'];
             $cardTrans['org_me_Totrev']+=$values['Gtotrev'];
             $cardTrans['org_me_TotTopay']+=$values['Gtobepaid'];
             $cardTrans['org_me_TotAmtMinusRc']+=$values['Gtobepaid']-$values['Grefundchrg'];
            }
    ?>
            <tr  style="font-weight: bold;">
                <td class='tblinner' valign='middle' width='4%' align='center'><font color='#000000'>Total</font></td> 
			<td class='tblinner' valign='middle' width='4%' align='center'><font color='#000000'><?=$cardTrans['org_me_Totalreg'];?></font></td>     		
			<td class='tblinner' valign='middle' width='5%' align='center'><font color='#000000'><?=$cardTrans['org_me_Totalqty'];?></font></td>
			<td class='tblinner' valign='middle' width='9%' align='center'><font color='#000000'> <?=$cardTrans['org_me_TottAmtCard'];?></font></td>
            <td class='tblinner' valign='middle' width='8%' align='center'><font color='#000000'> <?=$cardTrans['org_me_TotrAmtCard'];?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$cardTrans['org_me_TotAmtCard'];?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$cardTrans['org_me_TotVer'];?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$cardTrans['org_me_TotalNVer'];?></font></td>
              <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$cardTrans['org_me_Totrc'];?></font></td>
            <td class='tblinner' valign='middle' width='7%' align='center'><font color='#000000'> <?=$cardTrans['org_me_Totrev'];?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$cardTrans['org_me_TotTopay'];?></font></td>
             <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$cardTrans['org_me_TotAmtMinusRc'];?></font></td>          
          </tr>
</table>

<!------------------------------------------End of EBS Payment--------------------------------------->
<br/><br/>
<? //print_r($cardTrans);//exit; ?>

<? 
    $cardTotal['GateTotalreg']+=$cardTrans['MEeffort']['GTotalreg']+$cardTrans['Org Sales']['GTotalreg'];
    $cardTotal['GateTotalqty']+=$cardTrans['MEeffort']['GTotalqty']+$cardTrans['Org Sales']['GTotalqty'];
    $cardTotal['GateTotalAmountcard']+=$cardTrans['MEeffort']['GTotalAmountcard']+$cardTrans['Org Sales']['GTotalAmountcard'];
    $cardTotal['GateTotalAmountverified']+=$cardTrans['MEeffort']['GTotalAmountverified']+$cardTrans['Org Sales']['GTotalAmountverified'];
    $cardTotal['GateTotalNetverified']+=$cardTrans['MEeffort']['GTotalNetverified']+$cardTrans['Org Sales']['GTotalNetverified'];
    $cardTotal['Gaterefundchrg']+=$cardTrans['MEeffort']['Grefundchrg']+$cardTrans['Org Sales']['Grefundchrg'];
    $cardTotal['Gatetotrev']+=$cardTrans['MEeffort']['Gtotrev']+$cardTrans['Org Sales']['Gtotrev'];
    $cardTotal['Gatetobepaid']+=$cardTrans['MEeffort']['Gtobepaid']+$cardTrans['Org Sales']['Gtobepaid'];
    $cardTotal['GateAmtToPay']+=$cardTrans['MEeffort']['Gtobepaid']+$cardTrans['Org Sales']['Gtobepaid']-$cardTrans['MEeffort']['Grefundchrg']-$cardTrans['Org Sales']['Grefundchrg'];
}


?>
<!------------------------------------------PayPal Payment--------------------------------------------------------------------->
<?php




/*$PayTotaltAmountcard=0;
		 $PayTotalAmountcard=0;
		 $PayTotalrAmountcard=0;
		$PayTotalAmountverified=0;
		$PayTotalNetverified=0;
		$PayTotalreg=0;
		$PayTotalqty=0;
              $Paynetamt=0;
		$Paytotrev=0;
		$Paytobepaid=0;
		
	$PayCountQueryt = "SELECT  Id, SignupDt, Qty, Fees FROM EventSignup  where 1  AND Fees != 0 AND PaymentTransId ='PayPalPayment'  and EventId= $EventId "; 
	 $PayCountQueryRESt=$Global->SelectQuery($PayCountQueryt); 
	 for($j = 0; $j < count($PayCountQueryRESt); $j++)
	{ 
	 $PayTotalreg=$PayTotalreg+1;
	$PayTotalqty=$PayTotalqty+$PayCountQueryRESt[$j][Qty];
	$PayTotaltAmountcard=$PayTotaltAmountcard+($PayCountQueryRESt[$j][Qty]*$PayCountQueryRESt[$j][Fees]);
	
	}		

	$PayCountQueryr = "SELECT  Id, SignupDt, Qty, Fees FROM EventSignup  where 1  AND Fees != 0  AND PaymentTransId ='PayPalPayment'  and eChecked = 'Refunded' and EventId=$EventId "; 
	 $PayCountQueryRESr=$Global->SelectQuery($PayCountQueryr); 
	 for($j = 0; $j < count($PayCountQueryRESr); $j++)
	{ 
	$PayTotalrAmountcard=$PayTotalrAmountcard+($PayCountQueryRESr[$j][Qty]*$PayCountQueryRESr[$j][Fees]);
	}
	
	
	
	$PayCountQuery = "SELECT  Id, SignupDt, Qty, Fees FROM EventSignup  where 1  AND Fees != 0 AND PaymentTransId ='PayPalPayment' and eChecked != 'Refunded' and EventId=$EventId"; 
	 $PayCountQueryRES=$Global->SelectQuery($PayCountQuery); 
	 for($j = 0; $j < count($PayCountQueryRES); $j++)
	{ 
	$PayTotalAmountcard=$PayTotalAmountcard+($PayCountQueryRES[$j][Qty]*$PayCountQueryRES[$j][Fees]);
	
	}
	
	$PayCountQuery1 = "SELECT  Id, SignupDt, Qty, Fees FROM EventSignup  where 1  AND Fees != 0  AND PaymentTransId ='PayPalPayment'  and eChecked = 'Verified' and EventId=$EventId"; 
	 $PayCountQueryRES1=$Global->SelectQuery($PayCountQuery1); 
	 for($j = 0; $j < count($PayCountQueryRES1); $j++)
	{ 
	
	$PayTotalAmountverified=round($PayTotalAmountverified+($PayCountQueryRES1[$j][Qty]*$PayCountQueryRES1[$j][Fees]),2);
	
	}
	
	    $PayTotalNetverified=round($PayTotalAmountverified-($PayTotalAmountverified*0.08824),2);
     
		  
		
$Payperc=$Global->GetSingleFieldValue("select Paypal from commsion where EventId=$EventId");
if($Payperc==0 || $Payperc==""){
 
           $Payperc=5;
		    $Payp=5+(5*0.1236);
            	  $Paytobepaid=round($PayTotalAmountcard-($PayTotalAmountcard*($Payp/100)),2);
				 
		}else{
		 $Payp=$Payperc+($Payperc*0.1236);
                $Paytobepaid=round($PayTotalAmountcard-($PayTotalAmountcard*($Payp/100)),2);
		}
             
		$Paytotrev=$PayTotalAmountverified-$Paytobepaid;
		
       
     $Payrefundchrg=round($PayTotalrAmountcard*($Payp/100),2);

?>
<div><h3>PayPal Payments with <?=$Payperc;?>% commision</h3></div>
<table width='100%' border='1' cellpadding='0' cellspacing='0' >
			<thead>
            <tr bgcolor='#94D2F3'>
		  	
			
         
            <td class='tblinner' valign='middle' width='4%' align='center'>Reg No.</td>
            <td class='tblinner' valign='middle' width='5%' align='center'>Qty</td>
            <td class='tblinner' valign='middle' width='9%' align='center'>Total Trans Amount </td>
            <td class='tblinner' valign='middle' width='8%' align='center'>Refunded Amount </td>
             <td class='tblinner' valign='middle' width='6%' align='center'>Trans Amount </td>
             <td class='tblinner' valign='middle' width='6%' align='center'>Verified Amount </td>
             <td class='tblinner' valign='middle' width='6%' align='center'>Net Amount </td>
             <td class='tblinner' valign='middle' width='7%' align='center'>Refund Charges </td>
              <td class='tblinner' valign='middle' width='7%' align='center'>Revenue </td>
              <td class='tblinner' valign='middle' width='6%' align='center'>Amount </td> 
               <td class='tblinner' valign='middle' width='6%' align='center'>Amount to Pay </td>            
             
            
            
          </tr>
        </thead>
        
        
      
		<tr>
			
		
			<td class='tblinner' valign='middle' width='4%' align='center'><font color='#000000'><?=$PayTotalreg;?></font></td>     		
			<td class='tblinner' valign='middle' width='5%' align='center'><font color='#000000'><?=$PayTotalqty;?></font></td>
			<td class='tblinner' valign='middle' width='9%' align='center'><font color='#000000'> <?=$PayTotaltAmountcard;?></font></td>
            <td class='tblinner' valign='middle' width='8%' align='center'><font color='#000000'> <?=$PayTotalrAmountcard;?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$PayTotalAmountcard;?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$PayTotalAmountverified;?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$PayTotalNetverified;?></font></td>
              <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$Payrefundchrg;?></font></td>
            <td class='tblinner' valign='middle' width='7%' align='center'><font color='#000000'> <?=$Paytotrev;?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$Paytobepaid;?></font></td>
             <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$Paytobepaid-$Payrefundchrg;?></font></td>
        
           
            
          </tr>
        
	
  

</table>

<!------------------------------------------End of PayPal Payment--------------------------------------->

<br/><br/>




<!------------------------------------------COD Payment--------------------------------------------------------------------->
<? */

$CODRES=NULL;		
	$CODCountQueryt = "SELECT  s.Id, s.SignupDt, s.Qty, s.Fees,s.ucode FROM  EventSignup AS s , events AS e where  s.EventId = e.Id   AND (s.Fees != 0 AND (s.PaymentGateway = 'CashonDelivery' ))   and s.EventId= $EventId"; 
	 $CODCountQueryRESt=$Global->SelectQuery($CODCountQueryt); 
	 for($j = 0; $j < count($CODCountQueryRESt); $j++)
	{ 
            if(!empty($CODCountQueryRESt[0]['ucode'])){
                $CODRES['Org Sales']['CODTotalreg']+=1;
                $CODRES['Org Sales']['CODTotalqty']+=$CODCountQueryRESt[$j][Qty];
                $CODRES['Org Sales']['CODTotaltAmountcard']+=($CODCountQueryRESt[$j][Qty]*$CODCountQueryRESt[$j][Fees]);
            }else{
                $CODRES['MEeffort']['CODTotalreg']+=1;
                $CODRES['MEeffort']['CODTotalqty']+=$CODCountQueryRESt[$j][Qty];
                $CODRES['MEeffort']['CODTotaltAmountcard']+=($CODCountQueryRESt[$j][Qty]*$CODCountQueryRESt[$j][Fees]);
            }
	}		

	$CODCountQueryr = "SELECT  s.Id, s.SignupDt, s.Qty, s.Fees,s.ucode FROM  EventSignup AS s , events AS e where  s.EventId = e.Id   AND (s.Fees != 0 AND (s.PaymentGateway = 'CashonDelivery' ))   and s.eChecked = 'Refunded' and s.EventId=$EventId "; 
	 $CODCountQueryRESr=$Global->SelectQuery($CODCountQueryr); 
	 for($j = 0; $j < count($CODCountQueryRESr); $j++)
	{ 
            if(!empty($CODCountQueryr[$j]['ucode'])){
                $CODRES['Org Sales']['CODTotalrAmountcard']+=($CODCountQueryRESr[$j][Qty]*$CODCountQueryRESr[$j][Fees]);
            }else{
               $CODRES['MEeffort']['CODTotalrAmountcard']+=($CODCountQueryRESr[$j][Qty]*$CODCountQueryRESr[$j][Fees]);
            }
        }
	
	
       $CODt=count($CODCountQueryRESr);
    
	
	$CODCountQuery = "SELECT  s.Id, s.SignupDt, s.Qty, s.Fees,s.ucode FROM  EventSignup AS s , events AS e where  s.EventId = e.Id   AND (s.Fees != 0 AND (s.PaymentGateway = 'CashonDelivery' ))  and s.eChecked != 'Refunded' and s.EventId=$EventId"; 
	 $CODCountQueryRES=$Global->SelectQuery($CODCountQuery); 
	 for($j = 0; $j < count($CODCountQueryRES); $j++)
	{ 
            if(!empty($CODCountQueryRES[$j]['ucode']))
                $CODRES['Org Sales']['CODTotalAmountcard']+=($CODCountQueryRES[$j][Qty]*$CODCountQueryRES[$j][Fees]);
            else {
                $CODRES['MEeffort']['CODTotalAmountcard']+=($CODCountQueryRES[$j][Qty]*$CODCountQueryRES[$j][Fees]);
            }
	}
	
	 $CODCountQuery1 = "SELECT  s.Id, s.SignupDt, s.Qty, s.Fees,s.ucode FROM  EventSignup AS s , events AS e where  s.EventId = e.Id  AND (s.Fees != 0 AND (s.PaymentGateway = 'CashonDelivery' ))  and s.eChecked = 'Verified' and s.EventId=$EventId"; 
	 $CODCountQueryRES1=$Global->SelectQuery($CODCountQuery1); 
	 for($j = 0; $j < count($CODCountQueryRES1); $j++)
	{ 
            if(!empty($CODCountQueryRES1[$j]['ucode']))
                $CODRES['Org Sales']['CODTotalAmountverified']=round($CODTotalAmountverified['Org Sales']+($CODCountQueryRES1[$j][Qty]*$CODCountQueryRES1[$j][Fees]),2);
            else
                $CODRES['MEeffort']['CODTotalAmountverified']=round($CODTotalAmountverified['MEeffort']+($CODCountQueryRES1[$j][Qty]*$CODCountQueryRES1[$j][Fees]),2);
	}

    $CODp=$CodComm+($CodComm*($ServiceTaxComm/100));
    $CODRES['Org Sales']['CODtobepaid']=round($CODRES['Org Sales']['CODTotalAmountverified']-($CODRES['Org Sales']['CODTotalAmountverified']*($CODp/100)),2);
    $CODRES['Org Sales']['CODtotrev']=$CODRES['Org Sales']['CODTotalAmountverified']-$CODRES['Org Sales']['CODtobepaid'];
    $CODRES['Org Sales']['CODrefundchrg']=$CODt*40+($CODRES['Org Sales']['CODTotalrAmountcard']*($CODp/100));
    
    $COD_me_effort=$MEeffortComm+($MEeffortComm*($ServiceTaxComm/100));
    $CODRES['MEeffort']['CODtobepaid']=round($CODRES['MEeffort']['CODTotalAmountverified']-($CODRES['MEeffort']['CODTotalAmountverified']*($COD_me_effort/100)),2);
    $CODRES['MEeffort']['CODtotrev']=$CODRES['MEeffort']['CODTotalAmountverified']-$CODRES['MEeffort']['CODtobepaid'];
    $CODRES['MEeffort']['CODrefundchrg']=$CODt*40+($CODRES['MEeffort']['CODTotalrAmountcard']*($COD_me_effort/100));

?>
<div><h3>COD Payments with <?=$MEeffortComm?>% commission for MeraEvents sales,<?=$CodComm;?>% for Organizer Sales</h3></div>
<table width='100%' border='1' cellpadding='0' cellspacing='0' >
			<thead>
            <tr bgcolor='#94D2F3'>
		  	
			
            <td class='tblinner' valign='middle' width='4%' align='center'>Sales Type</td>
            <td class='tblinner' valign='middle' width='4%' align='center'>Reg No.</td>
            <td class='tblinner' valign='middle' width='5%' align='center'>Qty</td>
            <td class='tblinner' valign='middle' width='9%' align='center'>Total Trans Amount </td>
            <td class='tblinner' valign='middle' width='8%' align='center'>Refunded Amount </td>
             <td class='tblinner' valign='middle' width='6%' align='center'>Trans Amount </td>
             <td class='tblinner' valign='middle' width='6%' align='center'>Verified Amount </td>
             <td class='tblinner' valign='middle' width='6%' align='center'>Net Amount </td>
             <td class='tblinner' valign='middle' width='7%' align='center'>Refund Charges </td>
              <td class='tblinner' valign='middle' width='7%' align='center'>Revenue </td>
              <td class='tblinner' valign='middle' width='6%' align='center'>Amount </td> 
               <td class='tblinner' valign='middle' width='6%' align='center'>Amount to Pay </td>            
             
            
            
          </tr>
        </thead>
        
        <?php foreach ($CODRES as $sType=>$values){ ?>
      
		<tr>
			<td class='tblinner' valign='middle' width='4%' align='center'><font color='#000000'><?=$sType;?></font></td>     
		
                        <td class='tblinner' valign='middle' width='4%' align='center'><font color='#000000'><?php if(empty($values['CODTotalreg'])) $values['CODTotalreg']=0; echo $values['CODTotalreg'];?></font></td>     		
                        <td class='tblinner' valign='middle' width='5%' align='center'><font color='#000000'><?php if(empty($values['CODTotalqty'])) $values['CODTotalqty']=0; echo $values['CODTotalqty'];?></font></td>
			<td class='tblinner' valign='middle' width='9%' align='center'><font color='#000000'> <?php if(empty($values['CODTotaltAmountcard'])) $values['CODTotaltAmountcard']=0; echo $values['CODTotaltAmountcard']?></font></td>
            <td class='tblinner' valign='middle' width='8%' align='center'><font color='#000000'> <?php if(empty($values['CODTotalrAmountcard'])) $values['CODTotalrAmountcard']=0; echo $values['CODTotalrAmountcard'];;?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?php if(empty($values['CODTotalAmountcard'])) $values['CODTotalAmountcard']=0; echo $values['CODTotalAmountcard'];?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?php if(empty($values['CODTotalAmountverified'])) $values['CODTotalAmountverified']=0; echo $values['CODTotalAmountverified'];?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?php if(empty($values['CODTotalAmountverified'])) $values['CODTotalAmountverified']=0; echo $values['CODTotalAmountverified'];?></font></td>
              <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?php if(empty($values['CODrefundchrg'])) $values['CODrefundchrg']=0; echo $values['CODrefundchrg'];?></font></td>
            <td class='tblinner' valign='middle' width='7%' align='center'><font color='#000000'> <?php if(empty($values['CODtotrev'])) $values['CODtotrev']=0; echo $values['CODtotrev'];?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?php if(empty($values['$CODtobepaid'])) $values['$CODtobepaid']=0; echo $values['$CODtobepaid'];?></font></td>
            <?php $values['CODAmtToPay']=$values['CODtobepaid']-$values['CODrefundchrg'];?>
             <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$values['CODAmtToPay'];?></font></td>
        </tr>
        
	<?php 
             $CODRES['org_me_Totalreg']+=$values['CODTotalreg'];
             $CODRES['org_me_Totalqty']+=$values['CODTotalqty'];
             $CODRES['org_me_TottAmtCard']+=$values['CODTotaltAmountcard'];
             $CODRES['org_me_TotrAmtCard']+=$values['CODTotalrAmountcard'];
             $CODRES['org_me_TotAmtCard']+=$values['CODTotalAmountcard'];
             $CODRES['org_me_TotVer']+=$values['CODTotalAmountverified'];
             $CODRES['org_me_TotalNVer']+=$values['CODTotalNetverified'];
             $CODRES['org_me_Totrc']+=$values['CODrefundchrg'];
             $CODRES['org_me_Totrev']+=$values['CODtotrev'];
             $CODRES['org_me_TotTopay']+=$values['CODtobepaid'];
             $CODRES['org_me_TotAmtMinusRc']+=$CODRES['org_me_TotTopay']-$CODRES['org_me_Totrc'];
        } ?>
    <tr  style="font-weight: bold;">
                <td class='tblinner' valign='middle' width='4%' align='center'><font color='#000000'>Total</font></td> 
			<td class='tblinner' valign='middle' width='4%' align='center'><font color='#000000'><?=$CODRES['org_me_Totalreg'];?></font></td>     		
			<td class='tblinner' valign='middle' width='5%' align='center'><font color='#000000'><?=$CODRES['org_me_Totalqty'];?></font></td>
			<td class='tblinner' valign='middle' width='9%' align='center'><font color='#000000'> <?=$CODRES['org_me_TottAmtCard'];?></font></td>
            <td class='tblinner' valign='middle' width='8%' align='center'><font color='#000000'> <?=$CODRES['org_me_TotrAmtCard'];?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$CODRES['org_me_TotAmtCard'];?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$CODRES['org_me_TotVer'];?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$CODRES['org_me_TotalNVer'];?></font></td>
              <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$CODRES['org_me_Totrc'];?></font></td>
            <td class='tblinner' valign='middle' width='7%' align='center'><font color='#000000'> <?=$CODRES['org_me_Totrev'];?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$CODRES['org_me_TotTopay'];?></font></td>
             <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$CODRES['org_me_TotAmtMinusRc'];?></font></td>          
          </tr>

</table>

<!------------------------------------------End of COD Payment--------------------------------------->



<br/><br/>

<!------------------------------------------Mobikwik Payment--------------------------------------------------------------------->
<? /*
$mobikwikComm=5;
$MobikwikTotaltAmountcard=0;
		 $MobikwikTotalAmountcard=0;
		 $MobikwikTotalrAmountcard=0;
		$MobikwikTotalAmountverified=0;
		$MobikwikTotalNetverified=0;
		$MobikwikTotalreg=0;
		$MobikwikTotalqty=0;
              $Mobikwiknetamt=0;
		$Mobikwiktotrev=0;
		$Mobikwiktobepaid=0;
		
			$MobikwikCountQueryt = "SELECT  Id, SignupDt, Qty, Fees FROM EventSignup  where 1  AND (Fees != 0 AND (PaymentModeId=1 AND PaymentTransId != 'A1')) AND PaymentGateway='Mobikwik' and eChecked!='Canceled' and EventId= $EventId "; 
	 $MobikwikCountQueryRESt=$Global->SelectQuery($MobikwikCountQueryt); 
	 for($j = 0; $j < count($MobikwikCountQueryRESt); $j++)
	{ 
	 $MobikwikTotalreg=$MobikwikTotalreg+1;
	$MobikwikTotalqty=$MobikwikTotalqty+$MobikwikCountQueryRESt[$j][Qty];
	$MobikwikTotaltAmountcard=$MobikwikTotaltAmountcard+($MobikwikCountQueryRESt[$j][Qty]*$MobikwikCountQueryRESt[$j][Fees]);
	
	}		

	$MobikwikCountQueryr = "SELECT  Id, SignupDt, Qty, Fees FROM EventSignup  where 1  AND (Fees != 0 AND (PaymentModeId=1 AND PaymentTransId != 'A1')) AND PaymentGateway='Mobikwik'   and eChecked = 'Refunded' and EventId=$EventId "; 
	 $MobikwikCountQueryRESr=$Global->SelectQuery($MobikwikCountQueryr); 
	 for($j = 0; $j < count($MobikwikCountQueryRESr); $j++)
	{ 
	$MobikwikTotalrAmountcard=$MobikwikTotalrAmountcard+($MobikwikCountQueryRESr[$j][Qty]*$MobikwikCountQueryRESr[$j][Fees]);
	}
	
	//$EBSr=3.75+(3.75*0.1236); -not in use msJ
       
     
	
	$MobikwikCountQuery = "SELECT  Id, SignupDt, Qty, Fees FROM EventSignup  where 1  AND (Fees != 0 AND (PaymentModeId=1 AND PaymentTransId != 'A1')) AND PaymentGateway='Mobikwik' and eChecked!='Canceled'  and eChecked != 'Refunded' and EventId=$EventId"; 
	 $MobikwikCountQueryRES=$Global->SelectQuery($MobikwikCountQuery); 
	 for($j = 0; $j < count($MobikwikCountQueryRES); $j++)
	{ 
	$MobikwikTotalAmountcard=$MobikwikTotalAmountcard+($MobikwikCountQueryRES[$j][Qty]*$MobikwikCountQueryRES[$j][Fees]);
	
	}
	
	 $MobikwikCountQuery1 = "SELECT  Id, SignupDt, Qty, Fees FROM EventSignup  where 1  AND (Fees != 0 AND (PaymentModeId=1 AND PaymentTransId != 'A1')) AND PaymentGateway='Mobikwik' and eChecked = 'Verified' and EventId=$EventId"; 
	 $MobikwikCountQueryRES1=$Global->SelectQuery($MobikwikCountQuery1); 
	 for($j = 0; $j < count($MobikwikCountQueryRES1); $j++)
	{ 
	
	$MobikwikTotalAmountverified=round($MobikwikTotalAmountverified+($MobikwikCountQueryRES1[$j][Qty]*$MobikwikCountQueryRES1[$j][Fees]),2);
	
	}
	
	    $MobikwikTotalNetverified=round($MobikwikTotalAmountverified-($MobikwikTotalAmountverified*0.0237),2);
	   
		
		
	
     
		  
$Mobikwikperc=$Global->GetSingleFieldValue("select perc from events where Id=$EventId");
    if($Mobikwikperc==0 || $Mobikwikperc==""){
        $Mobikwikperc=$mobikwikComm;
        $Mobikwikp=$mobikwikComm+($mobikwikComm*0.1236);
    }else{
        $Mobikwikp=$Mobikwikperc+($Mobikwikperc*0.1236);
    }
$Mobikwiktobepaid=round($MobikwikTotalAmountcard-($MobikwikTotalAmountcard*($Mobikwikp/100)),2);
$Mobikwiktotrev=$MobikwikTotalNetverified-$Mobikwiktobepaid;
$Mobikwikrefundchrg=round($MobikwikTotalrAmountcard*($Mobikwikp/100),2);

?>
<div><h3>Mobikwik Payments with <?=$Mobikwikperc;?>% commision</h3></div>
<table width='100%' border='1' cellpadding='0' cellspacing='0' >
			<thead>
            <tr bgcolor='#94D2F3'>
		  	
			
         
            <td class='tblinner' valign='middle' width='4%' align='center'>Reg No.</td>
            <td class='tblinner' valign='middle' width='5%' align='center'>Qty</td>
            <td class='tblinner' valign='middle' width='9%' align='center'>Total Trans Amount </td>
            <td class='tblinner' valign='middle' width='8%' align='center'>Refunded Amount </td>
             <td class='tblinner' valign='middle' width='6%' align='center'>Trans Amount </td>
             <td class='tblinner' valign='middle' width='6%' align='center'>Verified Amount </td>
             <td class='tblinner' valign='middle' width='6%' align='center'>Net Amount </td>
             <td class='tblinner' valign='middle' width='7%' align='center'>Refund Charges </td>
              <td class='tblinner' valign='middle' width='7%' align='center'>Revenue </td>
              <td class='tblinner' valign='middle' width='6%' align='center'>Amount </td> 
               <td class='tblinner' valign='middle' width='6%' align='center'>Amount to Pay </td>            
             
            
            
          </tr>
        </thead>
        
        
      
		<tr>
			
		
			<td class='tblinner' valign='middle' width='4%' align='center'><font color='#000000'><?=$MobikwikTotalreg;?></font></td>     		
			<td class='tblinner' valign='middle' width='5%' align='center'><font color='#000000'><?=$MobikwikTotalqty;?></font></td>
			<td class='tblinner' valign='middle' width='9%' align='center'><font color='#000000'> <?=$MobikwikTotaltAmountcard;?></font></td>
            <td class='tblinner' valign='middle' width='8%' align='center'><font color='#000000'> <?=$MobikwikTotalrAmountcard;?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$MobikwikTotalAmountcard;?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$MobikwikTotalAmountverified;?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$MobikwikTotalNetverified;?></font></td>
              <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$Mobikwikrefundchrg;?></font></td>
            <td class='tblinner' valign='middle' width='7%' align='center'><font color='#000000'> <?=$Mobikwiktotrev;?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$Mobikwiktobepaid;?></font></td>
             <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?=$Mobikwiktobepaid-$Mobikwikrefundchrg;?></font></td>
        
           
            
          </tr>
        
	
  

</table>

<!------------------------------------------End of Mobikwik Payment--------------------------------------->
<br/><br/>


<!------------------------------------------PayatCounter Payment--------------------------------------------------------------------->
<? */
$CounterTotaltAmountcard=0;
		 $CounterTotalAmountcard=0;
		 $CounterTotalrAmountcard=0;
		$CounterTotalAmountverified=0;
		$CounterTotalNetverified=0;
		$CounterTotalreg=0;
		$CounterTotalqty=0;
        $Counternetamt=0;
		$Countertotrev=0;
		$Countertobepaid=0;
		
                $CounterCountQueryt = "SELECT s.Id, s.SignupDt, s.Qty, s.Fees
                                       FROM EventSignup AS s, events AS e
                                       where
                                          s.EventId = e.Id
                                        AND (s.Fees != 0
                                        AND (s.PaymentTransId = 'SpotCash'
                                        or s.PaymentTransId = 'SpotCard')) and s.eChecked= 'Verified'
                                        and s.EventId =" . $EventId;
         $CounterCountQueryRESt=$Global->SelectQuery($CounterCountQueryt); 
	 for($j = 0; $j < count($CounterCountQueryRESt); $j++)
	{ 
	 $CounterTotalreg=$CounterTotalreg+1;
	$CounterTotalqty=$CounterTotalqty+$CounterCountQueryRESt[$j][Qty];
	$CounterTotaltAmountcard=$CounterTotaltAmountcard+($CounterCountQueryRESt[$j][Qty]*$CounterCountQueryRESt[$j][Fees]);
	
	}		

//	$CounterCountQueryr = "SELECT  s.Id, s.SignupDt, s.Qty, s.Fees FROM  EventSignup AS s , events AS e where  s.EventId = e.Id   AND (s.Fees != 0 AND (s.PromotionCode = 'PayatCounter' or s.PromotionCode = 'Y' ))   and s.eChecked = 'Refunded' and s.EventId=$EventId "; 
//	 $CounterCountQueryRESr=$Global->SelectQuery($CounterCountQueryr); 
//	 for($j = 0; $j < count($CounterCountQueryRESr); $j++)
//	{ 
//	$CounterTotalrAmountcard=$CounterTotalrAmountcard+($CounterCountQueryRESr[$j][Qty]*$CounterCountQueryRESr[$j][Fees]);
//	}
	$CounterTotalrAmountcard=0;//No refund for counter transactions
	
      
    
	
//	$CounterCountQuery = "SELECT  s.Id, s.SignupDt, s.Qty, s.Fees FROM  EventSignup AS s , events AS e where  s.EventId = e.Id   AND (s.Fees != 0 AND (s.PromotionCode = 'PayatCounter' or s.PromotionCode = 'Y' ))  and s.eChecked != 'Refunded' and s.EventId=$EventId"; 
//	 $CounterCountQueryRES=$Global->SelectQuery($CounterCountQuery); 
//	 for($j = 0; $j < count($CounterCountQueryRES); $j++)
//	{ 
//	$CounterTotalAmountcard=$CounterTotalAmountcard+($CounterCountQueryRES[$j][Qty]*$CounterCountQueryRES[$j][Fees]);
//	
//	}
            
//	
//	 $CounterCountQuery1 = "SELECT  s.Id, s.SignupDt, s.Qty, s.Fees FROM  EventSignup AS s , events AS e where  s.EventId = e.Id  AND (s.Fees != 0 AND (s.PromotionCode = 'PayatCounter' or s.PromotionCode = 'Y' )) and s.delStatus='Delivered' and s.eChecked = 'Verified' and s.EventId=$EventId"; 
//	 $CounterCountQueryRES1=$Global->SelectQuery($CounterCountQuery1); 
//	 for($j = 0; $j < count($CounterCountQueryRES1); $j++)
//	{ 
//	
//	$CounterTotalAmountverified=round($CounterTotalAmountverified+($CounterCountQueryRES1[$j][Qty]*$CounterCountQueryRES1[$j][Fees]),2);
//	
//	}
	
	    //$CounterTotalNetverified=round($CounterTotalAmountverified-($CounterTotalAmountverified*0.0353),2);
	   
		
	$CounterTotalAmountverified=$CounterTotaltAmountcard;//no refund verified & total amount is same	
	
     
		  
		
$Countervalues=$Global->SelectQuery("select Counter,MEeffort from commsion where EventId=$EventId");
            
if(count($Countervalues)>0 && $Countervalues[0]['Counter']!=0){
    $Counterperc= $Countervalues[0]['Counter'];
}else if(count($Countervalues)>0){
     $Counterperc= $Countervalues[0]['MEeffort'];
}

if ($Counterperc == 0 || $Counterperc == "") {

    $Counterperc = $Global->GetSingleFieldValue("select counter from global_commissions");
    $Counterp = $Counterperc + ($Counterperc * 0.1236);
    $Countertobepaid = round($CounterTotalAmountverified - ($CounterTotalAmountverified * ($Counterp / 100)), 2);

} else {
            
    $Countertobepaid = round($CounterTotalAmountverified - ($CounterTotalAmountverified * ($Counterperc / 100)), 2);
}
$Countertotrev = $CounterTotalAmountverified - $Countertobepaid;


$Counterrefundchrg=round($CounterTotalrAmountcard*($Counterperc/100),2);

?>
<div><h3>Counter Payments with <?=$Counterperc;?>% commision</h3></div>
<table width='100%' border='1' cellpadding='0' cellspacing='0' >
			<thead>
            <tr bgcolor='#94D2F3'>
		  	
			
         
            <td class='tblinner' valign='middle' width='4%' align='center'>Reg No.</td>

            <td class='tblinner' valign='middle' width='5%' align='center'>Qty</td>
            <td class='tblinner' valign='middle' width='9%' align='center'>Total Trans Amount </td>
            <td class='tblinner' valign='middle' width='8%' align='center'>Refunded Amount </td>
             <td class='tblinner' valign='middle' width='6%' align='center'>Trans Amount </td>
             <td class='tblinner' valign='middle' width='6%' align='center'>Verified Amount </td>
             <td class='tblinner' valign='middle' width='6%' align='center'>Net Amount </td>
             <td class='tblinner' valign='middle' width='7%' align='center'>Refund Charges </td>
              <td class='tblinner' valign='middle' width='7%' align='center'>Revenue </td>
              <td class='tblinner' valign='middle' width='6%' align='center'>Amount </td> 
               <td class='tblinner' valign='middle' width='6%' align='center'>Amount to Pay </td>            
             
            
            
          </tr>
        </thead>
        
        
      
    <tr>
        <td class='tblinner' valign='middle' width='4%' align='center'><font color='#000000'><?= $CounterTotalreg; ?></font></td>     		
        <td class='tblinner' valign='middle' width='5%' align='center'><font color='#000000'><?= $CounterTotalqty; ?></font></td>
        <td class='tblinner' valign='middle' width='9%' align='center'><font color='#000000'> <?= $CounterTotaltAmountcard; ?></font></td>
        <td class='tblinner' valign='middle' width='8%' align='center'><font color='#000000'> <?= $CounterTotalrAmountcard; ?></font></td>
        <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?= $CounterTotaltAmountcard; ?></font></td>
        <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?= $CounterTotaltAmountcard; ?></font></td>
        <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?= $Countertobepaid; ?></font></td>
        <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?= $Counterrefundchrg; ?></font></td>
        <td class='tblinner' valign='middle' width='7%' align='center'><font color='#000000'> <?= $Countertotrev; ?></font></td>
        <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?= $Countertobepaid; ?></font></td>
        <td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'> <?= $Countertobepaid - $Counterrefundchrg; ?></font></td>
    </tr>

	
  

</table>

<!------------------------------------------End of PayatCounter Payment--------------------------------------->




<!-------------------------------ADD CONTENT PAGE ENDS HERE--------------------------------------------------------------->
	<?php
        $totPaidAmt=0;
                $dataHTML='';
                $selAllPayments="SELECT DATE(PDate) as date,AmountP,PType FROM Paymentinfo WHERE EventId='".$_REQUEST['EventId']."' and PType='Partial Payment' ORDER BY PDate ASC";
                    $resAllPayments=$Global->SelectQuery($selAllPayments);
                   if(count($resAllPayments)>0){  
        ?>
<div><h3>Partial Payments</h3></div>

<table width='100%' cellpadding='0' cellspacing='0' >
    <thead>
        
        <?php
    
    
                    
                        foreach ($resAllPayments as $k=>$v){
                            if(strcmp($v['PType'], 'Partial Payment')==0){
                                $dataHTML.='<tr height="20">
                                                <td  width="116" height="25" colspan="1">'.$v['AmountP'].'</td>
                                                <td width="200" height="25" colspan="3">'.$v['date'].'</td>
                                            </tr>';
                            }
                            $totPaidAmt += $v['AmountP'];
                        }
                    
        if($dataHTML != '') { 
         $total_amount_temp = ($tobepaid-$refundchrg)+$cardTotal['GateAmtToPay']+($CODRES['MEeffort']['CODtobepaid']+$CODRES['Org Sales']['CODtobepaid'])+($Countertobepaid-$Counterrefundchrg);
         $dataHTML .= '<tr>
                            <td colspan=2>Total Net Amount</td>
                            <td>'.$total_amount_temp.'</td>
                        </tr>
                        <tr>
                            <td colspan=2>Partially paid</td>
                            <td>'.$totPaidAmt.'</td>
                        </tr>
                        <tr>
                            <td colspan=2>Amount to be paid</td>
                            <td>'.($total_amount_temp-$totPaidAmt).'</td>
                        </tr>';
         
            $data.='
                <tr>
                    <td>
                        <table width="100%" cellspacing="0" cellpadding="2" style="margin-right:5px">
                            <tr>
                                <td colspan=2 align="left" nowrap="nowrap" bordercolor="#000000">
                                    <table width="100%" border="1" cellpadding="2" cellspacing="0">
                                        <col width="79" />
                                        <col width="128" />
                                        <tr bgcolor="#94D2F3">
                                            <td width="116" colspan="1">Partial Payment Amt'.$currencyType.'</td>
                                            <td width="200" colspan="3">Partial Payment Date</td>
                                        </tr>
                                        '.$dataHTML.'
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>';
        }
        echo $data;
    ?>
        
    </thead>
</table>
                   <?php } ?>
	
	</div></td>
  </tr>
            
    
            
  <tr><td align="center"><div><h3>Summary</h3></div></td></tr>
  <tr><td><table align="center" width="50%" border="1" cellspacing="2" cellpadding="2">
  <tr>
    <td><font color='#000000'><?=$Global->GetSingleFieldValue("select Title from events where Id='".$_REQUEST['EventId']."'");?></font></td>
  </tr>
 
 
  <tr>
    <td><font color='#000000'><?=date("F j, Y, g:i a",strtotime($Global->GetSingleFieldValue("select StartDt from events where Id='".$_REQUEST['EventId']."'"))). " to ".date("F j, Y, g:i a",strtotime($Global->GetSingleFieldValue("select EndDt from events where Id='".$_REQUEST['EventId']."'")));?></font></td>
  </tr>
  <tr>
      <td><font color='#000000'>Total Reg : <?=$Totalreg+$cardTotal['GateTotalreg']+$CODRES['MEeffort']['CODTotalreg']+$CODRES['Org Sales']['CODTotalreg']+$CounterTotalreg;?></font></td>
  </tr>
  <tr>
      <td><font color='#000000'>Total Qty : <?=$Totalqty+$cardTotal['GateTotalqty']+$CODRES['MEeffort']['CODTotalqty']+$CODRES['Org Sales']['CODTotalqty']+$CounterTotalqty;?></font></td>
  </tr>
  <tr>
       <td><font color='#000000'>Total Amount to Pay : <?=($tobepaid-$refundchrg)+$cardTotal['GateAmtToPay']+($CODRES['MEeffort']['CODtobepaid']+$CODRES['Org Sales']['CODtobepaid'])+($Countertobepaid-$Counterrefundchrg)-$totPaidAmt;?></font></td>
  </tr>
   <tr>
       <td><font color='#000000'>Total Revenue : <?=$totrev+$cardTotal['Gatetotrev']+$CODRES['MEeffort']['CODtotrev']+$CODRES['Org Sales']['CODtotrev']+$Countertotrev;?></font></td>
  </tr>
   
</table>
</td></tr>
</table>

	</td>
  </tr>
</table>
	</div>	
</body>
</html>
