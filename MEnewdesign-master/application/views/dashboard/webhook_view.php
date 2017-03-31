<div class="rightArea">
    <?php if (isset($output) && $output['status'] == TRUE) { ?>
        <div id="webHookMessage" class="db-alert db-alert-success flashHide">
            <strong>&nbsp;&nbsp;  <?php echo $output['response']['messages'][0]; ?></strong> 
        </div>
    <?php } if (isset($output) && $output['status'] == FALSE) { ?>
        <div id="webHookMessage" class="db-alert db-alert-warning flashHide">
            <strong>&nbsp;&nbsp;  <?php echo $output['response']['messages'][0]; ?></strong> 
        </div>
    <?php } ?>   

    <div class="heading">
        <h2>Add Web Hook URL: <span><?php echo $eventName; ?></span></h2>
    </div>
    <!--Data Section Start-->
	<div class="fs-form">
		<h2 class="fs-box-title">Web Hook URL <sup>*</sup></h2>
	    <div class="editFields">
	        <form name='webhookUrlForm' method='post' action='' id='webhookUrlForm'>
	            <!-- <label>Web Hook URL <span class="mandatory">*</span></label> -->
	            <input type="text" class="textfield" name='webhookUrl' id='webhookUrl' value='<?php echo $webhookUrl; ?>' >
	            <input type="submit" name='submit' class="createBtn float-right" value='Save'>
	        </form>
	    </div>
	</div>    
</div>
