
<div class="rightArea">
    <div class="heading float-left">
        <h2>Viral Ticketing: <span><?php echo $eventName; ?> (<?php echo $eventId; ?>) </span></h2>
        <p>In the "Referral Bonus" section you can choose whether the referrer receives a commission and how much this is from the ticket price if they recommend the the event
            successfully using viral ticketing. You can decide here whether the referral bonus is a percentage or a set amount from the ticket price when the new attendee has bought.</p>
    </div>
    <div class="float-right read-more pointerCursor"><a><u>Read more</u></a></div>
    <div id='moreContent' style='display:none'>
    <div>
        In the <span style='color:#09C'>"Referral Bonus"</span> section you can choose whether the referrer receives a commission and how much this is from the ticket price if they recommend the the event successfully using viral ticketing. You can decide here whether the referral bonus is a percentage or a set amount from the ticket price when the new attendee has bought using this referral URL.  
        <br>
    </div>   
    <div>
    In the <span style='color:#09C'>"Discount for Friends"</span> section you can determine the discount which the new attendee receive off your tickets. You can decide here whether the new attendee receives a percentage or a set amount from the ticket price.
    </div> 
    </div>
    <div class="float-right view-less  pointerCursor" style='display:none;'><a><span style='color:#515151;'><u>View less</u></span></a></div>    
    
    <div class="clearBoth"></div>
    <div class="clearBoth"></div>
            <?php //For all the errors of server side validations
    if (isset($messages)) {
    ?>
    <div id="commFlashErrorMessage" class="db-alert db-alert-danger flashHide">
        <strong>  <?php echo $messages; ?></strong> 
    </div>  
<?php } ?>
    <div class="tablefields">
        <form id="viralTicket" method="post" action="">
<!--            <table width="100%" border="0" cellspacing="0" cellpadding="0" data-tablesaw-mode="swipe" data-tablesaw-minimap>-->
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <thead>
                    <tr>
                        <th scope="col">Ticket Type</th>
                        <th scope="col" >Ticket Price</th>
                        <th scope="col">Enable / Disable</th>
                        <th scope="col">Commission Type</th>
                        <th scope="col" >Referral Bonus</th>
                        <th scope="col" >Discount for Friends</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $ticketsCount = count($ticketData);
                    if ($ticketsCount > 0) {
                        foreach ($ticketData as $key => $val) {
                        	$saleDone = $val['salesDone'];
                                $ids[] = $key;
                            ?>
                            <tr>
                                <td><?php echo $val['name']; ?></td>
                                <td><?php echo $val['currencyCode'].' '.$val['price']; ?></td>
                                <td><input type="checkbox" class="enableDisable" name="status<?php echo $val['id']; ?>" value="1" <?php if ($val['status'] == 1) { ?> checked="checked" <?php } ?> <?php if($val['salesDone'] === 1){ echo "salesDone=1";}?> ></td>
                                <td width="30%"><div class="fs-summary-detail">
                                        <label style="float:left;"> <input type="radio"   name="type<?php echo $val['id']; ?>" value="flat"  <?php if ($val['type'] == 'flat') { ?> checked="checked" <?php } ?> <?php if($saleDone == 1){echo "disabled";}?>> Amount </label>
                                    </div>
                                    <div  class="fs-summary-detail">

                                        <label style="float:left;"> <input type="radio"  name="type<?php echo $val['id']; ?>" value="percentage" <?php if ($val['type'] == 'percentage' || $val['type'] == '') { ?> checked="checked" <?php } ?> <?php if($saleDone == 1){echo "disabled";}?>> Percentage</label>
                                    </div>
                                </td>
                                <td><input type="text" class="textfield3 mandatory_class"  id="referrercommission<?php echo $val['id']; ?>" name="referrercommission<?php echo $val['id']; ?>" value="<?php echo (($val['referrercommission']==0)?'':$val['referrercommission']);?>" <?php if($saleDone == 1){echo "salesDone='1' disabled";}?>></td>
                                <td><input type="text" class="textfield3 mandatory_class" <?php if($val['salesDone'] === 1){ echo "disabled";}?> id="receivercommission<?php echo $val['id']; ?>" name="receivercommission<?php echo $val['id']; ?>" value="<?php echo (($val['receivercommission']==0)?'':$val['receivercommission']);  ?>" <?php if($saleDone == 1){echo "salesDone='1' disabled";}?>>
                                	 <input type="hidden"  name="salesDone<?php echo $val['id']; ?>" value="<?php echo $saleDone;?>" />
                                </td>
                            </tr>
                            <?php
                        }
                    
                        ?>
<?php
} else {
    ?>
    <tr> <td colspan="6">                
            <div id="noViralTicketingMessage" class="db-alert db-alert-info">                    
                    <strong>Viral ticketing not available, as you dont have paid tickets for this event</strong> 
                </div>
           </td>
    </tr>                        
<?php    
}
?>
                </tbody>
            </table>
            <div class="float-right">
                <input type="submit" class="createBtn float-right" name="viralTicketSubmit" id="viralTicketSubmit" value="Save & Exit"/>
            </div>
        </form>
        <input type="hidden" name="radioFieldname" id="radioFieldname" value="<?php echo json_encode($ids) ?>" />
    </div>
</div>