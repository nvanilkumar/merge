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
<!-------------------------------ADD PARTNER PAGE STARTS HERE--------------------------------------------------------------->
<script type="text/javascript" language="javascript">
	function validate_partner(){
	
		var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	
		if(document.add_partner.p_user.value == ''){
			alert("Please Enter A UserName.");
			document.add_partner.p_user.focus();
			return false;
		}
		
		if(document.add_partner.p_name.value == ''){
			alert("Please Enter A Display Name.");
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
<div align="center" style="width:100%">
<div align="center" style="width:100%" class="headtitle">Add a Partners</div>
<div align="center" style="width:100%">&nbsp;</div>
<form action="" method="post" name="add_partner" onSubmit="return validate_partner();">
<table width="70%" border="0" cellpadding="3" cellspacing="3" style="border:thin; border-color:#006699; border-style:solid;">
  <tr>
    <td width="9%" class="tblcont">&nbsp;</td>
    <td width="30%" align="left" valign="middle" class="tblcont"> User Name  <font color="#FF0000">*</font></td>
    <td width="5%" align="center" valign="middle" class="tblcont"> : </td>
    <td width="56%" align="left" valign="middle"><label>
      <input name="p_user" type="text" id="p_user" maxlength="50">
    </label></td>
  </tr>
  <tr>
    <td class="tblcont">&nbsp;</td>
    <td align="left" valign="middle" class="tblcont">Display Name  <font color="#FF0000">*</font></td>
    <td align="center" valign="middle" class="tblcont"> : </td>
    <td align="left" valign="middle"><label>
      <input name="p_name" type="text" id="p_name" title="Partner" maxlength="50" />
    </label></td>
  </tr>
  <tr>
    <td class="tblcont">&nbsp;</td>
    <td align="left" valign="middle" class="tblcont">Partners Name</td>
    <td align="center" valign="middle" class="tblcont"> : </td>
    <td align="left" valign="top">
      <div>
	  	<table width="100%">
			<tr><td>Salutation</td>
			<td>First Name  <font color="#FF0000">*</font></td>
			<td>Middle Name</td>
			<td>Last Name  <font color="#FF0000">*</font></td>
			</tr>
			<tr>
				<td>
					<select name="Salutation">
						<option value="0">Select</option>
						<option value="1">Mr.</option>
						<option value="2">Miss.</option>
						<option value="3">Mrs.</option>
						<option value="4">Msr.</option>
						<option value="5">Dr.</option>
					</select>
				</td>
				<td><input name="f_name" type="text" id="f_name" maxlength="25" title="First Name" /></td>
				<td><input name="m_name" type="text" id="m_name" maxlength="25" title="Middle Name" /></td>
				<td><input name="l_name" type="text" id="l_name" maxlength="25" title="Last Name" /></td>
			</tr>
		</table>
		</div>
    </td>
  </tr>
  <tr>
    <td class="tblcont">&nbsp;</td>
    <td align="left" valign="middle" class="tblcont">Company Name  <font color="#FF0000">*</font></td>
    <td align="center" valign="middle" class="tblcont"> : </td>
    <td align="left" valign="middle"><label>
      <input name="p_cname" type="text" id="p_cname" maxlength="100" />
    </label></td>
  </tr>
  <tr>
    <td class="tblcont">&nbsp;</td>
    <td align="left" valign="middle" class="tblcont">Offer Description  <font color="#FF0000">*</font></td>
    <td align="center" valign="middle" class="tblcont"> : </td>
    <td align="left" valign="middle"><label>
      <input name="p_offer" type="text" id="p_offer" maxlength="1000" />
    </label></td>
  </tr>
  <tr>
    <td class="tblcont">&nbsp;</td>
    <td align="left" valign="middle" class="tblcont">Contact No  <font color="#FF0000">*</font></td>
    <td align="center" valign="middle" class="tblcont"> : </td>
    <td align="left" valign="middle"><label>
      <input name="p_contact" type="text" id="p_contact" maxlength="15" />
    </label></td>
  </tr>
  <tr>
    <td class="tblcont">&nbsp;</td>
    <td align="left" valign="middle" class="tblcont">Email Id  <font color="#FF0000">*</font> </td>
    <td align="center" valign="middle" class="tblcont">: </td>
    <td align="left" valign="middle"><label>
      <input name="p_email" type="text" id="p_email" maxlength="100" />
    </label></td>
  </tr>
  <tr>
    <td class="tblcont">&nbsp;</td>
    <td align="left" valign="middle" class="tblcont">URL <font color="#FF0000">*</font></td>
    <td align="center" valign="middle" class="tblcont"> : </td>
    <td align="left" valign="middle"><label>
      <input name="p_url" type="text" id="p_url" maxlength="100" />
    </label></td>
  </tr>
  <tr>
    <td class="tblcont">&nbsp;</td>
    <td align="left" valign="middle" class="tblcont">Status</td>
    <td align="center" valign="middle" class="tblcont"> : </td>
    <td align="left" valign="middle"><label>
      <select name="p_status" id="p_status">
        <option value="1" selected="selected">Active</option>
        <option value="0">Inactive</option>
      </select>
    </label></td>
  </tr>
  <tr>
  	<td colspan="3">&nbsp;</td>
    <td align="left" valign="middle"><label>
      <input type="submit" name="Submit" value="Add" />
    </label></td>
    </tr>
	<tr>
		<td colspan="3">&nbsp;</td>
		<td align="left" valign="middle"><?php echo $msgAddPartner; ?></td>
	</tr>
</table>
</form>
</div>
<!-------------------------------ADD PARTNER PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>