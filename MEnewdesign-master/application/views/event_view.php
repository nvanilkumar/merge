<!--Event_banner-->
<div class="container event_detail_main">
    <div class="col-sm-12 col-xs-12 header_img">

        <img src="<?php echo $eventData['bannerPath'];?>" alt="<?php echo $eventData['title'];?>" title="<?php echo $eventData['title'];?>" onError="this.src='<?php echo  $eventData['defaultBannerImage'];?>'">
        <?php include_once('includes/elements/event_detail_top.php');?>

        <?php $limitSingleTicketType = $eventData['eventDetails']['limitSingleTicketType'];?>
        <input type="hidden" name="limitSingleTicketType" id="limitSingleTicketType" value="<?php echo $limitSingleTicketType;?>">
        <input type="hidden" name="pageType" id="pageType" value="<?php echo $pageType;?>">
 
        <div class="event_toggle">
            
            <div class="row eventDetails" id="event_tickets">
                <h3 class="get_tickts"><?php echo count($ticketDetails)>0?'Get your Tickets Now!':'';?></h3>
                <?php include_once('includes/elements/event_tickets.php');?>
                
                <div class="col-sm-4 col-xs-12 col-md-3 col-md-offset-1">
                    <div class="eventid"><a>Event ID : <?php echo $eventData['id'];?> </a></div>
                    <div class="invite_subcont">
                        <div class="inviteFriend">
                            <h4><?php if(isset($globalPromoterCode) && !empty($globalPromoterCode)){?>Affiliate Promoter Link<?php }else{?>Invite friends<?php } ?></h4>
                            <?php
                            
                                $tweet=  $title = $eventData['title'];
                                $linkToShare = $eventData['eventUrl'];
                                if(isset($referralCode) && !empty($referralCode)){
                                    $linkToShare = $eventData['eventUrl'].'?reffCode='.$referralCode;
                                }
                                if(isset($promoterCode) && !empty($promoterCode)){
                                    $linkToShare = $eventData['eventUrl'].'?ucode='.$promoterCode;
                                }
                                if(isset($globalPromoterCode) && !empty($globalPromoterCode)){
                                    $linkToShare = $eventData['eventUrl'].'?acode='.$globalPromoterCode;
                                }
                                $bitlyUrl=getTinyUrl($linkToShare);
                            ?>
                              <span class="invitefirends">
                                  <i class="icon1 icon1-invitefriend"></i>
                              </span>
                            <span class=""> 
                                <a href="http://www.facebook.com/share.php?u=<?= urlencode($linkToShare) ?>&title=Meraevents -<?= $title ?>"
                                   onClick="javascript: cGA('/event-share-facebook'); return fbs_click('<?= $linkToShare ?>', 'Meraevents - <?= $title ?> ')" 
                                   target="_blank"> 
                                    <i class="icon1 icon1-facebook"></i></a> 
                            </span>

                            <span class=""> 
                                <a onClick="javascript: cGA('/event-share-twitter');" 
                                   href="http://twitter.com/home?status=<?= substr($tweet, 0, 100) ?>...+<?= urlencode($bitlyUrl) ?>" 
                                   target="_blank" class="nounderline social"> 
                                    <i class="icon1 icon1-twitter"></i>
                                </a>
                            </span>
                             <span class=""> 
                                <a onClick="javascript: cGA('/event-share-linkedin');" 
                                   href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo urlencode($linkToShare) ?>&amp;title=<?php echo $tweet ?>&amp;summary=<?php echo $eventData['fullAddress'] ?>&amp;source=Meraevents" 
                                   target="_blank" class="nounderline"> 
                                    <i class="icon1 icon1-linkedin"></i></a>
                            </span>
                            
                            <span class="">
                                <a href="https://plus.google.com/share?url=<?= urlencode($linkToShare) ?>" target="_blank" >
                                    <i class="icon1 icon1-google-plus"></i>   </a> 
                            </span>
                           
                        </div>
                        <?php if($this->customsession->getData('userId')>0 && isset($globalPromoterCode)){?>
                                <div class="Affiliate-Promote-Link" style="margin-top:0px;">
                                    <!--<h3>Affiliate Promoter Link</h3>-->
                                    <div class="Affiliate-Promote-Copy">
                                    <input type="text" style="" value="<?php echo $eventData['eventUrl'].'?acode='.$globalPromoterCode;?>" id="affiliate_link" readonly/>
                                    <input type="button" id="copyGlobalCodeButton" value="Copy Link">
                                    </div>  
                                </div>
                        <?php }?>
                        <!--<h4 style="margin:0 0 10px 0;"><a href="<?php //echo commonHelperGetPageUrl("contactUs"); ?>" style="color:#464646;" target="_blank">Contact Us</a></h4>-->

                        <h4 style="margin:0 0 10px 0;"><a href="<?php echo commonHelperGetPageUrl("contactUs"); ?>" style="color:#464646;" target="_blank"><span class="icon2-envelope-o"> </span> Contact Us</a></h4>

                        <!--<div class="event_sub_sub_cont">
                        <p>@ Contact Us</a></p>
                                <p><?php //echo GENERAL_INQUIRY_MOBILE; ?></p>
                                <p><?php //echo GENERAL_INQUIRY_EMAIL; ?></p>
                        </div>-->
                        
                        
                       
                        
                        <?php if ($eventData['eventDetails']['contactdisplay'] == 1) { ?>            
                            <p><?php if($eventData['eventDetails']['contactdetails'] != "") {?>   <p>&nbsp;</p><h4>Tickets</h4><?php  echo $eventData['eventDetails']['contactdetails']; } ?>
                            </p>
                        <?php } ?>
                        
                        <div class="event_sub_sub_cont">
                             <p><b>Page Views : <?php echo $eventData['eventDetails']['viewcount']; ?></b></p>
                        <?php
						if(strlen($eventData['eventDetails']['promotionaltext']) > 0){
							?>
                            <!--event specific right side banner-->
                            <p><?php echo $eventData['eventDetails']['promotionaltext']; ?></p>
                            <?php
						}
						?>
                             
                            </div>
                           
                    </div>
                </div>
           
            </div>
            <?php if (count($ticketDetails) > 0) { ?>
            <div class="col-sm-12 eventDetails" id="event_about">
                <h3>About The Event</h3>
                   <div>
                       <p><?php echo stripslashes($eventData['description']); ?></p>
                </div>
            </div>
            <?php } ?>
            <?php include_once('includes/eventgallery.php');?>          
            <?php if ($eventData['eventDetails']['tnctype'] == 'organizer' && !empty($eventData['eventDetails']['organizertnc'])) { ?>
            <hr>    
            <div class="col-sm-12 eventDetails" id="event_terms">
                    <h3>Terms &amp; Conditions</h3><a id="tnc"></a>
                    <?php echo stripslashes($eventData['eventDetails']['organizertnc']); ?>
                </div>
                <?php } ?>
                <?php if ($eventData['eventDetails']['tnctype'] == 'meraevents' && !empty($eventData['eventDetails']['meraeventstnc'])) {?>
                    <div class="col-sm-12 eventDetails" id="event_terms">
                        <h3>Terms &amp; Condition</h3><a id="tnc"></a>                       
                    <?php echo stripslashes($eventData['eventDetails']['meraeventstnc']); ?>  </div><?php } ?>    

            <?php if($eventData['eventDetails']['organizertnc'] != '') { ?>
                <div class="col-sm-12 eventDetails" id="event_terms" style="display:none;">
                    <h3>Terms &amp; Conditions</h3>
                    <?php echo $eventData['eventDetails']['organizertnc'];?>
                </div>
            <?php } ?>
            

        </div>
    </div>
