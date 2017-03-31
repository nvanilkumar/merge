<div class="page-container">
<div class="container" id="ActivateContainer">
     <h3 class="innerTopText"></h3>

    <div class="row loginContainer">

        <div class="col-sm-12">
            <div class="loginBlog" ng-controller="changePasswordController">
                       <h2 class="login_header_rgt">Change Password</h2>
                         <span class="success" ng-bind="changeSuccessMsgs"></span>	      
                    <form novalidate name="changePasswordForm" id="changePasswordForm" ng-submit="submitForm(changePasswordForm.$valid,change)">
  	                        <div class="form-group">
                            <input ng-change="hideErrorMsgs()" ng-model="change.password" ng-required="true" name="password" type="password" ng-minlength="6" class="form-control userFields" id="exampleInputPassword1" placeholder="Password">
                             <span class="help-block   ng-cloak validation-error" ng-show="changePasswordForm.password.$error.required && changePasswordForm.$submitted">Password is required</span>
                             <span class="help-block   ng-cloak validation-error" ng-show="changePasswordForm.password.$error.minlength && changePasswordForm.$submitted">Your password must be at least 6 characters long</span>
                        </div>
                           <div class="form-group">
                            <input ng-change="hideErrorMsgs()" ng-model="change.confirmPassword" ng-required="true" name="confirmPassword" type="password"  ng-minlength="6" class="form-control userFields" id="exampleInputConfirmPassword1" placeholder="Confirm Password">
                             <span class="help-block   ng-cloak validation-error" ng-show="changePasswordForm.password.$error.required && changePasswordForm.$submitted">Confirm Password is required</span>
                             <span class="help-block   ng-cloak validation-error" ng-show="changePasswordForm.confirmPassword.$error.minlength && changePasswordForm.$submitted">Your password must be at least 6 characters long</span>
                        </div>
                        
                       <input value="<?php if($this->uri->segment(2)) { ?> <?php echo $this->uri->segment(2);}else{echo '';
                       
                       } ?>" type="hidden" class="textfield" ng-model="change.token" name="token">
                        <span class="error" ng-bind="changeErrorMsgs"></span> 
                        <button   type="submit" class="btn btn-default commonBtn forgotbtn login"  id="changePasswordSubmit">
                            UPDATE</button>
          	         
                           <div class="clearBoth"></div>
                         </form>
            </div>
        </div>
    </div>
</div>    
<?php include_once('includes/event_header_scroll.php'); ?>       
</div>
<script>
var api_UserchangePassword = "<?php echo commonHelperGetPageUrl('api_UserchangePassword')?>";
</script>


