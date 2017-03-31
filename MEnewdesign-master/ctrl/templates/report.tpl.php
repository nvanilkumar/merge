
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
		var SalesId = document.frmEofMonth.SalesId.value;
		
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

    
	var Tckwdz=document.getElementById('Tckwdz'+sId).value;
	var PaidBit=document.getElementById('PaidBit'+sId).value;
	var Exception=document.getElementById('Exception'+sId).value;
	var CompiOrg=document.getElementById('CompiOrg'+sId).value;
	var LeftForPayment=document.getElementById('LeftForPayment'+sId).value;
	var strtdt = document.frmEofMonth.txtSDt.value;
	var enddt = document.frmEofMonth.txtEDt.value;
    var SalesId = document.frmEofMonth.SalesId.value;
	var Eventid=document.frmEofMonth.Eventid.value;
	var eadd = document.frmEofMonth.eadd.value;
    var paid = document.frmEofMonth.paid.value;
	var Tck = document.frmEofMonth.Tck.value;
	var EPublished= document.frmEofMonth.EPublished.value;
	var EException= document.frmEofMonth.EException.value;
	  
window.location="eventchk.php?value=change&sId="+sId+"&Tckwdz="+Tckwdz+"&PaidBit="+PaidBit+"&CompiOrg="+CompiOrg+"&LeftForPayment="+LeftForPayment+"&txtSDt="+strtdt+"&txtEDt="+enddt+"&SalesId="+SalesId+"&Eventid="+Eventid+"&eadd="+eadd+"&paid="+paid+"&Tck="+Tck+"&EPublished="+EPublished+"&Exception="+Exception+"&EException="+EException;
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
	
	<script language="javascript">
  	document.getElementById('ans13').style.display='block';
</script>


 
<form action="" method="post" name="frmEofMonth" >
<?=$msg;?>

<table width="98%" border="0" cellpadding="3" cellspacing="3">
  <tr>
    <td colspan="2"><strong>Auto Report Generation</strong> </td>
  </tr><tr>
    <td colspan="2"><table width="50%" border="0" cellspacing="2" cellpadding="2">
  
</table> </td>
   
  </tr>
  <tr><td colspan="2"><table width="50%" align="left" class="tblcont">
	<tr>
	  <td width="35%" align="left" valign="middle">Start Date:&nbsp;<input type="text" name="txtSDt" value="<?php echo $SDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	  <td width="35%" align="left" valign="middle">End Date:&nbsp;<input type="text" name="txtEDt" value="<?php echo $EDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	<tr>
</table></td></tr>
<tr> <td width="19%" align="left" valign="middle"><input type="submit" name="submit" value="Show report" onclick="return SEdt_validate();" /></td><td width="81%"><strong>Total Events</strong> :<?=$rows;?></td>
</tr>
  <tr>
    <td colspan="2"><table width="100%" border="1" cellspacing="2" cellpadding="2">
  <tr>
    <td width="6%" align="center"><strong>Sno</strong></td>
     <td width="16%" align="center"><strong>Organizer</strong></td>
     <td width="30%" align="center"><strong>Events</strong></td>
       <td width="6%" align="center"><strong>Segment</strong></td>
    <td width="9%" align="center"><strong>Paid</strong></td>
    
    <td width="8%" align="center"><strong>TckWdz</strong></td>
    <td width="8%" align="center"><strong>Tot Trans</strong></td>
    <td width="8%" align="center"><strong>Tot Tck</strong></td>
    <td width="9%" align="center"><strong>Tot Amt</strong></td>
  </tr>
    <?
		$TotalEventsOfMonth = count($EventsOfMonth);
        
		for($i=0; $i < $TotalEventsOfMonth; $i++)
		{
		 
		 $sqlo="SELECT FirstName,Company,Email,Mobile FROM user where Id=".$EventsOfMonth[$i]['UserID'];
         $r=$Global->SelectQuery($sqlo);
		 $org=$r[0][FirstName]."<br/>".$r[0][Company]."<br/>".$r[0][Email]."<br/>".$r[0][Mobile];
		 
		
	    $eventUrl=_HTTP_SITE_ROOT."/event/".$EventsOfMonth[$i]['URL'];
		 if(substr_count($EventsOfMonth[$i]['OEmails'],"@meraevents.com")>=1)
		 {
		 $inner='bgcolor="#66FF66"';
		 }else{
		 $inner='';
		 }
		 
		$sqlTrans="select count(s.Id) as tottrans from EventSignup as s where s.EventId='".$EventsOfMonth[$i]['Id']."' and ((s.PaymentModeId=1 and s.PaymentTransId!='A1') or s.PaymentModeId=2)";
		  $tottrans=$Global->SelectQuery($sqlTrans);
		  
		   $sqlTck="select sum(s.Qty) as totqty from EventSignup as s where s.EventId='".$EventsOfMonth[$i]['Id']."' and ((s.PaymentModeId=1 and s.PaymentTransId!='A1') or s.PaymentModeId=2)";
		  $tottck=$Global->SelectQuery($sqlTck);
		  
		   $sqlAmt="select sum(s.Fees) as totamt from EventSignup as s where s.EventId='".$EventsOfMonth[$i]['Id']."' and ((s.PaymentModeId=1 and s.PaymentTransId!='A1') or s.PaymentModeId=2)";
		  $totamt=$Global->SelectQuery($sqlAmt);
        
		?>
  <tr <?=$inner;?>>
  <td><?=$i+1;?></td>
   <td align="center"><?=$org;?></td>
    <td><?=$EventsOfMonth[$i][Title];?></td>
     <td>&nbsp;</td>
     <td><?=$EventsOfMonth[$i][PaidBit];?></td>
     <td><?=$EventsOfMonth[$i][Tckwdz];?></td>
     <td><?=$tottrans[0][tottrans];?></td>
     <td><?=$tottck[0][totqty];?></td>
     <td><?=$totamt[0][totamt];?></td>
    
   
  
      
 
  <? }?>
  <tr><td colspan="5" align="right"><?=$pagination;?></td></tr>
  
</table></td>
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