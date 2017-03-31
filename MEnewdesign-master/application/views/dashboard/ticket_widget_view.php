<div class="rightArea">
      <div class="heading float-left">
        <h2>Ticket Widget Code: <span><?php echo $eventName;?></span></h2>
      </div>
      <div class="clearBoth"></div>
      <!--Data Section Start-->
      
      <div class="previewSec">
      	<div class="fs-form fs-form-widget-setting">
			<h2 class="fs-box-title">Preview</h2>
			<img alt="Ticket Widget" src="<?php echo $this->config->item('images_static_path'); ?>ticket_widget.jpg" />
      	</div>
      	<div class="fs-form fs-form-widget-setting">
			<h2 class="fs-box-title">Ticket Fields Preview</h2>	
	        <div class="ticketFields fs-form-content">
	            <ul class="fs-color-codes">
	                <li>
	                  <span class="fs-color-code-for">Event Title Text Color</span>
	                  <div class="colorCode"> <!-- <span class="code pink"></span><span class="value">#ba36a6</span> -->
	                  	<input class="color colorpicker" id="event_title_color" value="66ff00">
	                  </div>
	                </li>
	                <li>
	                	<span class="fs-color-code-for">Heading Background Color</span>
	                	<div class="colorCode"> <!-- <span class="code green"></span><span class="value">#ba36a6</span> -->
	                		<input class="color colorpicker" id="heading_bg_color" value="ff6600">
						</div>
					</li>
	                <li>
	                	<span class="fs-color-code-for">Ticket Text Color</span>
	                	<div class="colorCode"> <!-- <span class="code pink"></span><span class="value">#ba36a6</span> -->
	                		<input class="color colorpicker" id="ticket_txt_color" value="ff0000">
						</div>
	                </li>
	                <li>
	                	<span class="fs-color-code-for">Book Now Button Color</span>
	                	<div class="colorCode"> <!-- <span class="code pink"></span><span class="value">#ba36a6</span> -->
	                		<input class="color colorpicker" id="book_bt_color" value="000000">
						</div>
	                </li>
	             </ul>
		         <form id="ticketwidget" name="ticketwidget">
		           <label>Iframe Height in Pixels ( Ex : 700 )</label>
		           <input type="text" class="textfield2" id="iframe_height" name="Iframeheight" value="600">
		           <label>Redirect URL(Will redirect to mentioned URL after booking success)</label>
		           <input type="text" class="textfield" name="redirect_url" id="redirect_url">
		         </form>
	          	 <button type="button" class="createBtn float-right" name="widget_button" id="widget_button">generate code</button>
	        </div>
      	</div>
      	<div class="fs-form fs-form-widget-setting">
			<h2 class="fs-box-title">Ticket Widget Preview</h2>    
	        <div class="ticketFields fs-form-content">
	          <div class="WidgetPreview">
	              <iframe id="widget_frame" width="100%" height="600px" src="<?php echo commonHelperGetPageUrl('ticketWidget','','?eventId=' . $eventId);?>"> </iframe>
	          </div>
	        </div>
      	</div>
      	<div class="fs-form fs-form-widget-setting">
			<h2 class="fs-box-title">Embed the tickets</h2>	
	        <div class="ticketFields fs-form-content">
	          <p>If you want to embed the tickets in your website, Copy below HTML code and paste it on
	            your website</p><br>
	          <div class="generate">
	            <textarea readonly="readonly" cols="80" rows="4" id="text_area"><iframe id="ticketWidget" src="<?php echo commonHelperGetPageUrl('ticketWidget','','?eventId='.$eventId.'&ucode=organizer'); ?>" width="100%" height="600px" frameborder="0px" ></iframe></textarea>
	          </div>
	           <input type="hidden" id='url_val' value="<?php echo commonHelperGetPageUrl('ticketWidget','','?eventId='.$eventId.'&ucode=organizer'); ?>"/>
	        </div>
      </div>
    </div>

