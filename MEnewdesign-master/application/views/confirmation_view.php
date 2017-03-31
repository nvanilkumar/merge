<?php
//echo "<pre>";print_r($attendees['otherAttendee']);print_r($displayonTicketFields);
$configCustomDatemsg = json_decode(CONFIGCUSTOMDATEMSG,true);
$configTransactionDateonPass = json_decode(CONFIGTRANSACTIONDATEONPASS,true);
$configCustomTimemsg =  json_decode(CONFIGCUSTOMTIMEMSG,true);
$eventUrl = $eventData['eventUrl'];
$eventName = $eventData['title'];
$userEmail = $userDetail['email'];
$eventId = $eventData['id'];
$userId = $this->customsession->getData('userId');
$userMobile = $userDetail['mobile'];
$barcodeNumber = $eventsignupDetails['barcodenumber'];
$EventSignupId = $eventsignupDetails['id'];
$venueDetails = $eventData['fullAddress'];
$convertedStartTime=convertTime($eventData['startDate'],$eventData['location']['timeZoneName'],TRUE);
$convertedEndTime=convertTime($eventData['endDate'],$eventData['location']['timeZoneName'],TRUE);
 $ticketpriceClass = 'col-sm-6';
 if($eventSettings['displayamountonticket'] == 1){
 	$ticketpriceClass = 'col-sm-4';
 }
 $referaleventurl = $eventUrl;
 $viralticketUrl =  $eventUrl;
 $backpageUrl = $eventUrl;
 $pcodeName='ucode';
 if($eventsignupDetails['bookingtype']=='global'){
    $pcodeName='acode';
 }
 if(!empty($eventticketviralsetting)){
 	$viralclass = "col-md-6";
 	$referralcode = $eventsignupDetails['referralcode'];
 	if($eventticketviralsetting[0]['type']== 'percentage'){
 		$referercomm =  $eventticketviralsetting[0]['referrercommission']."%";
 		$receivercomm =  $eventticketviralsetting[0]['receivercommission']."%";
 	}else{
 		$referercomm =  $eventticketviralsetting[0]['referrercommission']." ".$eventsignupDetails['currencyCode'];
 		$receivercomm =  $eventticketviralsetting[0]['receivercommission']." ".$eventsignupDetails['currencyCode'];
 	}
 	$viralticketUrl =  $eventUrl."?reffCode=".$referralcode;
        if(strlen($eventsignupDetails['promotercode'])>1){
            $pcode = $eventsignupDetails['promotercode'];
            $referaleventurl = $eventUrl."?".$pcodeName."=".$pcode;
            //$viralticketUrl = $eventUrl."?ucode=".$pcode;
            $backpageUrl = $referaleventurl;
        }else{
           $backpageUrl = $eventUrl;
        
        }
 }else{
 	$viralclass="col-md-12";
        if(strlen($eventsignupDetails['promotercode']) >1  ){
                $pcode = $eventsignupDetails['promotercode'];
                $referaleventurl = $eventUrl."?".$pcodeName."=".$pcode;
                $viralticketUrl = $eventUrl."?".$pcodeName."=".$pcode;
                $backpageUrl = $referaleventurl;
         }
 }

 // Back Button Url For Preview Page
 if($previewPagebooking == 1){
 	$backpageUrl = commonHelperGetPageUrl('event-preview','','?view=preview&eventId=' . $eventId);

 }
 
 $fblink = 'http://www.facebook.com/sharer/sharer.php?u='.urlencode($referaleventurl);
 $twitterlink = "http://twitter.com/?status=Meraevents".urlencode($referaleventurl);
 $linkedinlink = "http://www.linkedin.com/shareArticle?mini=true&amp;url=".urlencode($referaleventurl)."title=Meraevents";
 $googlelink = "https://plus.google.com/share?url=".urlencode($referaleventurl);

