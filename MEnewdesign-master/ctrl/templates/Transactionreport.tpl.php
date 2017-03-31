<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<title>MeraEvents -Menu Content Management</title>
		<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
		<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>	
        <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script>	
        <link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.css" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.js"></script>
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
        <td width="14%">Include Offline Tr:
        <input type="checkbox" name="Offline" id="Offline" <?php if(isset($_REQUEST[Offline])){ ?> checked="checked" <?php }?> value="1"/> </td>
			  
				<td align="left" valign="middle" class="tblcont">City :
					<select name="selCity" id="selCity">
					<option value="">Select City</option>
				<?php
					for($i=0; $i < count($dtlCities); $i++)
					{
					//Rather than the city id we are sending the city name here for uniqueness 
				?>	<option value="<?=$dtlCities[$i]['id']?>" <?php if($_REQUEST['selCity']==$dtlCities[$i]['id']) {?> selected="selected" <?php }?>><?=$dtlCities[$i]['name']?></option>
				<?php	
					}
				?>
					</select>
				</td>
			</tr>

    <tr><td>&nbsp;</td><td width="25%" align="left" valign="middle"><input type="submit" name="submit" value="Show Report" onclick="return SEdt_validate();" /></td></tr>
</table>
</form>
	<div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
	
	
<!-------------------------------ADD CONTENT PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans4').style.display='block';
</script>
<table width='90%' border='1' cellpadding='0' cellspacing='0' >
			<thead>
            <tr bgcolor='#94D2F3'>
		  	<td class='tblinner' valign='middle' width='4%' align='center'>Sr. No.1</td>
			<td class='tblinner' valign='middle' width='14%' align='center'>Receipt No.</td>
            <td class='tblinner' valign='middle' width='11%' align='center'>Date</td>
            <td class='tblinner' valign='middle' width='27%' align='center'>Event Details</td>
            <td class='tblinner' valign='middle' width='18%' align='center'>Transaction No.</td>
            <td class='tblinner' valign='middle' width='5%' align='center'>Qty</td>
            <td class='tblinner' valign='middle' width='11%' align='center'>Amount (Rs.)</td>
            <td class='tblinner' valign='middle' width='10%' align='center'>Status</td>
          </tr>
        </thead>
        
        <?php	
		$TotalAmountcard=0;
		$TotalAmountPayatCounter=0;
		$TotalAmountchk=0;
		
		$Totalcard=$TotalPayatCounter=$TotalCOD=$Totalchk=0;
		while($ctrec=mysqli_fetch_assoc($TransactionRES))
		{
			
			$Totalcard++;
			?>
			<tr>
                <td class='tblinner' valign='middle' width='4%' align='center' ><font color='#000000'><?=$Totalcard;?></font></td>
                <td class='tblinner' valign='middle' width='14%' align='center'><font color='#000000'><?=$ctrec['id'];?></font></td>
                <td class='tblinner' valign='middle' width='11%' align='center' ><font color='#000000'><?=
                $common->convertTime($ctrec['signupdate'],DEFAULT_TIMEZONE,TRUE);
                ?></font></td>
                <td class='tblinner' valign='middle' width='27%' align='left'><font color='#000000'><?=$ctrec['title'];?></font></td>
                <td class='tblinner' valign='middle' width='18%' align='left'><font color='#000000'>TR:<?=$ctrec['paymenttransactionid'];?></font></td>     		
                <td class='tblinner' valign='middle' width='5%' align='right'><font color='#000000'><?=$ctrec['quantity'];?></font></td>
                <td class='tblinner' valign='middle' width='11%' align='right'><font color='#000000'><?=$ctrec['Amount'];?></font></td>
                <td class='tblinner' valign='middle' width='10%' align='right'><font color='#000000'><?=$ctrec['paymentstatus'] ;?></font></td>
            </tr>
            <?php
			$TotalAmountcard += $ctrec['Amount'] ;
			
		}
		
	?>
	<tr><td colspan="4" style="line-height:30px;"><strong>Total Card Transactions Amount:</strong></td><td colspan='3' align='right'><font color='#000000'>Rs. <?=$TotalAmountcard;?></font></td><td>&nbsp;</td></tr>
    
    <?php
	while($pcrec=mysqli_fetch_assoc($PayatCounterRES))
	{
		$TotalPayatCounter +=1;// $TransactionRES[$i]['Qty'];
		?>
        <tr>
			<td class='tblinner' valign='middle' width='4%' align='center' ><font color='#000000'><?=$TotalPayatCounter;?></font></td>
			<td class='tblinner' valign='middle' width='14%' align='center'><font color='#000000'><?=$pcrec['id'];?></font></td>
			<td class='tblinner' valign='middle' width='11%' align='center' ><font color='#000000'><?=$pcrec['signupdate'];?></font></td>
			<td class='tblinner' valign='middle' width='27%' align='left'><font color='#000000'><?=$pcrec['title'];?></font></td>
			<td class='tblinner' valign='middle' width='18%' align='left'><font color='#000000'>PayatCounter</font></td>     		
			<td class='tblinner' valign='middle' width='5%' align='right'><font color='#000000'><?=$pcrec['quantity'];?></font></td>
			<td class='tblinner' valign='middle' width='11%' align='right'><font color='#000000'><?=$pcrec['Amount'];?></font></td>
            <td class='tblinner' valign='middle' width='10%' align='right'><font color='#000000'><?=$pcrec['delStatus']?></font></td>
          </tr>
        <?php
		$TotalAmountPayatCounter += $pcrec['Amount'];
		
	}
	?>
    
    
	<tr><td colspan="4" style="line-height:30px;"><strong>Total Pay at Counter Amount:</strong></td><td colspan='3' align='right'><font color='#000000'>Rs. <?=$TotalAmountPayatCounter;?></font></td><td>&nbsp;</td></tr>
    <?php
	while($codrec=mysqli_fetch_assoc($CODRES))
	{
		$TotalCOD +=1;// $TransactionRES[$i]['Qty'];
		?>
        <tr>
			<td class='tblinner' valign='middle' width='4%' align='center' ><font color='#000000'><?=$TotalCOD;?></font></td>
			<td class='tblinner' valign='middle' width='14%' align='center'><font color='#000000'><?=$codrec['id'];?></font></td>
			<td class='tblinner' valign='middle' width='11%' align='center' ><font color='#000000'><?=$codrec['signupdate'];?></font></td>
			<td class='tblinner' valign='middle' width='27%' align='left'><font color='#000000'><?=$codrec['title'];?></font></td>
			<td class='tblinner' valign='middle' width='18%' align='left'><font color='#000000'>Cash on Delivery</font></td>     		
			<td class='tblinner' valign='middle' width='5%' align='right'><font color='#000000'><?=$codrec['quantity'];?></font></td>
			<td class='tblinner' valign='middle' width='11%' align='right'><font color='#000000'><?=$codrec['Amount'];?></font></td>
            <td class='tblinner' valign='middle' width='10%' align='right'><font color='#000000'><?=$codrec['status'];?></font></td>
          </tr>
        <?php
		$TotalAmountCOD += $codrec['Amount'];
		
	}
	?>
	
	
	<tr><td colspan="4" style="line-height:30px;"><strong>Total Cash on Delivery Amount:</strong></td><td colspan='3' align='right'><font color='#000000'>Rs. <?=$TotalAmountCOD;?></font></td><td>&nbsp;</td></tr>
    <?php
	while($Chqrec=mysqli_fetch_assoc($ChqTranRES))
	{
		$Totalchk +=1;// $ChqTranRES[$i]['Qty'];
		$ChqStatus = '';
		//Cheque Status : Pending[0] / Received[1] / Deposited[2] / Cleared[3] / Failure [4] 
		if($Chqrec['Cleared']==0) $ChqStatus = "Pending";
		if($Chqrec['Cleared']==1) $ChqStatus = "Received";
		if($Chqrec['Cleared']==2) $ChqStatus = "Deposited";
		if($Chqrec['Cleared']==3) $ChqStatus = "Cleared";
		if($Chqrec['Cleared']==4) $ChqStatus = "Failure";
		?>
        <tr>
				<td class='tblinner' valign='top' width='4%' align='center'><font color='#000000'><?=$Totalchk;?></font></td>
				<td class='tblinner' valign='top' width='14%' align='center'><font color='#000000'><?=$Chqrec['Id'];?></font></td>
				<td class='tblinner' valign='top' width='11%' align='center' ><font color='#000000'><?=$Chqrec['SignupDt'];?></font></td>
				<td class='tblinner' valign='top' width='27%' align='left'><font color='#000000'><?=$Chqrec['Title'];?></font></td>
				<td class='tblinner' valign='top' width='18%' align='left'><font color='#000000'>CH: <?=$Chqrec['ChqNo'];?><br /> 
		    Bank: <?=$ChqTranRES[$i]['ChqBank']."<br /> Date: ".$ChqTranRES[$i]['ChqDt']."<br />Status:".$ChqStatus;?></font></td>
				<td class='tblinner' valign='top' width='5%' align='right'><font color='#000000'><?=$Chqrec['Qty'];?></font></td>
			<td class='tblinner' valign='top' width='11%' align='right'><font color='#000000'><?=$Chqrec['Amount'];?></font></td>
                <td class='tblinner' valign='top' width='10%' align='right'><font color='#000000'><?=$ChqStatus;?></font></td>
		  </tr>
        <?php
		$TotalAmountchk += $Chqrec['Amount'];
		
	}
	
	
