
<!--important-->
<div class="page-container">
    <div class="wrap">
        <div class="container"> 

            <div class="innerPageContainer">
                <h2 class="create_eve_title">Start here </h2>
                
                <div class="row">
                    <form enctype="multipart/form-data" name="createEventForm" id="createEventForm" method="POST"> 
                        <input type="hidden" name="ticketCount" id="ticketCount" value="">
                        <input type="hidden" name="eventId" id="eventId" value="<?php  if (isset($eventId) && ($eventId > 0 )) { echo $eventId;} else{ echo 0;}?>">
                        <input type="hidden" name="thumbnailfileid" id="thumbnailfileid" value="<?php echo isset($eventDetails['thumbnailfileid'])?$eventDetails['thumbnailfileid']:'';?>">
                        <input type="hidden" name="bannerfileid" id="bannerfileid" value="<?php echo isset($eventDetails['bannerfileid'])?$eventDetails['bannerfileid']:'';?>">
                        <input type="hidden" name="submitValue" id="submitValue" value="" > 
                        <input type="hidden" name="categoryId" id="categoryId" value="<?php echo isset($eventDetails['categoryId'])?$eventDetails['categoryId']:'';?>"> 
                        <input type="hidden" name="subcategoryId" id="subcategoryId" value="<?php echo isset($eventDetails['subcategoryId'])?$eventDetails['subcategoryId']:'';?>">
                        <input type="hidden" id="addedCategories" value="<?php echo isset($eventDetails['categoryName'])?$eventDetails['categoryName']:'';?>">
                        <input type="hidden" id="cloudPath" value="<?php echo $this->config->item('images_content_cloud_path');?>">
                        <input type="hidden" name="latitude" id="latitude" value="<?php echo isset($eventDetails['latitude'])?$eventDetails['latitude']:'';?>">
                        <input type="hidden" name="longitude" id="longitude" value="<?php echo isset($eventDetails['longitude'])?$eventDetails['longitude']:'';?>"> 
                        <?php 
                        if(isset($userType) && $userType == "superadmin"){ ?>
                            <input type="hidden" name="userType" id="userType" value="<?php echo $userType ;?>"> 
                        <?php }
                               $disabledRegistrationTypeFree = $disabledRegistrationTypeNoReg = $cantChangeMessage = $bannerPathMsg=$thumbnailPathMsg='';
                               if (isset($eventId) && ($eventId > 0 )) {
                                   ?>
                            <input type="hidden" id="registrationTypeCheck" name="oldregistrationType" value="<?php echo isset($eventDetails['registrationType'])?$eventDetails['registrationType']:''; ?>">
                            <?php
                            if (isset($transactionsCount) && $transactionsCount > 0) {
                                if ($eventDetails['registrationType'] == 2) {
                                    $disabledRegistrationTypeFree = ' disabled="disabled" ';
                                    $disabledRegistrationTypeNoReg = ' disabled="disabled" ';
                                    $cantChangeMessage = 'cantChangeEventType';
                                }
                                if ($eventDetails['registrationType'] == 1) {
                                    $disabledRegistrationTypeNoReg = ' disabled="disabled" ';
                                    $cantChangeMessage = 'cantChangeEventType';
                                }
                            }
                            
                             if(isset($eventDetails['bannerPath']) && $eventDetails['bannerPath']!='' && ($eventDetails['bannerPath'] != $this->config->item('images_content_cloud_path'))){ 
                                $bannerPathMsg='style="display:none;"';
                        }
                              if(isset($eventDetails['thumbnailPath']) && $eventDetails['thumbnailPath']!='' && ($eventDetails['thumbnailPath'] != $this->config->item('images_content_cloud_path'))){ 
                                $thumbnailPathMsg='style="display:none;"';
                              }  
                        }
                        ?>
                        <div class="col-md-8 col-xs-12 col-sm-12">
                            <div class="row create_eve_container create_eve_bg animated">
                                <div class="col-sm-12 ">
                                    <h2 class="title_1">About</h2>
                                    <div id="eventDataSuccess" style="color: #26A65B;">

                                    </div>
                                    <div class="create-event-error">
                                        <ul id="eventDataErrors"></ul>
                                    </div>
                                  <!--   <div class="form-group event_type">
                                        <label>Event Type</label><br>
                                        <input <?php if(isset($eventDetails['registrationType']) && $eventDetails['registrationType']==2){ echo 'checked="checked"';}?> type="radio" name="registrationType" value="2" 
                                               id="registrationType2" class = "selecteventtype <?php echo $cantChangeMessage; ?>">
                                        <label class="eventype_space">Paid</label>
                                        <input <?php if(isset($eventDetails['registrationType']) && $eventDetails['registrationType']==1){ echo 'checked="checked"';}?> <?php echo $disabledRegistrationTypeFree; ?> type="radio" name="registrationType" value="1" id="registrationType1" class = "selecteventtype <?php echo $cantChangeMessage; ?>" >
                                        <label class="eventype_space">Free</label>
                                        <input <?php if(isset($eventDetails['registrationType']) && $eventDetails['registrationType']==3){ echo 'checked="checked"';}?> <?php echo $disabledRegistrationTypeNoReg; ?> type="radio" name="registrationType" value="3" id="registrationType3" class = "selecteventtype <?php echo $cantChangeMessage; ?>" >
                                        <label class="eventype_space">No Registration</label>

                                    </div>-->
                                    <div class="form-group">
                                        <label>Event Title</label>
                                        <input <?php if(!isset($eventDetails['title'])){ echo 'autofocus="true"'; } ?> type="text" class="form-control eventFields" name="title" id="eventTitle" value="<?php echo isset($eventDetails['title'])?$eventDetails['title']:'';?>">
                                    </div>

                                    <div class="form-group create_eve_labelspace">
                                        <label>Description</label>
                                        <textarea style="height: 170px;" type="text" ui-tinymce ="tinymceOptions" id="event-desc"  class="form-control eventFields" name="description" ><?php echo isset($eventDetails['description'])?$eventDetails['description']:'';?></textarea>
                                        
                                    </div>
                                    <div class="create_eve_dropdowns">
                                        <ul>
                                            <li class="dropdown fleft">
                                                 <label for="Category">Category</label>
                                                <a href="javascript:;" class="dropdown-togglep selectCategory" data-toggle="dropdown" role="button" aria-expanded="false" <?php if(isset($eventDetails['categoryThemeColor'])){echo 'style="background:'.$eventDetails['categoryThemeColor'].'"';}?>><?php echo isset($eventDetails['categoryName'])?$eventDetails['categoryName']:'Select a Category';?><span class="icon-downArrow"></span></a>
                                                <ul class="dropdown-menu categorySelect" role="menu">
                                                    <?php if(isset($categoryList) && !empty($categoryList)){
                                                        foreach ($categoryList as $cListKey => $clistValue) {  ?>
                                                    <li onclick="categoryChanged('<?php echo $clistValue['id'];?>','<?php echo $clistValue['name'];?>','true','<?php echo $clistValue['themecolor'];?>');">
                                                        <a>
                                                            <i class="icon1-<?php echo strtolower(str_replace(' ','',$clistValue['name']));?> col<?php echo strtolower($clistValue['name'])?>"></i><?php echo strtolower($clistValue['name'])?>
                                                        </a>
                                                    </li>
                                             <?php      }
                                                    }?>


                                                </ul>
                                                <span id="categoryError" class="create-event-error error"></span>
                                            </li>

                                            <li class="dropdown ">
