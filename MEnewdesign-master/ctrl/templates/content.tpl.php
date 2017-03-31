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
	
	
	<!-------------------------------CONTENT PAGE STARTS HERE--------------------------------------------------------------->
	
	
	<script>
	function editcontent(id)
	{
		window.location = 'addcontent.php?contentid='+id;
	}
	function deletcontent(idd)
	{
		if(confirm('It will delete all the Child Contents. Do you want to Delete the this Content?'))
		{
			document.frmnewslist.action.value = 'delete';
			document.frmnewslist.Id.value = idd;
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
<div align="center" style="width:100%" class="headtitle">Menu Content Management</div>
<div align="center" style="width:100%">&nbsp;</div>
<div align="left" style="width:100%; padding-left:100px;"><a href="addcontent.php">Add Content</a></div>
<div align="center" style="width:100%">&nbsp;</div>
  <form name="frmnewslist" action="" method="post">
	<input type="hidden" name="action" value="">
	<input type="hidden" name="Id" value="">
		<table width="80%" class="sortable">
			<thead><tr>
				<td width="49%" align="center" class="tblcont1" ts_nosort="ts_nosort">Content Title</td> 
				<td width="21%" align="center" class="tblcont1" ts_nosort="ts_nosort">Parent / Child </td>
				<td width="16%" align="center" class="tblcont1" ts_nosort="ts_nosort">Edit</td>
				<td width="14%" align="center" class="tblcont1" ts_nosort="ts_nosort">Delete</td>
			</tr></thead>
			<?php 
			for($i=0; $i<count($MenuList); $i++)
			{
			?>	
			<tr>
				<td align="left" style="padding-left:30px;" class="helpBod"><?=$MenuList[$i]['Title']?></td>	
				<td align="center" class="helpBod"><?php if($MenuList[$i]['ParentMenuId']!=0) { echo "Child Menu"; } else { echo "-"; }?></td>
				<td align="center" class="helpBod"><img border="0" title="Edit" onClick="editcontent(<?=$MenuList[$i]['Id']?>)" src="images/edit.gif" style="cursor:pointer;" /></td>
				<td align="center" class="helpBod"> 						              
                	<img border="0" title="Delete" onClick="deletcontent(<?=$MenuList[$i]['Id']?>)" src="images/delet.gif" style="cursor:pointer;"/>
				</td>
			</tr>
			<?php
			}
			?>
	  </table>
  </form>
  <div align="center" style="width:100%">&nbsp;</div>
</div>
	
	
	
	<!-------------------------------CONTENT PAGE ENDS HERE--------------------------------------------------------------->
	
	
	
	</div>
	</td>
  </tr>
</table>
	</div>	
</body>
</html>