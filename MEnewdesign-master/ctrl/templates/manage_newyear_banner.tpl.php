<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <title>meraevents.com - Admin Panel - Manage <?php echo ucfirst($btype); ?> Banner</title>
        <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
            <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
                <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/pagi_sort.min.css.gz" rel="stylesheet" type="text/css" media="all" />	
                <script language="javascript" src="<?= _HTTP_CF_ROOT; ?>/ctrl/css/sortable.min.js.gz"></script>	
                <script language="javascript" src="<?= _HTTP_CF_ROOT; ?>/ctrl/css/sortpagi.min.js.gz"></script>	
                <script type="text/javascript" language="javascript" src="<?= _HTTP_CF_ROOT; ?>/ctrl/includes/javascripts/sortpagi.min.js.gz"></script>
                <link rel="stylesheet" type="text/css" media="all" href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/CalendarControl.min.css.gz" />
                <script type="text/javascript" language="javascript" src="<?= _HTTP_CF_ROOT; ?>/ctrl/includes/javascripts/CalendarControl.min.js.gz"></script>

                </head>	
                <body style="background-image: url(images/background.gif); background-repeat:repeat-x; margin-top: 0px; margin-left: 0px; margin-right:0px; padding:0px">
                    <?php include('templates/header.tpl.php'); ?>	

                    </div>
                    <?php
                    if (strlen(trim($uploadToS3Error)) > 0)
                        echo "<div style='color:red'><strong>$uploadToS3Error</strong></div>";
                    if (strlen(trim($OptimizeImageScriptError)) > 0)
                        echo "<div style='color:red'><strong>$OptimizeImageScriptError</strong></div>";
                    ?>
                    <table style="width:100%; height:495px;" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="width:150px; vertical-align:top; background-image:url(images/menugradient.jpg); background-repeat:repeat-x">
                                <?php include('templates/left.tpl.php'); ?>
                                <script language="javascript">
                                    document.getElementById('ans68').style.display = 'block';
                                </script>
                            </td>
                            <td style="vertical-align:top">
                                <div id="divMainPage" style="margin-left:10px; margin-right:5px;">
                                    <!-------------------------------Manage Banner PAGE STARTS HERE--------------------------------------------------------------->
                                    <div align="center" style="width:100%">&nbsp;</div>
                                    <div align="center" style="width:100%" class="headtitle">Manage <?php echo ucfirst($btype); ?> Banner</div>
                                    <div align="center" style="width:100%">&nbsp;</div>
                                    <div><?= $msgActionStatus ?></div>
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
                                                    <td width="25%">
                                                        <b>Banner Type<font color="#FF0000">*</font>&nbsp;:</b>
                                                    </td>
                                                    <td width="75%">
                                                        <select name="btype" id="btype">
                                                            <option value="newyear" <?php if ($btype == "newyear") {
                                    echo 'selected';
                                } ?>>New Year</option>
                                                            <option value="holi" <?php if ($btype == "holi") {
                                    echo 'selected';
                                } ?>>Holi</option>
                                                        </select>
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
                                                        <b>EventId &nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
                                                    </td>
                                                    <td width="75%">
                                                        <input type="text" name="EventId" id="EventId" value="" maxlength="50" size="50" />
                                                    </td>
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
                                                            <td colspan="2">
                                                                <input type="checkbox" name="Main" id="Main" value="1"/>Main 
                                                                <input type="checkbox" name="Banglore" id="Banglore" value="1"/>Banglore 
                                                                <input type="checkbox" name="Chennai" id="Chennai" value="1"/>Chennai 
                                                                <input type="checkbox" name="Delhi" id="Delhi" value="1"/>Delhi 
                                                                <input type="checkbox" name="Hyderabad" id="Hyderabad" value="1"/>Hyderabad 
                                                                <input type="checkbox" name="Mumbai" id="Mumbai" value="1"/>Mumbai 
                                                                <input type="checkbox" name="Pune" id="Pune" value="1"/>Pune 
                                                                <input type="checkbox" name="Kolkata" id="Kolkata" value="1"/>Kolkata 
                                                                <input type="checkbox" name="AllCities" id="AllCities" value="1"/>AllCities 
                                                                <input type="checkbox" name="OtherCities" id="OtherCities" value="1"/>OtherCities 
                                                                <input type="checkbox" name="Goa" id="Goa" value="1"/>Goa
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
                                                                var Title = document.getElementById('txtTitle').value;
                                                                var EventId = document.getElementById('EventId').value;
                                                                var strtdt = document.getElementById('txtSDt').value;
                                                                var enddt = document.getElementById('txtEDt').value;
                                                                var BannerImage = document.getElementById('fileBannerImage').value;
                                                                var URL = document.getElementById('txtURL').value;
                                                                var SeqNo = document.getElementById('txtSeqNo').value;
                                                                var re3digit = /^\d{3}$/ //regular expression defining a 3 digit number

                                                                if (Title == '')
                                                                {
                                                                    alert("Please Enter the Banner Title");
                                                                    document.getElementById('txtTitle').focus();
                                                                    return false;
                                                                }
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

                                                                else if (BannerImage == '')
                                                                {
                                                                    alert("Please Select Banner Image");
                                                                    document.getElementById('fileBannerImage').focus();
                                                                    return false;
                                                                }
                                                                else if (SeqNo == '')
                                                                {
                                                                    alert("Please Enter the Sequence Number");
                                                                    document.getElementById('txtSeqNo').focus();
                                                                    return false;
                                                                }
                                                                else if (isNaN(SeqNo)) {
                                                                    alert("Please Enter the Sequence Number");
                                                                    document.getElementById('txtSeqNo').focus();
                                                                    return false;
                                                                } else if (CategoryId == '') {
                                                                    alert("Please Select Category");
                                                                    document.getElementById('CategoryId').focus();
                                                                }
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
                                                                return true;
                                                            }
                                                        </script>
                                                        </form>
                                                        </div>
                                                        <table width="80%">
                                                            <form name="citysearch" id="citysearch" action="">
                                                                <tr><td width="8%">
                                                                        Select city
                                                                    </td>
                                                                    <td width="10%">

                                                                        <select name="searchCT" id="searchCT" >
                                                                            <option value="">Select City</option>
                                                                            <option value="AllCities" <? if($_REQUEST['searchCT']=="AllCities"){?> selected="selected" <? }?>  >AllCities</option>
                                                                            <option value="Banglore" <? if($_REQUEST['searchCT']=="Banglore"){?> selected="selected" <? }?>>Banglore</option>
                                                                            <option value="Chennai" <? if($_REQUEST['searchCT']=="Chennai"){?> selected="selected" <? }?>>Chennai</option>
                                                                            <option value="Delhi" <? if($_REQUEST['searchCT']=="Delhi"){?> selected="selected" <? }?>>Delhi</option>
                                                                            <option value="Hyderabad" <? if($_REQUEST['searchCT']=="Hyderabad"){?> selected="selected" <? }?>>Hyderabad</option>
                                                                            <option value="Mumbai" <? if($_REQUEST['searchCT']=="Mumbai"){?> selected="selected" <? }?>>Mumbai</option>
                                                                            <option value="Pune" <? if($_REQUEST['searchCT']=="Pune"){?> selected="selected" <? }?>>Pune</option>
                                                                            <option value="Kolkata" <? if($_REQUEST['searchCT']=="Kolkata"){?> selected="selected" <? }?>>Kolkata</option>
                                                                            <option value="OtherCities" <? if($_REQUEST['searchCT']=="OtherCities"){?> selected="selected" <? }?>>OtherCities</option>
                                                                            <option value="Goa" <? if($_REQUEST['searchCT']=="Goa"){?> selected="selected" <? }?>>Goa</option>

                                                                        </select>
                                                                        <input type="hidden" name="btype" value="<?php echo $btype; ?>" />

                                                                    </td> <td width="10%">
                                                                        Select Category
                                                                    </td>
                                                                    <td width="10%">


                                                                    </td><td colspan="2"><input type="submit" name="submit" value="Filter" /></td></tr>
                                                            </form>
                                                        </table>
                                                        <table width="90%" cellpadding="2" cellspacing="1">
                                                            <?php
                                                            $count = 0;

                                                            for ($i = 0; $i < count($BannerList); $i++) {
                                                                $count++;
                                                                ?>
                                                                <tr>
                                                                    <td class="tblcont1"><div align="left"><b>Sr. No.</b></div></td>
                                                                    <td class="helpBod">
                                                                        <div align="left"><?= $count . '.' ?></div></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="tblcont1"><div align="left"><b>Title</b></div></td>
                                                                    <td class="helpBod">
                                                                        <div align="left"><?= $BannerList[$i]['Title'] ?></div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="tblcont1"><div align="left"><b>Event Id</b></div></td>
                                                                    <td class="helpBod">
                                                                        <div align="left"><?= $BannerList[$i]['EventId'] ?></div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="tblcont1"><div align="left"><b>Dates</b></div></td>
                                                                    <td class="helpBod">
                                                                        <div align="left"> from <?= $BannerList[$i]['StartDt'] ?> to <?= $BannerList[$i]['EndDt'] ?></div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="tblcont1"><div align="left"><b>URL</b></div></td>
                                                                    <td class="helpBod">
                                                                        <div align="left"><?= $BannerList[$i]['URL'] ?></div>
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
                                                                            if($BannerList[$i]['Kolkata']==1){ echo "Kolkata," ;}
                                                                            if($BannerList[$i]['AllCities']==1){ echo "AllCities," ;}
                                                                            if($BannerList[$i]['OtherCities']==1){ echo "OtherCities," ;}
                                                                            if($BannerList[$i]['Goa']==1){ echo "Goa" ;}




                                                                            ?>

                                                                        </div>
                                                                    </td>
                                                                </tr>

                                                                <td class="tblcont1"><div align="left"><b>Image</b></div></td>
                                                                <td class="helpBod">
                                                                    <div align="left"><img src="<?= _HTTP_CDN_ROOT . $BannerList[$i]['FileName'] ?>" height="200" width="772"></img></div>
                                                                </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="tblcont1"><div align="left"><b>Sequence No. </b></div></td>
                                                            <td class="helpBod">
                                                                <div align="left"><?= $BannerList[$i]['SeqNo'] ?></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="tblcont1" valign="top"><div align="left"><b>Options</b></div></td>							
                                                            <td class="helpBod">
                                                                <div align="left">
    <?php echo "Current Status: ";
    if ($BannerList[$i]['Active'] == '1') {
        echo "Active";
        $newStatus = 0;
    } else {
        echo "InActive";
        $newStatus = 1;
    } ?>
                                                                    <a href="manage_newyear_banner.php?btype=<?php echo $btype; ?>&Id=<?= $BannerList[$i]['Id'] ?>&Edit=ChangeStatus&Active=<?= $newStatus ?>">Change Status</a>
                                                                    &nbsp;
                                                                    <a href="manage_newyear_banner_edit.php?btype=<?php echo $btype; ?>&Id=<?= $BannerList[$i]['Id'] ?>">Edit</a>
                                                                    &nbsp;
                                                                    <a href="manage_newyear_banner.php?btype=<?php echo $btype; ?>&delete=<?= $BannerList[$i]['Id'] ?>" onclick="return confirm('Are you sure to delete this image');">Delete</a>									
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