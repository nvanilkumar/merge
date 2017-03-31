<?php if (isset($gallery) && count($gallery) > 0) { ?>
    <div class="col-sm-12 eventDetails" id="event_gallery">
        <h3>Gallery</h3>
        <div class="row">

            <div class="col-lg-12 event_image_thumb">
                <ul>
                    <?php foreach ($gallery as $key => $image) {
                        ?>
                        <li> <a href="<?php echo $image['imagePath'] ?>" class="" data-lightbox="image-2"> 
                                <img src="<?php echo $image['thumbnailPath'] ?>" alt="" width="200" height="200"> </a> 
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
<?php } ?>