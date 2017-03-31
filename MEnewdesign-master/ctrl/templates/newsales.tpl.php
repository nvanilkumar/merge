<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<title>MeraEvents -Menu Content Management</title>
		<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
		<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>	
        <script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script>	
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
<script language="javascript">
  	document.getElementById('ans13').style.display='block';
</script>
<script type="text/javascript" language="javascript">
	function validate_category(){
		if(document.add_category.sales_name.value == ''){
			alert("Please Enter Name");
			document.add_category.sales_name.focus();
			return false;
		}
		if(document.add_category.sales_mobile.value == ''){
			alert("Please Enter Mobile no");
			document.add_category.sales_mobile.focus();
			return false;
		}
		if(document.add_category.sales_email.value == ''){
			alert("Please Enter Email");
			document.add_category.sales_email.focus();
			return false;
		}
		
		return true;
	}
	
	function delsales(SId)
	{
		var r=confirm("Are you sure want to delete Sales person!!");
if (r==true)
  {
		window.location="sales.php?SalesId="+SId+"&del=true";
		
	}
	
	}
</script>

<form action="" method="post" name="add_category" onsubmit="return validate_category();">
<input type="hidden" name="SalesId" id="SalesId" value="<?php echo $_REQUEST['SalesId'];?>" />
<table width="80%" border="0" cellpadding="3" cellspacing="3">
  <tr>
    <td colspan="2"><strong>Add Sales Person</strong> </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
      <td style="width: 15%" > Name : </td>
    <td><label>
      <input name="sales_name" type="text" id="sales_name" value="<?php echo $resSales[0]['name'];?>" />
    </label></td>
  </tr>
  <tr>
    <td> Mobile : </td>
    <td><label>
      <input name="sales_mobile" type="text" id="sales_mobile" value="<?php echo $resSales[0]['mobile'];?>"/>
    </label></td>
  </tr>
  <tr>
    <td> Email : </td>
    <td><label>
      <input name="sales_email" type="text" id="sales_email" value="<?php echo $resSales[0]['email'];?>" />
    </label></td>
  </tr>
<tr> <td> Signature : </td>
<td><textarea name="esign" rows="10" cols="70" id="esign" ><?php echo $resSales[0]['signature'];?></textarea> 
				  <script language="javascript">
							var esign= new LiveValidation('esign');
							esign.add( Validate.Presence );
							
							</script>
</td></tr>
  <tr>
    <td>&nbsp;</td>
    <td><label>
    <?php
    if($_REQUEST['SalesId']!=""){?>
    <input type="submit" name="Submit" value="Save" />
    <?php }else{ ?>
      <input type="submit" name="Submit" value="Add" />
      <?php }?>
    </label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><?php echo $MsgcategoryExist; ?></td>
  </tr>
</table>
</form>

<!-------------------------------ADD CONTENT PAGE ENDS HERE--------------------------------------------------------------->
	<div align="center"><h3>Sales Persons List</h3></div>
  <table width="80%" border="1">
   <tr>
  <th>Name</th>
  <th>Mobile</th>
  <th>Email</th>
  <th>Status</th>
  <th colspan="2">Action</th>
  </tr>
  <?php
  for($i=0;$i<count($resList);$i++)
  {?>
  <tr>
  <td align="center"><?php echo $resList[$i]['name'];?></td>
  <td align="center"><?php echo $resList[$i]['mobile'];?></td>
  <td align="center"><?php echo $resList[$i]['email'];?></td>
  <td align="center"><?php echo $resList[$i]['status']==1?"Active":"Inactive";?></td>
  <td align="center"><a href="sales.php?SalesId=<?php echo $resList[$i]['id'];?>">Edit</a></td>
  <td align="center"><a style="cursor:pointer; text-decoration:underline" onclick="delsales('<?php echo $resList[$i]['id'];?>')">Delete</a></td>
  </tr>
  <?php }?>
  </td></tr>
 </table>
	
	
	</div>
	</td>
  </tr>
  
  
</table>
	</div>	
</body>
</html>