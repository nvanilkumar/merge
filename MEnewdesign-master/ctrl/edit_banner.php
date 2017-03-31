<?php
	include_once("includes/application_top.php");
	include('includes/functions.php');
	 include 'loginchk.php';
	$path_to_dir = '../sites/all/themes/denver/slideshow_images';
	if(isset($_POST['Submit']))
	{
	     
	    $path = $path_to_dir."/".$_GET['name']; 
		@unlink($path);
			
		copy($_FILES['banner']['tmp_name'], $path);
			
		//header("location:manage_banner.php?edit=edited");
		?>
		<script language="javascript" type="text/javascript">
		window.close();
		</script>
		<?
	}
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<tr>
<td colspan="3" id="edit_image">
<form action="" method="post" enctype="multipart/form-data">
<table width="100%">
<tr>
<td colspan="3"><img src="<?=$path_to_dir?>/<?=$_GET['name']?>" width="100px" height="100px"/></td>
</tr>
<tr>
<td colspan="3"></td>
</tr>
	<tr>
		<td width="6%">
			<b>Image :</b>		</td>
		<td width="23%">
			<input type="file" name="banner">
		</td>
		<td width="71%">
			<input type="Submit" name="Submit" value="Edit Banner">
			
		</td>
	</tr>
</table>
</form>
</td>
</body>
</html>
