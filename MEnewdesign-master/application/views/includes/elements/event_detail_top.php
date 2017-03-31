<?php 
$eventId =$eventData['id'];
$configCustomDatemsg = json_decode(CONFIGCUSTOMDATEMSG,true);
$configCustomTimemsg =  json_decode(CONFIGCUSTOMTIMEMSG,true);
$configspecialTickets = json_decode(SPECIALTICKETS,true);
$customValidationEventIds = json_decode(CUSTOMVALIDATIONEVENTIDS,true);
$configspecialTickets = isset($configspecialTickets[$eventId])?$configspecialTickets[$eventId]:array();

?>
<div style="visibility: hidden; display: none; width: 1170px; height: 128px; margin: 0px; float: none; position: static; top: auto; right: auto; bottom: auto; left: auto;"></div>
        <div id="event_div" class="" style="z-index: 99;">
            <div class="row <?php echo str_replace(" ", "", strtolower($eventData['categoryName'])); ?>">
                <div class="img_below_cont ">
                    <h1><?php echo isset($eventData['title'])?$eventData['title']:'';?></h1>
                    <div class="sub_links"><span class="icon-calender"></span>                       
                      <!--  <span>// $convertedStartTime=convertTime($eventData['startDate'],$eventData['location']['timeZoneName'],TRUE);$convertedEndTime=convertTime($eventData['endDate'],$eventData['location']['timeZoneName'],TRUE);?> -->
                               <?php 
                             if(isset($configCustomDatemsg[$eventId]) && !isset($configCustomTimemsg[$eventId])){
                             	echo $configCustomDatemsg[$eventId]." | ".allTimeFormats($eventData['startDate'],4).' to '.allTimeFormats($eventData['endDate'],4);
                            }else if(isset($configCustomTimemsg[$eventId]) && !isset($configCustomDatemsg[$eventId])){
                             	echo allTimeFormats($eventData['startDate'],3);?><?php if (allTimeFormats($eventData['startDate'],9) != allTimeFormats($eventData['endDate'],9))  { echo " - ". allTimeFormats($eventData['endDate'],3);} echo " | ".$configCustomTimemsg[$eventId];
                            }else if(isset($configCustomTimemsg[$eventId]) && isset($configCustomDatemsg[$eventId])){
                             	echo $configCustomDatemsg[$eventId]." | ".$configCustomTimemsg[$eventId];
                            }else{
                            	echo allTimeFormats($eventData['startDate'],3);?><?php if (allTimeFormats($eventData['startDate'],9) != allTimeFormats($eventData['endDate'],9))  { echo " - ". allTimeFormats($eventData['endDate'],3);} echo " | ".allTimeFormats($eventData['startDate'],4).' to '.allTimeFormats($eventData['endDate'],4);
                            }  ?>
                            </span></div>
                    <div class="sub_links"> <span class="icon-google_map_icon"></span><span><?php if(isset($eventData['eventMode']) && $eventData['eventMode'] == 1){ echo "Webinar"; } else { echo $eventData['fullAddress']; } ?></span></div>
                </div>
                <div class="Rlist">
                    <ul>
                        <?php if(isset($eventData['description']) && strlen(stripslashes($eventData['description'])) > 990 ) { $description = substr($eventData['description'],0,900)."..."; ?><?php } else { $description = $eventData['description']; } $description = urlencode(strip_tags($description));?>
                            <li class="<?php echo str_replace(" ", "", strtolower($eventData['categoryName']));?>"><a href="http://www.google.com/calendar/event?action=TEMPLATE&text=<?php echo urlencode($eventData['title']);?>&dates=<?php echo reminderDate($eventData['startDate']).'T'.date('His',strtotime($eventData['startDate']));?>/<?php echo reminderDate($eventData['endDate']).'T'.date('His',strtotime($eventData['endDate']));?>&location=<?php echo $eventData['fullAddress'];?>&details=<?php echo addslashes($description); ?>&trp=false&sprop=&sprop=name:" target="_blank" rel="nofollow"><span class="icon-alaram_icon"></span>Set Reminder</a></li>
                        <?php if(!empty($eventData['fullAddress'])){?>
                            <li class="<?php echo str_replace(" ", "", strtolower($eventData['categoryName']));?>">
								<a href="https://maps.google.com/maps?saddr=&daddr=<?php echo urlencode($eventData['fullAddress']);?>" target="_blank">
									<span class="icon-google_map_icon"></span>
									Get Directions
								</a>
							</li>
                        <?php }?>
                    </ul>
                </div>
            </div>

        </div>
        