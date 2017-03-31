<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>meraevents.com - Admin Panel - Manage Delegate FeedBack</title>
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/pagi_sort.min.css.gz" rel="stylesheet" type="text/css" media="all" />	
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>	
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script>	
	<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/sortpagi.min.js.gz"></script>
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
<!-------------------------------Manage Banner PAGE STARTS HERE--------------------------------------------------------------->
					<div align="center" style="width:100%">&nbsp;</div>
					<div align="center" style="width:100%" class="headtitle">Manage Delegate FeedBack</div>
					<div align="center" style="width:100%">&nbsp;</div>
					<div><?=$msgActionStatus?></div>
					<form name="frmEditBanner" action="" method="post" enctype="multipart/form-data">
						<input type="hidden" name="editid" value="<?=$ResTestQuery[0]['Id']?>" />
                                           
						
						<table width="90%" cellpadding="1" cellspacing="2">
							<tr>
								<td width="100%" colspan="2"><b>Edit Delegate FeedBack</b></td>
							</tr>
							<tr>
								<td width="100%" colspan="2">&nbsp;</td>
							</tr>
							<tr>
								<td width="25%">
									<b>Name &nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
								</td>
								<td width="75%">
									<input type="text" name="txtName" id="txtName" value="<?=$ResTestQuery[0]['vFName']?>" maxlength="50" size="50" />
								</td>
							</tr>
                            
                            <tr>
								<td width="25%">
									<b>Title &nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
								</td>
								<td width="75%">
									<input type="text" name="txtTitle" id="txtTitle" value="<?=$ResTestQuery[0]['Title']?>" maxlength="50" size="50" />
								</td>
							</tr>
							<tr>
								<td width="25%">
									<b>Email&nbsp;:</b>
								</td>
								<td width="75%">
									<input type="text" name="txtemail" id="txtemail" value="<?=$ResTestQuery[0]['vEmail']?>" size="50" />
								</td>
							</tr>
							<tr>
								<td width="25%">
									<b>Mobile &nbsp;:</b>
								</td>
								<td width="75%">
									<input type="text" name="txtMobile" id="txtMobile" value="<?=$ResTestQuery[0]['vMobile']?>" maxlength="100" size="50" />
								</td>
							</tr>
							<tr>
								<td width="25%">
									<b>Testimonials&nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
								</td>
								<td width="75%">
									<textarea name="txtComment" id="txtComment" rows=10 cols=50><?=$ResTestQuery[0]['tComment']?></textarea>
								</td>
							</tr>
                               <tr>
								<td width="25%">
									<b>eStatus &nbsp;&nbsp;:</b>
								</td>
								<td width="75%"><select name="eStatus" id="eStatus">
                                <option value="Inactive" <? if($ResTestQuery[0]['eStatus']=="Inactive"){ ?> selected="selected" <? } ?>>Inactive</option>
                                <option value="Active" <? if($ResTestQuery[0]['eStatus']=="Active"){ ?> selected="selected" <? } ?>>Active</option>
                                </select>
								</td>
							</tr>
							<tr>
								<td width="25%">&nbsp;</td>
								<td width="55%">
									<input type="Submit" name="Update" value="UpdateFeedBack" />
								</td>
							</tr>
							<tr>
								<td width="100%" colspan="2">&nbsp;</td>
							</tr>							
						</table>
						<script language="javascript" type="text/javascript">
							function val_editBanner()
							{
								var txtName = document.getElementById('txtName').value;
							   var txtComment = document.getElementById('txtComment').value;
							
								
								if(txtName == '')
								{
									alert("Please Enter the  Name");
									document.getElementById('txtName').focus();
									return false;
								}
								
								else if(txtComment == '')
								{
									alert("Please Enter FeedBack");
									document.getElementById('txtComment').focus();
									return false;								
								}
							}
						</script>
					</form>
<!-------------------------------Manage Banner PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		  </tr>
		</table>
	</div>	
</body>
</html>