<!--important-->
<div class="page-container">
	<div class="wrap">

		<div class="container eventsHappening mediakit">
			 <h1 class="text-center textpink">Download Our MEdia Kit</h1>
			<!--<p class="text-center">MeraEvents is India's largest web portal with a comprehensive database of event listings covering various categories. MeraEvents understands the importance of brand awareness and our media kit has been designed keeping in mind easy access to our logos and brand promotion material.</p>-->
			<table class="media-kit table table-responsive table-striped" cellpadding="0" cellspacing="0">
				<tbody><tr>
					<th class="kit-col-1"><img src="<?php echo $this->config->item('images_static_path'); ?>me-logo.svg" width="96" height="56" alt="Media Kit" title="Media Kit"></th>
					<th class="kit-col-2">Image Format</th>
					<th class="kit-col-3">Image Size</th>
					<th class="kit-col-4">Download</th>
				</tr>
<!--				<tr>
					<td class="kit-col-1 grey-light">MeraEvents Web Size</td>
					<td class="kit-col-2 grey-light">JPEG</td>
					<td class="kit-col-3 grey-light">144x91 Pixels</td>
					<td class="kit-col-4 grey-light"><a href="http://www.meraevents.com/download/download.php?f=MeaEvents_Logo.jpg">Download</a></td>
				</tr>
				<tr>
					<td class="kit-col-1 grey-dark">MeraEvents High Resolution</td>
					<td class="kit-col-2 grey-dark">JPEG</td>
					<td class="kit-col-3 grey-dark">1320x720 Pixels</td>
					<td class="kit-col-4 grey-dark"><a href="http://www.meraevents.com/download/download.php?f=MeaEvents_Logo_High.jpg">Download</a></td>
				</tr>
				<tr>
					<td class="kit-col-1 grey-light">MeraEvents Web Size</td>
					<td class="kit-col-2 grey-light">PNG</td>
					<td class="kit-col-3 grey-light">144x91 Pixels</td>
					<td class="kit-col-4 grey-light"><a href="http://www.meraevents.com/download/download.php?f=MeaEvents_Logo.png">Download</a></td>
				</tr>
				<tr>
					<td class="kit-col-1 grey-dark">MeraEvents High Resolution</td>
					<td class="kit-col-2 grey-dark">PNG</td>
					<td class="kit-col-3 grey-dark">1320x720 Pixels</td>
					<td class="kit-col-4 grey-dark"><a href="http://www.meraevents.com/download/download.php?f=MeaEvents_Logo_High.png">Download</a></td>
				</tr>
				<tr>
					<td class="kit-col-1 grey-light">MeraEvents White Web Size</td>
					<td class="kit-col-2 grey-light">PNG</td>
					<td class="kit-col-3 grey-light">144x91 Pixels</td>
					<td class="kit-col-4 grey-light"><a href="http://www.meraevents.com/download/download.php?f=MeaEvents_Logo_White.png">Download</a></td>
				</tr>
				<tr>
					<td class="kit-col-1 grey-dark">MeraEvents White High Resolution</td>
					<td class="kit-col-2 grey-dark">PNG</td>
					<td class="kit-col-3 grey-dark">1320x720 Pixels</td>
					<td class="kit-col-4 grey-dark"><a href="http://www.meraevents.com/download/download.php?f=MeaEvents_Logo_High_Reslution_White.png">Download</a></td>
				</tr>
				<tr>
					<td class="kit-col-1 grey-light">MeraEvents EPS</td>
					<td class="kit-col-2 grey-light">EPS</td>
					<td class="kit-col-3 grey-light">Open File</td>
					<td class="kit-col-4 grey-light"><a href="http://www.meraevents.com/download/mera-events-logo-eps.eps">Download</a></td>
				</tr>
				<tr>
					<td class="kit-col-1 grey-dark">MeraEvents CDR</td>
					<td class="kit-col-2 grey-dark">CDR</td>
					<td class="kit-col-3 grey-dark">Open File</td>
					<td class="kit-col-4 grey-dark"><a href="http://www.meraevents.com/download/mera-events-logo-cdr.cdr">Download</a></td>
				</tr>
				<tr>
					<td class="kit-col-1 grey-light">MeraEvents Logo</td>
					<td class="kit-col-2 grey-light">PDF</td>
					<td class="kit-col-3 grey-light">&nbsp;</td>
					<td class="kit-col-4 grey-light"><a href="http://www.meraevents.com/download/download.php?f=mera-events-logo.pdf">Download</a></td>
				</tr>-->
				<tr>
					<td class="kit-col-1 grey-dark"><strong>MeraEvents Logos</strong></td>
					<td class="kit-col-2 grey-dark"><strong>Media Kit</strong></td>
					<td class="kit-col-3 grey-dark"><strong>All Formats</strong></td>
                                        <?php 
                              $path=$this->config->item('images_cloud_path'). 'mediakit.zip'; ?>
                              <td class="kit-col-4 grey-dark"><a href="<?php echo $path?>">Download</a></td>
				</tr>
				</tbody></table>
		</div>
		<!-- get coupons-->

		<!-- Eo get coupons -->

		<!--Eventa happening-->



	</div>
	<!-- /.wrap -->
