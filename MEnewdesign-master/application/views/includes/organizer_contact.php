<div class="modal fade" id="ContactOrgModal" tabindex="-1"
     role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 600px;
    margin: 0px auto;">
    <div class="modal-dialog-invite modal-dialog-center1">
        
        <div class="modal-content">
            
            <div class="popup_signup" style="height:auto; overflow:auto;">
                <div class="popup-closeholder"><button data-dismiss="modal" class="popup-close">X</button></div>
                <h1>Contact The Organizer</h1>               
                
                <hr></hr>

                <form class="invitefriend_form form1" method="post" action="#" name="organizerContact" id="organizerContact">
                                       
                   <div id="messages" class="success"></div><div id="errormessages" class="error"></div>
                    <div class="form-group form-group45 form-group-mr">
                        <label class="form-group-label">Your Name</label>
                        <input type="text" class="form-control userFields" id="name" name="name">
                           </div>
                    <div class="form-group form-group45">
                        <label class="form-group-label">Email Address</label>
                        <input type="email" class="form-control userFields" id="email" name="email">
                    </div>
                    <div class="form-group">
                        <label class="form-group-label">Mobile No</label>
                        <input type="text" id="mobile" name="mobile" class="form-control userFields">
                    </div>

                    <div class="form-group">
                        <label class="form-group-label">Description</label>
                        <textarea name="description" id="description" rows="3" cols="2" class="form-control userFields"></textarea>	
                    </div>

                    <div class="form-group form-group70">
                        <label style="float:left; width:100%;">Captcha</label>
                        <input type="text" class="form-control userFields capthcafiled" id="CaptchaText" name="captchatext">
                        <p class="captcha-container"><img src="<?php echo commonHelperGetPageUrl('captcha'); ?>"></p>
                    </div>
                    <div class="form-group form-group30">
                        <input type="button" id="orgcontact_submit" class="btn btn-default commonBtn-send"  name="sendEmail" value="SEND">
<!--                        <a class="btn btn-default commonBtn-send" name="sendEmail" id="signin_submit" href="#">SEND</a>-->
                    </div>
                    <br><br>
                   
                </form>

            </div>
        </div>
    </div>
</div>

