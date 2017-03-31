<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<title>MeraEvents -Menu Content Management</title>
		<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
		<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
                        <script language="javascript" src="<?=_HTTP_SITE_ROOT;?>/js/public/jQuery.js"></script>  
        <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.js"></script>	
        <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.js"></script>	
        <link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.css" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.js"></script>
<script src="<?=_HTTP_SITE_ROOT ?>/ctrl/includes/javascripts/jquery.1.7.2.min.js"></script>
<script language="javascript">
	function SEdt_validate()
	{
            var eventid=document.getElementById('eventIdSrch').value;
            if(eventid>0)
	{$.get('includes/ajaxSeoTags.php',{eventIDChk:0,eventid:eventid}, function(data){
			if(data=="error")
			{
				alert("Sorry, we did not find the Event ID or Event is deleted, Please Re-enter");
				document.getElementById('eventIdSrch').focus();
				return false;
				
			}
		});
		
	}
            

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
		//var SerEventName = document.frmEofMonth.SerEventName.value;
              var compeve = document.frmEofMonth.compeve.value;
			 
		window.location="paymentdocs.php?EventId="+eid+"&Status="+status+"&txtSDt="+strtdt+"&txtEDt="+enddt+"&compeve="+compeve;
	}
        /*function addComments(srNo){
            var comments=document.getElementById('comments'+srNo).value;
            var eventid=document.getElementById('EventId_comments'+srNo).value;
            if(comments.trim().length>0 ){
                $.ajax({
                    type:'POST',
                    url:'<?=_HTTP_SITE_ROOT?>/ctrl/ajax.php',
                    data:'call=addPaymentComments&EventId='+eventid+'&comments='+comments,
                    success:function(res){
                        var newRes=$.parseJSON(res);
                        if(newRes['status']){
                            alert("Added Comments successfully");
                            document.getElementById('comments'+srNo).disabled=true;
                            document.getElementById('comments'+srNo).id="commentsdb"+newRes['Id'];
                            document.getElementById("commentsdb"+newRes['Id']).name="commentsdb"+newRes['Id'];
                            document.getElementById('commentsdb'+newRes['Id']).style.width="174px";
                            document.getElementById('commentsdb'+newRes['Id']).style.height="24px";
                            var txtarea=document.createElement('textarea');
                            txtarea.name='comments'+srNo;
                            txtarea.id='comments'+srNo;
                            var sub=document.getElementById('commentsSubmit'+srNo);
                            document.getElementById("commentsForm"+srNo).insertBefore(txtarea, sub);
                        }else{
                            alert("Comments can be added only after atleast one document is uploaded");
                        }
                    }
                });
                return false;
            }else{
                alert("Please enter valid comments");
                return false;
            }
        }*/
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
    <form action="TransbyEvent_new.php" method="post" name="frmEofMonth">
    <table width="70%" align="center" class="tblcont">
	<tr>
	  <td width="35%" align="left" valign="middle">Event Start Date:&nbsp;<input type="text" name="txtSDt" value="<?php echo $SDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	  <td width="35%" align="left" valign="middle">Event End Date:&nbsp;<input type="text" name="txtEDt" value="<?php echo $EDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	 
	</tr>
 
