
    <div class="rightArea">
        <?php if (isset($ticketSettings) && $ticketSettings['status'] == TRUE ) { ?>
        <div id="ticketOptionsMessage" class="db-alert db-alert-success flashHide">
            <strong>&nbsp;&nbsp;  <?php echo $message; ?></strong> 
        </div>
    <?php } ?>   
        <?php if (isset($ticketSettings) && $ticketSettings['status'] == FALSE ) { ?>
        <div id="ticketOptionsMessage" class="db-alert db-alert-danger flashHide">
            <strong>&nbsp;&nbsp;  <?php echo $message; ?></strong> 
        </div>
    <?php } ?> 
          <br>
      <div class="heading float-left">
        <h2>Ticketing Options: <span><?php echo $eventTitle;?></span></h2>
        <p>Define what information to collect registrants and how the payment process will work for this event. Its a simple 4 step process that you would have to setup only once for your
          event. Once registrations begin for your event, some of these options would be disabled in order to maintain data consistancy.</p>
      </div>
      <div class="clearBoth"></div>
      <!--Data Section Start-->
      <div class="information">
        <form id="ticketOptions" method="post" action="">
	        <div class="fs-form fs-form-widget-setting">
				<h2 class="fs-box-title">Information to  Collect</h2>
				<div class="fs-form-content">
					<p>
						<label>
							<input type="radio" name="collectmultipleattendeeinfo" value="1"  <?php if($ticketOptions[0]['collectmultipleattendeeinfo'] != 0) { ?> checked="checked" <?php } ?>>
							<span class="fs-label-content">Collect every attendee`s information in an order.</span> 
						</label>
						<span class="mleft">
							You will be able to export all the details collected for all your attendees at any point. This option is ideal for conferences and formal events.
	          			</span> 
	          		</p>
			  		<p>
				  		<label>
				  			<input type="radio" name="collectmultipleattendeeinfo" value="0"  <?php if($ticketOptions[0]['collectmultipleattendeeinfo'] == 0) { ?> checked="checked" <?php } ?>>
				  			<span class="fs-label-content">Collect only one person`s information in an order.</span> 
				  		</label>
				  		<span class="mleft">
				  			This person will act as the Ticket Buyer for the order. We recommend this option for 
	          social events like plays, concerts , informational gatherings, etc.
	          			</span> 
	          		</p>
				</div>
	        </div>
	        <div class="fs-form fs-form-widget-setting">
				<h2 class="fs-box-title">Ticket Settings</h2>
				<div class="fs-form-content">
					<p>
			        	<label>
			        		<input 
			        			type="checkbox" 
								name="limitsingletickettype" 
								<?php if($ticketOptions[1]['limitsingletickettype'] == 1) { ?> checked="checked" <?php }?>
							>
							<span class="fs-label-content">Limit one ticket type per booking</span> 
						</label>
						<span class="mleft">If checked, allows delegate to book only one type of ticket.</span> 
			        </p>
			        <p>
			          	<label>
			          		<input 
			          			type="checkbox" 
					  			name="displayamountonticket" 
					  			<?php if($ticketOptions[0]['displayamountonticket'] == 1) { ?> checked="checked" <?php }?>
					  		>
					  		<span class="fs-label-content">Display amount on Print Pass</span>
					  	</label>
			          	<span class="mleft">If checked, the amounts will be displayed on delegate pass.</span> 
			        </p>
                                <p>
			          	<label>
			          		<input 
			          			type="checkbox" 
					  			name="nonormalwhenbulk" 
					  			<?php if($ticketOptions[0]['nonormalwhenbulk'] == 1) { ?> checked="checked" <?php }?>
					  		>
					  		<span class="fs-label-content">Normal discount not applicable on bulk discounted ticket</span>
					  	</label>
			          	<span class="mleft">If checked, the normal discount will not be applied on bulk discounted ticket.</span> 
			        </p>
<!--			        <p>
			          	<label>
			          		<input 
			          			type="checkbox" 
			          			name="sendubermails" 
			          			<?php //if($ticketOptions[0]['sendubermails'] == 1) { ?> checked="checked" <?php// }?>
					  		>
					  		<span class="fs-label-content">Send UBER mails after each registration</span> 
					  	</label>
					  	<span class="mleft">If checked, an Email with the UBER discount code would be sent for every delegate registration.</span> 
			          </p>-->
				</div>
	        </div>		
			<input type="submit" class="submitBtn createBtn float-right" name="submit" value="save"/>
        </form>
      </div>
    </div>

<script>
     $(document).ready(function (){
         customCheckbox('nonormalwhenbulk');
     });
</script>