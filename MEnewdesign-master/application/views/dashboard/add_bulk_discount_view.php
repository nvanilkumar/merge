<div class="rightArea">
    <div class="heading">
        <h2>Add / Edit Bulk Discount Code : <span><?php echo $eventName; ?></span></h2>
    </div>
        <?php //For all the errors of server side validations
    if (isset($addDiscountOutput) &&!$addDiscountOutput['status']) {
    ?>
    <div id="discountFlashErrorMessage" class="db-alert db-alert-danger flashHide">
        <strong>  <?php print_r($addDiscountOutput['response']['messages'][0]); ?></strong> 
    </div>  
<?php } ?>
    <div class="editFields fs-add-discount-box">
        <form name='addDiscountForm' method='post' action='' id='addDiscountForm'>
            <input type="hidden" class="textfield" id="hiddenEventEndDate" name="hiddenEventEndDate" value="<?php if (isset($discountDetails['response']['discountList'][0]['startdatetime'])) {
                echo 0;
            }elseif(isset($eventEndDate)) {
                echo allTimeFormats(convertTime($eventEndDate, $eventTimeZoneName, TRUE),1);              
            }?>" >
            <input type="hidden" class="textfield" id="hiddenStartDate" name="hiddenStartDate" readonly value="<?php
            if (isset($discountDetails['response']['discountList'][0]['startdatetime'])) {
                echo allTimeFormats(convertTime($discountDetails['response']['discountList'][0]['startdatetime'], $eventTimeZoneName, TRUE),1);             
            } else {
                echo 0;
            }
            ?>" >            
            <input type="hidden" class="textfield" id="hiddenStartTime" name="hiddenStartTime" readonly value="<?php
            if (isset($discountDetails['response']['discountList'][0]['startdatetime'])) {
                echo allTimeFormats(convertTime($discountDetails['response']['discountList'][0]['startdatetime'], $eventTimeZoneName, TRUE),4);
            } else {
                echo 0;
            }
            ?>" >            
            <input type="hidden" class="textfield" id="hiddenEndDate" name="hiddenEndDate" readonly value="<?php
            if (isset($discountDetails['response']['discountList'][0]['enddatetime'])) {
                 echo allTimeFormats(convertTime($discountDetails['response']['discountList'][0]['enddatetime'], $eventTimeZoneName, TRUE),1);             
            } else {
                echo 0;
            }
            ?>" >            
            <input type="hidden" class="textfield" id="hiddenEndTime" name="hiddenEndTime" readonly value="<?php
            if (isset($discountDetails['response']['discountList'][0]['enddatetime'])) {
                echo allTimeFormats(convertTime($discountDetails['response']['discountList'][0]['enddatetime'], $eventTimeZoneName, TRUE),4);
            } else {
                echo 0;
            }
            ?>" >                                                                                                                                                                                                                           


            <input type="hidden" class="textfield" id="DiscountId" name="DiscountId" readonly value="<?php
            if (isset($discountDetails['response']['discountList'][0]['id'])) {
                echo $discountDetails['response']['discountList'][0]['id'];
            }
            ?>" >
            <label>Discount Name <span class="mandatory">*</span></label>                                                                                                                           
            <input type="text" class="textfield" id="discountName" name="discountName" <?php if (isset($discountDetails['response']['discountList'][0]['name'])) { ?>value="<?php echo $discountDetails['response']['discountList'][0]['name'] ?>"<?php } ?> >
            <div class="discountDateClass">
                <ul>
                    <li>
                        <label>Valid From Date <span class="mandatory">*</span></label>
                        <input type="text" class="textfield" id="discountStartDate" name="discountStartDate">  
                    </li>
                    <li>
                        <label>Valid From Time <span class="mandatory">*</span></label>
                         <div class="input-group bootstrap-timepicker">
                        <input type="text" class="textfield" id="discountStartTime" name="discountStartTime" data-toggle="dropdown">
                         </div>
                    </li>
                </ul>           
            </div>
            
            <div class="discountDateClass">
                <ul>
                    <li>
                        <label>Valid till Date <span class="mandatory">*</span></label>
                        <input type="text" class="textfield" id="discountEndDate" name="discountEndDate">
                    </li>
                    <li>
                        <label>Valid till Time <span class="mandatory">*</span></label>
                         <div class="input-group bootstrap-timepicker">
                        <input type="text" class="textfield" id="discountEndTime" name="discountEndTime" data-toggle="dropdown">
                        </div>
                    </li>
                </ul>
            </div>
            <div><span class='error' id='dateTimeError' ></span></div>
            <label style="float:left;">Discount Value <span class="mandatory">*</span> <span class="suggestiontext-g">(Enter the discount value here. For ex.200 for 200Rs)</span> </label>
            <input type="text" class="textfield" id="discountValue" name="discountValue" <?php if (isset($discountDetails)) { ?>value="<?php echo $discountDetails['response']['discountList'][0]['value']; ?>"<?php if($discountDetails['response']['discountList'][0]['totalused'] > 0){ echo "disabled"; } } ?> >
            <label>Discount Type <span class="mandatory">*</span></label>
            <div class="valid_date valid_bottom">
                <ul>
                    <li> 
                        <label><input type="radio" name="amountType" value="percentage" <?php
                               if (isset($discountDetails)) {
                                   if($discountDetails['response']['discountList'][0]['totalused'] > 0){echo "disabled";}
                                   if ($discountDetails['response']['discountList'][0]['calculationmode'] == 'percentage') {
                                       ?> checked="checked" <?php
                                   }
                               } else {
                                   ?> checked="checked"<?php } ?> >
                        Percentage </label></li>                    
                    <li>
                        <label><input type="radio" name="amountType"  <?php
                               if (isset($discountDetails)) {
                                   if($discountDetails['response']['discountList'][0]['totalused'] > 0){echo "disabled";}
                                   if ($discountDetails['response']['discountList'][0]['calculationmode'] == 'flat') {
                                       ?> checked="checked" <?php
                                   }
                               }
                               ?> value="flat">
                        Amount</label></li>
                </ul>
            </div>
            <div class="clearBoth height10"></div>

            <p class="discwidth100" style="float:left; width:25%; margin-right:30px;">
                <label>Tickets From <span class="mandatory">*</span><br><br></label>
                <input type="text" class="textfield" style="width:100%; float:left;" name="ticketsFrom" id="ticketsFrom" <?php if (isset($discountDetails['response']['discountList'][0]['minticketstobuy'])) { ?>value="<?php echo $discountDetails['response']['discountList'][0]['minticketstobuy']; ?>"<?php } ?> >
            </p>
            <p class="discwidth100" style="float:right; width:65%;">
                <label style="margin-left:10px;">Up To <span class="suggestiontext-g">(Leave blank if you want to give discount for unlimited no of tickets)</span></label>
                <input type="text" class="textfield" style="width:40%; float:left; margin-left:15px;" name="ticketsUpto" id="ticketsUpto" <?php if (isset($discountDetails['response']['discountList'][0]['maxticketstobuy'])) { if((($discountDetails['response']['discountList'][0]['maxticketstobuy']==' ')||($discountDetails['response']['discountList'][0]['maxticketstobuy']==0))){}else{ ?>value="<?php echo $discountDetails['response']['discountList'][0]['maxticketstobuy']; ?>"<?php }} ?> >
                <span id='ticketsUptoError' class='error'></span>
            </p>
            <div><span id='ticketsRangeError' class='error'></span></div>
            <div class="clearBoth height10"></div>
            <label>Ticket Type <span class="mandatory">*</span></label>
            <div class='child'>
                <?php
                $i = 0;
                if ($ticketDetails['response']['total'] > 0) {
                    foreach ($ticketDetails['response']['ticketName'] as $ticket) {
                        if (isset($discountDetails) && $ticket['type'] == 'paid' && $ticket['soldout'] == 0 && $ticket['quantity'] >= $ticket['totalsoldtickets']) {
                            ?>
                <div class="BulkDiscClass"><label> <input type="checkbox" id="<?php echo $ticket['id']; ?>" name="ticketIds[]" class="ticketCheckbox " value="<?php echo $ticket['id']; ?>" <?php
                                                                if (isset($ticketIdList)) {
                                                                    if (in_array($ticket['id'], $ticketIdList)) {
                                                                        ?>checked='checked'<?php
                                                                    }
                                                                }
                                                                ?>>
            <?php echo $ticket['name']; $i++;?></label> 
  </div>                        
                    <?php     }elseif (($ticket['type'] == 'paid' || $ticket['type'] == 'addon') && $ticket['soldout'] == 0 && strtotime($ticket['enddatetime']) > strtotime(allTimeFormats('',11)) && $ticket['quantity'] >= $ticket['totalsoldtickets']) {
                            ?>
                            <div class="BulkDiscClass"><label>   <input type="checkbox" id="<?php echo $ticket['id']; ?>" name="ticketIds[]" class="ticketCheckbox " value="<?php echo $ticket['id']; ?>" <?php
                                                                if (isset($ticketIdList)) {
                                                                    if (in_array($ticket['id'], $ticketIdList)) {
                                                                        ?>checked='checked'<?php
                                                                    }
                                                                }
                                                                ?>>
            <?php echo $ticket['name']; ?></label> 
                            <?php $i++;
                            ?>  </div>


                            <?php }
                    }
                    ?>
                    <?php if ($i == 0) { ?>
                    <div class="db-alert db-alert-info"> 
                        <span id="noTicket">No active tickets found for this event</span>   
                    </div>    
    <?php }
} elseif (isset($ticketDetails['response']['total']) && $ticketDetails['response']['total'] == 0) { ?>
                 <div class="db-alert db-alert-info">
                    <span id="noTicket" >No tickets found for this event</span>  
                 </div>     
<?php }
?>    
                <div id='checkboxErrorDiv' style='display:none;'><span class='error' id='ticketCheckboxError' style='font-size:14px; padding-top: 12px;'></span> </div>
            </div>

            <div class="btn-holder float-right">
                <input type="submit" name='discountSubmit' class="createBtn" id="discountSubmit" value='Save'>
                <a href="<?php echo commonHelperGetPageUrl('dashboard-bulkdiscount', $eventId); ?>" class="saveBtn"><span ></span>Cancel</a>           
            </div>            
        </form>
    </div>
</div>