<!--    <tr><td colspan="3">Select an Event <select name="EventId" id="EventId" >
        <option value="">Select Event</option>
        <?php
		$TotalEventQueryRES = count($EventQueryRES);

		for($i=0; $i < $TotalEventQueryRES; $i++)
		{
		?>
         <option value="<?=$EventQueryRES[$i]['eventid'];?>" <?php if($EventQueryRES[$i]['eventid']==$EventId){?> selected="selected" <?php }?>><?=$EventQueryRES[$i]['Details'];?></option>
         <?php }?>
      </select></td></tr>-->
      <tr><td>Event Id:&nbsp;<input type="text" name="eventIdSrch" id="eventIdSrch" value="<?= $EventId; ?>"></td>
      <td>Include Offline Tr. :<input type="checkbox" name="offline" <?php if($_REQUEST[offline]==1){?> checked="checked" <?php }?> id="offline" value="1"/> </td></tr>
      
        <tr><td>
		<!--Select Organizer <select name="SerEventName" id="SerEventName" >
       <option value="">Select Organizer Name</option>	
				<?php /*
				$SelectOrgNames1="SELECT orgDispName, Id FROM orgdispname where Active=1  ORDER BY orgDispName ASC";
                $OrgNames1=$Global->SelectQuery($SelectOrgNames1);
                $TotalOrgNames1=count($OrgNames1);
                for($i=0;$i<$TotalOrgNames1;$i++)
                {
                ?>
                <option value="<?php echo $OrgNames1[$i]['Id'];?>" <?php if($OrgNames1[$i]['Id'] == $_REQUEST['SerEventName']) { ?> selected="selected" <?php } ?>><?php echo $OrgNames1[$i]['orgDispName']; ?></option>
                <?php 
                } */
                ?>    
      </select-->
		&nbsp;Select Status <select name="Status" id="Status" >
       <option value="">Status</option>	
			
                <option value="Pending" <?php if($_REQUEST['Status']=="Pending") { ?> selected="selected" <?php } ?>>Pending</option>
                <option value="EventCanceled" <?php if($_REQUEST['Status']=="EventCanceled") { ?> selected="selected" <?php } ?>>Event Canceled</option>
                  <option value="Done" <?php if($_REQUEST['Status']=="Done") { ?> selected="selected" <?php } ?>>Done</option>
              
      </select> &nbsp; <input type="checkbox" name="compeve" id="compeve" <?php if($_REQUEST[compeve]==1){?> checked="checked" <?php }?> value="1" /> Completed</td>
            <td>Filter Type:<select name="amountsType" id="amountsType"><option value="all" <?php if($amountType=='all'){echo 'selected="selected"';}?>>All</option><option value="paid" <?php if($amountType=='paid'){echo 'selected="selected"';}?>>Paid</option><option value="free" <?php if($amountType=='free'){echo 'selected="selected"';}?>>Free</option></select></td></tr>
      <tr> <td width="30%"  style="padding:10px;" align="center" valign="middle"><input type="submit" name="submit" value="Show Report" onclick="return SEdt_validate();" />&nbsp;&nbsp;
	  <input type="submit" name="exportReports" id="exportReports" style="margin-left:10px;" value="Export Report" />
	  </td></tr>
</table>
</form>
	<div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
	
	
