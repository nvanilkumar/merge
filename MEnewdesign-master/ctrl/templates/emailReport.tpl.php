<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents - Admin Panel - EMail / SMS Management - EMail Report</title>
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.js"></script>	
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.js"></script>	
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
<!-------------------------------EMail Report PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans10').style.display='block';
</script>
<link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.css" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.js"></script>
<script language="javascript">
	function SEdt_validate()
	{
		var strtdt = document.frmEMailReport.txtSDt.value;
		var enddt = document.frmEMailReport.txtEDt.value;
		
		if(strtdt == '')
		{
			alert('Please select Start Date');
			document.frmEMailReport.txtSDt.focus();
			return false;
		}
		else if(enddt == '')
		{
			alert('Please select End Date');
			document.frmEMailReport.txtEDt.focus();
			return false;
		}
		else if(strtdt != '' && enddt != '')
		{   
			var startdate=strtdt.split('/');
			var startdatecon=startdate[2] + '/' + startdate[1]+ '/' + startdate[0];
			
			var enddate=enddt.split('/');
			var enddatecon=enddate[2] + '/' + enddate[1]+ '/' + enddate[0];
			
			if(Date.parse(enddatecon) < Date.parse(startdatecon))
			{
				alert('End Date must be greater then Start Date.');
				document.frmEMailReport.txtEDt.focus();
				return false;
			}
		}
		return true;
	}
</script>
<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">EMail Report</div>
<div align="center" style="width:100%">&nbsp;</div>
<form action="" method="post" name="frmEMailReport">
<table width="50%" align="center" class="tblcont">
	<tr>
	  <td width="35%" align="left" valign="middle">Start Date:&nbsp;<input type="text" name="txtSDt" value="<?php echo $SDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	  <td width="35%" align="left" valign="middle">End Date:&nbsp;<input type="text" name="txtEDt" value="<?php echo $EDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	  <td width="30%" align="left" valign="middle"><input type="submit" name="submit" value="Show EMail Report" onclick="return SEdt_validate();" /></td>
	<tr>
</table>
</form>
<?php 
if(count($EMailReport) > 0) 
{ 
?>
<table width="90%" align="center" class="sortable">
	<tr>
		<td width="25%" align="left" valign="middle" class="tblcont1">Name</td>
		<td width="25%" align="left" valign="middle" class="tblcont1">EMail Id</td>
		<td width="25%" align="left" valign="middle" class="tblcont1">Message Type</td>
		<td width="25%" align="left" valign="middle" class="tblcont1">Date</td>
    </tr>
	<?php 
		for($i = 0; $i < count($EMailReport); $i++)
		{
	?>
	<tr>
		<td align="left" valign="middle" class="helpBod" height="25"><?=$EMailReport[$i]['name']?></td> 	
		<td align="left" valign="middle" class="helpBod"><?=$EMailReport[$i]['email']?></td>
		<td align="left" valign="middle" class="helpBod"><?=$EMailReport[$i]['type']?></td>
		<td align="left" valign="middle" class="helpBod"><?=$EMailReport[$i]['cts']?></td>
	</tr>
	<?php 
		} 
	?>
</table>
<?php 
} 
else if(count(isset($EMailReport)) == 0)
{
?>
<table width="50%" align="center">
	<tr>
	  <td width="100%" height="30px" align="center" valign="middle"><font color="#FF0000">No Record Found.</font></td>
	</tr>
</table>
<?php
}
?>
<div align="center" style="width:100%">&nbsp;</div>
<!-------------------------------EMail Report PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>