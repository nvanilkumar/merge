<!--Right Content Area Start-->
            <div class="rightArea">
               
                <div class="heading float-left">
                    <h2>Event Details </h2>
                </div>
               
                <div class="clearBoth"></div>
                <!--Data Section Start-->

                <div class="clear-fix">&nbsp;</div>
                
                <div class="refundSec discount">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Event Title</th>
                                <th>Event ID</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $eventDetails['title']; ?></td>
                               
                                <td><?php echo $eventId; ?></td>
                                <td><?php echo $startDateTime;?></td>
                                <td><?php echo $endDateTime;?></td>
               
                            </tr>
                            <?php $eventUrl = $eventDetails['eventUrl']."?ucode=".$promoterCode;
                                 $shareLink = urlencode($eventUrl);
                            ?>
                            <tr>
                              <td colspan="4" style="text-align:left;padding-left:40px">Promoter URL <span ><a href="<?php echo $eventUrl; ?>" target="_blank" style="color:orange !important"><?php echo $eventDetails['eventUrl']."?ucode=".$promoterCode; ?></a></span></td>
                            </tr>
                            <?php if($type == "current"){ ?>
                             <tr>
                              <td colspan="2" style="text-align:left;padding-left:40px;font-weight:600">SHARE THIS EVENT WITH </td>
                                  <td colspan="2" style="text-align:left;padding-left:40px">
                                       
	      <span><a target="_new" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo $shareLink;?>"><i class="icon1 icon1-facebook"></i></a></span>              
	      <span> <a target="_new" href="http://twitter.com/?status=Meraevents <?php echo $shareLink;?>"><i class="icon1 icon1-twitter"></i></a></span>
	       <span> <a target="_new" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $shareLink;?>title=Meraevents"><i class="icon1 icon1-linkedin"></i></a></span>
	      <span><a target="_new" href="https://plus.google.com/share?url=<?php echo $shareLink;?>"><i class="icon1 icon1-google-plus"></i></a></span>
                              </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div>
                        <a href="<?php echo commonHelperGetPageUrl('promoter-transaction-report', $eventId . '&' .'affiliate'.'&'. $promoterCode);?>" class="createBtn float-right" >View Report</a>
                      
                    <!--<button type="button" onclick="viewReports('<?php //echo $eventId,$promoterCode; ?>')" class="btn btn-grey float-right">View Report</button>-->
                    </div>
                    </div>
            </div>
   
