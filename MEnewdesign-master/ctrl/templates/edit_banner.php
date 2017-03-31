<?php
	include_once("includes/application_top.php");
	include('includes/functions.php');
	include('includes/logincheck.php');
	
/**************************commented on 17082009 need to change afterwords **************************/
	$path_to_dir = '';//'../sites/all/themes/denver/slideshow_images';
	if(isset($_POST['Submit'])){
	     
	    $path = $path_to_dir."/".$_GET['name']; 
		@unlink($path);
		
		$num = $_POST['count'];
		//we will give an unique name, for example the time in unix time format
		echo $image_name=$_GET['name'];
		//the new name will be containing the full path where will be stored (images folder)
		echo $newname=$path_to_dir."/".$image_name;
		
		echo copy($files['banner']['tmp_name'], $newname);
		exit;
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
<title>MeraEvents - Admin Panel - Banner</title>
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
