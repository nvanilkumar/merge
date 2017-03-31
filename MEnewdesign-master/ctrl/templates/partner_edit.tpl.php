<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents -Master Management - User Management</title>
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
<!-------------------------------EDIT PARTNER PAGE STARTS HERE--------------------------------------------------------------->
<script type="text/javascript" language="javascript">
	function validate_partner(){
	
		var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	
		if(document.add_partner.p_user.value == ''){
			alert("Please Enter A UserName.");
			document.add_partner.p_user.focus();
			return false;
		}
		
		if(document.add_partner.p_name.value == ''){
			alert("Please Enter A Name.");
			document.add_partner.p_name.focus();
			return false;
		}
		
		if(document.add_partner.f_name.value == ''){
			alert("Please Enter A Partners First Name.");
			document.add_partner.f_name.focus();
			return false;
		}
		if(document.add_partner.l_name.value == ''){
			alert("Please Enter A Partners Last Name.");
			document.add_partner.l_name.focus();
			return false;
		}

		
		if(document.add_partner.p_cname.value == ''){
			alert("Please Enter A Company Name.");
			document.add_partner.p_cname.focus();
			return false;
		}
		
		if(document.add_partner.p_offer.value == ''){
			alert("Please Enter Offer Description.");
			document.add_partner.p_offer.focus();
			return false;
		}
		
		if(document.add_partner.p_contact.value == ''){
			alert("Please Enter Contact No.");
			document.add_partner.p_contact.focus();
			return false;
		}
		
		if(!isInteger(document.add_partner.p_contact.value)){
			alert("Contact No Should Be Numeric");
			document.add_partner.p_contact.focus();
			return false;
		}
		
		if(document.add_partner.p_email.value == ''){
			alert("Please Enter Email Address.");
			document.add_partner.p_email.focus();
			return false;
		}
		
		var address = document.add_partner.p_email.value;
		if(reg.test(address) == false){
			alert('Invalid Email Address.');
			document.add_partner.p_email.focus();
			return false;
		}
		
		if(document.add_partner.p_url.value == ''){
			alert("Please Enter URL.");
			document.add_partner.p_url.focus();
			return false;
		}
		
		return true;
	}
	
	function isInteger(s){
	var i;
    for (i = 0; i < s.length; i++){   
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    // All characters are numbers.
    return true;
}
</script>

<form action="" method="post" name="add_partner" onSubmit="return validate_partner();">
<table width="70%" border="0" cellpadding="3" cellspacing="3">
  <tr>
    <td colspan="2" align="center"><strong>Edit a Partners</strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td>User Name <font color="#FF0000">*</font> : </td>
    <td><label>
      <input name="p_user" type="text" id="p_user" value="<?php echo $p_user; ?>" />
    </label></td>
  </tr>
  <tr>
    <td>Display Name <font color="#FF0000">*</font> : </td>
    <td><label>
      <input name="p_name" type="text" id="p_name" value="<?php echo $p_name; ?>" />
    </label></td>
  </tr>
    <tr>
    <td>Partners Name : </td>
    <td>
      <div>
	  	<table width="100%">
			<tr><td>Salutation</td><td>First Name <font color="#FF0000">*</font></td><td>Middle Name</td><td>Last Name <font color="#FF0000">*</font></td></tr>
			<tr>
				<td>
					<select name="Salutation">
						<option value="0" <?php if($salutation == 0) { ?> selected="selected" <?php } ?>>Select</option>
						<option value="1" <?php if($salutation == 1) { ?> selected="selected" <?php } ?>>Mr.</option>
						<option value="2" <?php if($salutation == 2) { ?> selected="selected" <?php } ?>>Miss.</option>
						<option value="3" <?php if($salutation == 3) { ?> selected="selected" <?php } ?>>Mrs.</option>
						<option value="4" <?php if($salutation == 4) { ?> selected="selected" <?php } ?>>Msr.</option>
						<option value="5" <?php if($salutation == 5) { ?> selected="selected" <?php } ?>>Dr.</option>
					</select>
				</td>
				<td><input name="f_name" type="text" id="f_name" value="<?php echo $f_name; ?>" maxlength="25" title="First Name" /></td>
				<td><input name="m_name" type="text" id="m_name" value="<?php echo $m_name; ?>" maxlength="25" title="Middle Name" /></td>
				<td><input name="l_name" type="text" id="l_name" value="<?php echo $l_name; ?>" maxlength="25" title="Last Name" /></td>
			</tr>
		</table>
		</div>
    </td>
  </tr>
  <tr>
    <td>Company Name <font color="#FF0000">*</font> : </td>
    <td><label>
      <input name="p_cname" type="text" id="p_cname" value="<?php echo $p_cname; ?>" />
    </label></td>
  </tr>
  <tr>
    <td>Offer Description <font color="#FF0000">*</font> : </td>
    <td><label>
      <input name="p_offer" type="text" id="p_offer" value="<?php echo $p_offer; ?>" />
    </label></td>
  </tr>
  <tr>
    <td>Contact No <font color="#FF0000">*</font> : </td>
    <td><label>
      <input name="p_contact" type="text" id="p_contact" value="<?php echo $p_contact; ?>" />
    </label></td>
  </tr>
  <tr>
    <td>Email Id  <font color="#FF0000">*</font> : </td>
    <td><label>
      <input name="p_email" type="text" id="p_email" value="<?php echo $p_email; ?>"/>
    </label></td>
  </tr>
  <tr>
    <td>URL <font color="#FF0000">*</font> : </td>
    <td><label>
      <input name="p_url" type="text" id="p_url" value="<?php echo $p_url; ?>"/>
    </label></td>
  </tr>
  <tr>
    <td>Status : </td>
    <td><label>
      <select name="p_status" id="p_status">
        <option value="1" <?php if($p_status == 1) { echo "selected=selected"; } ?> >Active</option>
        <option value="0" <?php if($p_status == 0) { echo "selected=selected"; } ?>  >Suspend</option>
      </select>
    </label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><label>
      <input type="submit" name="Submit" value="Update" />
	  <input type="hidden" name="Id" value="<?php echo $Id; ?>" />
	  <input type="hidden" name="UserId" value="<?php echo $UserId; ?>" />
    </label></td>
  </tr>
  
</table>
</form>
<!-------------------------------EDIT PARTNER PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>