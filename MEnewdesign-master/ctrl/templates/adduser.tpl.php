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
<!-------------------------------EDIT USER PAGE STARTS HERE--------------------------------------------------------------->
<script>
	function isValidPattern_Email(str)
	{
		var regEx = /^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/i;
		return regEx.test(str);
	}
	function valdt()
	{
		var email = document.frmadmin.txtemail.value;
		var name = document.frmadmin.txtname.value;
		var rol = document.frmadmin.usrrole.value;
		var sts = document.frmadmin.status.value;
		
		if(email == '')
		{
			alert('Please Enter Email!!');
			document.frmadmin.txtemail.focus();
			return false;
		}
		if(!isValidPattern_Email(email))
		{
			alert('Please Enter Valid Email address!!');
			document.frmadmin.txtemail.focus();
			return false;
		}
		if(name == '')
		{
			alert('Please Enter User Name!!');
			document.frmadmin.txtname.focus();
			return false;
		}
		if(rol == -1)
		{
			alert('Please Select user designation!!');
			return false;
		}
		if(sts == -1)
		{
			alert('Please Select status for user!!');
			return false;
		}
		
	}
</script>
<style type="text/css">
<!--
.style1 {color: #FF0000}
-->
</style>

<div>
<form name="frmadmin" method="post" action="" onSubmit="return valdt();">
	<input type="hidden" name="newid" value="<?php echo $Id; ?>" />
	<input type="hidden" name="edit_id" value="<?php echo $Id; ?>" />
	<table width="100%" cellpadding="0" cellspacing="5">
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2" class="headtitle">Edit Admin User</td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
			<td align="center" colspan="2">
				<table width="60%" cellspacing="5">
					<tr>
						<td class="tblcont">Email ID </td>
						<td align="center" valign="middle">:</td>
						<td><input type="text" name="txtemail" value="<?php echo $Email; ?>" /></td>
					</tr>
					<tr>
						<td class="tblcont">Name</td>
						<td align="center" valign="middle">:</td>
						<td><input type="text" name="txtname" value="<?php echo $UserName; ?>" /></td>
					</tr>
					<tr>
						<td class="tblcont">Designation</td>
						<td align="center" valign="middle">:</td>
						<td>
							<select name="usrrole">
								<option value="-1" selected="selected">SELECT</option>
								<!--option value="Super Admin" <?php /*?><?php if($rol == 'Super Admin'){ ?><?php */?> selected="selected" <?php /*?><?php } ?><?php */?>>Super Admin</option>
								<option value="Backend Operator"  <?php /*?><?php if($rol == 'Backend Operator'){ ?><?php */?> selected="selected" <?php /*?><?php } ?><?php */?>>Backend Operator</option-->
								<?php 
								for($j = 0; $j < count($DesignationList); $j++)
								{
								?>
								<option value="<?php echo $DesignationList[$j]['Id']; ?>" <?php if($DesignationList[$j]['Id'] == $Designation) { ?> selected="selected" <?php } ?>><?php echo $DesignationList[$j]['Designation']; ?></option>
								<?php } ?>
							</select>									
						</td>
					</tr>
					<tr>
						<td class="tblcont">Status</td>
						<td align="center" valign="middle">:</td>
						<td>
							<select name="status">
								<option value="-1">SELECT</option>
								<option value="1" <?php if($status == 1) { ?> selected="selected" <?php } ?>>ACTIVE</option>
								<option value="0" <?php if($status == 0) { ?> selected="selected" <?php } ?>>INACTIVE</option>
							</select>									</td>
					</tr>
					<tr><td colspan="3">&nbsp;</td></tr>
					<?php /*?><?php if($_GET['flag'] == 1){?><?php */?>
					<!--tr><td colspan="3">Email Address or User Name Is Already Used.</td></tr-->
					<?php /*?><?php }?><?php */?>
					<!--tr><td colspan="3">&nbsp;</td></tr-->
					<tr>
						<td align="right">&nbsp;</td>
						<td>&nbsp;</td>
						<td><input type="submit" name="save" value="SUBMIT">&nbsp;<!--input type="reset" value="Cancel" name="reset"--></td>
					</tr>
					<tr><td colspan="3" align="left" valign="middle">
						<span class="style1"><strong>Note</strong>: Backend Operators will only be able to Add and Modify content, and not be able to Delete/Suspend content. Also - BO's will not be able to create new users.</span></td>
					</tr>
				</table>
		  </td>
		</tr>
	</table>
</form>	
</div>
<!-------------------------------EDIT USER PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>