<label for="Sub Category" class="">Sub Category</label>
<input type="text" placeholder="Enter Sub Category " class="form-control eventFields" id="subCategoryName" name="subCategoryName" value="<?php echo isset($eventDetails['subCategoryName'])?$eventDetails['subCategoryName']:'';?>"/>
                                            </li>

                                        </ul>
                                    </div>

                                    <div class="form-group eventTags fullwidth_form">
                                        <label>Event URL </label>
                                        <span> <?php echo commonHelperGetPageUrl('preview-event'); ?></span>
                                        <input type="text" class="form-control eventFields tagsField " value="<?php echo isset($eventDetails['url'])?$eventDetails['url']:'';?>" name="url" id="eventUrl">

                                        <a onclick="checkUrlExists()" href="javascript:;" class="checkurl_btn">Check Availability</a>
                                        <a onclick="editUrl()" href="javascript:;" class="checkurl_btn" id="editurl">Edit</a>
                                        <span id="checkUrlAvail"></span>
                                    </div>

                                    <div class="form-group eventTags">
                                        <label>Tags </label>
                                        <input id="event_tags" type="text" placeholder="Enter a tag"
                                               name="tags" value="<?php echo isset($eventDetails['tags'])?strtolower($eventDetails['tags']):'';?>">
                                        <span id="event_tags_error" style="color: red;"></span>


                                    </div> 

                                    <div >
                                        <h2 class="title_2 clearBoth">Where</h2>
                                        <div class="form-group sales">
                                            <?php
                                            $webinarHiddenValue = '<input type="hidden"  name="iswebinar"  value="0">';
                                            $className = "";
                                            $webinarCheckedStatus = "";
                                            $webinarDivStatus = "";
                                            $webinarModelValue = "false";
                                            $isWebinarValue=0;
                                            if (isset($eventId) && ($eventId > 0 ) && ($eventDetails['eventMode'] == 1)) {
                                                $webinarHiddenValue = "";
                                                $className = " selected";
                                                $webinarCheckedStatus = "checked='checked'";
                                                $webinarDivStatus = 'style="display: none;"';
                                                $webinarModelValue = "true";
                                                $isWebinarValue='1';
                                            }
                                            ?>
                                            <span class='custom-checkbox<?php echo $className; ?>'> 
                                                <input type="checkbox" id="webinar"  name="iswebinar" value="<?php echo $isWebinarValue;?>" <?php echo $webinarCheckedStatus; ?>
                                                       onclick="webinarChange(this)" /> 
                                                       <?php //echo $webinarHiddenValue; ?>

                                            </span>
                                            <h5> Webinar </h5><br>

                                        </div>
                                        <div id="div_webinar" <?php // echo $webinarDivStatus;  ?>>
                                            <div class="form-group" id="locationField"  >
                                                <label>Venue</label>
                                                <input type="text" class="form-control eventFields placechange" id="eventVenue" name="venueName" value="<?php echo isset($eventDetails['venueName'])?$eventDetails['venueName']:'';?>">
                                                <button id="clearVenue" class="clear-venue"><span class="icon2-repeat"></span></button>
                                                <!--onFocus="geolocate()"-->

                                                <span class="pull-right addAdd">+</span>
                                            </div>
                                            <div class="add_address" id="address" style="display: none;">
                                                <label class="add_address_space1">Address line 1</label>
                                                <input type="text" class="form-control eventFields field" id="eventAddress1" name="venueaddress1" value="<?php echo isset($eventDetails['location']['address1'])?$eventDetails['location']['address1']:'';?>">


                                                <div class="clear"></div>

                                                <label class="add_address_space">Address line 2</label>
                                                <input type="text" class="form-control eventFields" id="eventAddress2" name="venueaddress2" value="<?php echo isset($eventDetails['location']['address2'])?$eventDetails['location']['address2']:'';?>">


                                                <div class="clear"></div>				

                                                <ul>
                                                    <li>
                                                        <label class="add_address_space" for="Country">Country</label>
                                                        <input type="text" placeholder="Enter Your Country" class="form-control eventFields countryAutoComplete locationfields" id="country" name="country" value="<?php echo isset($eventDetails['location']['countryName'])?$eventDetails['location']['countryName']:'';?>"/>
                                                    <label for="country" id="countryError">&nbsp;</label>
                                                    </li>
                                                    <li>
                                                        <label for="State" class="add_address_space">State</label>
