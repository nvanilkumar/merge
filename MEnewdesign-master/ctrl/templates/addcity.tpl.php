<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents -Master Management - City Management</title>
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
<script language="javascript">
  	document.getElementById('ans2').style.display='block';
</script>

	<table width="50%" border="0" cellpadding="3" cellspacing="3">
		<tr>
			<td colspan="2"><strong>Add City </strong></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<form action="" method="post" id="city_list" name="city_list" onSubmit="return validate_city();">
		<tr>
			
			<td><label>Select Country :</label> </td>
		    <td><label>	
			<select name="countryid" onchange="this.form.submit()">
				<option value="0" selected="selected">--SELECT--</option>
				<?php 
				for($i = 0; $i < count($CountryList); $i++)
				{
					
				?>
				<option value="<?php echo $CountryList[$i]['id']; ?>" <?php echo ($countryid == $CountryList[$i]['id']) ? 'selected' : ''; ?>><?php echo $CountryList[$i]['name']; ?></option>
				<?php 
				}
				?>
			</select></label></td>
			</tr>
		<tr>
			<td>Select State : </td>
			<td><label>
			<select name="stateid">
				<option value="0" selected="selected">--SELECT--</option>
				<?php 
				for($i = 0; $i < count($StateList); $i++)
				{
				?>
				<option value="<?php echo $StateList[$i]['id']; ?>" <?php echo ($stateid == $StateList[$i]['id']) ? 'selected' : ''; ?>><?php echo $StateList[$i]['name']; ?></option>
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
			<td>Featured </td>
			<td><label>
			<input type="checkbox" name="featured" value="1" />
			</label></td>
		</tr>
		<tr>
			<td>Status </td>
			<td><label>
		
			 <input type="radio" name="status" value="1" />Active&nbsp;&nbsp;&nbsp;
			 <input type="radio" name="status" value="0" />Inactive
			</label></td>
		</tr>
		<tr>
			<td>SpecialCityState </td>
			<td><label>
			<input type="checkbox" name="specialcity" value="1" />
			</label></td>
		</tr>
		<tr>
			<td>Order </td>
			<td><label>
			<input type="text" name="order" maxlength="5" />
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
		</form>
	</table>

<div><?php echo $msgStateCityExist; ?></div>
<!-------------------------------ADD CITY PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>