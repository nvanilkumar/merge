
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<title>MeraEvents -Menu Content Management</title>
		<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
		<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortable.js"></script>	
        <script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortpagi.js"></script>
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.css" />
<script type="text/javascript" language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.js"></script>
<script language="javascript">
	function SEdt_validate()
	{
		var strtdt = document.frmEofMonth.txtSDt.value;
		var enddt = document.frmEofMonth.txtEDt.value;
		var SalesId = document.frmEofMonth.SalesId.value;
		if(SalesId=="")
			{
				alert('Please Select Sales Person.');
				document.frmEofMonth.SalesId.focus();
				return false;
			}
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
	<div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
	
	

 
<form action="" method="post" name="frmEofMonth" >
<?php echo $msg;?>
<script language="javascript">
  	document.getElementById('ans13').style.display='block';
</script>
<table width="90%" border="0" cellpadding="3" cellspacing="3">
  <tr>
    <td colspan="2"><strong>Monthly report of Sales Person</strong> </td>
  </tr>
  
  
  <tr>
    <td> Select Sales Person :</td>
    <td width="83%"><label>
      <select name="SalesId" id="SalesId" >
        <option value="">Select</option>
        <?php 
		$TotalSalesQueryRES = count($SalesQueryRES);

		for($i=0; $i < $TotalSalesQueryRES; $i++)
		{
		?>
         <option value="<?php echo $SalesQueryRES[$i]['SalesId'];?>" <?php  if($SalesQueryRES[$i]['SalesId']==$_REQUEST[SalesId]){?> selected="selected" <?php  }?>><?php echo $SalesQueryRES[$i]['SalesName'];?></option>
         <?php  }?>
      </select>
    </label></td>
  </tr>
   
  <tr>
    <td colspan="2"><table width="50%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td>Card : <input type="checkbox" name="card" value="1"  <?php  if($_REQUEST['card']==1){?> checked="checked" <?php  } ?> /> </td>
    <td>Cheque : <input type="checkbox" name="chq" value="1" <?php  if($_REQUEST['chq']==1){?> checked="checked" <?php  } ?>/> </td>
    <td>Free : <input type="checkbox" name="free" value="1" <?php  if($_REQUEST['free']==1){?> checked="checked" <?php  } ?>/> </td>
  </tr>
</table>
 </td>
   
  </tr>
  <tr><td colspan="2"><table width="50%" align="left" class="tblcont">
	<tr>
	  <td width="35%" align="left" valign="middle">Start Date:&nbsp;<input type="text" name="txtSDt" value="<?php echo $SDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	  <td width="35%" align="left" valign="middle">End Date:&nbsp;<input type="text" name="txtEDt" value="<?php echo $EDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	 
	<tr>
</table></td></tr>
<tr> <td width="17%" align="left" valign="middle"><input type="submit" name="submit" value="Show report" onclick="return SEdt_validate();" /></td></tr>
  <tr>
    <td colspan="2"><table width="100%" border="1" cellspacing="2" cellpadding="2">
  <tr>
    <td align="center"><strong>Organizers</strong></td>
    <td align="center"><strong>Events</strong></td>
    <td align="center"><strong>TicketSold</strong></td>
    <td align="center"><strong>Amount</strong></td>
  </tr>
    <?php 
		$TotalEventsOfMonth = count($EventsOfMonth);
          $totaltick=0;
		$totalamt=0;
                for($i=0; $i < $TotalEventsOfMonth; $i++)
                {
                   $totaltick=$totaltick+$EventsOfMonth[$i]['Tickets_Sold'];
                   $totalamt=$totalamt+$EventsOfMonth[$i]['Amount']+$EventsOfMonth[$i]['taxAmount'];
                    ?>
                    <tr>
                        <td><?php echo $EventsOfMonth[$i]['company'] ?></td>
                        <td><?php echo $EventsOfMonth[$i]['title'] ?></td>
                        <td align="center"><?php echo $EventsOfMonth[$i]['Tickets_Sold'] ?></td>
                        <td align="center"><?php echo $EventsOfMonth[$i]['Amount']+$EventsOfMonth[$i]['taxAmount'] ?></td>
                    </tr>
                    
            <?php     } ?>
            
            
            <?php /*
		for($i=0; $i < $TotalEventsOfMonth; $i++)
		{
		
		$TickQuery = "SELECT t.Id FROM tickets t where t.EventId=".$EventsOfMonth[$i][Id];
 $ResTickQuery = $Global->SelectQuery($TickQuery);
 $TotalResTickQuery = count($ResTickQuery);
        $tickcount=0;
		
		for($j=0; $j < $TotalResTickQuery; $j++)
		{
		 
		 $sqlthour="select sum(NumOfTickets) as nocnt from eventsignupticketdetails where TicketId='".$ResTickQuery[$j]['Id']."' and EventSignupId in(select s.Id FROM EventSignup AS s , events AS e, Attendees AS a  WHERE s.Id=a.EventSIgnupId and s.EventId = e.Id $stdt $incpay  and s.EventId='".$EventsOfMonth[$i][Id]."')";
		 $ResTickCnt = $Global->SelectQuery($sqlthour);
		 $tickcount=$tickcount+$ResTickCnt[0][nocnt];
		}
		 $totaltick= $totaltick+$tickcount;
		if($tickcount>0){
		?>
  <tr>
    <td><?php echo $Global->GetSingleFieldValue("select Company from user where Id='".$EventsOfMonth[$i][UserID]."'");?></td>
    <td><?php echo $EventsOfMonth[$i][Title];?></td>
    <td align="center"><?php 
	
	
 
		echo $tickcount;


?></td>
    <td align="center"><?php 
    $TickQuery1 = "SELECT t.Id,t.ServiceTax,t.ServiceTaxValue FROM tickets t where t.EventId=".$EventsOfMonth[$i][Id];
 $ResTickQuery1 = $Global->SelectQuery($TickQuery1);
 $TotalResTickQuery1 = count($ResTickQuery1);
        $tickamt=0;
		for($j=0; $j < $TotalResTickQuery1; $j++)
		{
		 
		 $sqlthour1="select sum(TicketAmt) as noamt from eventsignupticketdetails where TicketId='".$ResTickQuery1[$j]['Id']."' and EventSignupId in(select s.Id FROM EventSignup AS s , events AS e, Attendees AS a  WHERE s.Id=a.EventSIgnupId and s.EventId = e.Id $stdt $incpay and s.EventId='".$EventsOfMonth[$i][Id]."')";
		 $ResTickCnt1 = $Global->SelectQuery($sqlthour1);
		 if($ResTickQuery1[$j]['ServiceTax']==1){
		  $tickamt=$tickamt+round($ResTickCnt1[0][noamt]+($ResTickCnt1[0][noamt]*($ResTickQuery1[$j]['ServiceTaxValue']/100)));
		  }else{
		   $tickamt=$tickamt+$ResTickCnt1[0][noamt];
		  }
		}
		 $totalamt=$totalamt+$tickamt;
		echo $tickamt;
	?></td>
  </tr>
  <?php  }}?> */?>
  <tr><td align="center"><strong>Total</strong></td>
      <td align="center"><strong>&nbsp;</strong></td>
      <td align="center"><strong><?php echo $totaltick;?></strong></td>
      <td align="center"><strong><?php echo $totalamt;?></strong></td></tr>
</table>
</td>
  </tr>
 
</table>
</form>

<!-------------------------------ADD CONTENT PAGE ENDS HERE--------------------------------------------------------------->
	
	
	
	</div>
	</td>
  </tr>
</table>
	</div>	
</body>
</html>