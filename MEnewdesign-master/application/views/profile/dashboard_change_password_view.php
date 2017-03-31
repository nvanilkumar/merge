<div class="rightArea">
             <?php if (isset($output['response']['messages'][0])) { ?>
        <div id="passwordSuccess" <?php if($output['status']) { ?>class="db-alert db-alert-success flashHide"
                                <?php } else { ?> class="db-alert db-alert-danger flashHide" <?php } ?> >
            <strong>&nbsp;&nbsp;  <?php echo $output['response']['messages'][0]; ?></strong> 
        </div>
    <?php } ?>
    <div class="rightSec">
    </div>
    <div class="heading float-left">
        <h2>Change Password</h2>
    </div>
    <div class="clearBoth"></div>
    <!--Data Section Start-->

    <div class="editFields fs-dashboard-change-password">
        <form name="changePasswordForm" id="changePasswordForm" method="POST" action=''>
            <label>New Password <span class="mandatory">*</span></label>
            <input type="password" class="textfield" name="password" id="password" >
            <label>Confirm Password <span class="mandatory">*</span></label>
            <input type="password" class="textfield" name="confirmPassword"  id="confirmPassword">
            <div><span class='error' id='passwordsError' ></span> </div>
            <input type="submit" class="createBtn float-right"  name="changePasswordSubmit" id="changePasswordSubmit" value="UPDATE">
        </form>

    </div>
</div>