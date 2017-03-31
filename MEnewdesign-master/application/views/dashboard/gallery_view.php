<div class="rightArea">
    <div class="heading">
        <h2>Add Gallery: <span><?php echo $eventName; ?></span></h2>
    </div>
<!--    <ul class="tabs" data-persist="true">
        <li><a href="#Images">Images</a></li>
                <li><a href="#Videos">Videos</a></li>
    </ul>-->
    <div id="Images">
        <div>
            <?php if (isset($galleryImages) && $galleryImages['status'] && $galleryImages['response']['total'] <= 15) { ?>
            	<div class="fs-form">
					<h2 class="fs-box-title">Gallery</h2>  
	                <div class="editFields">
	                    <form enctype="multipart/form-data" name='galleryForm' id='galleryForm'  method='post' action='' >
	                        <!--              <div class="eventUploads demo_multi empty" data-url="" data-multiple="true" >
	                                        <div class="add">                   
	                                             <input type="file"  name='galleryFiles[]' multiple>
	                                        </div>
	                                    </div>-->
	                        <input type="file"  name='eventGallery[]' id='eventGallery' multiple>
	                        <div>
	                            <input type="submit" name='gallerySubmit' id='gallerySubmit' class="createBtn float-right" value='UPLOAD'>
	                        </div>
	                    </form>
	                    <div class='error'><span id='galleryNoSelectError'></span> </div>                
	                    <div id='galleryDiv'><span class='error' id='galleryError' style='font-size:18px;'> <?php
	                            if (isset($insertedGallery) && !$insertedGallery['status']) {
	                                echo $insertedGallery['response']['messages'][0];
	                            }
	                            ?></span> </div>
	        <!--            <script>
	                        $(funtion(){
	                            $('.eventUploads.demo_multi').eventUploads();
	                        }
	                        )
	        
	                    </script> -->
	                </div>
            	 </div>  
               <?php } ?>  
                <div class="GalleryView-Thumb">
                    <?php if (isset($galleryImages) && $galleryImages['status'] && $galleryImages['response']['total'] > 0) { ?>  
                        <ul class="GalleryThumb">              
                            <?php foreach ($galleryImages['response']['galleryList'] as $gallery) { ?>

                                <li>
                                    <img src="<?php echo $gallery['thumbnailPath']; ?> " width="200" height="200">
                                    <p class="Gallery-Delete"><a href="<?php echo commonHelperGetPageUrl('dashboard-gallery', $eventId . '&' . $gallery['imageId']); ?>"><img src=<?php echo $this->config->item('images_static_path') . 'close-icon_fonts.png'; ?> /></a></p>
                                </li>

                            <?php } ?>       
                        </ul> <?php } elseif ($galleryImages['response']['total'] == 0) { ?>
                    <div><div class="db-alert db-alert-info"><strong>There is no gallery for this event.You can add it now</strong> </div> <?php } ?> </div>
                </div>
            </div>
        </div>
       <?php if (isset($deleteGallery) && !$deleteGallery['status']) { ?>
        <div id="galleryErrorMessage" class="db-alert db-alert-danger flashHide">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            <strong>&nbsp;&nbsp;  <?php echo $deleteGallery['response']['messages'][0]; ?></strong> 
        </div>
    <?php } ?>  
<!--    If videos have to be uploaded-->
    <!--    <div id="Videos">
            <div class="editFields float-left">
                <div class="demo_multi empty" >
                    <div>
                        <label>Paste Embedded iframe Code Here</label>          
                        <input type="text" name="file" class="textfield">
                        <button type="button" class="createBtn float-right">SAVE</button>
                    </div>
                </div>
            </div>
        </div>-->
</div>