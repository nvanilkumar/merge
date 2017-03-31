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
<script language="javascript">
	function SEdt_validate()
	{
            var eventid=document.getElementById('eventIdSrch').value;
            if(eventid.length>0)
	{
		$.get('includes/ajaxSeoTags.php',{eventIDChk:0,eventid:eventid}, function(data){
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
	function TransStatus(sId)
	{
		
	var Status=document.getElementById('Status').value; 
	var eChecked=document.getElementById('eChecked'+sId).value; 
	var setDt=document.getElementById('setDt'+sId).value; 
	var depDt=document.getElementById('depDt'+sId).value;
	
	var strtdt = document.frmEofMonth.txtSDt.value;
		var enddt = document.frmEofMonth.txtEDt.value;
		var recptno = document.frmEofMonth.recptno.value;
		var EventId=document.frmEofMonth.eventIdSrch.value;
		var Payment=document.frmEofMonth.Payment.value;
		var settleDt=document.frmEofMonth.settleDt.value;
		
	window.location="CheckTrans.php?value="+eChecked+"&sId="+sId+"&txtSDt="+strtdt+"&txtEDt="+enddt+"&Status="+Status+"&recptno="+recptno+"&EventId="+EventId+"&setDt="+setDt+"&depDt="+depDt+"&Payment="+Payment+"&settleDt="+settleDt;
	}
	</script>
<style>
    .special_class{background-color: #AFE49F;}
</style>

	</head>	
<body style="background-image: url(images/background.gif); background-repeat:repeat-x; margin-top: 0px; margin-left: 0px; margin-right:0px; padding:0px">
	<?php include('templates/header.tpl.php'); ?>				
</div>
	<table style="width:100%; height:495px;" cellpadding="0" cellspacing="0">
  <tr>
	<td style="width:150px; vertical-align:top; background-image:url(images/menugradient.jpg); background-repeat:repeat-x">
		<?php include('templates/left.tpl.php'); ?>
	</td>
	<td style="vertical-align:top">
    <form action="" method="get" name="frmEofMonth">
    <table width="80%" align="center" class="tblcont">
    	<?php
            if(isset($sessData['failed1']) || isset($sessData['failed2']) || isset($sessData['failed3'])){
		?>
        	<tr><td colspan="3"><h3 style="color:#06F;"><?=$sessData['failed1'].'<br>'.$sessData['failed2'].'<br>'.$sessData['failed3'].'<br>'.$sessData['success'];?></h3></td></tr>
        <?php
			//unset($_SESSION['capturedTransStatus']);
		}else if(count($sessData)>0){ ?>
                    <tr><td colspan="3"><h3 style="color:#06F;">Updation successfull.</h3></td></tr>
               <?php }
    ?>
	<tr>
	  <td width="35%" align="left" valign="middle">Start Date:&nbsp;<input type="text" id="txtSDt" name="txtSDt" value="<?php echo $SDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	  <td width="35%" align="left" valign="middle">End Date:&nbsp;<input type="text" id="txtEDt" name="txtEDt" value="<?php echo $EDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	  <td width="30%" align="left" valign="middle">PaymentGateway : <select name="Payment" Id="Payment">
    <option value="All" <?php if($_REQUEST[Payment]=="All"){?> selected="selected" <?php }?>>All</option>
    <option value="1" <?php if($_REQUEST[Payment]=="1"){?> selected="selected" <?php }?>>EBS</option>
    <option value="9" <?php if($_REQUEST[Payment]=="9"){?> selected="selected" <?php }?>>Billdesk</option>
    <option value="4" <?php if($_REQUEST[Payment]=="4"){?> selected="selected" <?php }?>>PayPal</option>
    <option value="5" <?php if($_REQUEST[Payment]=="5"){?> selected="selected" <?php }?>>Mobikwik</option>
    <option value="6" <?php if($_REQUEST[Payment]=="6"){?> selected="selected" <?php }?>>Paytm</option>
   	

    </select></td>
	<tr>
    <tr><td>Select Status:  <select name="Status" Id="Status">
    <option value="">Select</option>
    <option value="Verified" <?php if($_REQUEST[Status]=="Verified"){?> selected="selected" <?php }?>>Verified</option>
    <option value="Captured" <?php if($_REQUEST[Status]=="Captured"){?> selected="selected" <?php }?>>Captured</option>
    <option value="NotVerified" <?php if($_REQUEST[Status]=="NotVerified"){?> selected="selected" <?php }?>>NotVerified</option>
    <option value="Refunded" <?php if($_REQUEST[Status]=="Refunded"){?> selected="selected" <?php }?>>Refunded</option>
    <option value="All" <?php if($_REQUEST[Status]=="All"){?> selected="selected" <?php }?>>All</option>

    </select></td><td>Receipt No. <input type="text" name="recptno" id="recptno" value="<?=$_REQUEST[recptno];?>" />
    </td><td>SettlementDate<input type="text" name="settleDt" id="settleDt" value="<?=$_REQUEST[settleDt];?>" size="8" onfocus="showCalendarControl(this);" /></td></tr>
    <!--tr><td colspan="3">Select an Event <select name="EventId" style="width:400px;" id="EventId" onChange="EventTrans(this.value);">
        <option value="">Select Event</option>
        <?php
		$TotalEventQueryRES = count($EventQueryRES);

		for($i=0; $i < $TotalEventQueryRES; $i++)
		{
		?>
         <option value="<?=$EventQueryRES[$i]['EventId'];?>" <?php if($EventQueryRES[$i]['EventId']==$_REQUEST[EventId]){?> selected="selected" <?php }?>><?=$EventQueryRES[$i]['Details'];?></option>
         <?php }?>
      </select> </td></tr-->
            <tr><td>Event Id:&nbsp;<input type="text" name="eventIdSrch" id="eventIdSrch" value=<?php if($_REQUEST['eventIdSrch']!=""){ echo $_REQUEST['eventIdSrch'] ;}else{ echo $_REQUEST[EventId];}?>></td>
            <td>Free:&nbsp;<input type="checkbox" name="freeTrans" id="freeTrans" <?php if(isset($_REQUEST['freeTrans'])){ echo 'checked'; } ?>></td>
            <td>Include Offline Tr:&nbsp;<input type="checkbox" name="offTrans" id="offTrans" <?php if(isset($_REQUEST['offTrans'])){ echo 'checked'; } ?>></td>
            </tr>
      
            <tr><td>&nbsp;&nbsp;<input type="submit" name="submit" value="Show Report"  onclick="return SEdt_validate();" />&nbsp;&nbsp;
                <input type="submit" name="exportReports" id="exportReports" style="margin-left:10px;" value="Export Report" />
                    
                </td></tr>
</table>
</form>
	<div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
	
	
<!-------------------------------ADD CONTENT PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans4').style.display='block';
</script>
<p><?php if($msg!='') echo $msg; ?></p>

<table width='95%' border='1' cellpadding='0' cellspacing='0' >
			<thead>
            <tr bgcolor='#94D2F3'>
		  	<td class='tblinner' valign='middle' width='3%' align='center'>Sr.No.</td>
			<td class='tblinner' valign='middle' width='10%' align='center'>Receipt No.</td>
            <td class='tblinner' valign='middle' width='16%' align='center'>Date</td>
            <td class='tblinner' valign='middle' width='32%' align='center'>Event Details</td>
            <td class='tblinner' valign='middle' width='10%' align='center'>Transaction No.</td>
            <td class='tblinner' valign='middle' width='5%' align='center'>Payment<br />Gateway</td>
            <td class='tblinner' valign='middle' width='5%' align='center'>Qty</td>
            <td class='tblinner' valign='middle' width='15%' align='center'>Paid</td>
            <!--td class='tblinner' valign='middle' width='15%' align='center'>Gateway Commission</td-->
            <td class='tblinner' valign='middle' width='15%' align='center'>Paypal<br />Converted Amt</td>
            <td class='tblinner' valign='middle' width='15%' align='center'>Converted Amt<?php if(strlen($currCode)>0) echo '<br><span align="center">('.$currCode.')</span>';?></td>
             <td class='tblinner' valign='middle' width='11%' align='center'>Ver/Not</td>
              <td class='tblinner' valign='middle' width='8%' align='center'>SettlementDate</td>
               <td class='tblinner' valign='middle' width='8%' align='center'>DepositedDate</td>
              <td class='tblinner' valign='middle' width='11%' align='center'>Action</td>
          </tr>
        </thead>
        
        <?php 
		$TotalAmountcard=$TotalAmountchk=$Totalchk=$Totalcard=$totConversionAmt=$tot_commission=0;
              
        $countTransactionRES=count($TransactionRES);
		for($i = 0; $i < $countTransactionRES; $i++)
		{ 
            $class_name="";
            if($TransactionRES[$i]['PaymentStatus'] === "Successful"){ $class_name='class="special_class"'; }
			
			$currencyCode=$TransactionRES[$i]['currencyCode'];
			
            /*if($TransactionRES[$i]['conversionRate']!=1 && $TransactionRES[$i]['currencyCode']!="INR"){
                         $currencyCode="INR";
            }*/
			
			$totConversionAmt+=round($TransactionRES[$i]['AMOUNT'],2);
			
            ?>
		<tr <?=$class_name?> >
			<td class='tblinner' valign='middle'  align='center' ><font color='#000000'><?=$cntTransactionRES;?></font></td>
			<td class='tblinner' valign='middle'  align='center'><font color='#000000'><?=$TransactionRES[$i]['id'];?></font></td>
			<td class='tblinner' valign='middle' align='center' ><font color='#000000'><?=
                        $common->convertTime($TransactionRES[$i]['signupdate'],DEFAULT_TIMEZONE,TRUE);?></font></td>
			<td class='tblinner' valign='middle' align='left'><font color='#000000'><?=stripslashes($TransactionRES[$i]['title']);?></font></td>
			<td class='tblinner' valign='middle'  align='left'><font color='#000000'>TR:<?=$TransactionRES[$i]['paymenttransactionid'];?></font></td>
            <td class='tblinner' valign='middle'  align='left'><font color='#000000'><?=$TransactionRES[$i]['name'];?></font></td>
            <td class='tblinner' valign='middle' align='right'><font color='#000000'><?=$TransactionRES[$i]['quantity'];?></font></td>
			<td class='tblinner' valign='middle'  align='right'><font color='#000000'><?php echo $TransactionRES[$i]['currencyCode']." ".$TransactionRES[$i]['totalamount'];?></font></td>
                        <!--td class='tblinner' valign='middle'  align='right'><font color='#000000'><?php // echo "INR ".$TransactionRES[$i]['gateway_commission'];?></font></td-->
            <td class='tblinner' valign='middle'  align='right'><font color='#000000'><?php if($TransactionRES[$i]['convertedamount']>0){ echo "USD ".round($TransactionRES[$i]['convertedamount']*$TransactionRES[$i]['quantity'],2); }else{ echo "NA";  } ?></font></td>
            
            <td class='tblinner' valign='middle'  align='right'><font color='#000000'>
			<?php 
			if($TransactionRES[$i]['conversionRate']>1){ 
				if($TransactionRES[$i]['convertedamount']>0){
					echo "INR ".round($TransactionRES[$i]['convertedamount']*$TransactionRES[$i]['quantity']*$TransactionRES[$i]['conversionRate'],2); 
				}
				else{ echo "INR ".round($TransactionRES[$i]['AMOUNT'],2);  }
				
			}
			else{ 
				if($TransactionRES[$i]['convertedamount']>0){
					echo "USD ".round($TransactionRES[$i]['convertedamount']*$TransactionRES[$i]['quantity'],2); 
				}
				else{ echo $TransactionRES[$i]['currencyCode']." ".$TransactionRES[$i]['AMOUNT'];   }
			
				 
			} ?></font></td>
            
            <td class='tblinner' valign='middle'  align='right'><font color='#000000'>
			<select name="eChecked<?=$TransactionRES[$i]['id']?>" id="eChecked<?=$TransactionRES[$i]['id']?>" >
        <?php if(!in_array($TransactionRES[$i]['paymentstatus'], $deductCount)){?>
            <option value="Verified" <?php if($TransactionRES[$i]['paymentstatus']=="Verified"){?> selected="selected" <?php } ?>>Verified</option>
            <option value="Captured" <?php if($TransactionRES[$i]['paymentstatus']=="Captured"){?> selected="selected" <?php } ?>>Captured</option>
            <option value="NotVerified" <?php if($TransactionRES[$i]['paymentstatus']=="NotVerified"){?> selected="selected" <?php } ?>>NotVerified</option>
            <option value="Refunded" <?php if($TransactionRES[$i]['paymentstatus']=="Refunded"){?> selected="selected" <?php } ?>>Refunded</option>
            <option value="Canceled" <?php if($TransactionRES[$i]['paymentstatus'] == "Canceled") { ?> selected="selected" <?php } ?>>Canceled</option>
        <?php } else if($TransactionRES[$i]['paymentstatus'] == 'Refunded'){ ?>
            <option value="Refunded" <?php if($TransactionRES[$i]['paymentstatus']=="Refunded"){?> selected="selected" <?php } ?>>Refunded</option>
        <?php }else { ?>
            <option value="Canceled" <?php if($TransactionRES[$i]['paymentstatus'] == "Canceled") { ?> selected="selected" <?php } ?>>Canceled</option>
        <?php } ?>
                        </select>
			</font></td>
            <?php if($TransactionRES[$i]['settlementdate']!="0000-00-00 00:00:00"){
			
                        $sdate=$common->convertTime($TransactionRES[$i]['settlementdate'],DEFAULT_TIMEZONE,TRUE);
						$sdate=date('d/m/Y',strtotime($sdate));
                        
			}else{
			$sdate="";
			}
			
			if($TransactionRES[$i]['depositdate']!="0000-00-00 00:00:00"){
			      $depdate=$common->convertTime($TransactionRES[$i]['depositdate'],DEFAULT_TIMEZONE,TRUE);
				  $depdate=date('d/m/Y',strtotime($depdate));
			}else{
			$depdate="";
			}
			
			?>
            <td class='tblinner' valign='middle' align='right'><input type="text" name="setDt<?=$TransactionRES[$i]['id']?>" 
			id="setDt<?=$TransactionRES[$i]['id']?>" value="<?php echo $sdate; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
               <td class='tblinner' valign='middle' align='right'><input type="text" name="depDt<?=$TransactionRES[$i]['id']?>" 
			   id="depDt<?=$TransactionRES[$i]['id']?>" value="<?php echo $depdate; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
            <td class='tblinner' valign='middle'  align='right'><input type="button" name="changetrans" value="change" onclick="TransStatus(<?=$TransactionRES[$i]['id']?>);"  /></td>
          </tr>
          <?php
		  $TotalAmountcard[$currencyCode] += $TransactionRES[$i]['totalamount'];
                  $tot_commission+=$TransactionRES[$i]['gateway_commission'];
		//$TotalAmountcard += $TransactionRES[$i]['Fees'] * $TransactionRES[$i]['Qty'];
		$Totalcard += $TransactionRES[$i]['quantity'];
		$cntTransactionRES++;
	}
	
	$totalAmountResStr=$totConversionAmtStr=$totPaypalConvAmtStr=0;
        if(count($totSum)>0){
            $tot=NULL;
            foreach($totSum as $k=>$v){
                $tot.=$k." ".round($v,2)."<br>";
            }
            $totalAmountResStr=$tot;
        }
		
		
		if(count($totConvertedSum)>0){
            $tot=NULL;
            foreach($totConvertedSum as $k=>$v){
                $tot.=$k." ".round($v,2)."<br>";
            }
            $totConversionAmtStr=$tot;
        }
		
		
		
		
		if(count($totPaypalConvertedSum)>0){
            $tot=NULL;
            foreach($totPaypalConvertedSum as $k=>$v){
                $tot.=$k." ".round($v,2)."<br>";
            }
            $totPaypalConvAmtStr=$tot;
        }
        ?>

        <tr><td colspan="4" style="line-height:30px;"><strong>Total Card Transactions Amount:</strong></td>
		<td colspan='4' align='right'><font color='#000000'><?=$totalAmountResStr;?></font></td>
		<!--td align='right'><font color='#000000'><?php echo "INR ".$tot_commission;?></font></td-->
		<td><font color='#000000'><?php echo $totPaypalConvAmtStr;?></font></td>
		<td align='right'><font color='#000000'><?php echo $totConversionAmtStr;?></font></td>
		</tr>
  
            
        
</table>
<!-------------------------------ADD CONTENT PAGE ENDS HERE--------------------------------------------------------------->
	
	<?php 
        
       $requesturi=$_SERVER['REQUEST_URI'];
         //echo $requesturi."<br>";
		 //$requesturi=preg_replace('/&page=[0-9]*/', '', $requesturi);
		 
		 if(strpos($requesturi,"page=")!==false)
		 {
			 $requesturiEx=explode("page=",$requesturi);
			 $requesturi=$requesturiEx[0];
			 
			 $lastChar=substr($requesturi,-1);
			 if($lastChar=='&')
			 {

				 $requesturi=substr($requesturi,0,-1);
			 }
		 }
		 
		 
		 if(strpos($requesturi,"?")!==false)
		 {
			  echo pagination($perPage,$page,"$requesturi&page=",$totalItems);
		 }
		 else
		 {
			  echo pagination($perPage,$page,"$requesturi?page=",$totalItems);
		 }
		// echo $requesturi;
		 
         
             
         // echo $requesturi;
              //echo $_SERVER['HTTP_HOST'];
              
        ?>
	
	</div>
	</td>
  </tr>
</table>
	</div>	
</body>
</html>