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
				<div id="divMainPage" style="margin-left: 10px; margin-right:5px">
<!-------------------------------EDIT CITY PAGE STARTS HERE--------------------------------------------------------------->
<script type="text/javascript" language="javascript">
	function validate_city(){
	
		if(document.edit_jujama.MEventId.value == "0" || document.edit_jujama.MEventId.value == "")
		{
			alert("Please Enter MeraEvents-EventId");
			document.edit_jujama.MEventId.focus();
			return false;
		}
		if(document.edit_jujama.JEventId.value == '')
		{
			alert("Please Enter Jujama-EventId");
			document.edit_jujama.JEventId.focus();
			return false;
		}
		if(document.edit_jujama.DPassword.value == '')
		{
			alert("Please Enter D-Password");
			document.edit_jujama.DPassword.focus();
			return false;
		}
              if(document.edit_jujama.ParTy.value == '')
		{
			alert("Please Enter Participation Type");
			document.edit_jujama.ParTy.focus();
			return false;
		}

		if(document.edit_jujama.EmailTxt.value == '')
		{
			alert("Please Enter Email Message");
			document.edit_jujama.EmailTxt.focus();
			return false;
		}
		
		return true;
	}
</script>

<form action="" method="post" name="edit_jujama" onSubmit="return validate_jujama();">
<input type="hidden" name="Id" value="<?=$Id;?>"/>
<table width="80%" border="0" cellpadding="3" cellspacing="3">
  <tr>
    <td colspan="2"><strong>Edit Jujama Connect </strong></td>
  </tr>
 <tr>
			<td width="15%">&nbsp;</td>
			<td width="85%">&nbsp;</td>
		</tr>
  <tr>
    <td>MeraEvents.com-Id : </td>
    <td><label>
      <input type="text" name="MEventId" value="<?php echo $MEventId; ?>" maxlength="80" />
    </label></td>
  </tr>
   <tr>
    <td>Jujama-Id : </td>
    <td><label>
      <input type="text" name="JEventId" value="<?=$JEventId;?>" maxlength="80" />
    </label></td>
  </tr>
  <tr>
    <td>D-Password: </td>
    <td><label>
      <input type="text" name="DPassword" value="<?=$DPassword;?>" maxlength="80" />
    </label></td>
  </tr>
  <tr>
			<td>ParticipationType : </td>
			<td><label>
			<input type="text" name="ParTy" value="<?=$ParTy;?>" maxlength="80" />
			</label></td>
		</tr>
<tr>
			<td>SendMail : </td>
			<td><label>
			<input type="checkbox" name="SendMail" <? if($SendMail==1){ ?> checked <? }?> value="1" /> SendMail
			</label></td>
		</tr>


  <tr>
			<td>Message Text : </td>
			<td><label>[ Maximum 5000 Characters, Use UserName for username,PassWord for password,Link for URL  ] </label>
			<textarea class="dropdown tarea1" rows="30" cols="80" style="width: 580px;" id="Description" name="Description" ><?php echo $EmailTxt; ?></textarea>

			</td>
		</tr>
  <tr>
    <td>&nbsp;</td>
    <td><label>
      <input type="submit" name="Submit" value="Update" />
    </label></td>
  </tr>
</table>
</form>
<!-------------------------------EDIT CITY PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>
<!-- TinyMCE -->
<script type="text/javascript" src="<?=_HTTP_CF_ROOT;?>/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="<?=_HTTP_CF_ROOT;?>/tinymce/jscripts/tiny_mce/tiny_mce_text.js"></script>
<!-- /TinyMCE -->