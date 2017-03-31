<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<title>MeraEvents -Menu Content Management</title>
		<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
		<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>	
        <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script>	
        <link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.min.css.gz" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.min.js.gz"></script>
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
    <form action="TransbyEvent.php" method="post" name="frmEofMonth">
    <table width="70%" align="center" class="tblcont">
	<tr>
	  <td width="35%" align="left" valign="middle">Start Date:&nbsp;<input type="text" name="txtSDt" value="<?php echo $SDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	  <td width="35%" align="left" valign="middle">End Date:&nbsp;<input type="text" name="txtEDt" value="<?php echo $EDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	 
	<tr>
 
    <tr><td colspan="3">Select an Event <select name="EventId" id="EventId" >
        <option value="">Select Event</option>
        <?
		$TotalEventQueryRES = count($EventQueryRES);

		for($i=0; $i < $TotalEventQueryRES; $i++)
		{
		?>
         <option value="<?=$EventQueryRES[$i]['EventId'];?>" <? if($EventQueryRES[$i]['EventId']==$_REQUEST[EventId]){?> selected="selected" <? }?>><?=$EventQueryRES[$i]['Details'];?></option>
         <? }?>
      </select></td></tr>
        <tr><td colspan="3">Select Organizer <select name="SerEventName" id="SerEventName" >
       <option value="">Select Organizer Name</option>	
				<?php 
				$SelectOrgNames1="SELECT orgDispName, Id FROM orgdispname where Active=1  ORDER BY orgDispName ASC";
                $OrgNames1=$Global->SelectQuery($SelectOrgNames1);
                $TotalOrgNames1=count($OrgNames1);
                for($i=0;$i<$TotalOrgNames1;$i++)
                {
                ?>
                <option value="<?php echo $OrgNames1[$i]['Id'];?>" <?php if($OrgNames1[$i]['Id'] == $_REQUEST['SerEventName']) { ?> selected="selected" <?php } ?>><?php echo $OrgNames1[$i]['orgDispName']; ?></option>
                <?php 
                } 
                ?>    
      </select>&nbsp;Select Status <select name="Status" id="Status" >
       <option value="">Status</option>	
			
                <option value="Pending" <?php if($_REQUEST['Status']=="Pending") { ?> selected="selected" <?php } ?>>Pending</option>
                  <option value="Done" <?php if($_REQUEST['Status']=="Done") { ?> selected="selected" <?php } ?>>Done</option>
              
      </select> &nbsp; <input type="checkbox" name="compeve" id="compeve" <? if($_REQUEST[compeve]==1){?> checked="checked" <? }?> value="1" /> Completed</td></tr>
      <tr> <td width="30%" colspan="2" style="padding:10px;" align="center" valign="middle"><input type="submit" name="submit" value="Show Report" onclick="return SEdt_validate();" /></td></tr>
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
			
            <td class='tblinner' valign='middle' width='4%' align='center'>Date</td>
            <td class='tblinner' valign='middle' width='5%' align='center'>Event Details</td>
             <td class='tblinner' valign='middle' width='8%' align='center'>OrgName</td>
            <td class='tblinner' valign='middle' width='4%' align='center'>Reg No.</td>
            <td class='tblinner' valign='middle' width='5%' align='center'>Qty</td>
            <td class='tblinner' valign='middle' width='9%' align='center'>Total Trans Amount </td>
            <td class='tblinner' valign='middle' width='8%' align='center'>Refunded Amount </td>
             <td class='tblinner' valign='middle' width='6%' align='center'>Trans Amount </td>
             <td class='tblinner' valign='middle' width='6%' align='center'>Verified Amount </td>
             <td class='tblinner' valign='middle' width='6%' align='center'>Net Amount </td>
              <td class='tblinner' valign='middle' width='7%' align='center'>Total Revenue </td>
              <td class='tblinner' valign='middle' width='6%' align='center'>Amount to be Paid </td>           
              <td class='tblinner' valign='middle' width='6%' align='center'>Amount Paid </td> 
              <td class='tblinner' valign='middle' width='6%' align='center'>Final Amount to be Paid </td>
            <td class='tblinner' valign='middle' width='9%' align='center'>UploadDocs</td>
            
          </tr>
        </thead>
        
        <?	
		$TotalAmount=0;
		$TotalAmount1=0;
		$TotalAmount2=0;
		$TotalAmount3=0;
		 $TotalNetAmount=0;
		 $TotalRevenue=0;
		for($i = 0; $i < count($TransactionRES); $i++)
	{ ?>
		<tr>
			<td class='tblinner' valign='middle' width='3%' align='center' ><font color='#000000'><?=$cntTransactionRES;?></font></td>
			<td class='tblinner' valign='middle' width='4%' align='center'><font color='#000000'><?=date("F j, Y, g:i a",strtotime($TransactionRES[$i]['StartDt'])). " to ".date("F j, Y, g:i a",strtotime($TransactionRES[$i]['EndDt']));?></font></td>
			<td class='tblinner' valign='middle' width='5%' align='center' ><font color='#000000'><?=$TransactionRES[$i]['Title'];?></font></td>
			<td class='tblinner' valign='middle' width='8%' align='left'><font color='#000000'><?=$Global->GetSingleFieldValue("select Company from user where Id='".$TransactionRES[$i]['UserID']."'");?></font></td>
            <?
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
		
		 $CountQueryt = "SELECT  Id, SignupDt, Qty, Fees FROM EventSignup  where 1  AND (Fees != 0 AND (PaymentTransId != 'A1')  ) and EventId='".$TransactionRES[$i]['EventId']."'"; 
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
	 $CountQueryr = "SELECT  Id, SignupDt, Qty, Fees FROM EventSignup  where 1  AND (Fees != 0 AND (PaymentTransId != 'A1')  and eChecked = 'Refunded') and EventId='".$TransactionRES[$i]['EventId']."'"; 
	 $CountQueryRESr=$Global->SelectQuery($CountQueryr); 
	 for($j = 0; $j < count($CountQueryRESr); $j++)
	{ 
	$TotalrAmountcard=$TotalrAmountcard+($CountQueryRESr[$j][Qty]*$CountQueryRESr[$j][Fees]);
	
	}
	
	 $CountQuery1 = "SELECT  Id, SignupDt, Qty, Fees FROM EventSignup  where 1  AND (Fees != 0 AND (PaymentTransId != 'A1')  and eChecked = 'Verified') and EventId='".$TransactionRES[$i]['EventId']."'"; 
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
		   $totrev=$TotalNetverified-$tobepaid;
			?>
			<td class='tblinner' valign='middle' width='4%' align='left'><font color='#000000'><?=$Totalreg;?></font></td>     		
			<td class='tblinner' valign='middle' width='5%' align='right'><font color='#000000'><?=$Totalqty;?></font></td>
			<td class='tblinner' valign='middle' width='9%' align='right'><font color='#000000'> <?=$TotaltAmountcard;?></font></td>
            <td class='tblinner' valign='middle' width='8%' align='right'><font color='#000000'> <?=$TotalrAmountcard;?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='right'><font color='#000000'> <?=$TotalAmountcard;?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='right'><font color='#000000'> <?=$TotalAmountverified;?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='right'><font color='#000000'> <?=$TotalNetverified;?></font></td>
            <td class='tblinner' valign='middle' width='7%' align='right'><font color='#000000'> <?=$totrev;?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='right'><font color='#000000'> <?=$tobepaid;?></font></td>
            <td class='tblinner' valign='middle' width='6%' align='right'><font color='#000000'> <?=$netamt;?></font></td>
              <td class='tblinner' valign='middle' width='6%' align='right'><font color='#000000'> <?=round($tobepaid-$netamt,2);?></font></td>
            <td class='tblinner' valign='middle' width='9%' align='left'><?=$conf;?>,&nbsp;&nbsp;&nbsp;<a onclick="uploaddoc(<?=$TransactionRES[$i]['EventId'];?>)" href="#">Click Here</a></td>
            
          </tr>
          <?
        $TotalAmount=$TotalAmount+$TotaltAmountcard;
		$TotalAmount1=$TotalAmount1+$TotalrAmountcard;
		$TotalAmount2=$TotalAmount2+$TotalAmountcard;
		$TotalAmount3=$TotalAmount3+$TotalAmountverified;
		$TotalAmount4=$TotalAmount4+$TotalNetverified;
		 $TotalNetAmount=$TotalNetAmount+$netamt;
		 $Totaltobepaid=$Totaltobepaid+$tobepaid;
		 $TotalRevenue=$TotalRevenue+$totrev;
		 $TotalAmount5=round($TotalAmount5+$tobepaid-$netamt,2);
		$cntTransactionRES++;
	}?>
	<tr><td colspan="6" style="line-height:30px;"><strong>Total  Transactions Amount:</strong></td><td  align='right'><font color='#000000'> <?=$TotalAmount;?></font></td><td  align='right'><font color='#000000'> <?=$TotalAmount1;?></font></td><td  align='right'><font color='#000000'> <?=$TotalAmount2;?></font></td><td  align='right'><font color='#000000'> <?=$TotalAmount3;?></font></td><td  align='right'><font color='#000000'> <?=$TotalAmount4;?></font></td><td  align='right'><font color='#000000'> <?=$TotalRevenue;?></font></td><td  align='right'><font color='#000000'> <?=$Totaltobepaid;?></font></td><td  align='right'><font color='#000000'> <?=$TotalNetAmount;?></font></td><td  align='right'><font color='#000000'> <?=$TotalAmount5;?></font></td></tr>
  

</table>
<!-------------------------------ADD CONTENT PAGE ENDS HERE--------------------------------------------------------------->
	
	
	
	</div>
	</td>
  </tr>
</table>
	</div>	
</body>
</html>