</div>
<!-- /.page-container -->
<!-- on scroll code-->
<?php  $this->load->view("includes/elements/home_scroll_filter.php"); ?>
</div>
</div>

<!-- Modal -->
<div class="modal fade signup_popup" id="myModal" tabindex="-1"
	 role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-center">
		<div class="modal-content">
			<!-- <div class="awesome_container">
<h3 class="icon-bookmark_icon"></h3>
<h1>Awesome!</h1>
 <h2>Event saved successfully!</h2>
</div>-->

			<div class="popup_signup">
				<h1>Save this event</h1>
				<h2>Please log in to save the event</h2>
				<hr>
				</hr>
				<ul>
					<li><a href="javascript:void(0)" class="icon-fb_circle"></a></li>
					<li><a href="javascript:void(0)" class="icon-twt_circle"></a></li>
					<li><a href="javascript:void(0)" class="icon-google_circle"></a></li>
				</ul>
				<h3>- or -</h3>
				<form class="signup_form">
					<div class="form-group ">
						<input type="email" class="form-control userFields"
							   id="exampleInputEmail1" placeholder="Email">
					</div>
					<div class="form-group">
						<input type="password" class="form-control userFields"
							   id="exampleInputPassword1" placeholder="Password">
					</div>
					<div class="checkbox">
						<label class="rember"> <span> <input
								type="checkbox" name="sport[]" value="football">
							</span>Remember me
						</label> <label class="fwd_pass"><a href="#">Forgot password?</a></label>
					</div>
					<a   class="btn btn-default commonBtn" href="meafterlogin.html">LOG
						IN</a>

					<h1 id="closeModal"
						style="font-size: 14px; font-weight: normal; cursor: pointer">No
						Thanks</h1>
					<br>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- After Login popup -->
<div class="modal fade signup_popup" id="myModal2" tabindex="-1"
	 role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-center">
		<div class="modal-content">
			<div class="awesome_container">
				<h3 class="icon-icon-bookmark_icon"></h3>
				<h1>Awesome!</h1>
				<h2>Event saved successfully!</h2>

				<div style="width: 15%; margin: 0 auto">
					<button type="submit" id="okId"
							class="btn btn-primary borderGrey collapsed"
							style="padding: 10px 20px; margin-bottom: 20px">oK</button>
				</div>
			</div>
		</div>
	</div>
