<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents -Master Management - Designation Management</title>
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
<!-------------------------------DESIGNATION LIST PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans2').style.display='block';
</script>
<div align="center" style="width:100%">
<form action="" method="post" name="edit_form">
<table width="50%" border="0">
      <tr>
        <td colspan="2" align="center" class="headtitle"><strong>Designation Management</strong> </td>
      </tr>
      <tr>
        <td colspan="2" align="left"><a href="admin.php" class="menuhead" title="Master management Home">Master Management Home</a></td>
      </tr>
      <tr>
        <td colspan="2"><table width="100%" class="sortable">
         <thead>
          <tr>
            <td class="tblcont1"><strong>Designation</strong></td>
            <td class="tblcont1" ts_nosort="ts_nosort"><strong>Edit</strong> </td>
            <td class="tblcont1" ts_nosort="ts_nosort"><strong>Delete</strong></td>
          </tr>
          </thead>
		<?php for($i = 0; $i < count($DesignationList); $i++) { ?>
          <tr>
            <td class="helpBod"><?php echo $DesignationList[$i]['Designation']; ?></td>
            <td class="helpBod"><a href="designation_edit.php?id=<?php echo $DesignationList[$i]['Id']; ?>">Edit</a></td>
            <td class="helpBod"><label>
              <input type="checkbox" name="desig[]" value="<?php echo $DesignationList[$i]['Id']; ?>" />
            </label></td>
          </tr>
		  <?php }	?>
        </table></td>
      </tr>
      <tr>
      <td><label>
          <div align="right">
            <input type="button" name="Add" value="Add" onClick="document.location='adddesignation.php'">
            </div>
        </label></td>
        <td><label>
          <div align="right">
            <input type="submit" name="Submit" value="Delete" onClick="return confirm('Are You Sure You Want To Delete These Designation.\n\nThe Changes Cannot Be Undone');">
            </div>
        </label></td>
      </tr>
    </table>
</form>
<div align="center" style="width:100%">&nbsp;</div>
</div>
<!-------------------------------DESIGNATION LIST PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>