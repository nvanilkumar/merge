<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>meraevents.com - Admin Panel - Manage Banner</title>
	<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
	<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/pagi_sort.css" rel="stylesheet" type="text/css" media="all" />	
	<script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortable.js"></script>	
	<script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortpagi.js"></script>	
	<script type="text/javascript" language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/includes/javascripts/sortpagi.js"></script>
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.css" />
<script type="text/javascript" language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/js/public/jQuery.js"></script>
<script language="javascript" type="text/javascript">
    function getCountryCities (countryId, cityField) {
        if (countryId != '') {
            $.ajax({
                   url: "<?php echo _HTTP_SITE_ROOT; ?>/ctrl/ajax.php",
                   type: "POST",
                   data: "call=getCountyCities&countryId="+countryId,
                   success: function (msg) {
                       if (msg == "ERROR!") {
                            var dataM = "";
                            dataM += '<option value="All">All cities</option>';
                            dataM += '<option value="Other">Other cities</option>';
                             $("#"+cityField+"").html(dataM);
                            //alert("No Cities!");
                       }
                       else {
                           $("#"+cityField+"").html(msg);
                       }
                   }
               });
        }
    }
    //Validation of adding
    function val_addBanner()
    {  
        var Title = document.getElementById('txtTitle').value;
        var EventId = document.getElementById('EventId').value;
        var strtdt = document.getElementById('txtSDt').value;
        var enddt = document.getElementById('txtEDt').value;
        var BannerImage = document.getElementById('fileBannerImage').value;

        var CategoryId = $('#frmCategory').val();
        var CityId =$('#frmCT').val();
        
        var URL = document.getElementById('txtURL').value;
        var SeqNo = document.getElementById('txtSeqNo').value;
        var re3digit=/^\d{3}$/ //regular expression defining a 3 digit number

        if(Title == '') {
            alert("Please Enter the Banner Title");
            document.getElementById('txtTitle').focus();
            return false;
        }
        else if($.isNumeric(EventId) & EventId >= 0) {
            $.get('includes/ajaxSeoTags.php', {eventIDChk: 0, eventid: EventId}, function (data) {
                                        if (data == "error")
                                        {
                                            alert("Sorry, we did not find the Event ID or Event is deleted, Please Re-enter");
                                            document.getElementById('EventId').focus();
                    return false;

                }
        });}
        else if(strtdt == '') {
            alert('Please select Start Date');
            document.getElementById('txtSDt').focus();
            return false;
        }
        else if(enddt == '') {
            alert('Please select End Date');
            document.getElementById('txtEDt').focus();
            return false;
        }
        else if(strtdt != '' && enddt != '') {   
            var startdate=strtdt.split('/');
            var startdatecon=startdate[2] + '/' + startdate[1]+ '/' + startdate[0];

            var enddate=enddt.split('/');
            var enddatecon=enddate[2] + '/' + enddate[1]+ '/' + enddate[0];

            if(Date.parse(enddatecon) < Date.parse(startdatecon)) {
                alert('End Date must be greater then Start Date.');
                document.getElementById('txtEDt').focus();
                return false;
            }
        }
        if(URL == '') {
            alert("Please Enter the URL");
            document.getElementById('txtURL').focus();
            return false;								
        }else{
            var pattern = new RegExp("^(http|https)://", "i");
            if(!pattern.test(URL)){
                alert("Please Enter URL which starts with http:// or https://");
                document.getElementById('txtURL').focus();
                return false;
            }
        }
        if(BannerImage.length == 0) {
            alert("Please Select Banner Image");
            document.getElementById('fileBannerImage').focus();
            return false;								
        }
        else if(SeqNo == '') {
            alert("Please Enter the Sequence Number");
            document.getElementById('txtSeqNo').focus();
            return false;								
        }
        else if(isNaN(SeqNo)){
            alert("Please Enter the Sequence Number");
            document.getElementById('txtSeqNo').focus();
            return false;
        }      
        else if(CategoryId == ''){
            alert("Please Select Category");
            document.getElementById('frmCategory').focus();
        }        
        else if(CityId == ''){
            alert("Please Select city");
            document.getElementById('frmCT').focus();
        }
//        if(SeqNo<=0){
//            alert("Please Enter number greater than 0");
//            document.getElementById('txtSeqNo').focus();
//            return false;
//        }
          
        return true; 
    }
</script>
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
			<script language="javascript">
  	document.getElementById('ans12').style.display='block';
