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
				<div id="divMainPage" style="margin-left: 10px; margin-right:5px">
<!-------------------------------EDIT CITY PAGE STARTS HERE--------------------------------------------------------------->
<script type="text/javascript" language="javascript">
	function validate_city(){
	
		if(document.edit_city.state.value == "0"){
			alert("Please Select State For The City Name");
			document.edit_city.state.focus();
			return false;
		}
		
		if(document.edit_city.city.value == ''){
			alert("Please Enter City Name");
			document.edit_city.city.focus();
			return false;
		}
		
		return true;
	}
</script>
<script language="javascript">
  	document.getElementById('ans2').style.display='block';
</script>
<form action="" method="post" name="edit_city" onSubmit="return validate_city();">
<table width="50%" border="0" cellpadding="3" cellspacing="3">
  <tr>
    <td colspan="2"><strong>Edit City </strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
			
			<td><label>Country :</label> </td>
		    <td><label>	<input type="text" name="country" value="<?php echo $countryname; ?>" disabled maxlength="80" />
			</label></td>
			</tr>
		<tr>
			<td>Select State : </td>
			<td><label>
			<input type="text" name="state" value="<?php echo $statename; ?>" disabled maxlength="80" />
			</label></td>
		</tr>
		<tr>
			<td>City Name : </td>
			<td><label>
			<input type="text" name="city" value="<?php echo $CityList[0]['name']; ?>" maxlength="80" />
			</label></td>
		</tr>
		<tr>
			<td>Featured </td>
			<td><label>
			<input type="checkbox" name="featured" <?php echo $CityList[0]['featured'] == 1 ? "checked" : ""; ?>   value="1" />
			</label></td>
		</tr>
		<tr>
			<td>Status </td>
			<td> 
			 <input type="radio" name="status" <?php echo $CityList[0]['status'] == 1 ? "checked" : ""; ?>  value="1" /><label>Active&nbsp;&nbsp;&nbsp;</label>
			 <input type="radio" name="status" <?php echo $CityList[0]['status'] == 0 ? "checked" : ""; ?>  value="0" /><label>Inactive
			</label></td>
		</tr>
		<tr>
			<td>SpecialCityState </td>
			<td><label>
			<input type="checkbox" name="specialcity" <?php echo $CityList[0]['splcitystateid'] > 0  ? "checked" : ""; ?>  value="1" />
			</label></td>
		</tr>
		<tr>
			<td>Order </td>
			<td><label>
			<input type="text" name="order" value="<?php echo $CityList[0]['order']; ?>" maxlength="5" />
			</label></td>
		</tr>
  <tr>
    <td>&nbsp;</td>
    <td><label>
      <input type="submit" name="Submit" value="Update" />
    </label></td>
  </tr>
</table>
</form>
<!-------------------------------EDIT CITY PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>