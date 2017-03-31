<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents -Master Management - User Management</title>
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
<!-------------------------------Manage User PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans3').style.display='block';
</script>
<link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.min.css.gz" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.min.js.gz"></script>
<script language="javascript" type="text/javascript">
	function isValidPattern_Email(str)
	{
		var regEx = /^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/i;
		return regEx.test(str);
	}
	function valid()
	{
		var name = document.frmusersearch.user.value;
		var email = document.frmusersearch.email.value;
		var company = document.frmusersearch.company.value;
		var strtdt = document.frmusersearch.strt_date.value;
		var enddt = document.frmusersearch.end_date.value;
		
		//if(name == '' && email == '' && company == '' && strtdt == '' && enddt == '')
		//{
		//	alert('Do not Leave all the fields blank..Please select atleast one field to Search!!');
		//	return false;
		//}
		
		if(email != '' && !isValidPattern_Email(email))
		{
			alert('Please Enter valid pattern of Email!!');
			document.frmusersearch.email.focus();
			return false;
		}
		
		if(strtdt != '' && enddt == '')
		{
			alert('You selected Start date then it is necessory to choose End Date also!!');
			document.frmusersearch.end_date.focus();
			return false;
		}
		
		if(strtdt == '' && enddt != '')
		{
			alert('You selected End date then it is necessory to choose Start Date also!!');
			document.frmusersearch.strt_date.focus();
			return false;
		}
		
		if(strtdt != '' && enddt != '')
		{   
			var startdate=strtdt.split('/');
			var startdatecon=startdate[0] + '/' + startdate[1]+ '/' + startdate[2];
			
			var enddate=enddt.split('/');
			var enddatecon=enddate[0] + '/' + enddate[1]+ '/' + enddate[2];
			
			if(Date.parse(enddatecon) <= Date.parse(startdatecon))
			{
				alert('End Date must be greater then Start Date.  Please try again.');
				return false;
			}
		}
	}
</script>
<div>
	<form name="frmusersearch" action="listusers.php" method="post" onSubmit="return valid();">
		<table width="100%" cellpadding="0" cellspacing="5" style="padding-left:20px;">
			<tr><td colspan="4">&nbsp;</td></tr>
			<tr><td colspan="4" class="headtitle" align="center">Search Users</td></tr>
			<tr><td colspan="4"><div align="center" style="width:55%;"><a href="user.php" class="menuhead" title="User Management Home">
            User Management Home</a></div></td></tr>
			<tr>
				<td align="center" colspan="4">
					<table width="60%" cellpadding="2" cellspacing="5" style="border:thin; border-color:#006699; border-style:solid;">
						<tr>
						  <td width="19%" class="tblcont">&nbsp;</td>
							<td width="28%" align="left" valign="middle" class="tblcont">Name</td>
							<td width="6%" align="center" valign="middle" class="tblcont">:</td>
						  <td colspan="3" align="left" valign="middle"><input type="text" name="user" /></td>
						</tr>
						<tr>
						  <td class="tblcont">&nbsp;</td>
							<td align="left" valign="middle" class="tblcont">Email</td>
							<td align="center" valign="middle" class="tblcont">:</td>
						  <td colspan="3" align="left" valign="middle"><input type="text" name="email" /></td>
						</tr>
						<tr>
						  <td class="tblcont">&nbsp;</td>
							<td align="left" valign="middle" class="tblcont">Company</td>
							<td align="center" valign="middle" class="tblcont">:</td>
						  <td colspan="3" align="left" valign="middle"><input type="text" name="company" /></td>
						</tr>
						<tr>
						  <td class="tblcont">&nbsp;</td>
							<td align="left" valign="middle" class="tblcont">Registered Between</td>
							<td align="center" valign="middle" class="tblcont">:</td>
						  <td width="10%" align="left" valign="middle"><input type="text" name="strt_date" size="8" onfocus="showCalendarControl(this);" /></td>
						  <td width="4%" align="left" valign="middle">To</td>
						  <td width="33%" align="left" valign="middle"><input type="text" name="end_date"  size="8" onfocus="showCalendarControl(this);" /></td>
					  </tr>
						<tr>
						  <td class="tblcont">&nbsp;</td>
							<td align="left" valign="middle" class="tblcont">Status</td>
							<td align="center" valign="middle" class="tblcont">:</td>
							<td colspan="3" align="left" valign="middle">
								<select name="sts">
									<option value="-1">SELECT</option>
									<option value="1">ACTIVE</option>
									<option value="0">INACTIVE</option>
						  </select>							</td>
						</tr>
						<tr>
							<td colspan="6" align="center"><input type="submit" name="search" value="SEARCH"></td>
						</tr>
				  </table>
			  </td>
			</tr>
		</table>
	</form>
</div>
<!-------------------------------Manage User PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>