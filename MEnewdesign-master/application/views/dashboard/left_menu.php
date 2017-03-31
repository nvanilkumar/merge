<?php
$eventIdPermission = $this->input->get('eventId'); 
$eventData = $this->config->item('eventData');
$isCurrentEvent = (strtotime($eventData["event" . $eventIdPermission]['endDateTime']) > strtotime(allTimeFormats('',11))) ? TRUE : FALSE;
$useridPermission = getUserId();
$isCollaborator = $this->customsession->getData('isCollaborator');
$manageModule = TRUE;
$promoteModule = TRUE;
$reportModule = TRUE;
if ($isCollaborator == 1) {
    $collaboratorEventAccess = $this->config->item('collaboratorEventAccess');
    $collabEventUser = "collaboratorEvent" . $useridPermission . $eventIdPermission; 
    if ($collaboratorEventAccess[$collabEventUser] == 1) {        
        $collaboratorAccess = $this->config->item('collaboratorAccess');
        $collaboratorUserId = "collaborator".$useridPermission;
        if (strpos($collaboratorAccess[$collaboratorUserId], 'manage') === FALSE) {
             $manageModule = FALSE;
        }
        if (strpos($collaboratorAccess[$collaboratorUserId], 'promote') === FALSE) {
             $promoteModule = FALSE;
        }    
        if (strpos($collaboratorAccess[$collaboratorUserId], 'report') === FALSE) {
             $reportModule = FALSE;
        }
        //echo $collaboratorAccessLevel = $collaboratorAccess[$collaboratorUserId]);
    }
}
?>
<div class="leftFixed">
    <div id='cssmenu'>
        <ul>
            <li class='has-sub'><a href='<?php echo getDashboardUrl(); ?>'>
                    <span class="icon icon-event menuLeft"></span><span>Organizer View</span></a>
            </li>
            <?php if ($manageModule) {?>
            <li class='has-sub'><a href='<?php echo commonHelperGetPageUrl('dashboard-eventhome', $this->input->get('eventId')); ?>' <?php echo $this->uri->segment(2) == 'home' ? ' class="currentPage"' : '';?> ><span class="icon icon2-dashboard menuLeft"></span><span>Dashboard</span></a>
            </li>
            <?php } ?>
            <?php if ($manageModule) {?>
            <li class='has-sub'><a href='javascript:void(0);' <?php echo ($this->uri->segment(2) == 'configure' || $this->uri->segment(2) == 'collaborator') ? ' class="currentPage"' : '';?>><span class="icon icon-configer menuLeft"></span><span>Manage</span></a>
                <ul <?php echo ($this->uri->segment(2) == 'configure' || $this->uri->segment(2) == 'collaborator') ? ' id="currentMenu" style="display: block"' : ''; ?>>
<!--                    <li <?php //echo ($this->uri->segment(3) == 'ticketOptions' ||$this->uri->segment(3) == 'ticketWidget'||$this->uri->segment(3) == 'paymentMode') ? ' id="currentMenu"   style="display: block"' : ''; ?>><a href="#" id="settings">Event Settings</span></a>
                        <ul id="settingsOpctions" style="display:none;" <?php // if($this->uri->segment(3) == 'ticketWidget' || $this->uri->segment(3) == 'ticketOptions' ||$this->uri->segment(3) == 'paymentMode') { echo ' class="settings" ';} ?> >
                        </ul>
                    </li>-->
                    <?php if($isCurrentEvent) { ?>
                        <li><a href="<?php echo commonHelperGetPageUrl('edit-event', $this->input->get('eventId')); ?>" target="_blank">Edit</a></li> 
                    <?php } ?>
                    <li><a href="<?php echo commonHelperGetPageUrl('event-preview','','?view=preview&eventId='. $this->input->get('eventId')); ?>" target="_blank">Preview</a></li> 
                    <li><a href="<?php echo commonHelperGetPageUrl('dashboard-ticketwidget', $this->input->get('eventId')); ?>" <?php echo $this->uri->segment(3) == 'ticketWidget' ? ' class="currentsubLink"' : ''; ?>>Ticket Widget</a></li> 
                    <li><a href="<?php echo commonHelperGetPageUrl('dashboard-ticketOption', $this->input->get('eventId')); ?>"  <?php echo $this->uri->segment(3) == 'ticketOptions' ? ' class="currentsubLink"' : ''; ?>>Ticket Options</a></li>  
                    <li><a href="<?php echo commonHelperGetPageUrl('dashboard-paymentMode', $this->input->get('eventId')); ?>"  <?php echo $this->uri->segment(3) == 'paymentMode' ? ' class="currentsubLink"' : ''; ?>>Payment Modes</a></li>  
                       
                    
                    <li><a href="<?php echo commonHelperGetPageUrl('dashboard-customField', $this->input->get('eventId')); ?>"  <?php echo $this->uri->segment(3) == 'customFields' ? ' class="currentsubLink"' : ''; ?>>Custom Fields</a></li>
                    <li><a href="<?php echo commonHelperGetPageUrl('dashboard-webhook', $this->input->get('eventId')); ?>"  <?php echo $this->uri->segment(3) == 'webhookUrl' ? ' class="currentsubLink"' : ''; ?> >Web Hook URL</a></li>
                    <li><a href="<?php echo commonHelperGetPageUrl('dashboard-gallery', $this->input->get('eventId')); ?>"  <?php echo $this->uri->segment(3) == 'gallery' ? ' class="currentsubLink"' : ''; ?> >Gallery</a></li>
                    <li><a href="<?php echo commonHelperGetPageUrl('dashboard-seo', $this->input->get('eventId')); ?>"  <?php echo $this->uri->segment(3) == 'seo' ? ' class="currentsubLink"' : ''; ?> >SEO</a></li>
                    <li><a href="<?php echo commonHelperGetPageUrl('dashboard-emailAttendees', $this->input->get('eventId')); ?>"  <?php echo $this->uri->segment(3) == 'emailAttendees' ? ' class="currentsubLink"' : ''; ?>>Email Attendees</a></li>
                    <li><a href="<?php echo commonHelperGetPageUrl('dashboard-contactinfo', $this->input->get('eventId')); ?>"  <?php echo $this->uri->segment(3) == 'contactInfo' ? ' class="currentsubLink"' : ''; ?> >Contact Information</a></li>
                    <li><a href="<?php echo commonHelperGetPageUrl('dashboard-tnc', $this->input->get('eventId')); ?>"  <?php echo $this->uri->segment(3) == 'tnc' ? ' class="currentsubLink"' : ''; ?>>Terms & Conditions</a></li>
                    
                   <?php  if ($collaboratorEventAccess[$collabEventUser] != 1){ ?>
                     <li><a href="<?php echo commonHelperGetPageUrl('dashboard-list-collaborator', $this->input->get('eventId')); ?>" <?php echo $this->uri->segment(3) == 'collaboratorlist' || $this->uri->segment(3) == 'addcollaborator' || $this->uri->segment(3) == 'editcollaborator'? ' class="currentsubLink"' : ''; ?> >Collaborator</a></li>
                    <?php } ?>
			<li><a href="<?php echo commonHelperGetPageUrl('dashboard-deleteRequest', $this->input->get('eventId')); ?>"  <?php echo $this->uri->segment(3) == 'deleteRequest' ? ' class="currentsubLink"' : ''; ?>>Delete Request</a></li>		
                </ul>
            </li>
            <?php } ?>
            <?php if ($promoteModule) {?>
            <li class='has-sub'><a href='javascript:void(0);' <?php echo ($this->uri->segment(2) == 'promote') ? ' class="currentPage"' : ''; ?>><span class="icon icon-promote menuLeft"></span><span>Promote</span></a>
                <ul <?php echo ($this->uri->segment(2) == 'promote') ? ' id="currentMenu" style="display: block"' : ''; ?>>
                    <li><a href="<?php echo commonHelperGetPageUrl('dashboard-discount', $this->input->get('eventId')); ?>" <?php echo $this->uri->segment(3) == 'discount' ? ' class="currentsubLink"' : ''; ?> >Discount</a></li>
                    <li><a href="<?php echo commonHelperGetPageUrl('dashboard-bulkdiscount', $this->input->get('eventId')); ?>" <?php echo $this->uri->segment(3) == 'bulkDiscount' ? ' class="currentsubLink"' : ''; ?> >Bulk Discounts</a></li>

                    <li><a href="<?php echo commonHelperGetPageUrl('dashboard-viralticket', $this->input->get('eventId')); ?>" <?php echo $this->uri->segment(3) == 'viralTicket' ? ' class="currentsubLink"' : ''; ?> >Viral Ticketing</a></li>
                    <li><a href="<?php echo commonHelperGetPageUrl('dashboard-affliate', $this->input->get('eventId')); ?>" <?php echo $this->uri->segment(3) == 'affiliate' ? ' class="currentsubLink"' : ''; ?>  >Affiliate Marketing</a></li>
                    <li><a href="<?php echo commonHelperGetPageUrl('dashboard-offlinepromoter', $this->input->get('eventId')); ?>" <?php echo $this->uri->segment(3) == 'offlinePromoterlist' || $this->uri->segment(3) == 'addOfflinePromoter' || $this->uri->segment(3) == 'editOfflinePromoter' ? ' class="currentsubLink"' : ''; ?>  >Offline Promoter</a></li>


                     <li><a href="<?php echo commonHelperGetPageUrl('dashboard-guestlist-booking', $this->input->get('eventId')); ?>" <?php echo $this->uri->segment(3) == 'guestListBooking' ? ' class="currentsubLink"' : ''; ?> >Guest List Booking</a></li>

                </ul>
            </li>
            <?php } ?>
            <?php if ($reportModule) {?>
            <li class='has-sub' ><a href='javascript:void(0);' <?php echo ($this->uri->segment(2) == 'reports' || $this->uri->segment(2) == 'saleseffort') ? ' class="currentPage"' : ''; ?>><span class="icon icon-report menuLeft"></span><span>Reports</span></a>
                <ul <?php echo ($this->uri->segment(2) == 'reports' || $this->uri->segment(2) == 'saleseffort') ? ' id="currentMenu" style="display: block"' : ''; ?>>
                    <li><a href="<?php echo commonHelperGetPageUrl('dashboard-transaction-report', $this->input->get('eventId') . '&summary&all&1'); ?>" <?php echo $this->uri->segment(2) == 'reports' ? ' class="currentsubLink"' : ''; ?>>Transactions</a></li>
                    <li><a href="<?php echo commonHelperGetPageUrl('dashboard-saleseffort-report', $this->input->get('eventId')); ?>" <?php echo $this->uri->segment(2) == 'saleseffort' ? ' class="currentsubLink"' : ''; ?>>Sales Effort</a></li>
                </ul>
            </li>
            <?php } ?>
            <?php if ($manageModule) {?>
            <li class='has-sub'><a href='javascript:void(0);' <?php echo $this->uri->segment(2) == 'payment' ? ' class="currentPage"' : '';?> ><span class="icon icon-payment menuLeft"></span><span>Payment</span></a>
                <ul <?php echo ($this->uri->segment(2) == 'payment') ? ' id="currentMenu" style="display: block"' : ''; ?>>
                    <li><a href="<?php echo commonHelperGetPageUrl('dashboard-refund', $this->input->get('eventId') . '&detail&refund'); ?>" <?php echo $this->uri->segment(3) == 'refund' ? ' class="currentsubLink"' : ''; ?>>Refund</a></li>
                    <li><a href="<?php echo commonHelperGetPageUrl('dashboard-payment-receipts', $this->input->get('eventId')); ?>" <?php echo $this->uri->segment(3) == 'receipts' ? ' class="currentsubLink"' : ''; ?>>Payment Receipts</a></li>
                    
                </ul>
            </li>
            <?php } ?>
        </ul>
    </div>
    <div class="sidebar-full-height-bg"></div>
</div>
