<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents -Master Management - City Management</title>
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
				<div id="divMainPage" style="margin-left: 10px; margin-right:5px">
<!-------------------------------CITY LIST PAGE STARTS HERE--------------------------------------------------------------->
<script type="text/javascript" >
  	document.getElementById('ans2').style.display='block';
	
	</script>

<div align="center" style="width:100%">
<form action="" method="post" name="edit_form">
<table width="60%" border="0" cellpadding="3" cellspacing="3">
      <tr>
        <td align="center" colspan="2" valign="middle" class="headtitle"><strong>City Management</strong> </td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><a href="admin.php" class="menuhead" title="Master management Home">Master Management Home</a></td>
      </tr>
	  <form action="" method="post" id="city_list" name="city_list" onSubmit="return validate_city();">
	  <tr>
			
			<td><label>Select Country :</label> 
			<select name="countryid" onchange="this.form.submit()">
				<option value="0" selected="selected">--SELECT--</option>
				<?php 
				for($i = 0; $i < count($CountryList); $i++)
				{
					if($countryid == $CountryList[$i]['id'])
						$countryname = $CountryList[$i]['name'];
				?>
				<option value="<?php echo $CountryList[$i]['id']; ?>" <?php echo ($countryid == $CountryList[$i]['id']) ? 'selected' : ''; ?>><?php echo $CountryList[$i]['name']; ?></option>
				<?php 
				}
				?>
			</select></td>
			<td><label>Select State :</label> 
			<select name="stateid" onchange="this.form.submit()">
				<option value="0" selected="selected">--SELECT--</option>
				<?php 
				for($i = 0; $i < count($StateList); $i++)
				{
					if($stateid == $StateList[$i]['id'])
						$statename = $StateList[$i]['name'];
				?>
				<option value="<?php echo $StateList[$i]['id']; ?>" <?php echo ($stateid == $StateList[$i]['id']) ? 'selected' : ''; ?>><?php echo $StateList[$i]['name']; ?></option>
				<?php 
				}
				?>
			</select>
			</td>
		</tr>
		
		</form>
		<?php 
		 if(count($CityList) > 0){	
		 ?>
      <tr>
        <td colspan="2"><table width="100%" class="sortable"  >
          <thead>
          <tr>
            <td class="tblcont1">City</td>
			<td class="tblcont1">State</td>
			<td class="tblcont1">Country</td>
			<td class="tblcont1">Featured</td>
			<td class="tblcont1">Status</td>
			<td class="tblcont1">order</td>
			
            <td class="tblcont1" ts_nosort="ts_nosort">Action </td>
            <td class="tblcont1" ts_nosort="ts_nosort">Delete</td>
          </tr></thead>
		<?php	
		$flag=0;
       	
		for($i = 0; $i < count($CityList); $i++)
		{
		?>
          <tr>
            <td class="helpBod"><?php echo $CityList[$i]['name']; ?></td>
			<td class="helpBod"><?php echo $statename; ?></td>
			<td class="helpBod"><?php echo $countryname; ?></td>
			<td class="helpBod"><?php echo $CityList[$i]['featured']? 'yes' : 'no'; ?></td>
                        <td class="helpBod"><?php if($CityList[$i]['status']==1){echo 'Active';}elseif($CityList[$i]['status']==0){echo 'Inactive';}elseif($CityList[$i]['status']==2){echo 'New';}?></td>
			<td class="helpBod"><?php echo $CityList[$i]['order']; ?></td>
            <td class="helpBod"><a href="city_edit.php?cityid=<?php echo $CityList[$i]['id']; ?>&stateid=<?php echo $stateid; ?>&countryid=<?php echo $countryid; ?>">Edit</a></td>
            <td class="helpBod"><input type="checkbox" name="city[]" value="<?php echo $CityList[$i]['id']; ?>" /></td>
          </tr>
		<?php 
		} 
		?>
        </table></td>
      </tr>
	  <?php 
	  }
	  ?>
      <tr>
      <td><label>
          <div align="right">
            <input type="button" name="Add" value="Add" onClick="document.location='addcity.php'">
			&nbsp;
			 <?php 
		  if(count($CityList) > 0){	?>
            <input type="submit" name="Submit" value="Delete" onClick="return confirm('Are You Sure You Want To Delete These Cities.\n\nThe Changes Cannot Be Undone');">
		  <?php }?>
            </div>
        </label></td> 
        <td><label>
          <div align="right">
		 
            </div>
        </label></td>
      </tr>
    </table>
</form>
<div align="center" style="width:100%">&nbsp;</div>
</div>
<!-------------------------------CITY LIST PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>