<?php 
	$userId = $this->customsession->getUserId();
        $attendeeStatus = $this->customsession->getData('isAttendee');
        $promoterStatus = $this->customsession->getData('isPromoter');
        $orgStatus = $this->customsession->getData('isOrganizer');
        $collaboratorStatus = $this->customsession->getData('isCollaborator');
        $attendeeCheck = (isset($attendeeStatus) && $attendeeStatus == 1) ? TRUE : FALSE;
        $promoterCheck = (isset($promoterStatus) && $promoterStatus == 1) ? TRUE : FALSE;
        $orgCheck = ((isset($orgStatus) && $orgStatus == 1) || (isset($collaboratorStatus) && $collaboratorStatus == 1)) ? TRUE : FALSE;
        $viewPageName = $this->uri->segment(1);
	$pageName = $this->uri->segment(2);
        $viewpage = $this->uri->segment(3);
 ?>
<div class="leftFixed">
 <div id='cssmenu'>
        <ul>
          <?php if($attendeeCheck){ ?>
          <li class="has-sub">
          	<a href="<?php echo getAttendeeUrl() ?>" <?php echo (($viewPageName == "currentTicket" || $viewPageName == "pastTicket" ) && $pageName == "") ? ' class="currentPage"' : ''; ?>><span class="icon icon-configer menuLeft icon2-ticket"></span>Attendee View</a>
            <ul <?php echo (($viewPageName == 'currentTicket' || $viewPageName == 'pastTicket') && $pageName == "") ? ' id="currentMenu" style="display: block"' : ''; ?>>
               
              <li><a <?php if($viewPageName == "currentTicket" ) { echo 'class="currentsubLink"';}?> href="<?php echo commonHelperGetPageUrl('user-attendeeview-current');?>" class="unselected">Current Tickets</a> </li>
              <li><a <?php if($viewPageName == "pastTicket" ) { echo 'class="currentsubLink"';}?> href="<?php echo commonHelperGetPageUrl('user-attendeeview-past');?>" class="unselected">Past Tickets</a>
			   
              </li>
			  
              </ul>
          </li>
          <?php } ?>
          <?php if($orgCheck){ ?> 
          <li class="has-sub">
                <a href="<?php echo getDashboardUrl() ?>" <?php echo ($viewPageName == "dashboard" && ($pageName == "" || $pageName == "pastEventList")) ? ' class="currentPage"' : ''; ?>><span class="icon icon-event icon-configer menuLeft"></span>Organizer View</a>
            <ul <?php echo ($viewPageName == 'dashboard' && ($pageName == "" || $pageName == "pastEventList")) ? ' id="currentMenu" style="display: block"' : ''; ?>>
              <li><a <?php if($viewPageName == "dashboard" && $pageName == ""){echo 'class="currentsubLink"';}?> href="<?php echo commonHelperGetPageUrl('dashboard-myevent');?>" class="unselected">Upcoming Events</a> </li>
              <li><a <?php if($viewPageName == "dashboard" && $pageName == "pastEventList" ) { echo 'class="currentsubLink"';}?> href="<?php echo commonHelperGetPageUrl('dashboard-pastevent');?>" class="unselected">Past Events</a></li>
			  
              </ul>
          </li>
          <?php } ?>
          <?php if($promoterCheck){?>
          <li class="has-sub" >
          	<a href="<?php echo getPromoterViewUrl() ?>" <?php echo ($viewPageName == "promoter") ? ' class="currentPage"' : ''; ?> ><span class="icon icon-configer menuLeft icon2-bullhorn"></span>Promoter View</a>
            <ul <?php echo ($viewPageName == 'promoter') ? ' id="currentMenu" style="display: block"' : ''; ?>>
              <li><a <?php if(($viewPageName == "promoter") && ($pageName == "currentlist")){echo 'class="currentsubLink"';}?> href="<?php echo commonHelperGetPageUrl('user-promoterview-current');?>" class="unselected">Current Events</a> </li>
              <li><a <?php if(($viewPageName == "promoter") && ($pageName == "pastlist")){echo 'class="currentsubLink"';}?> href="<?php echo commonHelperGetPageUrl('user-promoterview-past');?>" class="unselected">Past Events</a></li>
              <li><a <?php if(($viewPageName == "promoter") && ($pageName == "offlinebooking")){echo 'class="currentsubLink"';}?> href="<?php echo commonHelperGetPageUrl('user-promoterview-offlinebooking');?>" class="unselected">Offline Booking</a></li>
			  
              </ul>
          </li>
          <?php } ?>
        </ul>
		
      </div>
      <div class="sidebar-full-height-bg"></div>
 </div>
