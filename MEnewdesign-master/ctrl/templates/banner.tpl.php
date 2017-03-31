<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents -MeraEvents - Admin Panel - Ad Banner Management</title>
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
<!-------------------------------Ad Banner PAGE STARTS HERE--------------------------------------------------------------->
<div align="center" style="width:100%">
<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">Advertisements</div>
<div align="center" style="width:100%">&nbsp;</div>
<table width="80%" class="sotrtable">
  <thead>	
  <tr>
    <td class="tblcont1" align="left" style="padding-left:20px;">Title</td>
    <td class="tblcont1" align="center">URL</td>
    <td class="tblcont1" align="center">Status</td>
    <td class="tblcont1" align="center">Created</td>
    <td class="tblcont1" align="center">Action</td>
  </tr>
  </thead>
 
  <?php /**************************commented on 17082009 need to remove afterwords**************************?><?php while($sql_ad_row = mysql_fetch_array($sql_ad_res)){
  	if($sql_ad_row['status'] == 1){
		$status = "Active";
	}else{
		$status = "Inactive";
	}
  ?><?php */?>
  <tr>
    <td align="left" style="padding-left:20px;" class="helpBod">test banner<?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $sql_ad_row['title'];?><?php */?></td>
    <td align="center" class="helpBod">http://www.test.com<?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $sql_ad_row['url'];?><?php */?></td>
    <td align="center" class="helpBod">Active<?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $status;?><?php */?></td>
    <td align="center" class="helpBod"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo date("d F Y",$sql_ad_row['created'])?><?php */?></td>
    <td align="center" class="helpBod">
	<a href="ad_stats.php?aid=1<?php /**************************commented on 17082009 need to remove afterwords**************************?><?=$sql_ad_row['aid']?><?php */?>">View Statistics</a></td>
  </tr>
  <?php /**************************commented on 17082009 need to remove afterwords**************************?><?php }?><?php */?>
</table>
<!-------------------------------Ad BAnner PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>