<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<title>MeraEvents -Menu Content Management</title>
		<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
		<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.js"></script>	
        <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.js"></script>	
        <link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.css" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.js"></script>
<script language="javascript">
	
	
	function ClickHereToPrintpass()
{
	//alert("PrintTable");
	var DocumentContainer = document.getElementById('PrintTable');
	var WindowObject = window.open('PrintWindow',null,'width=900,height=650,top=50,left=50,toolbars=no,scrollbars=yes,status=no,resizable=yes');
	WindowObject.document.writeln(DocumentContainer.innerHTML);
	WindowObject.document.close();
	WindowObject.focus();
	WindowObject.print();
	WindowObject.close();
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
    <form action="AmountDepositedCOD.php" method="post" name="frmEofMonth">
    <table width="70%" align="center" class="tblcont">
 <tr><td align="center">Gharpay Settlement Report</td></tr>
	<tr>
	  <td width="35%" align="left" valign="middle">Deposited Date:&nbsp;<input type="text" name="txtSDt" value="<?php echo $SDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	
	 
	<tr>
 
    <!--tr><td colspan="3">Select an Event <select name="EventId" id="EventId" >
        <option value="">Select Event</option>
        <?php
		$TotalEventQueryRES = count($EventQueryRES);

		for($i=0; $i < $TotalEventQueryRES; $i++)
		{
		?>
         <option value="<?=$EventQueryRES[$i]['EventId'];?>" <?php if($EventQueryRES[$i]['EventId']==$EventId){?> selected="selected" <?php }?>><?=$EventQueryRES[$i]['Details'];?></option>
         <?php }?>
      </select></td></tr-->
      <tr><td>Event Id:&nbsp;<input type="text" name="eventIdSrch" id="eventIdSrch" value="<?= $EventId; ?>"></td></tr>
        <tr><td colspan="3">Select Organizer <select name="SerEventName" id="SerEventName" >
       <option value="">Select Organizer Name</option>	
				<?php 
				$SelectOrgNames1="SELECT name as orgDispName, id as Id FROM organization where status=1  ORDER BY name ASC";
                $OrgNames1=$Global->SelectQuery($SelectOrgNames1);
                $TotalOrgNames1=count($OrgNames1);
                for($i=0;$i<$TotalOrgNames1;$i++)
                {
                ?>
                <option value="<?php echo $OrgNames1[$i]['Id'];?>" <?php if($OrgNames1[$i]['Id'] == $_REQUEST['SerEventName']) { ?> selected="selected" <?php } ?>><?php echo $OrgNames1[$i]['orgDispName']; ?></option>
                <?php 
                } 
                ?>    
      </select></td></tr>
      <tr> <td width="30%" colspan="2" style="padding:10px;" align="center" valign="middle"><input type="submit" name="submit" value="Show Report" onclick="return SEdt_validate();" /></td></tr>
</table>
</form>
	<div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
	
	
<!-------------------------------ADD CONTENT PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans4').style.display='block';
</script>
<div id="PrintTable">
<table width='100%' border='1' cellpadding='0' cellspacing='0' >
			<thead>
            <tr bgcolor='#94D2F3'>
		  	<td class='tblinner' valign='middle' width='3%' align='center'>Sr. No.</td>
			

            <td class='tblinner' valign='middle' width='15%' align='center'>Event Details</td>
             <td class='tblinner' valign='middle' width='8%' align='center'>OrgName</td>
            <td class='tblinner' valign='middle' width='6%' align='center'>Reg No.</td>
            <td class='tblinner' valign='middle' width='8%' align='center'>Qty</td>
            <td class='tblinner' valign='middle' width='10%' align='center'>TotalTransAmount </td>
            <td class='tblinner' valign='middle' width='10%' align='center'>RefundedAmount </td>            
             <td class='tblinner' valign='middle' width='12%' align='center'>VerifiedAmount </td>
           
         
            
          </tr>
        </thead>
        
        <?php	
		$TotalAmount=0;
		$TotalAmount1=0;
		$TotalAmount2=0;
		$TotalAmount3=0;
		$TotalAmount4=0;
		 $TotalNetAmount=0;
		 $TotalRevenue=0;
		for($i = 0; $i < count($TransactionRES); $i++)
	{ ?>
		<tr>
			<td class='tblinner' valign='middle' width='3%' align='center' ><font color='#000000'><?=$cntTransactionRES;?></font></td>
			<td class='tblinner' valign='middle' width='15%' align='center' ><font color='#000000'><?=$Global->GetSingleFieldValue("select title as  Title from event where deleted=0 and Id='".$TransactionRES[$i]['EventId']."'");;?></font></td>
			<td class='tblinner' valign='middle' width='8%' align='left'><font color='#000000'><?=$Global->GetSingleFieldValue("select company as  Company from user as u,event as e where u.id=e.ownerid and e.id='".$TransactionRES[$i]['EventId']."'");?></font></td>
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
		
		 $CountQueryt = "SELECT  s.id as Id, s.signupdate as SignupDt,s.quantity as  Qty,(s.totalamount/s.quantity) Fees FROM eventsignup as s where 1  and s.depositdate!='0000-00-00 00:00:00'  AND  s.paymentgatewayid=2  $dates and eventid='".$TransactionRES[$i]['EventId']."'"; 
	 $CountQueryRESt=$Global->SelectQuery($CountQueryt); 
	 for($j = 0; $j < count($CountQueryRESt); $j++)
	{ 
	$Totalreg=$Totalreg+1;
	$Totalqty=$Totalqty+$CountQueryRESt[$j][Qty];
	$TotaltAmountcard=$TotaltAmountcard+($CountQueryRESt[$j][Qty]*$CountQueryRESt[$j][Fees]);
	
	}
		
	 $CountQuery = "SELECT  s.id as Id, s.signupdate as SignupDt,s.quantity as  Qty,(s.totalamount/s.quantity) Fees FROM eventsignup as s  where 1  and s.depositdate!='0000-00-00 00:00:00'   $dates AND (s.totalamount != 0 AND s.paymentgatewayid=2  and s.paymentstatus != 'Refunded') and eventid='".$TransactionRES[$i]['EventId']."'"; 
	 $CountQueryRES=$Global->SelectQuery($CountQuery); 
	 for($j = 0; $j < count($CountQueryRES); $j++)
	{ 
	$TotalAmountcard=$TotalAmountcard+($CountQueryRES[$j][Qty]*$CountQueryRES[$j][Fees]);
	
	}
	 $CountQueryr = "SELECT  s.id as Id, s.signupdate as SignupDt,s.quantity as  Qty,(s.totalamount/s.quantity) Fees FROM eventsignup as s  where 1 and s.depositdate!='0000-00-00 00:00:00'    $dates  AND (s.totalamount != 0 AND s.paymentgatewayid=2  and s.paymentstatus = 'Refunded') and eventid='".$TransactionRES[$i]['EventId']."'"; 
	 $CountQueryRESr=$Global->SelectQuery($CountQueryr); 
	 for($j = 0; $j < count($CountQueryRESr); $j++)
	{ 
	$TotalrAmountcard=$TotalrAmountcard+($CountQueryRESr[$j][Qty]*$CountQueryRESr[$j][Fees]);
	}
	
	
	
	
	$CountQuery1 = "SELECT  s.id as Id, s.signupdate as SignupDt,s.quantity as  Qty,(s.totalamount/s.quantity) Fees FROM eventsignup as s where 1 and s.depositdate!='0000-00-00 00:00:00'  $dates  AND (s.totalamount != 0 AND s.paymentgatewayid=2  and s.paymentstatus = 'Verified') and eventid='".$TransactionRES[$i]['EventId']."'"; 
	 $CountQueryRES1=$Global->SelectQuery($CountQuery1); 
	 for($j = 0; $j < count($CountQueryRES1); $j++)
	{ 
	
	$TotalAmountverified=round($TotalAmountverified+($CountQueryRES1[$j][Qty]*$CountQueryRES1[$j][Fees]),2);
	
	}
	
	 
			?>
			<td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'><?=$Totalreg;?></font></td>     		
			<td class='tblinner' valign='middle' width='8%' align='center'><font color='#000000'><?=$Totalqty;?></font></td>
			<td class='tblinner' valign='middle' width='10%' align='right'><font color='#000000'> <?=$TotaltAmountcard;?></font></td>
            <td class='tblinner' valign='middle' width='15%' align='right'><font color='#000000'> <?=$TotalrAmountcard;?></font></td>
            
            <td class='tblinner' valign='middle' width='12%' align='right'><font color='#000000'> <?=$TotalAmountverified;?></font></td>
           
            
            
          
           
          </tr>
          <?php
        $TotalAmount=$TotalAmount+$TotaltAmountcard;
		$TotalAmount1=$TotalAmount1+$TotalrAmountcard;
		$TotalAmount2=$TotalAmount2+$TotalAmountcard;
		$TotalAmount3=$TotalAmount3+$TotalAmountverified;
		
		 $TotalNetAmount=$TotalNetAmount+$netamt;
		 $Totaltobepaid=$Totaltobepaid+$tobepaid;
		 $TotalRevenue=$TotalRevenue+$totrev;
		 $TotalAmount5=round($TotalAmount5+$tobepaid-$netamt,2);
		$cntTransactionRES++;
	}?>
	<tr><td colspan="5" style="line-height:30px;"><strong>Total  Transactions Amount:</strong></td><td  align='right'><font color='#000000'> <?=$TotalAmount;?></font></td><td  align='right'><font color='#000000'> <?=$TotalAmount1;?></font></td><td  align='right'><font color='#000000'> <?=$TotalAmount3;?></font></td></tr>
    
   
</table>
</div>
<table width='90%'>
 <tr><td colspan="9" align="center">
<input src="<?=_HTTP_CF_ROOT;?>/ctrl/images/print.jpg" title="Print" name="button" value="Print" type="image" onclick="ClickHereToPrintpass()"></td></tr>
</table>
<!-------------------------------ADD CONTENT PAGE ENDS HERE--------------------------------------------------------------->
	<?php
	if($_REQUEST['txtSDt']!=""){
	$TAmount=0;
	$TDeposit=0;
	$DepositQuery = "SELECT s.id as Id,s.signupdate as SignupDt,s.quantity as Qty,(s.totalamount/s.quantity) as Fees,s.eventid as EventId FROM eventsignup AS s  where 1 and s.depositdate!='0000-00-00 00:00:00'  AND s.paymentgatewayid=2  $dates $EventId $SearchQuery  order by s.eventid DESC"; 
        $DepositRES=$Global->SelectQuery($DepositQuery);
		if(count($DepositRES)>0)
		{
	
	?>
<p>&nbsp;</p>
	<table width='100%' border='1' cellpadding='0' cellspacing='0' >
			<thead>
            <tr bgcolor='#94D2F3'>
		  	<td class='tblinner' valign='middle' width='3%' align='center'>Sr. No.</td>
				<td class='tblinner' valign='middle' width='3%' align='center'>Receipt No.</td>
              <td class='tblinner' valign='middle' width='12%' align='center'>RegDt.</td>
            <td class='tblinner' valign='middle' width='15%' align='center'>Event Details</td>
             <td class='tblinner' valign='middle' width='8%' align='center'>OrgName</td>
            <td class='tblinner' valign='middle' width='6%' align='center'>Amount</td>
            <td class='tblinner' valign='middle' width='6%' align='center'>Deposited</td>
               
            
            
         
            
          </tr>
        </thead>
        
        <?php	
	
		for($i = 0; $i < count($DepositRES); $i++)
	{ 
	$am=$DepositRES[$i]['Fees']*$DepositRES[$i]['Qty'];
	$p=($am*0.01)+40;
	if($p>140)
	{
	$p=140;
	}
	
	
	?>
		<tr>
			<td class='tblinner' valign='middle' width='3%' align='center' ><font color='#000000'><?=$i+1;?></font></td>
            <td class='tblinner' valign='middle' width='3%' align='center' ><font color='#000000'><?=$DepositRES[$i]['Id'];?></font></td>
            <td class='tblinner' valign='middle' width='3%' align='center' ><font color='#000000'><?=date('d/m/Y H:i:s',strtotime($DepositRES[$i]['SignupDt']));?></font></td>
			<td class='tblinner' valign='middle' width='15%' align='center' ><font color='#000000'><?=$Global->GetSingleFieldValue("select title from event where deleted=0 and id='".$DepositRES[$i]['EventId']."'");;?></font></td>
			<td class='tblinner' valign='middle' width='8%' align='left'><font color='#000000'><?=$Global->GetSingleFieldValue("select company from user as u,event as e where u.id=e.ownerid and e.id='".$DepositRES[$i]['EventId']."'");?></font></td>
          

			<td class='tblinner' valign='middle' width='6%' align='right'><font color='#000000'><?=$am;?></font></td> 
            <td class='tblinner' valign='middle' width='6%' align='right'><font color='#000000'><?=round(($am-$p),2);?></font></td>    		
			
            
            
          
           
          </tr>
          <?php
        $TAmount=$TAmount+$am;
		$TDeposit=$TDeposit+round(($am-$p),2);
	}?>
	<tr><td colspan="5" style="line-height:30px;"><strong>Total   Amount:</strong></td><td  align='right'><font color='#000000'> <?=$TAmount;?></font></td><td  align='right'><font color='#000000'> <?=$TDeposit;?></font></td></tr>
    
   
</table>
	
    
    
    <?php } }?>
	</div>
	</td>
  </tr>
</table>
	</div>	
</body>
</html>