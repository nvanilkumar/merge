<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>meraevents.com - Admin Panel - Manage Client Testimonials</title>
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
					<div align="center" style="width:100%" class="headtitle">Manage Client Testimonials</div>
					<div align="center" style="width:100%">&nbsp;</div>
					<div><?=$msgActionStatus?></div>
					<div id="add_image">
					<form name="frmAddBanner" action="" method="post" enctype="multipart/form-data" onsubmit="return val_addBanner();">
						<table width="90%" cellpadding="1" cellspacing="2">
							<tr>
								<td width="100%" colspan="2"><b>Add Client Testimonials</b></td>
							</tr>
							<tr>
								<td width="100%" colspan="2">&nbsp;</td>
							</tr>
							<tr>
								<td width="25%">
									<b>Name &nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
								</td>
								<td width="75%">
									<input type="text" name="txtName" id="txtName" value="" maxlength="50" size="50" />
								</td>
							</tr>
                            <tr>
								<td width="25%">
									<b>Company Name &nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
								</td>
								<td width="75%">
									<input type="text" name="txtcName" id="txtcName" value="" maxlength="50" size="50" />
								</td>
							</tr>
                            <tr>
								<td width="25%">
									<b>Title &nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
								</td>
								<td width="75%">
									<input type="text" name="txtTitle" id="txtTitle" value="" maxlength="50" size="50" />
								</td>
							</tr>
							<tr>
								<td width="25%">
									<b>Email &nbsp;&nbsp;:</b>
								</td>
								<td width="75%">
									<input type="text" name="txtemail" id="txtemail" size="50" />
								</td>
							</tr>
							<tr>
								<td width="25%">
									<b>Mobile &nbsp;:</b>
								</td>
								<td width="75%">
									<input type="text" name="txtMobile" id="txtMobile" value="" maxlength="100" size="50" />
								</td>
							</tr>
							<tr>
								<td width="25%">
									<b>Testimonials&nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
								</td>
								<td width="75%">
									<textarea name="txtComment" id="txtComment" rows=10 cols=50></textarea>
								</td>
							</tr>
                            <tr>
								<td width="25%">
									<b>Image &nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
								</td>
								<td width="75%">
									<input type="file" name="fileLogoImage" id="fileLogoImage" maxlength="100" title="Size should be 772 px X 200 px" size="50" />
								</td>
							</tr>
							<tr>
								<td width="25%">&nbsp;</td>
								<td width="55%">
									<input type="Submit" name="Submit" value="Submit" />
								</td>
							</tr>
							<tr>
								<td width="100%" colspan="2">&nbsp;</td>
							</tr>							
						</table>
						<script language="javascript" type="text/javascript">
							function val_addBanner()
							{
								var txtName = document.getElementById('txtName').value;
								var txtComment = document.getElementById('txtComment').value;
						
								
								if(txtName == '')
								{
									alert("Please Enter the Name");
									document.getElementById('txtName').focus();
									return false;
								}
							
								else if(txtComment == '')
								{
									alert("Please Enter Testimonials");
									document.getElementById('txtComment').focus();
									return false;								
								}
							
                             return true; 
}
						</script>
					</form>
					</div>
					<table width="100%" align="center" border="1">
   <tr bgcolor="#CCCCCC">
    <td>Sno</td>
    <td>Name</td>
    <td>Company Name</td>
    <td>Title</td>
    <td>Email</td>
    <td>Mobile</td>
    <td width="40%">Testimonials</td>
    <td>Posted Date</td>
    <td>Edit</td>
    <td>Delete</td>
    
  </tr>
  <?
  $totalFeedbacks=count($ResFeed);
              if($totalFeedbacks >0)
              {
		for($i=0;$i < $totalFeedbacks; $i++)
		{  
  ?>
  <tr>
    <td><?=$i+1;?></td>
    <td><?=$ResFeed[$i][vFName];?></td>
     <td><?=$ResFeed[$i][CompanyName];?></td>
      <td><?=$ResFeed[$i][Title];?></td>
    <td><?=$ResFeed[$i][vEmail];?></td>
    <td><?=$ResFeed[$i][vMobile];?></td>
    <td><?=$ResFeed[$i][tComment];?></td>
    <td><?=$ResFeed[$i][dPDate];?></td>
    <td align="center"><a href="manage_feedback_edit.php?editid=<?=$ResFeed[$i][Id];?>&Ax=Yes"><img src="images/edit.gif" border="0" /></a></td>
    <td align="center"><a href="manage_feedback.php?itdel=<?=$ResFeed[$i][Id];?>&Ax=Yes"><img src="images/delete.gif" border="0" /></a></td>
  </tr>
  <? } }else {?>
 <tr>
    <td>&nbsp;</td>
    <td colspan="7">No Testimonials Found</td>
  </tr>
<? } ?>
</table>

<!-------------------------------Manage Banner PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		  </tr>
		</table>
	</div>	
</body>
</html>