<div class="modal fade" id="InviteModal" tabindex="-1"
     role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 600px;
    margin: 0px auto;">
    <div class="modal-dialog-invite modal-dialog-center1">
        
        <div class="modal-content">
            
            <div class="popup_signup" style="height:auto; overflow:auto;">
                <div class="popup-closeholder"><button data-dismiss="modal" class="popup-close">X</button></div>
                <h3>Email to Friends and Colleagues</h3>               
                <h4 class="subject">You are invited to attend <?php echo $eventData['title'];?></h4> 
                <hr></hr>

                <form class="invitefriend_form form1" method="post" action="#" name="invitationFriends" id="invitationFriends">
                   <input type="hidden" value="<?php echo $eventData['id'];?>" name="EventId" id="EventId"/>
                   <input type="hidden" value="<?php echo $eventData['title'];?>" name="EventName" id="EventName"/>
                   <input type="hidden" value="<?php echo $linkToShare;?>" name="EventUrl" id="EventUrl"/>
                   <input type="hidden" value="<?php echo isset($venueName)?$venueName:$eventData['fullAddress'];?>" name="FullAddress" id="FullAddress"/>
                   <input type="hidden" value="<?php echo convertDate($eventData['startDate']); if (convertDate($eventData['startDate']) != convertDate($eventData['endDate'])){ echo " - ".convertDate($eventData['endDate']); } ?>" 
                       name="DateTime" id="DateTime" value="" />
                        <input type="hidden"  name="refcode"  value="<?php echo isset($referralcode)?$referralcode:0;?>" />
                   <div id="messages" class="success"></div><div id="errormessages" class="error"></div>
                    <div class="form-group form-group45 form-group-mr">
                        <label class="form-group-label">Your Name</label>
                        <input type="text" class="form-control userFields" id="from_name" name="from_name">
                           </div>
                    <div class="form-group form-group45">
                        <label class="form-group-label">Your Email Address</label>
                        <input type="email" class="form-control userFields" id="from_email" name="from_email">
                    </div>
                    <div class="form-group">
                        <label class="form-group-label">To Email Address <span>( Up to 10 email addresses separated by (,) commas )</span></label>
                        <textarea id="to_email" name="to_email" rows="3" cols="2" class="form-control userFields"></textarea>	
                    </div>

                    <div class="form-group">
                        <label class="form-group-label">Message</label>
                        <textarea name="yourmessage" id="message" rows="3" cols="2" class="form-control userFields"><?php if(isset($referralcode)){echo "I am attending this event, Join me and  you would get up to Rs. ".$receivercomm." discount";}else{?>Hi, 
							I am sure you would find this event interesting:<?php }?></textarea>	
                    </div>

                    <div class="form-group form-group70">
                        <label style="float:left; width:100%;">Captcha</label>
                        <input type="text" class="form-control userFields capthcafiled" id="CaptchaText" name="captchatext">
                        <p class="captcha-container"><img src="" id="capthaSrcUrl"></p>
                    </div>
                    <div class="form-group form-group30">
                        <input type="button" id="signin_submit" class="btn btn-default commonBtn-send"  name="sendEmail" value="SEND">
<!--                        <a class="btn btn-default commonBtn-send" name="sendEmail" id="signin_submit" href="#">SEND</a>-->
                    </div>
                    <br><br>
                    <div class="form-group" style="float:left;">
                        <p class="Invite-Privacy">Privacy Protected.<br> Your email will only be seen by your invitee(s). We do not share your contact information with anyone.</p>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
<script>
    var capthaUrl="<?php echo commonHelperGetPageUrl('captcha');?>";
</script>    
    

