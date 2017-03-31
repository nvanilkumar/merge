<style>

    #client_authorize {margin:40px 0 50px 0; float:left;}
    #client_authorize h3, .client_authorize p {color:#333; text-align: left;}
    #client_authorize h3{ font-size:30px; margin-bottom:15px;}
    #client_authorize h3 a{ color:#9063cd;}
    #client_authorize p{font-size: 15px;padding:5px 0 10px 0; margin: 0; color:#666; line-height:22px;}
    #client_authorize b{color:#9063cd; font-weight: normal;}
    #client_authorize .button{background: #9063cd !important;font-size:15px; border-radius: 5px; margin-right: 10px; border:none;text-transform:uppercase;
                              margin-top: 13px; padding: 12px 40px!important; color:#fff; cursor:pointer; width:auto; float:left;}

    #client_authorize .dgrey {background: transparent !important; font-size:15px; border-radius: 5px; margin-right: 10px; margin-top: 13px; padding: 10px 40px!important; color:#9063cd; border:2px solid #9063cd; cursor:pointer; width:auto; float:left; text-transform:uppercase; }


    .textcenter {text-align: center;}
    .api-logosection {padding:50px 0 60px 0;}

    @media only screen and (min-width: 280px) and (max-width: 768px) {
        #client_authorize h3{ font-size:22px; margin-bottom:15px;}
    }
</style>

<div class="page-container">
    <div class="wrap">
        <div class="container">
            <div class="col-md-8 col-sm-8 col-xs-12" id="client_authorize">
                <h3>Allow <a href="#"><?php echo $oauth_details[0]["app_name"] ?></a> to access your MeraEvents.com account?</h3>
                <p>MeraEvents.com takes privacy very seriously. Before allowing access to your account, please ensure that you trust <a href="#"><?php echo $oauth_details[0]["app_name"] ?></a> with your data and that you initiated this request.</p>
                <p>If you have questions or comments, please contact us.</p>
                <p>By proceeding, you agree to the MeraEvents 
                    <a href="<?php echo commonHelperGetPageUrl('terms'); ?>" target="_blank">Terms of Service</a> and <a href="<?php echo commonHelperGetPageUrl('terms'); ?>" target="_blank">Privacy Policy</a>.
                </p>
                <div style="float:left; width:100%; margin-bottom:30px;">
                    <form name="fSignIn" method="post" action="">
                        <input type="hidden" name="redirect_url" id="redirect_url" value="<?php echo $redirect_url;?>">
<!--                        <div class="button">ALLOW</div> 
                        <div class="dgrey">DENY</div>-->
                        
                         <input type="submit" name="authorized" class="button" value="yes" >&nbsp;&nbsp;
                                    <input type="submit" name="authorized" class="dgrey" value="no">
                        
                        <?php if (isset($_GET['call_back'])) { ?>
                            <input type="hidden" name="call_back" value="<?php echo $_GET['call_back']; ?>">
                        <?php } ?>
                    </form>    
                </div>
                <p>Logged in as  <?php   echo $userdata['response']['userData']['name'] ?> (<a href="<?php echo commonHelperGetPageUrl('user-logout'); ?>">not you?</a>).</p>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-12 textcenter api-logosection">
                 <?php 
                        if($oauth_details[0]['app_image'] > 0){
                            $fileUrl=$coludPath.$filedata['response']['fileData'][0]['path'];
                            
                           ?> 
                <img src="<?php echo $fileUrl; ?>" style="width:60%;margin-top:10px;"/>
<!--                <img src="http://placehold.it/300x150" />-->
                 <?php }
                        
                ?>
                
            </div> 
        </div>
    </div>
</div>