</div>
<script>

	$('.dropdown-inverse li > a').click(function(e){
		var selText = $(this).text();
		$(this).parents('.btn-group').find('.eventsClass').text(selText);

	});

	$('.cityList li > a').click(function(e){
		$('.filterdiv').slideUp();
		/* console.log("000000000",$(this).find('label').contents().get(0).nodeValue);
		 var cityName =$(this).find('span').closest('label').text();
		 console.log("-----",cityName); */
		var cityname=$(this).find('label').contents().get(0).nodeValue;
		$('.city').html(cityname).append('<span class="icon-downArrowH"></span>').prepend('<span class="icon_city hidden-lg hidden-md"></span>');

		$('.filterCity').show().contents().last()[0].textContent=cityname;
		$('.CloseFilter').click();
		/*if ($(window).width() <= 767){
		 $('#settingsBtn').trigger( "click" );
		 }*/
		//$("#locationContainer").removeClass('mobileFilter').hide();
	});
	$(function(){
		$('#datepicker').datepicker({
			onSelect : function(){
				var dateVal = $(this).val();
				$('.time').html(dateVal).append('<span class="icon-downArrowH"></span>').prepend('<span class="icon_date hidden-lg hidden-md"></span>');
				$('.filterDate').show().contents().last()[0].textContent=dateVal;
				$('.filterdiv').slideUp();
				$('.CloseFilter').click();
			//	$("#locationContainer").removeClass('mobileFilter').hide();
			}
		});
	});

	$(function(){
		$('#datepicker2').datepicker({
			onSelect : function(){
				var dateVal = $(this).val();
				$('.time').html(dateVal).append('<span class="icon-downArrowH"></span>');
				 $('.filterdiv').slideUp('slow');
				$('.filterDate').show().contents().last()[0].textContent=dateVal;
				//$("#locationContainer").removeClass('mobileFilter').hide();
			}
		});
	});
	
	$(function() {
		$('#returnToTop').click(function(){
			//$(this).scrollTop(200);
			$('html,body').animate({
				scrollTop : 0
			},1000);
			$("#locationContainer").hide();
		});
		$("#settingsBtn").click(function() {
			if($('.navbar-collapse').is(':visible')){
				$('.navbar-collapse').hide();
				$('.navbar-collapse').removeClass('collapse in');
			}
			//$("html, body").animate({scrollTop:0},500)
			if($("#locationContainer").is(':hidden')) {
				$("#locationContainer").slideDown('2500');
				$("#locationContainer").css('position' , 'fixed');
				$("#locationContainer").css('background' , '#f5f6f7');
				//$(window).off('scroll', onwindowscroll)

				// $('body').css({overflow: 'hidden'});
				//$(window).on("touchmove", false);

			} else {
				$("#locationContainer").hide();
				$("#locationContainer").css('position' , 'relative');
				$('body').css({overflow: 'auto'});
				//$("body").unbind("touchmove");
				//$(window).on('scroll', onwindowscroll)
			}

		});

		function onwindowscroll() {
			console.log('scrolled:'+$(document).scrollTop() );
			var top = $(document).scrollTop();
			if ($(window).width() > 767) {
				if (top >= 100) {
					$('.searchABC').hide();
					$("#locationContainer").show();
				} else{
					$('.searchABC').show();
				}
				if (top >= 450) {
					$("#locationContainer").hide();
					$('.filterdiv').hide();
					$('.searchABC').hide();
				} else {
					$("#locationContainer").show();
					$('.searchABC').show();
				}
			}
			if ($(window).width() <= 767) {
				$("#locationContainer").hide();
				if (top >= 500) {
					$('.navbar-collapse').hide();
					$('.navbar-collapse').removeClass('collapse in');
				}
			}
		}
		$(window).on('scroll', onwindowscroll)

	});
	document.querySelector( "#nav-toggle" )
			.addEventListener( "click", function(){
				this.classList.toggle( "active" );
			});

	document.querySelector( "#nav-toggle2" ).addEventListener( "click", function() {
		this.classList.toggle( "active" );

		if($('#locationContainer').is(':visible')){
			$("#locationContainer").hide();
		}
		$('.navbar-collapse').show();
	});
</script>
<script type="text/javascript">
	function customCheckbox(checkboxName){
		var checkBox = $('input[name="'+ checkboxName +'"]');
		$(checkBox).each(function(){
			$(this).wrap( "<span class='custom-checkbox'></span>" );
			if($(this).is(':checked')){
				$(this).parent().addClass("selected");
			}
		});
		$(checkBox).click(function(){
			$(this).parent().toggleClass("selected");
		});
	}
	$(document).ready(function (){
		customCheckbox("sport[]");

	})
	$('#saveEvent').click(function(e){
		e.preventDefault();
		$('#myModal').modal();
	});
	$('.successEvent').click(function(e){
		e.preventDefault();
		$('#myModal2').modal();
	});
	$('#closeModal').click(function(e){
		$('#myModal').modal('hide');
	});
	$('#okId').click(function(e){
		$('#myModal2').modal('hide');
	});
</script>
