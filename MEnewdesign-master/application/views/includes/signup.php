<div class="container" id="signUpContainer" style="display:none;" >
    <h2 class="innerTopText">Sign Up</h2>
    <h3 class="innerTopText">Sign Up to MeraEvents in less than 30 seconds</h3>
    <div class="row loginContainer">
<?php include_once('includes/elements/login_social_share.php');?>
      <div class="col-sm-6 rightSide">
        <div class="loginBlog">
        <h2 class="login_header_rgt">Or create a new account</h2>
          <form>
           <div class="form-group">
              <input type="email" class="form-control userFields" id="exampleInputEmail1" placeholder="Name">
            </div>
             <div class="form-group">
              <input type="email" class="form-control userFields" id="userEmail" placeholder="Email address">
            </div>
             <div class="form-group"> 
             
              <input type="password" class="form-control userFields" id="exampleInputPassword1" placeholder="Password">
            </div>
            <div class="form-group">
              <input type="text" class="form-control userFields" id="exampleInputEmail1" placeholder="Mobile number">
            </div>
           <p class="text">By signing up, I agree to MeraEvents <a href="">terms of service</a>, <a href="">privacy policy</a>, and <a href="">cookie policy</a>.</p>
           
            <!-- <input type="submit" class="btn btn-default commonBtn login" onclick="showhideactivation()">SIGN UP</button> -->
			
			<a onclick="showhideactivation()" class="btn btn-default commonBtn login" style="line-height: 43px">SING UP</a>
			
			
			
              <span><label class="al_reg"> <label >Already Registered?</label> <a onclick="showhide()">Log In</a></label></span>
            
            </div>
          </form>
          
      </div>
    </div>
  </div>