?>
<div class="page-container">
	<div class="wrap">
		<div class="container print_ticket">
		
			<div class="row">
			    <div class="col-lg-12 backtoevents"><a href="<?php  echo $backpageUrl; ?>" title="<?php echo $eventName;?>" > Back to Events Page </a></div>

   				 </div>
			
			<!-- Display the timer to redirect the page to widget redirect url -->
			<?php if (strlen(trim($widgetRedirectUrl)) > 0) {  ?>
				  <div class="row" id="timerBox">
					 <div class="WhiteBG" style="margin:10px 0 0px 0; padding:2px 3px;">
						 <h3 style="text-align:center; line-height:28px; font-size:14px;font-weight:normal !important;">
						 You will be redirected to <span style="color:#069"><?php echo $widgetRedirectUrl; ?></span> in <span id="sessionTime" style="color:#F00; font-weight:bold;">10</span> seconds, or&nbsp;&nbsp;<a style="color:#df3c19; cursor:pointer;" onClick="stopCount()">STAY HERE</a></h3>
					 </div>
				 </div>
			<?php } ?>
			
			
   				 <div class="row">
				<div class="col-lg-4 col-md-4 order_summary">
					<div class="ShareEarn">
						<h2>Your Registration is Confirmed</h2>
						<!-- <h2>You are going to Entertainment Category</h2> -->
						<p>Your order has been saved to my tickets</p>
						<p>
							A confirmation email has been sent to your email <br><?php echo isset($userEmail)?$userEmail:'';?>
						</p>
						<p>
							Confirmation SMS sent to your mobile <br><?php echo isset($userMobile)?$userMobile:'';?>
						</p>
						<p id="sendsuccess" style=" text-align: center;padding: 0;margin: 10px 0;">
						</p>
						<!-- <div class="PrintTicket-Share">
            <a><i class="icon1 icon1-invitefriend"></i></a>
            <a><i class="icon1 icon1-facebook"></i></a>
            <a><i class="icon1 icon1-twitter"></i></a>
            <a><i class="icon1 icon1-linkedin"></i></a>
            <a><i class="icon1 icon1-google-plus"></i></a>
          </div> -->
					</div>
					<a class="btn btn-primary borderGrey print_btn MarginRight resendEmail" href="javascript:;">RESEND EMAIL</a> 
					<a class="btn btn-primary borderGrey print_btn resendSMS" href="javascript:;">RESEND SMS</a>
				</div>
				<div class="col-lg-8 col-md-8 order_summary">
					<h1>Order Summary</h1>
					<table>
						<tbody>
							<tr>
								<th width="50%">Ticket Type</th>
								<th width="20%">Price</th>
								<th width="10%">Qty</th>
								<th width="20%">Total</th>
							</tr>
							<?php 
							$eventsignuptickettaxlabelamount = array();
							$discountamount=0;
							$ticketamount=0;
							$refferaldiscount=0;
							$eventsignuptaxtotal=array();
                                                       
               foreach($ticketDetails as $tkey => $tval ){
					 	// Ticket Total Amount
               	$ticketamount +=  $tval['amount'];
					 		$refferaldiscount+= $tval['referraldiscountamount'];
					 	//	$taxamount+= $taxamount+$tval['totaltaxamount'];
					 		$discountamount+= $tval['discountamount']+$tval['bulkdiscountamount'];
					 	// Main Attendee
					/*  	foreach($attendeecustomfieldsvalues[$ticket_main_attendees[$tval['ticketid']]['mainattendee']] as $custkey => $custvalues)
					 	{
					 		$mainattendeecustfields[$attendeecustfields[$custkey]['fieldname']] = $custvalues;
					 	}?> */
					 		?>
                <tr class="OrderSummary_Desc">
								<td width="50%"><?php echo $tval['name']; ?>
                      <span class="OrderSummary_Desc" style="font-size:10px;"><?php echo $tval['description']; ?></span>
								</td>
								<td width="20%"><?php 
								$price = $tval['amount']/$tval['ticketquantity'];
								if($price == 0){
									echo $price;
								}else{
									echo $price." ".$eventsignupDetails['currencyCode'];
								}?>
								</td>
								<td width="10%"><?php echo $tval['ticketquantity'];?></td>
								<td width="20%"><?php echo $tval['amount'];?> <?php if($tval['amount']>0){echo $eventsignupDetails['currencyCode'];}?></td>
							</tr>
					
							<!--     <tr class="OrderSummary_Desc">
                    <td>General Pass</td>
                    <td>1500 INR</td>
                    <td>1</td>
                    <td>1500 INR</td>
                    
                </tr> -->
               <?php  
               if(!empty($tval['ticketTaxes'])){
               foreach($tval['ticketTaxes'] as $taxLabel=>$taxAmount){
						$eventsignuptaxtotal[$taxLabel] += $taxAmount;
						}
               }
               }?>
               <tr>
               <td class="text-right" colspan="3">Total Amount</td>
								<td><?php echo round($ticketamount)." ";if($ticketamount > 0){echo $eventsignupDetails['currencyCode'] ;}?></td>
							</tr>
					
					<?php if($discountamount>0){?>
							<tr>
								<td class="text-right" colspan="3">Discount</td>
								<td><?php echo $discountamount;?> <?php echo $eventsignupDetails['currencyCode'];?></td>
							</tr>
					<?php }?>
					  
              <?php if($refferaldiscount>0){?>
							<tr>
								<td class="text-right" colspan="3">Referral Discount</td>
								<td><?php echo $refferaldiscount;?> <?php echo $eventsignupDetails['currencyCode'];?></td>
							</tr>
					<?php }?>
				
					<?php $eventsignuptotalamountinticket =$eventsignupDetails['totalamount']>0?round($eventsignupDetails['totalamount']):0;
							$eventsignuptotalamount = $eventsignupDetails['totalamount']>0?round($eventsignupDetails['totalamount']):0;?>
				
						
					<?php foreach($eventsignuptaxtotal as $taxname => $taxvalue){?>
               <tr><td class="text-right" colspan="3">	<?php echo $taxname;?></td>
              			<td><?php echo $taxvalue;?> <?php echo $eventsignupDetails['currencyCode'];?></td> </tr>
              			<?php }
              					if($eventsignupDetails['eventextrachargeamount']>0){
							//$eventsignuptotalamount = $eventsignuptotalamount+$eventsignupDetails['eventextrachargeamount'];
						?>
					<tr>
								<td class="text-right" colspan="3"><?php echo $eventsignupDetails['extraChargeLabel'];?></td>
								<td><?php echo $eventsignupDetails['eventextrachargeamount']." ". $eventsignupDetails['currencyCode'];?>
								</td>
								</tr>
							<?php }?>
							<tr>
								<td class="OrderSummary_Amount" colspan="4"><span>Purchase Amount :</span> <?php if($eventsignuptotalamount > 0){ echo round($eventsignuptotalamount)." ". $eventsignupDetails['currencyCode'] ;}else{echo "0 "; }?></td>
								
							</tr>
						</tbody>
					</table>
				</div>



			</div>
			<div class="row intro">  
			 <?php if(!empty($eventticketviralsetting)){?>
  <div class="col-md-6 ViralText">
	    <p><?php echo $userDetail['name'];?>, share this unique URL with your friends. 
	   		You get up to  <?php echo $referercomm;?> reward and up to <?php echo $receivercomm;?> discount for your friends.
	   </div>
	  <?php }?>
	 
	  <div class="<?php echo $viralclass;?> Viral-SocialText">
	    <p>Simply share this Event link with social media</p>
	    <div class="Viral-SocailShare">
	      <span class="invitefirends"><a href="javascript:;"><i class="icon1 icon1-invitefriend"></i></a></span>
	      <span><a target="_blank" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($viralticketUrl);?>"><i class="icon1 icon1-facebook"></i></a></span>              
	      <span> <a target="_blank" href="http://twitter.com/?status=Meraevents <?php echo urlencode($viralticketUrl);?>"><i class="icon1 icon1-twitter"></i></a></span>
	       <span> <a target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo urlencode($viralticketUrl);?>&title=<?php echo $eventName;?>"><i class="icon1 icon1-linkedin"></i></a></span>
	      <span><a target="_blank" href="https://plus.google.com/share?url=<?php echo urlencode($viralticketUrl);?>"><i class="icon1 icon1-google-plus"></i></a></span>
	    </div>
	    <?php if(!empty($eventticketviralsetting)){?>
	    <p>( or ) Email the below link to your friends.<br>
	    <a class="linkcolor"><?php echo $viralticketUrl;?></a></p>
	    <?php }?>
	  </div>
