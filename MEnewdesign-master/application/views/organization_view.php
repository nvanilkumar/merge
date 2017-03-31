<div class="container event_detail_main">
	<div class="col-sm-12 col-xs-12 header_img">
	<?php if(strlen($organizationDetails['bannerPath'])>0){?>
		<img src="<?php echo $organizationDetails['bannerPath'];?>" alt="<?php echo $organizationDetails['name'];?>" title="<?php echo $organizationDetails['name'];?>">
	<?php }?>
		<div id="event_div" class="" style="z-index: 99;">
            <div class="row orgbgcolor">
                <div class="img_below_cont ">
                    <h2><?php echo $organizationDetails['name']?></h2>
                    <div class="sub_links">
                    <!-- <span class="icon-calender"></span>
                      
                            Tuesday, 8th Dec 2015 - Thursday, 31st Dec 2015 | 01:00 AM to 06:00 PM<br> -->
                           <span class="icon2-eye"></span> Page Views:<span><?php echo $organizationDetails['viewcount'];?></span>
                            </div>
 
                    <!-- <div class="sub_links orgcontact_sublinks"></div> -->  
                </div>
                  <div class="Org_Rlist orgcontact_sublinks">
                      <?php
                             $tweet=  $title = $organizationDetails['name']; 
                                $linkToShare = current_url();
                                $bitlyUrl=getTinyUrl($linkToShare);
                            ?>
	                       <span class="orgContact"> 
	                       	<i class="icon1 icon1-invitefriend"></i>
	                       </span>
                           <span class=""> <a
							href="http://www.facebook.com/share.php?u=<?= urlencode($linkToShare) ?>&title=Meraevents -<?= $title ?>"
							onClick="javascript: cGA('/event-share-facebook'); return fbs_click('<?= $linkToShare ?>', 'Meraevents - <?= $title ?> ')"
							target="_blank"> <i class="icon1 icon1-facebook"></i></a>
						</span> 
						<span class=""> <a
							onClick="javascript: cGA('/event-share-twitter');"
							href="http://twitter.com/home?status=<?= substr($tweet, 0, 100) ?>...+<?= urlencode($bitlyUrl) ?>"
							target="_blank" class="nounderline social"> <i
								class="icon1 icon1-twitter"></i>
						</a>
						</span> 
						<span class=""> <a
							onClick="javascript: cGA('/event-share-linkedin');"
							href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo urlencode($linkToShare) ?>&amp;title=<?php echo $tweet ?>&amp;summary=<?php echo $eventDetails['fullAddress'] ?>&amp;source=Meraevents"
							target="_blank" class="nounderline"> <i
								class="icon1 icon1-linkedin"></i></a>
						</span> 
						<span class=""> <a
							href="https://plus.google.com/share?url=<?= urlencode($linkToShare) ?>"
							target="_blank"> <i class="icon1 icon1-google-plus"></i>
						</a>
						</span>
                </div>  
            </div>

        </div>	
		
		
	</div>
	<div class="row">
	<?php if(strlen($organizationDetails['information'])>0){?>
<h3 class="get_tickts">About <?php echo $organizationDetails['name']?></h3>
		<p>
		<?php echo $organizationDetails['information'];?>
		</p> 
<?php }?>
</div>
	<div class="row">
	<!-- <div class="col-sm-12"> -->
		<div class="event-tabs orgeventtabs">
			<a href="javascript:;" id="upcomingeventstab" class="eventtypes <?php if($pageType == 'upcoming'){echo 'eventsactive';}?>" pagetype="upcoming">
			<h4 class="subHeadingFont" id="eventCaption"><span>Upcoming Events</span></h4>
				</a>
				<a href="javascript:;" id="pasteventstab" class="eventtypes <?php if($pageType == 'past'){echo 'eventsactive';}?>" pagetype="past">
					<h4 class="subHeadingFont" id="eventCaption">
					<span id="past-events-tab">Past Events</span></h4>
				</a>
				<!-- <div class="searchBox">
                        <div class="search-container">
                            <input class="search searchExpand icon-search" id="dashboard_search" type="search" placeholder="Search" value="" onsearch="OnSearch(this)">
                            <a class="search icon-search cleareventsearch" id="searchicon"></a> 
                        </div>

                    </div> -->
				</div>
			<input type="hidden" id="page" value="0" />
			<input type="hidden" id="pageType" value="<?php echo $pageType;?>" />
			<input type="hidden" id="organization" value="<?php echo $organizationDetails['id'];?>" />
			<h4 id="no-events" class="nomorevents" style="display:none;"> No Events Found</h4>
			<ul id="upcoming_past_events" class="eventThumbs __web-inspector-hide-shortcut__">

			<?php if(!empty($eventsData)){
				foreach($eventsData as $key => $eventDetails){?>
				<li class="col-xs-12 col-sm-6 col-md-4 col-lg-4 thumbBlock"	itemscope="" itemtype="http://schema.org/Event">
				<a	href="<?php echo $eventDetails['url'];?>"
					class="thumbnail">
						<div class="eventImg">
							<img src="<?php echo $eventDetails['logoPath']?>"
								width="" height=""
								alt="<?php echo $eventDetails['title']?> - <?php echo $eventDetails['startDateTime'];?>"
								title="<?php echo $eventDetails['title']?> - <?php echo $eventDetails['startDateTime'];?>"
								errimg="<?php echo $eventDetails['defaultlogoPath'];?>" onerror="setimage(this)" >
						</div>
						<h6>
							<span class="eveHeadWrap"><?php  echo $eventDetails['title']?> - <?php echo $eventDetails['startDateTime'];?> </span>
							<!--            <span id="saveEvent" class="icon-fave"></span>-->
						</h6>
						<div class="info">
							<span content="<?php echo $eventDetails['startDateTime'] ;?>"><?php echo $eventDetails['startDateTime'];?></span>
						</div>
						<div class="overlay">
							<div class="overlayButt">
								<div class="overlaySocial">
									<span class="icon-fb"></span> <span class="icon-tweet"></span>
									<span class="icon-google"></span>
								</div>
							</div>
						</div>
				</a>
				 <a href="<?php echo $eventDetails['url'];?>" class="category"> <span class="icon1-training coltraining"></span>
						<span class="catName"><em><?php echo $eventDetails['categoryName'];?></em></span>
				</a>
			</li>
			<?php }}else{?>
					<h4 id="no-events" > No Events Found</h4>
			
			<?php }?>
			</ul>
