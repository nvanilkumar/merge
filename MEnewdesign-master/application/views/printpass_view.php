<?php 
$eventsignup = $this->uri->segment(2);
$regNo =  isset($eventsignup)?$eventsignup:'';
$userEmail = '';

if(!empty($this->input->post())){
	if(isset($eventsignupDetails)){
		$configCustomDatemsg = json_decode(CONFIGCUSTOMDATEMSG,true);
		$configCustomTimemsg =  json_decode(CONFIGCUSTOMTIMEMSG,true);
		$configTransactionDateonPass = json_decode(CONFIGTRANSACTIONDATEONPASS,true);
	$regNo = $this->input->post('regno');
	$enteredEmail = $this->input->post('useremail');
	$eventUrl = $eventData['eventUrl'];
	$eventName = $eventData['title'];
	$userEmail = $userDetail['email'];
	$eventId = $eventData['id'];
	$userId = isset($eventsignupDetails['userid'])? $eventsignupDetails['userid']:$this->customsession->getData('userId');
	$userMobile = $userDetail['mobile'];
	$barcodeNumber = $eventsignupDetails['barcodenumber'];
	$EventSignupId = $eventsignupDetails['id'];
	$venueName = $eventData['location']['venueName'].", ".$eventData['location']['cityName'];
	$convertedStartTime=convertTime($eventData['startDate'],$eventData['location']['timeZoneName'],TRUE);
	$convertedEndTime=convertTime($eventData['endDate'],$eventData['location']['timeZoneName'],TRUE);
	}
}else if(strlen($eventsignup)>0){
	if(isset($eventsignupDetails) ){
		$configCustomDatemsg = json_decode(CONFIGCUSTOMDATEMSG,true);
		$configCustomTimemsg =  json_decode(CONFIGCUSTOMTIMEMSG,true);
		$configTransactionDateonPass = json_decode(CONFIGTRANSACTIONDATEONPASS,true);
	//$regNo = $this->input->get('regno');
	$userEmail = $this->customsession->getData('userEmail');
	$enteredEmail =  $this->customsession->getData('userEmail');
	$eventUrl = $eventData['eventUrl'];
	$eventName = $eventData['title'];
	$userMobile = $userDetail['mobile'];
	$eventId = $eventData['id'];
	$userId = isset($eventsignupDetails['userid'])? $eventsignupDetails['userid']:$this->customsession->getData('userId');
	$barcodeNumber = $eventsignupDetails['barcodenumber'];
	$EventSignupId = $eventsignupDetails['id'];
	$venueName = $eventData['location']['venueName'].", ".$eventData['location']['cityName'];
	$convertedStartTime=convertTime($eventData['startDate'],$eventData['location']['timeZoneName'],TRUE);
	$convertedEndTime=convertTime($eventData['endDate'],$eventData['location']['timeZoneName'],TRUE);
	}
	
}
$ticketpriceClass = 'col-sm-6';
if($eventSettings['displayamountonticket'] == 1){
	$ticketpriceClass = 'col-sm-4';
}


?>
<div class="page-container">
<div class="wrap">  
<div class="container print_ticket"> 
	<form action="/delegatepass" method="post" id="delegatepass"  >
		 <input type="hidden"  name="eventsignupId" value="<?php echo $EventSignupId;?>" >
     	<input type="hidden"  name="userId" value="<?php echo $userId;?>" >
	</form>
     <input type="hidden"  name="regno_get" id="regno_get" value="<?php echo $regNo;?>" >
     <input type="hidden"  name="email_get" id="email_get" value="<?php echo $userEmail;?>" >
     <div class="row intro">  
 	  
  <?php if(isset($message) && $message != '' ){?>
 <div class="col-md-6 PrintTck_Attendee" style="margin-left:0px;">
<div class="norecords">
	    <?php echo $message;?></div>
	    </div>
	    </div>
    <?php }
     if(strlen($this->uri->segment(2))<=0){
    ?>   

  <div class="col-md-6 PrintTck_Attendee">
    <form method="POST" action="" id="printpass-form" >
    <div class="form-group">
      <span>Registration No <span class="err-msg">*</span></span>
      <input type="text" class="form-control userFields" id="userreg_no" name="regno" value="<?php echo $regNo;?>" >
      <label class="error regno"></label>
    </div>

    <div class="form-group">
      <span>Registration Email <span class="err-msg">*</span></span>
      <input type="text" class="form-control userFields" id="userEmail" name="useremail" value="<?php if(isset($enteredEmail)){echo $enteredEmail;}?>" >
        <label class="error useremail"></label>
    </div>

    <div class="form-group">
    <button class="btn btn-default sbtn login gobtn" href="javascript:void()" type="submit" id="printpass">GO</button>
    </div>
</form>
  </div>
  <div class="col-md-5 PrintTck_Attendee fright">
    <p style="font-size: 16px; margin: 10px 0 20px 0;">Please enter your Registration Number followed by Email ID to print your Delegate Pass</p>
    <p style="font-size: 16px;">For more details please <a href="<?php echo commonHelperGetPageUrl("contactUs"); ?>" target="_blank">Contact Us</a>
    <!--<br>contact us @ <?php //echo GENERAL_INQUIRY_MOBILE; ?> <br>
    Email us @  <?php //echo GENERAL_INQUIRY_EMAIL; ?></p>-->
    <!-- <div class="Viral-SocailShare">
      <span class="invitefirends"><i class="icon1 icon1-invitefriend"></i></span>
      <span><a href="#"><i class="icon1 icon1-facebook"></i></a></span>              
      <span><a><i class="icon1 icon1-twitter"></i></a></span>
       <span> <i class="icon1 icon1-linkedin"></i></span>
      <span> <i class="icon1 icon1-google-plus"></i></span>
    </div> -->
   <!--  <p>( or ) Email the below link to your friends.<br>
    <a class="linkcolor">http://stage.meraevents.com/event/entertainment-category&reffCode=ijT2fcFp</a></p> -->
  </div>

</div>
<?php }?>
 <!-- Start of Print Ticket Html -->    