</div>

			<div class="row intro">
				<h1>Print your ticket now!</h1>
				<a class="btn btn-primary borderGrey print_btn" target="_blank"	href="/delegatepass/<?php echo $EventSignupId."/".urlencode($userEmail);?>" id="printpass">PRINT TICKET</a>
				<!--  <a href="#" class="btn btn-primary borderGrey print_btn">RESEND EMAIL</a>
        <a href="#" class="btn btn-primary borderGrey print_btn">RESEND SMS</a> -->
			</div>

			<div class="row ticket_View">
				<!--  <div class="cut_circle">&nbsp;</div>  -->
				<div class="col-sm-10 ticket_details">
					<!-- <div class="cut_circle">&nbsp;</div> <div class="cut_circle2">&nbsp;</div> -->

					<!-- <div class="PrintTck-TopSection">
          <div class="col-md-4 col-sm-4" >Name : Shasha Soni</div>
          <div class="col-md-4 col-sm-4 text-center">Reg No : 195245</div>
         	<div class="col-md-4 col-sm-4 text-right">No of Tickets : 2</div>
           
         </div> -->
					<div class="row PrintTck_Title">

						<div class="col-lg-8">
							<h1><?php echo $eventData['title'];?></h1>
							<span class="leftsection"><i class="icon icon-calender"></i>
							<?php if(isset($configCustomDatemsg[$eventId]) && !isset($configCustomTimemsg[$eventId])){
									echo $configCustomDatemsg[$eventId]. " | ".allTimeFormats($convertedStartTime,4).' to '.allTimeFormats($convertedEndTime,4);
								} else if(isset($configCustomTimemsg[$eventId]) && !isset($configCustomDatemsg[$eventId])){
									echo convertDate($convertedStartTime);if ($convertedStartTime != $convertedEndTime)  { echo " - ". convertDate($convertedEndTime); } echo  " | ".$configCustomTimemsg[$eventId];
								}else if(isset($configCustomTimemsg[$eventId]) && isset($configCustomTimemsg[$eventId])){
									 echo $configCustomDatemsg[$eventId]. " | ".$configCustomTimemsg[$eventId];
								}else{
									echo convertDate($convertedStartTime);if ($convertedStartTime != $convertedEndTime)  { echo " - ". convertDate($convertedEndTime); } echo " | ".allTimeFormats($convertedStartTime,4).' to '.allTimeFormats($convertedEndTime,4);
								}
								 
										 ?>
             </span> 
             <?php if($eventData['eventMode'] != 1){?>
             <span class="leftsection"><i
								class="icon icon-google_map_icon"></i><?php echo $venueDetails;?>
             </span>
             <?php }?>
						</div>

						<div class="col-lg-4 PrintTck_UserInfo">
            <?php  //foreach($attendees['mainAttendee'] as $attndkey => $attndvalues){?>
                    <p><?php $fullName = "Full Name". ":". $attendees['mainAttendee'][0]['Full Name'] ;echo $fullName ;?> </p>
                    <?php //}?>
                    <p>Event ID : <?php echo $eventsignupDetails['eventid'];?></p>
             <p>Registration No : <?php echo $eventsignupDetails['id'];?></p>
              <p>Payment Mode : <?php echo $eventsignupDetails['paymentMode'];?></p>
              <?php if(isset($eventsignupDetails['seatNumbers']) && strlen($eventsignupDetails['seatNumbers'])>0){?>
              <p>Seat No : <?php echo $eventsignupDetails['seatNumbers'];?></p>
			<?php }?>
			<?php if(isset($configTransactionDateonPass[$eventId])){
					echo " <p> Trans Dt: ".$eventsignupDetails['signupdate']."</p>";
			}?>
							<!-- <p>Reg No : 345678</p> -->
						</div>

					</div>
			<ul class="PrintTck_Heading">
                 <li class="<?php echo $ticketpriceClass; ?>">Ticket Type</li>
                 <li class="<?php echo $ticketpriceClass; ?> "> Quantity</li>
                 <?php if($eventSettings['displayamountonticket'] == 1){
                 	?>
                 <li class="<?php echo $ticketpriceClass;?>"> Amount</li>
                 <?php }?>
             </ul>
					 	 	<?php   
					 	 	$ticketTaxes=array();
							$totaldiscountamount = 0;
             		foreach($ticketDetails as $tkey => $tval ){ 
             			$totaldiscountamount += $tval['discountamount']+$tval['referraldiscountamount']+$tval['bulkdiscountamount'];
             			foreach($tval['ticketTaxes'] as $tax => $taxvalues){
             				$ticketTaxes[$tax]+= $taxvalues;
             			}
             			?>
					 	<ul class="PrintTck_TicketType">
						<li class="<?php echo $ticketpriceClass;?>"><span class="Ticket_Name"><?php echo $tval['name']; ?></span>
							<span class="Summary_desc" style="font-size:10px;" ><?php echo $tval['description']; ?></span>
						</li>
						<li class="<?php echo $ticketpriceClass;?>"><span class="Ticket_Name"><?php echo $tval['ticketquantity'];?></span>
						</li>
						   <?php if($eventSettings['displayamountonticket'] == 1){?>
                                                <li class="<?php echo $ticketpriceClass;?>"><span class="Ticket_Name"><?php if($tval['amount'] != 0) { echo $tval['amount']." ".$eventsignupDetails['currencyCode']; } else{ echo $tval['amount']; }?></span>
                                                
							<!--  <span class="Summary_desc">Incl of all Taxes, Excluding Extra Charges</span> --></li>
							<?php }?>
								</ul>
					<?php } ?>
				
            <div class="row ticket_type">
						<div class="col-xs-6">
							<p class="organized_by">
								Organized By : <span> <?php echo $organizerDetails['name'];?> | Mobile : <?php echo $organizerDetails['mobile'];?></span>
								<?php if($eventData['eventDetails']['contactdisplay']==1 && strlen($eventData['eventDetails']['contactdetails'])>0){?><br><span><?php echo $eventData['eventDetails']['contactdetails'];?></span><?php }?>
							</p>
								<p class="organized_by">
								  <span style="float: left; width: 60%; margin-top: 0; font-size: 12px;">Powered By</span>
								</p>
								<p style="float: left;width: 100%;">
								  <img src="<?php echo MELOGO;?>" alt="" style="top: 8px;width: 150px;">
								</p>
						</div>
						<?php if($eventSettings['displayamountonticket'] == 1){?>

						<div class="col-xs-6 text-right">
						<p class="TotalAmount_Ticket">Total Amount: <?php echo $ticketamount>0?$ticketamount." ".$eventsignupDetails['currencyCode']:0;?></p>
							<?php if($totaldiscountamount>0){?>
							<p class="TotalAmount_Ticket">Discount :  <?php echo $totaldiscountamount;?> <?php echo $eventsignupDetails['currencyCode'];?></p>
							<?php }?>
							<?php 
					foreach($ticketTaxes as $taxlabelkey => $taxtotalamount){
						?>
                    	<p class="TotalAmount_Ticket">	<?php echo $taxlabelkey.":".$taxtotalamount." ".$eventsignupDetails['currencyCode'];?></p>
              <?php }?>
							</p>
							
								<?php if($eventsignupDetails['eventextrachargeamount']>0){
              						$eventsignuptotalamountinticket= $eventsignuptotalamountinticket-$eventsignupDetails['eventextrachargeamount'];
              						
              					}?>
							<p class="TotalAmount_Ticket">Purchase Amount : <?php if($eventsignuptotalamountinticket>0){ echo round($eventsignuptotalamountinticket)." ". $eventsignupDetails['currencyCode'] ;}else{echo '0';}?><br>
								<span class="Summary_desc">Inclusive of All Taxes and Excluding Convenience Fee</span> 
							</p>
					
						</div>
								<?php }?>
					</div>

				</div>
				<!--  <div class="cut_circle2">&nbsp;</div> -->

				<div class="col-lg-2 col-md-2 col-sm-2 ticket_barcode">
					<?php $barcodeurl =  $barcodeNumber.'&angle=270';?>
					<img src="/barcode/barcode.php?text=<?php echo $barcodeurl;?>" style="margin-top: 60px;" width="90" height="200">
					<div class="">&nbsp;</div>
				</div>
				<!-- <div class="row"> 
      <div class="co-lg-8">
