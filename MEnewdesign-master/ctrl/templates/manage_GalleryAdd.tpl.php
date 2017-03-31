<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents - Admin Panel - Manage Gallery</title>
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
<!-------------------------------Manage PopEvents PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans11').style.display='block';
</script>
					<div align="center" style="width:100%">&nbsp;</div>
					<div align="center" style="width:100%" class="headtitle">Manage Gallery</div>
					<div align="center" style="width:100%">&nbsp;</div>
					<div><?=$msgActionStatus?></div>
					<div id="add_image">
					<form name="frmAddGallery" action="" method="post" enctype="multipart/form-data" onsubmit="return val_addGallery();">
						<table width="90%" cellpadding="1" cellspacing="2">
							<tr>
								<td width="100%" colspan="2"><b>Add Gallery </b></td>
							</tr>
							<tr>
								<td width="100%" colspan="2">&nbsp;</td>
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
									<b>Image &nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
								</td>
								<td width="75%">
									<input type="file" name="fileGalleryAdd" id="fileGalleryAdd" maxlength="100" title="Size should be 772 px X 200 px" size="50" />
								</td>
							</tr>
							<!--<tr>
								<td width="25%">
									<b>URL &nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
								</td>
								<td width="75%">
									<input type="text" name="txtURL" id="txtURL" value="" maxlength="100" size="50" />
								</td>
							</tr>        -->
							<!--<tr>
								<td width="25%">
									<b>Sequence No. &nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
								</td>
								<td width="75%">
									<input type="text" name="txtSeqNo" id="txtSeqNo" value="" maxlength="3" size="5" />
								</td>
							</tr>           -->
							<tr>
								<td width="25%">&nbsp;</td>
								<td width="55%">
									<input type="Submit" name="Submit" value="Upload" />
								</td>
							</tr>
							<tr>
								<td width="100%" colspan="2">&nbsp;</td>
							</tr>							
						</table>
						<script language="javascript" type="text/javascript">
							function val_addGallery()
							{
								var Title = document.getElementById('txtTitle').value;
								var GalleryImage = document.getElementById('fileGalleryImage').value;
								/*var URL = document.getElementById('txtURL').value;
								var SeqNo = document.getElementById('txtSeqNo').value;  */
								
								if(Title == '')
								{
									alert("Please Enter the Image Title");
									document.getElementById('txtTitle').focus();
									return false;
								}
								else if(GalleryImage == '')
								{
									alert("Please Select gallery Image");
									document.getElementById('fileGalleryAdd').focus();
									return false;								
								}
								return true;
							}
						</script>
					</form>
					</div>
					<table width="90%" cellpadding="2" cellspacing="1">
						<?php 
						$count=0; 
						
						for($i = 0; $i < count($GalleryImagesList); $i++)
						{ 
							$count++; 
						?>
						<tr>
							<td class="tblcont1"><div align="left"><b>Sr. No.</b></div></td>
							<td class="helpBod">
							<div align="left"><?=$count.'.'?></div></td>
						</tr>
						<tr>
							<td class="tblcont1"><div align="left"><b>Title</b></div></td>
							<td class="helpBod">
								<div align="left"><?=$GalleryImagesList[$i]['Title']?></div>
							</td>
						</tr>
					
						<tr>
							<td class="tblcont1"><div align="left"><b>Image</b></div></td>
							<td class="helpBod">
								<div align="left"><img src="..<?=$GalleryImagesList[$i]['FileName']?>" /></div>
							</td>
						</tr>
					
						<tr>
							<td class="tblcont1" valign="top"><div align="left"><b>Options</b></div></td>							
							<td class="helpBod">
								<div align="left">
									<?php echo "Current Status: "; if($GalleryImagesList[$i]['Active']=='1') { echo "Active"; $newStatus=0; } else { echo "InActive"; $newStatus=1;} ?>
									<a href="manage_GalleryAdd.php?Id=<?=$GalleryImagesList[$i]['Id']?>&Edit=ChangeStatus&Active=<?=$newStatus?>">Change Status</a>
									&nbsp; 
									<a href="manage_GalleryAdd_edit.php?Id=<?=$GalleryImagesList[$i]['Id']?>">Edit</a>
									&nbsp;
									<a href="manage_GalleryAdd.php?delete=<?=$GalleryImagesList[$i]['Id']?>" onclick="return confirm('Are you sure to delete this image');">Delete</a>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="20px">&nbsp;</td>
						</tr>
						<?php 
						} 
						?>
					</table>
<!-------------------------------Manage Popular Events PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		  </tr>
		</table>
	</div>	
</body>
</html>