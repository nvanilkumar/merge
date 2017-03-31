<div class="page-container">
<div class="container" id="ActivateContainer">
    <h3 class="innerTopText"></h3>

    <div class="row loginContainer">

        <div class="col-sm-12 rightSide">
            <div class="loginBlog" ng-controller="resendlinkController">
                       <h2 class="login_header_rgt"> Resend Activation Link</h2>
                               
                     <form novalidate name="resendlink" id="resendlink" ng-submit="submitForm(resendlink.$valid,resend)">
                         <div class="form-group">  
                             <label>Enter your Email here to receive a Activation Link</label>
                          <?php   if($email){?>
                                  <input ng-change="hideErrorMsgs()" ng-model="resend.email" ng-required="true"  ng-init="resend.email='<?php echo $email; ?>'" 
                                         id="email" name="email" ng-pattern="/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/" type="email" class="form-control userFields">
                            <?php }else{ ?>                         
                         <input ng-change="hideErrorMsgs()" ng-model="resend.email" ng-required="true" id="email" 
                                name="email" ng-pattern="/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/" type="email" class="form-control userFields"  placeholder="Enter Your Email" 
                                value="<?php echo (isset($email))?$email:"";  ?>">
                         <?php }?>
                         <div id="responseMsg">

                    </div> 
                            <span class="help-block error  ng-cloak validation-error"  ng-show="resendlink.email.$error.required && resendlink.$submitted">Email is required</span>
                            <span class="help-block error  ng-cloak validation-error" ng-show="resendlink.email.$error.pattern && resendlink.$submitted">Please Enter valid email</span>
                    </div>
<button ng-disabled="isProcessing" type="submit" class="btn btn-default commonBtn forgotbtn login sbtn"  id="verifyEmail">CONFIRM</button>

                </form>
    
            </div>
        </div>
    </div>
</div>    
      
</div>
<?php include("includes/event_header.php"); ?> 