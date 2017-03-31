<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <title>meraevents.com - Admin Panel - Manage Banner</title>
        <link href="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
            <link href="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
                <link href="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/pagi_sort.css" rel="stylesheet" type="text/css" media="all" />	
                <script language="javascript" src="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/sortable.js"></script>	
                <script language="javascript" src="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/sortpagi.js"></script>	
                <script type="text/javascript" language="javascript" src="<?php echo _HTTP_CF_ROOT; ?>/ctrl/includes/javascripts/sortpagi.js"></script>
                <link rel="stylesheet" type="text/css" media="all" href="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/CalendarControl.css" />
                <script type="text/javascript" language="javascript" src="<?php echo _HTTP_CF_ROOT; ?>/ctrl/includes/javascripts/CalendarControl.js"></script>
                <script type="text/javascript" language="javascript" src="<?php echo _HTTP_CF_ROOT; ?>/js/public/jQuery.js"></script>
                </head>	
                <body style="background-image: url(images/background.gif); background-repeat:repeat-x; margin-top: 0px; margin-left: 0px; margin-right:0px; padding:0px">
                    <?php include('templates/header.tpl.php'); ?>	
                    <?php
                    if (strlen(trim($uploadToS3Error)) > 0)
                        echo "<div style='color:red'><strong>$uploadToS3Error</strong></div>";
                    if (strlen(trim($OptimizeImageScriptError)) > 0)
                        echo "<div style='color:red'><strong>$OptimizeImageScriptError</strong></div>";
                    ?>
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
                                    <div align="center" style="width:100%" class="headtitle">Manage Banner - Edit Banner Information</div>
                                    <div align="center" style="width:100%">&nbsp;</div>
                                    <div align="center" style="width:100%"><?php echo $msgActionStatus; ?></div>
                                    <form name="frmEditBanner" action="" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="Id" value="<?php echo $BannerList[0]['id']; ?>" />
                                        <input type="hidden" name="Active" value="<?php echo $BannerList[0]['status']; ?>" />
                                        <table width="100%" cellpadding="1" cellspacing="2">
                                            <tr>
                                                <td width="100%" colspan="2">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td width="25%">
                                                    <b>Title &nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
                                                </td>
                                                <td width="75%">
                                                    <input type="text" name="txtTitle" id="txtTitle" value="<?php echo $BannerList[0]['title']; ?>"  size="50"  />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="25%">
                                                    <b>Event Id &nbsp;&nbsp;:</b>
                                                </td>
                                                <td width="75%">
                                                    <input type="text" name="EventId" id="EventId" value="<?php if(!empty($BannerList[0]['eventid'])){echo $BannerList[0]['eventid'];} ?>" maxlength="50" size="50"  />
                                                </td>
                                            </tr>
                                            <tr><td colspan="2"><table width="100%">
                                                        <tr>
                                                            <td width="28%" align="left" valign="middle"><strong>Start Date<font color="#FF0000">*</font></strong>:&nbsp;
                                                                <input type="text" name="txtSDt" id="txtSDt" value="<?php echo date('d/m/Y', strtotime($BannerList[0]['startdatetime'])); ?>" size="8" onfocus="showCalendarControl(this);" /></td>
                                                            <td width="72%" align="left" valign="middle"><strong>End Date<font color="#FF0000">*</font></strong>:&nbsp;
                                                                <input type="text" name="txtEDt" id="txtEDt" value="<?php echo date('d/m/Y', strtotime($BannerList[0]['enddatetime'])); ?>" size="8" onfocus="showCalendarControl(this);" /></td>

                                                        </tr>
                                                    </table></td></tr>
                                            <tr>
                                                <td width="25%">
                                                    <b>Image&nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
                                                </td>
                                                <td width="75%">
                                                    <img src="<?php echo CONTENT_CLOUD_PATH . $BannerList[0]['banner_path']; ?>" height="200" width="772"></img><br /><br />
                                                    <input type="hidden" name="imagefileid" value="<?php echo $BannerList[0]['imagefileid']; ?>" />
                                                    <input type="hidden" name="imageFilePath" value="<?php echo CONTENT_CLOUD_PATH . $BannerList[0]['banner_path']; ?>" />
                                                    <input type="file" name="fileBannerImage" id="fileBannerImage" maxlength="100" title="Size should be 772 px X 200 px" value="" size="50"  />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="25%">
                                                    <b>URL &nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
                                                </td>
                                                <td width="75%">
                                                    <input type="text" name="txtURL" id="txtURL" value="<?php echo $BannerList[0]['url']; ?>"  size="50"  />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="25%">
                                                    <b>Sequence No.&nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
                                                </td>
                                                <td width="75%">
                                                    <input type="text" name="txtSeqNo" id="txtSeqNo" value="<?php echo $BannerList[0]['order']; ?>" maxlength="3" size="5"  />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Type &nbsp;<font color="#FF0000">*</font>&nbsp;:</b></td>
                                                <td>
                                                    <input type="hidden" name="bannerType" value="<?php echo $BannerList[0]['type']; ?>" />
                                                    <select name="frmType" id="frmType">
                                                        <option value="1" <?php if ($BannerList[0]['type'] == "1") { ?>selected="selected"<?php } ?>>Home Top</option>                            
                                                        <option value="2" <?php if ($BannerList[0]['type'] == "2") { ?>selected="selected"<?php } ?>>Home Bottom</option>
                                                        <option value="3" <?php if ($BannerList[0]['type'] == "3") { ?>selected="selected"<?php } ?>>New year</option>
                                                        <option value="4" <?php if ($BannerList[0]['type'] == "4") { ?>selected="selected"<?php } ?>>Holi</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Category &nbsp;<font color="#FF0000">*</font>&nbsp;:</b></td>
                                                <td>
                                                    <select id="frmCategory[]" name="frmCategory[]" style="width:25%; height:85px" multiple >
                                                        <option value="All" <?php if ($allcategories) { ?> selected="selected" <?php } ?>>All Categories</option>
                                                        <?php if (isset($CatList) && !empty($CatList)) {
                                                            foreach ($CatList as $category) {
                                                                ?>
                                                                <option value="<?php echo $category['id']; ?>" <?php if (in_array($category['id'], $categories)) { ?>selected="selected" <?php } ?>>
                                                                <?php echo $category['name']; ?>
                                                                </option>
                                                            <?php }
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Country &nbsp;<font color="#FF0000">*</font>&nbsp;:</b></td>
                                                <td>
                                                    <select name="frmCountry" id="frmCountry" style="width:25%" onchange="getCountryCities(this.value, 'frmCT')">
                                                        <option value="">Select Country</option>
                                                        <?php foreach ($countryList as $country) { ?>
                                                            <option value="<?php echo $country['id']; ?>" <?php if ($countryId == $country['id']) { ?>  selected="selected" <?php } ?>><?php echo $country['name']; ?></option>
<?php } ?>
                                                    </select>
                                                </td>
                                            </tr>                
                                            <tr>
                                                <td><b>City &nbsp;<font color="#FF0000">*</font>&nbsp;:</b></td>
                                                <td>
                                                    <select name="frmCT[]" id="frmCT" style="width:25%; height:85px" multiple>
                                                        <option value="All"<?php if ($allcities) { ?>  selected="selected" <?php } ?>>All cities</option>
                                                        <?php
                                                        if (count($cityList) > 0) {
                                                            foreach ($cityList as $cityDd) {
                                                                ?>
                                                                <option value="<?php echo $cityDd['id']; ?>" <?php if (in_array($cityDd['id'], $cities)) { ?>  selected="selected" <?php } ?>>
                                                                <?php echo $cityDd['name']; ?>
                                                                </option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                        <option value="Other"<?php if ($othercoties) { ?> selected="selected" <?php } ?>>Other cities</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="25%">&nbsp;</td>
                                                <td width="55%">
                                                    <input type="Submit" name="Update" value="UpdateBanner" onclick="return val_editBanner();" />&nbsp;<input type="button" name="Cancel" value="Cancel" onclick="javascript:window.location = 'manage_banner.php?<?php echo $queryString; ?>'" />
                                                </td>
                                            </tr>
                                        </table>
                                        <script language="javascript" type="text/javascript">
                                            function val_editBanner()
                                            {
                                                var Title = document.getElementById('txtTitle').value;
                                                var EventId = document.getElementById('EventId').value;
                                                var strtdt = document.getElementById('txtSDt').value;
                                                var enddt = document.getElementById('txtEDt').value;
                                                var BannerImage = document.getElementById('fileBannerImage').value;
                                                var URL = document.getElementById('txtURL').value;
                                                var SeqNo = document.getElementById('txtSeqNo').value;
                                                //var re3digit=/^\d{1}$/ //regular expression defining a 3 digit number

                                                if (Title == '')
                                                {
                                                    alert("Please Enter the Banner Updated Title");
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
                                                else if (strtdt == '')
                                                {
                                                    alert('Please select Start Date');
                                                    document.getElementById('txtSDt').focus();
                                                    return false;
                                                }
                                                else if (enddt == '')
                                                {
                                                    alert('Please select End Date');

                                                    document.getElementById('txtEDt').focus();
                                                    return false;
                                                }
                                                else if (strtdt != '' && enddt != '')
                                                {
                                                    var startdate = strtdt.split('/');
                                                    var startdatecon = startdate[2] + '/' + startdate[1] + '/' + startdate[0];

                                                    var enddate = enddt.split('/');
                                                    var enddatecon = enddate[2] + '/' + enddate[1] + '/' + enddate[0];

                                                    if (Date.parse(enddatecon) < Date.parse(startdatecon))
                                                    {
                                                        alert('End Date must be greater then Start Date.');
                                                        document.getElementById('txtEDt').focus();
                                                        return false;
                                                    }
                                                }
                                                else if (SeqNo == '')
                                                {
                                                    alert("Please Enter the Sequence Number");
                                                    document.getElementById('txtSeqNo').focus();
                                                    return false;
                                                }
                                                else if (isNaN(SeqNo))
                                                {
                                                    alert("Please Enter the Sequence Number");
                                                    document.getElementById('txtSeqNo').focus();
                                                    return false;
                                                }                                                
//                                                if(SeqNo<=0){
//                                                    alert("Please Enter number greater than 0");
//                                                    document.getElementById('txtSeqNo').focus();
//                                                    return false;
//                                                }
                                                if (URL == '') {
                                                    alert("Please Enter the URL");
                                                    document.getElementById('txtURL').focus();
                                                    return false;
                                                } else {
                                                    var pattern = new RegExp("^(http|https)://", "i");
                                                    if (!pattern.test(URL)) {
                                                        alert("Please Enter URL which starts with http:// or https://");
                                                        document.getElementById('txtURL').focus();
                                                        return false;
                                                    }
                                                }
                                            }

                                            function getCountryCities(countryId, cityField) {
                                                if (countryId != '') {
                                                    $.ajax({
                                                        url: "<?php echo _HTTP_SITE_ROOT; ?>/ctrl/ajax.php",
                                                        type: "POST",
                                                        data: "call=getCountyCities&countryId=" + countryId,
                                                        success: function (msg) {
                                                            if (msg == "ERROR!") {
                                                                alert("No Cities!");
                                                            }
                                                            else {
                                                                // alert(cityField);
                                                                $("#" + cityField + "").html(msg);
                                                            }
                                                        }
                                                    });
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
                    <script language="javascript">
                        document.getElementById('ans12').style.display = 'block';
                    </script>
                </body>
                </html>