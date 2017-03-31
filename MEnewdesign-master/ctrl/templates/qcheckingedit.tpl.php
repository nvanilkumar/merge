
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<title>MeraEvents -Menu Content Management</title>
		<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
		<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortable.js"></script>	
        <script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortpagi.js"></script>
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.css" />
<script type="text/javascript" language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.js"></script>
<script language="javascript">
	function SEdt_validate()
	{
		
		var echk = document.frmEofMonth.echk.value;
		var SalesId = document.frmEofMonth.SalesId.value;
		if(SalesId=="")
			{
				alert('Please Select Sales Person.');
				document.frmEofMonth.SalesId.focus();
				return false;
			}
			if(echk=="")
			{
				alert('Please Select Status.');
				document.frmEofMonth.echk.focus();
				return false;
			}
	
	}
    </script>	
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
	
	
<script language="javascript">
  	document.getElementById('ans13').style.display='block';
</script>

 
<form action="" method="post" name="frmEofMonth" >
<?php echo $msg;?>

<table width="98%" border="0" cellpadding="3" cellspacing="3">
  <tr>
    <td colspan="2"><strong>Quality Checking Edit</strong> </td>
  </tr>
  
   <tr>
    <td> Event Name :</td>
    <td width="81%"><label><?php echo $Global->GetSingleFieldValue("select title AS Title from event where deleted=0 and id='".$_REQUEST[eid]."'");?></label></td>
  </tr>
  <tr>
    <td> Select Sales Person :</td>
    <td width="81%"><label>
      <select name="SalesId" id="SalesId" >
        <option value="">Select</option>
        <?php 
		$TotalSalesQueryRES = count($SalesQueryRES);

		for($i=0; $i < $TotalSalesQueryRES; $i++)
		{
		?>
         <option value="<?php echo $SalesQueryRES[$i]['SalesId'];?>" <?php  if($SalesQueryRES[$i]['SalesId']==$salesid){?> selected="selected" <?php  }?>><?php echo $SalesQueryRES[$i]['SalesName'];?></option>
         <?php  }?>
      </select>
    </label></td>
  </tr>
   <tr>
    <td> Select Status :</td>
    <td width="81%"><label>
      <select name="echk" id="echk" >
        <option value="">Select</option>
        <option value="0" <?php  if($echkd=="0"){?> selected="selected" <?php  }?>>NotChecked</option>
         <option value="1" <?php  if($echkd=="1"){?> selected="selected" <?php  }?>>Checked</option>
        
      </select>
    </label></td>
  </tr>
 
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
<tr> <td width="19%" align="left" valign="middle"><input type="submit" name="submit" value="Submit" onclick="return SEdt_validate();" /></td></tr>
  <tr>
    <td colspan="2">&nbsp;</td>
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