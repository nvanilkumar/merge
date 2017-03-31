<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents -Master Management - News Management</title>
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
<!-------------------------------News PAGE STARTS HERE--------------------------------------------------------------->
<script>
	function editnews(id)
	{
		window.location = 'addnews.php?newsid='+id;
	}
	function deletnews(idd)
	{
		if(confirm('Do you want to Delete the this News??'))
		{
			document.frmnewslist.action.value = 'delete';
			document.frmnewslist.id.value = idd;
			document.frmnewslist.submit();
		}
		else
		{
			return false;
		}
	}
</script>
<div align="center" style="width:100%">
<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">News Management</div>
<div align="center" style="width:100%">&nbsp;</div>
<div align="left" style="width:100%; padding-left:100px;"><a href="addnews.php">Add News</a></div>
<div align="center" style="width:100%">&nbsp;</div>
  <form name="frmnewslist" action="" method="post">
	<input type="hidden" name="action" value="">
	<input type="hidden" name="id" value="">
		<table width="80%" class="sortable">
			<thead><tr>
				<td width="49%" align="center" class="tblcont1">News Title</td> 
			    <td width="21%" align="center" class="tblcont1" ts_nosort="ts_nosort">Date</td>
		      <td width="21%" align="center" class="tblcont1" ts_nosort="ts_nosort">Action</td>
			  <td width="16%" align="center" class="tblcont1" ts_nosort="ts_nosort">Edit</td>
			  <td width="14%" align="center" class="tblcont1" ts_nosort="ts_nosort">Delete</td>
			</tr></thead>
			<?php /**************************commented on 17082009 need to remove afterwords**************************?><?php
			while($row = mysql_fetch_array($sql_news))
			{
			?>	<?php */?>
			<tr>
				<td align="left" style="padding-left:30px;" class="helpBod">test news<?php /**************************commented on 17082009 need to remove afterwords**************************?><?=$row['title']?><?php */?></td>	
				<td align="center" class="helpBod"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $created = date("d/m/Y",$row['changed']); ?><?php */?></td>
				<td align="center" class="helpBod">Archive</td>
				<td align="center" class="helpBod"><img border="0" title="Edit" onclick="editnews(1<?php /**************************commented on 17082009 need to remove afterwords**************************?><?=$row['nid']?><?php */?>)" src="images/edit.gif"/></td>
				<td align="center" class="helpBod">
				 <?php if($_SESSION['backend_id'] != '1') { ?>  						              
                <img border="0" title="Delete" onclick="deletnews(1<?php /**************************commented on 17082009 need to remove afterwords**************************?><?=$row['nid']?><?php */?>)" src="images/delet.gif"/>
                <?php } ?>
                </td>
			</tr>
			<?php /**************************commented on 17082009 need to remove afterwords**************************?><?php
			}
			?><?php */?>
	  </table>
  </form>
  <div align="center" style="width:100%"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $projectpage->getPageLinks(); ?><?php */?></div>
</div>
<!-------------------------------NEWS PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>