<div class="rightArea">
    <?php if(isset($output) && $output['status'] == TRUE) { ?>
        <div id="" class="db-alert db-alert-success flashHide">
            <strong>&nbsp;&nbsp;  <?php echo $output['response']['messages'][0]; ?></strong> 
        </div>
    <?php } ?> 
    <?php if(isset($output) && $output['status'] == FALSE) { ?>
        <div id="" class="db-alert db-alert-danger flashHide">
            <strong>&nbsp;&nbsp;  <?php echo $output['response']['messages'][0]; ?></strong> 
        </div>
    <?php } ?>
    <div class="heading">
    	<h2>DELETE REQUEST FOR EVENT : <?php echo $eventName;?></h2>
    </div>
	  <!--Data Section Start-->
	<div class="fs-form">
		<h2 class="fs-box-title">EVENT DELETE REQUEST</h2>
		<div class="editFields">
		    <form name="deleteRequestForm" id=""method="post" action="" >
		      <label>Comment <span class="mandatory"></span></label>
		      <textarea class="textarea" name="deleteComment" rows="5" id="deleteComment"></textarea>		
			  <div class="seo-buttons float-right">
                              <input type="submit" name="deleteSubmit" id="deleteSubmit" onclick="return confirmDeleting('menew.com')" class="createBtn" value="Request for Delete">  
                                   <a href="<?php echo commonHelperGetPageUrl('dashboard-eventhome', $eventId); ?>" class="saveBtn"><span ></span>Cancel</a>
			  </div>
			</form>
		</div>
	</div>
</div>
