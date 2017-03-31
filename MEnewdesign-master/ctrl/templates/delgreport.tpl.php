<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents - Admin Panel - MIS Reports - Delegates Report</title>
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
<!-------------------------------Delegates Report PAGE STARTS HERE--------------------------------------------------------------->
<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">MIS Reports - Delegate Report</div>
<div align="center" style="width:100%">&nbsp;</div>
	<form name="frmevent" action="delegate_event.php" method="post">
		<table width="60%" cellpadding="5" cellspacing="5"  style="border:thin; border-color:#006699; border-style:solid;" align="center">
		  <tr>
				<td colspan="4" align="center" valign="middle">&nbsp;</td>
		  </tr>
			<tr>
			  <td width="20%" align="right">&nbsp;</td>
				<td width="27%" align="left" valign="middle" class="tblcont">Choose Event</td>
				<td width="7%" align="center" valign="middle"> : </td>
				<td width="46%" align="left" valign="middle">
					<select name="event">
						<option value="0">ALL EVENTS</option>
						<?php
						for($i = 0; $i < count($EventList); $i++)
						{
						?>
						<option value="<?php echo $EventList[$i][Id]; ?>"><?php echo $EventList[$i]['EventName']; ?></option>
						<?php		
						}
						?>
					</select>				
				</td>
			</tr>
			<tr>
				<td colspan="4" align="center" valign="middle"><input type="submit" name="show" value="Generate Report"></td>
			</tr>
		</table>
  </form>
</div>
<!-------------------------------Delegate Report PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>