<div class="rightArea">
    <?php if (isset($message)) { ?>
        <div id="companyDetailsMessage"  <?php if($status) { ?>
             class="db-alert db-alert-success flashHide" <?php } else { ?> 
             class="db-alert db-alert-danger flashHide" <?php } ?> >
            <strong>&nbsp;&nbsp;  <?php echo $message; ?></strong> 
        </div>
    <?php } ?> 
    <div class="heading">
        <h2>Company Details</h2>
    </div>
    <div class="clearBoth"></div>
    <div class="editFields fs-company-details">
        <form enctype="multipart/form-data" name="companyDetails" id="companyDetails" method="post" action="" >
            <label>Company Name <span class="mandatory">*</span></label>
            <input type="text" name="companyname" value="<?php echo $companyDetails['companyname'] ?>" class="textfield">
            <label>Designation</label>
            <input type="text" name="designation" value="<?php echo $companyDetails['designation'] ?>" class="textfield">
            <label> Company Email</label>
            <input type="text" name="email" value="<?php echo $companyDetails['email'] ?>" class="textfield">
            <label>Company Description</label>
            <textarea class="textarea" name="description"><?php echo $companyDetails['description'] ?></textarea>
            <label>Company URL</label>
            <input type="text" name="url" value="<?php echo $companyDetails['url'] ?>" class="textfield">
            <label>Location<span class="mandatory">*</span></label>
            <input type="text" class="textfield localityAutoComplete" id="locality" name="locality" value="<?php  echo (isset($locality) && $locality != '') ? $locality : ''; ?>">
            <input type="hidden" id="countryId" name="countryId" value = "<?php  echo $companyDetails['countryid']; ?>">
            <input type="hidden" id="stateId" name="stateId" value = "<?php  echo $companyDetails['stateid']; ?>">
            <input type="hidden" id="cityId" name="cityId" value = "<?php  echo $companyDetails['cityid']; ?>">

            <div class="clearBoth"></div>
            <label>Phone Number</label>
            <input type="text" name="phone" value="<?php echo $companyDetails['phone'] ?>" class="textfield">
            <div class="clearBoth"></div>
<!--            <img src="<?php //echo $companyDetails['logoPath']; ?>" alt="<?php //echo $companyDetails['logoPath']; ?>" id="picShow" width="80" height="80" /> 
            <input type="file" id="picture" name="picture"/>-->
            <input type="submit" name="companyForm" class="submitBtn fs-btn float-right" value="UPDATE"/>
        </form>

    </div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places"></script>
<script>
var api_cityCitysByState = "<?php echo commonHelperGetPageUrl('api_cityCitysByState');?>";
var api_countrySearch = "<?php echo commonHelperGetPageUrl('api_countrySearch')?>";
var api_stateSearch = "<?php echo commonHelperGetPageUrl('api_stateSearch')?>";
var api_citySearch = "<?php echo commonHelperGetPageUrl('api_citySearch')?>";
</script>