?>
<tr><td colspan="4" style="line-height:30px;"><strong>Total Cheque Transactions Amount:</strong></td><td colspan='3' align='right'><font color='#000000'>Rs. <?=$TotalAmountchk;?></font></td><td>&nbsp;</td></tr>
<tr><td colspan="4" style="line-height:30px;"><strong>Total :</strong></td><td colspan='3' align='right'><font color='#000000'>Rs. <?=$TotalAmountcard+$TotalAmountchk+$TotalAmountPayatCounter+$TotalAmountCOD;?></font></td><td>&nbsp;</td></tr>
<tr>
  <td colspan="4">Total Card Transactions Amount :</td>
  <td colspan='4' align='right'><font color='#000000'> <?=$TotalAmountcard;?></font></td></tr>
  <tr>
  <tr>
  <td colspan="4">Total Cheque Transactions Amount :</td>
  <td colspan='4' align='right'><font color='#000000'><?=$TotalAmountchk;?></font></td></tr>
  <tr>
  <tr>
  <td colspan="4">Total PayatCounter Amount :</td>
  <td colspan='4' align='right'><font color='#000000'><?=$TotalAmountPayatCounter;?></font></td></tr>
  <tr>
  <td colspan="4">Total CashonDelivery Amount :</td>
  <td colspan='4' align='right'><font color='#000000'><?=$TotalAmountCOD;?></font></td></tr>
   <tr>
  <td colspan="4">Total  Amount :</td>
  <td colspan='4' align='right'><font color='#000000'><?=$TotalAmountcard+$TotalAmountchk+$TotalAmountPayatCounter+$TotalAmountCOD;?></font></td></tr>
  <tr>