<input type="text" placeholder="Enter Your State" class="form-control eventFields stateAutoComplete locationfields" id="state" name="state" value="<?php echo isset($eventDetails['location']['stateName'])?$eventDetails['location']['stateName']:'';?>"/>
                                                   <label for="state" id="stateError">&nbsp;</label>
                                                    </li>
                                                    <li>
                                                        <label for="city" class="add_address_space">City</label>
<input type="text" placeholder="Enter Your City" class="form-control eventFields cityAutoComplete locationfields" id="city" name="city" value="<?php echo isset($eventDetails['location']['cityName'])?$eventDetails['location']['cityName']:'';?>"/>
                                                   <label for="city">&nbsp;</label>
                                                    </li>
                                                     <li>
                                                        <label for="pincode" class="add_address_space">Pincode</label>
<input type="text" placeholder="Enter Your Pincode" class="form-control eventFields" id="pincode" name="pincode" value="<?php echo (isset($eventDetails['location']['pincode'])&& $eventDetails['location']['pincode']>0) ? $eventDetails['location']['pincode']:'';?>"/>
<label for="pincode">&nbsp;</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>   
                                    </div>

                                    <h2 class="title_3 clearBoth">When</h2>

                                    <div class="create_eve_where change_currency ">
                                        <ul>
                                            <li>
                                                <label for="Start date">Start date </label>
                                                <?php if (isset($eventId) && $eventId != '' && ( strtotime($eventDetails['startDate']) <= strtotime(allTimeFormats('',11))) && $eventDetails['status'] == 1 && $userType != "superadmin") { ?>
                                                <input type="text" class="form-control eventFields" id="start_date" name="startDate" value="<?php echo $eventDetails['convertedStartDate'];?>" readonly="readonly" disabled>
                                                <?php } else { ?>
                                                <input type="text" class="form-control eventFields" id="start_date" name="startDate" readonly="readonly" value="<?php echo isset($eventDetails['convertedStartDate'])?$eventDetails['convertedStartDate']:'';?>">
                                                    
                                                <?php } ?>
                                            </li>
                                            <li>
                                                <label for="Start time">Start time</label>
                                                <div class="input-group bootstrap-timepicker">
                                                    <input id="event_start" type="text" class="input-small" name="startTime" readonly="readonly" value="<?php if(isset($eventDetails['convertedStartTime'])){ echo $eventDetails['convertedStartTime'];}?>" <?php if (isset($eventId) && $eventId != '' && ( strtotime($eventDetails['startDate']) <= strtotime(allTimeFormats('',12))) && $eventDetails['status'] == 1 && $userType != "superadmin") {
                                                        echo "disabled";
                                                } ?> >
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                                </div>
                                            </li>
                                            <li>
                                                <label for="End date">End date</label>
                                                <input type="text" class="form-control eventFields" id="end_date" name="endDate" value="<?php if(isset($eventDetails['convertedEndDate'])){echo $eventDetails['convertedEndDate'];}?>" readonly="readonly">
                                                
                                            </li>
                                            <li>
                                                <label for="End time">End time</label>
                                                <div class="input-group bootstrap-timepicker">
                                                    <input id="event_end" type="text" class="input-small " name="endTime" value="<?php if(isset($eventDetails['convertedEndTime'])){echo $eventDetails['convertedEndTime'];}?>" readonly="readonly">
                                                    
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                                </div>
                                            </li>
                                            <li>
                                                <label  >Time Zone</label>
                                                <div class="TicketDropdownHolder">
                                                    <select name="timezoneId"  id="timeZoneId">
                                                        <?php foreach ($timeZoneList as $timeZone) { ?>
                                                            <option  value="<?php echo $timeZone['id']; ?>"><?php echo $timeZone['timezone']; ?></option>
<?php } ?>
                                                    </select>
                                                </div>   
                                            </li>
                                        </ul>
                                      <!--  <span id="addMoretime" class="add_time">Add another time and date</span>-->
                                    </div>

                                    <div id="div_ticketwidget">
                                        <h2 class="title_3 clearBoth">Tickets</h2>
