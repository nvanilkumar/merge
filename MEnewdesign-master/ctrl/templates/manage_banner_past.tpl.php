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
                           alert("No Cities!");
                       }
                       else {
                           $("#"+cityField+"").html(msg);
                       }
                   }
               });
        }
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
				<div id="divMainPage" style="margin-left:10px; margin-right:5px;">
<!-------------------------------Manage Banner PAGE STARTS HERE--------------------------------------------------------------->
					<div align="center" style="width:100%">&nbsp;</div>
					<div align="center" style="width:100%" class="headtitle">Past Banners</div>
					<div align="center" style="width:100%">&nbsp;</div>
                    
    <form name="citysearch" id="citysearch">
        <table width="80%">
            <tr>
                <td width="13%"  align="left">Select country :  </td>
                <td width="10%"> 
				<input type="hidden" name="q" value="<?php echo $_GET['q'];?>" />
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
                                <a href="manage_banner.php?q=past&Id=<?php echo $BannerList[$i]['id'];?>&Edit=ChangeStatus&Active=<?php echo $newStatus;?>">Change Status</a>
                                &nbsp;
                                <a href="manage_banner_edit.php?q=past&Id=<?php echo $BannerList[$i]['id'];?>">Edit</a>
                                &nbsp;
                                <a href="manage_banner.php?q=past&delete=<?php echo $BannerList[$i]['id'];?>" onclick="return confirm('Are you sure to delete this image');">Delete</a>									
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
	</div>	<script language="javascript">
  	document.getElementById('ans12').style.display='block';
</script>
        
</body>
</html>