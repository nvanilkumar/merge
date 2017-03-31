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
					<div align="center" style="width:100%" class="headtitle">Manage Banner - Edit Side Banner Information</div>
					<div align="center" style="width:100%">&nbsp;</div>
					<div><?=$msgActionStatus?></div>
					<form name="frmEditBanner" action="" method="post" enctype="multipart/form-data">
						<input type="hidden" name="Id" value="<?=$BannerList[0]['Id']?>" />
						<input type="hidden" name="Active" value="<?=$BannerList[0]['Active']?>" />
						<table width="100%" cellpadding="1" cellspacing="2">
							<tr>
								<td width="100%" colspan="2">&nbsp;</td>
							</tr>
							
                            <tr><td colspan="2"><table width="100%">
 <tr>
	  <td width="28%" align="left" valign="middle"><strong>Start Date</strong>:&nbsp;
	    <input type="text" name="txtSDt" id="txtSDt" value="<?php echo date('d/m/Y',strtotime($BannerList[0]['StartDt'])); ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	  <td width="72%" align="left" valign="middle"><strong>End Date</strong>:&nbsp;
	    <input type="text" name="txtEDt" id="txtEDt" value="<?php echo date('d/m/Y',strtotime($BannerList[0]['EndDt'])); ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	  
	</tr>
    </table></td></tr>
							<tr>
								<td width="25%">
									<b>Image &nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
								</td>
								<td width="75%">
									<img src="<?=_HTTP_CDN_ROOT.$BannerList[0]['FileName']?>" ></img><br /><br />
									<input type="file" name="fileBannerImage" id="fileBannerImage" maxlength="100" title="Size should be 772 px X 200 px" value="" size="50"  />
								</td>
							</tr>
							<tr>
								<td width="25%">
									<b>URL &nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
								</td>
								<td width="75%">
									<input type="text" name="txtURL" id="txtURL" value="<?=$BannerList[0]['URL']?>"  size="50"  />
								</td>
							</tr>
							<tr>
								<td width="25%">
									<b>Sequence No.&nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
								</td>
								<td width="75%">
									<input type="text" name="txtSeqNo" id="txtSeqNo" value="<?=$BannerList[0]['SeqNo']?>" maxlength="3" size="5"  />
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
								 <input type="checkbox" <? if($BannerList[0]['Main']==1){?> checked="checked" <? }?> name="Main" id="Main" value="1"/>Main <input type="checkbox"  <? if($BannerList[0]['Banglore']==1){?> checked="checked" <? }?> name="Banglore" id="Banglore" value="1"/>Banglore <input type="checkbox"  <? if($BannerList[0]['Chennai']==1){?> checked="checked" <? }?> name="Chennai" id="Chennai" value="1"/>Chennai <input type="checkbox"  <? if($BannerList[0]['Delhi']==1){?> checked="checked" <? }?> name="Delhi" id="Delhi" value="1"/>Delhi <input type="checkbox"  <? if($BannerList[0]['Hyderabad']==1){?> checked="checked" <? }?> name="Hyderabad" id="Hyderabad" value="1"/>Hyderabad <input type="checkbox"  <? if($BannerList[0]['Mumbai']==1){?> checked="checked" <? }?> name="Mumbai" id="Mumbai" value="1"/>Mumbai <input type="checkbox"  <? if($BannerList[0]['Pune']==1){?> checked="checked" <? }?> name="Pune" id="Pune" value="1"/>Pune 
                                 <input type="checkbox"  <? if($BannerList[0]['Kolkata']==1){?> checked="checked" <? }?> name="Kolkata" id="Kolkata" value="1"/>Kolkata 
                                 <input type="checkbox"  <? if($BannerList[0]['Goa']==1){?> checked="checked" <? }?> name="Goa" id="Goa" value="1"/>Goa 
                                 <input type="checkbox"  <? if($BannerList[0]['Ahmedabad']==1){?> checked="checked" <? }?> name="Ahmedabad" id="Ahmedabad" value="1"/>Ahmedabad 
                                 <input type="checkbox"  <? if($BannerList[0]['Jaipur']==1){?> checked="checked" <? }?> name="Jaipur" id="Jaipur" value="1"/>Jaipur 
                                 <input type="checkbox" <? if($BannerList[0]['AllCities']==1){?> checked="checked" <? }?> name="AllCities" id="AllCities" value="1"/>AllCities <input type="checkbox" <? if($BannerList[0]['OtherCities']==1){?> checked="checked" <? }?> name="OtherCities" id="OtherCities" value="1"/>OtherCities
								</td>
								
							</tr>
							<tr>
								<td width="25%">&nbsp;</td>
								<td width="55%">
									<input type="Submit" name="Update" value="UpdateBanner" onclick="return val_editBanner();" />&nbsp;<input type="button" name="Cancel" value="Cancel" onclick="javascript:window.location='manage_banner.php?<?php echo $queryString;?>'" />
								</td>
							</tr>
						</table>
						<script language="javascript" type="text/javascript">
							function val_editBanner()
							{
								
								var strtdt = document.getElementById('txtSDt').value;
		                        var enddt = document.getElementById('txtEDt').value;
								var BannerImage = document.getElementById('fileBannerImage').value;
								var URL = document.getElementById('txtURL').value;
								var SeqNo = document.getElementById('txtSeqNo').value;
								//var re3digit=/^\d{1}$/ //regular expression defining a 3 digit number
								
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
								else if(isNaN(SeqNo))
								{
									alert("Please Enter the Sequence Number");
									document.getElementById('txtSeqNo').focus();
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