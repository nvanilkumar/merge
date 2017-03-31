<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents - Admin Panel - Manage Popular Events</title>
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
					<div align="center" style="width:100%">&nbsp;</div>
					<div align="center" style="width:100%" class="headtitle">Manage Popular Events - Edit Popular Events Information</div>
					<div align="center" style="width:100%">&nbsp;</div>
					<div><?=$msgActionStatus?></div>
					<form name="frmEditPopEvents" action="" method="post" enctype="multipart/form-data">
						<input type="hidden" name="Id" value="<?=$PopEventsList[0]['Id']?>" />
						<input type="hidden" name="Active" value="<?=$PopEventsList[0]['Active']?>" />
						<table width="100%" cellpadding="1" cellspacing="2">
							<tr>
								<td width="100%" colspan="2">&nbsp;</td>
							</tr>
							<tr>
								<td width="25%">
									<b>Title &nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
								</td>
								<td width="75%">
									<input type="text" name="txtTitle" id="txtTitle" value="<?=$PopEventsList[0]['Title']?>" maxlength="50" size="50"  />
								</td>
							</tr>
							<tr>
								<td width="25%">
									<b>Image &nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
								</td>
								<td width="75%">
									<img src="<?=_HTTP_SITE_ROOT.$PopEventsList[0]['FileName']?>" /><br /><br />
									<input type="file" name="filePopEventsImage" id="filePopEventsImage" maxlength="100" title="Size should be 772 px X 200 px" value="" size="50"  />
								</td>
							</tr>
							    <tr>
                                <td width="25%">
                                    <b>URL &nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
                                </td>
                                <td width="75%">
                                    <input type="text" name="txtURL" id="txtURL" value="<?=$PopEventsList[0]['URL'];?>" maxlength="100" size="50" />
                                </td>
                            </tr>       
                            <tr>
                                <td width="25%"><b>Organizer &nbsp;<font color="#FF0000">*</font>&nbsp;:</b></td>
                                <td width="75%">
                                <select tabindex="86" name="SerEventName" id="SerEventName" class="adTextFieldd">
                <option value="0">By Organizer Name</option>    
                <?php 
                $TotalOrgNames1=count($OrgNames1);
               for($i=0;$i<$TotalOrgNames1;$i++)
                {
                ?>
                <option value="<?php echo $OrgNames1[$i]['Id'];?>" <?php if($OrgNames1[$i]['Id'] == $PopEventsList[0]['UserId']) { ?> selected="selected" <?php } ?>><?php echo $OrgNames1[$i]['orgDispName']; ?></option>
                <?php 
                } 
                ?>     
            
            </select>
                            </td>
                        </tr>
							<tr>
								<td width="25%">
									<b>Sequence No.&nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
								</td>
								<td width="75%">
									<input type="text" name="txtSeqNo" id="txtSeqNo" value="<?=$PopEventsList[0]['SeqNo']?>" maxlength="3" size="5"  />
								</td>
							</tr>
							<tr>
								<td width="25%">&nbsp;</td>
								<td width="55%">
									<input type="Submit" name="Update" value="UpdatePopEvents" onclick="return val_editPopEvents();" />&nbsp;<input type="button" name="Cancel" value="Cancel" onclick="javascript:window.location='manage_PopEvents.php'" />
								</td>
							</tr>
						</table>
						<script language="javascript" type="text/javascript">
							function val_editPopEvents()
							{
								var Title = document.getElementById('txtTitle').value;
								var PopEventsImage = document.getElementById('filePopEventsImage').value;
								var URL = document.getElementById('txtURL').value;
								var SeqNo = document.getElementById('txtSeqNo').value;
								//var re3digit=/^\d{1}$/ //regular expression defining a 3 digit number
								
								if(Title == '')
								{
									alert("Please Enter the Title");
									document.getElementById('txtTitle').focus();
									return false;
								}
								//else if(PopEventsImage == '')
								//{
								//	alert("Please Select PopEvents Image");
								//	document.getElementById('filePopEventsImage').focus();
								//	return false;								
								//}
								/*else if(URL == '')
								{
									alert("Please Enter the URL");
									document.getElementById('txtURL').focus();
									return false;								
								}  */
								else if(SeqNo == '')
								{
									alert("Please Enter the Sequence Number");
									document.getElementById('txtSeqNo').focus();
									return false;								
								}
								else if(isNaN(SeqNo))
								{
									alert("Please Enter the Sequence Number");
									document.getElementById('txtSeqNo').focus();
									return false;								
								}
							}
						</script>
					</form>
<!-------------------------------Manage PopEvents PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		  </tr>
		</table>
	</div>	
</body>
</html>