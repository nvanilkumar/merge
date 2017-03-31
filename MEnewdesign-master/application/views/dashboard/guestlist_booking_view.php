<div class="rightArea">
     <?php  
        $guestListBookingFlashMessage=$this->customsession->getData('guestListBookingSuccessMessage');
        $this->customsession->unSetData('guestListBookingSuccessMessage');
    ?>
    
    <?php if($guestListBookingFlashMessage){?>
        <div class="db-alert db-alert-success flashHide">
            <strong>  <?php echo $guestListBookingFlashMessage; ?></strong> 
        </div>
    <?php }?>   
                 <?php if(isset($messages)){?>
                <?php if($status == false) { ?>
                <div id="Message" class="db-alert db-alert-danger flashHide">
            <strong>  <?php echo $messages;?></strong> <?php } ?>
        </div>                 
        <?php }?>  
    <div class="heading">
        <h2>GUEST LIST BOOKING FOR MERAEVENTS : <?php echo  $eventName;?></h2>
        <p style="font-size:14px; line-height: normal; margin-bottom:10px;">
           We are accepting only CSV files to use this page. These transactions will be recorded as offline transactions. Download the <a href="http://dn32eb0ljmu7q.cloudfront.net/content/demos/guest-booking/guest-list-sample.csv">Sample </a>file here. We will process only first 20 rows (excluding header) in CSV file, and ignores rest all if any. And make sure, the total Quantity should not exceed 50-60.
        </p>
    </div>
    <!--Data Section Start-->

    <div class="fs-form">
        <h2 class="fs-box-title">Guest List Booking</h2>
        <div class="editFields">
        <form enctype="multipart/form-data" method="post" name="guestlistbooking" id="guestlistbooking" action=''>
     
            <div class="clearBoth"></div>

            <label>Ticket Type <span class="mandatory">*</span></label>
            <label>
                <span style="top:10px;" class="float-left icon-downarrow"></span>
                <select id="ticketId" name="ticketId">
                    <option value="">Select Ticket</option>
  <?php foreach ($tickets as $ticket) { ?>
                        <option   value="<?php echo $ticket['id']; ?>"> <?php echo $ticket['name']; ?></option>
                    <?php } ?>
                </select>
            </label>

 <label>CSV File <span class="mandatory">*</span></label>
            <input type="file" name="csvfile" id="csvfile" class="textfield">
            <input type="submit" name="guestBooking" id="guestBooking" class="createBtn float-right" value="Upload"/>
        </form>
    </div>
    </div>
</div>
</div>

<script>
var api_bookingOfflineBooking = "<?php echo commonHelperGetPageUrl('api_bookingOfflineBooking');?>";
var api_getTicketCalculation = "<?php echo commonHelperGetPageUrl('api_getTicketCalculation');?>";
</script>