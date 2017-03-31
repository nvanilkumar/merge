<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents -Master Management - City Management</title>
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
<!-------------------------------CITY LIST PAGE STARTS HERE--------------------------------------------------------------->

<div align="center" style="width:100%">
<form action="" method="post" name="edit_form">
<table width="60%" border="0" cellpadding="3" cellspacing="3">
      <tr>
        <td align="center" colspan="2" valign="middle" class="headtitle"><strong>Moozup Management</strong> </td>
      </tr>
      
      <tr>
        <td colspan="2"><table width="100%" class="sortable" >
          <thead>
          <tr>
            <td class="tblcont1">MeraEventsId</td>
			<td class="tblcont1">MoozupId</td>
            <td class="tblcont1">DPassword</td>
            <td class="tblcont1" ts_nosort="ts_nosort">Edit </td>
            <td class="tblcont1" ts_nosort="ts_nosort">Delete</td>
          </tr></thead>
		<?php	
		$flag=0;							  
		for($i = 0; $i < count($JList); $i++)
		{
		?>
          <tr>
            <td class="helpBod"><a href="jujama_move.php?mid=<?php echo $JList[$i]['MEventId']; ?>"><?php echo $JList[$i]['MEventId']; ?></a></td>
			<td class="helpBod"><?php echo $JList[$i]['JEventId']; ?></td>
            <td class="helpBod"><?php echo $JList[$i]['DPassword']; ?></td>
            <td class="helpBod"><a href="jujama_edit.php?id=<?php echo $JList[$i]['Id']; ?>">Edit</a></td>
            <td class="helpBod"><input type="checkbox" name="jujama[]" value="<?php echo $JList[$i]['Id']; ?>" /></td>
          </tr>
		<?php 
		}
		?>
        </table></td>
      </tr>
      <tr>
      <td><label>
          <div align="right">
            <input type="button" name="Add" value="Add" onClick="document.location='addmoozup.php'">
            </div>
        </label></td> 
        <td><label>
          <div align="right">
            <input type="submit" name="Submit" value="Delete" onClick="return confirm('Are You Sure You Want To Delete These Event.\n\nThe Changes Cannot Be Undone');">
            </div>
        </label></td>
      </tr>
    </table>
</form>
<div align="center" style="width:100%">&nbsp;</div>
</div>
<!-------------------------------CITY LIST PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>