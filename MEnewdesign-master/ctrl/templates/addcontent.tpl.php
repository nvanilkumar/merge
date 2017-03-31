<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<title>MeraEvents -Menu Content Management</title>
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
	
	
<!-------------------------------ADD CONTENT PAGE STARTS HERE--------------------------------------------------------------->
<script type="text/javascript" src="fckeditor/fckconfig.min.js.gz"></script>
<script type="text/javascript" src="fckeditor/fckeditor.min.js.gz"></script>
<script type="text/javascript" language="javascript">
window.onload = function()
{
  var oFCKeditor = new FCKeditor( 'content' ) ;
  oFCKeditor.BasePath = "fckeditor/";
  oFCKeditor.ReplaceTextarea();
}

function valid()
{
	if(document.frmnews.menu_title.value == '')
	{
		alert('Please do not leave the Menu Title field blank...');
		document.frmnews.menu_title.focus();
		return false;
	}
}
</script>
<div>
<form action="" method="post" name="frmnews" onSubmit="return valid();">
	<input type="hidden" name="hidn_content" value="<?=$Id?>" />
	<table width="100%" cellpadding="0">
		<tr>
			<td colspan="2" align="center" class="headtitle"> CONTENT </td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
			<td>
				<table align="center" width="80%" cellpadding="2" cellspacing="2" style="border:thin; border-color:#006699; border-style:solid;">

					<tr align="center" valign="middle">
						<td align="left" valign="middle" class="tblcont">&nbsp;</td>
						<td align="left" valign="middle" class="tblcont">Menu Title</td>
						<td align="center" valign="middle" class="tblcont">:</td>
						<td align="left" valign="middle"><input type="text" name="menu_title" size="70px"  value="<?=$EditMenuContent[0]['Title']?>" maxlength="50" /></td>
						<td align="left" valign="middle" class="tblcont">&nbsp;</td>
					</tr>
					<tr align="center" valign="middle">
						<td align="left" valign="middle" class="tblcont">&nbsp;</td>
						<td align="left" valign="middle" class="tblcont">Menu Description </td>
						<td align="center" valign="middle" class="tblcont">:</td>
						<td align="left" valign="middle"><input type="text" name="menu_desc" size="70px"  value="<?=$EditMenuContent[0]['MenuDesc']?>" maxlength="255" /></td>
						<td align="left" valign="middle" class="tblcont">&nbsp;</td>
					</tr>
					<tr align="center" valign="middle">
						<td align="left" valign="middle" class="tblcont">&nbsp;</td>
						<td align="left" valign="middle" class="tblcont">Parent Menu Item </td>
						<td align="center" valign="middle" class="tblcont">:</td>
						<td align="left" valign="middle"><label>
							<select name="menu_parent">
								<option value="0">Select Parent Menu</option>
								<?php 	
								for($i=0; $i < count($ParentMenu); $i++) {
								?>
								<option value="<?=$ParentMenu[$i]['Id']?>" <?php if($ParentMenu[$i]['Id'] == $EditMenuContent[0]['ParentMenuId']) { ?> selected="selected" <?php } ?>><?=$ParentMenu[$i]['Title']?></option>
								<?php } ?>
							</select>
						</label></td>
						<td align="left" valign="middle" class="tblcont">&nbsp;</td>
					</tr>

					<tr align="center" valign="middle"><td colspan="5" align="left" valign="middle">&nbsp;</td></tr>
					<tr align="center" valign="middle"><td colspan="5" align="left" valign="middle">&nbsp;</td></tr>
					<tr align="center" valign="middle">
						<td align="left" valign="middle" class="tblcont">&nbsp;</td>
						<td align="left" valign="middle" class="tblcont">Long Description</td>
						<td align="center" valign="middle" class="tblcont">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle" class="tblcont">&nbsp;</td>
					</tr>
					<tr align="center" valign="middle">
						<td align="left" valign="middle" class="tblcont">&nbsp;</td>
						<td  colspan="3" align="left" valign="middle"><textarea name="content" id="content" cols="30" rows="8"><?php echo stripslashes($EditMenuContent[0]['Content']); ?></textarea></td>
						<td align="left" valign="middle" class="tblcont">&nbsp;</td>
					</tr>
					<tr><td colspan="5">&nbsp;</td></tr>
					<tr align="center" valign="middle">
						<td colspan="5"><input type="submit" name="save" value="SUBMIT"></td>
					</tr>
				</table> 
			</td>	
		</tr>	
	</table>
</form>
</div>
<!-------------------------------ADD CONTENT PAGE ENDS HERE--------------------------------------------------------------->
	
	
	
	</div>
	</td>
  </tr>
</table>
	</div>	
</body>
</html>