<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents - Admin Panel - Registration Report</title>
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>	
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script>	
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
<!-------------------------------Date Report of Registration PAGE STARTS HERE--------------------------------------------------------------->
<link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.min.css.gz" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.min.js.gz"></script>
<script language="javascript">
	function SEdt_validate()
	{
		var strtdt = document.frmRegDate.txtSDt.value;
		var enddt = document.frmRegDate.txtEDt.value;
		
		if(strtdt == '')
		{
			alert('Please select Start Date');
			document.frmRegDate.txtSDt.focus();
			return false;
		}
		else if(enddt == '')
		{
			alert('Please select End Date');
			document.frmRegDate.txtEDt.focus();
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
				alert('End Date must be greater then Start Date.  Please try again.');
				document.frmRegDate.txtEDt.focus();
				return false;
			}
		}
	}
</script>
<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">Date Report of Registration</div>
<div align="center" style="width:100%">&nbsp;</div>
	<form action="" method="post" name="frmRegDate">
	<table width="50%" align="center" class="tblcont">
		<tr>
		  <td width="35%" align="left" valign="middle">Start Date:&nbsp;<input type="text" name="txtSDt" value="<?php echo $SDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
		  <td width="35%" align="left" valign="middle">End Date:&nbsp;<input type="text" name="txtEDt" value="<?php echo $EDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
		  <td width="30%" align="left" valign="middle"><input type="submit" name="submit" value="Show Users" onclick="return SEdt_validate();" /></td>
		<tr>
	</table>
	</form>
	<?php 
	if(isset($UsersDateRegn)) 
	{ 
	?>
	<table width="90%" align="center">
		<tr>
			<td width="100%" height="10">&nbsp;</td>
		</tr>
		<tr>
			<td width="100%" height="25" align="center">Total Registrations Found: <?php echo count($UsersDateRegn); ?></td>
		</tr>
		<tr>
			<td width="100%" height="10">&nbsp;</td>
		</tr>
	</table>
	<?php 
	}
	if(count($UsersDateRegn) > 0) 
	{ 
	?>
	<table width="90%" align="center" class="sortable-onload-3r no-arrow colstyle-alt rowstyle-alt paginate-10 max-pages-3 paginationcallback-callbackTest-calculateTotalRating sortcompletecallback-callbackTest-calculateTotalRating">
		<tr>
		  <td width="15%" align="left" valign="middle" class="tblcont1">Sr. No.</td>
		  <td width="60%" align="left" valign="middle" class="tblcont1">Name</td>
		  <td width="25%" align="left" valign="middle" class="tblcont1">Registration Date</td>
		</tr>
		<?php 
			$cnt = 1;
			for($i = 0; $i < count($UsersDateRegn); $i++)
			{
		?>
		<tr>
			<td align="center" valign="middle" class="helpBod"><?php echo $cnt++; ?></td>
			<td align="left" valign="middle" class="helpBod" height="25"><?php echo $UsersDateRegn[$i]['FirstName']." ".$UsersDateRegn[$i]['MiddleName']." ".$UsersDateRegn[$i]['LastName']; ?></td>
			<td align="left" valign="middle" class="helpBod"><?php echo $UsersDateRegn[$i]['RegnDt']; ?></td>
		</tr>
		<?php 
			} //ends for loop
		?>
	</table>
	<?php 
	} //ends if loop
	?>
	<div align="center" style="width:100%">&nbsp;</div>
<!-------------------------------Date Report of Registration PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>