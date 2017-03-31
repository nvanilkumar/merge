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
<div>
	<form name="frmnewslist" action="" method="post">
	<input type="hidden" name="action" value="">
	<input type="hidden" name="id" value="">
		<table width="100%" cellpadding="0" cellspacing="0">
			<tr><td colspan="4">&nbsp;</td></tr>
			<tr>
				<td width="34%" align="center">News Title</td> 
				<td width="15%" align="center">Action</td>
				<td width="30%" align="center">Edit</td>
				<td width="21%" align="center">Delete</td>
			</tr>
			<?php
			while($row = mysql_fetch_array($sql_news))
			{
			?>	
			<tr>
				<td align="left" style="padding-left:30px;"><?=$row['title']?></td>	
				<td align="center">Archive</td>
				<td align="center"><img border="0" onclick="editnews(<?=$row['nid']?>)" src="images/edit.jpg"/></td>
				<td align="center"><img border="0" onclick="deletnews(<?=$row['nid']?>)" src="images/delet.jpg"/></td>
			</tr>
			<?php
			}
			?>
		</table>
	</form>
</div>