<p class="row organized_by">Organized By : <span>Asha Soni | Phone : 97012 31234</span></p>

          
      </div>
      <div class="co-lg-4">
        <p class="row TotalAmount_Ticket">Total Amount : 12300<br>
          <span class="Summary_desc">Inclusive of ST and Excluding Convenience Fee</span></p>
      </div>
    </div> -->
			</div>
			
			<p class="help">Need help? please call us at <?php echo GENERAL_INQUIRY_MOBILE; ?>
                           </p>
<?php if(count($displayonTicketFields)>2){?>                           
<?php if($eventSettings['collectmultipleattendeeinfo'] == 1 && !empty($attendees['otherAttendee'] )){?>
<div class="row">

				<div class="col-lg-12 col-md-12 order_summary">
					<h1>Attendees Information</h1>
					<table>
					  <tr class="OrderSummary_Desc">
					  <?php 
					  $custkeys = $displayonTicketFields;
					  foreach($custkeys as $h=> $v){?>
					  <th><?php echo $v;?></th>
					  <?php }?>
					  
					  </tr>
                <?php 
                foreach($attendees['otherAttendee'] as $attndkey => $attndvalues){?>
                <tr class="OrderSummary_Desc">
                <?php 
                	foreach($custkeys as $h=> $v){
                		echo "<td>".$attndvalues[$v]."</td>";
                	}
                ?>
                   
                </tr>
                <?php }?>
            </table>
				</div>

			</div>

    <?php }else{?>
    
    <div class="row">

				<div class="col-lg-12 col-md-12 order_summary">
					<h1>Attendees Information</h1>
					<table>
					  <tr class="OrderSummary_Desc">
					  <?php 
					  
					  $custkeys = array_keys($attendees['mainAttendee'][0]);
					  foreach($custkeys as $h=> $v){?>
				
					  <th><?php echo $v;?></th>
					  <?php }?>
					  
					  </tr>
                <?php 
                foreach($ticketDetails as $tkeys => $tvals ){
	                foreach($attendees['mainAttendee'] as $mainattndkey => $mainattndvalues){?>
		                <tr class="OrderSummary_Desc">
		                <?php 
		                	foreach($custkeys as $h=> $v){
		                		if($v == 'Ticket Name'){?>
		                		<td><?php echo $tvals['name'];?></td>
		                							  <?php }else{
		                		echo "<td>".$mainattndvalues[$v]."</td>";
		                	}
	                }
                }
                ?>
                   
                </tr>
                <?php }?>
            </table>
				</div>

		
    
    <?php }?>

  <?php }?>  
   <div class="col-lg-12 col-md-12 order_summary ">			
          	<?php 
   
          			if(strlen($eventData['eventDetails']['tnc'])>0){
          		?>
          			<h1>Terms &amp; Conditions</h1>
				<ol class="ticket_terms">
				<?php 
          		echo $eventData['eventDetails']['tnc'];?>
          		  </ol>
          		
          <?php 	}?>
       
			</div>
            </div>
    </div>
    </div>
    </div>
    <form action="/delegatepass" method="post" id="delegatepass"  >
		 <input type="hidden"  name="eventsignupId" value="<?php echo $EventSignupId;?>" >
     	<input type="hidden"  name="userId" value="<?php echo $userId;?>" >
	</form>
	<script type="text/javascript">
	 var api_eventMailInvitations = "<?php echo commonHelperGetPageUrl('api_eventMailInvitations')?>";
