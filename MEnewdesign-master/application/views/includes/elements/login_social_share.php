
<div class="col-sm-6 leftSide">

    <div class="loginBlog">
        <!--        <h2 class="login_header_lef">Register with your social account</h2>-->
        <button onclick="fb2Auth();" type="button" class="btn btn-default btn-md commonBtn Lfb"><span class="icon-login_fb"></span>Login with Facebook</button>
        <button onclick="return redirectToTwitter();" type="button"  class="btn btn-default btn-md commonBtn Ltwt"><span class="icon-login_tweet"></span>Login with Twitter</button>
        <button type="button" id="gp_login" class="btn btn-default btn-md commonBtn Lgoogle" onclick="javascript:googleAuth()" ><span class="icon-login_google"></span>Login with Google+</button>
    </div>
    <div class="socialIconsMobile">
        <ul>
            <li><a onclick="fb2Auth();" href="javascript:void(0)" class="icon-fb fb"></a></li>
            <li><a onclick="return redirectToTwitter();" href="javascript:void(0)" class="icon-tweet tweet"></a></li>
            <li><div id="gp_loginResponcive"><a href="javascript:void(0)" class="icon-google google"  onclick="javascript:googleAuth()" ></a></div></li>
        </ul>
    </div>
</div>
<script>
                var FB_APP_ID = '<?php echo $this->config->item('fb_app_id'); ?>';
                var TWITTER_APP_KEY = '<?php echo $this->config->item('twitter_app_key'); ?>';
                var GOOGLE_APP_ID = '<?php echo $this->config->item('google_app_id'); ?>';
                var h = parseInt($(window).height())*0.6; 
                var w = parseInt($(window).width())*0.6 ;
                function fb2Auth(){
					var fburl = "<?php echo $fbloginUrl;?>";
					window.open(fburl, "", "width="+w+", height="+h+"top=0,left=0");
					//window. fburl;
					
					/*var FBWindow = window.open(fburl, "", "width="+w+", height="+h+"top=0,left=0");  
					
					if(navigator.appVersion.search("Safari") == '-1')
					{ }
					else
					{
						var timer = setInterval(function() {   
							if(FBWindow.closed) {  
								clearInterval(timer);  
								window.location.reload();  
							}  
						}, 1000);
					}*/
					
                }
				
</script>

