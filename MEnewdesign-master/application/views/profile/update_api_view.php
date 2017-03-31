
<div class="rightArea">

<!--    <div id="" class="db-alert db-alert-success flashHide">
        <strong>&nbsp;&nbsp;  <?php echo $message; ?></strong> 
    </div>

    <div id="" class="db-alert db-alert-danger flashHide">
        <strong>&nbsp;&nbsp;  <?php echo $message; ?></strong> 
    </div>-->

    <!-- <div class="heading">
        <h2>CREATE API</h2>
    </div> -->
    <!--Data Section Start-->
    <div class="fs-form">
        <h2 class="fs-box-title">Update API</h2>
        <div class="editFields">
            <?php 
           
                         if($appList['response']['total'] > 0){
                             $appDetails=$appList['response']['appDetails'];
                             foreach($appDetails as $details){
                         
                         ?> 
            <form name="frmAppSettings" id="" method="post" action="" enctype="multipart/form-data">
                <label>Client Id <span class="mandatory">*</span></label>
                <input type="text" class="textfield" name="client_id" value="<?php echo $details['client_id'];?>" readonly="">
                <label>Client Secret Key <span class="mandatory">*</span></label>
                <input type="text" class="textfield" name="clientsecret" value="<?php echo $details['client_secret'];?>" readonly="">
                <label>Application Name <span class="mandatory">*</span></label>
                <input type="text" class="textfield" id="appName" name="appName" value="<?php echo $details['app_name'];?>">
 
                <div class="clearBoth"></div>
                <label>Application callback URL <span class="mandatory">*</span></label>
                <input type="text" class="textfield" id="callbackUrl" name="callbackUrl" value="<?php echo $details['redirect_uri'];?>">

                <label>Application Image <span class="mandatory"></span></label>

                <input class="eventGallery" type="file" name="appImage" id="" >
<!--                <img src="<?=$app_details["0"] ["app_image"] ?>" />-->
                
                <?php 
                 
                        if($details['app_image'] > 0){
                            $fileUrl=$coludPath.$filedata['response']['fileData'][0]['path'];
                            
                           ?> 
                <img src="<?php echo $fileUrl; ?>" style="width:60%;margin-top:10px;"/>
                 <input type="hidden" name="oldappimageid" value="<?php echo $details['app_image'];?>"/>
                       <?php }
                        
                ?>
                
               
                <p>&nbsp;</p><p>&nbsp;</p>

                <label>Access Level</label>

                <div class="valid_date valid_bottom">
                    <?php 
                    $readValue=$writeValue="";
                    if($details['access_level'] == 0){
                        $readValue=' checked="checked"';
                    }else{
                        $writeValue=' checked="checked"';
                    }
                    ?>
                    <ul>
                        <li> 
                            <label>
                                 
                                    <input type="radio" name="access_level" value="0" 
                                           <?php echo $readValue;?> >
                                
                                Read </label>
                        </li>                    
                        <li>
                            <label> 
                                    <input type="radio" name="access_level" value="1" <?php echo $writeValue;?>> 
                                Write</label>
                        </li>
                    </ul>
                </div>

                <div class="clearBoth height10"></div>

                <div class="seo-buttons float-right">
                    <input type="submit" name="submit" class="createBtn" value="submit">  
                    <a href="javascript:void(0);">
                        <input type="submit" name="cancel" class="saveBtn" value="cancel"> 
                    </a>  
                </div>
            </form>
            <?php  } 
                         }
                         ?>
        </div>
    </div>
</div>
