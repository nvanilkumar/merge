<div class="rightArea">
    <div id="offlineFlashSuccess" class="db-alert db-alert-success flashHide" style="display:none"> 
    </div>
    <div id="offlineFlashError" class="db-alert db-alert-danger flashHide" style="display:none"> 
    </div>
    <?php if (isset($messages)) { ?>
        <?php if ($status == false) { ?>
            <div id="Message" class="db-alert db-alert-danger flashHide">
                <strong>  <?php echo $messages; ?></strong> <?php } ?>
        </div>                 
    <?php } ?>
    <div class="heading">
        <h2>Book Offline Tickets <?php echo $eventId; ?></h2>
    </div>
    <!--Data Section Start-->

    <div class="fs-form">
       <h2 class="fs-box-title">Offline Booking </h2>
        <div class="editFields">
        <form method="post" name="offlinebooking" id="offlinebooking" >
            <label>Event Name <span class="mandatory">*</span></label>
            <label>

                <span style="top:10px;" class="float-left icon-downarrow"></span>

                <select name="eventId" id="event">
                    <option value="">Select Event</option>
                    <?php foreach ($events as $event) { ?>
                        <option  promoterId="<?php echo $event['promoterid']; ?>" value="<?php echo $event['id']; ?>" <?php if ($eventId == $event['id']) { ?>selected <?php } ?>> <?php echo $event['title']; ?></option>
                    <?php } ?>
                </select>
            </label>
            <div class="clearBoth"></div>
<!--            <input type="text" id="promoterId" value="<?php echo $event['promoterid']; ?>">-->
            <label>Ticket Type <span class="mandatory">*</span></label>
            <label>
                <span style="top:10px;" class="float-left icon-downarrow"></span>
                <select id="ticket" name="ticketId">
                    <option value="">Select Ticket</option>
                    <?php foreach ($eventTickets as $ticket) { ?>
                        <option   value="<?php echo $ticket['ticketid']; ?>"> <?php echo $ticket['ticketname']; ?></option>
                    <?php } ?>
                </select>
            </label>

            <label>Quantity <span class="mandatory">*</span></label>
            <label>
                <span style="top:10px;" class="float-left icon-downarrow"></span>
                <select id="quantity" name="quantity">
                    <option value="">Select Quantity</option>

                </select>
            </label>

            <label>Have a Discount Code</label>
            <label>
                <input type="text" id="discountCode"  name="discountCode" class="offdisc_textfield">            
                <button type="button" onclick="offlineCalculate()"  id="apply" class="createBtn">Apply</button>
                <button type="button" id="cancel" class="saveBtn">Clear</button>            
            </label>

            <div class="clearBoth">&nbsp;</div>

            <label style="width:40%; float:left;">Total Amount</label>

            <label class="totalAmount" id="total" style="width:40%; float:left; font-size:20px;"></label>

            <div class="clearBoth">&nbsp;</div>
            <label>Name <span class="mandatory">*</span></label>
            <input type="text" name="name" id="name" class="textfield">

            <label>Email Id <span class="mandatory">*</span></label>
            <input type="text" name="email" id="email" class="textfield">

            <label>Mobile No <span class="mandatory">*</span></label>
            <input type="text" name="mobile" id="mobile" class="textfield">

            <input type="submit" name="offlineBooking" id="offlineBooking" class="createBtn float-right" value="BOOK NOW"/>
            <div id="dvLoading" class="loadingopacity" style="display:none; float: right;  margin-left: 80px;  margin-top: 18px; width: 50px;"><img src="http://dn32eb0ljmu7q.cloudfront.net/images/static/loading.gif" class="loadingimg"></div>
        </form>
        </div>
    </div>
</div>
<script>
var api_promoteticketsData = "<?php echo commonHelperGetPageUrl('api_promoteticketsData')?>";
var api_promoteofflineTickets = "<?php echo commonHelperGetPageUrl('api_promoteofflineTickets')?>";
var api_promotesetStatus = "<?php echo commonHelperGetPageUrl('api_promotesetStatus')?>";
var api_bookingOfflineBooking = "<?php echo commonHelperGetPageUrl('api_bookingOfflineBooking');?>";
var api_getTicketCalculation = "<?php echo commonHelperGetPageUrl('api_getTicketCalculation');?>";
</script>