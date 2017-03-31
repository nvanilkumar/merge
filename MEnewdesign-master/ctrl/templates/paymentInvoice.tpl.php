<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents -Master Management - Event Search Management</title>
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>
     <link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.min.css.gz" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.min.js.gz"></script>
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
	<script language="javascript" type="text/javascript">
							function val_form()
							{
								var EventId = document.getElementById('EventId').value;
								var Comm = document.getElementById('Comm').value;
								
								
								if(EventId == '')
								{
									alert("Please Select Event Title");
									document.getElementById('EventId').focus();
									return false;
								}
								if(Comm == '')
								{
									alert("Please Enter Commision");
									document.getElementById('Comm').focus();
									return false;								
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
								return true; 
							}
							
							
							function loadper(val)
							{
							window.location="paymentInvoice.php?EventId="+val;
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
<!-------------------------------DISPLAY ALL EVENT PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans6').style.display='block';
</script>
<link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/pagi_sort.min.css.gz" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/sortpagi.min.js.gz"></script>
<div align="center" style="width:100%">
   <form action="" method="post" name="frmEofMonth" onsubmit="return val_form();">
	<table width="100%" >
      <tr>
        <td width="22%"  align="left" class="headtitle">  Select an Event <font color=red>*</font> </td><td width="78%"><select name="EventId" id="EventId" onchange="loadper(this.value);" >
        <option value="">Select Event</option>
        <?
		$TotalEventQueryRES = count($EventQueryRES);

		for($i=0; $i < $TotalEventQueryRES; $i++)
		{
		?>
         <option value="<?=$EventQueryRES[$i]['EventId'];?>" <? if($EventQueryRES[$i]['EventId']==$_REQUEST[EventId]){?> selected="selected" <? }?>><?=$EventQueryRES[$i]['Details'];?></option>
         <? }?>
      </select>
      </td> 
      </tr>
        <tr>
          <td  align="left">Commission<font color=red>*</font> (eg. 10 for 10%)</td><td><input type="text" name="Commm" value="<?=$perc;?>" id="Comm" size="40" /></td>
        </tr>
         <tr>
          <td  align="left" colspan="2"><table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td>PayatCounter :<input type="checkbox" name="payatcounter" id="payatcounter" value="1"/> </td>
    <td>Cheque :<input type="checkbox" name="Cheque" id="Cheque" value="1"/></td>
    <td>COD :<input type="checkbox" name="COD" id="COD" value="1"/></td>
  </tr>
</table>
</td>
        </tr>
        <tr><td colspan="2">
        <table width="50%">
        <tr>
	  <td width="35%" align="left" valign="middle">Start Date:&nbsp;<input type="text" name="txtSDt" value="<?php echo $SDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	  <td width="35%" align="left" valign="middle">End Date:&nbsp;<input type="text" name="txtEDt" value="<?php echo $EDt; ?>" size="8" onfocus="showCalendarControl(this);"  /></td>
	 
	</tr></table>
        </td></tr>
        <tr>
          <td  align="left">Cheque No</td><td><input type="text" name="Chqno" id="Chqno" size="40" /></td>
        </tr>
        <tr>
          <td align="left">Cheque Date</td><td><input type="text" name="Chqdt" id="Chqdt" size="40" /></td>
        </tr>
      
        <tr>
          <td colspan="2" align="left"><table width="100%" border="0" cellspacing="2" cellpadding="2">
    <tr height="20">
          <td height="25" colspan="2"><strong>Organizer Account   Details:</strong></td>
        </tr>
        <tr height="20">
          <td width="22%" height="25"> Beneficiary  Name</td>
          <td width="78%" height="25"><input type="text" name="AccName" value="<?=$BankQueryRES[0][AccName];?>" id="AccName" size="40"/></td>
        </tr>
        <tr height="20">
          <td height="25"> Beneficiary Ac/No</td>
          <td height="25"><input type="text" name="Accno" value="<?=$BankQueryRES[0][AccNo];?>" id="Accno" size="40"/></td>
        </tr>
        <tr height="20">
          <td height="25"> Account Type(Savings/Current) </td>
          <td height="25"><input type="text" name="Acctype" value="<?=$BankQueryRES[0][AccType];?>" id="Acctype" size="40"/></td>
        </tr>
        <tr height="20">
          <td height="25">Bank Name<strong>&nbsp;</strong></td>
          <td height="25"><input type="text" name="BnkName" value="<?=$BankQueryRES[0][BankName];?>" id="BnkName" size="40"/></td>
        </tr>
        <tr height="20">
          <td height="25">Bank    Branch &amp; Address</td>
          <td height="25"><input type="text" name="BnkBranch" value="<?=$BankQueryRES[0][Branch];?>" id="BnkBranch" size="40" /></td>
        </tr>
          <tr height="20">
          <td height="25">IFSC Code</td>
          <td height="25"><input type="text" name="IFCS" id="IFCS" value="<?=$BankQueryRES[0][IFCSCode];?>" size="40" /></td>
        </tr>
</table></td>
        </tr>
        <tr>
          <td colspan="2" align="left">&nbsp;</td>
        </tr>
        <td colspan="2" align="left">  <input type="submit" name="export"  value="ExportPaymentAdvice" /> &nbsp; <input type="submit" name="download"  value="DownloadPaymentAdvice" /></td>
      </tr>
      </table>
    
          </form>
  
</div>
<!-------------------------------DISPLAY ALL EVENT PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>
<script type="text/javascript" src="<?=_HTTP_SITE_ROOT?>/lightbox/prototype.min.js.gz"></script>
  <script type="text/javascript" src="<?=_HTTP_SITE_ROOT?>/lightbox/lightbox.min.js.gz"></script>
	<link type="text/css" rel="stylesheet" href="<?=_HTTP_SITE_ROOT?>/lightbox/lightbox.min.css.gz" media="screen,projection" />