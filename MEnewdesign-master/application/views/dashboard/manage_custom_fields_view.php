<div class="rightArea">
<!--    <div class="rightSec">
        <div class="search-container">
            <input class="search searchExpand icon-search" id="searchId" type="search" placeholder="Search">
            <a class="search icon-search"></a> </div>
    </div>-->
    <div class="heading">
        <h2>Add Custom Fields for Event : <span><?php echo $eventTitle; ?></span></h2>
        <div id="seoMessage" style="color:red"> 
            <?php
            if (isset($message)) {

                echo $message;
            }
            ?> 
        </div>
    </div>
    <!--Data Section Start-->
    <?php
    if (isset($errors)) {
        echo $errors[0];
        ?>

    <?php } else { ?>
    	<div class="fs-form">
			<h2 class="fs-box-title">Custom Fields</h2>
	        <div class="editFields">
	            <form name="customfields" id="customfields" method="post" action="">
	                <input type="hidden" name="eventId" value ="<?php echo $eventId; ?>" id="eventId"/>
	                <!--<input type="hidden" name="ticketId" value="<?php //echo ($customFieldData["ticketid"]) ? $customFieldData["ticketid"] : 0;   ?>" id="ticketId"/>-->
	    <!--                <input type="hidden" name="fieldLevel"  id="fieldlevel"/>-->
	                <div class="form-group">
	                    <label>Field Name<span class="mandatory"> *</span></label>
	                    <input type="text" class="textfield" name="fieldName" 
	                           id="fieldName" value ="<?php echo (count($customFieldData) > 0) ? $customFieldData["fieldname"] : ""; ?>">
	                    <span></span>
	                </div>
	                <div class="form-group">
	                    <label>Field Type<span class="mandatory"> *</span></label>
	                    <div style="position:relative"> 
	                        <span class="icon-downarrow downarrowSettings"></span>
	                        <select name="fieldType" required="" id="fieldType">
	                            <option value=""> Select an Option </option>
	                            <option value="textbox" <?php echo (count($customFieldData) > 0 && $customFieldData["fieldtype"] == 'textbox') ? 'selected="selected"' : ''; ?>>Textbox</option>
	                            <option value="textarea" <?php echo (count($customFieldData) > 0 && $customFieldData["fieldtype"] == 'textarea') ? 'selected="selected"' : ''; ?>>Textarea</option>
	                            <option value="dropdown" <?php echo (count($customFieldData) > 0 && $customFieldData["fieldtype"] == 'dropdown') ? 'selected="selected"' : ''; ?>>Dropdown</option>
	                            <option value="radio" <?php echo (count($customFieldData) > 0 && $customFieldData["fieldtype"] == 'radio') ? 'selected="selected"' : ''; ?>>Radio Options</option>
	                            <option value="checkbox" <?php echo (count($customFieldData) > 0 && $customFieldData["fieldtype"] == 'checkbox') ? 'selected="selected"' : ''; ?>>Check Boxes</option>
	                            <option value="date" <?php echo (count($customFieldData) > 0 && $customFieldData["fieldtype"] == 'date') ? 'selected="selected"' : ''; ?>>Date</option>
	                            <option value="file" <?php echo (count($customFieldData) > 0 && $customFieldData["fieldtype"] == 'file') ? 'selected="selected"' : ''; ?>>File Upload</option>
	                        </select>
	                    </div>
	                </div>
	
	
	
	                <div id="options_div" class="options_div">
	                    <ul>
	                        <?php 
	                        if (count($customFieldData) > 0 && ($customFieldData["fieldtype"] == 'dropdown' || $customFieldData["fieldtype"] == 'radio' || $customFieldData["fieldtype"] == 'checkbox')) {
	                            foreach ($customFieldValues as $key => $customFieldValue) {
	                                ?>
	                                <li>
	                                    <input id="addMoreTicketsInput" type="text" class="textfield addfield" 
	                                           name="customFieldValues[]" value="<?php echo $customFieldValue['value'];?>">
	                                    <span style="cursor: pointer;" class="removeOption">
	                                        Remove
	
	                                    </span>
	                                </li>
	                            <?php }
	                        } else {
	                            ?>
	                            <li>
	                                <input id="addMoreTicketsInput" type="text" class="textfield addfield" 
	                                       name="customFieldValues[]">
	                                <span style="cursor: pointer;" class="removeOption">
	                                    Remove
	
	                                </span>
	                            </li>
	    <?php } ?>
	                    </ul>
	                    <span class="field">
	                        <a href="javascript:void(0);"  id="addOptionClass" >+ Add more options</a>
	                    </span>
	                </div>
					<div class="fs-mandatory-field">
                                                <input type="checkbox" id="fs-mandatory-field" name="fieldMandatory" value="1" <?php if(isset($customFieldData)& count($customFieldData)>0 & ($customFieldData['fieldmandatory']==1)){?>checked="checked"<?php }?>>
						<label for="fs-mandatory-field">Make this  field Mandatory</label>	
					</div>
					<div class="fs-custom-field-buttons float-right">
                                                <input type="submit" name="submit" class="createBtn" value="<?php if(isset($customFieldData)& count($customFieldData)>0){echo 'Update';}else{ echo 'Add Field';}?>"/>
		                <a href="<?php echo site_url("dashboard/configure/customFields/".$eventId); ?>">
		                    <button type="button" class="saveBtn">cancel</button>
		                </a>
					</div>
	            </form>
	        </div>
    	</div>    
<?php } ?>
</div>
<script>
    var fieldType = "<?php echo $customFieldData["fieldtype"]; ?>";
    var fieldmandatory = "<?php echo $customFieldData["fieldmandatory"]; ?>";
    var customFieldVaues = "<?php json_encode($customFieldValues) ?>";
</script>    


