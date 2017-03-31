<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>Master Management - City Management</title>
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.min.css.gz" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.min.css.gz" rel="stylesheet" type="text/css">
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
<!-------------------------------ADD CITY PAGE STARTS HERE--------------------------------------------------------------->
<script type="text/javascript" language="javascript">
	function validate_city()
	{
		if(document.add_city.state.value == "0")
		{
			alert("Please Select A State For The City");
			document.add_city.state.focus();
			return false;
		}
		if(document.add_city.city.value == '')
		{
			alert("Please Enter City");
			document.add_city.city.focus();
			return false;
		}
		return true;
	}
</script>
    
<form action="" method="post" name="add_city" onSubmit="return validate_city();">
	<table width="50%" border="0" cellpadding="3" cellspacing="3">
		<tr>
			<td colspan="2"><strong>Add City </strong></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>Select State : </td>
			<td><label>
			<select name="state">
				<option value="0" selected="selected">--SELECT--</option>
				<?php 
				for($i = 0; $i < count($StateList); $i++)
				{
				?>
				<option value="<?php echo $StateList[$i]['Id']; ?>"><?php echo $StateList[$i]['State']; ?></option>
				<?php 
				}
				?>
			</select>
			</label></td>
		</tr>
		<tr>
			<td>City Name : </td>
			<td><label>
			<input type="text" name="city" maxlength="80" />
			</label></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><label>
			<input type="submit" name="Submit" value="Add" />
			</label></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	</table>
</form>
<div><?php echo $msgStateCityExist; ?></div>
<!-------------------------------ADD CITY PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>