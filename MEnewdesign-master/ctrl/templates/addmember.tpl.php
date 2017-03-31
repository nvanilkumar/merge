<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents -Master Management - Membership Management</title>
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
<!-------------------------------ADD New Memberships PAGE STARTS HERE--------------------------------------------------------------->
<script type="text/javascript" language="javascript">
	function validate_member(){
	
		var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	
		if(document.add_partner.member.value == ''){
			alert("Please Enter A UserName.");
			document.add_partner.member.focus();
			return false;
		}
		
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
<div align="center" style="width:100%" class="headtitle">Add Association Membership Option </div>
<div align="center" style="width:100%">&nbsp;</div>
<form action="" method="post" name="add_partner" onSubmit="return validate_member();">
<table width="50%" border="0" cellpadding="3" cellspacing="3" style="border:thin; border-color:#006699; border-style:solid;">
  <tr>
    <td width="9%" class="tblcont">&nbsp;</td>
    <td width="32%" align="left" valign="middle" class="tblcont">Membership Name </td>
    <td width="5%" align="center" valign="middle" class="tblcont"> : </td>
    <td width="54%" align="left" valign="middle"><label>
      <input name="member" type="text" id="member" value="<?php echo $member; ?>" maxlength="50" />
    </label></td>
  </tr>
  <tr>
    <td colspan="4" align="center" valign="middle"><label>
      <input type="submit" name="Submit" value="Add" />
    </label></td>
    </tr>
</table>
</form>
<div align="center" style="width:100%"><?php echo $msgAssoMemberExist; ?></div>
</div>
<!-------------------------------ADD New MEMBERSHIP PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>