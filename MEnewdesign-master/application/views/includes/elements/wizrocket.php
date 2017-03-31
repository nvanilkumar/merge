<script>
    var wizrocket_id = '<?php echo $this->config->item('wizrocket_id'); ?>';
    var wizrocket = {event:[], profile:[], account:[]};
    wizrocket.account.push({"id": wizrocket_id});
    (function () {
        var wzrk = document.createElement('script');
        wzrk.type = 'text/javascript';
        wzrk.async = true;
        wzrk.src = ('https:' == document.location.protocol ? 'https://d2r1yp2w7bby2u.cloudfront.net' : 'http://static.clevertap.com') + '/js/a.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(wzrk, s);
    })();
   // wizrocket.event.push("user registered", {"Event Name":"'my event'","Event Category":"Professional" });
</script>
<?php 
$callclass = $this->router->fetch_class();
$callmethod = $this->router->fetch_method();
if($callclass == 'event'){
	$eventtitle = isset($eventData['title'])?$eventData['title']:'';
	$categoryname = isset($eventData['categoryName'])?$eventData['categoryName']:'';
switch($callmethod){
	case "index":
		echo '<script>
        		 wizrocket.event.push("Event Viewed", {"Event Name":"'.$eventtitle.'","Event Category":"'.$categoryname.'" });
                		var pTotal =0;
                		var totDis = 0;
                		var tktQty =0;
                $("#content").click(function(){
                		 pTotal = parseInt($("#total_amount").text());
								$("select.ticket_selector").each(function() {
								    var num = parseInt(this.value, 10);
								    if (!isNaN(num)) {
								        tktQty += num;
								    }
								});
                		   wizrocket.event.push("Booking Initiated",{
     								 "Event Name":"'.$eventtitle.'",
      								 "Amount":pTotal,
      								 "Discounted Amount":totDis,
      								 "Number of Tickets":tktQty
   							 });
                		
                		
                		});
           </script>';
		break;
	default:
		break;
}
}
if($callclass == 'search'){
	$keyword = $this->input->get('keyword');;
	switch($callmethod){
		case "index":
			echo '<script>
			  wizrocket.event.push("Event Searched",{
      		"Keyword":"'.$keyword.'"
   			 });
           </script>';
			break;
		default:
			break;


	}

}


if($callclass == 'payment'){
	switch($callmethod){
		case "index":
			echo '<script>
      				
      				$("#paynow").click(function(){
      				var tcktqnty = $("#ticketQnty").html();
      				var paidamnt = $(".totalAmountid").attr("amnt");
      				if(paidamnt >0){
      						var tcktype = "paid";
      					}else{
							var tcktype = "free";
      					}
  				 wizrocket.event.push("Attendee Signedup",{
        		"Number":tcktqnty,
        		"Ticket Type":tcktype
    			});
	});
				</script>';
			break;
		default:
			break;

	}

}

if($callclass == 'confirmation'){
	
	$eventtitle = $eventData['title'];
	$categoryname = $eventData['categoryName'];
	switch($callmethod){
		case "index":
			echo '<script>
					  wizrocket.profile.push("Attendee logged in",{
     								 "Email":"'.$this->customsession->getData('userEmail').'",
      								 "Name":"'.$this->customsession->getData('userName').'",
      								 "Phone Number":"'.$this->customsession->getData('userMobile').'",
   							 });
					
        			wizrocket.event.push("Charged",
    {
        "Amount": "'.$eventsignupDetails['totalamount'].'",
        "Payment Mode": "'.$eventsignupDetails['paymentMode'].'", 
        "Event Category":"'.$eventData['categoryName'].'",
        "Event City":"'.$eventData['location']['cityName'].'",
         "Charged ID":"'.$eventsignupDetails['id'].'",
        "Transaction ID":"'.$eventsignupDetails['id'].'",
  		 "Discounted Amount":"'.$discountamount.'",
		 "Currency Type":"INR",
        "Items": [';
			foreach ($ticketDetails as $k=>$value){
			    $ticketamount+= $value['totalamount'] ;
                $ticketDiscount = $value['discountamount']+$value['referraldiscountamount']+$value['bulkdiscountamount'];
                $discountamount+= $ticketDiscount;
			      echo       '{';
			      echo  "'Event Name':'".$eventData['title']."',";
			      echo   "'Ticket Type':'". $value['name']."',";
			      echo   "'Discount Amount':'" .$ticketDiscount."',";
			      foreach($tval['ticketTaxes'] as $taxLabel=>$taxAmount){
			      	$eventsignuptaxtotal += $taxAmount;
			      	echo  $taxLabel.": ".$taxAmount.',';
			      }
			      echo "'Quantity':'".$value['ticketquantity']."',";
			      echo "'Ticket Amount':'".$value['totalamount']."'";
		    	  echo "},";
			 }  
			
		     echo ']
		    
    		} );
           </script>';
			break;
		default:
			break;


	}
}


?>