<div class="alignCenter" style="clear:both;display:block;">
<a id="viewMore" class="btn btn-primary borderGrey collapsed" <?php if($totalCount <= ORGANIZATION_EVENTS_DISPLAY_LIMIT ){?>style="display:none;" <?php }?>>View More</a>
</div>
	<!-- </div> -->
	
			<!-- <div class="col-sm-4 col-xs-12 col-md-3 col-md-offset-1">
			
				<div class="invite_subcont">
                                    <h1>Contact The Organizer</h1>
                                    <span class="orgContact"> <i
                                                        class="icon1 icon1-invitefriend"></i>
                                                </span>
                                    <h1>Views:<span><?php echo $organizationDetails['viewcount'];?></span></h1>
					<div class="inviteFriend">
						<h1>Social Share</h1>
                            <?php
                             $tweet=  $title = $organizationDetails['name']; 
                                $linkToShare = current_url();
                                $bitlyUrl=getTinyUrl($linkToShare);
                            ?>
                                                 <span class=""> <a
							href="http://www.facebook.com/share.php?u=<?= urlencode($linkToShare) ?>&title=Meraevents -<?= $title ?>"
							onClick="javascript: cGA('/event-share-facebook'); return fbs_click('<?= $linkToShare ?>', 'Meraevents - <?= $title ?> ')"
							target="_blank"> <i class="icon1 icon1-facebook"></i></a>
						</span> <span class=""> <a
							onClick="javascript: cGA('/event-share-twitter');"
							href="http://twitter.com/home?status=<?= substr($tweet, 0, 100) ?>...+<?= urlencode($bitlyUrl) ?>"
							target="_blank" class="nounderline social"> <i
								class="icon1 icon1-twitter"></i>
						</a>
						</span> <span class=""> <a
							onClick="javascript: cGA('/event-share-linkedin');"
							href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo urlencode($linkToShare) ?>&amp;title=<?php echo $tweet ?>&amp;summary=<?php echo $eventDetails['fullAddress'] ?>&amp;source=Meraevents"
							target="_blank" class="nounderline"> <i
								class="icon1 icon1-linkedin"></i></a>
						</span> <span class=""> <a
							href="https://plus.google.com/share?url=<?= urlencode($linkToShare) ?>"
							target="_blank"> <i class="icon1 icon1-google-plus"></i>
						</a>
						</span>

					</div>
					<h1>Need Support?</h1>
					<div class="event_sub_sub_cont">
						<h2><?php echo GENERAL_INQUIRY_MOBILE; ?></h2>
						<h2><?php echo GENERAL_INQUIRY_EMAIL; ?></h2>
					</div>

				</div>
			</div> -->

		</div>

		<div class="col-sm-12 eventDetails" id="event_about"></div>
		<!--end of view more-->

	</div>
<?php include_once('includes/organizer_contact.php');?>
</div>
<script>
function setimage(e){
	e.src= $(e).attr('errimg');
}
                            
var api_organizationEvents = "<?php echo commonHelperGetPageUrl('api_organizationEvents')?>";
var api_getOrgContacts = "<?php echo commonHelperGetPageUrl('api_organizerContactEmails')?>";
 </script>

