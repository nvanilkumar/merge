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
	
	
<!-------------------------------ADD CONTENT PAGE STARTS HERE--------------------------------------------------------------->
<script type="text/javascript" language="javascript">
	function validate_category(){
		if(document.add_category.category_name.value == ''){
			alert("Please Enter A Category");
			document.add_category.category_name.focus();
			return false;
		}
		if(document.add_category.DispOrder.value == ''){
			alert("Please Enter Display Order");
			document.add_category.DispOrder.focus();
			return false;
		}
		
		return true;
	}
</script>
<script language="javascript">
  	document.getElementById('ans2').style.display='block';
</script>
<form action="" method="post" name="add_category" onsubmit="return validate_category();">
<table width="50%" border="0" cellpadding="3" cellspacing="3">
  <tr>
    <td colspan="2"><strong>Add Category</strong> </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Category Name : </td>
    <td><label>
      <input name="category_name" type="text" id="category_name" />
    </label></td>
  </tr>
  <tr>
    <td>Display Order : </td>
    <td><label>
      <input name="DispOrder" type="text" id="DispOrder" />
    </label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><label>
      <input type="submit" name="Submit" value="Add" />
    </label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><?php echo $MsgcategoryExist; ?></td>
  </tr>
</table>
</form>

<!-------------------------------ADD CONTENT PAGE ENDS HERE--------------------------------------------------------------->
	
	
	
	</div>
	</td>
  </tr>
</table>
	</div>	
</body>
</html>