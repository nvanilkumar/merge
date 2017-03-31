<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents - Admin Panel - Email CMS Management</title>
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/pagi_sort.css" rel="stylesheet" type="text/css" media="all" />	
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.js"></script>	
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.js"></script>	
	<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/sortpagi.js"></script>
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
				<div id="divMainPage" style="margin-left:10px; margin-right:5px;">
<!-------------------------------Manage Email Content PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans10').style.display='block';
</script>
					<div align="center" style="width:100%">&nbsp;</div>
					<div align="center" style="width:100%" class="headtitle">Manage EMail Content</div>
					<div align="center" style="width:100%">&nbsp;</div>
					<div><?=$msgActionStatus?></div>
					<div id="add_image">
					<form name="frmAddEmailCMS" action="" method="post" enctype="multipart/form-data" onsubmit="val_addEmailContent();">
						<input type="hidden" name="Id" value="<?=$Id?>" />
						<table width="90%" cellpadding="1" cellspacing="2">
							<tr>
								<td width="100%" colspan="2"><b>Add EMail Content </b></td>
							</tr>
							<tr>
								<td width="100%" colspan="2">&nbsp;</td>
							</tr>
							<tr>
								<td width="25%" valign="top">
									<b>Message Content &nbsp;<font color="#FF0000">*</font>&nbsp;:</b></td>
								<td width="75%">[ Maximum 5000 Characters ] <br />
								  <br />
									<textarea name="txtMsg" id="txtMsg" cols="50" rows="10"><?=$txtMsg?></textarea></td>
							</tr>
							<tr>
								<td width="25%">
									<b>Message Type  &nbsp;<font color="#FF0000">*</font>&nbsp;:</b></td>
								<td width="75%">
									<input type="text" name="txtMsgType" id="txtMsgType" value="<?=$txtMsgType?>" maxlength="50" size="50" /></td>
							</tr>
							<tr>
								<td width="25%">
									<b>Send Through Email Id  &nbsp;<font color="#FF0000">*</font>&nbsp;:</b></td>
								<td width="75%">
									<input type="text" name="txtSendThruEMailId" id="txtSendThruEMailId" value="<?=$txtSendThruEMailId?>" maxlength="50" size="50" /></td>
							</tr>
							<tr>
								<td width="25%">&nbsp;</td>
								<td width="75%">
									<input type="Submit" name="Submit" value="Submit" /></td>
							</tr>
							<tr>
								<td width="100%" colspan="2">&nbsp;</td>
							</tr>							
						</table>
						<script language="javascript" type="text/javascript">
							function val_addEmailContent()
							{
								var msg = document.getElementById('txtMsg').value;
								var msgtype = document.getElementById('txtMsgType').value;
								var msgthrough = document.getElementById('txtSendThruEMailId').value;
								
								if(msg == '')
								{
									alert("Please Enter the Message");
									document.getElementById('txtMsg').focus();
									return false;
								}
								else if(msgtype == '')
								{
									alert("Please Enter Message Type");
									document.getElementById('txtMsgType').focus();
									return false;								
								}
								else if(msgthrough == '')
								{
									alert("Please Enter the EMail Message Through EMail Id");
									document.getElementById('txtSendThruEMailId').focus();
									return false;								
								}
							}
						</script>
					</form>
					</div>
					<table width="90%" cellpadding="2" cellspacing="1">
						<tr>
							<td class="tblcont1" width="15%"><div align="left"><b>Sr. No.</b></div></td>
							<td class="tblcont1" width="60%"><div align="left"><b>Message Type</b></div></td>
							<td class="tblcont1" width="25%"><div align="left"><b>Send Through EMailId</b></div></td>
							<td class="tblcont1" width="15%"><div align="left"><b>Action</b></div></td>
						</tr>
						<?php	
						$count=0; 
						
						for($i = 0; $i < count($EMailMsgsList); $i++)
						{ 
							$count++; 
						?>
						<tr>
							<td class="helpBod">
							<div align="left"><?=$count.'.'?></div></td>
							<td class="helpBod">
								<div align="left"><?=$EMailMsgsList[$i]['type']?></div>
							</td>
							<td class="helpBod">
								<div align="left"><?=$EMailMsgsList[$i]['fromemailid']?></div>
							</td>
							<td class="helpBod">
								<div align="left"><a href="emailCMSManagement.php?Edit=<?=$EMailMsgsList[$i]['id']?>">Edit</a>&nbsp;
								<a href="emailCMSManagement.php?delete=<?=$EMailMsgsList[$i]['id']?>">Delete</a></div>
							</td>
						</tr>
						<?php 
						} 
						?>
					</table>
<!-------------------------------Manage Email Content PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		  </tr>
		</table>
	</div>	
</body>
</html>