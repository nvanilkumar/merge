<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents -Master Management - Event Search Management</title>
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
<!-------------------------------DISPLAY ALL EVENT PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans2').style.display='block';
	function del_eve(delid)
	{
		 var confrm=confirm('Are You Sure You Want To Delete These Event Names.\n\nThe Changes Cannot Be Undone');
		 if(confrm)
		 {
		 	document.edit_form.act_id.value = delid;
			document.edit_form.action.value = 'Delete';
		 	document.edit_form.submit();
			return false;
		 }
		 else
		 {
		 	return false;
		 }
		 return true;
	}
</script>
<div align="center" style="width:100%">
<form action="" method="post" name="edit_form">
	<input type="hidden" name="action" value="" />
	<input type="hidden" name="act_id" value="" />
	<table width="50%">
      <tr>
        <td colspan="2" align="center" class="headtitle"><strong>Event Name Management</strong> </td>
      </tr>
      <tr>
        <td colspan="2" align="left"><a href="admin.php" class="menuhead" title="Master management Home">Master management Home</a></td>
      </tr>
      <tr>
        <td colspan="2"><table width="100%" class="sortable">
          <thead>
          <tr>
            <td class="tblcont1"><strong>Event Name </strong></td>
            <td class="tblcont1" ts_nosort="ts_nosort"><strong>Edit</strong> </td>
            <td class="tblcont1" ts_nosort="ts_nosort"><strong>Delete</strong></td>
          </tr>
          </thead>
		  <?php for($i = 0; $i < count($EventList); $i++) { ?>
          <tr>
            <td align="left" valign="middle" class="helpBod"><?php echo $EventList[$i]['EventName']; ?></td>
            <td  class="helpBod"><a href="eventname_edit.php?id=<?php echo $EventList[$i]['Id']; ?>">Edit</a></td>
            <td  class="helpBod"><label>
            </label>
            <img src="images/delete.gif" title="Delete" style="cursor:pointer" onclick="return del_eve('<?php echo $EventList[$i]['Id']; ?>');" />
            </td>
          </tr>
		  <?php 
		  }
		  ?>
        </table></td>
      </tr>
      <tr>
       <td><label>
          <div align="right">
            <input type="button" name="Add" value="Add" onClick="document.location='addeventname.php'" />
            </div>
        </label></td>
        <td><label>
          <div align="right">&nbsp;
            </div>
        </label></td>
      </tr>
    </table>
</form>
<div align="center" style="width:100%"></div>
</div>
<!-------------------------------DISPLAY ALL EVENT PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>