</div>
<!--end of banner-->
<script type="application/ld+json">
[{
	"@context":"http://schema.org",
	"@type":"Event",
	"name":"<?php echo $eventData['title'];?>",
	"image":"<?php echo $eventData['bannerPath'];?>",
	"url":"<?php echo $eventData['eventUrl'];?>",
	"startDate":"<?php echo appendTimeZone($eventData['startDate'],$eventData['location']['timeZoneName'],TRUE);?>",	
        "doorTime":"<?php  echo seoFormat($eventData['startDate']);?>",
        "location":{
    		"@type":"Place",
    		"name":"<?php echo $eventData['location']['venueName'];?>",
    		"address":"<?php echo $eventData['fullAddress'];?>"
        },
	"offers":[
                <?php $rows=1; foreach($ticketDetails as $ticket){?>
                        {
                            "@type":"Offer",
                            "name": "<?php echo $ticket['name'];?>", 
                            "price":"<?php echo $ticket['price'];?>",
                            "priceCurrency":"<?php echo $ticket['currencyCode'];?>", 
                            "availability": "<?php if($ticket['soldout']==0){echo 'Available';}else{echo 'Sold Out';}?>", 
                            "url":"<?php echo $eventData['eventUrl'];?>"
                        }
                    <?php if($rows<count($ticketDetails)){ echo ',';}$rows++;
                }?>	
        ]}]	
</script>
 

<div class="modal fade signup_popup" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-center">
        <div class="modal-content">
            <div class="awesome_container">
                <h3 class="icon-icon-bookmark_icon"></h3>
                <h4>Awesome!</h4>
                <h4>Event saved successfully!</h4>

                <div style="width: 15%; margin: 0 auto">
                    <button type="submit" id="okId" class="btn btn-primary borderGrey collapsed" style="padding: 10px 20px; margin-bottom: 20px">oK</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once('includes/email_invite.php');?>
<script>
	var api_searchSearchEventAutocomplete = "<?php echo commonHelperGetPageUrl('api_searchSearchEventAutocomplete')?>";
	var api_getTicketCalculation ='<?php echo commonHelperGetPageUrl('api_getTicketCalculation');?>';
	var api_bookNow = '<?php echo commonHelperGetPageUrl('api_bookNow');?>';
	var api_eventMailInvitations = "<?php echo commonHelperGetPageUrl('api_eventMailInvitations')?>";
	var api_getProfileDropdown = "<?php echo commonHelperGetPageUrl('api_getProfileDropdown')?>";
</script>

<?php
    $googleanalyticsscripts = $eventData['eventDetails']['googleanalyticsscripts'];
    if(isset($googleanalyticsscripts) && $googleanalyticsscripts != '') {
        echo $googleanalyticsscripts;
    }
?>
