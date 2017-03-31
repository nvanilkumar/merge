<div ng-controller="resetPasswordController" class="container" id="ForgotContainer" style="display:none;" >

    <h2 class="innerTopText">Forgot Password</h2>
    <h3 class="innerTopText"></h3>

<div class="row loginContainer" >
        <div class="col-sm-12 rightSide">
            <div class="loginBlog">
<!--            	<span class="success" ng-bind="resetSuccessMsgs"></span>	-->
                    <form novalidate name="resetPasswordForm" id="resetPasswordForm" ng-submit="submitForm(resetPasswordForm.$valid,reset)">
                        <div class="form-group">
                            <label class="login_header_rgt">Enter registered email id to reset password</label>
                            <input ng-change="hideErrorMsgs()" ng-model="reset.email" ng-required="true" name="email" 
                                   ng-pattern="/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/" type="email" id="forgotEmail" class="form-control userFields"  placeholder="Email">
                           <span class="successmsg" ng-bind="resetSuccessMsgs"></span>
                           <span class="error" ng-bind="resetErrorMsgs"></span>    
                            <span class="help-block error  ng-cloak validation-error" ng-show="resetPasswordForm.email.$error.required && resetPasswordForm.$submitted">Email is required</span>
                            <span class="help-block  error ng-cloak validation-error" ng-show="resetPasswordForm.email.$error.pattern && resetPasswordForm.$submitted">Enter valid email id</span>
                        </div>
                                          
                    <button type='submit' class="btn btn-default commonBtn forgotbtn login sbtn" id="ResetPassword">Reset Password</button>

                </form>
                <span> <label >Already Registered? </label> <a onclick="showhideforgot()">Log In</a></span> 
            </div>
        </div>
    </div>
</div>