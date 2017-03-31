<?php
$eventCount = count($eventList);
if($pageType == "current")
{
	$currentClass = "selected";
	$pastClass = "";
	$currentTotal = "( ".$eventCount." )";
	$pastTotal = "";
}
else if($pageType == "past")
{
	$currentClass = "";
	$pastClass = "selected";
	$pastTotal = "( ".$eventCount." )";
	$currentTotal = "";
}

?>
<div id="preloader" style="display:none;"><div class="bg"></div></div>
<div class="rightArea">
  <h3>Attendee View</h3>
  
   <div>
        <ul class="tabs" data-persist="true">
            <li class="<?php echo $currentClass;?>"><a href="<?php echo commonHelperGetPageUrl('user-attendeeview-current');?>">Current Tickets <?php echo $currentTotal;?></a></li>
            <li class="<?php echo $pastClass;?>"><a href="<?php echo commonHelperGetPageUrl('user-attendeeview-past');?>">Past Tickets <?php echo $pastTotal;?></a></li>
           <!--  <li><a href="<?php echo commonHelperGetPageUrl('user-attendeeview-referal');?>">Referral Bonus</a></li>-->
        </ul>
        <div class="tabcontents">
            <div id="view1">
			<?php
				if($eventCount > 0 )
				{
				$uloop = 1;
				$eventMonthName ="";
				
				foreach($eventList as $event)
				{
					
					$eventMonthDiv = "";
				 	$eventMonth = $event['eventMonth'];
					$eventName = $event['eventName'];
					$eventName = (strlen($eventName) > 30) ? substr($eventName,0,30)."..." : $eventName;
					$eventId = $event['eventId'];
					$quantity = $event['quantity'];
					$totalAmount = $event['totalAmount'];
					$currencyCode =  $event['currencyCode'];
					$eventStartDate = $event['signupdate'];
					$txnType = (isset($event['txnType'])) ? $event['txnType'] : "";
					$divClass = ($uloop%2 ==0) ? "boxTeal" : "";
					$divClass = ($uloop%3 == 0) ? "boxLightTeal" : "";
					
					if(strcmp($eventMonthName,$eventMonth) != 0)
					{
						$eventMonthName = $eventMonth;
					 	$eventMonthDiv = "<h6>".$eventMonth."</h6>";	
						if($uloop > 1)
						echo '<div class="clearBoth"></div>';
						
						$uloop =1;
					}
					
			
			?>
			<?php echo $eventMonthDiv;?>
			<div class="db_Eventbox">
				<h4 class="fs-event-title"> <?php echo $eventName; ?></h4>
				<div class="fs-db_Eventbox-content">
					<div class="fs-event-place-time">
	                	<div class="fs-event-start-date float-left"> 
	                		<span class="icon2-clock-o"></span>
	                		<span><?php echo $eventStartDate;?></span> 
	                	</div>
	                	<div class="fs-event-quantity float-right">  
	                		Qty: <?php echo $quantity;?> 
	                	</div>
	            	</div>
					<div class="db_Eventbox_section">
						  <div class="fs-ticket-management-buttons">
						  	  <div class="ticketsId">
						      		<p>Event ID: <strong><?php echo $eventId;?></strong></p>
						      		<!-- <h4><?php echo $eventName;?></h4> -->
						      </div>
						      <div class="fs-tickets-reg-no">
							      <p>
						      			<span class="icon-duplicate"></span> Reg No: <strong><?php echo $event['eventSignupId'];?></strong>
						      	  </p>
						      </div>
						  </div>
						  <div class="ticketsBooked">
						    <h5>Paid</h5>
							<!-- <h1>3689</h1> -->
						  	<div class="fs-total-amount"> <?php  if($totalAmount>0){ echo $currencyCode." "; } echo $totalAmount;?></div>
						  </div>
					</div>
				    <div  class="db_Eventbox_footer"> 
				    	<a class="fs-btn" href="printpass/<?php echo $event['eventSignupId'];?>" target="_blank"><span class="icon-manage" ></span>Email/Print Pass </a>
				    	<!-- <a href="javascript:;"><span class="icon-duplicate"></span> <?php //echo $txnType; ?></a>-->
						<!-- <a class="fs-btn" href="javascript:;" style="float: right;"><span class="icon-duplicate"></span> Reg No: <strong><?php echo $event['eventSignupId'];?></strong></span></a> -->
				    </div>
				 </div> 
			</div>
  					
					<?php
					if($uloop%2 == 0) { ?><div class="clearBoth"></div><?php }
					$uloop++;
				}
			}else{
				
				echo "<h6>No Tickets booked Yet</h6>";
			}
		?>
   
            </div>
        </div>
    </div>
</div>
