<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents - Admin Panel</title>
	<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
	<script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>	
	<script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script>	
</head>	
<body style="background-image: url(images/background.gif); background-repeat:repeat-x; margin-top: 0px; margin-left: 0px; margin-right:0px; padding:0px">
	<?php include('templates/header.tpl.php'); ?>				
	</div>
	<table style="width:100%; height:495px;" cellpadding="0" cellspacing="0">
		<tr>
			<td style="width:150px; vertical-align:top; background-image:url(images/menugradient.jpg); background-repeat:repeat-x">
				<?php //include('templates/left.tpl.php'); ?>
			</td>
			<td style="vertical-align:top">
				<div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
<!-------------------------------ADMIN LOGIN PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript" type="text/javascript" src="includes/javascripts/jscript.min.js.gz"></script>
<script language="javascript" type="text/javascript">
function formLoad()
{
	document.frmlogin.user_name.focus();
}
function checkALoginForm()
{
	var uname = document.frmlogin.user_name.value;
	var password = document.frmlogin.password.value;
	
	if(uname == '')
	{
		alert("Please enter User Name");
		document.frmlogin.user_name.focus();
		return false;
	}
	else if(password == '')
	{
		alert("Please enter Password");
		document.frmlogin.password.focus();
		return false;
	}
	return true;
}
</script>
<div id="divControl" style="margin-top: 10px; margin-bottom: 20px;">
	<table style="width: 100%; table-layout: fixed;" cellpadding="0" cellspacing="0">
		<tr>
			<td id="tdIcon" style="width:45px;"><img src="images/key.gif" id="imgIcon"></td>
			<td>				
				<div id="divTitle" class="pagetitle">Login</div>
			</td>
		</tr>
	</table>
</div>
<center>
<form name="frmlogin" method="post" action="">
<input type="hidden" name="process" value="SignIn" />
<table bordercolor="#0512AB" border="1" align="center" style="margin-top:50px;">
<tr>
<td>
<table align="center" style="margin:10px;" cellspacing="7">
<tr>
	<td class="label">
		User Name
	</td>

	<td>
		<input type="text" name="user_name" class="inputBoxWidth" maxlength="50" />
	</td>
</tr>

<tr>
	<td class="label">
		Password
	</td>

	<td>
		<input type="password" name="password" class="inputBoxWidth" maxlength="50" />
	</td>
</tr>

<tr>
	  <td colspan="2" align="center">
			<input type="image" name="Submit" src="images/CtrlSignBtn.gif" onclick="return checkALoginForm();" /> 
	  </td>
</tr>
<tr>
	  <td  align="center" colspan="2"><div class="message"><?php echo $msgLogin; ?></div>
			<!--a href ="forgot.php">Forgotten Password?</a-->
   	  </td>   
</tr>
</table>
</td>
</tr>
</table>
<table align="center">
<tr>
	<td align="center">
		<div id="divFailMsg">
			<div id="divFailures" class="message"></div>
		</div>
	</td>
</tr>
<tr>
	<td align="center">
		<div id="errorLogin" class="message"></div>
	</td>
</tr>
</table>
</form>
</center>
<!-------------------------------ADMIN LOGIN PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>