<?php if(!empty($ticketDetails)){?>
				<div class="row intro">
				<h1>Print your ticket now!</h1>
				<a class="btn btn-primary borderGrey print_btn" target="_new" id="delprintpass" href="/delegatepass/<?php echo $EventSignupId."/".urlencode($userEmail);?>">PRINT TICKET</a>
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
											<?php  
								if(isset($configCustomDatemsg[$eventId]) && !isset($configCustomTimemsg[$eventId])){
									echo $configCustomDatemsg[$eventId]. " | ".allTimeFormats($convertedStartTime,4).' to '.allTimeFormats($convertedEndTime,4);
								} else if(isset($configCustomTimemsg[$eventId]) && !isset($configCustomDatemsg[$eventId])){
									echo convertDate($convertedStartTime);if ($convertedStartTime != $convertedEndTime)  { echo " - ". convertDate($convertedEndTime); } echo  " | ".$configCustomTimemsg[$eventId];
								}else if(isset($configCustomTimemsg[$eventId]) && isset($configCustomTimemsg[$eventId])){
									 echo $configCustomDatemsg[$eventId]. " | ".$configCustomTimemsg[$eventId];
								}else{
									echo convertDate($convertedStartTime);if ($convertedStartTime != $convertedEndTime)  { echo " - ". convertDate($convertedEndTime); } echo " | ".allTimeFormats($convertedStartTime,4).' to '.allTimeFormats($convertedEndTime,4);
								}?>
             </span> 
             <?php if($eventData['eventMode'] != 1){?>
             <span class="leftsection"><i
								class="icon icon-google_map_icon"></i><?php echo $eventData['fullAddress'];?>
             </span>
             <?php }?>
						</div>

						<div class="col-lg-4 PrintTck_UserInfo">
            <?php  //foreach($attendees['mainAttendee'] as $attndkey => $attndvalues){?>
                    <p><?php $fullName ="Full Name". ":". $attendees['mainAttendee'][0]['Full Name'] ;echo $fullName;?> </p>
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
                               $ticketamount = 0;
                               $totaldiscountamount=0;
                               $ticketTaxes=array();
             		foreach($ticketDetails as $tkey => $tval ){
             			$ticketamount +=$tval['amount'];
             			$totaldiscountamount+= $tval['discountamount']+$tval['referraldiscountamount']+$tval['bulkdiscountamount'];
             			foreach($tval['ticketTaxes'] as $tax => $taxvalues){
             				$ticketTaxes[$tax]+= $taxvalues;
             			}
             			?>
					 	<ul class="PrintTck_TicketType">
						<li class="<?php echo $ticketpriceClass;?>"><span class="Ticket_Name"><?php echo $tval['name']; ?></span>
							<span class="Summary_desc" style="font-size:10px;"><?php echo $tval['description']; ?></span>
						</li>
						<li class="<?php echo $ticketpriceClass;?>"><span class="Ticket_Name"><?php echo $tval['ticketquantity'];?></span>
						</li>
						   <?php if($eventSettings['displayamountonticket'] == 1){?>
							
                                                <li class="<?php echo $ticketpriceClass;?>"><span class="Ticket_Name"><?php if($tval['amount'] != 0) { echo $tval['amount']." ".$eventsignupDetails['currencyCode']; } else{ echo $tval['amount']; }?></span>
                                                
							<!--  <span class="Summary_desc">Incl of all Taxes, Excluding Extra Charges</span> -->
							</li>
							<?php }?>
								</ul>
					<?php } ?>
				<?php 
							$eventsignuptotalamountinticket =$eventsignupDetails['totalamount']>0?round($eventsignupDetails['totalamount']):0;
							$eventsignuptotalamount = $eventsignupDetails['totalamount']>0?round($eventsignupDetails['totalamount']):0;?>
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
							if(!empty($ticketTaxes)){
					foreach($ticketTaxes as $taxlabelkey => $taxtotalamount){
						?>
                    	<p class="TotalAmount_Ticket">	<?php echo $taxlabelkey.":".$taxtotalamount." ".$eventsignupDetails['currencyCode'];?></p>
              <?php }}?>
						
						<?php if($eventsignupDetails['eventextrachargeamount']>0){
              						$eventsignuptotalamountinticket= $eventsignuptotalamountinticket-$eventsignupDetails['eventextrachargeamount'];
              					}?>
							
							<p class="TotalAmount_Ticket">Purchase Amount : <?php if($eventsignuptotalamountinticket>0){echo round($eventsignuptotalamountinticket)." ".$eventsignupDetails['currencyCode'] ;}else{echo "0 "; }?><br>
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
           
            <div class="row">
     <div class="col-lg-12 col-md-12 order_summary ">
          <div class="form-group">
              <label class="fs-sendmail-go">Send email to <span class="err-msg">*</span></label>
      <input type="text" class="form-control userFields" id="sentuseremail" value="<?php echo $enteredEmail;?>">
       <a class="senddelemail" href="javascript:void()">GO</a>
       <span id="sendsuccess" style="  top: 10px;  position: relative;  left: 10px;"></span>
       <label class="error" id="sentuser" style="float:left; padding:10px; line-height:normal;width: 100%;"></label>
    </div>
      </div>
    </div>
            </div>
  
        
        <?php }?>
        
    </div>
    </div>
<script>
var api_emailPrintpass = '<?php echo commonHelperGetPageUrl('api_emailPrintpass')?>';

          		</script>
    
