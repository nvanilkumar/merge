<div class="container"> 
    <div class="affiliate-nav">
        <ul>
            <li>
                <a href="<?php echo commonHelperGetPageUrl('dashboard-global-affliate-home');?>" <?php if($this->uri->segment(2)=='home'){ echo 'class="select"';}?>>Home</a>
            </li>
            <li>
                <a href="<?php echo commonHelperGetPageUrl('dashboard-global-affliate-why');?>" <?php if($this->uri->segment(2)=='why'){ echo 'class="select"';}?>>Why be an Affiliate</a>
            </li>
            <!-- <li>
                    <a href="javascript:void(0);">How it Works</a>
            </li> -->
            <li>
                <a href="<?php echo commonHelperGetPageUrl('dashboard-global-affliate')?>"  <?php if($this->uri->segment(2)=='join'){ echo 'class="select"';}?>>Join Now</a>
            </li>
            <li>
                <a href="<?php echo commonHelperGetPageUrl('dashboard-global-affliate-faq');?>" <?php if($this->uri->segment(2)=='faq'){ echo 'class="select"';}?>>FAQs</a>
            </li>
        </ul>
    </div>


</div><!-- About section end-->

<div class="container-fluid affiliate-banner">
    <div class="aff-banner-holder">
        <img src="<?php echo $this->config->item('images_static_path'); ?>affiliate/affiliate-banner.jpg">
    </div>
    <div class="container aff-banner-text">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12 aff-text">
                <!-- <h3>Affiliate Marketing</h3> -->
                <h3>Make money by promoting events</h3>
                <p>Earn upto <?php echo GLOBAL_AFFILIATE_COMMISSION;?>% commission on every event</p>
                <p><a href="<?php echo commonHelperGetPageUrl('dashboard-global-affliate');?>" class="aff-join-btn">Join now for free</a></p>
            </div>
        </div>
    </div>
</div>