<div class="page-container"><br>
 <div class="container" id="loginContainer" >
          <div id="activemessage">    
 <?php //$message='';
 $loginremember="";
 $userpassword="";
 $useremail="";
     $message= $this->customsession->getData( 'message' );
     $this->customsession->unSetData('message');
     if(!empty($_COOKIE["email"])){
          $useremail= $_COOKIE["email"];  
     }else if(isset($email)){
         $useremail=$email;
     }
     
     if(!empty($_COOKIE["password"])){
          $userpassword= $_COOKIE["password"];  
     }
     if(!empty($_COOKIE["loginremember"])){
          $loginremember= $_COOKIE["loginremember"];  
     }
        
    if($message){ ?>
        <div align="center"  style="color:green"> <?php  
    echo $message; ?> </div>
  <?php   }
    ?>
</div>
<!--        <h2 class="innerTopText">Log In</h2>-->
        <h3 class="innerTopText">Happy to see you return</h3>

        <div class="row loginContainer" ng-controller="loginController">
            <?php include_once('includes/elements/login_social_share.php'); ?>
            <div class="col-sm-6 rightSide">
                <div class="loginBlog">
<!--                    <h2 class="login_header_rgt">Login with email</h2>-->
                    <div class="form-group">
                        <p class="error ng-cloak" ng-bind="loginErrorMsgs" id="loginErrorMsgs"></p>
                    </div>
                    <form novalidate name="loginForm" id="loginForm" ng-submit="submitForm(loginForm.$valid, login)">
                        <div class="form-group">
                            <input ng-change="hideErrorMsgs()" ng-model="login.email" ng-required="true" autofocus
                                   name="email"  value="" ng-init="login.email='<?php echo $useremail?>'"
                                   type="text" class="form-control userFields" id="exampleInputEmail1" placeholder="Email">
                            <p class="error ng-cloak" ng-show="(loginForm.$submitted || loginForm.email.$dirty) && loginForm.email.$error.required">Email is required</p>
                            <p class="error ng-cloak" ng-show="(loginForm.$submitted || loginForm.email.$dirty) && loginForm.email.$error.pattern">Enter valid email id</p>
                        </div>
                        <div class="form-group">
                            <input ng-change="hideErrorMsgs()" ng-model="login.password" ng-init="login.password='<?php echo $userpassword?>'"
                                   ng-required="true" name="password" type="password" class="form-control userFields" id="exampleInputPassword1" placeholder="Password">
                            <p class="error ng-cloak" ng-show="loginForm.password.$error.required && (loginForm.$submitted || loginForm.password.$dirty)">Password is required</p>
                        </div>
                        <div class="checkbox">
                            <label class="rember" >
                                <input ng-model="login.remember" type="checkbox" ng-init="login.remember='<?php echo $loginremember?>'"
                                       name="sport[]" value="football" <?php echo ($loginremember=="true")?  "checked='checked'":""; ?>>Remember me </label>
                            <label class="fwd_pass"><a onclick="showhideforgot()">Forgot password?</a></label> 
<!--                            <label class="fwd_pass"><a href="javascript:void(0);" class="resentLink">Resend activation link</a></label>-->
                        </div>
                        <button type="submit" class="btn btn-default commonBtn login sbtn" style="line-height: 43px">LOG IN</button>
                    </form>
<span> <label >New to MeraEvents?</label> <a href="<?php echo commonHelperGetPageUrl('user-signup'); ?>">Sign Up</a></span> </div>
            </div>
             
        </div>
           
    </div>

    <!--sign up-->
    <?php //include_once('includes/signup.php'); ?>
    <!--End od Signup-->

    <!--Forgot Password-->
    <?php include_once('includes/elements/login_forgot_password.php'); ?>
    <!--end Forgot Password-->
    <!--Activation Page-->
    <?php //include_once('includes/elements/login_activation_link.php'); ?>
    <!--end Activation Page-->
</div>
<!-- /.wrap -->
<script>
var api_userGetUserData = "<?php echo commonHelperGetPageUrl('api_userGetUserData')?>";
var api_UserLogin = "<?php echo commonHelperGetPageUrl('api_UserLogin')?>";
    </script>

<?php //include("includes/event_header.php"); ?> 