</script>
				<div id="divMainPage" style="margin-left:10px; margin-right:5px;">

        <div align="center" style="width:100%">&nbsp;</div>
        <div align="center" style="width:100%" class="headtitle">Manage Banner</div>
        <div align="center" style="width:100%">&nbsp;</div>
        <div><?php echo $msgActionStatus?></div>
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
                    <td width="15%"><b>Title &nbsp;<font color="#FF0000">*</font>&nbsp;:</b></td>
                    <td width="85%">
                        <input type="text" name="txtTitle" id="txtTitle" value="" maxlength="50" size="50" />
                    </td>
                </tr>
                <tr>
                    <td><b>EventId &nbsp;&nbsp;:</b></td>
                    <td>
                        <input type="text" name="EventId" id="EventId" value="" maxlength="50" size="50" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table width="75%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td width="20%" align="left" valign="middle"><strong>Start Date<font color="#FF0000">*</font></strong>: </td>
                                <td width="25%" align="left" valign="middle"><input type="text" name="txtSDt" id="txtSDt" value="<?php echo $SDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
                                <td width="14%" align="left" valign="middle"><strong>End Date<font color="#FF0000">*</font></strong>: </td>
                                <td align="left" valign="middle"><input type="text" name="txtEDt" id="txtEDt" value="<?php echo $EDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td><b>Image &nbsp;<font color="#FF0000">*</font>&nbsp;:</b></td>
                    <td>
                        <input type="file" name="fileBannerImage" id="fileBannerImage" maxlength="100" title="Size should be 772 px X 200 px" size="50" />
                    </td>
                </tr>
                <tr>
                    <td><b>URL &nbsp;<font color="#FF0000">*</font>&nbsp;:</b></td>
                    <td>
                        <input type="text" name="txtURL" id="txtURL" value=""  size="50" />
                    </td>
                </tr>
                <tr>
                    <td><b>Sequence No. &nbsp;<font color="#FF0000">*</font>&nbsp;:</b></td>
                    <td>
                        <input type="text" name="txtSeqNo" id="txtSeqNo" value="" maxlength="3" size="5" />
                    </td>
                </tr>
                <tr>
                    <td><b>Type &nbsp;<font color="#FF0000">*</font>&nbsp;:</b></td>
                    <td>
                        <select name="frmType" id="frmType">
                            <option value="1">Home Top</option>                            
                            <option value="2">Home Bottom</option>
                            <option value="3">New year</option>
                            <option value="4">Holi</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><b>Category &nbsp;<font color="#FF0000">*</font>&nbsp;:</b></td>
                    <td>
                        <select id="frmCategory[]" name="frmCategory[]" style="width:25%; height:85px" multiple >
                            <option value="All" <?php if ($_REQUEST['searchCA'] == "All") { ?> selected="selected" <?php } ?>>All Categories</option>
                            <?php if (isset($CatList) && !empty($CatList)) { 
                                foreach ($CatList as $category) {?>
                            <option value="<?php echo $category['id'];?>"><?php echo $category['name'];?></option>
                            <?php } 
                            }?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><b>Country &nbsp;<font color="#FF0000">*</font>&nbsp;:</b></td>
                    <td>
                        <select name="frmCountry" id="frmCountry" style="width:25%" onchange="getCountryCities(this.value, 'frmCT')">
                            <option value="">Select Country</option>
                            <?php foreach ($countryList as $country) { ?>
                            <option value="<?php echo $country['id']; ?>"><?php echo $country['name']; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>                
                <tr>
                    <td><b>City &nbsp;<font color="#FF0000">*</font>&nbsp;:</b></td>
                    <td>
                        <select name="frmCT[]" id="frmCT" style="width:25%; height:85px" multiple>                            
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><input type="Submit" name="Submit" value="Upload" /></td>
                </tr>
                <tr>
                        <td width="100%" colspan="2">&nbsp;</td>
                </tr>							
            </table>
        </form>
    </div>
    <form name="citysearch" id="citysearch">
        <table width="80%">
            <tr>
                <td width="13%"  align="left">Select country :  </td>
                <td width="10%"> 
                    <select name="searchCountry" id="searchCountry" onchange="getCountryCities(this.value, 'searchCT')">
                        <option value="">Select Country</option>
                        <?php foreach ($countryList as $country) { ?>
                        <option value="<?php echo $country['id']; ?>" <?php if ($_REQUEST['searchCountry'] == $country['id']) { ?> selected="selected" <?php } ?>><?php echo $country['name']; ?></option>
                        <?php } ?>
                     </select>
                </td>
                <td width="12%" align="right"> Select city :  </td>
                <td width="10%"> 
                    <select name="searchCT" id="searchCT">
                        <option value="">Select City</option>
                        <?php if (isset($cityList) && !empty($cityList)) { ?>
                            <option value="All" <?php if ($_REQUEST['searchCT'] == 'All') { ?> selected="selected" <?php } ?>>All cities</option>
                        <?php foreach ($cityList as $city) { ?>
                        <option value="<?php echo $city['id']; ?>" <?php if ($_REQUEST['searchCT'] == $city['id']) { ?> selected="selected" <?php } ?>><?php echo $city['name']; ?></option>
                        <?php } ?>
                        <option value="Other" <?php if ($_REQUEST['searchCT'] == 'Other') { ?> selected="selected" <?php } ?>>Other cities</option>
                        <?php
                        }?>
                     </select>
                </td>
                <td width="15%" align="right">Select Category :  </td>
                <td width="10%">
                    <select name="searchCA" id="searchCA" >
                        <option value="">Select Category</option>
                        <option value="All" <?php if ($_REQUEST['searchCA'] == "All") { ?> selected="selected" <?php } ?>>All Categories</option>
                        <?php if (isset($CatList) && !empty($CatList)) { 
                            foreach ($CatList as $category) {?>
                        <option value="<?php echo $category['id'];?>" <?php if ($_REQUEST['searchCA'] == $category['id']) { ?> selected="selected" <?php } ?>><?php echo $category['name'];?></option>
                        <?php } 
                        }?>
                    </select>
                </td>
                <td colspan="2">
                    <input type="submit" name="submit" value="Filter" />
                </td>
            </tr>                        
        </table>
    </form>
    <table width="90%" cellpadding="2" cellspacing="1">
        <?php 
        $count=0;
        for($i = 0; $i < count($BannerList); $i++) {
            $count++; 
        ?>
        <tr>
            <td class="tblcont1"><div align="left"><b>Sr. No.</b></div></td>
            <td class="helpBod">
                <div align="left"><?php echo $count.'.';?></div>
            </td>
        </tr>
        <tr>
            <td class="tblcont1"><div align="left"><b>Title</b></div></td>
            <td class="helpBod">
                    <div align="left"><?php echo $BannerList[$i]['title'];?></div>
            </td>
        </tr>
        <tr>
            <td class="tblcont1"><div align="left"><b>Event Id</b></div></td>
            <td class="helpBod">
                    <div align="left"><?php echo $BannerList[$i]['eventid'];?></div>
            </td>
            </tr>
            <tr>
                <td class="tblcont1"><div align="left"><b>Dates</b></div></td>
                <td class="helpBod">
                        <div align="left"> from <?php echo $BannerList[$i]['startdatetime'];?> to <?php echo $BannerList[$i]['enddatetime'];?></div>
                </td>
            </tr>
            <tr>
                <td class="tblcont1"><div align="left"><b>URL</b></div></td>
                <td class="helpBod">
                        <div align="left"><?php echo $BannerList[$i]['url'];?></div>
                </td>
            </tr>
            <tr>
                <td class="tblcont1"><div align="left"><b>Pages</b></div></td>
                <td class="helpBod">
                    <div align="left"><?php 
                        if($BannerList[$i]['type']==1){ $pagesString= "Home Top, " ;}
                        if($BannerList[$i]['type']==2){ $pagesString= "Home Bottom, " ;}
                        if($BannerList[$i]['type']==3){ $pagesString= "New year, " ;}                                                                 
                        if($BannerList[$i]['type']==4){ $pagesString= "Holi, " ;}
                        echo substr($pagesString,0,-2);
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="tblcont1"><div align="left"><b>Cities</b></div></td>
                <td class="helpBod">
                    <div align="left"><?php echo getCities($Global, $BannerList[$i]['id'], $BannerList[$i]['allcities'], $BannerList[$i]['othercities']);?></div>
                </td>
            </tr>
            <tr>
                <td class="tblcont1"><div align="left"><b>Categories</b></div></td>
                <td class="helpBod">
                    <div align="left"><?php echo getCategories($Global, $BannerList[$i]['id'], $BannerList[$i]['allcategories'] );?></div>
                </td>
            </tr>
            <!--<tr>
                 <td class="tblcont1"><div align="left"><b>Click Stats</b></div></td>
                 <td class="helpBod">
                         <div align="left"> ><?php //echo $BannerList[$i]['clickscount']; ?>(Clicks) - <?php //echo $BannerList[$i]['conversion']; ?>(Conversion)</div>
                 </td>
            </tr>-->
            <tr>
                <td class="tblcont1"><div align="left"><b>Image</b></div></td>
                <td class="helpBod">
                        <div align="left"><img src="<?php echo CONTENT_CLOUD_PATH.$BannerList[$i]['banner_path'];?>" height="200" width="772"></img></div>
                </td>
            </tr>
            <tr>
                <td class="tblcont1"><div align="left"><b>Sequence No. </b></div></td>
                <td class="helpBod">
                        <div align="left"><?php echo $BannerList[$i]['order'];?></div>
                </td>
            </tr>
            <tr>
                <td class="tblcont1" valign="top"><div align="left"><b>Options</b></div></td>							
                <td class="helpBod">
                        <div align="left">
                                <?php echo "Current Status: "; if($BannerList[$i]['status']=='1') { echo "Active"; $newStatus=0; } else { echo "InActive"; $newStatus=1;} ?>
                                <a href="manage_banner.php?Id=<?php echo $BannerList[$i]['id'];?>&Edit=ChangeStatus&Active=<?php echo $newStatus;?>">Change Status</a>
                                &nbsp;
                                <a href="manage_banner_edit.php?Id=<?php echo $BannerList[$i]['id'];?>">Edit</a>
                                &nbsp;
                                <a href="manage_banner.php?delete=<?php echo $BannerList[$i]['id'];?>" onclick="return confirm('Are you sure to delete this image');">Delete</a>									
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