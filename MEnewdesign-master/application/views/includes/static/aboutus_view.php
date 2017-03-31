<!--important-->
    <div class="page-container">
      <div class="wrap">
    <!-- Copy from this section-->
  <div class="container about_us"> 
    <div class="about_desktop">
      <object type="image/svg+xml" 
     style="float:right" 
    data="<?php echo $this->config->item('images_static_path'); ?>about_desktop.svg">
      </object>
    </div>
    <div class="about_mobile">
      <object type="image/svg+xml" 
     style="width:90%"
    data="<?php echo $this->config->item('images_static_path'); ?>about_mobile.svg">
      </object>
    </div>
  </div>
    <!-- About section end-->
         <!-- EO blog--> 
      </div>
      <!-- /.wrap --> 
    </div>
    <!-- /.page-container --> 
    <!-- on scroll -->
    <?php  $this->load->view("includes/elements/home_scroll_filter.php"); ?>
  </div>
</div>


<!-- Modal -->
<div class="modal fade signup_popup" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
    <hr></hr>
        <ul>
            <li><a href="javascript:void(0)" class="icon-fb fb"></a></li>
            <li><a href="javascript:void(0)" class="icon-tweet tweet"></a></li>            
            <li><a href="javascript:void(0)" class="icon-google google"></a></li>
            </ul>
            <h3>- or -</h3>
            <form class="signup_form" >
            <div class="form-group ">
              <input type="email" class="form-control userFields" id="exampleInputEmail1" placeholder="Email">
            </div>
            <div class="form-group">
              <input type="password" class="form-control userFields" id="exampleInputPassword1" placeholder="Password">
            </div>
            <div class="checkbox">
              <label class="rember">
                <span><input type="checkbox" name="sport[]" value="football"></span>Remember me </label>
              <label class="fwd_pass"><a href="#">Forgot password?</a></label> </div>
            <button type="submit" class="btn btn-default commonBtn">LOG IN</button>
          </form>
    
    </div>
     
         
    
    </div>
  </div>
</div>


<script>
$('.dropdown-inverse li > a').click(function(e){
	var selText = $(this).text();
	$(this).parents('.btn-group').find('.eventsClass').text(selText);
	
});

  $(function() {

		$('#returnToTop').click(function(){
			//$(this).scrollTop(200);
			$('html,body').animate({
				scrollTop : 0
			},1000);
		//	$("#locationContainer").hide();
		});

		$("#settingsBtn").click(function() {
			if($('.navbar-collapse').is(':visible')){
				$('.navbar-collapse').hide();
				$('.navbar-collapse').removeClass('collapse in');
			}
			//$("html, body").animate({scrollTop:0},500)
			if($("#locationContainer").is(':hidden')) {
				$("#locationContainer").show();
				$("#locationContainer").css('position' , 'fixed');
				$("#locationContainer").css('background' , '#f5f6f7');
				//$(window).off('scroll', onwindowscroll)
				
				 //$('body').css({overflow: 'hidden'}); 
				//$(window).on("touchmove", false);
	
			} else {
				$("#locationContainer").hide();
				$("#locationContainer").css('position' , 'relative');
				 //$('body').css({overflow: 'auto'}); 
				//$("body").unbind("touchmove");
				//$(window).on('scroll', onwindowscroll)
			}
			
		});
		
		function onwindowscroll() {
			//console.log('scrolled:'+$(document).scrollTop() );
			var top = $(document).scrollTop();
			if(window.screen.width > 767){
				if(top >= 100){
					$('.searchABC').hide();
				}else{
					$('.searchABC').show();
				}
				if(top >= 350) {
					$("#locationContainer").hide('slow');
					$('.filterdiv').hide();
					$("#settingsBtn").show();
					
				} else {
					$("#settingsBtn").hide();
					$("#locationContainer").show();
				} 
			}
			if(window.screen.width <= 767){
				$("#locationContainer").hide();
				if(top >= 500){
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
	$('.icon-fave').click(function(e){
		e.preventDefault();
		$('#myModal').modal();
	});
</script>
