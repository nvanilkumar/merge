<!--important-->
    <div class="page-container">
      <div class="wrap">
    <!-- Copy from this section-->
  <div class="container"> 
     
      <div style="height:670px"><iframe width="100%" height="100%" frameborder="0" src="<?php echo $iframeSrc; ?>"></iframe></div>
  </div>
    
      </div>
      <!-- /.wrap --> 
    </div>
    <!-- /.page-container --> 
    <!-- on scroll -->
    <?php  $this->load->view("includes/elements/home_scroll_filter.php"); ?>
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
