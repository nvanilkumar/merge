<div class="rightArea">
     <?php if ($promoterId == 0) { ?>
    <div class="heading">
        <h2>Add Promoter:<span><?php echo $eventName; ?></span></h2>
    </div>
    <?php } ?> 
    <?php if (isset($output)) { ?>
        <div id="promoterFlashErrorMessage" class="db-alert db-alert-danger flashHide">
            <strong>  <?php echo $output ?></strong> 
        </div>                 
    <?php }
         if (isset($errors)) { ?>
        <div id="promoterFlashErrorMessage" class="db-alert db-alert-danger flashHide">
            <strong>  <?php echo $errors[0]; ?></strong> 
        </div>                 
    <?php }else{ ?>  
    <div class="editFields fs-add-promoter-form" <?php if($promoterId>0){ echo 'style="display:none;"';}?>>
        <form name='addPromoterForm' method='post' action='' id='addPromoterForm'>
            <label>Promoter Name <span class="mandatory">*</span></label>
            <input type="text" class="textfield" name='promoterName' id='promoterName'>
            <label>Email ID <span class="mandatory">*</span></label>
            <input type="text" class="textfield" name='promoterEmail' id='promoterEmail'>
            <label>Promoter Code <span class="mandatory">*</span></label>
            <input type="text" class="textfield" name='promoterCode' id='promoterCode'>
            <label>Your Site URL </label>
            <input type="text" class="textfield" name='orgPromoteURL' id='orgPromoteURL'>
            <div id='codeError' class='error'></div>
            <div class="clearBoth"></div>
            <div class="btn-holder float-right">
                <input name="submit" type="submit" id="promoButton" class="createBtn" value="Save">
                <a href="<?php echo commonHelperGetPageUrl("dashboard-affliate", $eventId); ?>">
                    <span class="saveBtn">Cancel</span> 
                </a>
            </div>
        </form>
    </div>         
    <div class="editFields fs-add-promoter-form">
        <form>
            <?php if ($promoterId > 0) { ?>
                <label>Promoter name <span class="mandatory">*</span></label>
                <input type="text" class="textfield" name='promoterName' id='promoterName' value="<?php echo $name;?>" readonly>
                <div class="clearBoth"></div>
                
                <label>Promoter Email Id <span class="mandatory">*</span></label>
                <input type="text" class="textfield" name='promoterEmail' id='promoterEmail' value="<?php echo $email;?>" readonly>
                <div class="clearBoth"></div>
            <?php } 
			?>
            <label>Promoter URL <span class="mandatory">*</span></label>
            <textarea name='promoterUrl' id='promoterUrl' class="textarea" readonly><?php echo isset($promoterEventURL)?$promoterEventURL:$eventUrl; ?></textarea>
            <div class="clearBoth"></div>
            <label>Iframe Code <span class="mandatory">*</span></label>
            <textarea name='iframeCode' id='iframeCode' class="textarea" readonly><iframe id="ticketWidget" width="100%" height="600px" src="<?php echo $iframeURL; ?>"></iframe></textarea>
            <div class="clearBoth"></div>
            <input type="hidden" class="textfield" id='promoterUrlH' value="<?php echo isset($promoterEventURL)?$promoterEventURL:$eventUrl; ?>">
            <input type="hidden" class="textfield" id='iframeCodeH' value="<iframe width='100%' height='600px' src='<?php echo $iframeURL; ?>'></iframe>">
        </form>
         <?php if ($promoterId > 0) { ?>
        <div class="btn-holder float-right">
            <a href="<?php echo commonHelperGetPageUrl("dashboard-affliate", $eventId); ?>" class="createBtn" style="float:left;">Go Back</a>
        </div>   
        <?php } ?>
    </div>
   <?php }?>

</div>
<script type="text/javascript" language="javascript">
var iframeURL = '<?php echo $iframeURL; ?>';
var eventURL = '<?php echo isset($promoterEventURL)?$promoterEventURL:$eventUrl; ?>';
</script>