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
				<div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
<!-------------------------------ADD CITY PAGE STARTS HERE--------------------------------------------------------------->
<script type="text/javascript" language="javascript">
  	document.getElementById('ans9').style.display='block';
	
	


	function validateGCForm(f)
	{
		var Ebs=f.Ebs.value;
		var Paypal=f.Paypal.value;
		//var Mobikwik=f.Mobikwik.value;
		//var Paytm=f.Paytm.value;
		
		var Cod=f.Cod.value;
		var Counter=f.Counter.value;
		
	
		
		
		if(Ebs == "")
		{
			alert("Please Enter commission value for EBS");
			f.Ebs.focus();
			return false;
		}
		else if(isNaN(Ebs) || Ebs < 0)
		{
			alert("Invalid commission value for EBS");
			f.Ebs.focus();
			return false;
		}
		
		
		if(Paypal == "")
		{
			alert("Please Enter commission value for Paypal");
			f.Paypal.focus();
			return false;
		}
		else if(isNaN(Paypal) || Paypal < 0)
		{
			alert("Invalid commission value for Paypal");
			f.Paypal.focus();
			return false;
		}
		
		
//		if(Mobikwik == "")
//		{
//			alert("Please Enter commission value for Mobikwik");
//			f.Mobikwik.focus();
//			return false;
//		}
//		else if(isNaN(Mobikwik) || Mobikwik < 0)
//		{
//			alert("Invalid commission value for Mobikwik");
//			f.Mobikwik.focus();
//			return false;
//		}
		
//		if(Paytm == "")
//		{
//			alert("Please Enter commission value for Paytm");
//			f.Paytm.focus();
//			return false;
//		}
//		else if(isNaN(Paytm) || Paytm < 0)
//		{
//			alert("Invalid commission value for Paytm");
//			f.Paytm.focus();
//			return false;
//		}
		
		
		if(Cod == "")
		{
			alert("Please Enter commission value for COD");
			f.Cod.focus();
			return false;
		}
		else if(isNaN(Cod) || Cod < 0)
		{
			alert("Invalid commission value for COD");
			f.Cod.focus();
			return false;
		}
		
		
		if(ServiceTax == "")
		{
			alert("Please Enter ServiceTax value");
			f.ServiceTax.focus();
			return false;
		}
		else if(isNaN(ServiceTax) || ServiceTax < 0)
		{
			alert("Invalid ServiceTax value");
			f.ServiceTax.focus();
			return false;
		}
		
		if(MEeffort == "")
		{
			alert("Please Enter commission value for Meraevents Sales Effort");
			f.MEeffort.focus();
			return false;
		}
		else if(isNaN(MEeffort) || MEeffort < 0)
		{
			alert("Invalid commission value for Meraevents Sales Effort");
			f.MEeffort.focus();
			return false;
		}
		
		
		
		
		if(Counter == "")
		{
			alert("Please Enter commission value for Counter");
			f.Counter.focus();
			return false;
		}
		else if(isNaN(Counter) || Counter < 0)
		{
			alert("Invalid commission value for Counter");
			f.Counter.focus();
			return false;
		}
		
		return true;
	}
</script>

<form action="" method="post" name="add_city" onSubmit="return validateGCForm(this);">
	<table width="50%" border="0" cellpadding="3" cellspacing="3">
		<tr>
			<td colspan="2"><strong>Global Commission Charges</strong></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
        
        <?php
		if(isset($_SESSION['GCupdated']))
		{
			?><tr><td colspan="2" style="color:#060; font-weight:bold; font-size:14px">Global commission values updated successfully..</td></tr><?php
			unset($_SESSION['GCupdated']);
		}
		
		?>
        
		<tr>
          <td>Card : </td>
		  <td><label>
            <input type="text" name="Ebs" maxlength="80" value="<?php echo $Ebs; ?>" />
          </label></td>
		  </tr>
		<tr>
          <td>Paypal : </td>
		  <td><label>
            <input type="text" name="Paypal" maxlength="80"  value="<?php echo $Paypal; ?>" />
          </label></td>
		  </tr>
          
<!--        <tr>
          <td>Mobikwik : </td>
		  <td><label>
            <input type="text" name="Mobikwik" maxlength="80"  value="<?php echo $Mobikwik; ?>" />
          </label></td>
		</tr>
        
        
        <tr>
          <td>Paytm : </td>
		  <td><label>
            <input type="text" name="Paytm" maxlength="80"  value="<?php echo $Paytm; ?>" />
          </label></td>
		</tr>
        -->
        
        
        <tr>
          <td>Service Tax : </td>
		  <td><label>
            <input type="text" name="ServiceTax" maxlength="80"  value="<?php echo $ServiceTax; ?>" />
          </label></td>
	    </tr>
        
        <tr>
          <td>Meraevents Sales Effort : </td>
		  <td><label>
            <input type="text" name="MEeffort" maxlength="80"  value="<?php echo $MEeffort; ?>" />
          </label></td>
	    </tr>

          
		<tr>
			<td>COD : </td>
			<td><label>
			<input type="text" name="Cod" maxlength="80"  value="<?php echo $Cod; ?>" />
			</label></td>
		</tr>
<tr>
			<td>Counter : </td>
			<td><label>
			<input type="text" name="Counter" maxlength="80" value="<?php echo $Counter; ?>" />  
			</label></td>
  </tr>

		<tr>
			<td>&nbsp;</td>
			<td><label>
			<input type="submit" name="Submit" value="Update" />
			</label></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	</table>
</form>
<div><?php echo $msgStateCityExist; ?></div>
<!-------------------------------ADD CITY PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>