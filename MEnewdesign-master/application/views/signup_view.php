<div class="page-container" ng-controller="signupController">

    <br>

    <!--sign up-->
    <div class="container" id="signUpContainer"  >
<!--    <h2 class="innerTopText">Sign Up</h2>-->
    <h3 class="innerTopText">Sign Up</h3>
    <div class="row loginContainer">
      
         <?php include_once('includes/elements/login_social_share.php');?>
        
        
      <div class="col-sm-6 rightSide">
        <div class="loginBlog">
<!--        <h2 class="login_header_rgt"> Create a new account</h2>-->
<!--        <p ng-if="commonError!=''" class="help-block   ng-cloak validation-error" id="commonErrorId">{{commonError}}</p>
        Display Errors if any
        -->
<div ng-show="commonErrors.length > 0">
  <div ng-repeat="error in commonErrors">
   <p class="help-block   ng-cloak validation-error" id="commonErrorId" ng-cloak>{{error}}</p>
  </div>
</div>


        <form name="signupForm" id="signupForm" novalidate ng-submit="submitted = true" >
           <div class="form-group">
               <input ng-change="hideErrorMsgs()" type="text" class="form-control userFields" style="text-transform: capitalize;"
                      name="name" id="nameid" placeholder="Full Name" ng-model="signup.name" required >
              <p ng-show=" submitted && signupForm.name.$error.required  " class="help-block   ng-cloak validation-error">Please enter your name</p>
           
            </div>
             <div class="form-group">

                 <input ng-change="hideErrorMsgs()" ng-model="signup.email" ng-required="true" name="email" ng-blur="emailBlur()"
                        ng-pattern="/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/" type="email" class="form-control userFields" id="emailid" placeholder="Email address">
                 <p class="error" ng-init="emailNameStatus=false" ng-cloak ng-show="emailNameStatus">Email already exists, please wait while you are being redirected to the login page</p>          
                 <p class="help-block  ng-cloak validation-error"  ng-show="signupForm.email.$error.required && submitted">Email is required</p>
                            <p class="help-block  ng-cloak validation-error"  ng-show="signupForm.email.$error.pattern && submitted">Enter valid email id</p>

        </div>
            
            <div class="form-group"> 
             <!--<pclass="alignRight"><a href="#">Forgot password?</a></p>-->
                <input ng-change="hideErrorMsgs()" type="password" class="form-control userFields" id="passwordid" name="password"  ng-minlength="6" placeholder="Password" ng-model="signup.password" required>
                <p class="help-block   ng-cloak validation-error"  ng-show="signupForm.password.$error.required && submitted">Password is required</p>
                <p class="help-block   ng-cloak validation-error"  ng-show="signupForm.password.$error.minlength && submitted">Please enter minimum 6 characters</p>
            </div>
<!--            <div class="form-group"> 
             
                <input  type="text" class="form-control userFields" 
                        id="location" name="location"  placeholder="location">
               
            </div>-->
            <div class="form-group">
            <input type="hidden" name="countryId" ng-model = "signup.countryId"  ng-init="signup.countryId =<?php echo $defaultCountryId; ?>" ng-value="<?php echo $defaultCountryId; ?>" />
             <input type="hidden" name="countryphoneCode" ng-model = "signup.countryphoneCode"  ng-init="signup.countryphoneCode = <?php echo $defaultCountryPhoneCode; ?>" ng-value="<?php echo $defaultCountryPhoneCode; ?>" />
              <div style="float: left;
    position: absolute;padding: 11px 10px;"><a href="javascript:void(0);" style="color: #aaa;font-size: 17px;">&nbsp;<?php echo $defaultCountryPhoneCode;?></a></div>
             <input ng-change="hideErrorMsgs()" type="text" class="form-control userFields" id="phonenumberid"
                       ng-minlength="10" ng-maxlength="10" name="phonenumber" placeholder="Mobile number" 
                       ng-model="signup.phonenumber" ng-pattern="/^[0-9+]+$/" required style="padding-left: 50px;"> 
                  <p class="help-block   ng-cloak validation-error"  ng-show="signupForm.phonenumber.$error.required && submitted">Phone number is required</p>
                 <p class="help-block  ng-cloak validation-error" ng-show="signupForm.phonenumber.$error.minlength && submitted">Please enter minimum 10 characters</p>
                 <p class="help-block  ng-cloak validation-error" ng-show="signupForm.phonenumber.$error.maxlength && submitted">You can enter upto 10 characters only</p>
                 <p class="help-block  ng-cloak validation-error" ng-show="signupForm.phonenumber.$error.pattern && submitted">Please enter only numeric value</p>
                   </div>
            <p class="text">By signing up, I agree to MeraEvents<a href="<?php echo commonHelperGetPageUrl('terms'); ?>" target="_blank"> T&C </a> and <a href="<?php echo commonHelperGetPageUrl("privacypolicy") ?>" target="_blank">Privacy Policy</a></p>
           
            <!-- <input type="submit" class="btn btn-default commonBtn login" onclick="showhideactivation()">SIGN UP</button> -->
		
			<button type="button" ng-click=" submitSignup(signup)" class="btn btn-default commonBtn sbtn login" style="line-height: 43px" >SIGN UP</button>
			
              <span><label class="al_reg"> <label >Already registered?</label> <a href="<?php echo commonHelperGetPageUrl('user-login'); ?>" target="_self">Log In</a></label></span>
            </form>
            </div>
          
          
      </div>
    </div>
  </div>
    <!--End od Signup-->

<!--    Successfull Registration-->
<div class="container" id="ActivateContainer" style="display: none;" >
    <h2 class="innerTopText">Thanks For Signing Up!</h2>
    <h3 class="innerTopText"></h3>
   
    <div class="row loginContainer">
      
      <div class="col-sm-12 rightSide">
        <div class="ConfirmBlog">
            <h2 class="login_header_rgt" style="font-size: 18px;" ng-cloak>Check {{registeredEmail}} for next steps. </h2>
          <form>
			   
          </form>
            <span> <label >Did not receive email? Please <a href="<?php echo site_url();?>resendActivationLink?email={{registeredEmail}}" > click here</a> to resend activation mail. </label> </span> 
		  </div>
      </div>
    </div>
  </div>

<!-- End of    Successfull Registration-->
</div>
<!-- /.wrap -->
<script src="https://maps.googleapis.com/maps/api/js?sensor=true&libraries=places"></script>
<script type="text/javascript">
var api_UsersignupEmailCheck = "<?php echo commonHelperGetPageUrl('api_UsersignupEmailCheck')?>";
var api_Usersignup = "<?php echo commonHelperGetPageUrl('api_Usersignup')?>";
function initialize() {
    var input = document.getElementById('location');
    var options = {types: ['(cities)'], componentRestrictions: {country: 'ind'}};

    new google.maps.places.Autocomplete(input, options);
}
             
google.maps.event.addDomListener(window, 'load', initialize);
</script>