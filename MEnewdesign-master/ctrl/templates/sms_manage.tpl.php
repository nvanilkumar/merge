<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents - Admin Panel - Manage SMS Settings</title>
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
<!-------------------------------SMS MANAGE PAGE STARTS HERE--------------------------------------------------------------->
<style type="text/css">
<!--
.style1 {
	font-size: 16px;
	font-weight: bold;
}
-->
</style>
<table width="70%" border="0" cellpadding="3" cellspacing="0" align="center">
  <tr>
    <td colspan="4"><div align="center" class="style1">Choose Triggers </div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="tblcont1" ts_nosort="ts_nosort"><div align="center">Trigger </div></td>
    <td class="tblcont1" ts_nosort="ts_nosort"><div align="center">SMS Message </div></td>
    <td class="tblcont1" ts_nosort="ts_nosort"><div align="center">Current Status </div></td>
    <td class="tblcont1" ts_nosort="ts_nosort"><div align="center">Options</div></td>
  </tr>
  <?php /*?><?php 
  while($triggers = mysql_fetch_array($sql_trig_res)){
  ?><?php */?>
  <tr>
    <td class="helpBod"><?php /*?><?php echo $triggers['title'];?><?php */?></td>
    <td class="helpBod"><?php /*?><?php echo $triggers['message'];?><?php */?></td>
    <td class="helpBod"><?php /*?><?php if($triggers['status'] == 1){echo "Enabled";}else{echo "Disabled";}?><?php */?></td>
    <td class="helpBod"><?php /*?><?php echo "<a href='edit_sms.php?id=".$triggers['tid']."' >Edit</a>";?><?php */?></td>
  </tr>
  <?php /*?><?php }?><?php */?>
</table>
<!-------------------------------SMS MANAGE PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>