<!-------------------------------ADD CONTENT PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans4').style.display='block';
</script>
<table width='100%' border='1' cellpadding='0' cellspacing='0' >
			<thead>
            <tr bgcolor='#94D2F3'>
		  	<td class='tblinner' valign='middle' width='3%' align='center'>Sr. No.</td>
			
            <td class='tblinner' valign='middle' width='10%' align='center'>Date</td>
            <td class='tblinner' valign='middle' width='15%' align='center'>Event Details</td>
             <td class='tblinner' valign='middle' width='5%' align='center'>OrgName</td>
            <td class='tblinner' valign='middle' width='6%' align='center'>Reg No.</td>
            <td class='tblinner' valign='middle' width='8%' align='center'>Qty</td>
            <td class='tblinner' valign='middle' width='5%' align='center'>TotalTransAmount </td>
            <td class='tblinner' valign='middle' width='5%' align='center'>RefundedAmount </td>            
             <td class='tblinner' valign='middle' width='5%' align='center'>VerifiedAmount </td>
             <!--td class='tblinner' valign='middle' width='5%' align='center'>CommissionAmount </td-->
              <td class='tblinner' valign='middle' width='10%' align='center'>Partial Payment</td>
              <td class='tblinner' valign='middle' width='10%' align='center'>Total Amt to be Paid</td>
              <!--td class='tblinner' valign='middle' width='10%' align='center'>Detail Report</td-->
              <td class='tblinner' valign='middle' width='10%' align='center'>UploadDocs</td>
              <td class='tblinner' valign='middle' width='10%' align='center'>Comments</td>
              <td class='tblinner' valign='middle' width='10%' align='center'>Add Comments</td>
         
            
          </tr>
                            
        </thead>
        
        <?php	
		$TotalAmount=0;
		$TotalAmount1=0;
		$TotalAmount2=0;
		$TotalAmount3=0;
		 $TotalNetAmount=0;
		 $TotalRevenue=0;
         $tot_commission=0;
				 
		$finalPartailAmt=$finalTotAmtToBePaid=0; 
		
		for($i = 0; $i < count($TransactionRES); $i++)
		{
			$Published=$TransactionRES[$i]['status'];
			if($Published==1){ $trColor='none';}else{$trColor='#F7D154';}
			$eventUrl="<a href='"._HTTP_SITE_ROOT."/event/".$TransactionRES[$i]['url']."' target='_blank'>".$TransactionRES[$i]['eventid']."</a>"; 
		?>
		<tr style="background-color:<?php echo $trColor; ?>">
			<td class='tblinner' valign='middle' width='3%' align='center' ><font color='#000000'><?=$cntTransactionRES;?></font></td>
			<td class='tblinner' valign='middle' width='10%' align='center'><font color='#000000'><?=date("F j, Y, g:i a",strtotime($TransactionRES[$i]['startdatetime'])). " to ".date("F j, Y, g:i a",strtotime($TransactionRES[$i]['enddatetime']));?></font></td>
			<td class='tblinner' valign='middle' width='15%' align='center' ><font color='#000000'><?php echo $TransactionRES[$i]['title']."  (".$eventUrl.")";?></font></td>
			<td class='tblinner' valign='middle' width='8%' align='left'><font color='#000000'><?=$TransactionRES[$i]['OrgName'];?></font></td>
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
		
		
		
		/* $CountQueryt = "SELECT  Id, SignupDt, Qty, Fees FROM EventSignup  where 1  AND (Fees!=0 and (PaymentModeId=1 and PaymentTransId != 'A1') or (PaymentModeId=2 or PromotionCode='CashonDelivery' or PromotionCode='PayatCounter')) and eChecked !='Canceled' and EventId='".$TransactionRES[$i]['EventId']."'"; 
	 $CountQueryRESt=$Global->SelectQuery($CountQueryt); 
	 for($j = 0; $j < count($CountQueryRESt); $j++)
	{ 
	$Totalreg=$Totalreg+1;
	$Totalqty=$Totalqty+$CountQueryRESt[$j][Qty];
	$TotaltAmountcard=$TotaltAmountcard+($CountQueryRESt[$j][Qty]*$CountQueryRESt[$j][Fees]);
	
	}
		
	 $CountQuery = "SELECT  Id, SignupDt, Qty, Fees FROM EventSignup  where 1  AND (Fees != 0 AND (PaymentTransId != 'A1')  and eChecked != 'Refunded') and EventId='".$TransactionRES[$i]['EventId']."'"; 
	 $CountQueryRES=$Global->SelectQuery($CountQuery); 
	 for($j = 0; $j < count($CountQueryRES); $j++)
	{ 
	$TotalAmountcard=$TotalAmountcard+($CountQueryRES[$j][Qty]*$CountQueryRES[$j][Fees]);
	
	}
	 $CountQueryr = "SELECT  Id, SignupDt, Qty, Fees FROM EventSignup  where 1  AND (Fees != 0   and eChecked = 'Refunded') and EventId='".$TransactionRES[$i]['EventId']."'"; 
	 $CountQueryRESr=$Global->SelectQuery($CountQueryr); 
	 for($j = 0; $j < count($CountQueryRESr); $j++)
	{ 
	$TotalrAmountcard=$TotalrAmountcard+($CountQueryRESr[$j][Qty]*$CountQueryRESr[$j][Fees]);
	}
	
	
	
	
	 $CountQuery1 = "SELECT  Id, SignupDt, Qty, Fees FROM EventSignup  where 1  AND (Fees != 0   and eChecked = 'Verified') and EventId='".$TransactionRES[$i]['EventId']."'"; 
	 $CountQueryRES1=$Global->SelectQuery($CountQuery1); 
	 for($j = 0; $j < count($CountQueryRES1); $j++)
	{ 
	
	$TotalAmountverified=round($TotalAmountverified+($CountQueryRES1[$j][Qty]*$CountQueryRES1[$j][Fees]),2);
	
	}
	$TotalNetverified=round($TotalAmountverified-($TotalAmountverified*0.0353),2);
	    $uploaded="";
		$uploaded=$Global->GetSingleFieldValue("SELECT Id FROM Paymentinfo where EventId=".$TransactionRES[$i]['EventId']);
		if($uploaded=="")
		{
		$conf="Pending";
		}else{
		$conf="Done";
		}
		
		$perc=$Global->GetSingleFieldValue("select perc from events where Id=".$TransactionRES[$i]['EventId']);
		if($perc==0){
		    $p=3.75+(3.75*0.103);
            	 $tobepaid=round($TotalAmountcard-($TotalAmountcard*($p/100)),2);
		}else{
		 $p=$perc+($perc*0.103);
                $tobepaid=round($TotalAmountcard-($TotalAmountcard*($p/100)),2);
		}
		
	$netamt=$Global->GetSingleFieldValue("select AmountP from Paymentinfo where EventId=".$TransactionRES[$i]['EventId']);
       if($netamt!="" && $netamt!=0){ 
	//$totrev=round(($TotalAmountcard-$netamt)-(($TotalAmountcard-$netamt)*0.0358),2);
	       }
		   $totrev=$TotalNetverified-$tobepaid;*/
			?>
			<td class='tblinner' valign='middle' width='6%' align='left'><font color='#000000'><?=$TransactionRES[$i]['RegNo'];?></font></td>     		
			<td class='tblinner' valign='middle' width='8%' align='right'><font color='#000000'><?=$TransactionRES[$i]['Qty'];?></font></td>
			<td class='tblinner' valign='middle' width='5%' align='right'><font color='#000000'> <?=$TransactionRES[$i]['TotalTransAmount'];?></font></td>
            <td class='tblinner' valign='middle' width='5%' align='right'><font color='#000000'> <?=$TransactionRES[$i]['RefundedAmount'];?></font></td>
            
            <td class='tblinner' valign='middle' width='5%' align='right'><font color='#000000'> <?=$TransactionRES[$i]['VerifiedAmount'];?></font></td>
            <!--td class='tblinner' valign='middle' width='5%' align='right'><font color='#000000'> <?=$TransactionRES[$i]['gateway_commission'];?></font></td-->
            <td class='tblinner' width='12%'>
                <?php 
$totPaidAmt=0;
                $dataHTML='';
                $selAllPayments="SELECT DATE(paymentdate) as date,amountpaid,paymenttype FROM settlement WHERE eventid='".$TransactionRES[$i]['eventid']."' and status='1' ORDER BY paymentdate ASC";
                    $resAllPayments=$Global->SelectQuery($selAllPayments);
                    if(count($resAllPayments)>0){ 
                        foreach ($resAllPayments as $k=>$v){
                               // if(strcmp($v['PType'], 'Partial Payment')==0){
                                    $dataHTML.='<tr>
                                                    <td>'.$v['amountpaid'].'</td>
                                                    <td>'.$v['date'].'</td>
                                                </tr>';
                               // }
                                    $totPaidAmt+=$v['amountpaid'];
                        }
                        if(strlen($dataHTML)>0){
                            echo '<table border="1px" width="100%" cellspacing="1" cellpadding="5">
                                    <thead><tr>
                                            <td>Amount</td>
                                            <td>Date</td>
                                            </tr></thead>
                                    <tbody>'.$dataHTML.'</tbody></table>';
                        }
                    }
					
					$totAmtToBePaid = $TransactionRES[$i]['VerifiedAmount']-$totPaidAmt;
					$finalPartailAmt+=$totPaidAmt;
					$finalTotAmtToBePaid+=$totAmtToBePaid;
					?>    
            </td>
            <td class='tblinner' valign='middle' width='10%' align='center'><font color='#000000'><?=$totAmtToBePaid;?></font></td>
            <!--td class='tblinner' valign='middle' width='10%' align='center'><font color='#000000'><a href="detailreport.php?EventId=<?php //echo $TransactionRES[$i]['eventid'];?>" target="_blank">Click Here</a></font></td-->
            <?php $selQry="SELECT paymenttype from settlement where eventid='".$TransactionRES[$i]['eventid']."' order by id desc limit 1";
              $resQry=$Global->SelectQuery($selQry);
              $status='PENDING';
              if(count($resQry)>0){
                  if(strcmp($resQry[0]['paymenttype'],'Done')==0){
                      $status='DONE'; 
                  }elseif(strcmp($resQry[0]['paymenttype'], 'EventCanceled')==0){
                      $status='CANCELED';
                  }
              }?>
              <td class='tblinner' valign='middle' width='15%' align='left'><?=$status;?>,&nbsp;&nbsp;&nbsp;<a onclick="uploaddoc(<?=$TransactionRES[$i]['eventid'];?>)" href="#">Click Here</a></td>
               <td class="tblinner"><font color='#000000'><?php echo $Global->GetSingleFieldValue("SELECT comment FROM comment where eventid='".$TransactionRES[$i]['eventid']."' order by id desc limit 1") ?></font>
              <td class="tblinner"><font color='#000000'><a href="partial_payment_comments.php?EventId=<?=$TransactionRES[$i]['eventid'];?>" target="_blank">AddComments(<?=$Global->GetSingleFieldValue("SELECT count(id) FROM comment WHERE eventid='".$TransactionRES[$i]['eventid']."'");?>)</a></font>
              </td>
          
           
          </tr>
          <?php
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
		$cntTransactionRES++;
	}?>
	<tr><td colspan="6" style="line-height:30px;"><strong>Total  Transactions Amount:</strong></td><td  align='right'><font color='#000000'> <?=$TotalAmount;?></font></td><td align='right'><font color='#000000'> <?=$TotalAmount1;?></font></td><td  align='right'><font color='#000000'> <?=$TotalAmount3;?></font></td><!--td align='right'><font color='#000000'> <?=$tot_commission;?></font></td-->
    
    <td align='right'><font color='#000000'> <?=$finalPartailAmt;?></font></td>
    <td align='right'><font color='#000000'> <?=$finalTotAmtToBePaid;?></font></td>
    </tr>
  

</table>
<!-------------------------------ADD CONTENT PAGE ENDS HERE--------------------------------------------------------------->
	
	
	
	</div>
	</td>
  </tr>
</table>
	</div>	
</body>
</html>