<?php echo $ticketView; ?>

                                    </div> <!--Entire Ticket Div-->
                                    <div class="create_sub">
                                        <button type="button" id="addnewticket" class="addmoretickets btn btn-default btn-md gobtn createeventbuttons add_ticket">Add Ticket</button>
                                    </div>

                                    <div>
                                        <h2 class="title_2 clearBoth">Payment Gateway & Service Charge:</h2>
                                        <div class="form-group TicketDropdownHolder">
                                            <?php
                                                    $feeArr = $this->config->item('organizer_fees');
                                                ?>
                                                <select name="organiser_fee"> 
                                                    <option value='' <?php if($organiser_fee == '') echo 'selected="selected"';;?>>Organiser Pay Both The Fees (3.99%+S.tax)</option>
                                                    <option value="gatewaycharge" <?php if($organiser_fee == 'gatewaycharge') echo 'selected="selected"';;?>>Pass Payment Gateway fee to Customer (1.99%+S.tax)</option>
                                                    <option value="servicecharge" <?php if($organiser_fee == 'servicecharge') echo 'selected="selected"';;?>>Pass Service Charge to Customer (2%+S.tax)</option>
                                                    <option value="both" <?php if($organiser_fee == 'both') echo 'selected="selected"';;?>>Customer Pay Both The Fees (3.99%+S.tax)</option>
                                                </select>
                                        </div>
                                    </div>

                                    <div class="create_eve_tickets" id="ticketsalebtn">

                                        <h2 class="title_3">Change the Label <?php echo isset($eventDetails['bookButtonValue'])?$eventDetails['bookButtonValue']:'';?></h2>
                                        <ul class="sales">

                                            <li class="salesbtn">
                                                <div class="TicketDropdownHolder">  
                                                    
                                                    <select  name="booknowButtonValue">
                                                        <?php if(isset($saleButtonTitleList) && !empty($saleButtonTitleList)){
                                                                foreach ($saleButtonTitleList as $sBkey => $sBvalue) { ?>
                                                        <option value="<?php echo $sBvalue['name']?>" <?php if(isset($eventDetails['eventDetails']['bookButtonValue']) && $eventDetails['eventDetails']['bookButtonValue']==$sBvalue['name']){ ?> selected <?php }?>>
                                                                   <?php echo $sBvalue['name']?>
                                                        </option>
                                                     <?php      }
                                                        }?>
                                                    </select> 
                                                </div>
                                            </li> 
                                        </ul>
                                    </div>
                                    <div class="public_event">
                                        <label><input <?php if(isset($eventDetails['private']) && $eventDetails['private']==0){ ?>checked="checked"<?php } ?> type="radio" name="private" value="0"  id="private0">
                                        Public page:</label><span> do list this event publicly</span>
                                        <br>
                                        <label><input <?php if(isset($eventDetails['private']) && $eventDetails['private']==1){ ?>checked="checked"<?php } ?> type="radio" name="private" value="1"  id="private1">
                                        Private page:</label> <span>do not list this event publicly</span>

                                    </div>
                                    <div class="form-group" style="padding:20px 0 0 0;">
                                     
                                        <label> <input style="float:left; margin:0 10px 0 0;" <?php if(isset($eventDetails['acceptmeeffortcommission']) && ($eventDetails['acceptmeeffortcommission']==1) ){ ?>checked="checked" value="1" <?php } elseif(isset($eventDetails['acceptmeeffortcommission']) && ($eventDetails['acceptmeeffortcommission']==0)){ ?> value="0" <?php }else{ ?> checked="checked" value="1" <?php } ?> type="checkbox" name="acceptmeeffortcommission"  id="acceptmeeffortcommission"> 
                                            <h5 style="font-size: 14px; margin-left:25px;"> I accept to pay additional 6% for all meraevents efforts, meraevents partners,and affiliate marketing efforts</h5><br> </label>
                                    </div>
                            </div>
                            <!--End Step1--> 
                            </div>  
                        </div>
                        <div class="col-xs-12 col-md-4 design_event" >
                            <img src="bannerImageSrc" id="hiddenImg" style="display: none;">

                            <h2 class="title">Design your event</h2>
                            <div class="create-event-error">
                                <ul id="eventBannerErrors"></ul>
                            </div>
                            <div id="bannerImageDiv" class="upload" style="<?php if(isset($eventDetails['bannerPath']) && (empty($eventDetails['bannerPath']) || ($eventDetails['bannerPath'] == $this->config->item('images_content_cloud_path')))){ echo "background:".$eventDetails['categoryThemeColor'].";";}?>background-image: url('<?php echo isset($eventDetails['bannerPath'])?$eventDetails['bannerPath']:'';?>');background-repeat:no-repeat;background-size:300px 100px;">

                                <input type="file" name="bannerImage" id="bannerImage" class="unused"/>
                                <span class="upload_image" <?php echo $bannerPathMsg;?> ></span>
                                <span class="upload_image_text" <?php echo $bannerPathMsg;?> >Upload Header Image<br>1170 x 370px</span>
                                <div id="removeBanner" style="float:right; width:auto; text-align:right; padding:2px 5px 5px 5px;"> <i class="icon2-times-circle"></i></div>
                            </div>
                            
                            <div class="create-event-error">
                                <ul id="eventLogoErrors"></ul>
                            </div>	  
                            <div id="thumbImageDiv" class="Upload_Thumb" style="<?php if(isset($eventDetails['thumbnailPath']) && (empty($eventDetails['thumbnailPath']) || ($eventDetails['thumbnailPath'] == $this->config->item('images_content_cloud_path')))){ echo "background:".$eventDetails['categoryThemeColor'].";";}?>background-image: url(' <?php echo isset($eventDetails['thumbnailPath'])?$eventDetails['thumbnailPath']:'';?> ');background-repeat:no-repeat;background-size:178px 103px;">
                                <input type="file" name="thumbImage" id="thumbImage" class="unused2"/>
                                <span class="upload_image2 " <?php echo $thumbnailPathMsg;?> ></span>
                                <span class="upload_image_text2 " <?php echo $thumbnailPathMsg;?> >Upload Thumbnail<br>350x 200px</span>
                               <div id="removeThumb" style="float:right; width:auto; text-align:right; padding:2px 5px 5px 5px;"><i class="icon2-times-circle"></i></div>
                            </div>


                            <div class="theme_text">
                                <h4 >Don't have any image to upload?</h4>
                                <h5 >Select picture from our library to match
                                    your theme.</h5>
                                <button style="display: none;" type="button" class="btn btn-default btn-md pickbtn">PICK A THEME</button>
                            </div>
                            <div class="theme_images">
                                <h3>PICK AN IMAGE</h3>
                                <ul>
                                    <?php
                                    foreach ($pickThemeImages as $theme) {
                                        ?>      
                                        <li>
                                            <a href="javascript:void(0);"  class="clip-circle" >
                                                <img src="<?php echo $theme['short']; ?>"   alt="<?php echo $theme['theam'];?>" title="<?php echo $theme['theam'];?>"
                                                     data-thumburl="<?php echo $theme['thumb']; ?>"
                                                     data-bannerurl="<?php echo $theme['banner']; ?>"/>
                                            </a>
                                        </li>   
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </div>

                        </div>
                            <?php include_once('includes/elements/create_event_buttons.php'); ?>
                    </form>

                </div>
                <!-- wrap --> 
            </div>

<?php include("includes/event_header.php"); ?> 
        </div>
        <!-- wrap --> 
    </div>
    <!-- page-container --> 
    <!-- on scroll code-->
    <!-- for prieview-->
    <a href="#" target="_blank" id="previewEventURL" style="display: none;">Preview</a>
</div>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=<?php echo $this->config->item('google_app_key');?>"></script>
<style>
    #MoreTaxes li { margin-right:10px; float:left; line-height:40px;}
    .TaxField_width {width:80px !important;}
</style>
<script>
var api_countrySearch = "<?php echo commonHelperGetPageUrl('api_countrySearch')?>";
var api_countryDetails = "<?php echo commonHelperGetPageUrl('api_countryDetails')?>";
var api_ticketCalculateTaxes = "<?php echo commonHelperGetPageUrl('api_ticketCalculateTaxes')?>";
var api_checkUrlExists = "<?php echo commonHelperGetPageUrl('api_checkUrlExists')?>";
var api_stateSearch = "<?php echo commonHelperGetPageUrl('api_stateSearch')?>";
var api_subcategoryList = "<?php echo commonHelperGetPageUrl('api_subcategoryList')?>";
var api_countrySearch = "<?php echo commonHelperGetPageUrl('api_countrySearch')?>";
var api_citySearch = "<?php echo commonHelperGetPageUrl('api_citySearch')?>";
var api_ticketDelete = "<?php echo commonHelperGetPageUrl('api_ticketDelete')?>";
var api_eventCreate = "<?php echo commonHelperGetPageUrl('api_eventCreate')?>";
var api_eventEdit = "<?php echo commonHelperGetPageUrl('api_eventEdit')?>";
var api_tagsList = "<?php echo commonHelperGetPageUrl('api_tagsList')?>";
</script>
<?php if (isset($eventId)) { ?>
    <script>
        var eventstartDate = '<?php echo $eventDetails['convertedStartDate'] ?>';
        var eventstartTime = '<?php echo $eventDetails['convertedStartTime'] ?>';
        var eventEndDate = '<?php echo $eventDetails['convertedEndDate'] ?>';
        var eventEndTime = '<?php echo $eventDetails['convertedEndTime'] ?>';
        var eventStatus = '<?php echo $eventDetails['status'] ?>';
    </script>   
<?php } ?>
 