<div class="rightArea">
     <?php if (isset($updateContactDetails) && $updateContactDetails['status'] === TRUE) { ?>
        <div class="db-alert db-alert-success flashHide">
            <strong>&nbsp;&nbsp;  <?php echo $message; ?></strong> 
        </div>
    <?php } ?>
      <?php if (isset($updateContactDetails) && $updateContactDetails['status'] === FALSE) { ?>
        <div id="paymentModeMessage" class="db-alert db-alert-danger flashHide">
            <strong>&nbsp;&nbsp;  <?php echo $message; ?></strong> 
        </div>
    <?php } ?>
    <div class="heading">
        <h2>Contact Information: <span><?php echo $eventName; ?></span></h2>
    </div>
    <div class="fs-form">
		<h2 class="fs-box-title">Contact Information</h2>
	    <div class="editFields">
	        <form name="eventContactInfoForm" id="eventContactInfoForm" action="" method="post">
	            <label>Enter Name(s), Phone or Email <span class="mandatory">*</span></label>
	            <textarea name="namesPhoneEmail" id="namesPhoneEmail" rows="5" class="textarea"><?php echo $contactDetails['contactdetails']; ?></textarea>
	            <label>More Email Id`s for Summary <span class="suggestiontext"> [ Enter list of email ids seperated by a comma(,) ]</span></label>
	            <textarea name="moreEmails" id="moreEmails" class="textarea"><?php echo $contactDetails['extrareportingemails']; ?></textarea>
	            <label>More Email Id`s for Ticket Registrations <span class="suggestiontext"> [ Enter list of email ids seperated by a comma(,) ]</span></label>
	            <textarea name="extratxnreportingemails" id="extratxnreportingemails" class="textarea"><?php echo $contactDetails['extratxnreportingemails']; ?></textarea>
	            <label>Event Website URL</label>
	            <input type="text" name="eventWebUrl" id="eventWebUrl" class="textfield" value="<?php echo $contactDetails['contactwebsiteurl']; ?>">
	
	            <label>Event Facebook Link</label>
	            <input type="text" name="eventFBUrl" id="eventFBUrl" class="textfield" value="<?php echo $contactDetails['facebooklink']; ?>">
	
	            <label>&nbsp;</label>
	            <input type="hidden" name="updateContactInfo" value="1" />
	            <input type="submit" name="submit" class="createBtn float-right" value="Update">
	        </form>        
	    </div>
    </div>
</div>

