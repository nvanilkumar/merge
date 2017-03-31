<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<title>MeraEvents -Menu Content Management</title>
		<link href="css/menus.css" rel="stylesheet" type="text/css">
		<link href="css/style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="css/sortable.js"></script>	
        <script language="javascript" src="css/sortpagi.js"></script>	
        <link rel="stylesheet" type="text/css" media="all" href="css/CalendarControl.css" />
<script type="text/javascript" language="javascript" src="includes/javascripts/CalendarControl.js"></script>
<script language="javascript">
	function SEdt_validate()
	{
		var strtdt = document.frmEofMonth.txtSDt.value;
		var enddt = document.frmEofMonth.txtEDt.value;
		if(strtdt == '')
		{
			alert('Please select Start Date');
			document.frmEofMonth.txtSDt.focus();
			return false;
		}
		else if(enddt == '')
		{
			alert('Please select End Date');
			document.frmEofMonth.txtEDt.focus();
			return false;
		}
		else //if(strtdt != '' && enddt != '')
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
	</script>
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
    <form action="" method="post" name="frmEofMonth">
    <table width="60%" align="center" class="tblcont">
	<tr>
	  <td width="33%" align="left" valign="middle">Start Date:&nbsp;
	    <input type="text" name="txtSDt" value="<?php echo $SDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	  <td width="28%" align="left" valign="middle">End Date:&nbsp;
	    <input type="text" name="txtEDt" value="<?php echo $EDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
      <td width="14%">NewYear:
        <input type="checkbox" name="NewYear" id="NewYear" <?php if(isset($_REQUEST[NewYear])){ ?> checked="checked" <?php }?> value="1"/> </td>
	  
	</tr>
    <tr>
			  
				<td align="left" valign="middle" class="tblcont">City :
					<select name="selCity" id="selCity">
				<option value="">All Cities</option>
			
            <option value="37" <?php if($_REQUEST['selCity']==37) {?> selected="selected" <?php }?>>Bengaluru</option>
                <option value="39" <?php if($_REQUEST['selCity']==39) {?> selected="selected" <?php }?>>Chennai</option>
            
                <option value="NewDelhi" <?php if($_REQUEST['selCity']=="NewDelhi") {?> selected="selected" <?php }?>>Delhi / NCR</option>
                <option value="Hyderabad"  <?php if($_REQUEST['selCity']=="Hyderabad") {?> selected="selected" <?php }?> >Hyderabad</option>
                <option value="Mumbai" <?php if($_REQUEST['selCity']=="Mumbai") {?> selected="selected" <?php }?>>Mumbai</option>
                
                <option value="77" <?php if($_REQUEST['selCity']==77) {?> selected="selected" <?php }?>>Pune</option>
                <option value="Other" <?php if($_REQUEST['selCity']=="Other") {?> selected="selected" <?php }?>>Other Cities</option>
					</select>
				</td>
                
                <td align="left" valign="middle" class="tblcont">Sales Person  : 
      <select name="SalesId" id="SalesId" >
        <option value="">Select</option>
        <?php
		$TotalSalesQueryRES = count($SalesQueryRES);

		for($i=0; $i < $TotalSalesQueryRES; $i++)
		{
		?>
         <option value="<?=$SalesQueryRES[$i]['SalesId'];?>" <?php if($SalesQueryRES[$i]['SalesId']==$_REQUEST[SalesId]){?> selected="selected" <?php }?>><?=$SalesQueryRES[$i]['SalesName'];?></option>
         <?php }?>
      </select>
    </td>
			</tr>

    <tr><td>&nbsp;</td><td width="25%" align="left" valign="middle"><input type="submit" name="submit" value="Show Report" onclick="return SEdt_validate();" /></td></tr>
</table>
</form>
	<div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
	
	
<!-------------------------------ADD CONTENT PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans22').style.display='block';
</script>
<table width='90%' border='1' cellpadding='0' cellspacing='0' >
			<thead>
            <tr bgcolor='#94D2F3'>
		  	<td class='tblinner' valign='middle' width='4%' align='center'>Sr. No.</td>
			<td class='tblinner' valign='middle' width='27%' align='center'>Event Details</td>
            <td class='tblinner' valign='middle' width='5%' align='center'>Qty</td>
            <td class='tblinner' valign='middle' width='11%' align='center'>Amount (Rs.)</td>
          
          </tr>
        </thead>
        
        <?php	
		$TotalAmountcard=0;
		$TotalAmountPayatCounter=0;
		$TotalAmountchk=0;
		$Totalchk=0;
		$Totalcard=0;
		$TotalPayatCounter=0;
		$ttck=0;
		
		
		$ttck=$feesaa=$qtyaa=$cnt=$preQty=$preCharge=$preFee=0 ;
		$same=1;
		
		 
	   while($ctrec=mysqli_fetch_assoc($TransactionRES))
		{
	      if($i==0)
	      {
	        $title = $ctrec['Title'];
	        $feesaa=0;
	        $qtyaa=0;
			$same=1;
			$cnt=0;
		}
		
		if($title == $ctrec['Title'] && $i!=0)
		{			
			$same=1;
		
		}else{
		$same=0;
		$Titlepre=$title;
		  $title = $ctrec['Title'];
	     $preCharge = $ctrec['Ccharge'];
         $preFee = $ctrec['Fees'];
         $preQty = $ctrec['Qty'];		 
	}
		
	
	$extraCharges=$preCharge;
	$feesaa += ($preFee * $preQty-$extraCharges);
	 $qtyaa +=$preQty;
	
	if($same==0 && $i!=0){
	?>
		<tr>
			<td class='tblinner' valign='middle' width='4%' align='center' ><font color='#000000'><?=++$cnt;?></font></td>
			<td class='tblinner' valign='middle' width='27%' align='left'><font color='#000000'><?=$Titlepre;?></font></td>
			 		
			<td class='tblinner' valign='middle' width='5%' align='right'><font color='#000000'><?=$qtyaa;?></font></td>
			<td class='tblinner' valign='middle' width='11%' align='right'><font color='#000000'><?=round($feesaa);?></font></td>
            </tr>
          <?php
		  $ttck=$ttck+$qtyaa;
		   $feesaa=0;
	        $qtyaa=0;
		   }
                   $extraCharges=$ctrec['Ccharge'];
		$TotalAmountcard +=($ctrec['Fees'] * $ctrec['Qty']-$extraCharges);
		$Totalcard +=1;// $ctrec['Qty'];
		$cntTransactionRES++;
	}?>
	<tr><td colspan="2" style="line-height:30px;"><strong>Total Card Transactions Amount:</strong></td><td  align='right'><font color='#000000'><?=$ttck;?></font></td><td  align='right'><font color='#000000'>Rs. <?=round($TotalAmountcard);?></font></td></tr>
    	<?php	
		
		$ttck=$feesaa=$qtyaa=$cnt=$preQty=$preCharge=$preFee=0 ;
		$same=1;
		
	while($pcrec=mysqli_fetch_assoc($PayatCounterRES))
		{
	if($i==0)
	      {
	        $title = $pcrec['Title'];
	        $feesaa=0;
	        $qtyaa=0;
			$same=1;
			$cnt=0;
		}
		
		if($title == $pcrec['Title'] && $i!=0)
		{			
			$same=1;
		
		}else{
		$same=0;
		$Titlepre=$title;
		  $title = $pcrec['Title'];
		  $preCharge = $pcrec['Ccharge'];
         $preFee = $pcrec['Fees'];
         $preQty = $pcrec['Qty'];
	       
	}
		
	
    $extraCharges=$preCharge;
	$feesaa += ($preFee * $preQty-$extraCharges);
	 $qtyaa +=$preQty;
	
	if($same==0 && $i!=0){
	?>
		<tr>
			<td class='tblinner' valign='middle' width='4%' align='center' ><font color='#000000'><?=++$cnt;?></font></td>
			
			<td class='tblinner' valign='middle' width='27%' align='left'><font color='#000000'><?=$Titlepre;?></font></td>
			<td class='tblinner' valign='middle' width='5%' align='right'><font color='#000000'><?=$qtyaa;?></font></td>
			<td class='tblinner' valign='middle' width='11%' align='right'><font color='#000000'><?=round($feesaa);?></font></td>
           
          </tr>
          <?php
		  $ttck=$ttck+$qtyaa;
		   $feesaa=0;
	        $qtyaa=0;
	}
                $extraCharges=$pcrec['Ccharge']+$pcrec['EntTax'];
		$TotalAmountPayatCounter += ($pcrec['Fees'] * $pcrec['Qty']-$extraCharges);
		$TotalPayatCounter +=1;// $ctrec['Qty'];
		
	}?>
	<tr><td colspan="2" style="line-height:30px;"><strong>Total Pay at Counter Amount:</strong></td><td  align='right'><font color='#000000'><?=$ttck;?></font></td><td  align='right'><font color='#000000'>Rs. <?=round($TotalAmountPayatCounter);?></font></td></tr>
    <?php	
	
	$ttck=$feesaa=$qtyaa=$cnt=$preQty=$preCharge=$preFee=0 ;
		$same=1;
	while($codrec=mysqli_fetch_assoc($CODRES))
	{ 
	if($i==0)
	      {
	        $title = $codrec['Title'];
	        $feesaa=0;
	        $qtyaa=0;
			$same=1;
			$cnt=0;
		}
		
		if($title == $codrec['Title'] && $i!=0)
		{			
			$same=1;
		
		}else{
		$same=0;
		$Titlepre=$title;
		  $title = $codrec['Title'];
	       
	         $preFee = $codrec['Fees'];
         $preQty = $codrec['Qty'];
	       
	}
	
	$feesaa += ($preFee * $preQty-$extraCharges);
	 $qtyaa +=$preQty;
		
	
	
	if($same==0 && $i!=0){
	?>
		<tr>
			<td class='tblinner' valign='middle' width='4%' align='center' ><font color='#000000'><?=++$cnt;?></font></td>
			<td class='tblinner' valign='middle' width='27%' align='left'><font color='#000000'><?=$Titlepre;?></font></td>
			<td class='tblinner' valign='middle' width='5%' align='right'><font color='#000000'><?=$qtyaa;?></font></td>
			<td class='tblinner' valign='middle' width='11%' align='right'><font color='#000000'><?=round($feesaa);?></font></td>
           
          </tr>
           <?php
		   $ttck=$ttck+$qtyaa;
		   $feesaa=0;
	        $qtyaa=0;
	}
		$TotalAmountCOD += $codrec['Fees'] * $codrec['Qty'];
		$TotalCOD +=1;// $TransactionRES[$i]['Qty'];
		
	}?>
	<tr><td colspan="2" style="line-height:30px;"><strong>Total Cash on Delivery Amount:</strong></td><td  align='right'><font color='#000000'><?=$ttck;?></font></td><td  align='right'><font color='#000000'>Rs. <?=round($TotalAmountCOD);?></font></td></tr>
    <?php 
	$ttck=$feesaa=$qtyaa=$cnt=$preQty=$preCharge=$preFee=0 ;
		$same=1;
	while($Chqrec=mysqli_fetch_assoc($ChqTranRES))
	{
		if($i==0)
	      {
	        $title = $Chqrec['Title'];
	        $feesaa=0;
	        $qtyaa=0;
			$same=1;
			$cnt=0;
		}
		
		if($title == $Chqrec['Title'] && $i!=0)
		{			
			$same=1;
		
		}else{
		$same=0;
		$Titlepre=$title;
		  $title = $Chqrec['Title'];
	 $preCharge = $Chqrec['Ccharge'];
         $preFee = $Chqrec['Fees'];
         $preQty = $Chqrec['Qty'];
	       
	}
		
	
    $extraCharges=$preCharge;
	$feesaa += ($preFee * $preQty-$extraCharges);
	 $qtyaa +=$preQty;
	
	if($same==0 && $i!=0){
	?>	
		<tr >
				<td class='tblinner' valign='top' width='4%' align='center'><font color='#000000'><?=++$cnt;?></font></td>
				<td class='tblinner' valign='top' width='27%' align='left'><font color='#000000'><?=$Titlepre;?></font></td>
				<td class='tblinner' valign='top' width='5%' align='right'><font color='#000000'><?=$qtyaa;?></font></td>
			<td class='tblinner' valign='top' width='11%' align='right'><font color='#000000'><?=round($feesaa);?></font></td>
               
		  </tr>
		<?php
        $ttck=$ttck+$qtyaa;
		 $feesaa=0;
	        $qtyaa=0;
	}
        $extraCharges=$Chqrec['Ccharge']+$Chqrec['EntTax'];
		$TotalAmountchk += ($Chqrec['Fees'] * $Chqrec['Qty']-$extraCharges);
		$Totalchk +=1;// $ChqTranRES[$i]['Qty'];
		
	}
	$TotalAmountcard = round($TotalAmountcard);
	$TotalAmountchk = round($TotalAmountchk);
	$TotalAmountPayatCounter = round($TotalAmountPayatCounter);
	$TotalAmountCOD = round($TotalAmountCOD);
?>
<tr><td colspan="2" style="line-height:30px;"><strong>Total Cheque Transactions Amount:</strong></td><td  align='right'><font color='#000000'><?=$ttck;?></font></td><td  align='right'><font color='#000000'>Rs. <?=$TotalAmountchk;?></font></td></tr>
<tr><td colspan="2" style="line-height:30px;"><strong>Total :</strong></td><td  align='right'><font color='#000000'><?=$ttck;?></font></td><td  align='right'><font color='#000000'>Rs. <?=$TotalAmountcard+$TotalAmountchk+$TotalAmountPayatCounter+$TotalAmountCOD;?></font></td></tr>
<tr>
  <td colspan="2">Total Card Transactions Amount :</td>
  <td colspan='2' align='right'><font color='#000000'> <?=$TotalAmountcard;?></font></td></tr>
  <tr>
  <tr>
  <td colspan="2">Total Cheque Transactions Amount :</td>
  <td colspan='2' align='right'><font color='#000000'><?=$TotalAmountchk;?></font></td></tr>
  <tr>
  <tr>
  <td colspan="2">Total PayatCounter Amount :</td>
  <td colspan='2' align='right'><font color='#000000'><?=$TotalAmountPayatCounter;?></font></td></tr>
  <tr>
  <td colspan="2">Total CashonDelivery Amount :</td>
  <td colspan='2' align='right'><font color='#000000'><?=$TotalAmountCOD;?></font></td></tr>
   <tr>
  <td colspan="2">Total  Amount :</td>
  <td colspan='2' align='right'><font color='#000000'><?=$TotalAmountcard+$TotalAmountchk+$TotalAmountPayatCounter+$TotalAmountCOD;?></font></td></tr>
  <tr>
<tr>
  <td colspan="2">Total Card Transactions :</td>
  <td colspan='2' align='right'><font color='#000000'><?=$Totalcard-1;?></font></td></tr>
  <tr>
  <td colspan="2">Total Pay at Counter :</td>
  <td colspan='2' align='right'><font color='#000000'><?=$TotalPayatCounter-1;?></font></td></tr>
  <tr>
  <td colspan="2">Total COD Transactions :</td>
  <td colspan='2' align='right'><font color='#000000'><?=$TotalCOD-1;?></font></td></tr>
<tr><td colspan="2">Total Cheque Transaction :</td><td colspan='2' align='right'><font color='#000000'><?=$Totalchk-1;?></font></td></tr>
<tr>
  <td colspan="2">Total Delegates Signed up for Tickets :</td><td colspan='2' align='right'><font color='#000000'><?=$TotalUsersRES[0][totusers];?></font></td></tr>
<tr>
  <td colspan="2">Delegates Signed up for Free Tickets :</td>
  <td colspan='2' align='right'><font color='#000000'><?=$TotalUsersFreeRES[0][totusersfree];?></font></td></tr>
<tr>
  <td colspan="2">Delegates Signed up for Paid Tickets :</td>
  <td colspan='2' align='right'><font color='#000000'><?=$TotalUsersPaidRES[0][totuserspaid];?></font></td></tr>
  <tr><td colspan="2">Total Users Signed up :</td><td colspan='2' align='right'><font color='#000000'><?=$TotalURES[0][ucount];?></font></td></tr>

<tr><td colspan="2">Total Organizers Registred :</td><td colspan='2' align='right'><font color='#000000'><?=$TotalOrgRES[0][orgcount];?></font></td></tr>
<tr><td colspan="2">Total Events Added :</td><td colspan='2' align='right'><font color='#000000'><?=$TotalEventsRES[0][eventcount];?></font></td></tr>
<tr><td colspan="2">Paid Events :</td><td colspan='2' align='right'><font color='#000000'><?=$TotalEventspaidRES[0][paidcount];?></font></td></tr>
<tr><td colspan="2">Free Events :</td><td colspan='2' align='right'><font color='#000000'><?=$TotalEventsfreeRES[0][freecount];?></font></td></tr>
<tr><td colspan="2">No Registration Events :</td><td colspan='2' align='right'><font color='#000000'><?=$TotalEventsnoregRES[0][noregcount];?></font></td></tr>
<tr><td colspan="2">No Unique Events :</td><td colspan='2' align='right'><font color='#000000'><?=$TotalunqEvents[0]['unqEventCount'];?></font></td></tr>
<tr><td colspan="2">No Unique Organizers :</td><td colspan='2' align='right'><font color='#000000'><?=$TotalunqUserID[0]['unqUserCount'];?></font></td></tr>
<tr><td colspan="2">No Unique Cities :</td><td colspan='2' align='right'><font color='#000000'><?=$TotalunqCityId[0]['unqCityCount'];?></font></td></tr>

</table>

<!-------------------------------ADD CONTENT PAGE ENDS HERE--------------------------------------------------------------->
	
	
	
	</div>
	</td>
  </tr>
</table>
	</div>	
</body>
</html>