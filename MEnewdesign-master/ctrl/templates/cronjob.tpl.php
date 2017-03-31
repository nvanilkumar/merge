<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents - Admin Panel - MIS Reports</title>
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
<!-------------------------------MIS Report PAGE STARTS HERE--------------------------------------------------------------->
<div align="center" style="width:100%">
	<table width="50%" cellpadding="0" cellspacing="0">
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td align="center" class="headtitle">Click to Run Cron Jobs</td>
		</tr>
		<tr>
			<td align="center">
				<table width="60%" cellpadding="0" cellspacing="0"  style="border:thin; border-color:#006699; border-style:solid;">
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td align="left" style="padding-left:10px;"><a href="<?=_HTTP_SITE_ROOT;?>/delhihtml.php?runNow=1" target="_blank" class="lnk">Home page(index.php)</a></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					
				</table>
			</td>
		</tr>
	</table>
</div>
<!-------------------------------MIS Report PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>