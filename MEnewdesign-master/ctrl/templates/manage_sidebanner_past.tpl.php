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
   <?php 
    if(strlen(trim($uploadToS3Error))>0)
    echo "<div style='color:red'><strong>$uploadToS3Error</strong></div>";
    if(strlen(trim($OptimizeImageScriptError))>0)
    echo "<div style='color:red'><strong>$OptimizeImageScriptError</strong></div>";
    ?>
	<table style="width:100%; height:495px;" cellpadding="0" cellspacing="0">
		<tr>
			<td style="width:150px; vertical-align:top; background-image:url(images/menugradient.jpg); background-repeat:repeat-x">
				<?php include('templates/left.tpl.php'); ?>
			</td>
			<td style="vertical-align:top">
				<div id="divMainPage" style="margin-left:10px; margin-right:5px;">
<!-------------------------------Manage Banner PAGE STARTS HERE--------------------------------------------------------------->
					<div align="center" style="width:100%">&nbsp;</div>
					<div align="center" style="width:100%" class="headtitle">Manage Side Banner</div>
					<div align="center" style="width:100%">&nbsp;</div>
					<div><?=$msgActionStatus?></div>
					<div id="add_image">
					<form name="frmAddBanner" action="" method="post" enctype="multipart/form-data" onsubmit="return val_addBanner();">
						<table width="90%" cellpadding="1" cellspacing="2">
							<tr>
								<td width="100%" colspan="2"><b>Add Banner</b></td>
							</tr>
							<tr>
								<td width="100%" colspan="2">&nbsp;</td>
							</tr>
							
                              <tr>
	  <td width="35%" align="left" valign="middle"><strong>Start Date</strong>:&nbsp;<input type="text" name="txtSDt" id="txtSDt" value="<?php echo $SDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	  <td width="35%" align="left" valign="middle"><strong>End Date</strong>:&nbsp;<input type="text" name="txtEDt" id="txtEDt" value="<?php echo $EDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	  
	<tr>
							<tr>
								<td width="25%">
									<b>Image &nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
								</td>
								<td width="75%">
									<input type="file" name="fileBannerImage" id="fileBannerImage" maxlength="100" title="Size should be 772 px X 200 px" size="50" />
								</td>
							</tr>
							<tr>
								<td width="25%">
									<b>URL &nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
								</td>
								<td width="75%">
									<input type="text" name="txtURL" id="txtURL" value=""  size="50" />
								</td>
							</tr>
							<tr>
								<td width="25%">
									<b>Sequence No. &nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
								</td>
								<td width="75%">
									<input type="text" name="txtSeqNo" id="txtSeqNo" value="" maxlength="3" size="5" />
								</td>
							</tr>
                            
                            <tr>
								<td width="25%">
									<b>Category. &nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
								</td>
								<td width="75%">
                                <select name="CategoryId" id="CategoryId">
                                <option value="">Select Category</option>
                                 <?
                                for($i = 0; $i < count($CatList); $i++)
						{ ?>
									<option value="<?=$CatList[$i]['Id'];?>"><?=$CatList[$i]['CatName'];?></option>
                                    
                                    <? }?>
                                    </select>
								</td>
							</tr>

                            <tr>
								<td colspan="2">
								 <input type="checkbox" name="Main" id="Main" value="1"/>Main <input type="checkbox" name="Banglore" id="Banglore" value="1"/>Banglore <input type="checkbox" name="Chennai" id="Chennai" value="1"/>Chennai <input type="checkbox" name="Delhi" id="Delhi" value="1"/>Delhi <input type="checkbox" name="Hyderabad" id="Hyderabad" value="1"/>Hyderabad <input type="checkbox" name="Mumbai" id="Mumbai" value="1"/>Mumbai <input type="checkbox" name="Pune" id="Pune" value="1"/>Pune <input type="checkbox" name="Kolkata" id="Kolkata" value="1"/>Kolkata 
                                  <input type="checkbox" name="Kolkata" id="Kolkata" value="1"/>Kolkata 
                                 <input type="checkbox" name="Goa" id="Goa" value="1"/>Goa 
                         <input type="checkbox" name="Ahmedabad" id="Ahmedabad" value="1"/>Ahmedabad 
                                 <input type="checkbox" name="AllCities" id="AllCities" value="1"/>AllCities <input type="checkbox" name="OtherCities" id="OtherCities" value="1"/>OtherCities
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
						<script language="javascript" type="text/javascript">
							function val_addBanner()
							{
								
														var strtdt = document.getElementById('txtSDt').value;
		                                                var enddt = document.getElementById('txtEDt').value;
								var BannerImage = document.getElementById('fileBannerImage').value;
								var URL = document.getElementById('txtURL').value;
								var SeqNo = document.getElementById('txtSeqNo').value;
								var re3digit=/^\d{3}$/ //regular expression defining a 3 digit number
								
								 if(strtdt == '')
		{
			alert('Please select Start Date');
			document.getElementById('txtSDt').focus();
			return false;
		}
		else if(enddt == '')
		{
			alert('Please select End Date');
			document.getElementById('txtEDt').focus();
			return false;
		}
		else if(strtdt != '' && enddt != '')
		{   
			var startdate=strtdt.split('/');
			var startdatecon=startdate[2] + '/' + startdate[1]+ '/' + startdate[0];
			
			var enddate=enddt.split('/');
			var enddatecon=enddate[2] + '/' + enddate[1]+ '/' + enddate[0];
			
			if(Date.parse(enddatecon) < Date.parse(startdatecon))
			{
				alert('End Date must be greater then Start Date.');
				document.getElementById('txtEDt').focus();
				return false;
			}
		}

								else if(BannerImage == '')
								{
									alert("Please Select Banner Image");
									document.getElementById('fileBannerImage').focus();
									return false;								
								}
								else if(URL == '')
								{
									alert("Please Enter the URL");
									document.getElementById('txtURL').focus();
									return false;								
								}
								else if(SeqNo == '')
								{
									alert("Please Enter the Sequence Number");
									document.getElementById('txtSeqNo').focus();
									return false;								
								}
								     else if(isNaN(SeqNo)){
                                    alert("Please Enter the Sequence Number");
                                    document.getElementById('txtSeqNo').focus();
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
							<td class="tblcont1"><div align="left"><b>Dates</b></div></td>
							<td class="helpBod">
								<div align="left"> from <?=$BannerList[$i]['StartDt']?> to <?=$BannerList[$i]['EndDt']?></div>
							</td>
						</tr>
						<tr>
							<td class="tblcont1"><div align="left"><b>URL</b></div></td>
							<td class="helpBod">
								<div align="left"><?=$BannerList[$i]['URL']?></div>
							</td>
						</tr>
                        <tr>
							<td class="tblcont1"><div align="left"><b>Pages</b></div></td>
							<td class="helpBod">
								<div align="left"><? if($BannerList[$i]['Main']==1){ echo "Main," ;}
								 if($BannerList[$i]['Banglore']==1){ echo "Banglore," ;}
								  if($BannerList[$i]['Chennai']==1){ echo "Chennai," ;}
								   if($BannerList[$i]['Delhi']==1){ echo "Delhi," ;}
								    if($BannerList[$i]['Hyderabad']==1){ echo "Hyderabad," ;}
									 if($BannerList[$i]['Mumbai']==1){ echo "Mumbai," ;}
									  if($BannerList[$i]['Pune']==1){ echo "Pune," ;}
									  if($BannerList[$i]['Kolkata']==1){ echo "Kolkata" ;}
                                      if($BannerList[$i]['Goa']==1){ echo "Goa," ;}
									  if($BannerList[$i]['Ahmedabad']==1){ echo "Ahmedabad," ;}
									  if($BannerList[$i]['Jaipur']==1){ echo "Jaipur," ;}
									  if($BannerList[$i]['AllCities']==1){ echo "AllCities" ;}
									  if($BannerList[$i]['OtherCities']==1){ echo "OtherCities" ;}
								
								
								?></div>
							</td>
						</tr>
                        <tr>
                        <td class="tblcont1"><div align="left"><b>Category</b></div></td>
							<td class="helpBod">
								<div align="left"><?=$Global->GetSingleFieldValue(" select CatName from category where Id=".$BannerList[$i]['CategoryId']);?> </div>
							</td>
						</tr>
						<tr>
							<td class="tblcont1"><div align="left"><b>Image</b></div></td>
							<td class="helpBod">  
								<div align="left"><img src="<?=_HTTP_CDN_ROOT.$BannerList[$i]['FileName']?>" ></img></div>
							</td>
						</tr>
						<tr>
							<td class="tblcont1"><div align="left"><b>Sequence No. </b></div></td>
							<td class="helpBod">
								<div align="left"><?=$BannerList[$i]['SeqNo']?></div>
							</td>
						</tr>
                        <tr>
							<td class="tblcont1"><div align="left"><b>Click Stats</b></div></td>
							<td class="helpBod">
								<div align="left"><?=$BannerList[$i]['clickscount']?> </div>
							</td>
						</tr>
						<tr>
							<td class="tblcont1" valign="top"><div align="left"><b>Options</b></div></td>							
							<td class="helpBod">
								<div align="left">
									<?php echo "Current Status: "; if($BannerList[$i]['Active']=='1') { echo "Active"; $newStatus=0; } else { echo "InActive"; $newStatus=1;} ?>
									<a href="manage_sidebanner.php?Id=<?=$BannerList[$i]['Id']?>&Edit=ChangeStatus&Active=<?=$newStatus?>&q=past">Change Status</a>
									&nbsp;
									<a href="manage_sidebanner_edit.php?Id=<?=$BannerList[$i]['Id']?>&q=past">Edit</a>
									&nbsp;
									<a href="manage_sidebanner.php?delete=<?=$BannerList[$i]['Id']?>&q=past" onclick="return confirm('Are you sure to delete this image');">Delete</a>									
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
<script language="javascript">
  	document.getElementById('ans17').style.display='block';
</script>
</body>
</html>