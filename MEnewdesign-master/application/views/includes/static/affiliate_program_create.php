<style type="text/css">



</style>

<!--important-->
<div class="page-container">
    <div class="wrap">
        <div class="container-fluid affiliatesection">
            <!-- Copy from this section-->
            <?php $this->load->view("includes/static/common/affiliate_header_view.php"); ?>



            <div class="container text-center">
                <h2>Get Started Right Away</h2>				
            </div>


            <div class="container affiliate-joinnow mbottom50">
                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12 aff-join-list">
                        <ul>
                            <li>Search for the Events you want to promote from the list of events. Or still better, request the host of the event to forward an invitation link to you.</li>
					<li>Click on the link provided by the host or copy and paste the link in a new browser and start off.</li>
					<li>A separate link will be provided to you for each of the events that you add to your account and promote.</li>
					<li>Email your links to your contacts and associates for them to act.</li>
					<li>Receive commission for every completed purchase.</li>
					<li>Your commission is paid to you monthly for all purchases generated from your links.</li>
                        </ul>
                    </div>

                </div>
            </div>


            <div class="container-fliud m-tb50"><!--m-tb50 aff-bg p-tb50-->
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                        <?php if (!$this->customsession->getData('userId')) { ?>
                            <div class="col-lg-12 affjoinnowbtn text-center">
                                <div class="aff-gen-holder">
                                    <input type="button" name="affjoinbtn" value="Login to Proceed" onclick="javascript:window.location.href = '<?php echo commonHelperGetPageUrl('user-login') . '?redirect_url=globalaffiliate/join'; ?>';">
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($this->customsession->getData('userId')) { ?>
                            <div class="col-lg-12 affjoinnowbtn text-center">
                                <p><?php
                                    if ($isGlobalPromoter) {
                                        echo "Your ";
                                    } else {
                                        echo 'Click here to generate a ';
                                    }
                                    ?>unique affiliate code to promote events.</p>
                                <div class="aff-gen-holder">
                                    <input type="text" id="globalPromoterCode" name="" placeholder="Enter your custom code" value="<?php echo $code; ?>" <?php
                                    if ($isGlobalPromoter) {
                                        echo 'disabled="disabled"';
                                    }
                                    ?>>
                                    <?php if (!$isGlobalPromoter) { ?>
                                    <input id="addGlobalPromoter" type="button" name="affjoinbtn" value="Generate Unique Code">
    <?php } ?>
                                </div>
                            </div>
<?php } ?>


                        <p id="successMessage" style="display:none;">
                            <span style="color:#339900;font-weight:bold;">Congratulations, Your Affiliate Code Generated</span><br>
                        </p>
                        <p id="messagePTag" style="display:none;">
                            <span style="color:red;font-weight:bold;" id="errorMessage"></span><br>
                        </p>
<?php if ($this->customsession->getData('userId')) { ?>
                            <div class="col-lg-12 affgeneratecode text-center affjoinnowtbox">
                                <input type="button" name="" value="Go to Dashboard" onclick="javascript:window.location.href='<?php echo commonHelperGetPageUrl('dashboard-global-affliate-bonus');?>'">
                            </div>
<?php } ?>
                    </div>
                </div>	
            </div>






            <!-- EO blog--> 
        </div><!--Fluid Div-->
    </div>
    <!-- /.wrap --> 
</div>
<!-- /.page-container --> 
<!-- on scroll -->
<?php $this->load->view("includes/elements/home_scroll_filter.php"); ?>

<script>
    var api_globalPromoter='<?php echo commonHelperGetPageUrl('api_globalPromoter'); ?>';
    var api_checkGlobalCodeAvailability='<?php echo commonHelperGetPageUrl('api_checkGlobalCodeAvailability'); ?>';
    var notAllowedCodes=['organizer','meraevents','meraevent'];
</script>

