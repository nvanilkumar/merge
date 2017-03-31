<div class="rightArea">
    <?php
    $bankDetailListMessage = $this->customsession->getData('bankDetailMessage');
    $this->customsession->unSetData('bankDetailMessage');
    $bankDetailErrorMessage = $this->customsession->getData('bankDetailErrorMessage');
    $this->customsession->unSetData('bankDetailErrorMessage');
    ?>

    <?php if ($bankDetailListMessage) { ?>
        <div   class="db-alert db-alert-success flashHide" ><?php echo $bankDetailListMessage; ?></div><?php } ?> 
        <?php if ($bankDetailErrorMessage) { ?>
            <div   class="db-alert db-alert-danger flashHide" ><?php echo $bankDetailErrorMessage; ?></div><?php } ?> 



            <div class="heading">
                <h2>Bank Details</h2>
            </div>
            <div class="editFields fs-bank-details">
                <form id="bankDetailsForm" name="bankDetailsForm" method="post" action="">
                    <label>Account Holder Name <span class="mandatory">*</span></label>
                    <input type="text" class="textfield" name="accountName" id="accountName" value="<?php echo $bankDetails['accountname']; ?>">
                    <label>Account Number <span class="mandatory">*</span></label>
                    <input type="text" class="textfield" name="accountNumber" id="accountNumber" value="<?php echo $bankDetails['accountnumber']; ?>">
                    <label>Bank Name <span class="mandatory">*</span></label>
                    <input type="text" class="textfield" name="bankName" id="bankName" value="<?php echo $bankDetails['bankname']; ?>">
                    <label>Branch <span class="mandatory">*</span></label>
                    <input type="text" class="textfield" name="branch" id="branch" value="<?php echo $bankDetails['branch']; ?>">
                    <label>IFSC Code <span class="mandatory">*</span></label>
                    <input type="text" class="textfield" name="ifsccode" id="ifsccode" value="<?php echo $bankDetails['ifsccode']; ?>">
                    <label>Account Type</label>
                    <p style="float:left; width:30%">
                        <label><input type="radio" name="accountType" id="accountType" value="savings" class="textfield"  <?php if ($bankDetails['accounttype'] == 'Savings' || $bankDetails['accounttype'] == '') { ?> checked="checked" <?php } ?> style="float:left; width:auto;margin-right:10px;margin-top:5px;">Savings</label></p>
                    <p style="float:left; width:30%">
                        <label><input type="radio" name="accountType" id="accountType"  value="current" class="textfield" <?php if ($bankDetails['accounttype'] == 'Current') { ?> checked="checked" <?php } ?>  style="float:left; width:auto;margin-right:10px; margin-top:5px;">Current</label>
            </p>
            <input type="submit" name="bankDetails"  value="SAVE"class="submitBtn fs-btn float-right"/>
        </form>
    </div>
</div>
