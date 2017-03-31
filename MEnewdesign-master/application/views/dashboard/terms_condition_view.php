<div class="rightArea">

     <?php if (isset($updateTnc) && $updateTnc['status'] === TRUE) { ?>
        <div id="paymentModeMessage" class="db-alert db-alert-success flashHide">
            <strong>&nbsp;&nbsp;  <?php echo $updateTnc['response']['messages'][0]; ?></strong> 
        </div>
    <?php } ?>
    <?php if (isset($updateTnc) && $updateTnc['status'] === FALSE) { ?>
        <div id="paymentModeMessage" class="db-alert db-alert-danger flashHide">
            <strong>&nbsp;&nbsp;  <?php echo $updateTnc['response']['messages'][0]; ?></strong> 
        </div>
    <?php } ?>
  
    <div class="heading">
        <h2>Terms & Conditions: <span><?php echo $eventName; ?></span></h2>
    </div>
    <!--Data Section Start-->
	<div class="fs-form">
		<h2 class="fs-box-title">Enter your Terms and Conditions</h2>
	    <div class="editFields">
	        <form name='tncForm' method='post' action='' id='tncForm'>
	           <textarea style="width:60%" class='required' tabindex="2" rows="10" name="tncDescription" id="tncDescription" ><?php echo $organizertnc; ?></textarea>     
	            <input type="submit" name='tncSubmit' class="createBtn float-right" id="tncSubmit" value='Save'>    
	            <div><span id="tncDiscriptionError" class="error"></span></div>
	        </form>
	
	    </div>
	</div>   
</div>