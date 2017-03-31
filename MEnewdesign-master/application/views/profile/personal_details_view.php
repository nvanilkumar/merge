<div class="rightArea">
     <?php if (isset($message)) { ?>
        <div id="personalDetailsMessage" <?php if($status) { ?>
             class="db-alert db-alert-success flashHide" <?php } else { ?> 
             class="db-alert db-alert-danger flashHide" <?php } ?> >
            <strong>&nbsp;&nbsp;  <?php echo $message; ?></strong> 
        </div>
    <?php } ?>
    <div class="heading">
        <h2>Personal Details</h2>
    </div>
    <div class="editFields fs-profile-form">
        <form enctype="multipart/form-data" name="personalDetails" id="personalDetails" method="post" action=""  >
            <label>User Name <span class="mandatory">*</span></label>
            <input type="text" id="username" name="username" value="<?php echo $personalDetails['username']; ?>" class="textfield">
            <div id="userSuccessMessage"> </div> <div id="userErrorMessage"> </div>
            <label>Email ID <span class="mandatory">*</span></label>
            <input type="text" name="email" value="<?php echo $personalDetails['email']; ?>" readonly class="textfield">
            <label>Name <span class="mandatory">*</span></label>
            <input type="text" name="name" value="<?php echo $personalDetails['name']; ?>" class="textfield">
            <label>Company Name</label>
            <input type="text" name="companyname" value="<?php echo $personalDetails['company'] ?>" class="textfield">
            <label>Address</label>
            <textarea class="textarea" name="address"><?php echo $personalDetails['address']; ?></textarea>
            <label>Location</label>
            <input type="text" class="textfield localityAutoComplete" id="locality" name="locality" value="<?php  echo (isset($locality) && $locality != '') ? $locality : ''; ?>">
            <input type="hidden" id="countryId" name="countryId" value = "<?php  echo $personalDetails['countryid']; ?>">
            <input type="hidden" id="stateId" name="stateId" value = "<?php  echo $personalDetails['stateid']; ?>">
            <input type="hidden" id="cityId" name="cityId" value = "<?php  echo $personalDetails['cityid']; ?>">
            <div class="clearBoth"></div>
            <label>Mobile Number <span class="mandatory">*</span></label>
            <input type="text"  name="mobile" value="<?php echo $personalDetails['mobile']; ?>" class="textfield">

            <label>Phone Number </label>
            <input type="text" name="phone" value="<?php echo $personalDetails['phone']; ?>" class="textfield">
            <label>Pincode </label>
            <input type="text" name="pincode" value="<?php echo $personalDetails['pincode']; ?>" class="textfield">
            <?php  
            $isOrganizer = $this->customsession->getData('isOrganizer');
            if($isOrganizer == 1) {
             ?>
            <label>Designation</label>
            <input type="text" name="designation" value="<?php echo $orgnizerDetails['designation'] ?>" class="textfield">
            <label>Facebook </label>
            <input type="text" value="<?php echo $orgnizerDetails['facebooklink']; ?>" name="facebooklink" class="textfield">
            <label>Twitter </label>
            <input type="text" value="<?php echo $orgnizerDetails['twitterlink']; ?>" name="twitterlink" class="textfield">
            <label>Google Plus </label>
            <input type="text" value="<?php echo $orgnizerDetails['googlepluslink']; ?>" name="googlepluslink" class="textfield">
             <label>Linked In </label>
            <input type="text" value="<?php echo $orgnizerDetails['linkedinlink']; ?>" name="linkedinlink" class="textfield">
<?php } ?>
            <div class="clearBoth"></div>

            <div class="clearBoth"></div>
<!--            <img src="<?php //echo $personalDetails['profileimagefilepath']; ?>" alt="<?php //echo $personalDetails['profileimagefilepath']; ?>" id="picShow" width="120" height="120" /> 
            <input type="file" id="picture" name="picture"/>-->
            <input type="submit"  name="personalDetailsForm" class="submitBtn createBtn float-right" value="UPDATE"/>
        </form>
    </div>
</div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places"></script>
<script>
//var api_cityCitysByState = "<?php// echo commonHelperGetPageUrl('api_cityCitysByState');?>";
var api_checkUserNameExist = "<?php echo commonHelperGetPageUrl('api_checkUserNameExist');?>";
var api_countrySearch = "<?php echo commonHelperGetPageUrl('api_countrySearch')?>";
var api_stateSearch = "<?php echo commonHelperGetPageUrl('api_stateSearch')?>";
var api_citySearch = "<?php echo commonHelperGetPageUrl('api_citySearch')?>";
</script>
