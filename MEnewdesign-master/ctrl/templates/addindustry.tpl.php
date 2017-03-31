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
<!-------------------------------ADD NEW INDUSTRY NAME PAGE STARTS HERE--------------------------------------------------------------->
<script type="text/javascript" language="javascript">
function validate()
{
	if(document.frmAddIndustry.txtIndustry.value == "")
	{
		alert("Please Enter Industry Name");
		document.frmAddIndustry.txtIndustry.focus();
		return false;
	}
	return true;
}
</script>
<form action="" method="post" name="frmAddIndustry" onSubmit="return validate()">
	<table width="50%" border="0">
	  <tr>
		<td colspan="2"><strong>Add Industry </strong></td>
	  </tr>
	  <tr>
		<td>Category</td>
		<td><select tabindex="4" name="CategoryId" id="CategoryId" >
				<?php 
				$TotalCategoryRES=count($CategoriesRES);
				for($i=0; $i<$TotalCategoryRES; $i++)
				{
				?>
					<option value="<?php echo $CategoriesRES[$i]['Id']; ?>" ><?php echo $CategoriesRES[$i]['Category']; ?></option>
				<?php 
				} 
				?>
				</select></td>
	  </tr>
	  <tr>
		<td>Industry : </td>
		<td><label>
		  <input type="text" name="txtIndustry" maxlength="50" />
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
		<td><?php echo $msgIndustryExist; ?></td>
	  </tr>
	</table>
</form>
<!-------------------------------ADD NEW INDUSTRY PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>