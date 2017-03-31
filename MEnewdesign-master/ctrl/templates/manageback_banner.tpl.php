<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>meraevents.com - Admin Panel - Manage Banner</title>
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/pagi_sort.min.css.gz" rel="stylesheet" type="text/css" media="all" />	
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>	
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script>	
	<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/sortpagi.min.js.gz"></script>
    <link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.min.css.gz" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.min.js.gz"></script>

</head>	
<body style="background-image: url(images/background.gif); background-repeat:repeat-x; margin-top: 0px; margin-left: 0px; margin-right:0px; padding:0px">
	<?php include('templates/header.tpl.php'); ?>				
	</div>
	<table width="147%" cellpadding="0" cellspacing="0" style="width:100%; height:495px;">
  <tr>
			<td width="150" style="width:150px; vertical-align:top; background-image:url(images/menugradient.jpg); background-repeat:repeat-x">
				<?php include('templates/left.tpl.php'); ?>			</td>
<td width="1325" style="vertical-align:top">
	  <div id="divMainPage" style="margin-left:10px; margin-right:5px;">
<!-------------------------------Manage Banner PAGE STARTS HERE--------------------------------------------------------------->
					<div align="center" style="width:100%">&nbsp;</div>
					<div align="center" style="width:100%" class="headtitle">Manage Back Ground Banner</div>
					<div align="center" style="width:100%">&nbsp;</div>
					<div><?=$msgActionStatus?></div>
					<div id="add_image">
					<form name="frmAddBanner" action="" method="post" enctype="multipart/form-data" >
						<table width="90%" cellpadding="1" cellspacing="2">
							<tr>
								<td width="100%" colspan="2"><b>Add Banner</b></td>
							</tr>
							<tr>
								<td width="100%" colspan="2">&nbsp;</td>
							</tr>
							<tr>
								<td width="25%">
									<b>Category &nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
								</td>
								<td width="75%">
									<select id="CategoryId" name="CategoryId" style="width:180px;">
                                    <option value="">Select</option>
                                    <? for($i=0;$i<count($categoryList);$i++)
									{?>
                                    <option value="<?=$categoryList[$i][Id];?>"><?=$categoryList[$i][Category];?></option>
                                    <? }?>
                                  </select>
								</td>
							</tr>
                                              
<tr>
								<td width="25%">
									<b>Image &nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
								</td>
								<td width="75%">
									<input type="file" name="fileBannerImage" id="fileBannerImage" maxlength="100" title="Size should be 772 px X 200 px" size="50" />
								</td>
							</tr>
							


                          
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
						
					</form>
					</div>
					<table width="90%" cellpadding="2" cellspacing="1">
						<?php 
						$count=0; 
						
						for($i = 0; $i < count($BannerList); $i++)
						{ 
							$count++; 
						?>
						<tr>
							<td class="tblcont1"><div align="left"><b>Sr. No.</b></div></td>
							<td class="helpBod">
							<div align="left"><?=$count.'.'?></div></td>
						</tr>
					
                        
<tr>
							<td class="tblcont1"><div align="left"><b>Image</b></div></td>
							<td class="helpBod">
								<div align="left"><img src="<?='http://content.meraevents.com/background/'.$BannerList[$i]['Image']?>" height="200" width="772"></img></div>
							</td>
						</tr>
						<tr>
							<td class="tblcont1"><div align="left"><b>Category. </b></div></td>
							<td class="helpBod">
								<div align="left"><?=$Global->GetSingleFieldValue("select Category from categories where Id=".$BannerList[$i]['CategoryId']); ?></div>
							</td>
						</tr>
						<tr>
							<td class="tblcont1" valign="top"><div align="left"><b>Options</b></div></td>							
							<td class="helpBod">
								<div align="left">
									<a href="manageback_banner.php?delete=<?=$BannerList[$i]['Id']?>" onclick="return confirm('Are you sure to delete this image');">Delete</a>									
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
<!-------------------------------Manage Banner PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
	  </tr>
		</table>
</div>	
</body>
</html>