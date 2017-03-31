<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents -Master Management - Event Type Management</title>
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
<!-------------------------------ADD EVENT TYPE PAGE STARTS HERE--------------------------------------------------------------->
<script type="text/javascript" language="javascript">
	function validate()
	{
		if(document.event_type.TargetAud.value == "")
		{
			alert("Please Enter Target Audience");
			document.event_type.TargetAud.focus();
			return false;
		}	
		if(document.event_type.DispOrder.value == "")
		{
			alert("Please Enter DispOrder");
			document.event_type.DispOrder.focus();
			return false;
		}			
		return true;
	}
</script>
<form action="" method="post" name="event_type" onSubmit="return validate()">
	<table width="50%" border="0">
	  <tr>
		<td colspan="2"><strong>Add Target Audience </strong></td>
	  </tr>
	  <tr>
		<td>Target Audience  : </td>
		<td><label>
		  <input type="text" name="TargetAud" maxlength="50" />
		</label></td>
	  </tr>
      <tr>
    <td>Display Order : </td>
    <td><label>
      <input name="DispOrder" type="text" id="DispOrder"  maxlength="50" />
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
		<td><?php echo $msgEventTypeExist; ?></td>
	  </tr>
	</table>
</form>
<!-------------------------------ADD EVENT TYPE PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>