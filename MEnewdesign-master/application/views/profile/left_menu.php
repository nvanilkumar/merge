<?php 
	$userId = $this->customsession->getUserId();
	$isLogin = ($userId > 0) ? 1 : 0 ;
	$isAttendee = $this->customsession->getData('isAttendee');
	$isPromoter = $this->customsession->getData('isPromoter');
	$isOrganizer = $this->customsession->getData('isOrganizer');

 ?>
<div class="leftFixed">
 <div id="cssmenu" class="fs-special-view-left-menu">
        <ul>
          <li class="has-sub"><a href="#" id="showdropdowntop" class="currentPage"><span class="icon icon-configer"></span>Profile</a>
            <ul>
              <li><a <?php if($this->uri->segment(2)==""){echo 'class="currentsubLink"';}?> href="<?php echo commonHelperGetPageUrl('user-myprofile');?>"  id="showdropdowntwo" class="unselected">Personal Details</a> </li>
			  <?php if($isOrganizer == 1) { ?>
              <li><a <?php if($this->uri->segment(2)=="company"){echo 'class="currentsubLink"';}?> href="<?php echo commonHelperGetPageUrl('user-companyprofile');?>" class="unselected">Company Details</a>
			   
			   <li><a <?php if($this->uri->segment(2)=="bank"){echo 'class="currentsubLink"';}?> href="<?php echo commonHelperGetPageUrl('user-bankdetail');?>" class="unselected">Bank Details</a></li>
               <li><a <?php if($this->uri->segment(2)=="alert"){echo 'class="currentsubLink"';}?>  href="<?php echo commonHelperGetPageUrl('user-alert');?>" class="unselected">Alerts</a></li>
			   
			  <?php } ?>
			  </li>
			  
              <li><a <?php if($this->uri->segment(2)=="changePassword"){echo 'class="currentsubLink"';}?>  href="<?php echo commonHelperGetPageUrl('changepassword');?>"  class="unselected">Change Password</a></li>
              <li><a <?php if($this->uri->segment(2)=="developerapi"){echo 'class="currentsubLink"';}?>  href="<?php echo commonHelperGetPageUrl('developerapi');?>"  class="unselected">Apps List</a></li>
              <li><a <?php if($this->uri->segment(3)=="affiliateBonus"){echo 'class="currentsubLink"';}?>  href="<?php echo commonHelperGetPageUrl('dashboard-global-affliate-bonus');?>"  class="unselected">Affiliate Bonus</a></li>
            </ul>
          </li>
            </ul>
      </div>
      <div class="sidebar-full-height-bg"></div>
 </div>
