<div class="rightArea">
    <?php if(isset($seo) && $seo['status'] == TRUE) { ?>
        <div id="seoMessage" class="db-alert db-alert-success flashHide">
            <strong>&nbsp;&nbsp;  <?php echo $message; ?></strong> 
        </div>
    <?php } ?> 
    <?php if(isset($seo) && $seo['status'] == FALSE) { ?>
        <div id="seoMessage" class="db-alert db-alert-danger flashHide">
            <strong>&nbsp;&nbsp;  <?php echo $message; ?></strong> 
        </div>
    <?php } ?>
    <div class="heading">
    	<h2>SEO FOR EVENT : <?php echo $eventTitle;?></h2>
    </div>
	  <!--Data Section Start-->
	<div class="fs-form">
		<h2 class="fs-box-title">SEO</h2>
		<div class="editFields">
		    <form name="seoForm" id="seoForm"method="post" action="" >
		      <label>Canonical URL</label>
		      <input type="text" class="textfield" name="conanicalurl" value="<?php echo $seoDetails['conanicalurl'];?>">
		      <!-- <div style="" class="tagscontainer"> <span class="tags">#Tag1</span><span class="tags">#Tag2</span><span class="tags">#Tag3</span> <span class="tags">#Tag1</span><span class="tags">#Tag2</span><span class="tags">#Tag3</span> </div> -->
		      <div class="clearBoth"></div>
		       <label>Event SEO Title</label>
		      <input type="text" class="textfield" name="seotitle" value="<?php echo $seoDetails['seotitle'];?>">
		
		      <label>Event SEO Keywords</label>
		      <input type="text" class="textfield" name="seokeywords" value="<?php echo $seoDetails['seokeywords'];?>">
		
		      <label>Event SEO Description</label>
		      <textarea class="textarea" name="seodescription" ><?php echo $seoDetails['seodescription'];?></textarea>
		
			  <div class="seo-buttons float-right">
				  <input type="submit" name="submit" class="createBtn" value="submit">  
				  <a href="customefields.html">
					  <input type="submit" name="cancel" class="saveBtn" value="cancel"> 
				  </a>  
			  </div>
			</form>
		</div>
	</div>
</div>
