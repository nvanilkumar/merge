<div class="rightArea">
    <div class="heading">
        <h2>Add Collaborator: <?php echo $eventId; ?></h2>
    </div>
    <input type="hidden" name="eventId" id="eventId" value="<?php echo $eventId; ?>"/>
    <input type="hidden" name="collaboratorId" id="collaboratorId" value="<?php echo $collaboratorId; ?>"/>
    <div class="editFields fs-add-collaborator-form">
        <form id="addCollaborator" action="javascript:;">
            <label>Collaborator Name <span class="mandatory">*</span></label>
            <input value="<?php echo isset($collaboratorList) ? $collaboratorList['name'] : ''; ?>" <?php echo isset($collaboratorList) ? 'disabled="disabled"' : ''; ?> name="name" type="text" class="textfield">
            <label>Email ID <span class="mandatory">*</span></label>
            <input name="email" type="text" class="textfield" value="<?php echo isset($collaboratorList) ? $collaboratorList['email'] : ''; ?>" <?php echo isset($collaboratorList) ? 'disabled="disabled"' : ''; ?>><div class="error" id="collaborator_email" style="display:none"> </div>
            <label>Mobile Number</label>
            <input name="mobile" type="text" class="textfield" value="<?php echo isset($collaboratorList) ? $collaboratorList['mobile'] : ''; ?>" />
            <label>Module Name <span class="mandatory">*</span></label>
            <div class="valid_date" style="width:100%;">
                <ul>
                    <li>
                        <input <?php echo (isset($collaboratorList) && (strpos($collaboratorList['modules'], 'manage') !== FALSE)) ? 'checked="checked"' : ''; ?> class="module" type="checkbox" name="module[]" value="manage">
                        Manage Event 
                        <span style="float:left; padding-left: 30px; font-size:13px; width:100%; margin: 5px 0;">(Full read and write permissions for this event)</span></li>
                    <li style="float:left; width:100%; margin: 5px 0 15px 0;">
                        <input <?php echo (isset($collaboratorList) && (strpos($collaboratorList['modules'], 'promote') !== FALSE)) ? 'checked="checked"' : ''; ?> class="module" type="checkbox" name="module[]" value="promote">
                        Promote 
                        <span style="float:left; padding-left: 30px; font-size:13px; width:100%; margin: 5px 0;">(Read and Write permissions for discounts, bulk discounts, viral ticketing, offline promoters and sales)</span>
                    </li>
                    <li style="float:left; width:100%; margin: 5px 0 15px 0;">
                        <input <?php echo (isset($collaboratorList) && (strpos($collaboratorList['modules'], 'report') !== FALSE)) ? 'checked="checked"' : ''; ?> class="module" type="checkbox" name="module[]" value="report" <?php
                        if (!isset($collaboratorList)) {
                            echo 'checked="checked"';
                        }
                        ?>>
                        Report 
                        <span style="float:left; padding-left: 30px; font-size:13px; width:100%; margin: 5px 0;">(Export/Download, send email feature available)</span>
                    </li>
                    <li id='modulesErr' style="float:left; width:100%; margin: 5px 0 15px 0;">

                    </li>
                </ul>
            </div>
            <div class="btn-holder float-right">
            	<button type="submit" class="createBtn" name="save">Submit</button>
		 <button  type="button" class="saveBtn" name="cancel">cancel</button>		
            </div>
        </form>
    </div>
    <!--    <div class="btn-holder float-right">
            <button type="button" class="createBtn float-right">Submit</button>
            <a href="<?php //echo site_url('dashboard/promote/collaboratorslist/' . $eventId); ?>"> <button type="button" class="saveBtn float-right">cancel</button></a>
        </div>-->
</div>