</script>
    <?php include_once('includes/email_invite.php');?>
	<script>
	
$(function(){
	$('.resendEmail').click(function(){
		var eventsignupId='<?php echo $EventSignupId;?>';
		$("#sendsuccess").html('<img id="success-img" src="../images/me-loading.gif">');
		$.ajax({
			type:'get',
			url:'<?php echo commonHelperGetPageUrl('api_resendDelegateEmail')?>',
			data:{eventsignupId:eventsignupId},
			success:function(res){
				$("#sendsuccess").html(res.response.messages);
				setTimeout(function(){$("#sendsuccess").html('');},2000);
			},
			error:function(res){
				$("#sendsuccess").html(res.responseJSON.response.messages);
				setTimeout(function(){$("#sendsuccess").html('');},2000);
			}
	});


		});

	$('.resendSMS').click(function(){
		var eventsignupid='<?php echo $EventSignupId;?>';
		var mobile='<?php echo $userMobile;?>';
		var eventtitle = '<?php echo $eventName;?>';
		$("#sendsuccess").html('<img id="success-img" src="../images/me-loading.gif">');
		$.ajax({
			type:'GET',
			url:'<?php echo commonHelperGetPageUrl('api_delegateSmsSend');?>',
			data:{eventsignupid:eventsignupid,mobile:mobile,eventtitle:eventtitle},
			success:function(res){
				$("#sendsuccess").html(res.response.messages);
				setTimeout(function(){$("#sendsuccess").html('');},2000);

			},
			error:function(res){
				$("#sendsuccess").html(res.responseJSON.response.messages);
				setTimeout(function(){$("#sendsuccess").html('');},2000);
			}
	});


		});
});
</script>
   <?php
	  if(isset($widgetRedirectUrl)){ 
		 if(strlen(trim($widgetRedirectUrl))>0){ 
   ?>
	  <script>
		 var maxSessTime = 10;
		 var t;
		 var timer_is_on = 0;
		   
		 function timedCount() {
			maxSessTime = maxSessTime - 1;
			document.getElementById("sessionTime").innerHTML = maxSessTime;
			 
			if(maxSessTime == 0)
			{ window.location='<?php echo $widgetRedirectUrl; ?>'; }
			else{t = setTimeout(function(){timedCount()}, 1000);}
		 }
		   
		 function stopCount() {
			clearTimeout(t);
			timer_is_on = 0;
			document.getElementById("timerBox").style.display='none';
		 }
		 timedCount();
	  </script> 
   <?php }
	  }	
   ?>
<?php
   $confirmationpagescripts = $eventData['eventDetails']['confirmationpagescripts'];
   if(isset($confirmationpagescripts) && $confirmationpagescripts != '') {
	  echo $confirmationpagescripts;
   }
?>
