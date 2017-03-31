<!--Right Content Area Start-->
<div class="rightArea" >
<!--    <div class="rightSec">
        <div class="search-container">
            <input class="search searchExpand icon-search"
                   id="searchId" type="search"
                   placeholder="Search">
            <a class="search icon-search"></a> </div>
    </div>-->
    <?php  
        $successFlashMessage=$this->customsession->getData('emailAttendeeSuccessMessage');
        $errorFlashMessage=$this->customsession->getData('emailAttendeeErrorMessage');
        $this->customsession->unSetData('emailAttendeeSuccessMessage');
        $this->customsession->unSetData('emailAttendeeErrorMessage');
    ?>
    <?php if($successFlashMessage){?>
        <div class="db-alert db-alert-success flashHide">
            <strong>  <?php echo $successFlashMessage; ?></strong> 
        </div>
    <?php }?>
    <?php if($errorFlashMessage){?>
        <div class="db-alert db-alert-info flashHide">
            <strong>  <?php echo $errorFlashMessage; ?></strong> 
        </div>
    <?php }?>
    <div class="heading">
        <h2>
            Send Email to Attendees for Event :<span><?php echo $eventName; ?></span></h2>
        
        <p>You can send emails to all attendees or Incomplete transaction users and even, Ticket wise booking users of this event.</p>
    </div>

    <!--Data Section Start-->
    <div class="fs-form" style='margin-top: 25px;'>
        <h2 class='fs-box-title'>Send Email</h2>
    <div class="editFields">
        <form name='emailAttendeesForm' method='post' action='' id='emailAttendeesForm'>
            <label>Name <span class="mandatory">*</span></label>
            <input type="text" class="textfield" name="userName" id="userName" value="<?php echo $userName; ?>">
            <div class="clearBoth"></div>

            <label>Reply to E-Mail <span class="mandatory" >*</span></label>
            <input type="text" class="textfield" name="replyEmail" id="replyEmail" value="<?php echo $email; ?>">



            <label>Subject <span class="mandatory" >*</span></label>
            <input type="text" class="textfield" name="subject" id="subject" >


            <label>To</label>
            <label>
                <span style="top: 10px;" class="float-left icon-downarrow"></span>
                <select name='toValue'>
                    <option value="ALL_ATTENDEES"  name="to">All Attendees </option>
                    <option value="INCOMPLETE_TRANSACTIONS" name="to">Incomplete Transactions</option>
                </select>
            </label>

            <label>Tickets (Optional)</label>
            <label>
                <span style="  top: 10px;" class="float-left icon-downarrow"></span>
                <select name='ticketId'>
                    <option value='0'>Select</option>
                    <?php
                    if (count($ticketDetails) > 0) {
                        foreach ($ticketDetails as $ticket) {
                            ?>
                            <option id="<?php echo $ticket['id']; ?>" name="ticketIds[]" class="ticketDropdown " value="<?php echo $ticket['id']; ?>"><?php echo $ticket['name']; ?></option>
                        <?php }
                    } else {
                        ?>  
                        <option value="0">No tickets found for this event</option>
                    <?php }
                    ?>              
                </select>
            </label>


            <label>Message <span class="mandatory">*</span></label>
            <textarea style="width:60%" class='required' tabindex="2" rows="10" id="emailAttendeeMessage" name="emailAttendeeMessage"></textarea>     
            <div><span id="messageError" class="error"></span></div>
            
            <input type="submit" name="emailAttendeesSendMail" id="emailAttendeesSendMail" class="createBtn float-right" value="Send email">
            <div class='fs-send-test-mail'>
                <label>Test Mail </label>
                <input type="text" name="testMail" id="testMail" class='textfield'>
                <input type="submit" class="createBtn" value="Send test mail"   name="emailAttendeesSendTestMail" id="emailAttendeesSendTestMail">       
                <br><div><span id="testMailError" class="error"></span></div>
            </div>
        </form>
    </div>
    </div>
</div>

