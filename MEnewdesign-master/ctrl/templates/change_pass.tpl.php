<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents -Master Management - Industry Management</title>
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
				<div id="divMainPage" style="margin-left: 10px; margin-right:5px">
<!-------------------------------CHANGE PASSWROD PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
	function validate()
	{
		if(document.change_p.old_pass.value == '')
		{
			alert("Plese Enter Your Old Password");
			return false;
		}
		if(document.change_p.new_pass.value == '')
		{
			alert("Plese Enter Your New Password");
			return false;
		}
		if(document.change_p.cnew_pass.value == '')
		{
			alert("Plese Enter Your Confirm new Password");
			return false;
		}
		if(document.change_p.new_pass.value != document.change_p.cnew_pass.value)
		{
			alert("Your New Password And Confirm New Password Does not Match");
			return false;
		}
		return true;
	}
</script>
<div align="center" style="width:100%">
<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">Change Password</div>
<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%; color:#FF0000; font-weight:bold;"><?php echo $msgChangePWD;  ?></div>
<div align="center" style="width:100%">&nbsp;</div>
	<form name="change_p" action="" onSubmit="return validate();" method="post">
		<table align="center" width="50%" style="border:thin; border-color:#006699; border-style:solid;">
	<tr>
	  <td>&nbsp;</td>
	  <td align="left" valign="middle">&nbsp;</td>
	  <td align="center" valign="middle">&nbsp;</td>
	  <td align="left" valign="middle"></td>
    </tr>
	<tr>
	  <td width="15%">&nbsp;</td>
	  <td width="29%" align="left" valign="middle" class="tblcont">Old Password</td>
    	<td width="10%" align="center" valign="middle" class="tblcont">:</td>
        <td width="46%" align="left" valign="middle"><input type="password" name="old_pass"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="left" valign="middle" class="tblcont">New Password</td>
    	<td align="center" valign="middle" class="tblcont">:</td>
        <td align="left" valign="middle"><input type="password" name="new_pass"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="left" valign="middle" class="tblcont">Confirm New Password</td>
    	<td align="center" valign="middle" class="tblcont">:</td>
        <td align="left" valign="middle"><input type="password" name="cnew_pass"></td>
    </tr>
     <tr>
       <td colspan="4" align="center"><input type="submit" name="Change" value="Change" /></td>
    </tr>
</table>
	</form>
</div>
<!-------------------------------CHANGE PASSWORD PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>