<style type="text/css">



</style>

<!--important-->
<div class="page-container">
    <div class="wrap">
        <div class="container-fluid affiliatesection">
            <!-- Copy from this section-->
            <?php $this->load->view("includes/static/common/affiliate_header_view.php"); ?>



            <div class="container text-center">
                <h2>Earn Money with MeraEvents Affiliate</h2>				
            </div>


            <div class="container affiliate-feature-tabs">
                <div class="row">
                    <ul class="" role="tablist" id="myTab"><!--nav nav-tabs-->
                        <li role="presentation" class="active col-lg-4 col-md-6 col-sm-12 affiliate-tab text-center">
                            <a href="#findevents" aria-controls="findevents" role="tab" data-toggle="tab" aria-expanded="false"><img src="<?php echo $this->config->item('images_static_path'); ?>affiliate/findevents-img.png">
                                <p class="aff-tab-subheading">Find Events</p></a>
                        </li>
                        <li role="presentation" class="col-lg-4 col-md-6 col-sm-12 affiliate-tab text-center">
                            <a href="#shareevent" aria-controls="shareevent" role="tab" data-toggle="tab" aria-expanded="true"><img src="<?php echo $this->config->item('images_static_path'); ?>affiliate/shareevents-img.png">
                                <p class="aff-tab-subheading">Share Events</p></a>
                        </li>
                        <li role="presentation" class="col-lg-4 col-md-6 col-sm-12 affiliate-tab text-center">
                            <a href="#earnmoney" aria-controls="earnmoney" role="tab" data-toggle="tab" aria-expanded="true"><img src="<?php echo $this->config->item('images_static_path'); ?>affiliate/earnmoney-img.png">
                                <p class="aff-tab-subheading">Earn Money</p></a>
                        </li>
                    </ul>
                </div>
            </div>


            <div class="container affiliate-feature-tabs tab-content">
                <div role="tabpanel" class="tab-pane affiliate-tab-content find active" id="findevents">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="aff-tabcontent-info">
                                <p>Browse through the list of events and pick and choose in tune with your tastes!</p>
                            </div>
                            <div class="aff-button-holder"><a href="<?php echo site_url('search');?>" class="aff-tabcontent-button">Find Events</a></div>

                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 affiliate-infographic">
                            <img src="<?php echo $this->config->item('images_static_path'); ?>affiliate/affiliate-search-events.png">
                        </div>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane affiliate-tab-content share" id="shareevent">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="aff-tabcontent-info">
                               <p>Share events details with your friends and associates through email & Social Media</p>
                            </div>
                            <!--<div class="aff-button-holder"><a href="#" class="aff-tabcontent-button"">Share Events</a></div>-->

                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 affiliate-infographic">
                            <img src="<?php echo $this->config->item('images_static_path'); ?>affiliate/affiliate-search-events.png">
                        </div>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane affiliate-tab-content earn" id="earnmoney">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="aff-tabcontent-info">
                                <p>Earn decent commission on purchases made through links shared by you.<br> MeraEvents is replete with various hues of Events from sports to cultural events; from Professional to Music events. You name them, we have them for you. We offer you a wide spectrum to choose from and make money on the go! What more, we made it super easy to share and rake in the moolah! Come and experience the cozy and cool way of making money. All this, without ever leaving your premises! Sounds great, ah!</p>
                            </div>
                            <!--<div class="aff-button-holder"><a href="#" class="aff-tabcontent-button"">View Earnings</a></div>-->

                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 affiliate-infographic">
                            <img src="<?php echo $this->config->item('images_static_path'); ?>affiliate/affiliate-search-events.png">
                        </div>
                    </div>
                </div>

            </div><!--Tab Content End-->










            <!-- EO blog--> 
        </div><!--Fluid Div-->
    </div>
    <!-- /.wrap --> 
</div>
<!-- /.page-container --> 
<!-- on scroll -->
<?php $this->load->view("includes/elements/home_scroll_filter.php"); ?>
</div>
</div>




