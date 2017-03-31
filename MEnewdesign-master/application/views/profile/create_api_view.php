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
        <h2 class="fs-box-title">CREATE API</h2>
        <div class="editFields">
            <form name="frmAppSettings" id="frmAppSettings" method="post" action="" enctype="multipart/form-data">
                <label>Application Name <span class="mandatory">*</span></label>
                <input type="text" class="textfield" name="appName" value="">
                <!-- <div style="" class="tagscontainer"> <span class="tags">#Tag1</span><span class="tags">#Tag2</span><span class="tags">#Tag3</span> <span class="tags">#Tag1</span><span class="tags">#Tag2</span><span class="tags">#Tag3</span> </div> -->
                <div class="clearBoth"></div>
                <label>Application callback URL <span class="mandatory">*</span></label>
                <input type="text" class="textfield" name="callbackUrl" value="">

                <label>Application Image <span class="mandatory"></span></label>

                <input type="file" name="appImage" id="" class="eventGallery">

                <p>&nbsp;</p><p>&nbsp;</p>

                <label>Access Level</label>

                <div class="valid_date valid_bottom">
                    <ul>
                        <li> 
                            <label><span class="custom-radio selected"><input type="radio" name="access_level" value="0" checked="checked"></span>
                                Read </label></li>                    
                        <li>
                            <label><span class="custom-radio"><input type="radio" name="access_level" value="1"></span>
                                Write</label></li>
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
        </div>
    </div>
</div>