<tr>
  <td colspan="4">Total Card Transactions :</td>
  <td colspan='4' align='right'><font color='#000000'><?=$Totalcard;?></font></td></tr>
  <tr>
  <td colspan="4">Total Pay at Counter :</td>
  <td colspan='4' align='right'><font color='#000000'><?=$TotalPayatCounter;?></font></td></tr>
  <tr>
  <td colspan="4">Total COD Transactions :</td>
  <td colspan='4' align='right'><font color='#000000'><?=$TotalCOD;?></font></td></tr>
<tr><td colspan="4">Total Cheque Transaction :</td><td colspan='4' align='right'><font color='#000000'><?=$Totalchk;?></font></td></tr>
<tr>
  <td colspan="4">Total Delegates Signed up for Tickets :</td><td colspan='4' align='right'><font color='#000000'><?=$TotalUsersRES[0][totusers];?></font></td></tr>
<tr>
  <td colspan="4">Delegates Signed up for Free Tickets :</td>
  <td colspan='4' align='right'><font color='#000000'><?=$TotalUsersFreeRES[0][totusersfree];?></font></td></tr>
<tr>
  <td colspan="4">Delegates Signed up for Paid Tickets :</td>
  <td colspan='4' align='right'><font color='#000000'><?=$TotalUsersPaidRES[0][totuserspaid];?></font></td></tr>
  <tr><td colspan="4">Total Users Signed up :</td><td colspan='4' align='right'><font color='#000000'><?=$TotalURES[0][ucount];?></font></td></tr>

<tr><td colspan="4">Total Organizers Registred :</td><td colspan='4' align='right'><font color='#000000'><?=$TotalOrgRES[0][orgcount];?></font></td></tr>
<tr><td colspan="4">Total Events Added :</td><td colspan='4' align='right'><font color='#000000'><?=$TotalEventsRES[0][eventcount];?></font></td></tr>
<tr><td colspan="4">Paid Events :</td><td colspan='4' align='right'><font color='#000000'><?=$TotalEventspaidRES[0][paidcount];?></font></td></tr>
<tr><td colspan="4">Free Events :</td><td colspan='4' align='right'><font color='#000000'><?=$TotalEventsfreeRES[0][freecount];?></font></td></tr>
<tr><td colspan="4">No Registration Events :</td><td colspan='4' align='right'><font color='#000000'><?=$TotalEventsnoregRES[0][noregcount];?></font></td></tr>
<tr><td colspan="4">No Unique Events :</td><td colspan='4' align='right'><font color='#000000'><?=$TotalunqEvents[0]['unqEventCount'];?></font></td></tr>
<tr><td colspan="4">No Unique Organizers :</td><td colspan='4' align='right'><font color='#000000'><?=$TotalunqUserID[0]['unqUserCount'];?></font></td></tr>
<tr><td colspan="4">No Unique Cities :</td><td colspan='4' align='right'><font color='#000000'><?=$TotalunqCityId[0]['unqCityCount'];?></font></td></tr>

</table>
<!-------------------------------ADD CONTENT PAGE ENDS HERE--------------------------------------------------------------->
	
	
	
	</div>
	</td>
  </tr>
</table>
	</div>	
</body>
</html>