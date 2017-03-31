<?php
$eventCount = count($eventList);
if ($pageType == "upcoming") {
    $currentClass = "selected";
    $pastClass = "";
   $totalEventCount= isset($totalEventCount)?$totalEventCount:0;
    $currentTotal = "( <span id='totalcount'>" .$totalEventCount. "</span> )";
    $pastTotal = "";
    $message = $this->customsession->getData('message');
    $publishedLink = $this->customsession->getData('eventLink');
} else {
    $currentClass = "";
    $pastClass = "selected";
    $totalEventCount= isset($totalEventCount)?$totalEventCount:0;
    $pastTotal = "( <span id='totalcount'>" . $totalEventCount . " </span>)";
    $currentTotal = "";
}

?>
<div class="rightArea">
  <div class="searchBox">
                        <div class="search-container">
                            <input class="search icon-search"
                                   id="dashboard_search" type="search"
                                   placeholder="Search" value="<?php echo $searchword;?>"  onsearch="OnSearch(this)">
                            <a class="search icon-search cleareventsearch" id="searchicon"></a> 
                        </div>

                    </div>
    <input type="hidden" id="page" name="page" value="<?php echo $page;?>"/>
    <input type="hidden" id="pageType" name="pageType" value="<?php echo $pageType;?>"/>
    <h3>Dashboard</h3>
    <div class="dbalert db-success hide" id="publishMsg" <?php if (isset($message) && strlen($message)>0) { ?>style="display: block;" <?php } else { ?> style="display: none;"<?php } ?>><?php echo $message; if(isset($publishedLink) && !empty($publishedLink)){ ?> <a target="_blank" style="color:#ba36a6;" href="<?php echo $publishedLink; ?>">  Click Here  </a> <?php } $this->customsession->unSetData('eventLink');$this->customsession->unSetData('message');?> </div>
    <div style='display:none' class="db-alert db-alert-danger" id="publishDateError"></div>
    <div>
        <ul class="tabs" data-persist="true">
            <li class="<?php echo $currentClass; ?>"><a href="<?php echo commonHelperGetPageUrl('dashboard-myevent'); ?>" class="eventtypes" >Upcoming Events <?php echo $currentTotal; ?></a></li>
            <li class="<?php echo $pastClass; ?>"><a href="<?php echo commonHelperGetPageUrl('dashboard-pastevent'); ?>" class="eventtypes" >Past Events <?php echo $pastTotal; ?></a></li>
            <li>
            
            
            </li>
        </ul>
        
        <div class="tabcontents">
                <h6 id='no-events' style="display: none"> There are <span id="eventscount"></span> events found for the searched keyword:<span id='searchkeyword'></span> </h6>
            <div id="upcoming-eventview">
                <?php
                if ($eventCount > 0) {
                    $uloop = 1;
                    $eventMonthName = "";

                    foreach ($eventList as $event) {

                        $eventMonthDiv = "";
                        $eventMonth = $event['eventMonth'];
                        $eventId = $event['eventId'];
                        $allAccess = true;
                        $manageURL = commonHelperGetPageUrl('dashboard-eventhome', $eventId);
                        $collaborativeEventIds = array_keys($collaborativeEventData);
                        if (in_array($eventId, $collaborativeEventIds) && $collaborativeEventData[$eventId] != 'manage') {
                            $allAccess = false;
                        }
                        if (in_array($eventId, $collaborativeEventIds) && $collaborativeEventData[$eventId] == 'promote') {
                            $manageURL = commonHelperGetPageUrl('dashboard-list-discount', $eventId);
                        } elseif (in_array($eventId, $collaborativeEventIds) && $collaborativeEventData[$eventId] == 'report') {
                            $manageURL = commonHelperGetPageUrl('dashboard-transaction-report', $eventId . '&summary&all&1');
                        }
                        $reportsURL = commonHelperGetPageUrl('dashboard-transaction-report', $eventId . '&summary&all&1');
                        if (in_array($eventId, $collaborativeEventIds) && ($collaborativeEventData[$eventId] == 'promote')) {
                            $reportsURL = 'javascript:;';
                        }
                        $eventName = $event['eventName'];
                        $eventName = (strlen($eventName) > 30) ? substr($eventName, 0, 30) . "..." : $eventName;

                        $soldOutTickets = $event['soldOutTickets'];
                        $eventCityName = $event['eventCityName'];
                        $eventPublishStatus = "Publish";
                        $titleColorClass='fs-unpublished-event';
                        $eventunpublishStatus = "UnPublished";
                        if($event['eventStatus'] == 1 ){
                           $eventPublishStatus= "Unpublish";
                           $titleColorClass='';
                           $eventunpublishStatus="published";
                        }
                        $eventUrl = $event['url'];
                        $eventStartDate =  $event['eventStartDate'];
                        $eventEndDate =  $event['eventEndDate'];
                        $divClass = ($uloop % 2 == 0) ? "boxTeal" : "";
                        $divClass = ($uloop % 3 == 0) ? "boxLightTeal" : "";
                        if (strcmp($eventMonthName, $eventMonth) != 0) {
                            $eventMonthName = $eventMonth;
                            $eventMonthDiv = "<h6 class='".$eventMonth." event-months'>" . $eventMonth . "</h6>";
                             if ($uloop > 1){
                                echo '<div class="clearBoth"></div>'; 
                             }
                            $uloop = 1;
                        }
						if ($event['eventStatus'] == 1){
								// $pageSiteUrl .'event/'.$eventUrl . '?ucode=organizer'; 
								$Url = commonHelperGetPageUrl('event-detail','',$eventUrl.'?ucode=organizer');
								 }
							    else{	 
								$Url =  commonHelperGetPageUrl('event-preview','','?view=preview&eventId=' . $eventId); 
								}
                        ?>

                        <?php echo $eventMonthDiv; ?>
                        <div class="db_Eventbox <?php echo $titleColorClass;?>" eventid="<?php echo $eventId;?>" eventtitle= "<?php echo preg_replace('/[^A-Za-z0-9?!]/','',$eventName);?>" eventmon = "<?php echo $eventMonthName;?>">
                        	<h4 class="fs-event-title showeventbox">
                        		<a class="showeventbox" href="<?php
                                echo $Url;
								?>" target="_blank" title="<?php echo $event['eventName']; ?>">
                        			<?php echo $eventName; ?>
									<?php if ( in_array( $eventId, $collaborativeEventIds ) ) { echo '(Collaborative Event)'; } ?>
                        		</a>	
                        	</h4>
                        	<div class="fs-db_Eventbox-content showeventbox">
                            	<div class="fs-event-place-time showeventbox">
	                            	<div class="fs-event-start-date showeventbox"> 
	                            		<span class="icon2-clock-o showeventbox"></span>
	                            		<span class="showeventbox"><?php echo $eventStartDate." - ".$eventEndDate; ?></span> 
	                            	</div>
	                            	<div class="fs-event-city-name showeventbox">   
	                            		<span class="icon-locator showeventbox"></span>
	                            		<span class="showeventbox"><?php echo $eventCityName ;if($event['eventmode'] == 1){if(strlen($eventCityName)>0){echo " - ";}echo "Webinar";}; ?></span> 
	                            	</div>
                            	</div>
	                            <div class="db_Eventbox_section showeventbox">
	                                <div class="fs-ticket-management-buttons showeventbox">
                                    	<a class="ticketsId fs-ticket-preview-button fs-btn showeventbox" href="<?php echo $Url; ?>" target="_blank">
                                    		Event ID: <strong><?php echo $eventId; ?></strong>
                                    	</a>
                                    	<a class="fs-ticket-manage-button fs-btn showeventbox" href="<?php echo $manageURL; ?>">
                                    		<span class="icon-configer showeventbox"></span>Manage
                                    	</a>
                                            <input type='text' hidden="hidden" value='<?php echo $event['ActualeventStartDate'];?>' id="<?php echo $eventId . "eventStartDate"; ?>">
                                    	<?php if ($allAccess ) { 
                                    			if(strlen($currentClass)>0){
                                    		?>
		                                   <a class="fs-event-publish-button fs-btn showeventbox" href="javascript:void(0);" onclick="changeEventStatus('<?php echo commonHelperGetPageUrl('edit-event', $eventId); ?>','<?php echo $eventId; ?>');">
		                                    	<span class="icon-publish showeventbox"></span>
		                                    	<span class="showeventbox" id=<?php echo $eventId . "publishStatusText"; ?>><?php echo $eventPublishStatus; ?></span>
		                                    </a>
		                                    <?php }else{ ?>
                              						  <a class="fs-event-publish-button fs-btn showeventbox" href="javascript:void(0);" data-disabled="disabled" style="cursor: default !important;"><span class="icon-publish "></span><span id=<?php echo $eventId . "publishStatusText"; ?>><?php echo $eventunpublishStatus; ?></span></a>
                                		<?php }?> 
                                                    <a class="fs-ticket-manage-button fs-btn showeventbox" href="javascript:void(0);" onclick="copyEvent('<?php echo $eventId;?>')">
                                                        <span class="icon-manage showeventbox"></span>Copy
                                                    </a>
										<?php }?>
	                                    <!-- <h4><a href="<?php echo commonHelperGetPageUrl('event-preview','','?view=preview&eventId=' . $eventId);?>" target="_blank"><?php echo $eventName; ?></a></h4>
	                                    <?php if (in_array($eventId, $collaborativeEventIds)) { ?>
	                                        <h4>(Collaborative Event)</h4>
	                                    <?php } ?>  -->
	                                </div>
	                                <div class="ticketsBooked showeventbox">
	                                    <h5 class="showeventbox">Tickets Booked</h5>
	                                    <a class="showeventbox" href="<?php echo $reportsURL ?>"><?php echo $soldOutTickets; ?></a>
                                            <p style="padding: 25px 0;font-size: 16px;">Page Views : <?php echo $event['viewcount']; ?></p>
	                                </div>
	                            </div>
<!--	                            <div class="db_Eventbox_footer showeventbox" >
	                        		<p class="fs-promote-event showeventbox">
	                        			Few more days to go. Find more ways to <a href="#" class="fs-inline-btn fs-btn showeventbox">Promote Event</a>
		                    		</p>        
	                            </div>-->
                        	</div>    
                        </div>
					<?php 
                        $uloop++;
                    }
                }else{
                	echo '<h6 id="no-results"> There are no events in the dashboard </h6>';
                
                }
                ?>
            </div>
           <?php   if($totalEventCount>EVENTS_DISPLAY_LIMIT){?>
                    <a id="viewMore" class="fs-inline-btn fs-btn" >View More</a>
                <?php }?>
        </div>
    </div>
</div>
<div class="clearBoth"></div>
<script>
$(function(){
    $('.db_Eventbox').matchHeight();

});

</script>
