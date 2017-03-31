jQuery(function($){
    "use strict";
	
	
	/*============
	1-Hero Header
	2-Navigation
	3-Video Banner
	4-Gallery
	5-Fraction Slider
	6-Parallax
	7-Carousel fredsel
	8-Owl Slider
	9-Vegas Slider
	10-Tweet
	11-JPlayer
	12- Contact Form
	==============*/
	
	
	
	var isStickyPlayer=$(".sticky_player").attr('data-sticky'),
		isStickyNav=$("#sticktop").attr('data-sticky'),
		naviheight=$("#sticktop").height(),
		playerHeight=$(".sticky_player").height(),
		navTopSpace=0,NavOffset=0;
		var $winHeight=$(window).height(),
		$winWidth=$(window).width();
	/*=======================================
	1-Hero Header
	=======================================*/
		
	$(window).on('resize', function(){
		$winHeight=$(window).height();
		$winWidth=$(window).width();
		
		$('.hero_section').css('height',$winHeight+'px');
		var $hero_height=$('.hero_section').height(), 
			$hero_content_height=$('.hero_content').height();
		
		if($hero_height<$hero_content_height ){$('.hero_section').css('height',$hero_content_height+'px');}
		//$('.hero_section').css('padding-top',($hero_height/2)-($hero_content_height/2)+'px');
		
	}).resize();
		
	//$(".list_scroll").mCustomScrollbar({advanced: {updateOnContentResize: true},});
		
	/*=======================================
	2-Navigation
	=======================================*/
	
	if(isStickyNav!="false"){NavOffset=naviheight + 10;}
	if(isStickyPlayer!="false"){NavOffset=playerHeight+10;}
	if(isStickyNav!="false" && isStickyPlayer!="false"){NavOffset=naviheight+playerHeight+10;}	
	$('body').attr('data-offset',NavOffset+10);
	
    $(".navbar-nav a[href^='#'],.ScrollTo,.btn-scroll").click(function (e) {
		e.preventDefault();
        $('html, body').stop().animate({scrollTop: $($.attr(this, 'href')).offset().top - NavOffset}, 1000,"swing");
    });
	
	
	if($winWidth>700){
	  if($(".sticky_player").attr('data-sticky')!="false"){navTopSpace=playerHeight;}
	  if(isStickyNav!="false"){
		  $("#sticktop").sticky({topSpacing:navTopSpace});}  
	  if($(".sticky_player").attr('data-sticky')!="false"){
		  $(".sticky_player").sticky({topSpacing: 0});
	  }
	  $('#sticktop').on('sticky-start', function() {
		  if($(".sticky_player").attr('data-sticky')!="false")
		  $('.rock_player').removeClass('pre_sticky');
	  });
	  $('#sticktop').on('sticky-end', function() {
		  if($(".sticky_player").attr('data-sticky')!="false")
		  $('.rock_player').addClass('pre_sticky');
	  });  
	}
	
	/*=======================================
	3-Video Banner
	=======================================*/
	 
	
	/*=======================================
	4-Gallery
	=======================================*/
	
	$('.sliderGallery').click(function(e){
		e.preventDefault();
		$('.gallayoutOption li').removeClass('active');
		$(this).parent('li').addClass('active');
		var $this=$(this);
		$('.gal_list li').each(function() {
			$(this).removeClass('trigger_slider').addClass('gallery-item');
        });
		$('.gal_list').addClass('owl-carousel owl-gallery');
		 $(".owl-gallery").owlCarousel({
			slideSpeed : 1000,
			pagination : false,
			singleItem:true,
			navigation : true,
		 });
		 $('.social_share').slideDown();
	});
	
	$('.gridGallery').on('click', function (e) {
		$('.gallayoutOption li').removeClass('active');
		$(this).parent('li').addClass('active');
		e.preventDefault();
		if($(".owl-gallery").length){
			$(".owl-gallery").data('owlCarousel').destroy();
			$('.gal_list li').each(function() {
				$(this).addClass('trigger_slider').removeClass('gallery-item');
			});
			$('.gal_list').removeClass('owl-carousel owl-gallery');
		}
	});
	
	/*=======================================
	5-Fraction Slider
	=======================================*/
	 
	
	
	/*=======================================
	6-Parallax
	=======================================*/
	
	  $.stellar({
		horizontalScrolling: false,
		verticalOffset: 0,
		responsive:true,
	  });

	/*=======================================
	7-Carousel fredsel
	=======================================*/
	 
	/*==========================================
	8-Owl Slider
	=======================================*/
	 
	 
	 /*============================
	9-Vegas Slider
	============================*/
	 
	
	reanimate();
    function reanimate() {
        $('.ScrollTo > i').animate({top:0}, 1000).animate({top: 20},1000,function(){setTimeout(reanimate, 100);});
    }
	
	/*========================================
	10-Tweet
	===========================================*/
	 
	
	/*==========================================
	11-JPlayer
	=======================================*/
	
	 
	
	function IsEmail(email) {
			var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			return regex.test(email);
		}
		
		if($("#contactform").length!=0){
			$("#contactform").submit(function (e) {
				e.preventDefault();
				var name = $("#name").val(),
				email = $("#email").val(),
				subject = $("#subject").val(),
				message = $("#message").val(),
				dataString = 'name=' + name + '&email=' + email+ '&subject=' + subject + '&message=' + message;
		
				if (name === '' || !IsEmail(email) || message === '') {
					$('#valid-issue').html('Please Provide Valid Information').slideDown();
				} else {
					$.ajax({
						type: "POST",
						url: "assets/php/submit.php",
						data: dataString,
						success: function () {
							$('#contactform').slideUp();
							$('#valid-issue').html('Your message has been sent,<BR> We will contact you back with in next 24 hours.').show();
						}
					});
				}
			});
		}
		
		/*==========================
		Ajax Expander
		==========================*/
		/* $('.triggerTrack').click(function(e){
			e.preventDefault();
			$('.trackLoading').show();
			console.log($('#tracksAjax').height());
			var $this = $(this),href = $this.attr('href'),key=$this.attr("data-number");
			if(!href==='#'){ return}
			$.ajax({
				url:  href ,
				dataType: 'html',
				success: function(data) {
					var targetPageContent = $('<div />').html(data).find('.pageContentArea #album'+key);
					$('#tracksAjax').html(targetPageContent).slideDown();
					$('.closeTrackAjax').show();
					$('.trackLoading').hide();
					$('html, body').stop().animate({scrollTop: $("#tracksAjaxWrapper").offset().top - NavOffset}, 1000,"swing");
				},
				error: function (request, status, error) {
					alert(request.responseText);
					$('.trackLoading').hide();
				}
				
			});
		 });
		 
		$('.closeTrackAjax').click(function(e){
			e.preventDefault();
			$('html, body').stop().animate({scrollTop: $($(this).attr('href')).offset().top - NavOffset}, 500,"swing");
			$(this).hide();
			$('#tracksAjax').slideUp();
		}); */
		
		
		
});

